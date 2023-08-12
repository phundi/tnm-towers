<?php
Class Wallet {
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
    public function pay()
    {
        global $db,$config,$_LIBS;
        if (!empty($_POST['pay_for']) && in_array($_POST['pay_for'], array('pro','private_photo','private_video'))) {
            if ($_POST['pay_for'] == 'pro') {
                if (!empty($_POST['price']) && is_numeric($_POST['price'])) {
                    $price = intval($_POST['price']);
                    $membershipType = 0;
                    if ($price == $config->weekly_pro_plan) {
                        $membershipType = 1;
                    } else if ($price == $config->monthly_pro_plan) {
                        $membershipType = 2;
                    } else if ($price == $config->yearly_pro_plan) {
                        $membershipType = 3;
                    } else if ($price == $config->lifetime_pro_plan) {
                        $membershipType = 4;
                    }
                    if ($membershipType > 0) {
                        if (intval(Auth()->balance) < ($price * $config->credit_price)) {
                            return json(array(
                                'message' => 'please top up credits',
                                'code' => 400,
                                'errors'         => array(
                                    'error_id'   => '16',
                                    'error_text' => 'please top up credits'
                                )
                            ), 400);
                        }
                        else{
                            $protime                = time();
                            $is_pro                 = "1";
                            $pro_type               = $membershipType;
                            $updated                = $db->where('id', Auth()->id)->update('users', array(
                                'pro_time' => $protime,
                                'is_pro' => $is_pro,
                                'pro_type' => $pro_type,
                                'balance' => $db->dec(($price * $config->credit_price))
                            ));
                            if ($updated) {
                                RegisterAffRevenue(Auth()->id,$price);
                                $db->insert('payments', array(
                                    'user_id' => Auth()->id,
                                    'amount' => $price,
                                    'type' => 'PRO',
                                    'pro_plan' => $membershipType,
                                    'credit_amount' => '0',
                                    'via' => 'Credits'
                                ));
                                $_SESSION[ 'userEdited' ] = true;
                                SuperCache::cache('pro_users')->destroy();
                            }
                            return json(array(
                                'message' => 'SUCCESS',
                                'code' => 200
                            ), 200);
                        }
                    }
                }
                else{
                    return json(array(
                        'message' => 'price can not be empty',
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '15',
                            'error_text' => 'price can not be empty'
                        )
                    ), 400);
                } 
            }
            else if ($_POST['pay_for'] == 'private_photo') {
                $price = ((int)$config->lock_private_photo_fee  * $config->credit_price);
                $updated    = $db->where('id', Auth()->id)->update('users', array('lock_private_photo' => 0,
                                                                                              'balance' => $db->dec($price)));
                if ($updated) {
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => (int)$config->lock_private_photo_fee,
                        'type' => 'unlock_private_photo',
                        'pro_plan' => '0',
                        'credit_amount' => '0',
                        'via' => 'Credits'
                    ));
                }
                $_SESSION[ 'userEdited' ] = true;
                return json(array(
                    'message' => 'SUCCESS',
                    'code' => 200
                ), 200);
            }
            else if ($_POST['pay_for'] == 'private_video') {
                $price = ((int)$config->lock_pro_video_fee  * $config->credit_price);
                $updated    = $db->where('id', Auth()->id)->update('users', array('lock_pro_video' => 0,
                                                                                              'balance' => $db->dec($price)));
                if ($updated) {
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => (int)$config->lock_pro_video_fee,
                        'type' => 'lock_pro_video',
                        'pro_plan' => '0',
                        'credit_amount' => '0',
                        'via' => 'Credits'
                    ));
                }
                $_SESSION[ 'userEdited' ] = true;
                return json(array(
                    'message' => 'SUCCESS',
                    'code' => 200
                ), 200);
            }
        }
        else{
            return json(array(
                'message' => 'pay_for can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'pay_for can not be empty'
                )
            ), 400);
        }
    }
}