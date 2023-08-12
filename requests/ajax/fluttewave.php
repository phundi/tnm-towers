<?php
Class Fluttewave extends Aj {
	public function pay()
    {
    	global $db, $config, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $data['status'] = 400;
		if (!empty($_POST['amount']) && is_numeric($_POST['amount']) && !empty($_POST['email'])) {
			$email = Secure($_POST['email']);
		    $price = Secure($_POST['amount']);
		    $membershipType = 0;
		    $amount = 0;
		    $description = 'Upgrade to pro';
		    if ($price == self::Config()->weekly_pro_plan) {
                $membershipType = 1;
            } else if ($price == self::Config()->monthly_pro_plan) {
                $membershipType = 2;
            } else if ($price == self::Config()->yearly_pro_plan) {
                $membershipType = 3;
            } else if ($price == self::Config()->lifetime_pro_plan) {
                $membershipType = 4;
            }
		    $redirect_url = $config->uri .'/aj/fluttewave/success?mode=pro&membershipType=' . $membershipType;
		    if (!empty($_GET['pay_type']) && $_GET['pay_type'] == 'credits') {
		    	if ($price == self::Config()->bag_of_credits_price) {
	                $amount = self::Config()->bag_of_credits_amount;
	            } else if ($price == self::Config()->box_of_credits_price) {
	                $amount = self::Config()->box_of_credits_amount;
	            } else if ($price == self::Config()->chest_of_credits_price) {
	                $amount = self::Config()->chest_of_credits_amount;
	            }
	            $redirect_url = $config->uri .'/aj/fluttewave/success?mode=credits&amount=' . $amount . '&price=' .$price;
	            $description = 'Buy credits';
		    }

		    //* Prepare our rave request
		    $request = [
		        'tx_ref' => time(),
		        'amount' => $price,
		        'currency' => 'NGN',
		        'payment_options' => 'card',
		        'redirect_url' => $redirect_url,
		        'customer' => [
		            'email' => $email,
		            'name' => 'user_'.uniqid()
		        ],
		        'meta' => [
		            'price' => $price
		        ],
		        'customizations' => [
		            'title' => $description,
		            'description' => $description
		        ]
		    ];

		    //* Ca;; f;iterwave emdpoint
		    $curl = curl_init();

		    curl_setopt_array($curl, array(
		    CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_ENCODING => '',
		    CURLOPT_MAXREDIRS => 10,
		    CURLOPT_TIMEOUT => 0,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_CUSTOMREQUEST => 'POST',
		    CURLOPT_POSTFIELDS => json_encode($request),
		    CURLOPT_HTTPHEADER => array(
		        'Authorization: Bearer '.$config->fluttewave_secret_key,
		        'Content-Type: application/json'
		    ),
		    ));

		    $response = curl_exec($curl);

		    curl_close($curl);
		    
		    $res = json_decode($response);
		    if($res->status == 'success')
		    {
		    	$data['status'] = 200;
		        $data['url'] = $res->data->link;
		    }
		    else
		    {
		        $data['message'] = __('Something went wrong, please try again later.');
		    }
		}
		else{
			$data['message'] = __('please_check_details');
		}
		return $data;
    }
    public function success()
    {
    	global $db, $config, $_BASEPATH, $_DS;
    	if (!empty($_GET['status']) && $_GET['status'] == 'successful' && !empty($_GET['transaction_id'])) {
			$txid = $_GET['transaction_id'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txid}/verify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                  "Content-Type: application/json",
                  "Authorization: Bearer ".self::Config()->fluttewave_secret_key
                ),
            ));
              
            $response = curl_exec($curl);
              
            curl_close($curl);
              
            $res = json_decode($response);
            if($res->status){
            	$price = $res->data->charged_amount;
            	if ($_GET['mode'] == 'pro' && !empty($_GET['membershipType']) && in_array($_GET['membershipType'], array(1,2,3,4))) {
            		
            		// $response[ 'location' ] = '/ProSuccess?mode=pro';
		            $protime                = time();
		            $is_pro                 = "1";
		            $pro_type               = Secure($_GET['membershipType']);
		            $updated                = $db->where('id', self::ActiveUser()->id)->update('users', array(
		                'pro_time' => $protime,
		                'is_pro' => $is_pro,
		                'pro_type' => $pro_type
		            ));
		            if ($updated) {
		                RegisterAffRevenue(self::ActiveUser()->id,$price);
		                $db->insert('payments', array(
		                    'user_id' => self::ActiveUser()->id,
		                    'amount' => $price,
		                    'type' => 'PRO',
		                    'pro_plan' => $membershipType,
		                    'credit_amount' => '0',
		                    'via' => 'Fluttewave'
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
            	elseif ($_GET['mode'] == 'credits' && !empty($_GET['amount']) && is_numeric($_GET['amount'])) {
            		$amount = Secure($_GET['amount']);
            		$newbalance = self::ActiveUser()->balance + $amount;
		            $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array(
		                'balance' => $newbalance
		            ));
		            if ($updated) {
		                RegisterAffRevenue(self::ActiveUser()->id,$price);
		                $db->insert('payments', array(
		                    'user_id' => self::ActiveUser()->id,
		                    'amount' => $price,
		                    'type' => 'CREDITS',
		                    'pro_plan' => '0',
		                    'credit_amount' => $amount,
		                    'via' => 'Fluttewave'
		                ));
		                $_SESSION[ 'userEdited' ] = true;
		                // $response[ 'credit_amount' ]  = (int) $newbalance;
		                $url = $config->uri . '/credit';
		                if (!empty($_COOKIE['redirect_page'])) {
		                    $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
		                    $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
		                }
		                header('Location: ' . $url);
		                exit();
		            } else {
		                header('Location: ' . self::Config()->uri . '/credit');
       					exit();
		            }
            	}
            }
		}
		header('Location: ' . self::Config()->uri . '/pro');
        exit();
    }
}