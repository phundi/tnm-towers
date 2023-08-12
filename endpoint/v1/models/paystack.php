<?php
class Paystack {
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
        if (route(4) == 'initialize') {
            json($this->initialize());
        }
        elseif (route(4) == 'payment') {
            json($this->payment());
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

    public function initialize(){
        global $db,$config,$_LIBS;
        $types = array(
            'credit',
            'go_pro',
            'unlock_private_photo',
            'lock_pro_video'
        );
        $response = array();
        $response['status'] = 400;

        if (!empty($_POST['type']) && in_array($_POST['type'], $types) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['price']) && is_numeric($_POST['price'])) {
            $price = intval($_POST['price']);
            $amount = 0;
            if ($_POST['type'] == 'go_pro') {
                if ($price == $config->weekly_pro_plan) {
                    $membershipType = 1;
                } else if ($price == $config->monthly_pro_plan) {
                    $membershipType = 2;
                } else if ($price == $config->yearly_pro_plan) {
                    $membershipType = 3;
                } else if ($price == $config->lifetime_pro_plan) {
                    $membershipType = 4;
                }
                $callback_url = SeoUri('aj/paystack/payment?pay_type=go_pro&membershipType='.$membershipType.'&price='.$price);
            }
            elseif ($_POST['type'] == 'credit') {
                if ($price == $config->bag_of_credits_price) {
                    $amount = $config->bag_of_credits_amount;
                } else if ($price == $config->box_of_credits_price) {
                    $amount = $config->box_of_credits_amount;
                } else if ($price == $config->chest_of_credits_price) {
                    $amount = $config->chest_of_credits_amount;
                }
                $callback_url = SeoUri('aj/paystack/payment?pay_type=credit&amount='.$amount.'&price='.$price);
            }
            elseif ($_POST['type'] == 'unlock_private_photo') {
                if ((int)$price == (int)$config->lock_private_photo_fee) {
                    $amount = (int)$config->lock_private_photo_fee;
                }
                $callback_url = SeoUri('aj/paystack/payment?pay_type=unlock_private_photo&price='.$price);
            }
            elseif ($_POST['type'] == 'lock_pro_video') {
                if ((int)$price == (int)$config->lock_pro_video_fee) {
                    $amount = (int)$config->lock_pro_video_fee;
                }
                $callback_url = SeoUri('aj/paystack/payment?pay_type=lock_pro_video&price='.$price);
            }
            $price = $price * 100;
            $result = array();
            $reference = uniqid();

            //Set other parameters as keys in the $postdata array
            $postdata =  array('email' => $_POST['email'], 'amount' => $price,"reference" => $reference,'callback_url' => $callback_url);
            $url = "https://api.paystack.co/transaction/initialize";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = [
              'Authorization: Bearer '.$config->paystack_secret_key,
              'Content-Type: application/json',

            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $request = curl_exec ($ch);

            curl_close ($ch);

            if ($request) {
                $result = json_decode($request, true);
                if (!empty($result)) {
                     if (!empty($result['status']) && $result['status'] == 1 && !empty($result['data']) && !empty($result['data']['authorization_url']) && !empty($result['data']['access_code'])) {
                        $db->where('id',Auth()->id)->update('users',array('paystack_ref' => $reference));
                        $response['status'] = 200;
                        $response['url'] = $result['data']['authorization_url'];
                    }
                    else{
                        $response['message'] = $result['message'];
                    }
                }
                else{
                    $response['message'] = __('Something went wrong, please try again later.');
                }
            }
            else{
                $response['message'] = __('Something went wrong, please try again later.');
            }

        }
        else{
            $response['message'] = __('please check your details');
        }
        return $response;
    }

    public function payment()
    {
        global $db,$config,$_LIBS;
        $response = array();
        $response['status'] = 400;
        if (!empty($_POST['reference']) && !empty($_POST['pay_type']) && !empty($_POST['price']) && is_numeric($_POST['price'])) {
            $payment  = CheckPaystackPayment($_POST['reference']);
            if ($payment) {
                $price = intval(Secure($_POST['price']));
                if ($_POST['pay_type'] == 'credit') {
                    if (!empty($_POST['amount']) && is_numeric($_POST['amount'])) {
                        $amount = intval(Secure($_POST['amount']));
                        $user           = $db->objectBuilder()->where('id', Auth()->id)->getOne('users', array('balance'));
                        $newbalance = $user->balance + $amount;
                        $updated    = $db->where('id', Auth()->id)->update('users', array('balance' => $newbalance));
                        if ($updated) {
                            RegisterAffRevenue(Auth()->id,$price);
                            $db->insert('payments', array(
                                'user_id' => Auth()->id,
                                'amount' => $price,
                                'type' => 'CREDITS',
                                'pro_plan' => '0',
                                'credit_amount' => $amount,
                                'via' => 'paystack'
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                            $response['status'] = 200;
                            $response['message'] = 'payment Success';
                        } else {
                            $response['message'] = 'Error While update balance after charging';
                        }
                    }
                    else{
                        $response['message'] = 'amount can not be empty';
                    }
                        
                }
                elseif ($_POST['pay_type'] == 'go_pro') {
                    if (!empty($_POST['membershipType']) && in_array($_POST['membershipType'], array(1,2,3,4))) {
                        $membershipType = Secure($_POST['membershipType']);
                        $protime                = time();
                        $is_pro                 = "1";
                        $pro_type               = $membershipType;
                        $updated                = $db->where('id', Auth()->id)->update('users', array(
                            'pro_time' => $protime,
                            'is_pro' => $is_pro,
                            'pro_type' => $pro_type
                        ));
                        if ($updated) {
                            RegisterAffRevenue(Auth()->id,$price);
                            $db->insert('payments', array(
                                'user_id' => Auth()->id,
                                'amount' => $price,
                                'type' => 'PRO',
                                'pro_plan' => $membershipType,
                                'credit_amount' => '0',
                                'via' => 'paystack'
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                            SuperCache::cache('pro_users')->destroy();
                            $response['status'] = 200;
                            $response['message'] = 'payment Success';
                        } else {
                            $response['message'] = 'Error While make you pro';
                        }
                    }
                    else{
                        $response['message'] = 'membershipType can not be empty';
                    }
                        
                }
                elseif ($_GET['pay_type'] == 'unlock_private_photo') {
                    $updated    = $db->where('id', Auth()->id)->update('users', array('lock_private_photo' => 0));
                    if ($updated) {
                        $db->insert('payments', array(
                            'user_id' => Auth()->id,
                            'amount' => $price,
                            'type' => 'unlock_private_photo',
                            'pro_plan' => '0',
                            'credit_amount' => '0',
                            'via' => 'paystack'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        $response['status'] = 200;
                        $response['message'] = 'payment Success';
                    } else {
                        $response['message'] = 'Error While update Unlock private photo charging';
                    }
                }
                elseif ($_GET['pay_type'] == 'lock_pro_video') {
                    $updated    = $db->where('id', Auth()->id)->update('users', array('lock_pro_video' => 0));
                    if ($updated) {
                        $db->insert('payments', array(
                            'user_id' => Auth()->id,
                            'amount' => $price,
                            'type' => 'lock_pro_video',
                            'pro_plan' => '0',
                            'credit_amount' => '0',
                            'via' => 'paystack'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        $response['status'] = 200;
                        $response['message'] = 'payment Success';
                    } else {
                        $response['message'] = 'Error While update Unlock private photo charging';
                    }
                }
                else{
                    $response['message'] = __('Something went wrong, please try again later.');
                }
            }
            else{
                $response['message'] = __('Something went wrong, please try again later.');
            }
        }
        else{
            $response['message'] = __('please check your details');
        }
        return $response;
    }
}