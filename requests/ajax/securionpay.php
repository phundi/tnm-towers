<?php
use SecurionPay\SecurionPayGateway;
use SecurionPay\Exception\SecurionPayException;
use SecurionPay\Request\CheckoutRequestCharge;
use SecurionPay\Request\CheckoutRequest;
Class Securionpay extends Aj {
    public function token(){
        global $db,$config,$_LIBS;
        $types = array(
            'credit',
            'go_pro',
            'unlock_private_photo',
            'lock_pro_video'
        );
        $response = array();
        $response['status'] = 400;
        if (!empty($_POST['type']) && in_array($_POST['type'], $types) && !empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
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
                else{
                    $response['message'] = __('please check your details');
                    return $response;
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
                else{
                    $response['message'] = __('please check your details');
                    return $response;
                }
            }
            elseif ($_POST['type'] == 'unlock_private_photo') {
                if ((int)$price == (int)self::Config()->lock_private_photo_fee) {
                    $amount = (int)self::Config()->lock_private_photo_fee;
                }
                else{
                    $response['message'] = __('please check your details');
                    return $response;
                }
            }
            elseif ($_POST['type'] == 'lock_pro_video') {
                if ((int)$price == (int)self::Config()->lock_pro_video_fee) {
                    $amount = (int)self::Config()->lock_pro_video_fee;
                }
                else{
                    $response['message'] = __('please check your details');
                    return $response;
                }
            }
            require_once $_LIBS . 'securionpay/vendor/autoload.php';
            $securionPay = new SecurionPayGateway($config->securionpay_secret_key);
            $user_key = rand(1111,9999).rand(11111,99999);

            $checkoutCharge = new CheckoutRequestCharge();
            $checkoutCharge->amount(($price * 100))->currency('USD')->metadata(array('user_key' => $user_key,
                                                                                     'type' => $_POST['type']));

            $checkoutRequest = new CheckoutRequest();
            $checkoutRequest->charge($checkoutCharge);

            $signedCheckoutRequest = $securionPay->signCheckoutRequest($checkoutRequest);
            if (!empty($signedCheckoutRequest)) {
                $db->where('id',Auth()->id)->update('users',array('securionpay_key' => $user_key));
                $response['status'] = 200;
                $response['token'] = $signedCheckoutRequest;
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
    public function handle()
    {
        global $db,$config,$_LIBS;
        $types = array(
            'credit',
            'go_pro',
            'unlock_private_photo',
            'lock_pro_video'
        );
        $response = array();
        $response['status'] = 400;
        if (!empty($_POST) && !empty($_POST['charge']) && !empty($_POST['charge']['id'])) {
            $url = "https://api.securionpay.com/charges?limit=10";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_USERPWD, $config->securionpay_secret_key.":password");
            $resp = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($resp,true);
            if (!empty($resp) && !empty($resp['list'])) {
                foreach ($resp['list'] as $key => $value) {
                    if ($value['id'] == $_POST['charge']['id']) {
                        if (!empty($value['metadata']) && !empty($value['metadata']['type']) && in_array($value['metadata']['type'], $types) && !empty($value['metadata']['user_key']) && !empty($value['amount'])) {
                            if (Auth()->securionpay_key == $value['metadata']['user_key']) {
                                $db->where('id',Auth()->id)->update('users',array('securionpay_key' => 0));
                                $price = intval(Secure($value['amount'])) / 100;
                                $amount = 0;
                                $user = Auth();
                                if ($value['metadata']['type'] == 'go_pro') {
                                    if ($price == self::Config()->weekly_pro_plan) {
                                        $membershipType = 1;
                                    } else if ($price == self::Config()->monthly_pro_plan) {
                                        $membershipType = 2;
                                    } else if ($price == self::Config()->yearly_pro_plan) {
                                        $membershipType = 3;
                                    } else if ($price == self::Config()->lifetime_pro_plan) {
                                        $membershipType = 4;
                                    }
                                    else{
                                        $response['message'] = __('please check your details');
                                        return $response;
                                    }
                                }
                                elseif ($value['metadata']['type'] == 'credit') {
                                    if ($price == self::Config()->bag_of_credits_price) {
                                        $amount = self::Config()->bag_of_credits_amount;
                                    } else if ($price == self::Config()->box_of_credits_price) {
                                        $amount = self::Config()->box_of_credits_amount;
                                    } else if ($price == self::Config()->chest_of_credits_price) {
                                        $amount = self::Config()->chest_of_credits_amount;
                                    }
                                    else{
                                        $response['message'] = __('please check your details');
                                        return $response;
                                    }
                                }
                                elseif ($value['metadata']['type'] == 'unlock_private_photo') {
                                    if ((int)$price == (int)self::Config()->lock_private_photo_fee) {
                                        $amount = (int)self::Config()->lock_private_photo_fee;
                                    }
                                    else{
                                        $response['message'] = __('please check your details');
                                        return $response;
                                    }
                                }
                                elseif ($value['metadata']['type'] == 'lock_pro_video') {
                                    if ((int)$price == (int)self::Config()->lock_pro_video_fee) {
                                        $amount = (int)self::Config()->lock_pro_video_fee;
                                    }
                                    else{
                                        $response['message'] = __('please check your details');
                                        return $response;
                                    }
                                }
                                if ($value['metadata']['type'] == 'credit') {
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
                                        $url = $config->uri . '/credit';
                                        if (!empty($_COOKIE['redirect_page'])) {
                                            $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                                            $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                                        }
                                        $_SESSION[ 'userEdited' ] = true;
                                        $response[ 'credit_amount' ]  = (int) $newbalance;
                                        $response[ 'url' ] = $url;
                                        $response['status'] = 200;
                                        return $response;
                                    } else {
                                        $response['message'] = __('Error While update balance after charging');
                                    }
                                } else if ($value['metadata']['type'] == 'go_pro') {
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
                                        return $response;
                                    } else {
                                        $response['message'] = __('Error While make you pro');
                                    }
                                } else if ($value['metadata']['type'] == 'unlock_private_photo') {
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
                                        return $response;
                                    } else {
                                        $response['message'] = __('Error While update Unlock private photo charging');
                                    }
                                } else if ($value['metadata']['type'] == 'lock_pro_video') {
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
                                        return $response;
                                    } else {
                                        $response['message'] = __('Error While update Unlock private photo charging');
                                    }
                                }
                            }
                            else{
                                $response['message'] = __('unknown_error');
                            }
                        }
                        else{
                            $response['message'] = __('unknown_error');
                        }
                    }
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