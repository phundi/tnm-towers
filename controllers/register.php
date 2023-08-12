<?php
Class Register extends Theme {
    public static $page_data = array('title' => 'Register');
    public static $partial = 'register';
    public static function init_data() {
        global $config;
        parent::init_data();
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        // if (isset(self::$page_data['title']) && self::$page_data['title'] !== '') {
        //     parent::$data['title'] = ucfirst(__('Register')) . ' . ' . $config->site_name;
        // }
        parent::$data['name'] = self::$partial;
    }
    public static function show($partial = '') {
        global $config;
        if ($config->user_registration != 1 && (!isset($_GET['invite']) || (!IsAdminInvitationExists($_GET['invite']) && !IsUserInvitationExists($_GET['invite'])) )) {
            header('location: ' . self::Config()->uri);
            exit();
        }
        else{
            self::init_data();
            parent::show(self::$partial);
        }
    }
}