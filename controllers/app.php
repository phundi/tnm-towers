<?php
Class App extends Theme {
    public static $page_data = array('title' => 'Show App');
    public static $partial = 'app';
    public static function init_data() {
        global $config;
        parent::init_data();
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['name'] = self::$partial;
        if ($config->developers_page != '1') {
            header('location: ' . self::Config()->uri);
            exit();
        }
        if (!empty(route(2)) && is_numeric(route(2)) && route(2) > 0) {
            parent::$data['app_data'] = GetApp(Secure(route(2)));
        }
        else{
            header('location: ' . self::Config()->uri);
            exit();
        }
    }
    public static function show($partial = array()) {
        self::init_data();
        parent::show(self::$partial);
    }
}