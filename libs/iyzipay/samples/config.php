<?php
global $config;
require_once(dirname(__DIR__).'/IyzipayBootstrap.php');

IyzipayBootstrap::init();

class IyzipayConfig
{
    public static function options()
    {
    	global $config;
        $url = 'https://sandbox-api.iyzipay.com';
        if ($config->iyzipay_mode == '0') {
            $url = 'https://merchant.iyzipay.com';
        }

        $options = new \Iyzipay\Options();
        $options->setApiKey($config->iyzipay_key);
        $options->setSecretKey($config->iyzipay_secret_key);
        $options->setBaseUrl($url);

        return $options;
    }
}
$ConversationId = rand(11111111,99999999);
$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId($ConversationId);
$request->setCurrency(\Iyzipay\Model\Currency::TL);
$request->setBasketId("B".rand(11111111,99999999));
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
$request->setEnabledInstallments(array(2, 3, 6, 9));


$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId($config->iyzipay_buyer_id);
$buyer->setName($config->iyzipay_buyer_name);
$buyer->setSurname($config->iyzipay_buyer_surname);
$buyer->setGsmNumber($config->iyzipay_buyer_gsm_number);
$buyer->setEmail($config->iyzipay_buyer_email);
$buyer->setIdentityNumber($config->iyzipay_identity_number);
$buyer->setRegistrationAddress($config->iyzipay_address);
$buyer->setCity($config->iyzipay_city);
$buyer->setCountry($config->iyzipay_country);
$buyer->setZipCode($config->iyzipay_zip);
$request->setBuyer($buyer);


$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName($config->iyzipay_buyer_name.' '.$config->iyzipay_buyer_surname);
$shippingAddress->setCity($config->iyzipay_city);
$shippingAddress->setCountry($config->iyzipay_country);
$shippingAddress->setAddress($config->iyzipay_address);
$shippingAddress->setZipCode($config->iyzipay_zip);
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName($config->iyzipay_buyer_name.' '.$config->iyzipay_buyer_surname);
$billingAddress->setCity($config->iyzipay_city);
$billingAddress->setCountry($config->iyzipay_country);
$billingAddress->setAddress($config->iyzipay_address);
$billingAddress->setZipCode($config->iyzipay_zip);
$request->setBillingAddress($billingAddress);