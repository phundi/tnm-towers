<?php
Class Coinpayments extends Aj {
	public function get()
    {
    	global $db, $config, $_BASEPATH, $_DS,$_LIBS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (self::Config()->coinpayments != 1) {
        	return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!empty(self::ActiveUser()->coinpayments_txn_id)) {
            return array(
                'status' => 403,
                'message' => __('pending_request_please_try')
            );
        }
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
            if (!empty($_GET['pay_type']) && $_GET['pay_type'] == 'pro') {
                if ($realprice == self::Config()->weekly_pro_plan) {
                    $membershipType = 1;
                } else if ($realprice == self::Config()->monthly_pro_plan) {
                    $membershipType = 2;
                } else if ($realprice == self::Config()->yearly_pro_plan) {
                    $membershipType = 3;
                } else if ($realprice == self::Config()->lifetime_pro_plan) {
                    $membershipType = 4;
                }
                $amount = 'pro_'.$membershipType;
            }
            if (empty($config->coinpayments_coin)) {
                $config->coinpayments_coin = 'BTC';
            }
            $result = coinpayments_api_call(array('key' => $config->coinpayments_public_key,
                                                  'version' => '1',
                                                  'format' => 'json',
                                                  'cmd' => 'create_transaction',
                                                  'amount' => $realprice,
                                                  'currency1' => $config->currency,
                                                  'currency2' => $config->coinpayments_coin,
                                                  'custom' => $amount,
                                                  'cancel_url' => SeoUri('aj/coinpayments/cancel'),
                                                  'buyer_email' => self::ActiveUser()->email));

            
            if (!empty($result) && $result['status'] == 200) {
                $db->where('id',self::ActiveUser()->id)->update('users',array('coinpayments_txn_id' => $result['data']['txn_id']));
                return array(
                    'status' => 200,
                    'url' => $result['data']['checkout_url']
                );
            }
            else{
                return array(
                    'status' => 403,
                    'message' => $result['message']
                );
            }
        }
        else{
            return array(
                'status' => 403,
                'message' => __('no_amount_passed')
            );
        }
    }
    public function cancel()
    {
        global $db, $config, $_BASEPATH, $_DS,$_LIBS;
        if (self::ActiveUser() == NULL) {
            header('Location: ' . self::Config()->uri . '/credit');
            exit();
        }
        $db->where('id',self::ActiveUser()->id)->update('users',array('coinpayments_txn_id' => ''));
        header('Location: ' . self::Config()->uri . '/credit');
        exit();
    }
}