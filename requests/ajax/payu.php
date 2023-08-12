<?php 
Class Payu extends Aj {
	public function pay(){
		global $db,$config,$_LIBS;
		$response = array();
		$response['status'] = 400;
		if (!empty($_POST['card_number']) && !empty($_POST['card_month']) && !empty($_POST['card_year']) && !empty($_POST['card_cvc']) && !empty($_POST['type']) && !empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0 && !empty($_POST['description']) && in_array($_POST['type'], array('go_pro','credit','unlock_private_photo','lock_pro_video'))) {
			require_once($_LIBS . "PayU.php");
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
			}
			elseif ($_POST['type'] == 'credit') {
				if ($price == self::Config()->bag_of_credits_price) {
	                $amount = self::Config()->bag_of_credits_amount;
	            } else if ($price == self::Config()->box_of_credits_price) {
	                $amount = self::Config()->box_of_credits_amount;
	            } else if ($price == self::Config()->chest_of_credits_price) {
	                $amount = self::Config()->chest_of_credits_amount;
	            }
			}
			elseif ($_POST['type'] == 'unlock_private_photo') {
				if ((int)$price == (int)self::Config()->lock_private_photo_fee) {
	                $amount = (int)self::Config()->lock_private_photo_fee;
	            }
			}
			elseif ($_POST['type'] == 'lock_pro_video') {
				if ((int)$price == (int)self::Config()->lock_pro_video_fee) {
					$amount = (int)self::Config()->lock_pro_video_fee;
				}
			}
			$arParams['ORDER_PNAME[0]'] = Secure($_POST['description']);
			$arParams['ORDER_PRICE[0]'] = $price;
			$arParams['CC_NUMBER'] = $_POST['card_number'];
			$arParams['EXP_MONTH'] = $_POST['card_month'];
			$arParams['EXP_YEAR'] = $_POST['card_year'];
			$arParams['CC_CVV'] = $_POST['card_cvc'];
			$info = PayUPayment($arParams);
			if ($info['status'] == 200) {
				if ($_POST['type'] == 'go_pro') {
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
			                'via' => 'payu'
			            ));
			            $_SESSION[ 'userEdited' ] = true;
			            SuperCache::cache('pro_users')->destroy();
			            $response['url'] = self::Config()->uri . '/ProSuccess?paymode=pro';
			            $response['status'] = 200;
			        } else {
			        	$response['message'] = __('Error While make you pro');
			            $response['status'] = 400;
			        }
				}
				elseif ($_POST['type'] == 'credit') {
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
	                        'via' => 'payu'
	                    ));
	                    $_SESSION[ 'userEdited' ] = true;
	                    $response[ 'credit_amount' ]  = (int) $newbalance;
	                    $response['url'] = self::Config()->uri . '/ProSuccess';
			            $response['status'] = 200;
	                } else {
	                	$response['message'] = __('Error While update balance after charging');
			            $response['status'] = 400;
	                }
				}
				elseif ($_POST['type'] == 'unlock_private_photo') {
					$updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_private_photo' => 0));
	                if ($updated) {
	                    $db->insert('payments', array(
	                        'user_id' => self::ActiveUser()->id,
	                        'amount' => $price,
	                        'type' => 'unlock_private_photo',
	                        'pro_plan' => '0',
	                        'credit_amount' => '0',
	                        'via' => 'payu'
	                    ));
	                    $_SESSION[ 'userEdited' ] = true;
	                    $response['url'] = self::Config()->uri . '/ProSuccess?paymode=unlock';
			            $response['status'] = 200;
	                } else {
	                    $response['message'] = __('Error While update Unlock private photo charging');
			            $response['status'] = 400;
	                }
				}
				elseif ($_POST['type'] == 'lock_pro_video') {
					//done
	                $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_pro_video' => 0));
	                if ($updated) {
	                    $db->insert('payments', array(
	                        'user_id' => self::ActiveUser()->id,
	                        'amount' => $price,
	                        'type' => 'lock_pro_video',
	                        'pro_plan' => '0',
	                        'credit_amount' => '0',
	                        'via' => 'payu'
	                    ));
	                    $_SESSION[ 'userEdited' ] = true;
	                    $response['url'] = self::Config()->uri . '/ProSuccess?paymode=unlock';
			            $response['status'] = 200;
	                } else {
	                    $response['message'] = __('Error While update Unlock private photo charging');
			            $response['status'] = 400;
	                }
				}
			}
			else{
				$response['message'] = $info['error'];
			}
		}
		else{
			$response['message'] = __('please check your details');
		}
		return $response;
	}
}

