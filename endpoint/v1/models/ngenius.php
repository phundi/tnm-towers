<?php
Class Ngenius {
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

    public function get()
    {
        global $db,$config,$_LIBS;
        if (!empty($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] > 0) {
            $token = GetNgeniusToken();
            if (!empty($token->message)) {
                return json(array(
                    'message' => $token->message,
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '15',
                        'error_text' => $token->message
                    )
                ), 400);
            }
            elseif (!empty($token->errors) && !empty($token->errors[0]) && !empty($token->errors[0]->message)) {
                return json(array(
                    'message' => $token->errors[0]->message,
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '16',
                        'error_text' => $token->errors[0]->message
                    )
                ), 400);
            }
            else{
                $realprice   = (int)Secure($_POST[ 'price' ]);
                $amount      = 0;
                if ($realprice == $config->bag_of_credits_price) {
                    $amount = $config->bag_of_credits_amount;
                } else if ($realprice == $config->box_of_credits_price) {
                    $amount = $config->box_of_credits_amount;
                } else if ($realprice == $config->chest_of_credits_price) {
                    $amount = $config->chest_of_credits_amount;
                }
                $successURL = SeoUri('aj/ngenius/success?credit='.$amount);
                //$successURL = 'http://192.168.1.108/quick/aj/ngenius/success?credit=1000';
                $postData = new StdClass();
                $postData->action = "SALE";
                $postData->amount = new StdClass();
                $postData->amount->currencyCode = "AED";
                $postData->amount->value = $realprice;
                $postData->merchantAttributes = new \stdClass();
                $postData->merchantAttributes->redirectUrl = $successURL;
                $order = CreateNgeniusOrder($token->access_token,$postData);
                if (!empty($order->message)) {
                    return json(array(
                        'message' => $order->message,
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '17',
                            'error_text' => $order->message
                        )
                    ), 400);
                }
                elseif (!empty($order->errors) && !empty($order->errors[0]) && !empty($order->errors[0]->message)) {
                    return json(array(
                        'message' => $order->errors[0]->message,
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '18',
                            'error_text' => $order->errors[0]->message
                        )
                    ), 400);
                }
                else{
                    $db->where('id',Auth()->id)->update('users',array('ngenius_ref' => $order->reference));
                    return json(array(
                        'url' => $order->_links->payment->href,
                        'code' => 200
                    ), 200);
                }
            }
        }
        else{
            return json(array(
                'message' => 'price can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'price can not be empty'
                )
            ), 400);
        }
    }

    public function success()
    {
        global $db,$config,$_LIBS;
        if (!empty($_POST['ref']) && !empty($_POST['credit'])) {
            $user = $db->objectBuilder()->where('ngenius_ref',Secure($_POST['ref']))->getOne('users');
            if (!empty($user)) {
                $token = GetNgeniusToken();
                if (!empty($token->message)) {
                    return json(array(
                        'message' => $token->message,
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '15',
                            'error_text' => $token->message
                        )
                    ), 400);
                }
                elseif (!empty($token->errors) && !empty($token->errors[0]) && !empty($token->errors[0]->message)) {
                    return json(array(
                        'message' => $token->errors[0]->message,
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '16',
                            'error_text' => $token->errors[0]->message
                        )
                    ), 400);
                }
                else{
                    $order = NgeniusCheckOrder($token->access_token,$user->ngenius_ref);
                    if (!empty($order->message)) {
                        return json(array(
                            'message' => $order->message,
                            'code' => 400,
                            'errors'         => array(
                                'error_id'   => '17',
                                'error_text' => $order->message
                            )
                        ), 400);
                    }
                    elseif (!empty($order->errors) && !empty($order->errors[0]) && !empty($order->errors[0]->message)) {
                        return json(array(
                            'message' => $order->errors[0]->message,
                            'code' => 400,
                            'errors'         => array(
                                'error_id'   => '18',
                                'error_text' => $order->errors[0]->message
                            )
                        ), 400);
                    }
                    else{
                        if ($order->_embedded->payment[0]->state == "CAPTURED") {
                            $update_data = array();
                            $price = Secure($order->amount->value);
                            $amount = Secure($_POST['credit']);
                            $newbalance = $user->balance + $amount;
                            $update_data['balance'] = $newbalance;
                            $update_data['ngenius_ref'] = '';
                            $updated    = $db->where('id', $user->id)->update('users', $update_data);
                            if ($updated) {
                                RegisterAffRevenue($user->id,$price);
                                $db->insert('payments', array(
                                    'user_id' => $user->id,
                                    'amount' => $price,
                                    'type' => 'CREDITS',
                                    'pro_plan' => '0',
                                    'credit_amount' => $amount,
                                    'via' => 'Ngenius'
                                ));
                                $_SESSION[ 'userEdited' ] = true;
                                return json(array(
                                    'message' => 'SUCCESS',
                                    'code' => 200
                                ), 200);
                            }
                        }
                        else{
                            return json(array(
                                'message' => 'not paid',
                                'code' => 400,
                                'errors'         => array(
                                    'error_id'   => '21',
                                    'error_text' => 'not paid'
                                )
                            ), 400);
                        }
                    }
                }
            }
        }
        else{
            return json(array(
                'message' => 'credit , ref can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '20',
                    'error_text' => 'credit , ref can not be empty'
                )
            ), 400);
        }
    }
}