<?php
Class Yoomoney extends Aj {
	public function create()
    {
    	global $db;
    	if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
			$realprice   = (int)Secure($_POST[ 'price' ]);
            $amount      = 0;
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }

			$order_id = uniqid();
			$yoomoney_hash = rand(11111,99999).rand(11111,99999);
			$receiver = self::Config()->yoomoney_wallet_id;
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
		    $db->where('id',self::ActiveUser()->id)->update('users',array('yoomoney_hash' => $yoomoney_hash));
			$data['status'] = 200;
			$data['html'] = $form;
		}
		else{
	        $data['status'] = 400;
	        $data['message'] = __('no_amount_passed');
	    }
	    return $data;
    }
    public function success()
    {
    	global $db;
    	$hash = sha1($_POST['notification_type'].'&'.
		$_POST['operation_id'].'&'.
		$_POST['amount'].'&'.
		$_POST['currency'].'&'.
		$_POST['datetime'].'&'.
		$_POST['sender'].'&'.
		$_POST['codepro'].'&'.
		self::Config()->yoomoney_notifications_secret.'&'.
		$_POST['label']);

		if ($_POST['sha1_hash'] != $hash || $_POST['codepro'] == true || $_POST['unaccepted'] == true) {
			header('Location: ' . self::Config()->uri . '/credit');
        	exit();
		}
		else{
			if (!empty($_POST['label']) && !empty($_GET['credit']) && is_numeric($_GET['credit'])) {
				$user = $db->objectBuilder()->where('yoomoney_hash',Secure($_POST['label']))->getOne('users');
				if (!empty($user)) {
					$update_data = array();
					$price = Secure($_POST['amount']);
                    $amount = Secure($_GET['credit']);
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
    }
    public function go_pro()
    {
    	global $db;
    	if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
			$realprice   = (int)Secure($_POST[ 'price' ]);
			if ($realprice == self::Config()->weekly_pro_plan) {
                $membershipType = 1;
            } else if ($realprice == self::Config()->monthly_pro_plan) {
                $membershipType = 2;
            } else if ($realprice == self::Config()->yearly_pro_plan) {
                $membershipType = 3;
            } else if ($realprice == self::Config()->lifetime_pro_plan) {
                $membershipType = 4;
            }
            $amount = $realprice;
            $order_id = uniqid();
			$yoomoney_hash = rand(11111,99999).rand(11111,99999);
			$receiver = self::Config()->yoomoney_wallet_id;
			$successURL = SeoUri('aj/yoomoney/pro_success?credit='.$amount.'&membershipType='.$membershipType);
			$form = '<form id="yoomoney_form" method="POST" action="https://yoomoney.ru/quickpay/confirm.xml">    
						<input type="hidden" name="receiver" value="'.$receiver.'"> 
						<input type="hidden" name="quickpay-form" value="donate"> 
						<input type="hidden" name="targets" value="transaction '.$order_id.'">   
						<input type="hidden" name="paymentType" value="PC"> 
						<input type="hidden" name="sum" value="'.$realprice.'" data-type="number"> 
						<input type="hidden" name="successURL" value="'.$successURL.'">
						<input type="hidden" name="label" value="'.$yoomoney_hash.'">
					</form>';
		    $db->where('id',self::ActiveUser()->id)->update('users',array('yoomoney_hash' => $yoomoney_hash));
			$data['status'] = 200;
			$data['html'] = $form;
		}
		else{
	        $data['status'] = 400;
	        $data['message'] = __('no_amount_passed');
	    }
	    return $data;
    }
    public function pro_success()
    {
    	global $db;
    	$hash = sha1($_POST['notification_type'].'&'.
		$_POST['operation_id'].'&'.
		$_POST['amount'].'&'.
		$_POST['currency'].'&'.
		$_POST['datetime'].'&'.
		$_POST['sender'].'&'.
		$_POST['codepro'].'&'.
		self::Config()->yoomoney_notifications_secret.'&'.
		$_POST['label']);

		if ($_POST['sha1_hash'] != $hash || $_POST['codepro'] == true || $_POST['unaccepted'] == true) {
			header('Location: ' . self::Config()->uri . '/pro');
        	exit();
		}
		else{
			if (!empty($_POST['label']) && !empty($_GET['credit']) && is_numeric($_GET['credit']) && !empty($_GET['membershipType']) && in_array($_GET['membershipType'], array(1,2,3,4))) {
				$user = $db->objectBuilder()->where('yoomoney_hash',Secure($_POST['label']))->getOne('users');
				if (!empty($user)) {
					$price = Secure($_GET['credit']);
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
		                    'via' => 'Yoomoney'
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
		}
		header('Location: ' . self::Config()->uri . '/pro');
        exit();
    }
}