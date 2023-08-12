<?php
Class LiveUsers extends Theme {
    public static $page_data = array('title' => 'Live Users');
    public static $partial = 'live-users';

    public static function init_data() {
        global $config, $db;
        parent::init_data();

        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        parent::$data['name']         = self::$partial;
        $live_users = self::LoadLiveUsers();
        if ($live_users['status'] == 200) {
            parent::$data['live_users'] = $live_users['data'];
            parent::$data['live_users_html'] = $live_users['html'];
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
    public static function LoadLiveUsers() {
        global $_AJAX, $_CONTROLLERS;
        $live_users            = array();
        $ajax_class      = realpath($_CONTROLLERS . 'aj.php');
        $ajax_class_file = realpath($_AJAX . 'loadmore.php');
        if (file_exists($ajax_class_file)) {
            require_once $ajax_class;
            require_once $ajax_class_file;
            $loadmore      = new Loadmore();
            $live_users   = $loadmore->LoadLiveUsers();
        }
        return $live_users;
    }
}
