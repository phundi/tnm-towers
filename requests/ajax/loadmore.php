<?php
Class Loadmore extends Aj {
    function random_users() {
        global $db, $_BASEPATH, $_DS,$config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';

        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }


//        $genders = GetGenders(self::ActiveUser());
//
//
//
//
//
//        $where_and = '';
//        if (isset($_SESSION[ '_gender' ]) && $_SESSION[ '_gender' ] !== '') {
//            if( !empty($_SESSION[ '_gender' ]) ) {
//                $_genders = @explode( ',' , $_SESSION[ '_gender' ] );
//                if($config->opposite_gender == "1"){
//                    foreach($_genders as $key => $value ){
//                        if($value == self::ActiveUser()->gender){
//                            unset($_genders[$key]);
//                        }
//                    }
//                }
//                $_genders = @implode( ',' , $_genders );
//                $where_and = '`gender` IN (' . $_genders . ')';
//            }else{
//                $where_and = '`gender` IN ('.$genders.')';
//            }
//        }else{
//            $where_and = '`gender` IN ('.$genders.')';
//        }


        $genders = null;

        if( self::Config()->opposite_gender == "1" ) {
            if ($genders == null) {
                $genders = GetGenders(self::ActiveUser());
            }
        }else{
            if( isset($_SESSION['_gender']) && $_SESSION['_gender'] !== ''){
                $genders = Secure( $_SESSION['_gender'] );
            }
            if( isset($_POST['_gender']) && $_POST['_gender'] !== ''){
                $_SESSION[ '_gender' ] = $_POST['_gender'];
                $genders = Secure( $_POST['_gender'] );
            }
        }

        if( is_array($genders) ){
            $genders = @implode( ',' , $genders );
        }else{
            $genders = @explode( ',' , $genders );

            if($config->opposite_gender == "1"){
                foreach($genders as $key => $value ){
                    if($value == self::ActiveUser()->gender){
                        unset($genders[$key]);
                    }
                }
            }
            $genders = @implode( ',' , $genders );
        }

        $where_and = '';
        if( !empty($genders) ){

            if( strpos( $genders, ',' ) === false ) {
                $gender_query = '`gender` = "'. $genders .'"';
                $where_and = ' AND ' .$gender_query;
            }else{
                $gender_query = '`gender` IN ('. $genders .')';
                $where_and = ' AND ' .$gender_query;
            }
        }


        $where_country = ' ';

//        if( !empty(self::ActiveUser()->show_me_to) ){
//            $where_country .= ' AND `country` = "'. self::ActiveUser()->show_me_to . '" ';
//        }

        // if(self::ActiveUser()->country !== '' || self::ActiveUser()->show_me_to !== '' ) {
        //     $where_country .= ' AND ( ';
        //     if (self::ActiveUser()->country !== '') {
        //         $where_country .= ' `country` = "' . self::ActiveUser()->country . '" ';
        //     }

        //     if (self::ActiveUser()->country !== '' && self::ActiveUser()->show_me_to !== '') {
        //         $where_country .= ' OR ';
        //     }

        //     if (self::ActiveUser()->show_me_to !== '') {
        //         $where_country .= ' `country` = "' . self::ActiveUser()->show_me_to . '" ';
        //     }
        //     $where_country .= ' ) ';
        // }
        $dist_query = '';
        
        $json = array();
        if (!empty(self::ActiveUser()) && !empty(self::ActiveUser()->find_match_data)) {
            $json = json_decode(self::ActiveUser()->find_match_data,true);
        }
        if ((!empty($json['rand_lat']) && !empty($json['rand_lng']) && !empty($json['rand_located'])) || !empty($json['rand_country'])) {
            $dist_query = ' AND ( ';
        }
        if (!empty($json['rand_lat']) && !empty($json['rand_lng']) && !empty($json['rand_located'])) {
            $located = $json['rand_located'];
            $lat = $json['rand_lat'];
            $lng = $json['rand_lng'];

            $distance = ' ROUND( ( 6371 * acos(cos(radians(' . $lat . ')) * cos(radians(`lat`)) * cos(radians(`lng`) - radians(' . $lng . ')) + sin(radians(' . $lat . ')) * sin(radians(`lat`)))) ,1) ';
            $dist_query .= $distance . ' <= ' . $located ;
        }
        if (!empty($json['rand_country'])) {
            if (!empty($json['rand_lat']) && !empty($json['rand_lng']) && !empty($json['rand_located'])) {
                $dist_query .= ' OR ';
            }
            if ($json['rand_country'] != 'all') {
                $dist_query .= ' `country` = "' . $json['rand_country'] . '" ';
            }
            else{
                $dist_query .= ' `country` != "" ';
            }
        }
        if ((!empty($json['rand_lat']) && !empty($json['rand_lng']) && !empty($json['rand_located'])) || !empty($json['rand_country'])) {
            $dist_query .= ' ) ';
        }


        // $mycountry = self::ActiveUser()->show_me_to;
        // if(empty(self::ActiveUser()->show_me_to)){
        //     $mycountry = self::ActiveUser()->country;
        // }

        // if ($mycountry == self::ActiveUser()->country) {
        //     //var_dump('activate distance filter');
        //     $located = 7;
        //     $lat = 0;
        //     $lng = 0;
        //     if( isset( $_SESSION['_lat'] ) && !empty($_SESSION['_lat']) ) $lat = Secure($_SESSION['_lat']);
        //     if( isset( $_POST['_lat'] ) && !empty($_POST['_lat']) ) $lat = Secure($_POST['_lat']);

        //     if( isset( $_SESSION['_lng'] )  && !empty($_SESSION['_lng'])) $lng = Secure($_SESSION['_lng']);
        //     if( isset( $_POST['_lng'] ) && !empty($_POST['_lng']) ) $lng = Secure($_POST['_lng']);

        //     if( isset( $_SESSION['_located'] ) && !empty($_SESSION['_located']) ) $located = Secure($_SESSION['_located']);
        //     if( isset( $_POST['_located'] ) && !empty($_POST['_located']) ) $located = Secure($_POST['_located']);
        //     if (empty($lat) && !empty(self::ActiveUser()->lat)) {
        //         $lat = self::ActiveUser()->lat;
        //     }
        //     if (empty($lng) && !empty(self::ActiveUser()->lng)) {
        //         $lng = self::ActiveUser()->lng;
        //     }
        //     //var_dump('distance : ' . $located);
        //     //var_dump('lat : ' . $lat . ', lng :' . $lng);

        //     $distance = ' AND ROUND( ( 6371 * acos(cos(radians(' . $lat . ')) * cos(radians(`lat`)) * cos(radians(`lng`) - radians(' . $lng . ')) + sin(radians(' . $lat . ')) * sin(radians(`lat`)))) ,1) ';
        //     $dist_query = $distance . ' <= ' . $located . ' AND `country` = "' . $mycountry . '"';

        // } else {
        //     //var_dump('activate country filter');
        //     $dist_query = ' AND `country` = "' . $mycountry . '"';
        // }

        $age_query = '';
        // check age from post or from session
        if( isset($_POST['_age_from']) && !empty($_POST['_age_from']) && isset($_POST['_age_to']) && !empty($_POST['_age_to']) ){
            $age_query = ' AND (DATEDIFF(CURDATE(), `birthday`)/365 >= "'. Secure($_POST['_age_from']) .'" AND DATEDIFF(CURDATE(), `birthday`)/365 <= "'. Secure($_POST['_age_to']) . '") ';
        }else{
            if(isset( $_SESSION['_age_from'] ) && isset( $_SESSION['_age_to'] )) {
                $age_query = ' AND (DATEDIFF(CURDATE(), `birthday`)/365 >= "'. Secure($_SESSION['_age_from']) .'" AND DATEDIFF(CURDATE(), `birthday`)/365 <= "'. Secure($_SESSION['_age_to']) . '") ';
            }else{
                $age_query = ' AND (DATEDIFF(CURDATE(), `birthday`)/365 >= "18" AND DATEDIFF(CURDATE(), `birthday`)/365 <= "55") ';
            }
        }

        if ($error == '') {
            $sql = 'SELECT `id`,`online`,`lastseen`,`username`,`avater`,`country`,`first_name`,`last_name`,`location`,`birthday`,`language`,`relationship`,`height`,`body`,`smoke`,`ethnicity`,`pets`,`gender`,`district` FROM `users` ';
            $sql .= ' WHERE  ';
            $sql .= ' `id` <> "'.self::ActiveUser()->id.'" AND `active` = "1" AND `verified` = "1" AND `privacy_show_profile_random_users` = "1" ';

            // to exclude blocked users
            $notin = ' AND `id` NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.') ';
            // to exclude liked and disliked users users
            $notin .= ' AND `id` NOT IN (SELECT `like_userid` FROM `likes` WHERE ( `user_id` = '.self::ActiveUser()->id.' ) ) ';

            //if( isset( $_SESSION['homepage'] ) && $_SESSION['homepage'] == false ) {
                $sql .= '   ' . $where_and . $notin . $dist_query . $age_query;
            // }else{
            //     $sql .= '   ' . $where_and . $notin;
            // }


            $sql .= ' ORDER BY `boosted_time` DESC, `xlikes_created_at` DESC,`xvisits_created_at` DESC,`user_buy_xvisits` DESC,`is_pro` DESC,`hot_count` DESC,`id` DESC LIMIT '.$perpage.' OFFSET '.$page * $perpage.';';
            //print_r($sql);
            //exit($sql);
            $random_users        = $db->objectBuilder()->rawQuery($sql);

            $theme_path          = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template            = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'random_users.php';
            if (file_exists($template)) {
                global $config;
                foreach ($random_users as $random_user) {
                    ob_start();
                    if(!is_avatar_approved($random_user->id, $random_user->avater)) {
                        $random_user->avater = $config->userDefaultAvatar;
                    }
                    $all_genders = Dataset::load('gender');
                    if (empty($random_user->gender) && !empty($all_genders) && !empty(array_keys($all_genders))) {
                        $random_user->gender = array_keys($all_genders)[0];
                        $db->where('id',$random_user->id)->update('users',['gender' => array_keys($all_genders)[0]]);
                    }
                    require($template);
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function liked_users($all = false,$with_html = true) {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        $page_limit = null;
        if (!$all) {
            if (isset($_POST) && !empty($_POST)) {
                if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                    $error = '<p>• ' . __('no page number found.') . '</p>';
                } else {
                    $page = (int) Secure($_POST[ 'page' ]) - 1;
                }
            }
            $page_limit =   array(
                                $page * $perpage,
                                $perpage
                            );
        }
        if ($error == '') {
            $db->objectBuilder()->join('users u', 'l.like_userid=u.id', 'LEFT')
                ->where('l.user_id', self::ActiveUser()->id)
                ->where('l.is_like', '1')
                ->where('u.verified', '1')
                ->where('l.like_userid', self::ActiveUser()->id, '<>')
                ->groupBy('l.like_userid')
                ->orderBy('l.created_at', 'DESC');

            // to exclude blocked users
            $db->where('l.like_userid NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');

            $liked_users        = $db->get('likes l', $page_limit, array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.district',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'max(l.created_at) as created_at'
            ));
            foreach ($liked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'liked.php';
            if ($with_html) {
                if (file_exists($template)) {
                    global $config;
                    foreach ($liked_users as $row) {
                        ob_start();
                        if(!is_avatar_approved($row->id, $row->avater)) {
                            $row->avater = $config->userDefaultAvatar;
                        }
                        include $template;
                        $html .= ob_get_contents();
                        ob_end_clean();
                    }
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html,
                'data' => $liked_users
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function likes_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $db->objectBuilder()->join('users u', 'l.user_id=u.id', 'LEFT')
                ->where('l.like_userid', self::ActiveUser()->id)
                ->where('l.is_like', "1")
                ->where('l.user_id', self::ActiveUser()->id, '<>')
                ->groupBy('l.user_id')
                ->orderBy('l.created_at', 'DESC');

            // to exclude blocked users
            $db->where('l.user_id NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');



            $liked_users        = $db->get('likes l', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.district',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'max(l.created_at) as created_at'
            ));
            foreach ($liked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'likes.php';
            global $config;
            if (file_exists($template)) {
                foreach ($liked_users as $row) {
                    ob_start();
                    if(!is_avatar_approved($row->id, $row->avater)) {
                        $row->avater = $config->userDefaultAvatar;
                    }
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function gifts_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $db->objectBuilder()->join('users u', 'g.from=u.id', 'LEFT')
                ->where('g.to', self::ActiveUser()->id)
                ->where('g.from', self::ActiveUser()->id, '<>')
                //->groupBy('g.from')
                ->orderBy('g.time', 'DESC');
            // to exclude blocked users
            $db->where('g.from NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');
            $liked_users        = $db->get('user_gifts g', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.district',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'g.id',
                'g.gift_id',
                '(SELECT media_file FROM gifts WHERE id = g.gift_id) as gift_media_file',
                'g.time'
            ));
            foreach ($liked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'gifts.php';
            global $config;
            if (file_exists($template)) {
                foreach ($liked_users as $row) {
                    ob_start();
                    if(!is_avatar_approved($row->id, $row->avater)) {
                        $row->avater = $config->userDefaultAvatar;
                    }
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function disliked_users($all = false,$with_html = true) {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        $page_limit = null;
        if (!$all) {
            if (isset($_POST) && !empty($_POST)) {
                if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                    $error = '<p>• ' . __('no page number found.') . '</p>';
                } else {
                    $page = (int) Secure($_POST[ 'page' ]) - 1;
                }
            }
            $page_limit =   array(
                                $page * $perpage,
                                $perpage
                            );
        }
        if ($error == '') {
            $db->objectBuilder()->join('users u', 'l.like_userid=u.id', 'LEFT')
                ->where('l.user_id', self::ActiveUser()->id)
                ->where('l.is_dislike', '1')
                ->where('u.verified', '1')
                ->where('l.like_userid', self::ActiveUser()->id, '<>')
                ->groupBy('l.like_userid')
                ->orderBy('l.created_at', 'DESC');

            // to exclude blocked users
            $db->where('l.like_userid NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');


            $disliked_users     = $db->get('likes l', $page_limit, array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.district',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'max(l.created_at) as created_at'
            ));
            foreach ($disliked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'disliked.php';
            if ($with_html) {
                if (file_exists($template)) {
                    global $config;
                    foreach ($disliked_users as $row) {
                        ob_start();
                        if(!is_avatar_approved($row->id, $row->avater)) {
                            $row->avater = $config->userDefaultAvatar;
                        }
                        include $template;
                        $html .= ob_get_contents();
                        ob_end_clean();
                    }
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html,
                'data' => $disliked_users
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function visits() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $db->objectBuilder()->join('users u', 'v.user_id=u.id', 'LEFT')
                ->where('v.view_userid', self::ActiveUser()->id)
                ->where('v.user_id', self::ActiveUser()->id, '<>')
                ->where('u.verified', '1')
                ->orderBy('v.created_at', 'DESC');

            // to exclude blocked users
            $db->where('v.user_id NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');


            $visits             = $db->get('views v', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
				'u.district',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'v.created_at'
            ));
            foreach ($visits as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', strtotime($value->created_at));
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'visits.php';
            global $config;
            if (file_exists($template)) {
                foreach ($visits as $row) {
                    ob_start();
                    if(!is_avatar_approved($row->id, $row->avater)) {
                        $row->avater = $config->userDefaultAvatar;
                    }
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function blocked_users() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 9;
        $html     = '';
        $template = '';
        $data     = array();
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {

            $uid = self::ActiveUser()->id;
            $target_user = route(2);
            if($target_user !== ''){
                $_user = LoadEndPointResource('users');
                if( $_user ){
                    if( $target_user !== '' ){
                        $user = $_user->get_user_profile(Secure($target_user));
                        if( !empty($user) && !empty($user->id) ){
                            $uid = $user->id;
                        }
                    }
                }
            }
            $blockes = $db->objectBuilder()
                          ->where('user_id', $uid)
                          ->get('blocks',array(
                              $page * $perpage,
                              $perpage
                          ),array('block_userid'));

            foreach ($blockes as $key => $user) {
                $data[$key] = $db->objectBuilder()
                                        ->where('id', $user->block_userid)
                                        ->getOne('users',array('id','username','first_name','last_name','avater'));

                $data[$key]->_full_name = ucfirst(trim($data[$key]->first_name . ' ' . $data[$key]->last_name));
                $data[$key]->full_name = ($data[$key]->_full_name == '') ? ucfirst(trim($data[$key]->username)) : $data[$key]->_full_name;

            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'blocked.php';
            global $config;
            if (file_exists($template)) {
                foreach ($data as $row) {
                    ob_start();
                    if(!is_avatar_approved($row->id, $row->avater)) {
                        $row->avater = $config->userDefaultAvatar;
                    }
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    public function GetLiveVideo($id)
    {
        global $db, $_BASEPATH, $_DS,$_excludes,$config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $post = $db->where('id',Secure($id))->objectbuilder()->getOne('posts');
            if (!empty($post)) {
                $post->is_still_live = false;
                $post->org_image = $post->image;
                $post->image = GetMedia($post->image);
                $post->user_data = userData($post->user_id);
                $post->live_sub_users = 0;
                if (!empty($post->stream_name) && !empty($post->live_time) && $post->live_time >= (time() - 10) && $post->live_ended == 0) {
                    $post->is_still_live = true;
                    $post->live_sub_users = $db->where('post_id',$post->id)->where('time',time()-6,'>=')->getValue('live_sub_users','COUNT(*)');
                }

                $comments_html = '';
                if ($post->live_ended != 0) {
                    $post->postFile = $config->s3_site_url_2 . '/' . $post->postFile;
                    $comments = $db->where('post_id',$post->id)->where('text','','!=')->get('comments');
                    if (!empty($comments)) {
                        $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
                        $template   = $theme_path . 'partails' . $_DS . 'live' . $_DS . 'live_comment.php';
                        foreach ($comments as $key => $value) {
                            if (!empty($value['text'])) {
                                $comment = GetPostComment($value['id']);
                                if (!empty($comment)) {
                                    ob_start();
                                    include $template;
                                    $comments_html .= ob_get_contents();
                                    ob_end_clean();
                                }
                            }
                        }
                    }
                }
                return array('status' => 200,
                             'data' => $post,
                             'comments_html' => $comments_html);
            }
            else{
                return array(
                    'status' => 403,
                    'message' => __('Forbidden')
                );
            }
        }
        else{
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
    }
    function LoadLiveUsers($all = false,$with_html = true)
    {
        global $db, $_BASEPATH, $_DS,$_excludes,$config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $html = '';
        $posts_array = array();
        $error     = '';
        $page      = 1;
        $db->pageLimit   = (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] < 51) ? Secure($_POST['limit']) : 7;
        if (!$all) {
            if (isset($_POST) && !empty($_POST) && !empty($_POST[ 'page' ]) && is_numeric($_POST[ 'page' ]) && $_POST[ 'page' ] > 0) {
                $page = (int) Secure($_POST[ 'page' ]);
            }
        }
        $posts = $db->where('live_ended',0)->where('live_time',(time() - (60 * 5)),'>')->groupBy('user_id')->orderBy('id','DESC')->objectbuilder()->paginate('posts', $page);
        //$posts = $db->orderBy('id','DESC')->objectbuilder()->paginate('posts', $page);
        $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
        $template   = $theme_path . 'partails' . $_DS . 'live' . $_DS . 'users.php';
        foreach ($posts as $key => $value) {
            $value->org_image = $value->image;
            $value->image = GetMedia($value->image);
            $value->user_data = userData($value->user_id);
            ob_start();
            include $template;
            $html .= ob_get_contents();
            ob_end_clean();
            $posts_array[] = $value;
        }
        return array('status' => 200,
                     'html' => $html,
                     'page' => $page + 1,
                     'data' => $posts_array);

    }
    function LoadUserLive($user_id = 0,$all = false,$with_html = true)
    {
        global $db, $_BASEPATH, $_DS,$_excludes,$config;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (empty($_POST['user_id']) && (empty($user_id) || !is_numeric($user_id) || $user_id < 1)) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!empty($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0) {
            $user_id = Secure($_POST['user_id']);
        }
        $html = '';
        $posts_array = array();
        $error     = '';
        $page      = 1;
        $db->pageLimit   = (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] < 51) ? Secure($_POST['limit']) : 8;
        if (!$all) {
            if (isset($_POST) && !empty($_POST) && !empty($_POST[ 'page' ]) && is_numeric($_POST[ 'page' ]) && $_POST[ 'page' ] > 0) {
                $page = (int) Secure($_POST[ 'page' ]);
            }
        }
        $posts = $db->where('user_id',Secure($user_id))->objectbuilder()->paginate('posts', $page);
        $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
        $template   = $theme_path . 'partails' . $_DS . 'live' . $_DS . 'users.php';
        foreach ($posts as $key => $value) {
            $value->org_image = $value->image;
            $value->image = GetMedia($value->image);
            $value->user_data = userData($value->user_id);
            ob_start();
            include $template;
            $html .= ob_get_contents();
            ob_end_clean();
            $posts_array[] = $value;
        }
        return array('status' => 200,
                     'html' => $html,
                     'data' => $posts_array,
                     'page' => $page + 1,);

    }
    function match_users($mode='findmatches') {
        global $db, $_BASEPATH, $_DS,$_excludes;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }

        $error     = '';
        $page      = 0;
        $perpage   = 7;
        $html      = '';
        $html_imgs = '';
        $template  = '';
        $listmode = 'findmatches';
        if (!empty(auth()) && empty(auth()->lat) && empty(auth()->lng)) {
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html,
                'query' => '',
                'html_imgs' => $html_imgs,
                'muser' => array()
            );
        }

        $execludecond = ' `id` > 0';
        $lastid = 0;
        if (isset($_GET['lastid']) && !empty($_GET['lastid'])) {
            $lastid = (int) Secure($_GET['lastid']);
            $execludecond = ' `id` < ' . $lastid;
        }
        if (isset($_GET['mode']) && !empty($_GET['mode'])) {
            $listmode = Secure($_GET['mode']);
        }else{
            if( route(1) == 'hot' ){
                $listmode = 'hot';
            }
        }
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $limit = $perpage;
            $offset = $page * $perpage;
            if ($listmode == 'findmatches') {
                $query = GetFindMatcheQuery(self::ActiveUser()->id, $limit, $offset);
                //print_r($query);
            }elseif ($listmode == 'hot') {
                $execludes = '';

                if(isset($_GET['execlude']) && !empty($_GET['execlude']) ){
                    $execludecond .= ' AND `id` NOT IN (' . Secure($_GET['execlude']) .') ';
                }

                $genders = GetGenders(self::ActiveUser());
                if( is_array($genders) ){
                    $genders = @implode( ',' , $genders );
                }else{
                    $genders = @explode( ',' , $genders );

                    if(self::Config()->opposite_gender == "1"){
                        foreach($genders as $key => $value ){
                            if($value == self::ActiveUser()->gender){
                                unset($genders[$key]);
                            }
                        }
                    }
                    $genders = @implode( ',' , $genders );
                }
                $gender_query = '';
                if( strpos( $genders, ',' ) === false ) {
                    $gender_query = '`gender` = "'. $genders .'" AND ';
                }else{
                    $gender_query = '`gender` IN ('. $genders .') AND ';
                }
                $query = 'SELECT * FROM `users` WHERE '. $execludecond .' AND '.$gender_query.' `active` = "1" AND `verified` = "1" AND `id` NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = ' . self::ActiveUser()->id . ') '. $execludes .' AND `id` NOT IN (SELECT `like_userid` FROM `likes` WHERE `user_id` = ' . self::ActiveUser()->id . ') AND (SELECT count(*) FROM `mediafiles` WHERE `user_id` = `users`.`id` AND `mediafiles`.`is_private` = 0) > 0 AND `id` NOT IN (SELECT `hot_userid` FROM `hot` WHERE `user_id` = ' . self::ActiveUser()->id . ') AND `id` <> "' . self::ActiveUser()->id . '"  ORDER BY `id` DESC LIMIT ' . $limit;
            }
            $match_users       = $db->rawQuery($query);
            //print_r($match_users);


            $match_users_array = array();


            foreach ($match_users as $key => $value) {
                $user = new stdClass();
                $user->id = $value['id'];
                $user->online = $value['online'];
                $user->lastseen = $value['lastseen'];
                $user->username = $value['username'];
                $user->avater = $value['avater'];
                $user->country = (!empty(Dataset::load('countries')) && !empty($value['country']) && !empty(Dataset::load('countries')[$value['country']]) && !empty(Dataset::load('countries')[$value['country']]['name'])) ? Dataset::load('countries')[$value['country']]['name'] : '';
                $user->first_name = $value['first_name'];
                $user->last_name = $value['last_name'];
                $user->birthday = $value['birthday'];
                $user->language = $value['language'];
                $user->relationship = (!empty($value['relationship']) ? Dataset::load('relationship')[$value['relationship']] : $value['relationship']);//$value['relationship'];
                $user->height = $value['height'];
                $user->body = (!empty($value['body']) ? Dataset::load('body')[$value['body']] : $value['body']);//$value['body'];
                $user->about = (!empty($value['about']) ? $value['about'] : '');//$value['body'];
                $user->smoke = (!empty($value['smoke']) ? Dataset::load('smoke')[$value['smoke']] : $value['smoke']);//$value['smoke'];
                $user->ethnicity = (!empty($value['ethnicity']) ? Dataset::load('ethnicity')[$value['ethnicity']] : $value['ethnicity']);//$value['ethnicity'];
                $user->pets = (!empty($value['pets']) ? Dataset::load('pets')[$value['pets']] : $value['pets']);//$value['pets'];
                $user->gender = __($value['gender']);
                $user->mediafiles = array();
                $user->district = $value['district'];

                $mediafiles = $db->where('user_id', $value['id'])->where('is_private', '0')->where('is_video', '0')->orderBy('id', 'desc')->get('mediafiles', 4, array('id','file','is_private','private_file'));
                if ($mediafiles) {
                    $mediafilesid = 0;
                    foreach ($mediafiles as $mediafile) {
                        $mf = array(
                            'id' => $mediafile['id'],
                            'full' => GetMedia($mediafile['file'], false),
                            'avater' => GetMedia(str_replace('_full.', '_avater.', $mediafile['file']), false),
                            'is_private' => $mediafile['is_private'],
                            'private_file_full' => GetMedia( $mediafile['private_file'], false),
                            'private_file_avater' => GetMedia(str_replace('_full.', '_avatar.', $mediafile['private_file']), false)
                        );
                        $user->mediafiles[] = $mf;
                    }
                }
                $img              = 0;
                foreach ($mediafiles as $k => $v) {
                    if ($img < 4) {
                        //$user->mediafiles[] = $v;
                    }
                    $img++;
                }
                //if($img > 0){
                    $match_users_array[ $user->id ] = $user;
                //}
            }
            unset($match_users_array[ self::ActiveUser()->id ]);
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'matches.php';
            $template1  = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'matches_imgs.php';
            if (file_exists($template)) {
                $matche_first     = false;
                $matche_img_first = false;
                if ($page == 0) {
                    $matche_first     = true;
                    $matche_img_first = true;
                }
                $mode = $listmode;
                global $config;
                foreach ($match_users_array as $matche) {

                    ob_start();
                    if($value['src'] !== 'Fake'){
                        if(!is_avatar_approved($matche->id, $matche->avater)) {
                            $matche->avater = $config->userDefaultAvatar;
                        }
                    }
                    include $template;
                    $matche_first = false;
                    $html .= ob_get_contents();
                    ob_end_clean();
                    ob_start();
                    include $template1;
                    $matche_img_first = false;
                    $html_imgs .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html,
                'query' => $query,
                'html_imgs' => $html_imgs,
                'muser' => $match_users_array
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function interest() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error        = '';
        $page         = 0;
        $perpage      = 8;
        $interest_tag = '';
        $html         = '';
        $template     = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
            if (isset($_POST[ 'tags' ]) && !empty($_POST[ 'tags' ])) {
                $interest_tag = strtolower(Secure($_POST[ 'tags' ]));
            }
        }
        if ($interest_tag == '') {
            $interest_tag = Secure(route(2));
        }
        if ($error == '') {
            $liked_user_array   = (array_keys(LikedUsers())) ? array_keys(LikedUsers()) : array(
                ''
            );


            $db->objectBuilder()->where('verified', '1')
                ->where('interest', '%' . $interest_tag . '%', 'like')
                ->orderBy('xlikes_created_at', 'DESC')
                ->orderBy('boosted_time', 'DESC')
                ->orderBy('is_boosted', 'DESC')
                ->orderBy('is_pro', 'DESC');

            // to exclude blocked users
            $db->where('`id` NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');
            // to exclude liked and disliked users users
            $db->where('`id` NOT IN (SELECT `like_userid` FROM `likes` WHERE ( `user_id` = '.self::ActiveUser()->id.' OR `like_userid` = '.self::ActiveUser()->id.' ) )');



            if (is_array($liked_user_array)) {
                if (count($liked_user_array) > 0) {
                    $db->where('id', $liked_user_array, 'NOT IN');
                }
            }


            $interest           = $db->get('users', array(
                $page * $perpage,
                $perpage
            ), array(
                'id',
                'online',
                'lastseen',
                'username',
                'avater',
                'country',
                'first_name',
                'last_name',
                'birthday',
                'language',
                'relationship',
                'height',
                'body',
                'smoke',
                'ethnicity',
                'pets'
            ));
            foreach ($interest as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'interest.php';
            global $config;
            if (file_exists($template)) {
                foreach ($interest as $row) {
                    ob_start();
                    if(!is_avatar_approved($row->id, $row->avater)) {
                        $row->avater = $config->userDefaultAvatar;
                    }
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function find_matches() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $data     = array();
        $last_id  = 0;
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST[ '_where' ])) {
            foreach (json_decode($_POST[ '_where' ]) as $key => $value) {
                if ($key !== 'page') {
                    $_POST[ $key ] = $value;
                }
            }
        }
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]);
            }
        }

        if (isset($_POST[ '_age_from' ])) {
            $_SESSION[ '_age_from' ] = (int) $_POST[ '_age_from' ];
            $_SESSION['homepage'] = false;
        }
        if (isset($_POST[ '_age_to' ])) {
            $_SESSION[ '_age_to' ] = (int) $_POST[ '_age_to' ];
        }
        if (isset($_POST[ '_located' ])) {
            $_SESSION[ '_located' ] = (int) $_POST[ '_located' ];
        }
        if (isset($_POST[ '_gender' ])) {
            $d = array();
            $c = explode(',', $_POST[ '_gender' ]);
            foreach ($c as $key) {
                $d[ $key ] = $key;
            }
            $_SESSION[ '_gender' ] = $d;
        }

        $_genders = null;
        if( self::Config()->opposite_gender == "1" ) {
            $_genders = GetGenders(self::ActiveUser());
        }else{
            $_genders = $_SESSION[ '_gender' ];
        }

        $limit  = $perpage;
        $offset = $page * $perpage;
        $data       = array();
        if (!empty(self::ActiveUser()->lat) && !empty(self::ActiveUser()->lng)) {
            $query  = GetFindMatcheQuery(self::ActiveUser()->id, $limit, $offset,true,true);
            //print_r($query);

            $data       = $db->objectBuilder()->rawQuery($query);
        }
            
        //print_r($data);
        $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
        $template   = $theme_path . 'partails' . $_DS . 'find-matches' . $_DS . 'search.php';
        if (file_exists($template)) {
            foreach ($data as $row) {
//                if( allow_gender($_genders, $row->gender) === false ){
//                    continue;
//                }
                ob_start();
                include $template;
                $html .= ob_get_contents();
                ob_end_clean();
            }
        }
        if ($error == '') {
            return array(
                'status' => 200,
                'page' => $page + 1,
                'post' => json_encode($_POST),
                'html' => $html,
                'where' => (isset($_POST[ '_where' ]) ? json_decode($_POST[ '_where' ]) : '')
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function matches() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {

            // to exclude blocked users
            $notin = ' `users`.`id` NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.') AND ';

            $sql = 'SELECT
              users.id,
              users.username,
              users.avater,
              users.country,
              users.district,
              users.first_name,
              users.last_name,
              users.location,
              users.birthday,
              users.language,
              notifications.created_at,
              users.relationship,
              users.pets,
              users.ethnicity,
              users.smoke,
              users.height,
              users.online,
              users.lastseen
            FROM
              users
              INNER JOIN notifications ON (users.id = notifications.recipient_id)
            WHERE
              '. $notin .'
              notifications.notifier_id = ' . self::ActiveUser()->id . ' AND
              notifications.`type` = \'got_new_match\' AND
              users.verified = \'1\' AND
              notifications.recipient_id <> ' . self::ActiveUser()->id . '
            ORDER BY
              notifications.created_at DESC
            LIMIT ' . $perpage . ' OFFSET ' . $page * $perpage;

            $matches            = $db->objectBuilder()->rawQuery($sql);
            foreach ($matches as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'matches.php';
            global $config;
            if (file_exists($template)) {
                foreach ($matches as $row) {
                    ob_start();
                    if(!is_avatar_approved($row->id, $row->avater)) {
                        $row->avater = $config->userDefaultAvatar;
                    }
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function stories() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';

        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }

        if ($error == '') {
            $sql = 'SELECT * FROM `success_stories` ';
            $sql .= ' WHERE ';
            $sql .= ' `status` = "1" ';
            $sql .= ' ORDER BY `story_date` DESC, `id` DESC LIMIT '.$perpage.' OFFSET '.$page * $perpage.';';
            $success_stories        = $db->objectBuilder()->rawQuery($sql);
            $theme_path          = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template            = $theme_path . 'partails' . $_DS . 'success-stories.php';
            if (file_exists($template)) {
                foreach ($success_stories as $story) {
                    ob_start();
                    $story->user = userData($story->user_id);
                    $story->story_user = userData($story->story_user_id);
                    require($template);
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function articles() {
        global $db, $_BASEPATH, $_DS;
//        if (self::ActiveUser() == NULL) {
//            return array(
//                'status' => 403,
//                'message' => __('Forbidden')
//            );
//        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';

        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }

        $search = '';
        $blog_categories = Dataset::blog_categories();

        $category = null;
        if (!empty(route(2))) {
            $arr = explode("_",route(2));
            if( isset($arr[0]) && $arr[0] > 0 && in_array($arr[0], array_keys($blog_categories))){
                $category = Secure((int)$arr[0]);
            }
        }

        if( $category !== null ) {
            if ($db->where('ref', 'blog_categories')->where('lang_key', $category)->getValue('langs', 'id') === NULL) {
                return array(
                    'status' => 400,
                    'message' => ''
                );
            }
        }else{
            if( route(2) !== '' && route(2) !== 'loadmore'){
                $keyword = Secure(route(2));
                $search = ' WHERE `title` LIKE \'%'.$keyword.'%\' OR `content` LIKE \'%'.$keyword.'%\' OR `description` LIKE \'%'.$keyword.'%\' OR `tags` LIKE \'%'.$keyword.'%\'';
            }
        }

        if ($error == '') {
            $sql = 'SELECT * FROM `blog` ';
            if( $category !== null ){
                $sql .= ' WHERE `category` = '. $category;
            }else{
                $sql .= $search;
            }
            $sql .= ' ORDER BY `created_at` DESC, `id` DESC LIMIT '.$perpage.' OFFSET '.$page * $perpage.';';
            $articles            = $db->objectBuilder()->rawQuery($sql);
            $theme_path          = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template            = $theme_path . 'partails' . $_DS . 'article.php';
            if (file_exists($template)) {
                foreach ($articles as $article) {
                    ob_start();
                    require($template);
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function blog_search(){
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $html    = '';
        $keyword = '';
        if(isset($_POST['keyword']) && $_POST['keyword'] !== ''){
            $keyword = Secure($_POST['keyword']);
        }
        if($keyword !== ''){
            $sql = 'SELECT * FROM `blog` WHERE `title` LIKE \'%'.$keyword.'%\' OR `content` LIKE \'%'.$keyword.'%\' OR `description` LIKE \'%'.$keyword.'%\' OR `tags` LIKE \'%'.$keyword.'%\'';
            $sql .= ' ORDER BY `created_at` DESC, `id` DESC LIMIT 50;';
            $articles            = $db->objectBuilder()->rawQuery($sql);
            $theme_path          = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template            = $theme_path . 'partails' . $_DS . 'article.php';
            if (file_exists($template)) {
                foreach ($articles as $article) {
                    ob_start();
                    require($template);
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }

            if( $html == '' ){
                $template_empty = $theme_path . 'partails' . $_DS . 'empty-article.php';
                if (file_exists($template_empty)) {
                    ob_start();
                    require($template_empty);
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }

            return array(
                'status' => 200,
                'html' => $html
            );
        }else{
            return array(
                'status' => 400,
                'message' => ''
            );
        }
    }
    function friend_requests() {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                $error = '<p>• ' . __('no page number found.') . '</p>';
            } else {
                $page = (int) Secure($_POST[ 'page' ]) - 1;
            }
        }
        if ($error == '') {
            $db->objectBuilder()->join('users u', 'f.follower_id=u.id', 'LEFT')
                //->where('f.follower_id', self::ActiveUser()->id)
                ->where('f.active', "0")
               // ->where('f.following_id', self::ActiveUser()->id, '<>')
                ->where('f.following_id', self::ActiveUser()->id)
                ->groupBy('f.follower_id')
                ->orderBy('f.created_at', 'DESC');

            // to exclude blocked users
            $db->where('f.follower_id NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');



            $liked_users        = $db->get('followers f', array(
                $page * $perpage,
                $perpage
            ), array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'f.created_at'
            ));
            foreach ($liked_users as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', $value->created_at);
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'likes.php';
            global $config;
            if (file_exists($template)) {
                foreach ($liked_users as $row) {
                    ob_start();
                    if(!is_avatar_approved($row->id, $row->avater)) {
                        $row->avater = $config->userDefaultAvatar;
                    }
                    include $template;
                    $html .= ob_get_contents();
                    ob_end_clean();
                }
            }
            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
    function friends($all = false,$with_html = true) {
        global $db, $_BASEPATH, $_DS;
        if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        $error    = '';
        $page     = 0;
        $perpage  = 12;
        $html     = '';
        $template = '';
        $page_limit = null;
        if (!$all) {
            if (isset($_POST) && !empty($_POST)) {
                if (isset($_POST[ 'page' ]) && (!is_numeric($_POST[ 'page' ]) || empty($_POST[ 'page' ]))) {
                    $error = '<p>• ' . __('no page number found.') . '</p>';
                } else {
                    $page = (int) Secure($_POST[ 'page' ]) - 1;
                }
            }
            $page_limit =   array(
                                $page * $perpage,
                                $perpage
                            );
        }

        if ($error == '') {
            $total_friends = [];
            $db->objectBuilder()
                ->join('users u', 'f.follower_id=u.id', 'LEFT')
                ->where('f.active', "1")
                ->where('f.following_id', self::ActiveUser()->id)
                ->groupBy('f.follower_id')
                ->orderBy('f.created_at', 'DESC');

            // to exclude blocked users
            $db->where('f.follower_id NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');
            $liked_users        = $db->get('followers f', $page_limit, array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'f.created_at'
            ));
            foreach ($liked_users as $key => $value) {
                $total_friends[$value->id] = $value;
            }

            $db->objectBuilder()
                ->join('users u', 'f.following_id=u.id', 'LEFT')
                ->where('f.active', "1")
                ->where('f.follower_id', self::ActiveUser()->id)
                ->groupBy('f.follower_id')
                ->orderBy('f.created_at', 'DESC');

            // to exclude blocked users
            $db->where('f.following_id NOT IN (SELECT `block_userid` FROM `blocks` WHERE `user_id` = '.self::ActiveUser()->id.')');
            $liked_users1        = $db->get('followers f', $page_limit, array(
                'u.id',
                'u.online',
                'u.lastseen',
                'u.username',
                'u.avater',
                'u.country',
                'u.first_name',
                'u.last_name',
                'u.location',
                'u.birthday',
                'u.language',
                'u.relationship',
                'u.height',
                'u.body',
                'u.smoke',
                'u.ethnicity',
                'u.pets',
                'f.created_at'
            ));
            foreach ($liked_users1 as $key => $value) {
                $total_friends[$value->id] = $value;
            }

            foreach ($total_friends as $key => $value) {
                if ($value->country !== '') {
                    $countries = Dataset::load('countries');
                    if (isset($countries[ $value->country ])) {
                        $value->country_txt = $countries[ $value->country ][ 'name' ];
                    }
                } else {
                    $value->country_txt = '';
                }
                if ($value->created_at !== '') {
                    $value->created_at = date('c', $value->created_at);
                }
            }
            $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
            $template   = $theme_path . 'partails' . $_DS . 'likes.php';
            global $config;
            if ($with_html) {
                if (file_exists($template)) {
                    foreach ($total_friends as $row) {
                        ob_start();
                        if(!is_avatar_approved($row->id, $row->avater)) {
                            $row->avater = $config->userDefaultAvatar;
                        }
                        include $template;
                        $html .= ob_get_contents();
                        ob_end_clean();
                    }
                }
            }

            return array(
                'status' => 200,
                'page' => $page + 2,
                'html' => $html,
                'data' => $total_friends
            );
        } else {
            return array(
                'status' => 400,
                'message' => $error
            );
        }
    }
}
