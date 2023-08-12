<?php
Class UserLive extends Theme {
    public static $page_data = array('title' => 'Live Videos');
    public static $partial = 'user-live';

    public static function init_data() {
        global $config, $db;
        parent::init_data();

        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        parent::$data['name']         = self::$partial;
        if (!empty(route(2)) && is_numeric(route(2)) && route(2) > 0) {
            $user = userData(Secure(route(2)));
            if (!empty($user)) {
                $live_videos = self::LoadUserLive($user->id);
                if ($live_videos['status'] == 200) {
                    parent::$data['live_users'] = $live_videos['data'];
                    parent::$data['live_users_html'] = $live_videos['html'];
                    parent::$data['user'] = $user;
                }
            }
            else{
                header('location: ' . self::Config()->uri);
                exit();
            }
        }
        else{
            header('location: ' . self::Config()->uri);
            exit();
        }
    }
    public static function show($partial = '') {
        self::init_data();
        parent::show(self::$partial);
    }
    public static function LoadUserLive($id) {
        global $_AJAX, $_CONTROLLERS;
        $live_users            = array();
        $ajax_class      = realpath($_CONTROLLERS . 'aj.php');
        $ajax_class_file = realpath($_AJAX . 'loadmore.php');
        if (file_exists($ajax_class_file)) {
            require_once $ajax_class;
            require_once $ajax_class_file;
            $loadmore      = new Loadmore();
            $live_users   = $loadmore->LoadUserLive($id);
        }
        return $live_users;
    }
}
