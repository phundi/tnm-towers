<?php
Class Authorize extends Theme {
	public static function init_data() {
		if (empty($_GET['app_id'])) {
		    $errors = array(
		    	'status' => 400,
		        'errors' => array(
		            'error_code' => 1,
		            'message' => 'Empty app ID'
		        )
		    );
		    header("Content-type: application/json");
		    echo json_encode($errors, JSON_PRETTY_PRINT);
		    exit();
		}
		if (empty($_GET['app_secret'])) {
		    $errors = array(
		    	'status' => 400,
		        'errors' => array(
		            'error_code' => 2,
		            'message' => 'Empty app secret'
		        )
		    );
		    header("Content-type: application/json");
		    echo json_encode($errors, JSON_PRETTY_PRINT);
		    exit();
		}
		if (empty($_GET['code'])) {
		    $errors = array(
		    	'status' => 400,
		        'errors' => array(
		            'error_code' => 3,
		            'message' => 'Empty code'
		        )
		    );
		    header("Content-type: application/json");
		    echo json_encode($errors, JSON_PRETTY_PRINT);
		    exit();
		}
		if (VerifyAPIApii($_GET['app_id'], $_GET['app_secret']) === false) {
			$errors = array(
		    	'status' => 400,
		        'errors' => array(
		            'error_code' => 4,
		            'message' => 'App id not found or secret id is wrong'
		        )
		    );
		    header("Content-type: application/json");
		    echo json_encode($errors, JSON_PRETTY_PRINT);
		    exit();
		}
		if (empty($_GET['code'])) {
		    $errors = array(
		    	'status' => 400,
		        'errors' => array(
		            'error_code' => 5,
		            'message' => 'Empty code'
		        )
		    );
		    header("Content-type: application/json");
		    echo json_encode($errors, JSON_PRETTY_PRINT);
		    exit();
		}
		$code = GetCode($_GET['code']);
		if (empty($code)) {
			$errors = array(
		    	'status' => 400,
		        'errors' => array(
		            'error_code' => 6,
		            'message' => 'Code is invalid'
		        )
		    );
		    header("Content-type: application/json");
		    echo json_encode($errors, JSON_PRETTY_PRINT);
		    exit();
		}
		if (AppHasPermission($code['user_id'], $code['app_id']) === false) {
			$errors = array(
		    	'status' => 400,
		        'errors' => array(
		            'error_code' => 7,
		            'message' => 'No permission givin'
		        )
		    );
		    header("Content-type: application/json");
		    echo json_encode($errors, JSON_PRETTY_PRINT);
		    exit();
		}

		$data = userData($code['user_id']);
		unset($data->web_token);
		unset($data->password);
		unset($data->web_device);
		unset($data->email_code);
		unset($data->paystack_ref);
		unset($data->paypal_email);
		unset($data->two_factor_email_code);
		unset($data->mobile_device);

		$code = Secure($code['code']);
		//$query = mysqli_query($sqlConnect, "DELETE FROM `codes` WHERE `code` = '$code'");

		header("Content-type: application/json");
		echo json_encode($data, JSON_PRETTY_PRINT);
		exit();

	}
	public static function show($partial = '') {
        self::init_data();
    }
}