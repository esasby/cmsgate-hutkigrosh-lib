<?php

namespace esas\cmsgate\hutkigrosh\protocol;

use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\protocol\Amount;
use esas\cmsgate\protocol\RsType;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\Logger;
use Exception;
use Throwable;

/**
 * HootkiGrosh class
 */
class HutkigroshProtocol
{
    private static $cookies_file;
    private $base_url; // url api
    private $ch; // curl object
    public $cookies_dir;
    // api url
    const API_URL = 'https://www.hutkigrosh.by/API/v1/'; // рабочий
    const API_URL_TEST = 'https://trial.hgrosh.by/API/v1/'; // тестовый

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ConfigWrapperHutkigrosh
     */
    private $configWrapper;

    /**
     * @param ConfigWrapperHutkigrosh $configWrapper
     * @throws Exception
     */
    public function __construct($configWrapper)
    {
        $this->logger = Logger::getLogger(HutkigroshProtocol::class);
        $this->configWrapper = $configWrapper;
        if ($this->configWrapper->isSandbox()) {
            $this->base_url = self::API_URL_TEST;
            $this->logger->info("Test mode is on");
        } else {
            $this->base_url = self::API_URL;
        }
        if (!isset(self::$cookies_file)) {
            self::$cookies_file = 'cookies-' . time() . '.txt';
        }
        $dir = $this->configWrapper->getCookiePath();
        if (empty($dir)) {
            $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . "cookies";
        }
        $this->setCookiesDir($dir);
    }

    /**
     * Задать путь к папке, где будет находиться файл cookies
     *
     * @param string $dir
     * @throws Exception
     */
    public function setCookiesDir($dir)
    {
        $dir = rtrim($dir, '\\/');
        if (!is_dir($dir) && !mkdir($dir)) {
            throw new Exception("Can not create dir[" . $dir . "]");
        }
        $this->cookies_dir = $dir;
        $this->logger->debug("Cookies dir is set to: " . $this->cookies_dir);
    }

    /**
     * Аутентифицирует пользователя в системе
     *
     * @return HutkigroshLoginRs
     */
    public function apiLogIn(HutkigroshLoginRq $loginRq = null)
    {
        $resp = new HutkigroshLoginRs();
        try {
            if ($loginRq == null)
                $loginRq = new HutkigroshLoginRq($this->configWrapper->getHutkigroshLogin(), $this->configWrapper->getHutkigroshPassword());
            $this->logger->info("Logging in: host[" . $this->base_url . "],  username[" . $loginRq->getUsername() . "]");
            if (empty($loginRq->getUsername()) || empty($loginRq->getPassword())) {
                throw new Exception("Ошибка конфигурации! Не задан login или password", HutkigroshRs::ERROR_CONFIG);
            }
            // формируем xml
            $Credentials = new \SimpleXMLElement("<Credentials></Credentials>");
            $Credentials->addAttribute('xmlns', 'http://www.hutkigrosh.by/api');
            $Credentials->addChild('user', $loginRq->getUsername());
            $Credentials->addChild('pwd', $loginRq->getPassword());
            $xml = $Credentials->asXML();
            // запрос
            $res = $this->requestPost('Security/LogIn', $xml, RsType::_STRING);
            // проверим, верны ли логин/пароль
            if (!preg_match('/true/', $res)) {
                throw new Exception("Ошибка авторизации сервисом Hutkigrosh!", HutkigroshRs::ERROR_AUTH);
            }
        } catch (Exception $e) {
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        }
        return $resp;
    }

    /**
     * Завершает сессию
     * @return bool
     * @throws Exception
     */
    public function apiLogOut()
    {
        $this->logger->info("Logging out...");
        $res = $this->requestPost('Security/LogOut');
        // удалим файл с cookies
        $cookies_path = $this->cookies_dir . DIRECTORY_SEPARATOR . self::$cookies_file;
        if (is_file($cookies_path)) {
            @unlink($cookies_path);
        }
        return $res; //todo переделать в Rs
    }

    /**
     * Добавляет новый счет в систему
     *
     * @param HutkigroshBillNewRq $billNewRq
     * @return HutkigroshBillNewRs
     * @throws Exception
     */
    public function apiBillNew(HutkigroshBillNewRq $billNewRq)
    {
        $resp = new HutkigroshBillNewRs();
        $loggerMainString = "Order[" . $billNewRq->getInvId() . "]: ";
        try {// формируем xml
            $this->logger->debug($loggerMainString . "apiBillNew started");
            $Bill = new \SimpleXMLElement("<Bill></Bill>");
            $Bill->addAttribute('xmlns', 'http://www.hutkigrosh.by/api/invoicing');
            $Bill->addChild('eripId', $billNewRq->getEripId());
            $Bill->addChild('invId', $billNewRq->getInvId());
            $Bill->addChild('dueDt', date('c', strtotime('+' . $billNewRq->getDueInterval() . ' days'))); // +N день
            $Bill->addChild('addedDt', date('c'));
            $Bill->addChild('fullName', self::convert2utf8($billNewRq->getFullName()));
            $Bill->addChild('mobilePhone', $billNewRq->getMobilePhone());
            $Bill->addChild('notifyByMobilePhone', $billNewRq->isNotifyByMobilePhone() ? "true" : "false");
            if (!empty($billNewRq->getEmail())) {
                $Bill->addChild('email', $billNewRq->getEmail()); // опционально
                $Bill->addChild('notifyByEMail', $billNewRq->isNotifyByEMail() ? "true" : "false");
            }
            if (!empty($billNewRq->getFullAddress())) {
                $Bill->addChild('fullAddress', self::convert2utf8($billNewRq->getFullAddress())); // опционально
            }
            $Bill->addChild('amt', (float)$billNewRq->getAmount()->getValue());
            $Bill->addChild('curr', $billNewRq->getAmount()->getCurrency());
            $Bill->addChild('statusEnum', 'NotSet');
            // Список товаров/услуг
            if (!empty($billNewRq->getProducts())) {
                $products = $Bill->addChild('products');
                foreach ($billNewRq->getProducts() as $pr) {
                    $ProductInfo = $products->addChild('ProductInfo');
                    if (!empty($pr->getInvId())) {
                        $ProductInfo->addChild('invItemId', $pr->getInvId()); // опционально
                    }
                    $ProductInfo->addChild('desc', htmlentities(self::convert2utf8($pr->getName()), ENT_XML1));
                    $ProductInfo->addChild('count', $pr->getCount());
                    if (!empty($pr->getUnitPrice())) {
                        $ProductInfo->addChild('amt', $pr->getUnitPrice()); // опционально
                    }
                }
            }

            $xml = $Bill->asXML();
            // запрос
            $resArray = $this->requestPost('Invoicing/Bill', $xml, RsType::_ARRAY);
            if (!is_array($resArray) || !isset($resArray['status']) || !isset($resArray['billID'])) {
                throw new Exception("Wrong response!", HutkigroshRs::ERROR_RESP_FORMAT);
            }
            $resp->setResponseCode($resArray['status']);
            $resp->setBillId($resArray['billID']);
            $this->logger->debug($loggerMainString . "apiBillNew ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "apiBillNew exception", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "apiBillNew exception", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        }
        return $resp;
    }

    /**
     * Добавляет новый счет в систему AllfaClick
     *
     * @param HutkigroshAlfaclickRq $alfaclickRq
     * @return HutkigroshAlfaclickRs
     * @internal param array $data
     *
     */
    public function apiAlfaClick(HutkigroshAlfaclickRq $alfaclickRq)
    {
        $resp = new HutkigroshAlfaclickRs();
        $loggerMainString = "Bill[" . $alfaclickRq->getBillId() . "]: ";
        try {
            $this->logger->debug($loggerMainString . "apiAlfaClick started");
            // формируем xml
            $Bill = new \SimpleXMLElement("<AlfaClickParam></AlfaClickParam>");
            $Bill->addAttribute('xmlns', 'http://www.hutkigrosh.by/API/PaymentSystems');
            $Bill->addChild('billId', $alfaclickRq->getBillId());
            $Bill->addChild('phone', $alfaclickRq->getPhone());
            $xml = $Bill->asXML();
            // запрос
            $responseXML = $this->requestPost('Pay/AlfaClick', $xml, RsType::_XML); // 0 – если произошла ошибка, billId – если удалось выставить счет в AlfaClick
            if (intval($responseXML->__toString()) == '0') {
                throw new Exception("Ошибка выставления счета в Альфаклик", HutkigroshRs::ERROR_ALFACLICK_BILL_NOT_ADDED);
            }
            $this->logger->debug($loggerMainString . "apiAlfaClick ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "apiAlfaClick exception:", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "apiAlfaClick exception:", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        }
        return $resp;
    }

    /**
     * Получение формы виджета для оплаты картой
     *
     * @param HutkigroshWebPayRq $webPayRq
     * @return HutkigroshWebPayRs
     */

    public function apiWebPay(HutkigroshWebPayRq $webPayRq)
    {
        $resp = new HutkigroshWebPayRs();
        $loggerMainString = "Bill[" . $webPayRq->getBillId() . "]: ";
        try {// формируем xml
            $this->logger->debug($loggerMainString . "apiWebPay started");
            $Bill = new \SimpleXMLElement("<WebPayParam></WebPayParam>");
            $Bill->addAttribute('xmlns', 'http://www.hutkigrosh.by/API/PaymentSystems');
            $Bill->addChild('billId', $webPayRq->getBillId());
            $Bill->addChild('returnUrl', htmlspecialchars($webPayRq->getReturnUrl()));
            $Bill->addChild('cancelReturnUrl', htmlspecialchars($webPayRq->getCancelReturnUrl()));
            $Bill->addChild('submitValue', $webPayRq->getButtonLabel());
            $xml = $Bill->asXML();
            // запрос
            $resStr = $this->requestPost('Pay/WebPay', $xml, RsType::_STRING);
            $resXml = simplexml_load_string($resStr, null, LIBXML_NOCDATA);
            if (!isset($resXml->status)) {
                throw new Exception("Неверный формат ответа", HutkigroshRs::ERROR_RESP_FORMAT);
            }
            $resp->setResponseCode($resXml->status);
            $resp->setHtmlForm($resXml->form->__toString());
            $this->logger->debug($loggerMainString . "apiWebPay ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "apiWebPay exception: ", $e);
            $resp->setResponseCode(HutkigroshRs::ERROR_DEFAULT);
            $resp->setResponseMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "apiWebPay exception: ", $e);
            $resp->setResponseCode(HutkigroshRs::ERROR_DEFAULT);
            $resp->setResponseMessage($e->getMessage());
        }
        return $resp;
    }


    /**
     * Извлекает информацию о выставленном счете
     *
     * @param HutkigroshBillInfoRq $billInfoRq
     *
     * @return HutkigroshBillInfoRs
     */
    public function apiBillInfo(HutkigroshBillInfoRq $billInfoRq)
    {
        $resp = new HutkigroshBillInfoRs();
        $loggerMainString = "Bill[" . $billInfoRq->getBillId() . "]: ";
        try {// запрос
            $this->logger->debug($loggerMainString . "apiBillInfo started");
            $resArray = $this->requestGet('Invoicing/Bill(' . $billInfoRq->getBillId() . ')', '', RsType::_ARRAY);
            if (empty($resArray)) {
                throw new Exception("Wrong message format", HutkigroshRs::ERROR_RESP_FORMAT);
            }
            $resp->setResponseCode($resArray['status']);
            $resp->setBillId($resArray["bill"]["billID"]);
            $resp->setInvId($resArray["bill"]["invId"]);
            $resp->setEripId($resArray["bill"]["eripId"]);
            $resp->setFullName($resArray["bill"]["fullName"]);
            $resp->setFullAddress($resArray["bill"]["fullAddress"]);
            $resp->setAmount(new Amount($resArray["bill"]["amt"], $resArray["bill"]["curr"]));
            $resp->setEmail($resArray["bill"]["email"]);
            $resp->setMobilePhone($resArray["bill"]["mobilePhone"]);
            $resp->setStatus($resArray["bill"]["statusEnum"]);
            //todo переложить продукты
            $this->logger->debug($loggerMainString . "apiBillInfo ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "apiBillInfo exception.", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "apiBillInfo exception.", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        }
        return $resp;
    }

    /**
     * Подключение GET
     *
     * @param string $path
     * @param string $data
     * @param int $rsType
     * @internal param RS_TYPE $rqType
     *
     * @return mixed
     * @throws Exception
     */
    private function requestGet($path, $data = '', $rsType = RsType::_ARRAY)
    {
        return $this->connect($path, $data, 'GET', $rsType);
    }

    /**
     * Подключение POST
     *
     * @param string $path
     * @param string $data
     * @param int $rsType
     * @internal param RS_TYPE $rqType
     * @return bool
     * @throws Exception
     */
    private function requestPost($path, $data = '', $rsType = RsType::_ARRAY)
    {
        return $this->connect($path, $data, 'POST', $rsType);
    }

    /**
     * Подключение DELETE
     *
     * @param string $path
     * @param string $data
     * @param int $rsType
     * @internal param RS_TYPE $rqType
     *
     * @return mixed
     * @throws Exception
     */
    private function requestDelete($path, $data = '', $rsType = RsType::_ARRAY)
    {
        return $this->connect($path, $data, 'DELETE', $rsType);
    }

    /**
     * Подключение GET, POST или DELETE
     *
     * @param string $path
     * @param string $data Сформированный для отправки XML
     * @param string $request
     * @param $rsType
     *
     * @return mixed
     * @throws Exception
     */
    private function connect($path, $data = '', $request = 'GET', $rsType)
    {
        $headers = array('Content-Type: application/xml', 'Content-Length: ' . strlen($data));

        $cookies_path = $this->cookies_dir . DIRECTORY_SEPARATOR . self::$cookies_file;
        // если файла еще нет, то создадим его при залогинивании и будем затем использовать при дальнейших запросах
        if (!is_file($cookies_path) && !is_writable($this->cookies_dir)) {
            throw new Exception('Cookie file[' . $cookies_path . '] is not writable! Check permissions for directory[' . $this->cookies_dir . ']');
        }

        try {
            $this->ch = curl_init();
            $url = $this->base_url . $path;
            curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookies_path);
            curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookies_path);
            curl_setopt($this->ch, CURLOPT_URL, $url);
            curl_setopt($this->ch, CURLOPT_HEADER, false); // включение заголовков в выводе
            curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($this->ch, CURLOPT_VERBOSE, true); // вывод доп. информации в STDERR
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false); // не проверять сертификат узла сети
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false); // проверка существования общего имени в сертификате SSL
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true); // возврат результата вместо вывода на экран
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers); // Массив устанавливаемых HTTP-заголовков
            if ($request == 'POST') {
                curl_setopt($this->ch, CURLOPT_POST, true);
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
            }
            if ($request == 'DELETE') {
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }
            // для безопасности прячем пароли из лога
            $this->logger->info('Sending ' . $request . ' request[' . preg_replace('/(<pwd>).*(<\/pwd>)/', '$1********$2', $data) . "] to url[" . $url . "]");
            $response = curl_exec($this->ch);
            $this->logger->info('Got response[' . $response . "]");
            if (curl_errno($this->ch)) {
                throw new Exception(curl_error($this->ch), curl_errno($this->ch));
            }
        } finally {
            curl_close($this->ch);
        }
        switch ($rsType) {
            case RsType::_STRING:
                return $response;
            case RsType::_XML:
                return simplexml_load_string($response);
            case RsType::_ARRAY:
                return $this->responseToArray($response);
            default:
                throw new Exception("Wrong rsType.");
        }

    }

    /**
     * Преобразуем XML в массив
     *
     * @return mixed
     */
    private function responseToArray($response)
    {
        $response = trim($response);
        $array = array();
        // проверим, что это xml
        if (preg_match('/^<(.*)>$/', $response)) {
            $xml = simplexml_load_string($response);
            $array = json_decode(json_encode($xml), true);
        }
        return $array;
    }

    /**
     * Принудительная конвертация кодировки в utf8
     * @param $string
     * @return string
     */
    public static function convert2utf8($string) {
        return mb_convert_encoding($string, "utf-8", Registry::getRegistry()->getCmsConnector()->getCurrentEncoding());
    }

}