<?php
Class Checkout extends Aj {
    public function createsession(){
        global $db,$config,$_LIBS;
        $countries_name = Dataset::load('countries');
        $checkout_payment = $config->checkout_payment;
        $checkout_mode = $config->checkout_mode;
        $checkout_currency = $config->checkout_currency;
        $checkout_seller_id = $config->checkout_seller_id;
        $checkout_publishable_key = $config->checkout_publishable_key;
        $checkout_private_key = $config->checkout_private_key;
        if ($checkout_payment !== 'yes' || self::ActiveUser() == NULL) {
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
        $types = array('credits','membership','unlock_private_photo','lock_pro_video');
        if (empty($_POST['card_number']) || empty($_POST['card_cvc']) || empty($_POST['card_month']) || empty($_POST['card_year']) || empty($_POST['token']) || empty($_POST['card_name']) || empty($_POST['card_address']) || empty($_POST['card_city']) || empty($_POST['card_state']) || empty($_POST['card_zip']) || empty($_POST['card_country']) || empty($_POST['card_email']) || empty($_POST['card_phone']) || empty($_POST['type']) || !in_array($_POST['type'], $types)) {
            return array(
                'status' => 400,
                'error' => __('unknown_error')
            );
        } else {

            require_once($_LIBS . "2checkout/lib/Twocheckout.php");
            Twocheckout::privateKey($config->checkout_private_key);
            Twocheckout::sellerId($config->checkout_seller_id);
            if ($config->checkout_mode == 'sandbox') {
                Twocheckout::sandbox(true);
            } else {
                Twocheckout::sandbox(false);
            }

            $product     = Secure($_POST[ 'description' ]);
            $realprice = $sum  = (int)Secure($_POST[ 'price' ]);
            $price       = (int)Secure($_POST[ 'price' ]) * 100;
            $amount      = 0;
            $currency    = strtolower(self::Config()->currency);
            $payType     = Secure($_POST[ 'payType' ]);

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
                $amount = (int)self::Config()->lock_pro_video_fee;
            }


            try {
                if (!empty(self::Config()->currency_array) && in_array(self::Config()->checkout_currency, self::Config()->currency_array) && self::Config()->checkout_currency != self::Config()->currency && !empty(self::Config()->exchange) && !empty(self::Config()->exchange[self::Config()->checkout_currency])) {
                    $sum = (int)(($sum * self::Config()->exchange[self::Config()->checkout_currency]));
                }
                $charge  = Twocheckout_Charge::auth(array(
                    "merchantOrderId" => "123",
                    "token" => $_POST['token'],
                    "currency" => $checkout_currency,
                    "total" => $sum,
                    "billingAddr" => array(
                        "name"          => Secure($_POST['card_name']),
                        "addrLine1"     => Secure($_POST['card_address']),
                        "city"          => Secure($_POST['card_city']),
                        "state"         => Secure($_POST['card_state']),
                        "zipCode"       => Secure($_POST['card_zip']),
                        "country"       => $countries_name[$_POST['card_country']],
                        "email"         => Secure($_POST['card_email']),
                        "phoneNumber"   => Secure($_POST['card_phone'])
                    )
                ));

                if ($charge['response']['responseCode'] == 'APPROVED') {

                    $url = '';
                    if ($payType == 'credits') {
                        //done
                        $newbalance = $user->balance + $amount;
                        $updated    = $db->where('id', self::ActiveUser()->id)->update('users', array('balance' => $newbalance));
                        if ($updated) {
                            RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                            $db->insert('payments', array(
                                'user_id' => self::ActiveUser()->id,
                                'amount' => $price / 100,
                                'type' => 'CREDITS',
                                'pro_plan' => '0',
                                'credit_amount' => $amount,
                                'via' => 'Cashfree'
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                            $response[ 'credit_amount' ]  = (int) $newbalance;
                            $url = self::Config()->uri . '/ProSuccess';
                            if (!empty($_COOKIE['redirect_page'])) {
                                $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                                $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                            }
                        } else {
                            exit(__('Error While update balance after charging'));
                        }
                    } else if ($payType == 'membership') {
                        //done
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
                                'via' => 'Cashfree'
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                            SuperCache::cache('pro_users')->destroy();
                        } else {
                            exit(__('Error While make you pro'));
                        }
                        $url = self::Config()->uri . '/ProSuccess?paymode=pro';
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
                                'via' => 'Cashfree'
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                            $url = self::Config()->uri . '/ProSuccess?paymode=unlock';
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
                                'via' => 'Cashfree'
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                            $url = self::Config()->uri . '/ProSuccess?paymode=unlock';
                        } else {
                            exit(__('Error While update Unlock private photo charging'));
                        }
                    }

                    if (self::ActiveUser()->address != $_POST['card_address'] ||
                        self::ActiveUser()->city != $_POST['card_city'] || 
                        self::ActiveUser()->state != $_POST['card_state'] || 
                        self::ActiveUser()->zip != $_POST['card_zip'] || 
                        self::ActiveUser()->country_id != $_POST['card_country'] || 
                        self::ActiveUser()->cc_phone_number != $_POST['card_phone']
                    ) {
                        $update_data = array(
                            'address'           => Secure($_POST['card_address']),
                            'city'              => Secure($_POST['card_city']),
                            'state'             => Secure($_POST['card_state']),
                            'zip'               => Secure($_POST['card_zip']),
                            'country_id'        => Secure($_POST['card_country']),
                            'cc_phone_number'   => Secure($_POST['card_phone'])
                        );
                        $db->where('id', self::ActiveUser()->id)->update('users', $update_data);
                    }

                    $data = array(
                        'status'    => 200,
                        'url'       => $url
                    );

                } else {
                    return array(
                        'status' => 400,
                        'error' => __('checkout_declined')
                    );
                }
                
            }
            catch (Twocheckout_Error $e) {
                return array(
                    'status' => 400,
                    'error' => $e->getMessage()
                );
            }

        }
    }
}