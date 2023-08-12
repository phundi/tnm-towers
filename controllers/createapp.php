<?php
Class CreateApp extends Theme {
    public static $page_data = array('title' => 'Create App');
    public static $partial = 'create-app';
    public static function init_data() {
        global $config;
        parent::init_data();
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['name'] = self::$partial;
        if ($config->developers_page != '1') {
            header('location: ' . self::Config()->uri);
            exit();
        }
    }
    public static function show($partial = array()) {
        self::init_data();
        parent::show(self::$partial);
    }
}