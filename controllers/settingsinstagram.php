<?php
Class SettingsInstagram extends Theme {
    public static $page_data = array('title' => 'Instergram Importer');
    public static $partial = 'settings-instagram';
    public static function init_data() {
        global $config;
        parent::init_data();
        parent::$data['title'] = __('instergram_importer') . ' . ' . $config->site_name;
        parent::$data['name'] = self::$partial;
    }
    public static function show($partials = array()) {
        self::init_data();
        parent::show(self::$partial);
    }
}