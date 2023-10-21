<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use GuzzleHttp\Client;
require $_BASEPATH . 'config.php';

Class AirtelMoney extends Aj {
	
	public function createVisaSession(){
        global $db,$config,$_LIBS;		
		
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        
        if (empty($_POST[ 'price' ])) {
            return array(
                'status' => 400,
                'message' => __('No Amount Specified')
            );
        }
        
		ob_start();

        $realprice   = (int)Secure($_POST[ 'price' ]);
        $price       = (int)Secure($_POST[ 'price' ]);
        $pro_plan    = Secure($_POST[ 'pro_plan' ]);
        $amount      = 0;
        $membershipType      = 0;
        $payType     = Secure($_POST[ 'payType' ]);
        $type        = '';
        if ($payType == 'credits') {
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }

            $type = "CREDITS";
        } else if ($payType == 'membership') {
            if ($pro_plan == 'weekly') {
                $membershipType = 1;
            } else if ($pro_plan == 'monthly') {
                $membershipType = 2;
            } else if ($pro_plan == 'yearly') {
                $membershipType = 3;
            } else if ($pro_plan == 'lifetime') {
                $membershipType = 4;
            }else if ($pro_plan == 'daily'){
                $membershipType = 5;
            }

			$amount = $price;
			$type = 'PRO';
		}
        
	    require_once($_LIBS . 'africastalking/vendor/autoload.php');
        $client = new GuzzleHttp\Client();
        
        $url         = "https://api-gateway.ctechpay.com/?endpoint=order";
        //$token       = $config->token;
        $redirectUrl = "https://staging.malovings.com/pro";
        
        
        $res = $client->request('POST', $url, [
            'form_params' => [
                "token" => $token,
                "amount" => $amount,
                "merchantAttributes" => true,
                "redirectUrl" => $redirectUrl,
                "cancelUrl" => "https://staging.malovings.com",
                "CancelText" => "Go Back To Malovings Dating Site"
            ]
        ]);        
        
		$body = json_decode($res->getBody()->getContents());
	    var_dump($body);

		error_log(ob_get_clean());

       if($res->getStatusCode() == '200'){
		   
		   $db->insert('payment_requests', array(
                'user_id' => self::ActiveUser()->id,
                'transaction_id' => $body->order_reference,
                'amount' => $amount,
                'phone_number' => self::ActiveUser()->phone_number,
                'status' => '0',  //0=PENDING, 1=SUCCESS, 2=CONFLICT
                'type' => $type,
                'pro_plan' => $membershipType,
                'via' => 'ctechvisapay'
            ));
            
		   return array(
                'status' => 200,
                'data' => $body
            );
			
	   }else{
		
		   return array(
                'status' => 400,
                'message' =>  "Something went wrong!!"
            );
	   }


	}


	public function createManualSession(){
        global $db,$config,$_LIBS;		
		
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        
        if (empty($_POST[ 'price' ])) {
            return array(
                'status' => 400,
                'message' => __('No Amount Specified')
            );
        }
        
        if (empty($_POST[ 'payment_proof' ])) {
            return array(
                'status' => 400,
                'message' => __('No Proof of Payment Specified')
            );
        }
        
		if (empty($_POST[ 'method' ])) {
            return array(
                'status' => 400,
                'message' => __('No Payment Method Specified')
            );
        }
        

        $realprice   = (int)Secure($_POST[ 'price' ]);
        $price       = (int)Secure($_POST[ 'price' ]);
        $pro_plan    = Secure($_POST[ 'pro_plan' ]);
        $amount      = 0;
        $membershipType      = 0;
        $payType     = Secure($_POST[ 'payType' ]);
        $type        = '';
        
		$pop       	= Secure($_POST[ 'payment_proof' ]);
		$method     = Secure($_POST[ 'method' ]);

        if ($payType == 'credits') {
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }

            $type = "CREDITS";
        } else if ($payType == 'membership') {
            if ($pro_plan == 'weekly') {
                $membershipType = 1;
            } else if ($pro_plan == 'monthly') {
                $membershipType = 2;
            } else if ($pro_plan == 'yearly') {
                $membershipType = 3;
            } else if ($pro_plan == 'lifetime') {
                $membershipType = 4;
            }else if ($pro_plan == 'daily'){
                $membershipType = 5;
            }

			$amount = $price;
			$type = 'PRO';
		}
        
		ob_start();
		var_dump($method);
		var_dump($pop);
		error_log(ob_get_clean());

		   
	   $db->insert('payment_requests', array(
			'user_id' => self::ActiveUser()->id,
			'transaction_id' => $pop,
			'amount' => $amount,
			'phone_number' => self::ActiveUser()->phone_number,
			'status' => '0',  //0=PENDING, 1=SUCCESS, 2=CONFLICT
			'type' => $type,
			'pro_plan' => $membershipType,
			'via' => $method
		));
		
		$message = $method.' Payment From '.self::ActiveUser()->phone_number. ' Waiting Your Approval on Malovings, Amount Paid: '. $amount. ' MWK'; 
		SendSMS('+265995555626', $message);
		//SendSMS('+265991763413', $message);
		
	   return array(
			'status' => 200,
			'data' => 'SUCCESS'
		);

	}

	public function updateManualSession(){
        global $db,$config,$_LIBS;		
		
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        
        if (empty($_POST[ 'transID' ])) {
            return array(
                'status' => 400,
                'message' => __('No Transaction ID Provided')
            );
        }
        
        if (empty($_POST[ 'actionToDo' ])) {
            return array(
                'status' => 400,
                'message' => __('Missing Action')
            );
        }
		
		ob_start();

        $transID   	= Secure($_POST[ 'transID' ]);
        $action     = Secure($_POST[ 'actionToDo' ]);        
		$request = $db->objectBuilder()->where('id', $transID)->getOne("payment_requests");
		$message = "";
		

		$user = $db->objectBuilder()->where('id', $request->user_id)->getOne('users');
        error_log(ob_get_clean());

        if ($action == 'Confirm'){
			
			if ($request->type == 'PRO'){
                $membershipType = $request->pro_plan;   
                $amount = $request->amount;
                
                $protime                = time();
                $is_pro                 = "1";
                $updated                = $db->where('id', $user->id)->update('users', array(
                    'pro_time' => $protime,
                    'is_pro' => $is_pro,
                    'pro_type' => $membershipType
                ));

                if ($updated) {
                    
                    $db->insert('payments', array(
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'type' => 'PRO',
                        'pro_plan' => $membershipType,
                        'credit_amount' => '0',
                        'via' => $request->via
                    ));
                    
                    $db->where('id', $transID)->update('payment_requests', array(
                        'status' => "1",
                        'verified_at' => date('Y-m-d H:i:s')
                    ));
				}
                 
				$message = 'Your Subscription Payment From ' . $request->via . ' Has Been Confirmed. Please Login to https://malovings.com to Enjoy!!'; 
   
			}else if($request->type == 'CREDITS'){
				$amount = $request->amount;
                $newbalance = $user->balance + $amount;

                $updated    = $db->where('id', $user->id)->update('users',
                                     array('balance' => $newbalance));
                if ($updated) {
                    $db->insert('payments', array(
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => $request->via
                    ));

                    $db->where('id', $transID)->update('payment_requests', array(
                        'status' => "1",
                        'verified_at' => date('Y-m-d H:i:s')
                    ));                    
				}
				
				$message = 'Your Credit Payment From ' . $request->via . ' Has Been Confirmed. Please Login to https://malovings.com to Enjoy!!'; 

			}
		}else if ($action == 'Reject'){
			
		
			$db->where('id', $transID)->update('payment_requests', array(
                        'status' => "2"
			));
			  
			$message = 'Your Payment From ' . $request->via . ' Has Been Rejected. Please Provide Proper Payment Details!!'; 
				
		}
		 
		if(!empty($message)){
			SendSMS($user->phone_number, $message);
		}
		
	   return array(
			'status' => 200,
			'data' => 'SUCCESS'
		);

	}
	
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

        $phone = Secure($_POST[ 'phone' ]);
        if(empty($phone)){
            $phone = $db->objectBuilder()->where('id', 
                self::ActiveUser()->id)->getValue('users', 'phone_number');     
        }
        
        
        if (empty($phone)){
            return array(
                'status' => 400,
                'message' => __('Missing Phone Number')
            );
        }

        $realprice   = (int)Secure($_POST[ 'price' ]);
        $price       = (int)Secure($_POST[ 'price' ]);
        $pro_plan       = Secure($_POST[ 'pro_plan' ]);
        $amount      = 0;
        $membershipType      = 0;
        $payType     = Secure($_POST[ 'payType' ]);
        $url         = "https://api-gateway.ctechpay.com/airtel/access/";
        //$token       = $config->token;
        $type        = '';

        if ($payType == 'credits') {
            if ($realprice == self::Config()->bag_of_credits_price) {
                $amount = self::Config()->bag_of_credits_amount;
            } else if ($realprice == self::Config()->box_of_credits_price) {
                $amount = self::Config()->box_of_credits_amount;
            } else if ($realprice == self::Config()->chest_of_credits_price) {
                $amount = self::Config()->chest_of_credits_amount;
            }

            $type = "CREDITS";
        } else if ($payType == 'membership') {
            if ($pro_plan == 'weekly') {
                $membershipType = 1;
            } else if ($pro_plan == 'monthly') {
                $membershipType = 2;
            } else if ($pro_plan == 'yearly') {
                $membershipType = 3;
            } else if ($pro_plan == 'lifetime') {
                $membershipType = 4;
            }else if ($pro_plan == 'daily'){
                $membershipType = 5;
            }


            $amount = $price;
            $type = 'PRO';
        } else if ($payType == 'unlock_private_photo') {
            if ((int)$realprice == (int)self::Config()->lock_private_photo_fee) {
                $amount = (int)self::Config()->lock_private_photo_fee;
            }
        } else if ($payType == 'lock_pro_video'){
            $amount = (int)self::Config()->lock_pro_video_fee;
        }
     
    
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

        
        if ($res->getStatusCode() == 200){

            $ussd_data = json_decode($res->getBody()->getContents(), true)['data'];

            $trans_id = $ussd_data['transaction']["id"];

            $db->insert('payment_requests', array(
                'user_id' => self::ActiveUser()->id,
                'transaction_id' => $trans_id,
                'phone_number' => $phone,
                'amount' => $amount,
                'status' => '0',  //0=PENDING, 1=SUCCESS, 2=CONFLICT
                'type' => $type,
                'pro_plan' => $membershipType,
                'via' => 'airtelmoney'
            ));

            return array(
                'status' => 200,
                'message' => __('Request Successfull'),
                'data' => $trans_id
            );
        }else{
            return array(
                'status' => 501,
                'message' => __('Bad Request')
            );
        }
    }

    public function success(){
        global $db,$config,$_LIBS;
     
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }

        $payType = Secure($_POST['payType']);
        $transID = Secure($_POST['transID']);

        require_once($_LIBS . 'africastalking/vendor/autoload.php');
        $url = "https://api-gateway.ctechpay.com/airtel/access/status/?trans_id=".$transID;

        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $url);

        if ($res->getStatusCode() != 200){
            return array(
                'status' => 401,
                'message' => __('Bad Request'),
            );     
        }

        $data = json_decode($res->getBody()->getContents(), true);
        
        //ob_start();
        //var_dump($url);
        //var_dump("Status: ".$data['transaction_status']);
        //error_log(ob_get_clean());

        if ($data['transaction_status'] == 'TIP'){
            return array(
                'status' => 200,
                'message' => __('Transaction in progress'),
            );     
        }

        if ($data['transaction_status'] == 'TF'){
            return array(
                'status' => 501,
                'message' => __('Transaction failed!!'),
            );     
        }

        if ($data['transaction_status'] == 'TS') {
            
            $user           = $db->objectBuilder()->where('id', self::ActiveUser()->id)->getOne('users', array('balance'));
            if ($payType == 'credits') {
                //done
                $paymentRequest = $db->objectBuilder()->where('transaction_id', $transID)->getOne("payment_requests");
                $amount = $paymentRequest->amount;
                $newbalance = $user->balance + $amount;

                $updated    = $db->where('id', self::ActiveUser()->id)->update('users',
                                     array('balance' => $newbalance));
                if ($updated) {
                    //RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                    $db->insert('payments', array(
                        'user_id' => self::ActiveUser()->id,
                        'amount' => $amount,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'airtelmoney'
                    ));

                    $db->where('transaction_id', $_POST['transID'])->update('payment_requests', array(
                        'status' => "1",
                        'verified_at' => date('Y-m-d H:i:s')
                    ));
                    
                    $_SESSION[ 'userEdited' ] = true;

                    return array(
                        'status' => 200,
                        'message' => __('Transaction Successful!!'),
                    ); 
                    
                } else {
                    return array(
                        'status' => 501,
                        'message' => __('Transaction failed!!'),
                    );
                }
            } else if ($payType == 'membership') {

                $paymentRequest = $db->objectBuilder()->where('transaction_id', $transID)->getOne("payment_requests");
                $membershipType = $paymentRequest->pro_plan;   

                $amount = $paymentRequest->amount;
                
                $protime                = time();
                $is_pro                 = "1";
                $updated                = $db->where('id', self::ActiveUser()->id)->update('users', array(
                    'pro_time' => $protime,
                    'is_pro' => $is_pro,
                    'pro_type' => $membershipType
                ));

                if ($updated) {
                    //RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                    
                    //ob_start();
                    //var_dump($membershipType);
                    //error_log(ob_get_clean());
            
                    
                    $db->insert('payments', array(
                        'user_id' => self::ActiveUser()->id,
                        'amount' => $amount,
                        'type' => 'PRO',
                        'pro_plan' => $membershipType,
                        'credit_amount' => '0',
                        'via' => 'airtelmoney'
                    ));
                    
                    $db->where('transaction_id', $_POST['transID'])->update('payment_requests', array(
                        'status' => "1",
                        'verified_at' => date('Y-m-d H:i:s')
                    ));
                    
                    $_SESSION[ 'userEdited' ] = true;
                    SuperCache::cache('pro_users')->destroy();

                    return array(
                        'status' => 200,
                        'message' => __('Transaction Successful!!'),
                    ); 
                }else{
                    return array(
                        'status' => 501,
                        'message' => __('Transaction failed!!'),
                    ); 
                }

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
            return array(
                'status' => 501,
                'message' => __('Transaction failed!!'),
            );     
        
        }
    }
    
    
    public function checkVisa(){
        global $db,$config,$_LIBS;
     
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }

        $payType = Secure($_POST['payType']);
        $transID = Secure($_POST['ref']);

        require_once($_LIBS . 'africastalking/vendor/autoload.php');
        $client = new GuzzleHttp\Client();

        $url         = "https://api-gateway.ctechpay.com/get_order_status/";
        //$token       = $config->token;
        
        
        $res = $client->request('POST', $url, [
            'form_params' => [
                "token" => $token,
                "orderRef" => $transID,
            ]
        ]);        
        
		$body = json_decode($res->getBody()->__toString());

		ob_start();

	    var_dump($body);
        error_log(ob_get_clean());


		if ($body == null || empty($body) || (!empty($body) && $res->getStatusCode() != 200)){
			return array(
				'status' => 501,
				'message' => __('Transaction Failed !!'),
			);     
		}
               
		if ($body->status != 'PURCHASED'){
			return array(
				'status' => 501,
				'data' =>  $body,
				'message' => 'Transaction Failed!!'
			); 
		}else{

            $user           = $db->objectBuilder()->where('id', self::ActiveUser()->id)->getOne('users', array('balance'));
            if ($payType == 'credits') {
                //done
                $paymentRequest = $db->objectBuilder()->where('transaction_id', $transID)->getOne("payment_requests");
                $amount = $paymentRequest->amount;
                $newbalance = $user->balance + $amount;

                $updated    = $db->where('id', self::ActiveUser()->id)->update('users',
                                     array('balance' => $newbalance));
                if ($updated) {
                    //RegisterAffRevenue(self::ActiveUser()->id,$price / 100);
                    $db->insert('payments', array(
                        'user_id' => self::ActiveUser()->id,
                        'amount' => $amount,
                        'type' => 'CREDITS',
                        'pro_plan' => '0',
                        'credit_amount' => $amount,
                        'via' => 'ctechvisaypay'
                    ));

                    $db->where('transaction_id', $_POST['transID'])->update('payment_requests', array(
                        'status' => "1",
                        'verified_at' => date('Y-m-d H:i:s')
                    ));
                    
                    $_SESSION[ 'userEdited' ] = true;

                    return array(
                        'status' => 200,
                        'message' => __('Transaction Successful!!'),
                    ); 
                    
                } else {
                    return array(
                        'status' => 501,
                        'message' => __('Transaction failed!!'),
                    );
                }
            } else if ($payType == 'membership') {

                $paymentRequest = $db->objectBuilder()->where('transaction_id', $transID)->getOne("payment_requests");
                $membershipType = $paymentRequest->pro_plan;   

                $amount = $paymentRequest->amount;
                
                $protime                = time();
                $is_pro                 = "1";
                $updated                = $db->where('id', self::ActiveUser()->id)->update('users', array(
                    'pro_time' => $protime,
                    'is_pro' => $is_pro,
                    'pro_type' => $membershipType
                ));

                if ($updated) {
                   
                    
                    $db->insert('payments', array(
                        'user_id' => self::ActiveUser()->id,
                        'amount' => $amount,
                        'type' => 'PRO',
                        'pro_plan' => $membershipType,
                        'credit_amount' => '0',
                        'via' => 'airtelmoney'
                    ));
                    
                    $db->where('transaction_id', $transID)->update('payment_requests', array(
                        'status' => "1",
                        'verified_at' => date('Y-m-d H:i:s')
                    ));
                    
                    $_SESSION[ 'userEdited' ] = true;
                    SuperCache::cache('pro_users')->destroy();

                    return array(
                        'status' => 200,
                        'message' => __('Transaction Successful!!'),
                    ); 
                }else{
                    return array(
                        'status' => 501,
                        'message' => __('Transaction failed!!'),
                    ); 
                }

            } 
		}
    }
}
