<?php
Class Coinbase extends Aj {
    public function create()
    {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!empty($_POST[ 'price' ]) && is_numeric($_POST[ 'price' ]) && $_POST[ 'price' ] > 0) {
            $realprice   = (int)Secure($_POST[ 'price' ]);
            $amount      = 0;
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }


            try {
                $coinbase_hash = rand(1111,9999).rand(11111,99999);
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.commerce.coinbase.com/charges');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                $postdata =  array('name' => 'Top Up Wallet','description' => 'Top Up Wallet','pricing_type' => 'fixed_price','local_price' => array('amount' => $realprice , 'currency' => self::Config()->currency), 'metadata' => array('coinbase_hash' => $coinbase_hash,'amount' => $amount),"redirect_url" => SeoUri('aj/coinbase/coinbase_handle?coinbase_hash='.$coinbase_hash.'&credit='.$amount),'cancel_url' => SeoUri('aj/coinbase/coinbase_cancel?coinbase_hash='.$coinbase_hash.'&credit='.$amount));


                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'X-Cc-Api-Key: '.self::Config()->coinbase_key;
                $headers[] = 'X-Cc-Version: 2018-03-22';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    $data = array(
                        'status' => 400,
                        'message' => curl_error($ch)
                    );
                }
                curl_close($ch);

                $result = json_decode($result,true);
                if (!empty($result) && !empty($result['data']) && !empty($result['data']['hosted_url']) && !empty($result['data']['id']) && !empty($result['data']['code'])) {
                    $db->where('id', self::ActiveUser()->id)->update('users', array('coinbase_hash' => $coinbase_hash,
                                                                                    'coinbase_code' => $result['data']['code']));
                    $data['status'] = 200;
                    $data['url'] = $result['data']['hosted_url'];
                }
            }
            catch (Exception $e) {
                $data = array(
                    'status' => 400,
                    'message' => $e->getMessage()
                );
            }
        }
        else{
            $data = array(
                'status' => 400,
                'message' => __('no_amount_passed')
            );
        }
        return $data;
    }
    public function coinbase_handle()
    {
        global $db;
        if (!empty($_GET['coinbase_hash']) && is_numeric($_GET['coinbase_hash']) && !empty($_GET['credit']) && is_numeric($_GET['credit'])) {
            $coinbase_hash = Secure($_GET['coinbase_hash']);
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
                    header('Location: ' . self::Config()->uri . '/credit');
                    exit();
                }
                curl_close($ch);

                $result = json_decode($result,true);
                $update_data = array('coinbase_hash' => '',
                                     'coinbase_code' => '');
                if (!empty($result) && !empty($result['data']) && !empty($result['data']['pricing']) && !empty($result['data']['pricing']['local']) && !empty($result['data']['pricing']['local']['amount']) && !empty($result['data']['payments']) && !empty($result['data']['payments'][0]['status']) && $result['data']['payments'][0]['status'] == 'CONFIRMED') {
                    
                    $price = (int)$result['data']['pricing']['local']['amount'];
                    $amount = Secure($_GET['credit']);
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
                        $url = $config->uri . '/ProSuccess';
                        if (!empty($_COOKIE['redirect_page'])) {
                            $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                            $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                        }
                        header('Location: ' . $url);
                    }
                }
            }
        }
        header('Location: ' . self::Config()->uri . '/credit');
        exit();
    }
    public function coinbase_cancel()
    {
        global $db;
        if (!empty($_GET['coinbase_hash']) && is_numeric($_GET['coinbase_hash'])) {
            $coinbase_hash = Secure($_GET['coinbase_hash']);
            $user = $db->where('coinbase_hash',$coinbase_hash)->getOne('users');
            if (!empty($user)) {
                $db->where('id', $user->id)->update('users', array('coinbase_hash' => '',
                                                                   'coinbase_code' => ''));
            }
        }
        header('Location: ' . self::Config()->uri . '/credit');
        exit();
    }
    public function create_pro()
    {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!empty($_POST[ 'price' ]) && is_numeric($_POST[ 'price' ]) && $_POST[ 'price' ] > 0) {
            $price   = (int)Secure($_POST[ 'price' ]);
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


            try {
                $coinbase_hash = rand(1111,9999).rand(11111,99999);
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.commerce.coinbase.com/charges');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                $postdata =  array('name' => 'Top Up Wallet','description' => 'Top Up Wallet','pricing_type' => 'fixed_price','local_price' => array('amount' => $price , 'currency' => self::Config()->currency), 'metadata' => array('coinbase_hash' => $coinbase_hash,'amount' => $price),"redirect_url" => SeoUri('aj/coinbase/pro_coinbase_handle?coinbase_hash='.$coinbase_hash.'&membershipType='.$membershipType),'cancel_url' => SeoUri('aj/coinbase/coinbase_cancel?coinbase_hash='.$coinbase_hash));


                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'X-Cc-Api-Key: '.self::Config()->coinbase_key;
                $headers[] = 'X-Cc-Version: 2018-03-22';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    $data = array(
                        'status' => 400,
                        'message' => curl_error($ch)
                    );
                }
                curl_close($ch);

                $result = json_decode($result,true);
                if (!empty($result) && !empty($result['data']) && !empty($result['data']['hosted_url']) && !empty($result['data']['id']) && !empty($result['data']['code'])) {
                    $db->where('id', self::ActiveUser()->id)->update('users', array('coinbase_hash' => $coinbase_hash,
                                                                                    'coinbase_code' => $result['data']['code']));
                    $data['status'] = 200;
                    $data['url'] = $result['data']['hosted_url'];
                }
            }
            catch (Exception $e) {
                $data = array(
                    'status' => 400,
                    'message' => $e->getMessage()
                );
            }
        }
        else{
            $data = array(
                'status' => 400,
                'message' => __('no_amount_passed')
            );
        }
        return $data;
    }
    public function pro_coinbase_handle()
    {
        global $db;
        if (!empty($_GET['coinbase_hash']) && is_numeric($_GET['coinbase_hash']) && !empty($_GET['credit']) && is_numeric($_GET['credit'])) {
            $coinbase_hash = Secure($_GET['coinbase_hash']);
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
                    header('Location: ' . self::Config()->uri . '/credit');
                    exit();
                }
                curl_close($ch);

                $result = json_decode($result,true);
                $update_data = array('coinbase_hash' => '',
                                     'coinbase_code' => '');
                if (!empty($result) && !empty($result['data']) && !empty($result['data']['pricing']) && !empty($result['data']['pricing']['local']) && !empty($result['data']['pricing']['local']['amount']) && !empty($result['data']['payments']) && !empty($result['data']['payments'][0]['status']) && $result['data']['payments'][0]['status'] == 'CONFIRMED') {

                    $price = (int)$result['data']['pricing']['local']['amount'];
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
                            'via' => 'Coinbase'
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