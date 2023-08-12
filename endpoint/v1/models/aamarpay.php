<?php
Class Aamarpay {
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
        if (route(4) == 'get') {
            json($this->get());
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
    public function get(){
        global $db,$config,$_LIBS;
        $types = array(
            'credit',
            'go_pro'
        );
        if (!empty($_POST['type']) && in_array($_POST['type'], $types) && !empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0 && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
            $realprice   = (int)Secure($_POST[ 'price' ]);
            $name   = Secure($_POST[ 'name' ]);
            $email   = Secure($_POST[ 'email' ]);
            $phone   = Secure($_POST[ 'phone' ]);
            $amount      = 0;
            if ($_POST['type'] == 'go_pro') {
                if ($realprice == $config->weekly_pro_plan) {
                    $membershipType = 1;
                } else if ($realprice == $config->monthly_pro_plan) {
                    $membershipType = 2;
                } else if ($realprice == $config->yearly_pro_plan) {
                    $membershipType = 3;
                } else if ($realprice == $config->lifetime_pro_plan) {
                    $membershipType = 4;
                }
                else{
                    return ['message' => 'Please enter the correct amount',
                            'status' => 400];
                }
            }
            else if ($_POST['type'] == 'credit') {
                if ($realprice == $config->bag_of_credits_price) {
                    $amount = $config->bag_of_credits_amount;
                } else if ($realprice == $config->box_of_credits_price) {
                    $amount = $config->box_of_credits_amount;
                } else if ($realprice == $config->chest_of_credits_price) {
                    $amount = $config->chest_of_credits_amount;
                }
                else{
                    return ['message' => 'Please enter the correct amount',
                            'status' => 400];
                }
            }
            if ($config->aamarpay_mode == 'sandbox') {
                $url = 'https://sandbox.aamarpay.com/request.php'; // live url https://secure.aamarpay.com/request.php
            }
            else {
                $url = 'https://secure.aamarpay.com/request.php';
            }
            $tran_id = rand(1111111,9999999);
            $fields = array(
                'store_id' => $config->aamarpay_store_id, //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
                'amount' => $realprice, //transaction amount
                'payment_type' => 'VISA', //no need to change
                'currency' => 'BDT',  //currenct will be USD/BDT
                'tran_id' => $tran_id, //transaction id must be unique from your end
                'cus_name' => $name,  //customer name
                'cus_email' => $email, //customer email address
                'cus_add1' => '',  //customer address
                'cus_add2' => '', //customer address
                'cus_city' => '',  //customer city
                'cus_state' => '',  //state
                'cus_postcode' => '', //postcode or zipcode
                'cus_country' => 'Bangladesh',  //country
                'cus_phone' => $phone, //customer phone number
                'cus_fax' => 'Not¬Applicable',  //fax
                'ship_name' => '', //ship name
                'ship_add1' => '',  //ship address
                'ship_add2' => '',
                'ship_city' => '',
                'ship_state' => '',
                'ship_postcode' => '',
                'ship_country' => 'Bangladesh',
                'desc' => 'top up credits',
                'success_url' => SeoUri('aj/aamarpay/success?credit='.$amount), //your success route
                'fail_url' => SeoUri('aj/aamarpay/cancel?credit='.$amount), //your fail route
                'cancel_url' => SeoUri('aj/aamarpay/cancel?credit='.$amount), //your cancel url
                'opt_a' => '',  //optional paramter
                'opt_b' => '',
                'opt_c' => '',
                'opt_d' => '',
                'signature_key' => $config->aamarpay_signature_key //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key
            );
            $fields_string = http_build_query($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $url_forward = str_replace('"', '', stripslashes($result));
            curl_close($ch);
            $db->where('id',Auth()->id)->update('users',array('aamarpay_tran_id' => $tran_id));
            if ($config->aamarpay_mode == 'sandbox') {
                $base_url = 'https://sandbox.aamarpay.com/'.$url_forward;
            }
            else {
                $base_url = 'https://secure.aamarpay.com/'.$url_forward;
            }
            $data['status'] = 200;
            $data['url'] = $base_url;
        }
        else{
            $data['status'] = 400;
            $data['message'] = __('missing_fields');
        }
        return $data;
    }
    public function success()
    {
        global $db,$config,$_LIBS;
        $types = array(
            'credit',
            'go_pro'
        );
        if (!empty($_POST['type']) && in_array($_POST['type'], $types) && !empty($_POST['amount']) && !empty($_POST['mer_txnid']) && !empty($_POST['pay_status']) && $_POST['pay_status'] == 'Successful') {
            $user = $db->objectBuilder()->where('aamarpay_tran_id',Secure($_POST['mer_txnid']))->getOne('users');
            if (!empty($user)) {
                $update_data = array();
                $price = (int)Secure($_POST['amount']);
                $amount = 0;
                $membershipType = 0;
                if ($_POST['type'] == 'credit') {
                    if ($realprice == $config->bag_of_credits_price) {
                        $amount = $config->bag_of_credits_amount;
                    } else if ($realprice == $config->box_of_credits_price) {
                        $amount = $config->box_of_credits_amount;
                    } else if ($realprice == $config->chest_of_credits_price) {
                        $amount = $config->chest_of_credits_amount;
                    }
                    else{
                        return ['message' => 'Please enter the correct amount',
                                'status' => 400];
                    }
                    $newbalance = Auth()->balance + $amount;
                    $update_data['balance'] = $newbalance;
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => $price,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'Aamarpay'
                    ));
                }
                else if ($_POST['type'] == 'go_pro') {
                    if ($realprice == $config->weekly_pro_plan) {
                        $membershipType = 1;
                    } else if ($realprice == $config->monthly_pro_plan) {
                        $membershipType = 2;
                    } else if ($realprice == $config->yearly_pro_plan) {
                        $membershipType = 3;
                    } else if ($realprice == $config->lifetime_pro_plan) {
                        $membershipType = 4;
                    }
                    else{
                        return ['message' => 'Please enter the correct amount',
                                'status' => 400];
                    }
                    $update_data = array(
                        'pro_time' => time(),
                        'is_pro' => "1",
                        'pro_type' => $membershipType
                    );
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => $price,
                        'type' => 'PRO',
                        'pro_plan' => $membershipType,
                        'credit_amount' => '0',
                        'via' => 'Aamarpay'
                    ));
                }
                
                
                $update_data['aamarpay_tran_id'] = '';
                $updated    = $db->where('id', Auth()->id)->update('users', $update_data);
                if ($updated) {
                    RegisterAffRevenue(Auth()->id,$price);
                    
                    $_SESSION[ 'userEdited' ] = true;
                    $response[ 'credit_amount' ]  = (int) $newbalance;
                    return json(array(
                        'message' => 'SUCCESS',
                        'code' => 200
                    ), 200);
                }
                else{
                    return json(array(
                        'message' => 'something went wrong',
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '15',
                            'error_text' => 'something went wrong'
                        )
                    ), 400);
                }
            }
            else{
                return json(array(
                    'message' => 'No pending payment found',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '15',
                        'error_text' => 'No pending payment found'
                    )
                ), 400);
            }
        }
        else{
            $data['status'] = 400;
            $data['message'] = __('missing_fields');
            return $data;
        }    
    }
}