<?php
Class Ngenius extends Aj {
	public function get()
    {
    	global $db;
    	if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
    		$token = GetNgeniusToken();
    		if (!empty($token->message)) {
    			$data['status'] = 400;
		        $data['message'] = $token->message;
    		}
    		elseif (!empty($token->errors) && !empty($token->errors[0]) && !empty($token->errors[0]->message)) {
    			$data['status'] = 400;
		        $data['message'] = $token->errors[0]->message;
    		}
    		else{
    			$realprice   = (int)Secure($_POST[ 'price' ]);
	            $amount      = 0;
	            if ($realprice == self::Config()->bag_of_credits_price) {
	                $amount = self::Config()->bag_of_credits_amount;
	            } else if ($realprice == self::Config()->box_of_credits_price) {
	                $amount = self::Config()->box_of_credits_amount;
	            } else if ($realprice == self::Config()->chest_of_credits_price) {
	                $amount = self::Config()->chest_of_credits_amount;
	            }
	            $successURL = SeoUri('aj/ngenius/success?credit='.$amount);
	            //$successURL = 'http://192.168.1.108/quick/aj/ngenius/success?credit=1000';
    			$postData = new StdClass();
			    $postData->action = "SALE";
			    $postData->amount = new StdClass();
			    $postData->amount->currencyCode = "AED";
			    $postData->amount->value = $realprice;
			    $postData->merchantAttributes = new \stdClass();
		        $postData->merchantAttributes->redirectUrl = $successURL;
			    $order = CreateNgeniusOrder($token->access_token,$postData);
			    if (!empty($order->message)) {
	    			$data['status'] = 400;
			        $data['message'] = $order->message;
	    		}
	    		elseif (!empty($order->errors) && !empty($order->errors[0]) && !empty($order->errors[0]->message)) {
	    			$data['status'] = 400;
			        $data['message'] = $order->errors[0]->message;
	    		}
	    		else{
	    			$db->where('id',self::ActiveUser()->id)->update('users',array('ngenius_ref' => $order->reference));
	    			$data['status'] = 200;
			        $data['url'] = $order->_links->payment->href;
	    		}
    		}
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
    	if (!empty($_GET['ref']) && !empty($_GET['credit'])) {
    		$user = $db->objectBuilder()->where('ngenius_ref',Secure($_GET['ref']))->getOne('users');
    		if (!empty($user)) {
    			$token = GetNgeniusToken();
	    		if (!empty($token->message)) {
	    			header('Location: ' . self::Config()->uri . '/credit');
		        	exit();
	    		}
	    		elseif (!empty($token->errors) && !empty($token->errors[0]) && !empty($token->errors[0]->message)) {
	    			header('Location: ' . self::Config()->uri . '/credit');
		        	exit();
	    		}
	    		else{
	    			$order = NgeniusCheckOrder($token->access_token,$user->ngenius_ref);
	    			if (!empty($order->message)) {
		    			header('Location: ' . self::Config()->uri . '/credit');
			        	exit();
		    		}
		    		elseif (!empty($order->errors) && !empty($order->errors[0]) && !empty($order->errors[0]->message)) {
		    			header('Location: ' . self::Config()->uri . '/credit');
			        	exit();
		    		}
		    		else{
		    			if ($order->_embedded->payment[0]->state == "CAPTURED") {
		    				$update_data = array();
							$price = Secure($order->amount->value);
			                $amount = Secure($_GET['credit']);
			                $newbalance = $user->balance + $amount;
			                $update_data['balance'] = $newbalance;
			                $update_data['ngenius_ref'] = '';
			                $updated    = $db->where('id', $user->id)->update('users', $update_data);
			                if ($updated) {
			                    RegisterAffRevenue($user->id,$price);
			                    $db->insert('payments', array(
			                        'user_id' => $user->id,
			                        'amount' => $price,
			                        'type' => 'CREDITS',
			                        'pro_plan' => '0',
			                        'credit_amount' => $amount,
			                        'via' => 'Ngenius'
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
	    		}
    		}
    	}
    	header('Location: ' . self::Config()->uri . '/credit');
		exit();
    }
    public function get_pro()
    {
    	global $db;
    	if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
    		$token = GetNgeniusToken();
    		if (!empty($token->message)) {
    			$data['status'] = 400;
		        $data['message'] = $token->message;
    		}
    		elseif (!empty($token->errors) && !empty($token->errors[0]) && !empty($token->errors[0]->message)) {
    			$data['status'] = 400;
		        $data['message'] = $token->errors[0]->message;
    		}
    		else{
    			$price   = (int)Secure($_POST[ 'price' ]);
	            $amount      = 0;
	            $membershipType      = 0;
	            if ($price == self::Config()->weekly_pro_plan) {
	                $membershipType = 1;
	            } else if ($price == self::Config()->monthly_pro_plan) {
	                $membershipType = 2;
	            } else if ($price == self::Config()->yearly_pro_plan) {
	                $membershipType = 3;
	            } else if ($price == self::Config()->lifetime_pro_plan) {
	                $membershipType = 4;
	            }
	            $successURL = SeoUri('aj/ngenius/success?membershipType='.$membershipType);
	            // $successURL = 'http://192.168.1.108/quick/aj/ngenius/pro_success?membershipType='.$membershipType;
    			$postData = new StdClass();
			    $postData->action = "SALE";
			    $postData->amount = new StdClass();
			    $postData->amount->currencyCode = "AED";
			    $postData->amount->value = $price;
			    $postData->merchantAttributes = new \stdClass();
		        $postData->merchantAttributes->redirectUrl = $successURL;
			    $order = CreateNgeniusOrder($token->access_token,$postData);
			    if (!empty($order->message)) {
	    			$data['status'] = 400;
			        $data['message'] = $order->message;
	    		}
	    		elseif (!empty($order->errors) && !empty($order->errors[0]) && !empty($order->errors[0]->message)) {
	    			$data['status'] = 400;
			        $data['message'] = $order->errors[0]->message;
	    		}
	    		else{
	    			$db->where('id',self::ActiveUser()->id)->update('users',array('ngenius_ref' => $order->reference));
	    			$data['status'] = 200;
			        $data['url'] = $order->_links->payment->href;
	    		}
    		}
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
    	if (!empty($_GET['ref']) && !empty($_GET['membershipType'])) {
    		$user = $db->objectBuilder()->where('ngenius_ref',Secure($_GET['ref']))->getOne('users');
    		if (!empty($user)) {
    			$token = GetNgeniusToken();
	    		if (!empty($token->message)) {
	    			header('Location: ' . self::Config()->uri . '/pro');
		        	exit();
	    		}
	    		elseif (!empty($token->errors) && !empty($token->errors[0]) && !empty($token->errors[0]->message)) {
	    			header('Location: ' . self::Config()->uri . '/pro');
		        	exit();
	    		}
	    		else{
	    			$order = NgeniusCheckOrder($token->access_token,$user->ngenius_ref);
	    			if (!empty($order->message)) {
		    			header('Location: ' . self::Config()->uri . '/pro');
			        	exit();
		    		}
		    		elseif (!empty($order->errors) && !empty($order->errors[0]) && !empty($order->errors[0]->message)) {
		    			header('Location: ' . self::Config()->uri . '/pro');
			        	exit();
		    		}
		    		else{
		    			if ($order->_embedded->payment[0]->state == "CAPTURED") {
							$price = Secure($order->amount->value);
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
			                    RegisterAffRevenue($user->id,$price);
			                    $db->insert('payments', array(
			                        'user_id' => $user->id,
			                        'amount' => $price,
			                        'type' => 'PRO',
			                        'pro_plan' => $pro_type,
			                        'credit_amount' => '0',
			                        'via' => 'Ngenius'
			                    ));
			                    $_SESSION[ 'userEdited' ] = true;
			                    SuperCache::cache('pro_users')->destroy();
			                    header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=pro');
			        			exit();
			                }
			                header('Location: ' . self::Config()->uri . '/pro');
			        	    exit();
		    			}
		    		}
	    		}
    		}
    	}
    	header('Location: ' . self::Config()->uri . '/pro');
		exit();
    }
}