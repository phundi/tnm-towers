<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use GuzzleHttp\Client;

Class AirtelMoney extends Aj {
    public function createsession(){
        global $db,$config,$_LIBS;
        
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

        $phone = $db->objectBuilder()->where('id', 
                self::ActiveUser()->id)->getValue('users', 'phone_number');

        if (empty($phone)){
            return array(
                'status' => 400,
                'message' => __('Missing Phone Number')
            );
        }

        $response    = array();
        $product     = Secure($_POST[ 'description' ]);
        $realprice   = (int)Secure($_POST[ 'price' ]);
        $price       = (int)Secure($_POST[ 'price' ]);
        $amount      = 0;
        $membershipType      = 0;
        $payType     = Secure($_POST[ 'payType' ]);
        $url         = "https://api-gateway.ctechpay.com/airtel/access/";
        $token       = "fclcC3IDZtzdTKUG6PniPIbNiKIX3ItWiDseRL1pGWyRWfinlt3n8n8BjsKGCZN5";

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
     
        $payload = [
            'userid'            => self::ActiveUser()->id,
            'description'       => $product,
            'realprice'         => $realprice,
            'price'             => $price,
            'amount'            => $amount,
            'payType'           => $payType,
            'membershipType'    => $membershipType,
            'currency'          => "MK"
        ];
        
        require_once($_LIBS . 'africastalking/vendor/autoload.php');
        $client = new GuzzleHttp\Client();
        
        $res = $client->request('POST', $url, [
            'form_params' => [
                "token" => $token,
                "amount" => $amount,
                "phone"  => $phone,
                'airtel' => "1"
            ]
        ]);

        echo $res->getStatusCode();
        if ($res->getStatusCode() == 200){

            //echo $res->getStatusCode();
            //echo $res->getHeader('content-type')[0];
            //echo $res->getBody();

            return array(
                'status' => 200,
                'message' => __('Request Successfull')
            );
        }else{
            
        }
        
        $hash = base64_encode(serialize($payload));
        $callback_url = SeoUri('aj/airtelmoney/success?hash='.$hash);
    }

    public function success(){
        global $db,$config,$_LIBS;
        $iyzipay_payment = $config->iyzipay_payment;

        if ($iyzipay_payment !== 'yes' || self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_GET[ 'hash' ]) || ( empty($_POST['token']) && empty(self::ActiveUser()->conversation_id) ) ) {
            return array(
                'status' => 400,
                'message' => __('No hash')
            );
        }
        require_once($_LIBS . "iyzipay/samples/config.php");

        $data           = unserialize(base64_decode(Secure($_GET[ 'hash' ])));
        $description    = $data['description'];
        $realprice      = $data['realprice'];
        $price          = $data['price'];
        $amount         = $data['amount'];
        $payType        = $data['payType'];
        $membershipType = $data['membershipType'];
        $currency       = $data['currency'];
        $user           = $db->objectBuilder()->where('id', self::ActiveUser()->id)->getOne('users', array('balance'));

        # create request class
		$_request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
		$_request->setLocale(\Iyzipay\Model\Locale::TR);
		$_request->setConversationId(self::ActiveUser()->conversation_id);
		$_request->setToken(Secure($_POST['token']));

		# make request
		$checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($_request, IyzipayConfig::options());

        if ($checkoutForm->getPaymentStatus() == 'SUCCESS') {

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
                        'via' => 'iyzipay'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    $response[ 'credit_amount' ]  = (int) $newbalance;
                    $url = $config->uri . '/ProSuccess';
                    if (!empty($_COOKIE['redirect_page'])) {
                        $redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
                        $url = preg_replace('/\((.*?)\)/m', '', $redirect_page);
                    }
                    header('Location: ' . $url);
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
                        'via' => 'iyzipay'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    SuperCache::cache('pro_users')->destroy();
                } else {
                    exit(__('Error While make you pro'));
                }
                header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=pro');
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
                        'via' => 'iyzipay'
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
                        'via' => 'iyzipay'
                    ));
                    $_SESSION[ 'userEdited' ] = true;
                    header('Location: ' . self::Config()->uri . '/ProSuccess?paymode=unlock');
                    exit();
                } else {
                    exit(__('Error While update Unlock private photo charging'));
                }
            }

        } else {
            header('Location: ' . SeoUri(''));
        }
        exit();
    }
}