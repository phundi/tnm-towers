<?php
Class Video extends Theme {
    public static $page_data = array('title' => 'Live Video');
    public static $partial = 'video';

    public static function init_data() {
        global $config, $db;
        parent::init_data();

        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        parent::$data['name']         = self::$partial;
        if (!empty(route(2)) && is_numeric(route(2)) && route(2) > 0) {
            $live_video = self::GetLiveVideo(route(2));
            if (!empty($live_video) && $live_video['status'] == 200) {
                parent::$data['live_video'] = $live_video['data'];
                parent::$data['video_comments'] = $live_video['comments_html'];
            }
            else{
                header('location: ' . self::Config()->uri);
                exit();
            }
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
    public static function GetLiveVideo($id) {
        global $_AJAX, $_CONTROLLERS;
        $live            = array();
        $ajax_class      = realpath($_CONTROLLERS . 'aj.php');
        $ajax_class_file = realpath($_AJAX . 'loadmore.php');
        if (file_exists($ajax_class_file)) {
            require_once $ajax_class;
            require_once $ajax_class_file;
            $loadmore      = new Loadmore();
            $live   = $loadmore->GetLiveVideo($id);
        }
        return $live;
    }
}
