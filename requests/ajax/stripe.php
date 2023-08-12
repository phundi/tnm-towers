<?php
global $config,$_LIBS;
$stripe = array(
    'secret_key' => $config->stripe_secret,
    'publishable_key' => $config->stripe_id
);
require_once $_LIBS . 'stripe/vendor/autoload.php';
$z= \Stripe\Stripe::setApiKey($stripe[ 'secret_key' ]);
Class Stripe extends Aj {
    public function createsession(){
        global $db,$config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_POST[ 'description' ])) {
            return array(
                'status' => 400,
                'message' => __('No description')
            );
        }
        if (empty($_POST[ 'payType' ])) {
            return array(
                'status' => 400,
                'message' => __('No payType')
            );
        }

        $product        = Secure($_POST[ 'description' ]);
        $realprice      = (int)Secure($_POST[ 'price' ]);
        $price          = (int)Secure($_POST[ 'price' ]) * 100;
        $amount         = 0;
        $membershipType         = 0;
        $currency       = self::Config()->stripe_currency;
        $payType        = Secure($_POST[ 'payType' ]);

        if ($payType == 'credits') {
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }
        } else if ($payType == 'membership') {
            if ($realprice == self::Config()->weekly_pro_plan) {
                $membershipType = 1;
            } else if ($realprice == self::Config()->monthly_pro_plan) {
                $membershipType = 2;
            } else if ($realprice == self::Config()->yearly_pro_plan) {
                $membershipType = 3;
            } else if ($realprice == self::Config()->lifetime_pro_plan) {
                $membershipType = 4;
            }
            $amount = $price;
        } else if ($payType == 'unlock_private_photo') {
            if ((int)$realprice == (int)self::Config()->lock_private_photo_fee) {
                $amount = (int)self::Config()->lock_private_photo_fee;
            }
        } else if ($payType == 'lock_pro_video'){
            //if ((int)$realprice == (int)self::Config()->lock_pro_video_fee) {
                $amount = (int)self::Config()->lock_pro_video_fee;
            //}
        }

        
        $payload = [
            'description' => $product,
            'realprice' => $realprice,
            'price' => $price,
            'amount' => $amount,
            'payType' => $payType,
            'membershipType' => $membershipType,
            'currency' => $currency
        ];


        $hash = base64_encode(serialize($payload));
        if (!empty(self::Config()->currency_array) && in_array(self::Config()->stripe_currency, self::Config()->currency_array) && self::Config()->stripe_currency != self::Config()->currency && !empty(self::Config()->exchange) && !empty(self::Config()->exchange[self::Config()->stripe_currency])) {
            $price = (int)(($price * self::Config()->exchange[self::Config()->stripe_currency]));
        }
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [ 'name' => $product ],
                        'unit_amount' => $price,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => SeoUri('aj/stripe/success?hash='.$hash),
            'cancel_url' => SeoUri('aj/stripe/cancel?hash='.$hash),
        ]);
        $data = array(
            'status' => 200,
            'session_id' => $session->id
        );
        return $data;
    }

    public function success(){
        global $db,$config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_GET[ 'hash' ])) {
            return array(
                'status' => 400,
                'message' => __('No hash')
            );
        }

        $data = unserialize(base64_decode(Secure($_GET[ 'hash' ])));

        $description    = $data['description'];
        $realprice      = $data['realprice'];
        $price          = $data['price'];
        $amount         = $data['amount'];
        $payType        = $data['payType'];
        $membershipType = $data['membershipType'];
        $currency       = $data['currency'];

        $user           = $db->objectBuilder()->where('id', self::ActiveUser()->id)->getOne('users', array('balance'));

        // var_dump($data);
        // var_dump($user);

        // $newbalance = $user->balance + $amount;
        // var_dump($newbalance);
        // exit();
        $response = array();
        $response[ 'status' ]   = 200;
        $response[ 'message' ]  = __('Payment successfully');
        $response[ 'location' ] = '/ProSuccess';

        if ($payType == 'credits') {
            //done
            $newbalance = $user->balance + $amount;
            $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array(
                'balance' => $newbalance
            ));
            if ($updated) {
                RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                $db->insert('payments', array(
                    'user_id' => self::ActiveUser()->id,
                    'amount' => $price / 100,
                    'type' => 'CREDITS',
                    'pro_plan' => '0',
                    'credit_amount' => $amount,
                    'via' => 'Stripe'
                ));
                $_SESSION[ 'userEdited' ] = true;
                $response[ 'credit_amount' ]  = (int) $newbalance;
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
        } else if ($payType == 'membership') {
            //done
            $response[ 'location' ] = '/ProSuccess?mode=pro';
            $protime                = time();
            $is_pro                 = "1";
            $pro_type               = $membershipType;
            $updated                = $db->where('id', self::ActiveUser()->id)->update('users', array(
                'pro_time' => $protime,
                'is_pro' => $is_pro,
                'pro_type' => $pro_type
            ));
            if ($updated) {
                RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                $db->insert('payments', array(
                    'user_id' => self::ActiveUser()->id,
                    'amount' => $price / 100,
                    'type' => 'PRO',
                    'pro_plan' => $membershipType,
                    'credit_amount' => '0',
                    'via' => 'Stripe'
                ));
                $_SESSION[ 'userEdited' ] = true;
                SuperCache::cache('pro_users')->destroy();
            } else {
                exit(__('Error While make you pro'));
            }
            header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=pro');
            exit();
        } else if ($payType == 'unlock_private_photo') {
            //done
            $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_private_photo' => 0));
            if ($updated) {
                $db->insert('payments', array(
                    'user_id' => self::ActiveUser()->id,
                    'amount' => $price /100,
                    'type' => 'unlock_private_photo',
                    'pro_plan' => '0',
                    'credit_amount' => '0',
                    'via' => 'Stripe'
                ));
                $_SESSION[ 'userEdited' ] = true;
                header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=unlock');
                exit();
            } else {
                exit(__('Error While update Unlock private photo charging'));
            }
        } else if ($payType == 'lock_pro_video') {
            //done
            $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_pro_video' => 0));
            if ($updated) {
                $db->insert('payments', array(
                    'user_id' => self::ActiveUser()->id,
                    'amount' => $price /100,
                    'type' => 'lock_pro_video',
                    'pro_plan' => '0',
                    'credit_amount' => '0',
                    'via' => 'Stripe'
                ));
                $_SESSION[ 'userEdited' ] = true;
                header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=unlock');
                exit();
            } else {
                exit(__('Error While update Unlock private photo charging'));
            }
        }


        $response[ 'data' ]     = $data;
        $response[ 'user' ]     = $user;

        return $response;
    }

    public function cancel(){
        return array(
            'status' => 300,
            'message' => __('Success')
        );
    }

    public function handle() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $data = array();
        if (empty($_POST[ 'stripeToken' ])) {
            return array(
                'status' => 400,
                'message' => __('No Token')
            );
        }
        if (empty($_POST[ 'description' ])) {
            return array(
                'status' => 400,
                'message' => __('No description')
            );
        }
        if (empty($_POST[ 'payType' ])) {
            return array(
                'status' => 400,
                'message' => __('No payType')
            );
        }
        $product        = Secure($_POST[ 'description' ]);
        $realprice      = Secure($_POST[ 'price' ]);
        $price          = Secure($_POST[ 'price' ]) * 100;
        $amount         = 0;
        $currency       = strtolower(self::Config()->currency);
        $payType        = Secure($_POST[ 'payType' ]);
        $membershipType = 0;
        $token          = $_POST[ 'stripeToken' ];
        if ($payType == 'credits') {
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }
        } else if ($payType == 'membership') {
            if ($realprice == self::Config()->weekly_pro_plan) {
                $membershipType = 1;
            } else if ($realprice == self::Config()->monthly_pro_plan) {
                $membershipType = 2;
            } else if ($realprice == self::Config()->yearly_pro_plan) {
                $membershipType = 3;
            } else if ($realprice == self::Config()->lifetime_pro_plan) {
                $membershipType = 4;
            }
        } else if ($payType == 'unlock_private_photo') {
            if ((int)$realprice == (int)self::Config()->lock_private_photo_fee) {
                $amount = (int)self::Config()->lock_private_photo_fee;
            }
        } else if ($payType == 'lock_pro_video'){
            if ((int)$realprice == (int)self::Config()->lock_pro_video_fee) {
                $amount = (int)self::Config()->lock_pro_video_fee;
            }
        }
        try {
            $customer = \Stripe\Customer::create(array(
                'source' => $token
            ));
            $charge   = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $price,
                'currency' => $currency
            ));
            if ($charge) {
                $user               = $db->objectBuilder()->where('id', self::ActiveUser()->id)->getOne('users', array(
                    'balance'
                ));
                $data[ 'status' ]   = 200;
                $data[ 'message' ]  = __('Payment successfully');
                $data[ 'location' ] = '/ProSuccess';
                if ($payType == 'credits') {
                    $newbalance = $user->balance + $amount;
                    $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array(
                        'balance' => $newbalance
                    ));
                    if ($updated) {
                        RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                        $db->insert('payments', array(
                            'user_id' => self::ActiveUser()->id,
                            'amount' => $price / 100,
                            'type' => 'CREDITS',
                            'pro_plan' => '0',
                            'credit_amount' => $amount,
                            'via' => 'Stripe'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        $data[ 'credit_amount' ]  = (int) $newbalance;
                        return $data;
                    } else {
                        return array(
                            'status' => 400,
                            'message' => __('Error While update balance after charging')
                        );
                    }
                } else if ($payType == 'membership') {
                    $data[ 'location' ] = '/ProSuccess?mode=pro';
                    $protime            = time();
                    $is_pro             = "1";
                    $pro_type           = $membershipType;
                    $updated            = $db->where('id', self::ActiveUser()->id)->update('users', array(
                        'pro_time' => $protime,
                        'is_pro' => $is_pro,
                        'pro_type' => $pro_type
                    ));
                    if ($updated) {
                        RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                        $db->insert('payments', array(
                            'user_id' => self::ActiveUser()->id,
                            'amount' => $price / 100,
                            'type' => 'PRO',
                            'pro_plan' => $membershipType,
                            'credit_amount' => '0',
                            'via' => 'Stripe'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        SuperCache::cache('pro_users')->destroy();
                    } else {
                        return array(
                            'status' => 400,
                            'message' => __('Error While update balance after charging')
                        );
                    }
                } else if ($payType == 'unlock_private_photo') {
                    $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_private_photo' => 0));
                    if ($updated) {
                        $db->insert('payments', array(
                            'user_id' => self::ActiveUser()->id,
                            'amount' => $price /100,
                            'type' => 'unlock_private_photo',
                            'pro_plan' => '0',
                            'credit_amount' => '0',
                            'via' => 'Stripe'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=unlock');
                        exit();
                    } else {
                        exit(__('Error While update Unlock private photo charging'));
                    }
                } else if ($payType == 'lock_pro_video') {
                    $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('lock_pro_video' => 0));
                    if ($updated) {
                        $db->insert('payments', array(
                            'user_id' => self::ActiveUser()->id,
                            'amount' => $price /100,
                            'type' => 'lock_pro_video',
                            'pro_plan' => '0',
                            'credit_amount' => '0',
                            'via' => 'Stripe'
                        ));
                        $_SESSION[ 'userEdited' ] = true;
                        header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=unlock');
                        exit();
                    } else {
                        exit(__('Error While update Unlock private photo charging'));
                    }
                }
                return $data;
            } else {
            }
        }
        catch (Exception $e) {
            return array(
                'status' => 400,
                'message' => $e->getMessage()
            );
        }
    }
}