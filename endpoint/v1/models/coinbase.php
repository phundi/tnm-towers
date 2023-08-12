<?php
Class Coinbase {
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
        if (route(4) == 'coinbase_handle') {
            json($this->coinbase_handle());
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
        if (!empty($_POST[ 'price' ]) && is_numeric($_POST[ 'price' ]) && $_POST[ 'price' ] > 0) {
            $realprice   = (int)Secure($_POST[ 'price' ]);
            $amount      = 0;
            if ($realprice == $config->bag_of_credits_price) {
                $amount = $config->bag_of_credits_amount;
            } else if ($realprice == $config->box_of_credits_price) {
                $amount = $config->box_of_credits_amount;
            } else if ($realprice == $config->chest_of_credits_price) {
                $amount = $config->chest_of_credits_amount;
            }


            try {
                $coinbase_hash = rand(1111,9999).rand(11111,99999);
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.commerce.coinbase.com/charges');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                $postdata =  array('name' => 'Top Up Wallet','description' => 'Top Up Wallet','pricing_type' => 'fixed_price','local_price' => array('amount' => $realprice , 'currency' => $config->currency), 'metadata' => array('coinbase_hash' => $coinbase_hash,'amount' => $amount),"redirect_url" => SeoUri('aj/coinbase/coinbase_handle?coinbase_hash='.$coinbase_hash.'&credit='.$amount),'cancel_url' => SeoUri('aj/coinbase/coinbase_cancel?coinbase_hash='.$coinbase_hash.'&credit='.$amount));


                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'X-Cc-Api-Key: '.$config->coinbase_key;
                $headers[] = 'X-Cc-Version: 2018-03-22';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    return json(array(
		                'message' => curl_error($ch),
		                'code' => 400,
		                'errors'         => array(
		                    'error_id'   => '13',
		                    'error_text' => curl_error($ch)
		                )
		            ), 400);
                }
                curl_close($ch);

                $result = json_decode($result,true);
                if (!empty($result) && !empty($result['data']) && !empty($result['data']['hosted_url']) && !empty($result['data']['id']) && !empty($result['data']['code'])) {
                    $db->where('id', Auth()->id)->update('users', array('coinbase_hash' => $coinbase_hash,
                                                                                    'coinbase_code' => $result['data']['code']));
                    return json(array(
		                'url' => $result['data']['hosted_url'],
		                'code' => 200
		            ), 200);
                }
                return json(array(
	                'message' => 'no data found',
	                'code' => 400,
	                'errors'         => array(
	                    'error_id'   => '14',
	                    'error_text' => 'no data found'
	                )
	            ), 400);
            }
            catch (Exception $e) {
                return json(array(
	                'message' => $e->getMessage(),
	                'code' => 400,
	                'errors'         => array(
	                    'error_id'   => '11',
	                    'error_text' => $e->getMessage()
	                )
	            ), 400);
            }
        }
        else{
        	return json(array(
                'message' => 'no price passed',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '12',
                    'error_text' => 'no price passed'
                )
            ), 400);
        }
    }
    public function coinbase_handle()
    {
        global $db,$config,$_LIBS;
        if (!empty($_POST['coinbase_hash']) && is_numeric($_POST['coinbase_hash']) && !empty($_POST['credit']) && is_numeric($_POST['credit'])) {
            $coinbase_hash = Secure($_POST['coinbase_hash']);
            $user           = $db->objectBuilder()->where('coinbase_hash',$coinbase_hash)->getOne('users');

            if (!empty($user)) {


                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.commerce.coinbase.com/charges/'.$user->coinbase_code);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'X-Cc-Api-Key: '.self::Config()->coinbase_key;
                $headers[] = 'X-Cc-Version: 2018-03-22';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    return json(array(
                        'message' => curl_error($ch),
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '13',
                            'error_text' => curl_error($ch)
                        )
                    ), 400);
                }
                curl_close($ch);

                $result = json_decode($result,true);
                $update_data = array('coinbase_hash' => '',
                                     'coinbase_code' => '');
                if (!empty($result) && !empty($result['data']) && !empty($result['data']['pricing']) && !empty($result['data']['pricing']['local']) && !empty($result['data']['pricing']['local']['amount']) && !empty($result['data']['payments']) && !empty($result['data']['payments'][0]['status']) && $result['data']['payments'][0]['status'] == 'CONFIRMED') {
                    
                    $price = (int)$result['data']['pricing']['local']['amount'];
                    $amount = Secure($_POST['credit']);
                    $newbalance = $user->balance + $amount;
                    $update_data['balance'] = $newbalance;
                    $updated    = $db->where('id', $user->id)->update('users', $update_data);
                    if ($updated) {
                        RegisterAffRevenue($user->id,$price);
                        $db->insert('payments', array(
                            'user_id' => $user->id,
                            'amount' => $price,
                            'type' => 'CREDITS',
                            'pro_plan' => '0',
                            'credit_amount' => $amount,
                            'via' => 'Coinbase'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        $response[ 'credit_amount' ]  = (int) $newbalance;
                        return json(array(
                            'message' => 'SUCCESS',
                            'code' => 200
                        ), 200);
                    }
                }
            }
        }
        return json(array(
            'message' => 'something went wrong',
            'code' => 400,
            'errors'         => array(
                'error_id'   => '14',
                'error_text' => 'something went wrong'
            )
        ), 400);
    }




}