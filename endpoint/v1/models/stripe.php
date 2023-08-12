<?php
Class Stripe {
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
        if (route(4) == 'success') {
            json($this->success());
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
        $stripe = array(
            'secret_key' => $config->stripe_secret,
            'publishable_key' => $config->stripe_id
        );
        require_once $_LIBS . 'stripe/vendor/autoload.php';
        $z = \Stripe\Stripe::setApiKey($stripe['secret_key']);
        if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
            $product        ='top up credits';
            $realprice      = (int)Secure($_POST[ 'price' ]);
            $price          = (int)Secure($_POST[ 'price' ]) * 100;
            $amount         = 0;
            $membershipType         = 0;
            $payType         = 0;
            $currency       = $config->stripe_currency;
            if ($realprice == $config->bag_of_credits_price) {
                $amount = $config->bag_of_credits_amount;
            } else if ($realprice == $config->box_of_credits_price) {
                $amount = $config->box_of_credits_amount;
            } else if ($realprice == $config->chest_of_credits_price) {
                $amount = $config->chest_of_credits_amount;
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
            if (!empty($config->currency_array) && in_array($config->stripe_currency, $config->currency_array) && $config->stripe_currency != $config->currency && !empty($config->exchange) && !empty($config->exchange[$config->stripe_currency])) {
                $price = (int)(($price * $config->exchange[$config->stripe_currency]));
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

            return json(array(
                'session_id' =>  $session->id,
                'hash' => $hash,
                'code' => 200
            ), 200);
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
    public function success()
    {
        global $db,$config,$_LIBS;
        $stripe = array(
            'secret_key' => $config->stripe_secret,
            'publishable_key' => $config->stripe_id
        );
        require_once $_LIBS . 'stripe/vendor/autoload.php';
        $z = \Stripe\Stripe::setApiKey($stripe['secret_key']);
        if (!empty($_POST[ 'hash' ]) && !empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0 && !empty($_POST['stripeToken'])) {
            $product        = 'top up credits';
            $realprice      = Secure($_POST[ 'price' ]);
            $price          = Secure($_POST[ 'price' ]) * 100;
            $amount         = 0;
            $currency       = strtolower($config->currency);
            $payType        = 0;
            $membershipType = 0;
            $token          = $_POST[ 'stripeToken' ];
            $customer = \Stripe\Customer::create(array(
                'source' => $token
            ));
            $charge   = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $price,
                'currency' => $currency
            ));
            if ($charge) {
                $data = unserialize(base64_decode(Secure($_POST[ 'hash' ])));

                $description    = $data['description'];
                $realprice      = $data['realprice'];
                $price          = $data['price'];
                $amount         = $data['amount'];
                $payType        = $data['payType'];
                $membershipType = $data['membershipType'];
                $currency       = $data['currency'];

                $user           = $db->objectBuilder()->where('id', Auth()->id)->getOne('users', array('balance'));
                $newbalance = $user->balance + $amount;
                $updated    = $db->where('id', Auth()->id)->update('users', array(
                    'balance' => $newbalance
                ));
                if ($updated) {
                    RegisterAffRevenue(Auth()->id,$price / 100);
                    $db->insert('payments', array(
                        'user_id' => Auth()->id,
                        'amount' => $price / 100,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'Stripe'
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
                            'error_id'   => '14',
                            'error_text' => 'Error While update balance after charging'
                        )
                    ), 400);
                }
            }
            else{
                return json(array(
                    'message' => 'not charged',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '13',
                        'error_text' => 'not charged'
                    )
                ), 400);
            }
        }
        else{
            return json(array(
                'message' => 'hash , price , stripeToken can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '13',
                    'error_text' => 'hash , price , stripeToken can not be empty'
                )
            ), 400);
        }
    }
}