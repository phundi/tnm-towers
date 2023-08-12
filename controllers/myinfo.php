<?php
Class Myinfo extends Theme {
    public static $page_data = array('title' => 'My Information');
    public static $partial = 'my_info';
    public static function init_data() {
        global $config;
        parent::init_data();
        if (isset(self::$page_data['title']) && self::$page_data['title'] !== '') {
            parent::$data['title'] = ucfirst(__('My Information')) . ' . ' . $config->site_name;
        }
        parent::$data['name'] = self::$partial;
    }
    public static function show($partials = array()) {
        self::init_data();
        parent::show(self::$partial);
    }
}
