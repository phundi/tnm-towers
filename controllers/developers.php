<?php
Class Developers extends Theme {
    public static $page_data = array('title' => 'Developers');
    public static $partial = 'developers';
    public static function init_data() {
        global $config;
        parent::init_data();
        if ($config->developers_page != '1') {
            header('location: ' . self::Config()->uri);
            exit();
        }
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        parent::$data['name'] = self::$partial;
    }
    public static function show($partial = '') {
        self::init_data();
        parent::show(self::$partial);
    }
}