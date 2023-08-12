<?php
Class Razorpay {
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
        if (route(4) == 'create') {
            json($this->create());
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

	public function create()
    {
    	global $db,$config,$_LIBS;
    	if (!empty($_POST['payment_id']) && !empty($_POST['order_id']) && !empty($_POST['merchant_amount']) && is_numeric($_POST['merchant_amount'])) {


    		$payment_id = Secure($_POST['payment_id']);
    		$realprice    = (int)Secure($_POST['merchant_amount']);
    		$amount      = 0;
    		$realprice = $realprice;
            if ($realprice == $config->bag_of_credits_price) {
                $amount = $config->bag_of_credits_amount;
            } else if ($realprice == $config->box_of_credits_price) {
                $amount = $config->box_of_credits_amount;
            } else if ($realprice == $config->chest_of_credits_price) {
                $amount = $config->chest_of_credits_amount;
            }
            if (empty($amount)) {
                return json(array(
                    'message' => 'Please enter the correct amount',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '18',
                        'error_text' => 'Please enter the correct amount'
                    )
                ), 400);
            }
    		$currency_code = "INR";
		    $check = array(
			    'amount' => $realprice,
			    'currency' => $currency_code,
			);


			$json = CheckRazorpayPayment($payment_id,$check);
			if (!empty($json) && empty($json->error_code)) {
				$user           = $db->objectBuilder()->where('id', Auth()->id)->getOne('users', array('balance'));
				$newbalance = $user['balance'] + $amount;
                $updated    = $db->where('id', Auth()->id)->update('users', array('balance' => $newbalance));
                if ($updated) {
                    RegisterAffRevenue(Auth()->id,$realprice);
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => $realprice,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'Razorpay'
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
		                    'error_id'   => '16',
		                    'error_text' => 'Error While update balance after charging'
		                )
		            ), 400);
                }
			}
			else{
		    	return json(array(
	                'message' => $json->error_code . ':' . $json->error_description,
	                'code' => 400,
	                'errors'         => array(
	                    'error_id'   => '15',
	                    'error_text' => $json->error_code . ':' . $json->error_description
	                )
	            ), 400);
		    }
    	}
    	else{
    		return json(array(
                'message' => 'payment_id , order_id , merchant_amount can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'payment_id , order_id , merchant_amount can not be empty'
                )
            ), 400);
    	}
    }
}