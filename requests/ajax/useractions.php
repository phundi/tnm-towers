<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Class UserActions extends Aj {
    function register() {
        global $config, $db;
        $error      = '';
        $first_name = '';
        $last_name  = '';
        $username   = '';
        $email      = '';
        $password   = '';
        $users      = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                if ($_POST[ 'password' ] !== $_POST[ 'c_password' ]) {
                    $error .= '<p>• ' . __('Passwords Don\'t Match.') . '</p>';
                }
                UNSET($_POST[ 'c_password' ]);
                $first_name = Secure($_POST[ 'first_name' ]);
                $last_name  = Secure($_POST[ 'last_name' ]);
                $phone_number      = Secure($_POST[ 'phone_number' ]);
                $password   = $_POST[ 'password' ];


                
               // if (!empty($email) && !filter_var($_POST[ 'email' ], FILTER_VALIDATE_EMAIL)) {
               //     $error .= '<p>• ' . __('This E-mail is invalid.') . '</p>';
               // }


                if (substr($phone_number, 0, 4) !== '+265' && substr($phone_number, 0, 3) !== '265' && substr($phone_number, 0, 2) !== '09' && substr($phone_number, 0, 2) !== '08') {
                    $error = '<p>• ' . __('Please provide Airtel or TNM phone number') . '</p>';
                }
                if (strlen($phone_number) != 13 && strlen($phone_number) != 10 && strlen($phone_number) != 12) {
                    $error = '<p>• ' . __('Please enter valid number.') . '</p>';
                }
                if (!is_numeric(substr($phone_number, 1))) {
                    $error = '<p>• ' . __('Invalid phone number characters.') . '</p>';
                }

                if (strlen($phone_number) == 10 && (substr($phone_number, 0, 1) == "0")) {
                    $pattern = '/^0/';
                    $phone_number = preg_replace($pattern, "+265", $phone_number);
                }

                if (strlen($phone_number) == 12 && (substr($phone_number, 0, 3) == "265")) {
                    $pattern = '/^265/';
                    $phone_number = preg_replace($pattern, "+265", $phone_number);
                }


                $_POST['phone_number'] = $phone_number;
                $_POST['username'] = hash('md5', $phone_number);
                $username   = Secure($_POST[ 'username' ]);
                $email      = $username . "@malovings.com"; //Secure($_POST[ 'email' ]);
                $_POST['email'] = $email;


                if (isset($_POST[ 'username' ]) && empty($_POST[ 'username' ])) {
                    $error .= '<p>• ' . __('Missing username.') . '</p>';
                }
                if ($config->reserved_usernames_system == 1 && in_array($_POST['username'],$config->reserved_usernames_array)) {
                    $error .= '<p>• ' . __('disallowed_username') . '</p>';
                }
                if (isset($_POST[ 'password' ]) && empty($_POST[ 'password' ])) {
                    $error .= '<p>• ' . __('Missing password.') . '</p>';
                }

                if (strlen($password) < 4) {
                    $error .= '<p>• ' . __('Password is too short.') . '</p>';
                }
                if (Wo_IsBanned($username)) {
                    $error .= '<p>• ' . __('The number is blacklisted and not allowed, please choose another username.') . '</p>';
                }
                if (Wo_IsBanned($email)) {
                    $error .= '<p>• ' . __('The email address is blacklisted and not allowed, please choose another email.') . '</p>';
                }
                if (preg_match_all('~@(.*?)(.*)~', $email, $matches) && !empty($matches[2]) && !empty($matches[2][0]) && Wo_IsBanned($matches[2][0])) {
                    $error .= '<p>• ' . __('The email provider is blacklisted and not allowed, please choose another email provider.') . '</p>';
                }
                if(!empty($config->specific_email_signup)){
                    $specific_email_signup = explode(',', $config->specific_email_signup);
                    if (preg_match_all('~@(.*?)(.*)~', $_POST['email'], $matches) && !empty($matches[2]) && !empty($matches[2][0]) && !in_array($matches[2][0], $specific_email_signup)) {
                        $error = str_replace('{0}',$config->specific_email_signup , __('you must signup using {0} only.'));
                    }
                }
                if ($config->recaptcha == 'on' && !empty($config->recaptcha_secret_key)) {
                    if (empty($_POST['g-recaptcha-response'])) {
                        $error = __("please_check_recaptcha");
                    }
                    else{
                        $recaptcha_data = array(
                        'secret' => $config->recaptcha_secret_key,
                        'response' => $_POST['g-recaptcha-response']
                        );
                        $response = checkRecaptcha($recaptcha_data);
                        
                        if (!$response->success) {
                            $error = __('reCaptcha_error');
                        }
                    }
                    unset($_POST['g-recaptcha-response']);
                }

                if ($error == '') {
                    $re_data = $_POST;
                    $ref_user_id = null;
                    $ref = (!empty($_SESSION['ref'])) ? $_SESSION['ref'] : (!empty($_COOKIE['ref']) ? $_COOKIE['ref'] : '') ;
                    if (!empty($ref)) {
                        $ref_user_id = UserIdFromUsername($ref);
                        if (!empty($ref_user_id) && is_numeric($ref_user_id)) {
                            $re_data['referrer'] = Secure($ref_user_id);
                            $re_data['src']      = Secure('Referrer');
                            if ($config->affiliate_type == 0) {
                                $update_balance      = Wo_UpdateBalance($ref_user_id, $config->amount_ref);
                                unset($_SESSION['ref']);
                                setcookie('ref', '', 1, '/');
                            }
                        }
                    }

                    $regestered_user = $users->register($re_data);

                    if ($regestered_user[ 'code' ] == 200) {

                        $db->where('id', $regestered_user['userId'])->update('users', array(
                            'pro_time' => time(),
                            'is_pro' => '1',
                            'pro_type' => '1'
                        ));
                                
                        sendSMS($phone_number, 
                            'One week free subscription has been given to your account as we celebrate one week old of malovings.com. We love you');

                        if (!empty($_POST['invite']) && !empty($regestered_user['userId'])) {
                            $invite = Secure($_POST['invite']);
                            @DeleteAdminInvitation('code', $invite);
                            AddInvitedUser($regestered_user['userId'],$invite);
                        }
                        if (!empty($config->auto_user_like)) {
                            AutoUserLike(UserIdFromUsername($_POST['username']));
                        }
                        $user = $users->login($username, $password);
                        if ($user[ 'code' ] == 200) {
                            SessionStart();
                            $JWT                   = $user[ 'userProfile' ]->web_token;
                            $url                   = $config->uri . '/steps';
                            $_SESSION[ 'JWT' ]     = $user[ 'userProfile' ];
                            $_SESSION[ 'user_id' ] = $JWT;
                            setcookie( "JWT", $JWT, time() + (10 * 365 * 24 * 60 * 60), '/');
                            if($config->credit_earn_system == 1){
                                RecordDailyCredit($user['data']['user_id']);
                            }
                            return array(
                                'status' => 200,
                                'message' => __('Registration successfully'),
                                'url' => $url,
                                'cookies' => array(
                                )
                            );
                        } else {
                            $error .= '<p>• ' . __('Incorrect username or password.') . '</p>';
                        }
                    } else {
                        $error .= '<p>• ' . $regestered_user[ 'message' ] . '</p>';
                    }
                }
            }
            if ($error !== '') {
                return array(
                    'status' => 401,
                    'message' => $error
                );
            }
        } else {
            return array(
                'status' => 401,
                'message' => '<p>• ' . __('Resource endpoint class file not found.') . '</p>'
            );
        }
    }
    function login() {
        global $app, $config, $db;
        $error = '';
        $users = LoadEndPointResource('users');


        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
              
               
                
                if (isset($_POST[ 'username' ]) && empty($_POST[ 'username' ])) {
                    $error .= '<p>• ' . __('Missing phone number.') . '</p>';
                }

                if (!((strlen($_POST[ 'username' ]) == 13 && (str_starts_with($_POST[ 'username' ], '2659') || str_starts_with($_POST[ 'username' ], '2658')))
                        || 
                    (strlen($_POST[ 'username' ]) == 12 && (str_starts_with($_POST[ 'username' ], '2659') || str_starts_with($_POST[ 'username' ], '2658'))))) {
                    $error .= '<p>• ' . __('Invalid phone number.') . '</p>';
                }

                
                if (isset($_POST[ 'password' ]) && empty($_POST[ 'password' ])) {
                    $error .= '<p>• ' . __('Missing password.') . '</p>';
                }



                $phone_number = $_POST[ 'username' ];
                if (substr($phone_number, 0, 4) !== '+265' && substr($phone_number, 0, 3) !== '265' && substr($phone_number, 0, 2) !== '09' && substr($phone_number, 0, 2) !== '08') {
                    $error = '<p>• ' . __('Please provide Airtel or TNM phone number') . '</p>';
                }
                if (strlen($phone_number) != 13 && strlen($phone_number) != 10 && strlen($phone_number) != 12) {
                    $error = '<p>• ' . __('Please enter valid number.') . '</p>';
                }
                if (!is_numeric(substr($phone_number, 1))) {
                    $error = '<p>• ' . __('Invalid phone number characters.') . '</p>';
                }

                if (strlen($phone_number) == 10 && (substr($phone_number, 0, 1) == "0")) {
                    $pattern = '/^0/';
                    $phone_number = preg_replace($pattern, "+265", $phone_number);
                }

                if (strlen($phone_number) == 12 && (substr($phone_number, 0, 3) == "265")) {
                    $pattern = '/^265/';
                    $phone_number = preg_replace($pattern, "+265", $phone_number);
                }

                $_POST['username'] = $phone_number;


                if (isset($_POST[ 'username' ]) && !empty($_POST[ 'username' ]) && isset($_POST[ 'password' ]) && !empty($_POST[ 'password' ])) {
                    if ($config->prevent_system == 1) {
                        if (!CanLogin()) {
                            return array(
                                'status' => 400,
                                'message' => '<p>• Too many login attempts please try again later</p>'
                            );
                        }
                    }

                    $username        = secure($_POST['username']);
                    $password        = secure($_POST['password']);

                    if (strlen($username) == 12 && (str_starts_with($username, '2659') || str_starts_with($username, '2658'))) {
                        $username = "+" . $username;
                    }

                    
                    $getUser = $db->where("(username = ? or phone_number = ?)", array(
                        $username,
                        $username
                    ))->getOne('users', ["password", "id", "active","admin","username"]);

                    
                    $user = $users->login($username, $password);

                    if ($user[ 'code' ] == 200) {
                        if (TwoFactor($getUser['id']) === false) {
                            session_start();
                            $_SESSION['code_id'] = $getUser['id'];
                           setcookie("code_id", $getUser['id'], time() + (10 * 365 * 24 * 60 * 60), '/');
                           $_COOKIE['code_id'] = $getUser['id'];
                            return array(
                                'status' => 600,
                                'url' => $config->uri . '/unusual-login?type=two-factor'
                            );
                        }
                        SessionStart();

                        if ( $config->maintenance_mode == 1 ) {
                            if ($user[ 'userProfile' ]->admin === "0") {
                                return array(
                                    'status' => 400,
                                    'message' => '<p>• Website maintenance mode is active, Login for user is forbidden</p>'
                                );
                            }
                        }

                        $JWT = $user[ 'userProfile' ]->web_token;
                        $url = '';
                        if ($user[ 'userProfile' ]->start_up == 3 && $user[ 'userProfile' ]->verified == 1) {
                            $url = $config->uri . '/find-matches';
                        } else {
                            $url = $config->uri . '/steps';
                        }
                        if (!empty($_POST[ 'last_url' ])) {
                            $url = urldecode(Secure($_POST['last_url']));
                        }
                        $_SESSION[ 'JWT' ]     = $user[ 'userProfile' ];
                        $_SESSION[ 'user_id' ] = $JWT;
                        setcookie( "JWT", $JWT, time() + (10 * 365 * 24 * 60 * 60), '/');
                        if($config->credit_earn_system == 1){
                            RecordDailyCredit($user['data']['user_id']);
                        }
                        return array(
                            'status' => 200,
                            'message' => __('Login successfully'),
                            'url' => $url,
                            'cookies' => array(
                            )
                        );
                    } else {
                        if ($config->prevent_system == 1) {
                            AddBadLoginLog();
                        }
                        $error .= '<p>• ' . __('Incorrect username or password.') . '</p>';
                    }
                } else {
                    return array(
                        'status' => 400,
                        'message' => '<p>• ' . __('An error occurred while processing the form.') . '</p>'
                    );
                }
                if ($error !== '') {
                    return array(
                        'status' => 401,
                        'message' => $error
                    );
                }
            }
        } else {
            return array(
                'status' => 401,
                'message' => '<p>• ' . __('Resource endpoint class file not found.') . '</p>'
            );
        }
    }
    function forget_password() {
        global $db;
        $error = '';
        
        if (isset($_POST) && !empty($_POST)) {

    
            if (empty($_POST[ 'phone_number' ])) {
                $error .= '<p>• ' . __('Missing Phone Number.') . '</p>';
            }

            $phone_number = $_POST[ 'phone_number' ];

            if (substr($phone_number, 0, 4) !== '+265' && substr($phone_number, 0, 3) !== '265' && substr($phone_number, 0, 2) !== '09' && substr($phone_number, 0, 2) !== '08') {
                $error = '<p>• ' . __('Please provide Airtel or TNM phone number') . '</p>';
            }
            if (strlen($phone_number) != 13 && strlen($phone_number) != 10 && strlen($phone_number) != 12) {
                $error = '<p>• ' . __('Please enter valid number.') . '</p>';
            }
            if (!is_numeric(substr($phone_number, 1))) {
                $error = '<p>• ' . __('Invalid phone number characters.') . '</p>';
            }

            if (strlen($phone_number) == 10 && (substr($phone_number, 0, 1) == "0")) {
                $pattern = '/^0/';
                $phone_number = preg_replace($pattern, "+265", $phone_number);
            }

            if (strlen($phone_number) == 12 && (substr($phone_number, 0, 3) == "265")) {
                $pattern = '/^265/';
                $phone_number = preg_replace($pattern, "+265", $phone_number);
            }

            $_POST['phone_number'] = $phone_number;

            

            if ($error !== '') {
                return array(
                    'status' => 400,
                    'message' => $error
                );
            }

            $phone = $_POST[ 'phone_number' ];

            $user = $db->where('phone_number', $phone)->getOne('users');
            


            if (empty($user)){
                return array(
                    'status' => 401,
                    'message' => '<p>• ' . __('Not a registered phone number') . '</p>'
                );
            }

            if ($user) {
                $phone_code = Secure(rand(1111, 9999));
                $db->where('id', $user['id'])->update('users',array('smscode'=>$phone_code));
                
                $message = __('Account Reset Code:') . ' '.$phone_code;
                $send    = SendSMS($phone, $message);
                if ($send) {
                    return array(
                        'status' => 200,
                        'message' => __("Verification sms sent successfully.")
                    );
                } else {
                    $error = '<p>• ' . __('Can\'t send verification email, please try again later.') . '</p>';
                }
            }
            
        } else {
            return array(
                'status' => 400,
                'message' => '<p>• ' . __('An error occurred while processing the form.') . '</p>'
            );
        }
        
       
    }
    function mailotp() {
        global $db;
        $error      = '';
        $email      = '';
        $email_code = '';
        $users      = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                if (isset($_POST[ 'email' ]) && empty($_POST[ 'email' ])) {
                    $error .= '<p>• ' . __('Missing E-mail.') . '</p>';
                } else {
                    if (!filter_var($_POST[ 'email' ], FILTER_VALIDATE_EMAIL)) {
                        $error .= '<p>• ' . __('This E-mail is invalid.') . '</p>';
                    } else {
                        if (!$users->isEmailExists($_POST[ 'email' ])) {
                            $error .= '<p>• ' . __('This E-mail') . ' "' . $_POST[ 'email' ] . '" ' . __('is not registered.') . '</p>';
                        }
                    }
                }
                if (isset($_POST[ 'email_code' ]) && empty($_POST[ 'email_code' ])) {
                    $error .= '<p>• ' . __('Missing email code.') . '</p>';
                }
                $email      = Secure($_POST[ 'email' ]);
                $email_code = Secure($_POST[ 'email_code' ]);
                $user       = $db->where('email', $email)->where('email_code', $email_code)->getOne('users');
                if ($user) {
                    if ($user[ 'email_code' ] == $email_code) {
                        return array(
                            'status' => 200,
                            'message' => __('Email verified successfully'),
                            'ajaxRedirect' => '/reset/' . base64_encode(strrev($email)),
                            'cookies' => array(
                                'email_code' => $user[ 'email_code' ]
                            )
                        );
                    } else {
                        $error .= '<p>• ' . __('Wrong email verification code.') . '</p>';
                    }
                } else {
                    $error .= '<p>• ' . __('No user found with this email or code.') . '</p>';
                }
            }
            if ($error !== '') {
                return array(
                    'status' => 400,
                    'message' => $error
                );
            }
        } else {
            return array(
                'status' => 401,
                'message' => '<p>• ' . __('Resource endpoint class file not found.') . '</p>'
            );
        }
    }
    function resetpassword() {
        global $db, $config;
        $error        = '';
        $phone        = '';
        $phone_code   = '';
        $password     = '';
        $new_password = '';
        $users        = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                if ((isset($_POST[ 'phone_number' ]) && empty($_POST[ 'phone_number' ])) && (isset($_POST[ 'phone_number' ]) && empty($_POST[ 'phone_number' ]))) {
                    $error .= '<p>• ' . __('You are not allowed to open this page directly.') . '</p>';
                } else {
                    if (isset($_POST[ 'phone_number' ]) && empty($_POST[ 'phone_number' ])) {
                        $error .= '<p>• ' . __('Missing phone number.') . '</p>';
                    } else {
                        if (strlen($_POST[ 'phone_number' ]) != 13) {
                            $error .= '<p>• ' . __('This phone number is invalid.') . '</p>';
                        }
                    }
                    if (isset($_POST[ 'phone_code' ]) && empty($_POST[ 'phone_code' ])) {
                        $error .= '<p>• ' . __('Missing phone code.') . '</p>';
                    } else {
                        if (!is_numeric($_POST[ 'phone_code' ])) {
                            $error .= '<p>• ' . __('This phone code is invalid.') . '</p>';
                        }
                    }
                    if (isset($_POST[ 'password' ]) && empty($_POST[ 'password' ])) {
                        $error .= '<p>• ' . __('Empty password.') . '</p>';
                    } else {
                        if ($_POST[ 'password' ] !== $_POST[ 'c_password' ]) {
                            $error .= '<p>• ' . __('Passwords Don\'t Match.') . '</p>';
                        }
                        if (!empty($_POST[ 'password' ]) && strlen($_POST[ 'password' ]) < 6) {
                            $error .= '<p>• ' . __('Password is too short.') . '</p>';
                        }
                    }
                }
                if ($config->recaptcha == 'on' && !empty($config->recaptcha_secret_key)) {
                    if (empty($_POST['g-recaptcha-response'])) {
                        $error = __("please_check_recaptcha");
                    }
                    else{
                        $recaptcha_data = array(
                        'secret' => $config->recaptcha_secret_key,
                        'response' => $_POST['g-recaptcha-response']
                        );
                        $response = checkRecaptcha($recaptcha_data);
                        
                        if (!$response->success) {
                            $error = __('reCaptcha_error');
                        }
                    }
                    unset($_POST['g-recaptcha-response']);
                }
                if ($error == '') {
                    $phone      = Secure($_POST[ 'phone_number' ]);
                    $phone_code = Secure($_POST[ 'phone_code' ]);
                    $user       = $db->where('phone_number', $phone)->where('smscode', $phone_code)->getOne('users');
                    
                    if ($user) {
                        if ($user[ 'smscode' ] == $phone_code) {
                            $new_password = password_hash(Secure($_POST[ 'password' ]), PASSWORD_DEFAULT, array(
                                'cost' => 11
                            ));
                            $updated      = $db->where('id', $user[ 'id' ])->update('users', array(
                                'password' => $new_password
                            ));
                            if ($updated) {
                                $new_user_login = $users->login($user[ 'username' ], Secure($_POST[ 'password' ]));
                                if ($new_user_login[ 'code' ] == 200) {
                                    SessionStart();
                                    $_SESSION[ 'JWT' ] = $new_user_login[ 'userProfile' ];
                                    setcookie( "JWT", $new_user_login[ 'userProfile' ]->web_token, time() + (10 * 365 * 24 * 60 * 60), '/');
                                    $url               = '';
                                    if ($new_user_login[ 'userProfile' ]->start_up == 3) {
                                        $url = $config->uri . '/find-matches';
                                    } else {
                                        $url = $config->uri . '/steps';
                                    }
                                    if($config->credit_earn_system == 1){
                                        RecordDailyCredit($new_user_login['data']['user_id']);
                                    }
                                    return array(
                                        'status' => 200,
                                        'message' => __('Password reset successfully'),
                                        'url' => $url,
                                        'cookies' => array(
                                        ),
                                        'remove_cookies' => array(
                                            'verify_email' => true,
                                            'phone_code' => true,
                                            'phone_number' => true
                                        )
                                    );
                                } else {
                                    $error .= '<p>• ' . __('Error While login with new password.') . '</p>';
                                }
                            } else {
                                $error .= '<p>• ' . __('Error While save new password.') . '</p>';
                            }
                        } else {
                            $error .= '<p>• ' . __('Wrong phone verification code.') . '</p>';
                        }
                    } else {
                        $error .= '<p>• ' . __('No user found with this phone number or code.') . '</p>';
                    }
                }
            }
            if ($error !== '') {
                return array(
                    'status' => 400,
                    'message' => $error
                );
            }
        } else {
            return array(
                'status' => 401,
                'message' => '<p>• ' . __('Resource endpoint class file not found.') . '</p>'
            );
        }
    }
    function UpdateAnnouncementViews(){
        global $conn, $wo,$is_admin;
//    if ($is_admin == false) {
//        return false;
//    }
        $id      = Secure($_GET['id']);
        $user_id = Secure(self::ActiveUser()->id);
        if (IsActiveAnnouncement($id) === false) {
            return false;
        }
        if (IsViewedAnnouncement($id) === true) {
            return false;
        }
        $query_one = mysqli_query($conn, "INSERT INTO `announcement_views` (`user_id`, `announcement_id`) VALUES ('{$user_id}', '{$id}')");
        if ($query_one) {
            return array(
                'status' => 200);
        }
    }
    function subscribe()
    {
        global $db, $config;
        if (empty($_POST['email']) || (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
            return [
                'status' => 400,
                'message' => __('This E-mail is invalid.')
            ];
        }

        $count = $db->where('email',Secure($_POST['email']))->getValue('email_subscribers','COUNT(*)');
        if ($count > 0) {
            return [
                'status' => 400,
                'message' => __('already_subscribed')
            ];
        }

        $db->insert('email_subscribers',array('email' => Secure($_POST['email']),
                                              'time' => time()));
        return [
                'status' => 200,
                'message' => __('successfully_subscribed')
            ];
    }
    function get_sms_verification_code() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (self::ActiveUser()->smscode !== '') {
            return array(
                'status' => 200,
                'code' => self::ActiveUser()->smscode
            );
        } else {
            return array(
                'status' => 204
            );
        }
    }
    
    function get_fp_sms_verification_code() {
        global $db;

        $phone_number = Secure($_GET[ 'phone_number' ]);


        if (strlen($phone_number) == 10 && (substr($phone_number, 0, 1) == "0")) {
            $pattern = '/^0/';
            $phone_number = preg_replace($pattern, "+265", $phone_number);
        }

        if (strlen($phone_number) == 12 && (substr($phone_number, 0, 3) == "265")) {
            $pattern = '/^265/';
            $phone_number = preg_replace($pattern, "+265", $phone_number);
        }


        $phone = $phone_number;
        $user = $db->where('phone_number', $phone)->getOne('users');
        
        if (!empty($user) && $user['smscode'] !== '') {
            
            return array(
                'status' => 200,
                'code' => $user['smscode'],
                'reset_link' => '/reset/' . base64_encode(strrev($phone)) . '/' .  base64_encode(strrev($user['smscode'])),
                'cookies' => array(
                    'phone_code' => $user['smscode']
                )
            );
           
        } else {
            return array(
                'status' => 204
            );
        }
        
    }
   

    function send_verefication_sms() {
        $data = array(
            'status' => 200
        );
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $phone = '';
        $users = LoadEndPointResource('users');
        if ($users) {
            if (isset($_GET) && !empty($_GET)) {
                if (empty($_GET[ 'phone' ])) {
                    $error = '<p>• ' . __('Missing phone number.') . '</p>';
                }
                $phone = Secure($_GET[ 'phone' ]);
                if (substr($_GET[ 'phone' ], 0, 1) !== '+') {
                    $error = '<p>• ' . __('Please provide international number with your area code starting with +.') . '</p>';
                }
                if (strlen($phone) < 6 OR strlen($phone) > 32) {
                    $error = '<p>• ' . __('Please enter valid number.') . '</p>';
                }
                if (!is_numeric(substr($phone, 1))) {
                    $error = '<p>• ' . __('Invalid phone number characters.') . '</p>';
                }
                $mob = $db->objectBuilder()->where('phone_number', str_replace('+', '', $phone))->where('id', self::ActiveUser()->id, '<>')->getValue('users', 'id');
                if ($mob > 0) {
                    $error .= '<p>• ' . __('This Mobile number is Already exist.') . '</p>';
                }

                $activation_request_count = $db->where('id', self::ActiveUser()->id)->getValue('users', 'activation_request_count');
                $last_activation_request = $db->where('id', self::ActiveUser()->id)->getValue('users', 'last_activation_request');

                if( self::Config()->activation_limit_system == '1' ){
                    if( $activation_request_count >= self::Config()->max_activation_request ){
                        $error = '<p>• ' . __('You have been exceed the activation request limit.') . '</p>';
                    }

                    $timediff = intval( floor( time() - $last_activation_request ) / 60 );
                    if( $timediff < intval( self::Config()->activation_request_time_limit ) ){
                        $error = '<p>• ' . __('You have to wait') . ' ' . self::Config()->activation_request_time_limit . ' ' . __( ' minutes before you try to activate again.') . '</p>';
                    }

                }

                if ($error == '') {
                    $message = __('Mobile Activation code.') . ' ' . self::ActiveUser()->smscode;
                    $send    = SendSMS($phone, $message);
                    if ($send) {
                        if( self::Config()->activation_limit_system == '1' ){
                            $db->where('id', self::ActiveUser()->id)->update('users', array('activation_request_count' => $db->inc(1) , 'last_activation_request' => time() ));
                        }

                        return array(
                            'status' => 200,
                            'message' => __('Verification sms sent successfully.')
                        );
                    } else {
                        $error = '<p>• ' . __('Can\'t send verification sms, please try again later.') . '</p>';
                    }
                }else{
                    return array(
                        'status' => 400,
                        'message' => $error
                    );
                }
            }
        }
        if ($error !== '') {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function get_email_verification_code() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (self::ActiveUser()->email_code !== '') {
            return array(
                'status' => 200,
                'code' => self::ActiveUser()->email_code
            );
        } else {
            return array(
                'status' => 204
            );
        }
    }
    function send_verefication_email() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $email = '';
        $users = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                $email = strtolower(Secure($_POST[ 'email' ]));
                if (isset($_POST[ 'email' ]) && empty($_POST[ 'email' ])) {
                    $error = '• ' . __('Missing email.');
                }
                if (!filter_var($_POST[ 'email' ], FILTER_VALIDATE_EMAIL)) {
                    $error = '• ' . __('This E-mail is invalid.');
                }
                if (strtolower(self::ActiveUser()->email) !== $email) {
                    if ($users->isEmailExists($email)) {
                        $error = __('This E-mail is Already exist.');
                    }
                }
                if ($error == '') {
                    $message_body = Emails::parse('auth/activate', array(
                        'first_name' => (self::ActiveUser()->first_name !== '' ? self::ActiveUser()->first_name : self::ActiveUser()->username),
                        'email_code' => self::ActiveUser()->email_code
                    ));
                    if (strtolower(self::ActiveUser()->email) !== $email) {
                        $email = strtolower($email);
                    } else {
                        $email = strtolower(self::ActiveUser()->email);
                    }
                    $send = SendEmail($email, __('Thank you for your registration.'), $message_body);
                    if ($send) {
                        if (strtolower(self::ActiveUser()->email) !== $email) {
                            $db->where('id', self::ActiveUser()->id)->update('users', array(
                                'email' => $email
                            ));
                            $_SESSION[ 'userEdited' ] = true;
                        }
                        return array(
                            'status' => 200
                        );
                    } else {
                        return array(
                            'status' => 403,
                            'message' => __('Can\'t send verification email, please try again later.')
                        );
                    }
                } else {
                    return array(
                        'status' => 403,
                        'message' => $error
                    );
                }
            } else {
                return array(
                    'status' => 403,
                    'message' => __('Can\'t send verification email, please try again later.')
                );
            }
        } else {
            return array(
                'status' => 403,
                'message' => __('Can\'t send verification email, please try again later.')
            );
        }
    }
    function save_user_location() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        // if (empty($_POST[ 'lat' ]) || empty($_POST[ 'lng' ])) {
        //     return array(
        //         'status' => 403,
        //         'message' => __('Forbidden')
        //     );
        // }
        $data = array();
        if (isset($_POST[ 'lat' ]) && !empty($_POST[ 'lat' ])) {
            $data[ 'lat' ] = Secure($_POST[ 'lat' ]);
        }
        if (isset($_POST[ 'lng' ]) && !empty($_POST[ 'lng' ])) {
            $data[ 'lng' ] = Secure($_POST[ 'lng' ]);
        }
        if (empty($data[ 'lat' ]) || empty($data[ 'lng' ])) {
            $data[ 'lat' ] = 0;
            $data[ 'lng' ] = 0;
        }
        $info = '';
        if ($data[ 'lat' ] == 0 && $data[ 'lng' ] == 0) {
            $info = 'show';
        }
        if (!empty($data[ 'lat' ]) && $data[ 'lat' ] != 0 && $data[ 'lng' ] != 0 && (self::ActiveUser()->lat == 0 || self::ActiveUser()->lng == 0)) {
            $info = 'hide';
        }
        $data[ 'last_location_update' ] = time();
        $updated                        = $db->where('id', self::ActiveUser()->id)->update('users', $data);
        if ($updated) {
            return array(
                'status' => 200,
                'info' => $info
            );
        } else {
            return array(
                'status' => 204
            );
        }
    }
    function like() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if( Auth()->verified !== "1" ) {
            return array(
                'status' => 400,
                'message' => __('account_not_verified_text')
            );
        }
        $user                  = null;
        $userid                = '';
        $web_device_id         = '';
        $full_name             = '';
        $username              = '';
        $email                 = '';
        $email_on_profile_like = null;
        $message_body          = '';
        $is_find_matches       = false;
        $max_swaps             = self::Config()->max_swaps;
        if (isset($_POST[ 'source' ]) && !empty($_POST[ 'source' ])) {
            if (Secure($_POST[ 'source' ]) == 'find-matches') {
                $is_find_matches = true;
            }
        }

        if ( isGenderFree(self::ActiveUser()->gender) === false ){

            if(self::ActiveUser()->is_pro === "0" && self::Config()->pro_system === "1"){
                $last_swipe = $db->where('user_id', self::ActiveUser()->id)->orderBy('created_at','DESC')->get('likes', 1, array('created_at'));
                if(isset($last_swipe[0])) {
                    $raminhours = 24 - intval(date('H', time() - strtotime($last_swipe[0]['created_at'])));
                }else{
                    $raminhours = 24;
                }

                $count_swipe_for_this_day = GetUserTotalSwipes(self::ActiveUser()->id);
                if($count_swipe_for_this_day >= $max_swaps){
                    return array(
                        'status' => 200,
                        'maxswaps' => $max_swaps,
                        'count_swipe_for_this_day' => $count_swipe_for_this_day,
                        'hours' => str_replace('{0}',$raminhours, __('You reach the max of swipes per day. you have to wait {0} hours before you can redo likes Or upgrade to pro to for unlimited.') )
                    );
                }
            }

        }

        if (isset($_POST[ 'username' ]) && !empty($_POST[ 'username' ])) {
            $username = Secure($_POST[ 'username' ]);
        }
        if (isset($_POST[ 'full_name' ]) && !empty($_POST[ 'full_name' ])) {
            $full_name = Secure($_POST[ 'full_name' ]);
        }
        if (isset($_POST[ 'email' ]) && !empty($_POST[ 'email' ])) {
            $email = Secure($_POST[ 'email' ]);
        }
        if (isset($_POST[ 'email_on_profile_like' ]) && !empty($_POST[ 'email_on_profile_like' ])) {
            $email_on_profile_like = (int) Secure($_POST[ 'email_on_profile_like' ]);
        }
        if (isset($_POST[ 'web_device_id' ]) && !empty($_POST[ 'web_device_id' ])) {
            $web_device_id = Secure($_POST[ 'web_device_id' ]);
        }
        if (isset($_POST[ 'username' ]) && !empty($_POST[ 'username' ])) {
            $userid = Secure($_POST[ 'username' ]);
            $user = $db->where('username', $userid)->getOne('users',array('id','web_device_id','is_pro'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }else{
                $web_device_id = $user[ 'web_device_id' ];
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }

        if (self::ActiveUser()->id == $user['id']) {
            return array(
                'status' => 400,
                'message' => __('You can not like your self.')
            );
        }
        $is_user_matches = $db->where('user_id', $user['id'])->where('like_userid', self::ActiveUser()->id)->getOne('likes', array('id'));
        $id              = $db->where('user_id', self::ActiveUser()->id)->where('like_userid', $user['id'])->getValue('likes', 'id');
        $saved           = false;
        if ($id > 0) {
            $saved = $db->where('id', $id)->update('likes', array(
                'user_id' => self::ActiveUser()->id,
                'like_userid' => $user['id'],
                'is_like' => 1,
                'is_dislike' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ));
        } else {
            if(isUserLiked($user['id']) === false) {
                $saved = $db->insert('likes', array(
                    'user_id' => self::ActiveUser()->id,
                    'like_userid' => $user['id'],
                    'is_like' => 1,
                    'is_dislike' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ));
            }
        }
        if ($saved) {
            if ($id == 0) {
                $Notification = LoadEndPointResource('Notifications');
                if ($is_user_matches > 0) {
                    $Notification->createNotification($web_device_id, self::ActiveUser()->id, $user['id'], 'got_new_match', '', '/@' . self::ActiveUser()->username);
                    $Notification->createNotification($web_device_id, $user['id'], self::ActiveUser()->id, 'got_new_match', '', '/@' . $username);
                }
                if(self::Config()->pro_system === "1") {
                   // if ($user['is_pro'] === "1") {
                        $db->where('notifier_id', self::ActiveUser()->id)->where('recipient_id', $user['id'])->where('type', 'like')->delete('notifications');
                        $Notification->createNotification($web_device_id, self::ActiveUser()->id, $user['id'], 'like', '', '/@' . self::ActiveUser()->username);
                    //}
                }else{
                    $db->where('notifier_id', self::ActiveUser()->id)->where('recipient_id', $user['id'])->where('type', 'like')->delete('notifications');
                    $Notification->createNotification($web_device_id, self::ActiveUser()->id, $user['id'], 'like', '', '/@' . self::ActiveUser()->username);
                }
            }
            return array(
                'status' => 200,
                'like_text' => __('Liked'),
                'liked_text' => __('Like'),
                'dislike_text' => __('Dislike'),
                //'msg' => $message_body,
                //'user' => self::ActiveUser(),
                //'userid' => $userid
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while save like.')
            );
        }
    }
    function remove_like() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $userid = '';
        if (isset($_POST[ 'username' ]) && !empty($_POST[ 'username' ])) {
            $userid = Secure($_POST[ 'username' ]);
            $user = $db->where('username', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            if (isset($_POST[ 'userid' ]) && !empty($_POST[ 'userid' ])) {
                $userid = Secure($_POST[ 'userid' ]);
                $user = $db->where('id', $userid)->getOne('users',array('id'));
                if(empty($user)){
                    return array(
                        'status' => 400,
                        'message' => __('The User not exist.')
                    );
                }
            }else{
                return array(
                    'status' => 400,
                    'message' => __('No User ID found.')
                );
            }
        }
        if (self::ActiveUser()->id == $user['id']) {
            return array(
                'status' => 400,
                'message' => __('You can not perform this action.')
            );
        }
        $deleted = $db->where('user_id', self::ActiveUser()->id)->where('like_userid', $user['id'])->where('is_like', '1')->delete('likes');
        if ($deleted) {
            $db->where('notifier_id', $user['id'])->where('recipient_id', self::ActiveUser()->id)->where('type', 'like')->delete('notifications');
            $db->where('notifier_id', $user['id'])->where('recipient_id', self::ActiveUser()->id)->where('type', 'got_new_match')->delete('notifications');
            return array(
                'status' => 200,
                'like_text' => __('like'),
                'userid' => $user['id']
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while deleting dislike.')
            );
        }
    }
    function dislike() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if( Auth()->verified !== "1" ) {
            return array(
                'status' => 400,
                'message' => __('account_not_verified_text')
            );
        }
        $user                  = null;
        $userid          = '';
        $max_swaps             = self::Config()->max_swaps;
        $is_find_matches = false;
        if (isset($_POST[ 'source' ]) && !empty($_POST[ 'source' ])) {
            if (Secure($_POST[ 'source' ]) == 'find-matches') {
                $is_find_matches = true;
            }
        }
        //if($is_find_matches === true){
            $last_swipe = $db->where('user_id', self::ActiveUser()->id)->orderBy('created_at','DESC')->get('likes', 1, array('created_at'));
            $raminhours = 1;
            if (!empty($last_swipe)) {
                $raminhours = 24 - intval(date('H', time() - strtotime($last_swipe[0]['created_at'])));
            }
            

            $count_swipe_for_this_day = GetUserTotalSwipes(self::ActiveUser()->id);
            if($count_swipe_for_this_day >= $max_swaps){
                return array(
                    'status' => 200,
                    'maxswaps' => $max_swaps,
                    'source' => ($is_find_matches) ? 1: 0,
                    'count_swipe_for_this_day' => $count_swipe_for_this_day,
                    'hours' => str_replace('{0}',$raminhours, __('You reach the max of swipes per day. you have to wait {0} hours before you can redo likes Or upgrade to pro to for unlimited.') )
                );
            }
        //}

        $web_device_id = '';
        if (isset($_POST[ 'web_device_id' ]) && !empty($_POST[ 'web_device_id' ])) {
            $web_device_id = Secure($_POST[ 'web_device_id' ]);
        }
        if (isset($_POST[ 'username' ]) && !empty($_POST[ 'username' ])) {
            $userid = Secure($_POST[ 'username' ]);
            $user = $db->where('username', $userid)->getOne('users',array('id','web_device_id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }else{
                $web_device_id = $user['web_device_id'];
            }
        } else if (isset($_POST[ 'userid' ]) && !empty($_POST[ 'userid' ])) {
            $userid = Secure($_POST[ 'userid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id','web_device_id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }else{
                $web_device_id = $user['web_device_id'];
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }
        if (self::ActiveUser()->id == $user['id']) {
            return array(
                'status' => 400,
                'message' => __('You can not perform this action.')
            );
        }
        $id    = $db->where('user_id', self::ActiveUser()->id)->where('like_userid', $user['id'])->getValue('likes', 'id');
        $saved = false;
        if ($id > 0) {
            $saved = $db->where('id', $id)->update('likes', array(
                'user_id' => self::ActiveUser()->id,
                'like_userid' => $user['id'],
                'is_like' => 0,
                'is_dislike' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ));
        } else {
            if(isUserDisliked($user['id']) === false) {
                $saved = $db->insert('likes', array(
                    'user_id' => self::ActiveUser()->id,
                    'like_userid' => $user['id'],
                    'is_like' => 0,
                    'is_dislike' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ));
            }
        }
        if ($saved) {
            if ($is_find_matches === false) {
            }
            return array(
                'status' => 200,
                'like_text' => __('Like'),
                'disliked_text' => __('Dislike'),
                'dislike_text' => __('Disliked'),
                'userid' => $user['id']
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while save like.')
            );
        }
    }
    function remove_dislike() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $userid = '';
        if (isset($_POST[ 'username' ])) {
            $userid = Secure($_POST[ 'username' ]);
            $user = $db->where('username', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ]) && (int)$_POST[ 'userid' ] > 0) {
                $userid = (int)Secure($_POST[ 'userid' ]);
                $user = $db->where('id', (int)$userid)->getOne('users',array('id'));
                if(empty($user)){
                    return array(
                        'status' => 400,
                        'message' => __('The User not exist.')
                    );
                }
            }else{
                return array(
                    'status' => 400,
                    'message' => __('No User ID found.')
                );
            }
        }
        if (self::ActiveUser()->id == $userid) {
            return array(
                'status' => 400,
                'message' => __('You can not perform this action.')
            );
        }
        $deleted = $db->where('user_id', self::ActiveUser()->id)->where('like_userid', $user['id'])->where('is_dislike', '1')->delete('likes');
        if ($deleted) {
            $db->where('notifier_id', self::ActiveUser()->id)->where('recipient_id', $user['id'])->where('type', 'dislike')->delete('notifications');
            return array(
                'status' => 200,
                'dislike_text' => __('Dislike'),
                'userid' => $userid,
                'uid' => $user['id']
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while deleting dislike.')
            );
        }
    }
    function block() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $userid = "";
        if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ])) {
            $userid = (int) Secure($_POST[ 'userid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }
        if (self::ActiveUser()->id == $userid) {
            return array(
                'status' => 400,
                'message' => __('You can not perform this action.')
            );
        }
        $saved = false;
        if(isUserBlocked( (int)$userid ) === false) {
            $saved = $db->insert('blocks', array(
                'user_id' => self::ActiveUser()->id,
                'block_userid' => $userid,
                'created_at' => date('Y-m-d H:i:s')
            ));
        }
        if ($saved) {
            if (isset($_SESSION[ 'blocked_users' ])) {
                unset($_SESSION[ 'blocked_users' ]);
                $_SESSION[ 'blocked_users_expiry' ] = time();
            }
            return array(
                'status' => 200,
                'block_text' => __('Unblock'),
                'message' => __('User has been blocked successfully.')
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while save block.')
            );
        }
    }
    function unblock() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $userid = "";
        if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ])) {
            $userid = (int) Secure($_POST[ 'userid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }
        $target_id = self::ActiveUser()->id;
        if (isset($_POST[ 'targetuid' ]) && $_POST[ 'targetuid' ] !== '') {
            $targetuid = base64_decode(strrev(Secure($_POST[ 'targetuid' ])));
            if (is_numeric($targetuid) && $targetuid > 0) {
                $target_id = (int) $targetuid;
            }
        }
        if ($target_id == $userid) {
            return array(
                'status' => 400,
                'message' => __('You can not perform this action.')
            );
        }
        $deleted = $db->where('user_id', $target_id)->where('block_userid', $userid)->delete('blocks');
        if ($deleted) {
            if ($target_id == self::ActiveUser()->id) {
                if (isset($_SESSION[ 'blocked_users' ])) {
                    unset($_SESSION[ 'blocked_users' ]);
                    $_SESSION[ 'blocked_users_expiry' ] = time();
                }
            }
            return array(
                'status' => 200,
                'id' => $userid,
                'block_text' => __('Block User'),
                'message' => __('User has been unblocked successfully.')
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while delete user block.')
            );
        }
    }
    function report() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $userid = "";
        if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ])) {
            $userid = (int) Secure($_POST[ 'userid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }
        $report_text = '';
        if (isset($_POST[ 'report_content' ]) && !empty($_POST[ 'report_content' ])) {
            $report_content = Secure($_POST[ 'report_content' ]);
        }
        if (self::ActiveUser()->id == $userid) {
            return array(
                'status' => 400,
                'message' => __('You can not perform this action.')
            );
        }

        $saved = false;
        if(isUserReported( (int)$userid ) === false) {
            $saved = $db->insert('reports', array(
                'user_id' => self::ActiveUser()->id,
                'report_userid' => $userid,
                'report_text' => $report_content,
                'created_at' => date('Y-m-d H:i:s')
            ));
            $notif_data = array(
                'recipient_id' => 0,
                'type' => 'report',
                'admin' => 1,
                'created_at' => time()
            );
            
            $db->insert('notifications', $notif_data);
        }
        if ($saved) {
            return array(
                'status' => 200,
                'report_text' => __('UnReport'),
                'message' => __('User has been reported successfully.')
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while save report.')
            );
        }
    }
    function unreport() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $userid = '';
        if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ])) {
            $userid = (int) Secure($_POST[ 'userid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }
        if (self::ActiveUser()->id == $userid) {
            return array(
                'status' => 400,
                'message' => __('You can not perform this action.')
            );
        }
        $deleted = $db->where('user_id', self::ActiveUser()->id)->where('report_userid', $userid)->delete('reports');
        if ($deleted) {
            return array(
                'status' => 200,
                'report_text' => __('Report User'),
                'message' => __('User has been unreported successfully.')
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while delete user report.')
            );
        }
    }
    function check_phone_number(){
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $users = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                if (isset($_POST[ 'phone_number' ]) && empty($_POST[ 'phone_number' ])) {
                    $error .=  __('Missing phone number.') ;
                }
                $phone_number = strtolower(Secure($_POST[ 'phone_number' ]));
                if ($users->isPhoneExists($phone_number)) {
                    $error .=  __('This Phone number is Already exist.');
                }
                if ($error !== '') {
                    return array(
                        'status' => 200,
                        'message' => $error
                    );
                }else{
                    return array(
                        'status' => 200,
                        'message' => ''
                    );
                }
            }
        }
    }
    function verifymail() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $email = '';
        $users = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                $email = strtolower(Secure($_POST[ 'email' ]));
                if (isset($_POST[ 'email' ]) && empty($_POST[ 'email' ])) {
                    $error .= '<p>• ' . __('Missing email.') . '</p>';
                }
                if (!filter_var($_POST[ 'email' ], FILTER_VALIDATE_EMAIL)) {
                    $error .= '<p>• ' . __('This E-mail is invalid.') . '</p>';
                }
                if (strtolower(self::ActiveUser()->email) !== $email) {
                    if ($users->isEmailExists($email)) {
                        $error .= '<p>• ' . __('This E-mail is Already exist.') . '</p>';
                    }
                }
                if ($error == '') {
                    $message_body = Emails::parse('auth/activate', array(
                        'first_name' => self::ActiveUser()->full_name,
                        'email_code' => self::ActiveUser()->email_code
                    ));
                    $send         = SendEmail($email, __('Email Verification.'), $message_body);
                    if ($send) {
                        return array(
                            'status' => 200,
                            'message' => __('Verification email sent successfully.'),
                            'ajaxRedirect' => '/verifymailotp',
                            'cookies' => array(
                                'verify_email' => strtolower($email)
                            )
                        );
                    } else {
                        $error .= '<p>• ' . __('Can\'t send verification email, please try again later.') . '</p>';
                    }
                }
            }
        }
        if ($error !== '') {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function verifymail_otp() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error      = '';
        $email      = '';
        $email_code = '';
        $users      = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                if (isset($_POST[ 'email' ]) && empty($_POST[ 'email' ])) {
                    $error .= '<p>• ' . __('Missing E-mail.') . '</p>';
                } else {
                    if (!filter_var($_POST[ 'email' ], FILTER_VALIDATE_EMAIL)) {
                        $error .= '<p>• ' . __('This E-mail is invalid.') . '</p>';
                    }
                }
                if (isset($_POST[ 'email_code' ]) && empty($_POST[ 'email_code' ])) {
                    $error .= '<p>• ' . __('Missing email code.') . '</p>';
                }
                $email      = Secure($_POST[ 'email' ]);
                $email_code = Secure($_POST[ 'email_code' ]);
                $user       = $db->where('id', self::ActiveUser()->id)->where('email_code', $email_code)->getOne('users');
                if ($user) {
                    if (self::ActiveUser()->email_code == $email_code) {
                        $saved = $db->where('id', self::ActiveUser()->id)->update('users', array(
                            'verified' => '1',
                            'active' => '1',
                            'email' => $email
                        ));
                        if ($saved) {
                            if (strtolower(self::ActiveUser()->email) !== $email) {
                                $db->where('id', self::ActiveUser()->id)->update('users', array(
                                    'email' => $email
                                ));
                                $_SESSION[ 'userEdited' ] = true;
                            }
                            return array(
                                'status' => 200,
                                'message' => __('Email verified successfully'),
                                'url' => $config->uri . '/find-matches',
                                'remove_cookies' => array(
                                    'verify_email' => true
                                )
                            );
                        } else {
                            $error .= '<p>• ' . __('Error while update email activation.') . '</p>';
                        }
                    } else {
                        $error .= '<p>• ' . __('Wrong email verification code.') . '</p>';
                    }
                } else {
                    $error .= '<p>• ' . __('No user found with this email or code.') . '</p>';
                }
            }
            if ($error !== '') {
                return array(
                    'status' => 400,
                    'message' => $error
                );
            }
        } else {
            return array(
                'status' => 401,
                'message' => '<p>• ' . __('Resource endpoint class file not found.') . '</p>'
            );
        }
    }
    function shownotifications() {
        global $db, $config, $_BASEPATH, $_DS;
        $site_url   = $config->uri;
        $theme_url  = $config->uri . '/themes/' . $config->theme . '/';
        $theme_path = $_BASEPATH . 'themes' . $_DS . $config->theme . $_DS;
        $html       = '';
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $seen  = ' = 0';
        $limit = null;
        if (isset($_POST[ 'seen' ])) {
            $seen  = ' > 0';
            $limit = 20;
        }
        $blocked_user_array = array();
        $blocked_user_array = (array_keys(BlokedUsers())) ? array_keys(BlokedUsers()) : array(
            ''
        );
        $notifications      = $db->objectBuilder()->where('recipient_id', self::ActiveUser()->id)->where('`seen`' . $seen)->orderBy('created_at', 'DESC')->where('notifier_id', $blocked_user_array, 'NOT IN')->get('notifications', $limit, array(
            'id',
            'notifier_id',
            'recipient_id',
            'type',
            'seen',
            'text',
            'url',
            'created_at'
        ));
        $Notification       = LoadEndPointResource('Notifications');
        foreach ($notifications as $key => $value) {
            $html .= $Notification->htmlNotification($value);
        }
        $db->where('recipient_id', self::ActiveUser()->id)->where('seen', '0')->update('notifications', array(
            'seen' => time()
        ));
        if ($html == '') {
            if (file_exists($theme_path . 'main' . $_DS . 'empty-notification.php')) {
                ob_start();
                require($theme_path . 'main' . $_DS . 'empty-notification.php');
                $html = ob_get_contents();
                ob_end_clean();
            }
        }
        return array(
            'status' => 200,
            'notifications' => $html,
            'count' => count((array) $notifications)
        );
    }
    function verifyphone() {
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error = '';
        $phone = '';
        $users = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                $phone = Secure($_POST[ 'phone' ]);
                if (isset($_POST[ 'phone' ]) && empty($_POST[ 'phone' ])) {
                    $error = '<p>• ' . __('Missing phone number.') . '</p>';
                }
                if (substr($_POST[ 'phone' ], 0, 1) !== '+') {
                    $error = '<p>• ' . __('Please provide international number with your area code starting with +.') . '</p>';
                }
                if (strlen($phone) < 6 OR strlen($phone) > 32) {
                    $error = '<p>• ' . __('Please enter valid number.') . '</p>';
                }
                if (!is_numeric(substr($phone, 1))) {
                    $error = '<p>• ' . __('Invalid phone number characters.') . '</p>';
                }
                if ($error == '') {
                    $message = __('Mobile Activation code.') . ' ' . self::ActiveUser()->smscode;
                    $send    = SendSMS($phone, $message);
                    if ($send) {
                        return array(
                            'status' => 200,
                            'message' => __('Verification sms sent successfully.'),
                            'ajaxRedirect' => '/verifyphoneotp',
                            'cookies' => array(
                                'verify_phone' => strtolower($phone)
                            )
                        );
                    } else {
                        $error = '<p>• ' . __('Can\'t send verification email, please try again later.') . '</p>';
                    }
                }
            }
        }
        if ($error !== '') {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function verifyphone_otp() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $phone    = '';
        $sms_code = '';
        $users    = LoadEndPointResource('users');
        if ($users) {
            if (isset($_POST) && !empty($_POST)) {
                if (isset($_POST[ 'phone' ]) && empty($_POST[ 'phone' ])) {
                    $error .= '<p>• ' . __('Missing E-mail.') . '</p>';
                }
                if (isset($_POST[ 'sms_code' ]) && empty($_POST[ 'sms_code' ]) && !is_numeric($_POST[ 'sms_code' ])) {
                    $error .= '<p>• ' . __('Missing sms code.') . '</p>';
                }
                $phone    = Secure($_POST[ 'phone' ]);
                $sms_code = Secure($_POST[ 'sms_code' ]);
                $user     = $db->where('id', self::ActiveUser()->id)->where('smscode', $sms_code)->getOne('users');
                if ($user) {
                    if (self::ActiveUser()->smscode == $sms_code) {
                        $saved = $db->where('id', self::ActiveUser()->id)->update('users', array(
                            'verified' => '1',
                            'phone_verified' => '1',
                            'phone_number' => str_replace('+', '', $phone)
                        ));
                        if ($saved) {
                            $_SESSION[ 'userEdited' ] = true;
                            return array(
                                'status' => 200,
                                'message' => __('Phone verified successfully'),
                                'url' => $config->uri . '/find-matches',
                                'remove_cookies' => array(
                                    'verify_phone' => true
                                )
                            );
                        } else {
                            $error .= '<p>• ' . __('Error while update phone activation.') . '</p>';
                        }
                    } else {
                        $error .= '<p>• ' . __('Wrong phone verification code.') . '</p>';
                    }
                } else {
                    $error .= '<p>• ' . __('No user found with this phone number or code.') . '</p>';
                }
            }
            if ($error !== '') {
                return array(
                    'status' => 400,
                    'message' => $error
                );
            }
        } else {
            return array(
                'status' => 401,
                'message' => '<p>• ' . __('Resource endpoint class file not found.') . '</p>'
            );
        }
    }
    function boostnow() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $_cost  = 0;
        $userid = 0;
        $error  = '';
        if (isset($_POST[ 'uid' ]) && !empty($_POST[ 'uid' ])) {
            $userid = Secure($_POST[ 'uid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        }
        if ($userid == 0) {
            $error = '<p>• ' . __('No user ID found.') . '</p>';
        }
        if (self::ActiveUser()->is_pro == "1" && self::Config()->pro_system === "1") {
            $_cost = $config->pro_boost_me_credits_price;
        } else {
            $_cost = $config->normal_boost_me_credits_price;
        }
        if ( isGenderFree(self::ActiveUser()->gender) === true ) {
            $_cost = 0;
        }
        if (self::ActiveUser()->balance >= $_cost) {
        } else {
            $error = '<p>• ' . __('No credit available.') . '</p>';
        }
        if ($error == '') {
            $saved = $db->where('id', self::ActiveUser()->id)->update('users', array(
                'is_boosted' => '1',
                'boosted_time' => time(),
                'balance' => $db->dec($_cost)
            ));
            if ($saved) {
                $_SESSION[ 'userEdited' ] = true;
                return array(
                    'status' => 200,
                    'current_credit' => self::ActiveUser()->balance - $_cost,
                    'message' => __('User boosted successfully.')
                );
            } else {
                $error = '<p>• ' . __('Error while boost user.') . '</p>';
            }
        }
        if ($error !== '') {
            return array(
                'status' => 400,
                'message' => __('Error while save like.')
            );
        }
    }
    function unmatche() {
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $username = '';
        if (isset($_POST[ 'username' ]) && !empty($_POST[ 'username' ])) {
            $username = Secure($_POST[ 'username' ]);
        }
        $userid = '';
        if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ])) {
            $userid = (int) Secure($_POST[ 'userid' ]);
        } else {
            return array(
                'status' => 400,
                'message' => __('No user ID found.')
            );
        }
        if (self::ActiveUser()->id == $userid) {
            return array(
                'status' => 400,
                'message' => __('You can not like your self.')
            );
        }
        $deleted = $db->where('notifier_id', $userid)->where('recipient_id', self::ActiveUser()->id)->where('type', 'got_new_match')->delete('notifications');
        $deleted = $db->where('notifier_id', self::ActiveUser()->id)->where('recipient_id', $userid)->where('type', 'got_new_match')->delete('notifications');
        $deleted = $db->where('user_id', $userid)->where('like_userid', self::ActiveUser()->id)->delete('likes');
        $deleted = $db->where('user_id', self::ActiveUser()->id)->where('like_userid', $userid)->delete('likes');
        if ($deleted) {
            return array(
                'status' => 200,
                'message' => __('User unmatched successfully.'),
                'userid' => $userid
            );
        } else {
            return array(
                'status' => 400,
                'message' => __('Error while save like.')
            );
        }
    }
    function send_mails(){
        global $db, $config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }

        if ($config->emailNotification == 0) {
            return array(
                'status' => 200,
            );
        }
        $send = SendMessageFromDB();
        if ($send) {
            return array(
                'status' => 200,
            );
        }

    }
    function hot(){
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if( Auth()->verified !== "1" ) {
            return array(
                'status' => 400,
                'message' => __('account_not_verified_text')
            );
        }
        $userid = null;
        if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ])) {
            $userid = (int) Secure($_POST[ 'userid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }

        $is_user_rate_before = $db->where('user_id', self::ActiveUser()->id)->where('hot_userid', $userid)->get('hot',null,array(0));
        if(empty($is_user_rate_before)){
            $db->where('id', $userid)->update('users', array(
                'hot_count' => $db->inc()
            ));
            $db->insert('hot', array('user_id'=> self::ActiveUser()->id, 'hot_userid'=> $userid, 'val' => "1", 'created_at' => time()));

        }
        return array(
            'status' => 200,
            'cookie' => $userid
        );

    }
    function not(){
        global $db;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if( Auth()->verified !== "1" ) {
            return array(
                'status' => 400,
                'message' => __('account_not_verified_text')
            );
        }
        $userid = null;
        if (isset($_POST[ 'userid' ]) && is_numeric($_POST[ 'userid' ])) {
            $userid = (int) Secure($_POST[ 'userid' ]);
            $user = $db->where('id', $userid)->getOne('users',array('id'));
            if(empty($user)){
                return array(
                    'status' => 400,
                    'message' => __('The User not exist.')
                );
            }
        } else {
            return array(
                'status' => 400,
                'message' => __('No User ID found.')
            );
        }

        $is_user_rate_before = $db->where('user_id', self::ActiveUser()->id)->where('hot_userid', $userid)->get('hot',null,array(0));
        if(empty($is_user_rate_before)){
            $db->where('id', $userid)->update('users', array(
                'hot_count' => $db->dec()
            ));
            $db->insert('hot', array('user_id'=> self::ActiveUser()->id, 'hot_userid'=> $userid, 'val' => "0", 'created_at' => time()));

        }
        return array(
            'status' => 200,
            'cookie' => $userid
        );
    }

    function yes_18(){
        global $db;
        setcookie("pop_up_18", 'yes', time() + (10 * 365 * 24 * 60 * 60), "/");
        return array(
            'status' => 200
        );
    }
    function no_18(){
        global $config;
        setcookie("pop_up_18", 'no', time() + ($config->time_18 * 60), "/");
        return array(
            'status' => 200
        );
    }
}
