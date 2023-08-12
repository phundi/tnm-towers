<?php
Class Fortumo {
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
    	global $db, $config, $_BASEPATH, $_DS;
        $hash = rand(11111,55555).rand(11111,55555);
        $db->where('id',Auth()->id)->update('users',array('fortumo_hash' => $hash));
        return json(array(
            'url' => 'https://pay.fortumo.com/mobile_payments/'.$config->fortumo_service_id.'?cuid='.$hash,
            'code' => 200
        ), 200);
    }

    public function success()
    {
    	global $db, $config, $_BASEPATH, $_DS;
        if (!empty($_POST) && !empty($_POST['amount']) && !empty($_POST['status']) && $_POST['status'] == 'completed' && !empty($_POST['cuid']) && !empty($_POST['price'])) {
            $fortumo_hash = Secure($_POST['cuid']);
            $amount = (int) Secure($_POST['amount']);
            $price = (int) Secure($_POST['price']);
            $user = $db->objectBuilder()->where('fortumo_hash',$fortumo_hash)->getOne('users');
            if (!empty($user)) {
            	$db->where('fortumo_hash',$fortumo_hash)->update('users',array('fortumo_hash' => '',
	                                                                           'balance' => $db->inc($amount)));
	            RegisterAffRevenue($user->id,$price);
	            $db->insert('payments', array(
	                'user_id' => $user->id,
	                'amount' => $price,
	                'type' => 'CREDITS',
	                'pro_plan' => '0',
	                'credit_amount' => $amount,
	                'via' => 'Fortumo'
	            ));
	            $_SESSION[ 'userEdited' ] = true;
	            return json(array(
	                'message' => 'SUCCESS',
	                'code' => 200
	            ), 200);
            }
            else{
            	return json(array(
	                'message' => 'user not found',
	                'code' => 400,
	                'errors'         => array(
	                    'error_id'   => '14',
	                    'error_text' => 'user not found'
	                )
	            ), 400);
            } 
        }
        else{
        	return json(array(
                'message' => 'amount , status , cuid can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '13',
                    'error_text' => 'amount , status , cuid can not be empty'
                )
            ), 400);
        }
    }
}