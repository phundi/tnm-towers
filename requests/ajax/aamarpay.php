<?php
Class Aamarpay extends Aj {
	public function get()
    {
    	global $db;
    	if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0 && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
			$realprice   = (int)Secure($_POST[ 'price' ]);
			$name   = Secure($_POST[ 'name' ]);
			$email   = Secure($_POST[ 'email' ]);
			$phone   = Secure($_POST[ 'phone' ]);
            $amount      = 0;
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }
            if (self::Config()->aamarpay_mode == 'sandbox') {
	            $url = 'https://sandbox.aamarpay.com/request.php'; // live url https://secure.aamarpay.com/request.php
	        }
	        else {
	            $url = 'https://secure.aamarpay.com/request.php';
	        }
            $tran_id = rand(1111111,9999999);
            $fields = array(
	            'store_id' => self::Config()->aamarpay_store_id, //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
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
	            'signature_key' => self::Config()->aamarpay_signature_key //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key
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
	        $db->where('id',self::ActiveUser()->id)->update('users',array('aamarpay_tran_id' => $tran_id));
	        if (self::Config()->aamarpay_mode == 'sandbox') {
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
    	global $db;
    	if (!empty($_POST['amount']) && !empty($_POST['mer_txnid']) && !empty($_POST['pay_status']) && $_POST['pay_status'] == 'Successful') {
    		$user = $db->objectBuilder()->where('aamarpay_tran_id',Secure($_POST['mer_txnid']))->getOne('users');
			if (!empty($user)) {
				$update_data = array();
				$price = (int)Secure($_POST['amount']);
                $amount = (int)Secure($_GET['credit']);
                $newbalance = $user->balance + $amount;
                $update_data['balance'] = $newbalance;
                $update_data['aamarpay_tran_id'] = '';
                $updated    = $db->where('id', $user->id)->update('users', $update_data);
                if ($updated) {
                    RegisterAffRevenue($user->id,$price);
                    $db->insert('payments', array(
                        'user_id' => $user->id,
                        'amount' => $price,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'Aamarpay'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    $response[ 'credit_amount' ]  = (int) $newbalance;
                    $url = self::Config()->uri . '/ProSuccess';
                    if (!empty($_COOKIE['redirect_page'])) {
                        $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                        $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                    }
                    header('Location: ' . $url);
                }
			}
    	}
    	header('Location: ' . self::Config()->uri . '/credit');
        exit();
    }
    public function cancel()
    {
    	global $db;
    	$db->where('id',self::ActiveUser()->id)->update('users',array('aamarpay_tran_id' => ''));
    	header('Location: ' . self::Config()->uri . '/credit');
        exit();
    }
    public function get_pro()
    {
    	global $db;
    	if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0 && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
			$price   = (int)Secure($_POST[ 'price' ]);
			$name   = Secure($_POST[ 'name' ]);
			$email   = Secure($_POST[ 'email' ]);
			$phone   = Secure($_POST[ 'phone' ]);
            if ($price == self::Config()->weekly_pro_plan) {
	            $membershipType = 1;
	        } else if ($price == self::Config()->monthly_pro_plan) {
	            $membershipType = 2;
	        } else if ($price == self::Config()->yearly_pro_plan) {
	            $membershipType = 3;
	        } else if ($price == self::Config()->lifetime_pro_plan) {
	            $membershipType = 4;
	        }
            if (self::Config()->aamarpay_mode == 'sandbox') {
	            $url = 'https://sandbox.aamarpay.com/request.php'; // live url https://secure.aamarpay.com/request.php
	        }
	        else {
	            $url = 'https://secure.aamarpay.com/request.php';
	        }
            $tran_id = rand(1111111,9999999);
            $fields = array(
	            'store_id' => self::Config()->aamarpay_store_id, //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
	            'amount' => $price, //transaction amount
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
	            'success_url' => SeoUri('aj/aamarpay/pro_success?membershipType='.$membershipType), //your success route
	            'fail_url' => SeoUri('aj/aamarpay/cancel'), //your fail route
	            'cancel_url' => SeoUri('aj/aamarpay/cancel'), //your cancel url
	            'opt_a' => '',  //optional paramter
	            'opt_b' => '',
	            'opt_c' => '',
	            'opt_d' => '',
	            'signature_key' => self::Config()->aamarpay_signature_key //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key
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
	        $db->where('id',self::ActiveUser()->id)->update('users',array('aamarpay_tran_id' => $tran_id));
	        if (self::Config()->aamarpay_mode == 'sandbox') {
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
    public function pro_success()
    {
    	global $db;
    	if (!empty($_POST['amount']) && !empty($_POST['mer_txnid']) && !empty($_POST['pay_status']) && $_POST['pay_status'] == 'Successful' && !empty($_GET['membershipType']) && in_array($_GET['membershipType'], array(1,2,3,4))) {
    		$user = $db->objectBuilder()->where('aamarpay_tran_id',Secure($_POST['mer_txnid']))->getOne('users');
			if (!empty($user)) {
				$price = Secure($_POST['amount']);
				$response[ 'location' ] = '/ProSuccess?mode=pro';
	            $protime                = time();
	            $is_pro                 = "1";
	            $pro_type               = Secure($_GET['membershipType']);
	            $updated                = $db->where('id', $user->id)->update('users', array(
	                'pro_time' => $protime,
	                'is_pro' => $is_pro,
	                'pro_type' => $pro_type
	            ));
	            if ($updated) {
	                RegisterAffRevenue($user->id,$price / 100);
	                $db->insert('payments', array(
	                    'user_id' => $user->id,
	                    'amount' => $price,
	                    'type' => 'PRO',
	                    'pro_plan' => $pro_type,
	                    'credit_amount' => '0',
	                    'via' => 'Aamarpay'
	                ));
	                $_SESSION[ 'userEdited' ] = true;
	                SuperCache::cache('pro_users')->destroy();
	            } else {
	                header('Location: ' . self::Config()->uri . '/pro');
    				exit();
	            }
	            header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=pro');
	            exit();
			}
		}
		header('Location: ' . self::Config()->uri . '/pro');
        exit();
    }
}