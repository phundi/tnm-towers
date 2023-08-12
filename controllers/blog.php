<?php
Class Blog extends Theme {
    public static $page_data = array('title' => 'Blog');
    public static $partial = 'blog';
    public static function init_data() {
        global $config,$db;
        parent::init_data();
        if (isset(self::$page_data['title']) && self::$page_data['title'] !== '') {
            parent::$data['title'] = ucfirst(__('Blog')) . ' . ' . $config->site_name;
        }
        parent::$data['name'] = self::$partial;
        parent::$data['articles'] = self::Articles();
        $today_start = strtotime(date('M')." ".date('d').", ".date('Y')." 12:00am");
        parent::$data['today_blog'] = $db->where('created_at',$today_start,'>=')->orderBy('view','DESC')->getOne('blog');
        parent::$data['popular_blogs'] = $db->orderBy('RAND()')->get('blog',5);
    }
    public static function show($partial = '') {
        self::init_data();
        parent::show(self::$partial);
    }
    public static function Articles() {
        global $_AJAX, $_CONTROLLERS;
        $data            = '';
        $ajax_class      = realpath($_CONTROLLERS . 'aj.php');
        $ajax_class_file = realpath($_AJAX . 'loadmore.php');
        if (file_exists($ajax_class_file)) {
            require_once $ajax_class;
            require_once $ajax_class_file;
            $_POST['page'] = 1;
            $loadmore      = new Loadmore();
            $match_users   = $loadmore->articles();
            if (isset($match_users['html'])) {
                $data = $match_users['html'];
            }
        }
        return $data;
    }

}