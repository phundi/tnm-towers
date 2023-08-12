<?php
Class Live extends Theme {
    public static $page_data = array('title' => 'Live Video');
    public static $partial = 'live';
    public static function init_data() {
        global $config,$q, $db;
        parent::init_data();
        parent::$data['title'] = GetPageTitle(self::$partial);
        parent::$data['keywords'] = GetPageKeyword(self::$partial);
        parent::$data['description'] = GetPageDescription(self::$partial);
        parent::$data['name'] = self::$partial;
        $if_live = $db->where('user_id',auth()->id)->where('stream_name','','!=')->where('live_time',time() - 5,'>=')->getValue('posts','COUNT(*)');
        if ($if_live > 0) {
            header('location: ' . self::Config()->uri);
            exit();
        }
    }
    public static function show($partial = '') {
    	global $config,$q,$_LIBS,$_BASEPATH,$_DS;
    	$q['AgorachannelName'] = "stream_".auth()->id.'_'.rand(1111111,9999999);
        $q['AgoraToken'] = null;
    	if (!empty($config->agora_app_certificate)) {
            include_once $_LIBS . 'AgoraDynamicKey'.$_DS.'src'.$_DS.'RtcTokenBuilder.php';
            $appID = $config->agora_app_id;
            $appCertificate = $config->agora_app_certificate;
            $uid = 0;
            $uidStr = "0";
            $role = RtcTokenBuilder::RoleAttendee;
            $expireTimeInSeconds = 36000000;
            $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
            $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;
            $q['AgoraToken'] = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $q['AgorachannelName'], $uid, $role, $privilegeExpiredTs);
        }
        self::init_data();
        parent::show(self::$partial);
    }
}