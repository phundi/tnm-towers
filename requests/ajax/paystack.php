<?php
Class Paystack extends Aj {
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
				if ($price == self::Config()->weekly_pro_plan) {
		            $membershipType = 1;
		        } else if ($price == self::Config()->monthly_pro_plan) {
		            $membershipType = 2;
		        } else if ($price == self::Config()->yearly_pro_plan) {
		            $membershipType = 3;
		        } else if ($price == self::Config()->lifetime_pro_plan) {
		            $membershipType = 4;
		        }
		        $callback_url = SeoUri('aj/paystack/payment?pay_type=go_pro&membershipType='.$membershipType.'&price='.$price);
			}
			elseif ($_POST['type'] == 'credit') {
				if ($price == self::Config()->bag_of_credits_price) {
	                $amount = self::Config()->bag_of_credits_amount;
	            } else if ($price == self::Config()->box_of_credits_price) {
	                $amount = self::Config()->box_of_credits_amount;
	            } else if ($price == self::Config()->chest_of_credits_price) {
	                $amount = self::Config()->chest_of_credits_amount;
	            }
	            $callback_url = SeoUri('aj/paystack/payment?pay_type=credit&amount='.$amount.'&price='.$price);
			}
			elseif ($_POST['type'] == 'unlock_private_photo') {
				if ((int)$price == (int)self::Config()->lock_private_photo_fee) {
	                $amount = (int)self::Config()->lock_private_photo_fee;
	            }
	            $callback_url = SeoUri('aj/paystack/payment?pay_type=unlock_private_photo&price='.$price);
			}
			elseif ($_POST['type'] == 'lock_pro_video') {
				if ((int)$price == (int)self::Config()->lock_pro_video_fee) {
					$amount = (int)self::Config()->lock_pro_video_fee;
				}
				$callback_url = SeoUri('aj/paystack/payment?pay_type=lock_pro_video&price='.$price);
			}
			if (!empty(self::Config()->currency_array) && in_array(self::Config()->paystack_currency, self::Config()->currency_array) && self::Config()->paystack_currency != self::Config()->currency && !empty(self::Config()->exchange) && !empty(self::Config()->exchange[self::Config()->paystack_currency])) {
                $price = (($price * self::Config()->exchange[self::Config()->paystack_currency]));
            }
			$price = $price * 100;
			$result = array();
		    $reference = uniqid();

			//Set other parameters as keys in the $postdata array
			$postdata =  array('email' => $_POST['email'], 'amount' => $price,"reference" => $reference,'callback_url' => $callback_url,'currency' => self::Config()->paystack_currency);
			$url = "https://api.paystack.co/transaction/initialize";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$headers = [
			  'Authorization: Bearer '.self::Config()->paystack_secret_key,
			  'Content-Type: application/json',

			];
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$request = curl_exec ($ch);

			curl_close ($ch);

			if ($request) {
			    $result = json_decode($request, true);
			    if (!empty($result)) {
					 if (!empty($result['status']) && $result['status'] == 1 && !empty($result['data']) && !empty($result['data']['authorization_url']) && !empty($result['data']['access_code'])) {
					 	$db->where('id',self::ActiveUser()->id)->update('users',array('paystack_ref' => $reference));
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
    	$payment  = CheckPaystackPayment($_GET['reference']);
    	if ($payment) {
    		$price = intval(Secure($_GET['price']));
    		if ($_GET['pay_type'] == 'credit') {
    			$amount = intval(Secure($_GET['amount']));
    			$user           = $db->objectBuilder()->where('id', self::ActiveUser()->id)->getOne('users', array('balance'));
				$newbalance = $user->balance + $amount;
                $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('balance' => $newbalance));
                if ($updated) {
                    RegisterAffRevenue(self::ActiveUser()->id,$price);
                    $db->insert('payments', array(
                        'user_id' => self::ActiveUser()->id,
                        'amount' => $price,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'paystack'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    $url = $config->uri . '/ProSuccess';
                    if (!empty($_COOKIE['redirect_page'])) {
                        $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                        $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                    }
                    header('Location: ' . $url);
                    exit();
                } else {
                	exit(__('Error While update balance after charging'));
                }
    		}
    		elseif ($_GET['pay_type'] == 'go_pro') {
    			if (!empty($_GET['membershipType']) && in_array($_GET['membershipType'], array(1,2,3,4))) {
    				$membershipType = Secure($_GET['membershipType']);
	    			$protime                = time();
			        $is_pro                 = "1";
			        $pro_type               = $membershipType;
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
			                'via' => 'paystack'
			            ));
			            $_SESSION[ 'userEdited' ] = true;
			            SuperCache::cache('pro_users')->destroy();
			            header('Location: ' . $config->uri . '/ProSuccess?paymode=pro');
	                    exit();
			        } else {
			        	exit(__('Error While make you pro'));
			        }
    			}
    			else{
    				exit(__('Error While make you pro'));
    			}
	    			
    		}
    		elseif ($_GET['pay_type'] == 'unlock_private_photo') {
    			$updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_private_photo' => 0));
                if ($updated) {
                    $db->insert('payments', array(
                        'user_id' => self::ActiveUser()->id,
                        'amount' => $price,
                        'type' => 'unlock_private_photo',
                        'pro_plan' => '0',
                        'credit_amount' => '0',
                        'via' => 'paystack'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    header('Location: ' . $config->uri . '/ProSuccess?paymode=unlock');
                    exit();
                } else {
                	exit(__('Error While update Unlock private photo charging'));
                }
    		}
    		elseif ($_GET['pay_type'] == 'lock_pro_video') {
    			$updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_pro_video' => 0));
                if ($updated) {
                    $db->insert('payments', array(
                        'user_id' => self::ActiveUser()->id,
                        'amount' => $price,
                        'type' => 'lock_pro_video',
                        'pro_plan' => '0',
                        'credit_amount' => '0',
                        'via' => 'paystack'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    header('Location: ' . $config->uri . '/ProSuccess?paymode=unlock');
                    exit();
                } else {
                    exit(__('Error While update Unlock private photo charging'));
                }
    		}

    	}
    }
}