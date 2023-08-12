<?php
Class Cashfree {
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
        if (route(4) == 'pay') {
            json($this->pay());
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

    public function pay(){
        global $db,$config,$_LIBS;
        if (!empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['price']) && is_numeric($_POST['price'])) {
            $cashfree_payment = $config->cashfree_payment;
            $cashfree_mode = $config->cashfree_mode;
            $cashfree_client_key = $config->cashfree_client_key;
            $cashfree_secret_key = $config->cashfree_secret_key;
        	$name       = Secure($_POST['name']);
            $email      = Secure($_POST['email']);
            $phone      = Secure($_POST['phone']);


            $order_id   = uniqid();
            $product    = 'Top up credits';
            $realprice  = $sum   = (int)Secure($_POST[ 'price' ]);
            $price   = (int)Secure($_POST[ 'price' ]) * 100;
            $amount     = 0;
            $membershipType     = 0;
            $currency   = strtolower($config->cashfree_currency);

            if ($realprice == $config->bag_of_credits_price) {
                $amount = $config->bag_of_credits_amount;
            } else if ($realprice == $config->box_of_credits_price) {
                $amount = $config->box_of_credits_amount;
            } else if ($realprice == $config->chest_of_credits_price) {
                $amount = $config->chest_of_credits_amount;
            }

            $payload = [
                'userid'            => Auth()->id,
                'description'       => $product,
                'realprice'         => $realprice,
                'price'             => $price,
                'amount'            => $amount,
                'membershipType'    => $membershipType,
                'currency'          => $config->cashfree_currency
            ];
            if (!empty($config->currency_array) && in_array($config->cashfree_currency, $config->currency_array) && $config->cashfree_currency != $config->currency && !empty($config->exchange) && !empty($config->exchange[$config->cashfree_currency])) {
                $sum = (($sum * $config->exchange[$config->cashfree_currency]));
                $sum = round($sum, 2);
            }
    
            $hash = base64_encode(serialize($payload));
            $success_url = SeoUri('aj/cashfree/success?hash='.$hash);
            $postData = array( 
                "appId"             => $cashfree_client_key, 
                "orderId"           => "order".$order_id, 
                "orderAmount"       => ($sum), 
                "orderCurrency"     => $config->cashfree_currency, 
                "orderNote"         => "", 
                "customerName"      => $name, 
                "customerPhone"     => $phone, 
                "customerEmail"     => $email,
                "returnUrl"         => $success_url, 
                "notifyUrl"         => $success_url,
            );
            // get secret key from your config
            ksort($postData);
            $signatureData = "";
            foreach ($postData as $key => $value){
                $signatureData .= $key.$value;
            }
            $signature = hash_hmac('sha256', $signatureData, $cashfree_secret_key,true);
            $signature = base64_encode($signature);
            $cashfree_link = 'https://test.cashfree.com/billpay/checkout/post/submit';
            if ($cashfree_mode == 'live') {
                $cashfree_link = 'https://www.cashfree.com/checkout/post/submit';
            }

            $form = '<form id="redirectForm" method="post" action="'.$cashfree_link.'"><input type="hidden" name="appId" value="'.$cashfree_client_key.'"/><input type="hidden" name="orderId" value="order'.$order_id.'"/><input type="hidden" name="orderAmount" value="'.$price.'"/><input type="hidden" name="orderCurrency" value="INR"/><input type="hidden" name="orderNote" value=""/><input type="hidden" name="customerName" value="'.$name.'"/><input type="hidden" name="customerEmail" value="'.$email.'"/><input type="hidden" name="customerPhone" value="'.$phone.'"/><input type="hidden" name="returnUrl" value="'.$success_url.'"/><input type="hidden" name="notifyUrl" value="'.$success_url.'"/><input type="hidden" name="signature" value="'.$signature.'"/></form>';
            return json(array(
                'html' => $form,
                'appId' => $cashfree_client_key,
                'orderId' => 'order'.$order_id,
                'orderAmount' => $price,
                'orderCurrency' => 'INR',
                'orderNote' => '',
                'customerName' => $name,
                'customerEmail' => $email,
                'customerPhone' => $phone,
                'returnUrl' => $success_url,
                'notifyUrl' => $success_url,
                'signature' => $signature,
                'code' => 200
            ), 200);
        }
        else{
        	return json(array(
                'message' => __('Function not found'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '11',
                    'error_text' => 'name , phone , email , price can not be empty'
                )
            ), 400);
        }
    }
    public function success(){
        global $db, $config;
        $cashfree_payment = $config->cashfree_payment;
        $cashfree_mode = $config->cashfree_mode;
        $cashfree_client_key = $config->cashfree_client_key;
        $cashfree_secret_key = $config->cashfree_secret_key;
        if (empty($_POST['txStatus']) || $_POST['txStatus'] != 'SUCCESS') {
            return json(array(
                'message' => 'txStatus not SUCCESS',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '12',
                    'error_text' => 'txStatus not SUCCESS'
                )
            ), 400);
        }
        if (empty($_POST['hash'])) {
            return json(array(
                'message' => 'No hash',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '13',
                    'error_text' => 'No hash'
                )
            ), 400);
        }
        $data               = unserialize(base64_decode(Secure($_POST[ 'hash' ])));
        $userid             = $data['userid'];
        $description        = $data['description'];
        $realprice          = $data['realprice'];
        $price              = $data['price'];
        $amount             = $data['amount'];
        $membershipType     = $data['membershipType'];
        $currency           = $data['currency'];
        $user               = $db->objectBuilder()->where('id', $userid)->getOne('users', array('*'));

        $orderId            = Secure($_POST["orderId"]);
        $orderAmount        = Secure($_POST["orderAmount"]);
        $referenceId        = Secure($_POST["referenceId"]);
        $txStatus           = Secure($_POST["txStatus"]);
        $paymentMode        = Secure($_POST["paymentMode"]);
        $txMsg              = Secure($_POST["txMsg"]);
        $txTime             = Secure($_POST["txTime"]);
        $signature          = Secure($_POST["signature"]);
        $_data              = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
        $hash_hmac          = hash_hmac('sha256', $_data, $cashfree_secret_key, true) ;
        $computedSignature  = base64_encode($hash_hmac);

        if ($signature == $computedSignature) {
            $newbalance = $user->balance + $amount;
            $updated    = $db->where('id', $user->id)->update('users', array('balance' => $newbalance));
            if ($updated) {
                RegisterAffRevenue($user->id,$price / 100);
                $db->insert('payments', array(
                    'user_id' => $user->id,
                    'amount' => $price / 100,
                    'type' => 'CREDITS',
                    'pro_plan' => '0',
                    'credit_amount' => $amount,
                    'via' => 'Cashfree'
                ));
                $_SESSION[ 'userEdited' ] = true;
                $response[ 'credit_amount' ]  = (int) $newbalance;
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
        } else {
            return json(array(
                'message' => 'something went wrong',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'something went wrong'
                )
            ), 400);
        }
    }
}