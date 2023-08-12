<?php
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
Class Authorize extends Aj {
    public function pay(){
        global $db,$config,$_LIBS;
        $types = array(
            'credit',
            'go_pro',
            'unlock_private_photo',
            'lock_pro_video'
        );
        $response = array();
        $response['status'] = 400;

        if (!empty($_POST['type']) && in_array($_POST['type'], $types) && !empty($_POST['card_number']) && !empty($_POST['card_month']) && !empty($_POST['card_year']) && !empty($_POST['card_cvc']) && !empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
            $price = intval($_POST['price']);
            $amount = 0;
            $user = Auth();
            if ($_POST['type'] == 'go_pro') {
                if ($price == self::Config()->weekly_pro_plan) {
                    $membershipType = 1;
                } else if ($price == self::Config()->monthly_pro_plan) {
                    $membershipType = 2;
                } else if ($price == self::Config()->yearly_pro_plan) {
                    $membershipType = 3;
                } else if ($price == self::Config()->lifetime_pro_plan) {
                    $membershipType = 4;
                }
            }
            elseif ($_POST['type'] == 'credit') {
                if ($price == self::Config()->bag_of_credits_price) {
                    $amount = self::Config()->bag_of_credits_amount;
                } else if ($price == self::Config()->box_of_credits_price) {
                    $amount = self::Config()->box_of_credits_amount;
                } else if ($price == self::Config()->chest_of_credits_price) {
                    $amount = self::Config()->chest_of_credits_amount;
                }
            }
            elseif ($_POST['type'] == 'unlock_private_photo') {
                if ((int)$price == (int)self::Config()->lock_private_photo_fee) {
                    $amount = (int)self::Config()->lock_private_photo_fee;
                }
            }
            elseif ($_POST['type'] == 'lock_pro_video') {
                if ((int)$price == (int)self::Config()->lock_pro_video_fee) {
                    $amount = (int)self::Config()->lock_pro_video_fee;
                }
            }
            require_once $_LIBS . 'authorize/vendor/autoload.php';
            
            $APILoginId = $config->authorize_login_id;
            $APIKey = $config->authorize_transaction_key;
            $refId = 'ref' . time();
            define("AUTHORIZE_MODE", $config->authorize_test_mode);
            
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($APILoginId);
            $merchantAuthentication->setTransactionKey($APIKey);

            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($_POST['card_number']);
            $creditCard->setExpirationDate($_POST['card_year'] . "-" . $_POST['card_month']);
            $creditCard->setCardCode($_POST['card_cvc']);

            $paymentType = new AnetAPI\PaymentType();
            $paymentType->setCreditCard($creditCard);

            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($price);
            $transactionRequestType->setPayment($paymentType);

            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            if ($config->authorize_test_mode == 'SANDBOX') {
                $Aresponse = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            }
            else{
                $Aresponse = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
            }
            // print_r($Aresponse->getMessages());
            // print_r($Aresponse->getMessages()->getResultCode());
            
            if ($Aresponse != null) {
                if ($Aresponse->getMessages()->getResultCode() == 'Ok') {
                    $trans = $Aresponse->getTransactionResponse();
                    if ($trans != null && $trans->getMessages() != null) {
                        if ($_POST['type'] == 'credit') {
                            //done
                            $newbalance = $user->balance + $amount;
                            $updated    = $db->where('id', $user->id)->update('users', array('balance' => $newbalance));
                            if ($updated) {
                                RegisterAffRevenue($user->id,$price);
                                $db->insert('payments', array(
                                    'user_id' => $user->id,
                                    'amount' => $price,
                                    'type' => 'CREDITS',
                                    'pro_plan' => '0',
                                    'credit_amount' => $amount,
                                    'via' => 'Authorize'
                                ));
                                $url = $config->uri . '/ProSuccess';
                                if (!empty($_COOKIE['redirect_page'])) {
                                    $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                                    $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                                }
                                $_SESSION[ 'userEdited' ] = true;
                                $response[ 'credit_amount' ]  = (int) $newbalance;
                                $response[ 'url' ] = $url;
                                $response['status'] = 200;
                            } else {
                                $response['message'] = __('Error While update balance after charging');
                            }
                        } else if ($_POST['type'] == 'go_pro') {
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
                                RegisterAffRevenue($user->id,$price);
                                $db->insert('payments', array(
                                    'user_id' => $user->id,
                                    'amount' => $price,
                                    'type' => 'PRO',
                                    'pro_plan' => $membershipType,
                                    'credit_amount' => '0',
                                    'via' => 'Authorize'
                                ));
                                $_SESSION[ 'userEdited' ] = true;
                                SuperCache::cache('pro_users')->destroy();
                                $response[ 'url' ] = self::Config()->uri . '/ProSuccess?paymode=pro';
                                $response['status'] = 200;
                            } else {
                                $response['message'] = __('Error While make you pro');
                            }
                        } else if ($_POST['type'] == 'unlock_private_photo') {
                            //done
                            $updated    = $db->where('id', $user->id)->update('users', array('lock_private_photo' => 0));
                            if ($updated) {
                                $db->insert('payments', array(
                                    'user_id' => $user->id,
                                    'amount' => $price,
                                    'type' => 'unlock_private_photo',
                                    'pro_plan' => '0',
                                    'credit_amount' => '0',
                                    'via' => 'Authorize'
                                ));
                                $_SESSION[ 'userEdited' ] = true;
                                $response[ 'url' ] = self::Config()->uri . '/ProSuccess?paymode=unlock';
                                $response['status'] = 200;
                            } else {
                                $response['message'] = __('Error While update Unlock private photo charging');
                            }
                        } else if ($_POST['type'] == 'lock_pro_video') {
                            //done
                            $updated    = $db->where('id', $user->id)->update('users', array('lock_pro_video' => 0));
                            if ($updated) {
                                $db->insert('payments', array(
                                    'user_id' => $user->id,
                                    'amount' => $price,
                                    'type' => 'lock_pro_video',
                                    'pro_plan' => '0',
                                    'credit_amount' => '0',
                                    'via' => 'Authorize'
                                ));
                                $_SESSION[ 'userEdited' ] = true;
                                $response[ 'url' ] = self::Config()->uri . '/ProSuccess?paymode=unlock';
                                $response['status'] = 200;
                            } else {
                                $response['message'] = __('Error While update Unlock private photo charging');
                            }
                        }
                    }
                    else{
                        $error = __('unknown_error');
                        if ($trans->getErrors() != null) {
                            $error = $trans->getErrors()[0]->getErrorText();
                        }
                        $response['message'] = $error;
                    }
                }
                else{
                    $trans = $Aresponse->getTransactionResponse();
                    $error = __('unknown_error');
                    if ($trans->getErrors() != null) {
                        $error = $trans->getErrors()[0]->getErrorText();
                    }
                    $response['message'] = $error;
                }
            }
            else{
                $response['message'] = __('unknown_error');
            }
        }
        else{
            $response['message'] = __('please check your details');
        }
        return $response;
    }
}