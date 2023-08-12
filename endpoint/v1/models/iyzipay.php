<?php
Class Iyzipay {
    public function __construct() {
        if (empty(route(4))) {
            return json(array(
                'message' => __('Function not found'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '1',
                    'error_text' => __('Function not found')
                )
            ), 400);
        }
        if (route(4) == 'createsession') {
            json($this->createsession());
        }
        if (route(4) == 'success') {
            json($this->success());
        }
        else{
            return json(array(
                'message' => __('Function not found'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '10',
                    'error_text' => __('Function not found')
                )
            ), 400);
        }
    }
    public function createsession(){
        global $db,$config,$_LIBS;
        $iyzipay_payment = $config->iyzipay_payment;
        $iyzipay_mode = $config->iyzipay_mode;
        $iyzipay_key = $config->iyzipay_key;
        $iyzipay_buyer_id = $config->iyzipay_buyer_id;
        $iyzipay_secret_key = $config->iyzipay_secret_key;
        $iyzipay_buyer_name = $config->iyzipay_buyer_name;
        $iyzipay_buyer_surname = $config->iyzipay_buyer_surname;
        $iyzipay_buyer_gsm_number = $config->iyzipay_buyer_gsm_number;
        $iyzipay_buyer_email = $config->iyzipay_buyer_email;
        $iyzipay_identity_number = $config->iyzipay_identity_number;
        $iyzipay_city = $config->iyzipay_city;
        $iyzipay_zip = $config->iyzipay_zip;
        require_once($_LIBS . "iyzipay/samples/config.php");
        if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
            $response    = array();
            $product     = 'top up credits';
            $realprice   = (int)Secure($_POST[ 'price' ]);
            $price       = (int)Secure($_POST[ 'price' ]) * 100;
            $amount      = 0;
            $membershipType      = 0;
            $currency    = $config->iyzipay_currency;
            if ($realprice == $config->bag_of_credits_price) {
                $amount = $config->bag_of_credits_amount;
            } else if ($realprice == $config->box_of_credits_price) {
                $amount = $config->box_of_credits_amount;
            } else if ($realprice == $config->chest_of_credits_price) {
                $amount = $config->chest_of_credits_amount;
            }
            if (!empty($config->currency_array) && in_array($config->iyzipay_currency, $config->currency_array) && $config->iyzipay_currency != $config->currency && !empty($config->exchange) && !empty($config->exchange[$config->iyzipay_currency])) {
                $realprice = (int)(($realprice * $config->exchange[$config->iyzipay_currency]));
            }
            $payload = [
                'userid'            => Auth()->id,
                'description'       => $product,
                'realprice'         => $realprice,
                'price'             => $price,
                'amount'            => $amount,
                'membershipType'    => $membershipType,
                'currency'          => $currency
            ];

            $hash = base64_encode(serialize($payload));
            $callback_url = SeoUri('aj/iyzipay/success?hash='.$hash);
        
            $request->setPrice($realprice);
            $request->setPaidPrice($realprice);
            $request->setCallbackUrl($callback_url);
            
            $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId("BI".rand(11111111,99999999));
            $firstBasketItem->setName($product);
            $firstBasketItem->setCategory1($product);
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice($realprice);
            $basketItems[0] = $firstBasketItem;
            $request->setBasketItems($basketItems);
            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, IyzipayConfig::options());
            $content = $checkoutFormInitialize->getCheckoutFormContent();
            if (!empty($content)) {
                $db->where('id',Auth()->id)->update('users',array('conversation_id' => $ConversationId));
                return json(array(
                    'html' => $content,
                    'code' => 200,
                    'hash' => $hash
                ), 200);
            }
            else{
                return json(array(
                    'message' => 'unknown error',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '14',
                        'error_text' => 'unknown error'
                    )
                ), 400);
            }

        }
        else{
            return json(array(
                'message' => 'no price passed',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '13',
                    'error_text' => 'no price passed'
                )
            ), 400);
        }
    }
    public function success()
    {
        global $db,$config,$_LIBS;
        $iyzipay_payment = $config->iyzipay_payment;

        
        if (empty($_POST[ 'hash' ]) || empty($_POST['token']) || empty(Auth()->conversation_id)) {
            return json(array(
                'message' => 'hash , token , conversation_id can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'hash , token , conversation_id can not be empty'
                )
            ), 400);
        }
        require_once($_LIBS . "iyzipay/samples/config.php");

        $data           = unserialize(base64_decode(Secure($_POST[ 'hash' ])));
        $description    = $data['description'];
        $realprice      = $data['realprice'];
        $price          = $data['price'];
        $amount         = $data['amount'];
        $payType        = $data['payType'];
        $membershipType = $data['membershipType'];
        $currency       = $data['currency'];
        $user           = $db->objectBuilder()->where('id', Auth()->id)->getOne('users', array('balance'));

        # create request class
        $_request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $_request->setLocale(\Iyzipay\Model\Locale::TR);
        $_request->setConversationId(Auth()->conversation_id);
        $_request->setToken(Secure($_POST['token']));

        # make request
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($_request, IyzipayConfig::options());

        if ($checkoutForm->getPaymentStatus() == 'SUCCESS') {
            $newbalance = $user->balance + $amount;
            $updated    = $db->where('id', Auth()->id)->update('users', array('balance' => $newbalance));
            if ($updated) {
                RegisterAffRevenue(Auth()->id,$price / 100);
                $db->insert('payments', array(
                    'user_id' => Auth()->id,
                    'amount' => $price / 100,
                    'type' => 'CREDITS',
                    'pro_plan' => '0',
                    'credit_amount' => $amount,
                    'via' => 'iyzipay'
                ));
                $_SESSION[ 'userEdited' ] = true;
                return json(array(
                    'message' => 'SUCCESS',
                    'code' => 200
                ), 200);
            } else {
                return json(array(
                    'message' => 'Error While update balance after charging',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '15',
                        'error_text' => 'Error While update balance after charging'
                    )
                ), 400);
            }
        }
        else{
            return json(array(
                'message' => 'Error While update balance after charging',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '15',
                    'error_text' => 'Error While update balance after charging'
                )
            ), 400);
        }
    }
}