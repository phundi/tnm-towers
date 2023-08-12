<?php
Class Fluttewave {
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
        $data = ['status' => 400];
		if (!empty($_POST['amount']) && is_numeric($_POST['amount']) && !empty($_POST['email']) && !empty($_POST['type']) && in_array($_POST['type'], ['credit','go_pro'])) {
			$email = Secure($_POST['email']);
		    $price = Secure($_POST['amount']);
		    $membershipType = 0;
		    $amount = 0;
		    $description = '';
		    $redirect_url = '';
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
	            else{
	            	return ['message' => 'Please enter the correct amount',
                            'status' => 400];
	            }
	            $redirect_url = $config->uri .'/aj/fluttewave/success?mode=pro&membershipType=' . $membershipType;
	            $description = 'Upgrade to pro';
		    }
		    else{
		    	if ($price == $config->bag_of_credits_price) {
	                $amount = $config->bag_of_credits_amount;
	            } else if ($price == $config->box_of_credits_price) {
	                $amount = $config->box_of_credits_amount;
	            } else if ($price == $config->chest_of_credits_price) {
	                $amount = $config->chest_of_credits_amount;
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
		    	$data['status'] = 400;
		        $data['message'] = __('Something went wrong, please try again later.');
		    }
		}
		else{
			$data['status'] = 400;
		    $data['message'] = __('please_check_details');
		}
		return $data;
    }

    public function success()
    {
    	global $db, $config, $_BASEPATH, $_DS;
    	$data = ['status' => 400];
    	if (!empty($_POST['status']) && $_POST['status'] == 'successful' && !empty($_POST['transaction_id']) && !empty($_POST['type']) && in_array($_POST['type'], ['credit','go_pro'])) {

    		if ($_POST['type'] == 'go_pro' && (empty($_POST['membershipType']) || !in_array($_POST['membershipType'], array(1,2,3,4)))) {
    			$data['status'] = 400;
		    	$data['message'] = 'membershipType can not be empty';
		    	return $data;
    		}
    		elseif ($_POST['type'] == 'credit' && empty($_POST['amount'])) {
    			$data['status'] = 400;
		    	$data['message'] = 'amount can not be empty';
		    	return $data;
    		}
			$txid = $_POST['transaction_id'];

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
                  "Authorization: Bearer ".$config->fluttewave_secret_key
                ),
            ));
              
            $response = curl_exec($curl);
              
            curl_close($curl);
              
            $res = json_decode($response);
            if($res->status && !empty($res->data->charged_amount)){
            	$price = $res->data->charged_amount;
            	if ($_POST['type'] == 'go_pro') {
            		
            		// $response[ 'location' ] = '/ProSuccess?mode=pro';
		            $protime                = time();
		            $is_pro                 = "1";
		            $pro_type               = Secure($_POST['membershipType']);
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
		                    'via' => 'Fluttewave'
		                ));
		                $_SESSION[ 'userEdited' ] = true;
		                SuperCache::cache('pro_users')->destroy();
		                return json(array(
	                        'message' => 'SUCCESS',
	                        'code' => 200
	                    ), 200);
		            } else {
		                $data['status'] = 400;
		        		$data['message'] = __('Something went wrong, please try again later.');
		            }
            	}
            	elseif ($_POST['type'] == 'credit') {
            		$amount = Secure($_POST['amount']);
            		$newbalance = Auth()->balance + $amount;
		            $updated    = $db->where('id', Auth()->id)->update('users', array(
		                'balance' => $newbalance
		            ));
		            if ($updated) {
		                RegisterAffRevenue(Auth()->id,$price);
		                $db->insert('payments', array(
		                    'user_id' => Auth()->id,
		                    'amount' => $price,
		                    'type' => 'CREDITS',
		                    'pro_plan' => '0',
		                    'credit_amount' => $amount,
		                    'via' => 'Fluttewave'
		                ));
		                $_SESSION[ 'userEdited' ] = true;
		                return json(array(
	                        'message' => 'SUCCESS',
	                        'code' => 200
	                    ), 200);
		            } else {
		                $data['status'] = 400;
		        		$data['message'] = __('Something went wrong, please try again later.');
		            }
            	}
            }
            else{
            	$data['status'] = 400;
		        $data['message'] = __('Something went wrong, please try again later.');
            }
		}
		else{
			$data['status'] = 400;
		    $data['message'] = __('please_check_details');
		}
		return $data;
    }
}