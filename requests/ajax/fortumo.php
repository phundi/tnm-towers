<?php
Class Fortumo extends Aj {
	public function get()
    {
    	global $db, $config, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (self::Config()->fortumo_payment != 1) {
        	return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $hash = rand(11111,55555).rand(11111,55555);
        if (!empty($_GET['pay_type']) && $_GET['pay_type'] == 'pro') {
            $hash .= '_pro';
        }
        $db->where('id',self::ActiveUser()->id)->update('users',array('fortumo_hash' => $hash));
        return array(
            'status' => 200,
            'url' => 'https://pay.fortumo.com/mobile_payments/'.self::Config()->fortumo_service_id.'?cuid='.$hash
        );
        
    }
    public function success()
    {
    	global $db, $config, $_BASEPATH, $_DS;
        
        if (self::Config()->fortumo_payment != 1) {
        	header('Location: ' . self::Config()->uri . '/credit');
        	exit();
        }
        if (!empty($_GET) && !empty($_GET['amount']) && !empty($_GET['status']) && $_GET['status'] == 'completed' && !empty($_GET['cuid']) && !empty($_GET['price'])) {
            $fortumo_hash = Secure($_GET['cuid']);
            $amount = (int) Secure($_GET['amount']);
            $price = (int) Secure($_GET['price']);
            $user = $db->objectBuilder()->where('fortumo_hash',$fortumo_hash)->getOne('users');
            if (!empty($user)) {
                if (strpos($fortumo_hash, '_pro') !== false) {
                    if ($price == self::Config()->weekly_pro_plan) {
                        $membershipType = 1;
                    } else if ($price == self::Config()->monthly_pro_plan) {
                        $membershipType = 2;
                    } else if ($price == self::Config()->yearly_pro_plan) {
                        $membershipType = 3;
                    } else if ($price == self::Config()->lifetime_pro_plan) {
                        $membershipType = 4;
                    }

                    $response[ 'location' ] = '/ProSuccess?mode=pro';
                    $protime                = time();
                    $is_pro                 = "1";
                    $pro_type               = $membershipType;
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
                            'pro_plan' => $membershipType,
                            'credit_amount' => '0',
                            'via' => 'Fortumo'
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
                else{
                    $db->where('fortumo_hash',$fortumo_hash)->update('users',array('fortumo_hash' => '',
                                                                           'balance' => $db->inc($amount)));
                    RegisterAffRevenue($user->id,$price);
                    $db->insert('payments', array(
                        'user_id' => $user->id,
                        'amount' => $price,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'Fortumo'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    $url = $config->uri . '/ProSuccess';
                    if (!empty($_COOKIE['redirect_page'])) {
                        $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                        $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                    }
                    header('Location: ' . $url);
                    exit();
                }
            }
        }

        header('Location: ' . self::Config()->uri . '/credit');
        exit();
    }
}