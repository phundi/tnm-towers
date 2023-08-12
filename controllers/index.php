<?php
Class Index extends Theme {
    public static $page_data = array('title' => 'home');
    public static $partial = 'index';
    public static function init_data() {
        global $config,$db;
        parent::init_data();
        parent::$data['title'] = $config->default_title;
        parent::$data['keywords'] = $config->meta_keywords;
        parent::$data['description'] = $config->meta_description;
        $countries = Dataset::load('countries');
        if (!empty($countries)) {
            $db->where('country',array_keys($countries),'IN');
        }
        $users = $db->where('birthday','0000-00-00','!=')->where('verified','1')->where('active','1')->orderBy('RAND()')->get('users',20,['id']);
        if (!empty($countries)) {
            $db->where('country',array_keys($countries),'IN');
        }
        $pro_users = $db->where('birthday','0000-00-00','!=')->where('verified','1')->where('active','1')->where('is_pro',1)->orderBy('RAND()')->get('users',4,['id']);
        foreach ($users as $key => $user) {
            $users[$key] = userData($user['id']);
        }
        parent::$data['users'] = $users;
        foreach ($pro_users as $key => $user) {
            $pro_users[$key] = userData($user['id']);
        }
        parent::$data['pro_users'] = $pro_users;
        // if (isset(self::$page_data['title']) && self::$page_data['title'] !== '') {
        //     parent::$data['title'] = ucfirst(__('Home')) . ' . ' . $config->site_name;
        // }
        parent::$data['name'] = self::$partial;
    }
    public static function show($partial = '') {
        self::init_data();
        parent::show(self::$partial);
    }
}
