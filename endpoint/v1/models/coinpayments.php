<?php
Class Coinpayments {
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
        if (route(4) == 'get') {
            json($this->get());
        }
        if (route(4) == 'approve') {
            json($this->approve());
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

	public function get()
    {
    	global $db,$config,$_LIBS;
    	if (!empty(Auth()->coinpayments_txn_id)) {
    		return json(array(
                'message' => 'you have a pending request please try again later',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '12',
                    'error_text' => 'you have a pending request please try again later'
                )
            ), 400);
        }
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
                                                  'buyer_email' => Auth()->email));

            
            if (!empty($result) && $result['status'] == 200) {
                $db->where('id',Auth()->id)->update('users',array('coinpayments_txn_id' => $result['data']['txn_id']));
                return json(array(
	                'url' => $result['data']['checkout_url'],
	                'code' => 200
	            ), 200);
            }
            else{
                return json(array(
	                'message' => $result['message'],
	                'code' => 400,
	                'errors'         => array(
	                    'error_id'   => '14',
	                    'error_text' => $result['message']
	                )
	            ), 400);
            }
        }
        else{
            return json(array(
                'message' => 'no price passed',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '13',
                    'error_text' => 'no price passed'
                )
            ), 400);
        }
    }
    public function approve()
    {
    	global $db,$config,$_LIBS;
    	if (!empty(Auth()->coinpayments_txn_id)) {
            $result = coinpayments_api_call(array('key' => $config->coinpayments_public_key,
                                                  'version' => '1',
                                                  'format' => 'json',
                                                  'cmd' => 'get_tx_info',
                                                  'full' => '1',
                                                  'txid' => Auth()->coinpayments_txn_id));
            if (!empty($result) && $result['status'] == 200) {
                if ($result['data']['status'] == -1) {
                    $db->where('id',Auth()->id)->update('users',array('coinpayments_txn_id' => ''));
                    $notifications->createNotification(Auth()->web_device_id, Auth()->id, Auth()->id, 'coinpayments_canceled', __('coinpayments_canceled'), '/credit');
                    return json(array(
                        'message' => 'coinpayments canceled',
                        'code' => 200
                    ), 200);
                }
                elseif ($result['data']['status'] == 100) {
                    $db->where('id',Auth()->id)->update('users',array('coinpayments_txn_id' => '',
                                                                                  'balance' => $db->inc($result['data']['checkout']['custom'])));
                    $price = $result['data']['checkout']['amountf'];
                    RegisterAffRevenue(Auth()->id,$price);
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => $price,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $result['data']['checkout']['custom'],
                        'via' => 'CoinPayments'
                    ));
                    $_SESSION[ 'userEdited' ] = true;

                    $notifications->createNotification(Auth()->web_device_id, Auth()->id, Auth()->id, 'coinpayments_approved', __('coinpayments_approved'), '/credit');
                    return json(array(
                        'message' => 'SUCCESS',
                        'code' => 200
                    ), 200);
                }
            }
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
        	return json(array(
                'message' => 'no txn_id passed',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'no txn_id passed'
                )
            ), 400);
        }
    }
}