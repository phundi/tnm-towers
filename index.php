<?php
ob_start();
require_once realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$console_log = array();
$url_action   = (empty(route(1))) ? 'index' : str_replace('-', '', route(1));
$public_pages = array(
	'login',
	'register',
	'about',
	'contact',
	'forgot',
	'mail-otp',
	'privacy',
	'terms',
	'reset',
	'profile',
	'aj',
	'worker',
    'page',
    'article',
    'unusual-login',
    'blog',
    'oauth',
    'authorize',
    'age-block',
);
$active_user = auth();
$config->nextmode_text = __('Night mode');
$config->is_rtl = false;
if(GetActiveLang() == 'arabic'){
    $config->is_rtl = true;
}

//if (!isset($_COOKIE['activeLang'])) {
//    setcookie("activeLang", $config->default_language, time() + (10 * 365 * 24 * 60 * 60), '/');
//}

// night mode
if (empty($_COOKIE['mode'])) {

    if (!empty($config->displaymode)) {
        if ($config->displaymode == 'day') {
            setcookie("mode", 'day', time() + (10 * 365 * 24 * 60 * 60), '/');
            $_COOKIE['mode'] = 'day';
            $config->displaymode = 'day';
            $config->nextmode = 'night';
            $config->nextmode_text = __('Night mode');
        } else if ($config->displaymode == 'night') {
            setcookie("mode", 'night', time() + (10 * 365 * 24 * 60 * 60), '/');
            $_COOKIE['mode'] = 'night';
            $config->displaymode = 'night';
            $config->nextmode = 'day';
            $config->nextmode_text = __('Day mode');
        }
    }

} else {
    if ($_COOKIE['mode'] == 'day') {
        $config->displaymode = 'day';
        $config->nextmode = 'night';
        $config->nextmode_text = __('Night mode');
    }
    if ($_COOKIE['mode'] == 'night') {
        $config->displaymode = 'night';
        $config->nextmode = 'day';
        $config->nextmode_text = __('Day mode');
    }
}

if (!empty($_GET['mode'])) {
    if ($_GET['mode'] == 'day') {
        setcookie("mode", 'day', time() + (10 * 365 * 24 * 60 * 60), '/');
        $_COOKIE['mode'] = 'day';
        $config->displaymode = 'day';
        $config->nextmode = 'night';
        $config->nextmode_text = __('Night mode');
    } else if ($_GET['mode'] == 'night') {
        setcookie("mode", 'night', time() + (10 * 365 * 24 * 60 * 60), '/');
        $_COOKIE['mode'] = 'night';
        $config->displaymode = 'night';
        $config->nextmode = 'day';
        $config->nextmode_text = __('Day mode');
    }
}

$default_logo = $_BASEPATH . 'themes' . $_DS . $config->theme  . $_DS . 'assets' . $_DS . 'img' . $_DS . 'logo.png';
$light_logo = $_BASEPATH . 'themes' . $_DS . $config->theme . $_DS . 'assets' . $_DS . 'img' . $_DS . 'logo-light.png';

$config->sitelogo = $config->uri . '/themes/' . $config->theme . '/assets/img/logo.png';
$config->siteIcon = $config->uri . '/themes/' . $config->theme . '/assets/img/icon.png';
if( file_exists($light_logo) ){
    if( $config->displaymode == 'night' ) {
        $config->sitelogo = $config->uri . '/themes/' . $config->theme . '/assets/img/logo-light.png';
    }
}

if (!empty($_GET['ref']) && IS_LOGGED == false){// && !isset($_COOKIE['src'])) {
    $get_ip = get_ip_address();
    if (!isset($_SESSION['ref'])){// && !empty($get_ip)) {
        $_GET['ref'] = Secure($_GET['ref']);
        $ref_user_id = UserIdFromUsername($_GET['ref']);
        $user_date = Wo_UserData($ref_user_id);
        if (!empty($user_date)) {
            //if (ip_in_range($user_date['ip_address'], '/24') === false && $user_date['ip_address'] != $get_ip) {
            $_SESSION['ref'] = $user_date['username'];
            @setcookie('ref', $user_date['username'], time() + 31556926, '/');
            //}
        }
    }
}
if (!isset($_COOKIE['src'])) {
    @setcookie('src', '1', time() + 31556926, '/');
}

if(IS_LOGGED === true){

}else{
    logout(false);
}
if( !isset( $_COOKIE['JWT'] ) ) {
    logout(false);
}else{
    $u = $db->objectBuilder()->where('id',GetUserFromSessionID($_COOKIE['JWT']))->get('users',1,array('web_token','start_up','active','web_token_created_at','verified'));
    if( !empty($u)) {
        $_SESSION['JWT'] = $u[0];
    }
    $_SESSION['user_id'] = $_COOKIE['JWT'];
}

if( isset($_COOKIE['JWT']) && isset( $_SESSION['user_id'] ) ){
    if( $_COOKIE['JWT'] !== $_SESSION['user_id'] ){
        logout();
    }
}

if( $url_action == 'admincp' ){
    require 'admin-panel/autoload.php';
    exit();
}
if (!empty($_COOKIE['maintenance_mode']) && $_COOKIE['maintenance_mode'] == 'no') {
    if (IS_LOGGED && !empty($active_user) && $active_user->admin === "0") {

    }
    else{
        $config->maintenance_mode = 0;
    } 
}
$baned_ips = Wo_GetBanned('user');
if (in_array($_SERVER["REMOTE_ADDR"], $baned_ips)) {
    exit();
}

if( $url_action == 'Useractions' && route(2) == 'login' ){
    header("Location: ./");
    exit();
}

$maintenance_mode = false;
if ( $config->maintenance_mode == 1 ) {
    if ( IS_LOGGED === false ) {
        $maintenance_mode = true;
        //http://localhost/quickdatescript.com/?access=admin
        if(isset($_GET['access']) && $_GET['access'] == 'admin'){
            setcookie("maintenance_mode", 'no', time() + (10 * 365 * 24 * 60 * 60), '/');
            $maintenance_mode = false;
        }
    } else {
        if($active_user) {
            if ($active_user->admin === "0") {
                $maintenance_mode = true;
            }
        }
    }
    if( $maintenance_mode === true ){
        $maintenance_contoller_file       = $_CONTROLLERS . 'maintenance.php';
        if (file_exists($maintenance_contoller_file)) {
            require_once $maintenance_contoller_file;
            Maintenance::show();
            exit();
        }

    }
}
$q['likes_count'] = 0;
$q['following_count'] = 0;
$q['views_count'] = 0;
$q['matches_count'] = 0;

if (IS_LOGGED && !empty($active_user)) {
    $q['likes_count'] = $db->where('like_userid',$active_user->id)->where('is_like', 1)->getValue('likes','COUNT(*)');
    $q['following_count'] = $db->where('following_id',$active_user->id)->getValue('followers','COUNT(*)');
    $q['matches_count'] = $db->where('notifier_id',$user->id)->where('type','got_new_match')->getValue('notifications','COUNT(*)');    $q['views_count'] = $db->where('view_userid ',$active_user->id)->getValue('views ','COUNT(*)');
    $q['liked_count'] = $db->where('user_id',$active_user->id)->where('is_like', 1)->getValue('likes','COUNT(*)');
    $q['disliked_count'] = $db->where('user_id',$active_user->id)->where('is_dislike', 1)->getValue('likes','COUNT(*)');
}

$contoller_file       = $_CONTROLLERS . strtolower($url_action) . '.php';
if (file_exists($contoller_file)) {
    if (!empty($_GET)) {
        foreach ($_GET as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
                $value = preg_replace('/\((.*?)\)/m', '', $value);
                $_GET[$key] = strip_tags($value);
            }
        }
    }
    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
                $_POST[$key] = strip_tags($value);
            }
        }
    }
    require_once $contoller_file;
}
if ($config->pop_up_18 == 'on' && (!empty($_COOKIE['pop_up_18']) && $_COOKIE['pop_up_18'] == 'no' && route(1) != 'age-block' && !IS_LOGGED)) {
    header('Location: ' . $config->uri . '/age-block');
    exit();
}

if (IS_LOGGED === true) {
    if ($url_action == 'register' || $url_action == 'forgot' || $url_action == 'reset') {
        header("Location: ./");
        exit();
    }
}


$contoller_index_file = $_CONTROLLERS . 'findmatches.php';

if (isset($_SESSION['JWT']) && !empty($_SESSION['JWT'])) {

    if(strpos($_SERVER["REQUEST_URI"],'/aj/') === false) {
        $stop = false;
        if (!empty($active_user)) {
            if( $config->image_verification == 1 && $config->pending_verification == 1 && $active_user->approved_at == 0 ){
                $stop = true;
            }
            if ($active_user->start_up == "0" || $active_user->start_up == "1" || $active_user->start_up == "2") {
                $stop = true;
            }
            //TODO::
            if( $config->image_verification == 1 ){
                if( (int)$config->image_verification_start < (int)$active_user->registered && $active_user->approved_at > 0){
                    $stop = false;
                // }else{
                //     $stop = true;
                }
            }
            //TODO::
            if ($active_user->admin == "1" || ($active_user->start_up == "3" && $active_user->admin == "0")) {
                $stop = false;
            }
            if ($config->emailValidation == 1 && $active_user->verified != 1) {
                $stop = true;
            }
            if ($active_user->start_up == 3 && $active_user->verified != 1) {
                $stop = true;
            }
        }
            
            
            
        if( $stop === true ){
            $contoller_userverify_file = $_CONTROLLERS . 'steps.php';
            if (file_exists($contoller_userverify_file)) {
                if (!class_exists('Steps', false)) {
                    require_once $contoller_userverify_file;
                }
                Steps::show();
                exit();
            }
        }
    }
    if (!empty($active_user)) {

        if ($active_user->verified == 0) {
            if (route(1) == 'verifymail' || route(1) == 'verifymailotp' || route(1) == 'verifyphone' || route(1) == 'verifyphoneotp' || route(1) == 'aj') {
                call_user_func(array(
                    ucfirst($url_action),
                    'show'
                ));
            } else {
    //            if( $config->emailValidation == "1" ) {
    //                $contoller_userverify_file = $_CONTROLLERS . 'steps.php';
    //                if (file_exists($contoller_userverify_file)) {
    //                    if (!class_exists('Steps', false)) {
    //                        require_once $contoller_userverify_file;
    //                    }
    //                    Steps::show();
    //                }
    //            }
                $contoller_index_file = $_CONTROLLERS . 'findmatches.php';
                if (file_exists($contoller_index_file)) {
                    if( !class_exists('FindMatches',false) ){
                        require_once $contoller_index_file;
                    }
                    FindMatches::show();
                }
            }
        }
    }

    // if (route(1) == '') {
    //     $contoller_index_file = $_CONTROLLERS . 'findmatches.php';
    //     if (file_exists($contoller_index_file)) {
    //         if( !class_exists('FindMatches',false) ){
    //             require_once $contoller_index_file;
    //         }
    //         FindMatches::show();
    //     }

    if (route(1) === NULL) {
        header('Location: ' . $config->uri . '/find-matches');
        exit();
    } else {
        if (strpos($url_action, 'settings') !== false && IS_LOGGED) {
            $route_2 = route(2);
            if (!empty($route_2)) {
                $_user2 = LoadEndPointResource('users');
                $setting_id = $_user2->isUsernameExists($route_2);
                if (!$active_user->admin && intval($setting_id['id']) != intval($active_user->id)) {
                    header('Location: ' . $config->uri . '/find-matches');
                    exit();
                }
            }
        }

        if( substr($url_action, 0, 1) == '@' ){
            $username = strtolower(substr($url_action, 1));


            if( strtolower($active_user->username) == $username ){
                $contoller_myprofile_file = $_CONTROLLERS . 'myprofile.php';
                if (file_exists($contoller_myprofile_file)) {
                    if( !class_exists('Myprofile',false) ){
                        require_once $contoller_myprofile_file;
                    }
                    Myprofile::show();
                }
            }else{
                $_user = LoadEndPointResource('users');
                $user = $_user->isUsernameExists($username);
                if( isset( $user['id'] ) && $user['id'] > 0 ){


                    if(isUserInBlockList(strtolower($username)) === false) {
                        //if( $user->verified !== "1" ) {
                            //header('Location: ' . $config->uri);
                            //exit();
                        //}

                        $contoller_profile_file = $_CONTROLLERS . 'profile.php';
                        if (file_exists($contoller_profile_file)) {
                            if( !class_exists('Profile',false) ){
                                require_once $contoller_profile_file;
                            }
                            Profile::show();
                        }
                    }else{


                        if(strpos($_SERVER["REQUEST_URI"],'/aj/') === false) {
                            header('Location: ' . $config->uri);
                        }else{
                            echo "<script>window.location.href = window.site_url;</script>";
                        }
                        exit();
                    }

                }else{
                    header('Location: ' . $config->uri);
                    exit();
                }
            }

            exit();
        }
        if (ctype_alpha($url_action)) {
            if (is_callable(array(
                ucfirst($url_action),
                'show'
            ))) {
                call_user_func(array(
                    ucfirst($url_action),
                    'show'
                ));
            } else {
                header('Location: ' . $config->uri);
                exit();
            }
        } else {
            header('Location: ' . $config->uri);
            exit();
        }
    }

}else{

    if (ctype_alpha($url_action)) {
		if (in_array(route(1), $public_pages)) {
			if (is_callable(array(
				ucfirst($url_action),
				'show'
			))) {
				call_user_func(array(
					ucfirst($url_action),
					'show'
				));
			} else {
				header('Location: ' . $config->uri);
				exit();
			}
		} else {
            $contoller_home_file  = $_CONTROLLERS . 'index.php';
            if (file_exists($contoller_home_file)) {
				if( !class_exists('Index',false) ){
                    require_once $contoller_home_file;
				}
                Index::show();
            }
		}
	} else {
        if( $config->show_user_on_homepage == '1'){
            if( substr($url_action, 0, 1) == '@' ) {
                $username = strtolower(substr($url_action, 1));
                $_user = LoadEndPointResource('users');
                $user = $_user->isUsernameExists($username);
                if( isset( $user['id'] ) && $user['id'] > 0 ){


                    if(isUserInBlockList(strtolower($username)) === false) {
//                        if( $user->verified !== "1" ) {
//                            header('Location: ' . $config->uri);
//                            exit();
//                        }

                        $contoller_profile_file = $_CONTROLLERS . 'profile.php';
                        if (file_exists($contoller_profile_file)) {
                            if( !class_exists('Profile',false) ){
                                require_once $contoller_profile_file;
                            }
                            Profile::show();
                        }
                    }else{


                        if(strpos($_SERVER["REQUEST_URI"],'/aj/') === false) {
                            header('Location: ' . $config->uri);
                        }else{
                            echo "<script>window.location.href = window.site_url;</script>";
                        }
                        exit();
                    }

                }else{
                    header('Location: ' . $config->uri);
                    exit();
                }
            }
        }else {
            header('Location: ' . $config->uri);
            exit();
        }
	}
}


$config = null;
$lang = null;
mysqli_close($conn);
$db = null;
$conn = null;