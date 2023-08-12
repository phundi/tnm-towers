<?php
Class Cashfree extends Aj {
    public function createsession(){
        global $db, $config;
        $cashfree_payment = $config->cashfree_payment;
        $cashfree_mode = $config->cashfree_mode;
        $cashfree_client_key = $config->cashfree_client_key;
        $cashfree_secret_key = $config->cashfree_secret_key;
        if($cashfree_payment !== 'yes' || self::ActiveUser() == NULL){
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_POST[ 'description' ])) {
            return array(
                'status' => 400,
                'message' => __('No description')
            );
        }
        if (empty($_POST[ 'payType' ])) {
            return array(
                'status' => 400,
                'message' => __('No payType')
            );
        }
        if (!empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $name       = Secure($_POST['name']);
            $email      = Secure($_POST['email']);
            $phone      = Secure($_POST['phone']);


            $order_id   = uniqid();
            $product    = Secure($_POST[ 'description' ]);
            $realprice  = $sum   = (int)Secure($_POST[ 'price' ]);
            $price   = (int)Secure($_POST[ 'price' ]) * 100;
            $amount     = 0;
            $membershipType     = 0;
            $currency   = strtolower(self::Config()->cashfree_currency);
            $payType    = Secure($_POST[ 'payType' ]);
    
            if ($payType == 'credits') {
                if ($realprice == self::Config()->bag_of_credits_price) {
                    $amount = self::Config()->bag_of_credits_amount;
                } else if ($realprice == self::Config()->box_of_credits_price) {
                    $amount = self::Config()->box_of_credits_amount;
                } else if ($realprice == self::Config()->chest_of_credits_price) {
                    $amount = self::Config()->chest_of_credits_amount;
                }
            } else if ($payType == 'membership') {
                if ($realprice == self::Config()->weekly_pro_plan) {
                    $membershipType = 1;
                } else if ($realprice == self::Config()->monthly_pro_plan) {
                    $membershipType = 2;
                } else if ($realprice == self::Config()->yearly_pro_plan) {
                    $membershipType = 3;
                } else if ($realprice == self::Config()->lifetime_pro_plan) {
                    $membershipType = 4;
                }
                $amount = $price;
            } else if ($payType == 'unlock_private_photo') {
                if ((int)$realprice == (int)self::Config()->lock_private_photo_fee) {
                    $amount = (int)self::Config()->lock_private_photo_fee;
                }
            } else if ($payType == 'lock_pro_video'){
                $amount = (int)self::Config()->lock_pro_video_fee;
            }
    
            $payload = [
                'userid'            => self::ActiveUser()->id,
                'description'       => $product,
                'realprice'         => $realprice,
                'price'             => $price,
                'amount'            => $amount,
                'payType'           => $payType,
                'membershipType'    => $membershipType,
                'currency'          => self::Config()->cashfree_currency
            ];
            if (!empty(self::Config()->currency_array) && in_array(self::Config()->cashfree_currency, self::Config()->currency_array) && self::Config()->cashfree_currency != self::Config()->currency && !empty(self::Config()->exchange) && !empty(self::Config()->exchange[self::Config()->cashfree_currency])) {
                $sum = (($sum * self::Config()->exchange[self::Config()->cashfree_currency]));
                $sum = round($sum, 2);
            }
    
            $hash = base64_encode(serialize($payload));
            $success_url = SeoUri('aj/cashfree/success?hash='.$hash);
            $postData = array( 
                "appId"             => $cashfree_client_key, 
                "orderId"           => "order".$order_id, 
                "orderAmount"       => ($sum), 
                "orderCurrency"     => self::Config()->cashfree_currency, 
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
            $data = array(
                'status' => 200,
                'html' => $form
            );
            return $data;
        }else{
            return array(
                'status' => 400,
                'message' => __('Forbidden')
            );
        }
    }

    public function success(){
        global $db, $config;
        $cashfree_payment = $config->cashfree_payment;
        $cashfree_mode = $config->cashfree_mode;
        $cashfree_client_key = $config->cashfree_client_key;
        $cashfree_secret_key = $config->cashfree_secret_key;
        if (empty($_POST['txStatus']) || $_POST['txStatus'] != 'SUCCESS') {
            header('Location: ' . SeoUri(''));
            exit();
        }
        if ($cashfree_payment !== 'yes') {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_GET[ 'hash' ])) {
            return array(
                'status' => 400,
                'message' => __('No hash')
            );
        }
        $data               = unserialize(base64_decode(Secure($_GET[ 'hash' ])));
        $userid             = $data['userid'];
        $description        = $data['description'];
        $realprice          = $data['realprice'];
        $price              = $data['price'];
        $amount             = $data['amount'];
        $payType            = $data['payType'];
        $membershipType     = $data['membershipType'];
        $currency           = $data['currency'];
        $user               = $db->objectBuilder()->where('id', $userid)->getOne('users', array('*'));

        $orderId            = Secure($_POST["orderId"]);
        //$amount             = Secure($_GET["amount"]);
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

            if ($payType == 'credits') {
                //done
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
                    $url = $config->uri . '/ProSuccess';
                    if (!empty($_COOKIE['redirect_page'])) {
                        $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                        $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                    }
                    header('Location: ' . $url);
                } else {
                    exit(__('Error While update balance after charging'));
                }
            } else if ($payType == 'membership') {
                //done
                $protime                = time();
                $is_pro                 = "1";
                $pro_type               = $membershipType;
                $updated                = $db->where('id', $user->id)->update('users', array(
                    'pro_time' => $protime,
                    'is_pro' => $is_pro,
                    'pro_type' => $pro_type
                ));
                if ($updated) {
                    RegisterAffRevenue($user->id,$price / 100);
                    $db->insert('payments', array(
                        'user_id' => $user->id,
                        'amount' => $price / 100,
                        'type' => 'PRO',
                        'pro_plan' => $membershipType,
                        'credit_amount' => '0',
                        'via' => 'Cashfree'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    SuperCache::cache('pro_users')->destroy();
                } else {
                    exit(__('Error While make you pro'));
                }
                header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=pro');
            } else if ($payType == 'unlock_private_photo') {
                //done
                $updated    = $db->where('id', $user->id)->update('users', array('lock_private_photo' => 0));
                if ($updated) {
                    $db->insert('payments', array(
                        'user_id' => $user->id,
                        'amount' => $price /100,
                        'type' => 'unlock_private_photo',
                        'pro_plan' => '0',
                        'credit_amount' => '0',
                        'via' => 'Cashfree'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=unlock');
                    exit();
                } else {
                    exit(__('Error While update Unlock private photo charging'));
                }
            } else if ($payType == 'lock_pro_video') {
                //done
                $updated    = $db->where('id', $user->id)->update('users', array('lock_pro_video' => 0));
                if ($updated) {
                    $db->insert('payments', array(
                        'user_id' => $user->id,
                        'amount' => $price /100,
                        'type' => 'lock_pro_video',
                        'pro_plan' => '0',
                        'credit_amount' => '0',
                        'via' => 'Cashfree'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=unlock');
                    exit();
                } else {
                    exit(__('Error While update Unlock private photo charging'));
                }
            }

        } else {
            header('Location: ' . SeoUri(''));
        }
        exit();
    }
}