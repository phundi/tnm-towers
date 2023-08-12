<?php
Class Wallet extends Aj {
	public function pay()
    {
    	global $db;
    	if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $data['status']  = 400;
    	if (!empty($_GET['pay_for']) && in_array($_GET['pay_for'], array('pro','private_photo','private_video'))) {
    		if ($_GET['pay_for'] == 'pro' && !empty($_GET['price']) && is_numeric($_GET['price'])) {
    			$price = intval($_GET['price']);
    			$membershipType = 0;
    			if ($price == self::Config()->weekly_pro_plan) {
                    $membershipType = 1;
                } else if ($price == self::Config()->monthly_pro_plan) {
                    $membershipType = 2;
                } else if ($price == self::Config()->yearly_pro_plan) {
                    $membershipType = 3;
                } else if ($price == self::Config()->lifetime_pro_plan) {
                    $membershipType = 4;
                }
                if ($membershipType > 0) {
                	if (intval(self::ActiveUser()->balance) < ($price * self::Config()->credit_price)) {
                		$data['message'] = "<a href='" . self::Config()->uri . "/credit'>" . __('please_top_up_credits') . "</a>";
                	}
                	else{
                		$protime                = time();
                        $is_pro                 = "1";
                        $pro_type               = $membershipType;
                        $updated                = $db->where('id', self::ActiveUser()->id)->update('users', array(
                            'pro_time' => $protime,
                            'is_pro' => $is_pro,
                            'pro_type' => $pro_type,
                            'balance' => $db->dec(($price * self::Config()->credit_price))
                        ));
                        if ($updated) {
                            RegisterAffRevenue(self::ActiveUser()->id,$price);
                            $db->insert('payments', array(
                                'user_id' => self::ActiveUser()->id,
                                'amount' => $price,
                                'type' => 'PRO',
                                'pro_plan' => $membershipType,
                                'credit_amount' => '0',
                                'via' => 'Credits'
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                            SuperCache::cache('pro_users')->destroy();
                        }
                        $data[ 'url' ] = self::Config()->uri . '/ProSuccess?paymode=pro';
                        $data['status'] = 200;

                	}
                }
    		}
    		else if ($_GET['pay_for'] == 'private_photo') {
    			$price = ((int)self::Config()->lock_private_photo_fee  * self::Config()->credit_price);
    			$updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_private_photo' => 0,
    		                                                                                  'balance' => $db->dec($price)));
	            if ($updated) {
	                $db->insert('payments', array(
	                    'user_id' => self::ActiveUser()->id,
	                    'amount' => (int)self::Config()->lock_private_photo_fee,
	                    'type' => 'unlock_private_photo',
	                    'pro_plan' => '0',
	                    'credit_amount' => '0',
	                    'via' => 'Credits'
	                ));
	            }
	            $_SESSION[ 'userEdited' ] = true;
	            $data[ 'url' ] = self::Config()->uri . '/ProSuccess?paymode=unlock';
                $data['status'] = 200;
    		}
    		else if ($_GET['pay_for'] == 'private_video') {
    			$price = ((int)self::Config()->lock_pro_video_fee  * self::Config()->credit_price);
    			$updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_pro_video' => 0,
    		                                                                                  'balance' => $db->dec($price)));
	            if ($updated) {
	                $db->insert('payments', array(
	                    'user_id' => self::ActiveUser()->id,
	                    'amount' => (int)self::Config()->lock_pro_video_fee,
	                    'type' => 'lock_pro_video',
	                    'pro_plan' => '0',
	                    'credit_amount' => '0',
	                    'via' => 'Credits'
	                ));
	            }
	            $_SESSION[ 'userEdited' ] = true;
	            $data[ 'url' ] = self::Config()->uri . '/ProSuccess?paymode=unlock';
                $data['status'] = 200;
    		}
    	}
    	else{
    		$data['status']  = 400;
	        $data['message'] = __('No payType');
    	}
    	return $data;
    }
    public function set()
    {
    	if (!empty($_GET['type']) && in_array($_GET['type'], array(
            'pro',
            'private_photo'
        ))) {
            if ($_GET['type'] == 'pro') {
                setcookie("redirect_page", self::Config()->uri . '/pro', time() + (60 * 60), '/');
            } else if ($_GET['type'] == 'private_photo') {
               setcookie("redirect_page", self::Config()->uri . '/@'.self::ActiveUser()->username, time() + (60 * 60), '/');
            }
        }
    	$data['status']  = 200;
    	return $data;
    }
}