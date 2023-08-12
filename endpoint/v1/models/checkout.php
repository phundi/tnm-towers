<?php
Class Checkout {
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
        if (route(4) == 'createsession') {
            json($this->createsession());
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

    public function createsession(){
        global $db,$config,$_LIBS;
        $countries_name = Dataset::load('countries');
        $checkout_payment = $config->checkout_payment;
        $checkout_mode = $config->checkout_mode;
        $checkout_currency = $config->checkout_currency;
        $checkout_seller_id = $config->checkout_seller_id;
        $checkout_publishable_key = $config->checkout_publishable_key;
        $checkout_private_key = $config->checkout_private_key;
        if (empty($_POST['card_number']) || empty($_POST['card_cvc']) || empty($_POST['card_month']) || empty($_POST['card_year']) || empty($_POST['token']) || empty($_POST['card_name']) || empty($_POST['card_address']) || empty($_POST['card_city']) || empty($_POST['card_state']) || empty($_POST['card_zip']) || empty($_POST['card_country']) || empty($_POST['card_email']) || empty($_POST['card_phone']) || empty($_POST['price'])) {
            return json(array(
                'message' => 'card_number , card_cvc , card_month , card_year , token , card_name , card_address , card_city , card_state , card_zip , card_country , card_email , card_phone , price can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'card_number , card_cvc , card_month , card_year , token , card_name , card_address , card_city , card_state , card_zip , card_country , card_email , card_phone , price can not be empty'
                )
            ), 400);
        } else {
            require_once($_LIBS . "2checkout/lib/Twocheckout.php");
            Twocheckout::privateKey($config->checkout_private_key);
            Twocheckout::sellerId($config->checkout_seller_id);
            if ($config->checkout_mode == 'sandbox') {
                Twocheckout::sandbox(true);
            } else {
                Twocheckout::sandbox(false);
            }

            $product     = 'top up credits';
            $realprice = $sum  = (int)Secure($_POST[ 'price' ]);
            $price       = (int)Secure($_POST[ 'price' ]) * 100;
            $amount      = 0;
            $currency    = strtolower($config->currency);
            $payType     = 0;
            if ($realprice == $config->bag_of_credits_price) {
                $amount = $config->bag_of_credits_amount;
            } else if ($realprice == $config->box_of_credits_price) {
                $amount = $config->box_of_credits_amount;
            } else if ($realprice == $config->chest_of_credits_price) {
                $amount = $config->chest_of_credits_amount;
            }
            if (!empty($config->currency_array) && in_array($config->checkout_currency, $config->currency_array) && $config->checkout_currency != $config->currency && !empty($config->exchange) && !empty($config->exchange[$config->checkout_currency])) {
                $sum = (int)(($sum * $config->exchange[$config->checkout_currency]));
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
                $newbalance = $user->balance + $amount;
                $updated    = $db->where('id', Auth()->id)->update('users', array('balance' => $newbalance));
                if ($updated) {
                    RegisterAffRevenue(Auth()->id,$price / 100);
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => $price / 100,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'Cashfree'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    return json(array(
                        'message' => 'SUCCESS',
                        'code' => 200
                    ), 200);
                } else {
                    return json(array(
                        'message' => 'Error While update balance after charging',
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '16',
                            'error_text' => 'Error While update balance after charging'
                        )
                    ), 400);
                }
            }
            else{
                return json(array(
                    'message' => 'checkout declined',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '15',
                        'error_text' => 'checkout declined'
                    )
                ), 400);
            }
        }
    }
}