<?php
Class AgeBlock extends Theme {
    public static $page_data = array('title' => 'My Apps');
    public static $partial = 'age_block';
    public static function init_data() {
        global $config;
        parent::init_data();
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['name'] = self::$partial;
        parent::$data['apps_data'] = GetApps();
    }
    public static function show($partial = array()) {
        self::init_data();
        parent::show(self::$partial);
    }
}