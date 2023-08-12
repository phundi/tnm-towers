<?php
Class Yoomoney {
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

	public function create()
    {
    	global $db,$config,$_LIBS;
    	if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
			$realprice   = (int)Secure($_POST[ 'price' ]);
            $amount      = 0;
            if ($realprice == $config->bag_of_credits_price) {
                $amount = $config->bag_of_credits_amount;
            } else if ($realprice == $config->box_of_credits_price) {
                $amount = $config->box_of_credits_amount;
            } else if ($realprice == $config->chest_of_credits_price) {
                $amount = $config->chest_of_credits_amount;
            }

			$order_id = uniqid();
			$yoomoney_hash = rand(11111,99999).rand(11111,99999);
			$receiver = $config->yoomoney_wallet_id;
			$successURL = SeoUri('aj/yoomoney/success?credit='.$amount);
			$form = '<form id="yoomoney_form" method="POST" action="https://yoomoney.ru/quickpay/confirm.xml">    
						<input type="hidden" name="receiver" value="'.$receiver.'"> 
						<input type="hidden" name="quickpay-form" value="donate"> 
						<input type="hidden" name="targets" value="transaction '.$order_id.'">   
						<input type="hidden" name="paymentType" value="PC"> 
						<input type="hidden" name="sum" value="'.$realprice.'" data-type="number"> 
						<input type="hidden" name="successURL" value="'.$successURL.'">
						<input type="hidden" name="label" value="'.$yoomoney_hash.'">
					</form>';
		    $db->where('id',Auth()->id)->update('users',array('yoomoney_hash' => $yoomoney_hash));
			return json(array(
                'html' => $form,
                'code' => 200
            ), 200);
		}
		else{
	        return json(array(
                'message' => 'price can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'price can not be empty'
                )
            ), 400);
	    }
    }
    public function success()
    {
    	global $db,$config,$_LIBS;
    	if (!empty($_POST['notification_type']) && !empty($_POST['operation_id']) && !empty($_POST['amount']) && !empty($_POST['currency']) && !empty($_POST['datetime']) && !empty($_POST['sender']) && !empty($_POST['codepro']) && !empty($_POST['label']) && !empty($_POST['credit']) && is_numeric($_POST['credit']) && !empty($_POST['sha1_hash'])) {
    		$hash = sha1($_POST['notification_type'].'&'.
			$_POST['operation_id'].'&'.
			$_POST['amount'].'&'.
			$_POST['currency'].'&'.
			$_POST['datetime'].'&'.
			$_POST['sender'].'&'.
			$_POST['codepro'].'&'.
			$config->yoomoney_notifications_secret.'&'.
			$_POST['label']);

			if ($_POST['sha1_hash'] != $hash || $_POST['codepro'] == true || $_POST['unaccepted'] == true) {
				return json(array(
	                'message' => 'something went wrong',
	                'code' => 400,
	                'errors'         => array(
	                    'error_id'   => '15',
	                    'error_text' => 'something went wrong'
	                )
	            ), 400);
			}
			else{
				$user = $db->objectBuilder()->where('yoomoney_hash',Secure($_POST['label']))->getOne(T_USERS);
				if (!empty($user)) {
					$update_data = array();
					$price = Secure($_POST['amount']);
                    $amount = Secure($_POST['credit']);
                    $newbalance = $user->balance + $amount;
                    $update_data['balance'] = $newbalance;
                    $update_data['yoomoney_hash'] = '';
                    $updated    = $db->where('id', $user->id)->update('users', $update_data);
                    if ($updated) {
                        RegisterAffRevenue($user->id,$price);
                        $db->insert('payments', array(
                            'user_id' => $user->id,
                            'amount' => $price,
                            'type' => 'CREDITS',
                            'pro_plan' => '0',
                            'credit_amount' => $amount,
                            'via' => 'Yoomoney'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        return json(array(
		                    'message' => 'SUCCESS',
		                    'code' => 200
		                ), 200);
                    }
                    else{
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
		                'message' => 'user not found',
		                'code' => 400,
		                'errors'         => array(
		                    'error_id'   => '15',
		                    'error_text' => 'user not found'
		                )
		            ), 400);
				}
			}
    	}
    	else{
    		return json(array(
                'message' => 'notification_type , operation_id , amount , currency , datetime , sender , codepro , label , credit , sha1_hash can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'notification_type , operation_id , amount , currency , datetime , sender , codepro , label , credit , sha1_hash can not be empty'
                )
            ), 400);
    	}
    }
}