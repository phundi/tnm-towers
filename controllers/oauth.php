<?php
Class Oauth extends Theme {
    public static $page_data = array('title' => 'Oauth');
    public static $partial = 'oauth';
    public static function init_data() {
        global $config,$site_url;
        parent::init_data();
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        parent::$data['name'] = self::$partial;
        if (!empty($_GET['app_id'])) {
        	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			if (IS_LOGGED == false) {
				header("Location: ". $site_url.'/login' . '?last_url=' . urlencode($actual_link));
				exit();
			}
			else{
				$app = array();
				$app_valid = IsValidApp($_GET['app_id']);
				if ($app_valid === true) {
					$app_id = GetIdFromAppID($_GET['app_id']);
					$app = GetApp($app_id);
				    if (AppHasPermission(auth()->id, $app_id) === true) {
				    	$url = $app['app_website_url'];
				        if (isset($_GET['redirect_uri']) && !empty($_GET['redirect_uri'])) {
				            $url = $_GET['redirect_uri'];
				        } else if (!empty($app['app_callback_url'])) {
				            $url = $app['app_callback_url'];
				        }
				        $import = GenrateCode(auth()->id, $app_id);
				    	header("Location: {$url}?code=$import");
				        exit();
				    }
				    parent::$data['app_data'] = $app;
				} else {
				    header("Location: " . $site_url.'/login');
					exit();
				}
			}
		}
		else{
			header("Location: " . $site_url.'/login');
			exit();
		}
    }
    public static function show($partial = '') {
        self::init_data();
        parent::show(self::$partial);
    }
}