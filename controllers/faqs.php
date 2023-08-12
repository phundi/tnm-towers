<?php
Class Faqs extends Theme {
    public static $page_data = array('title' => 'FAQs');
    public static $partial = 'faqs';
    public static function init_data() {
        global $config,$db;
        parent::init_data();
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        parent::$data['name'] = self::$partial;
        parent::$data['faqs'] = $db->objectbuilder()->orderBy('id', 'DESC')->get('faqs');
    }
    public static function show($partial = '') {
        self::init_data();
        parent::show(self::$partial);
    }
}