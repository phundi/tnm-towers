<?php
Class Apps extends Aj {
    public function add_new_app(){
        global $db, $_UPLOAD, $_DS,$site_url;
        if (empty($_POST['app_name']) || empty($_POST['app_website_url']) || empty($_POST['app_description'])) {
            return array(
                'status' => 403,
                'message' => __('please_check_details')
            );
        }
        if (!filter_var($_POST['app_website_url'], FILTER_VALIDATE_URL)) {
            return array(
                'status' => 403,
                'message' => __('Invalid Url')
            );
        }
        if (empty($errors)) {
            $app_callback_url = $_POST['app_callback_url'];
            $re_app_data = array(
                'app_user_id' => self::ActiveUser()->id,
                'app_name' => Secure($_POST['app_name']),
                'app_website_url' => Secure($_POST['app_website_url']),
                'app_description' => Secure($_POST['app_description']),
                'app_callback_url' => Secure($app_callback_url)
            );
            $id_str                    = sha1($re_app_data['app_user_id'] . microtime() . time());
            $re_app_data['app_id']     = Secure(substr($id_str, 0, 20));
            $secret_str                      = sha1($re_app_data['app_user_id'] . GenerateKey(55, 55) . microtime());
            $re_app_data['app_secret'] = Secure(substr($secret_str, 0, 39));
            if (empty($re_app_data['app_secret']) || empty($re_app_data['app_id'])) {
                return array(
                    'status' => 403,
                    'message' => __('Forbidden')
                );
            }
            if (!empty($_FILES["app_avatar"]["name"])) {
                if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
                    mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
                }
                if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
                    mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
                }
                $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
                $ext      = pathinfo($_FILES["app_avatar"]["name"], PATHINFO_EXTENSION);
                $key      = GenerateKey();
                $filename = $dir . $_DS . $key . '.' . $ext;
                if (move_uploaded_file($_FILES["app_avatar"]["tmp_name"], $filename)) {
                    $re_app_data[ 'app_avatar' ] = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '.' . $ext;
                }
            }
            $app_id = $db->insert('apps',$re_app_data);
            return array(
                'status' => 200,
                'location' => $site_url.'/app/'.$app_id
            );
        }
    }
    public function update_app(){
        global $db, $_UPLOAD, $_DS,$site_url;
        if (empty($_POST['app_name']) || empty($_POST['app_website_url']) || empty($_POST['app_description']) || empty($_POST['app_id']) || !is_numeric($_POST['app_id']) || $_POST['app_id'] < 1) {
            return array(
                'status' => 403,
                'message' => __('please_check_details')
            );
        }
        if (!filter_var($_POST['app_website_url'], FILTER_VALIDATE_URL)) {
            return array(
                'status' => 403,
                'message' => __('Invalid Url')
            );
        }
        if (empty($errors)) {
            $app_callback_url = $_POST['app_callback_url'];
            $re_app_data = array(
                'app_name' => Secure($_POST['app_name']),
                'app_website_url' => Secure($_POST['app_website_url']),
                'app_description' => Secure($_POST['app_description']),
                'app_callback_url' => Secure($app_callback_url)
            );
            
            if (!empty($_FILES["app_avatar"]["name"])) {
                if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
                    mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
                }
                if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
                    mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
                }
                $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
                $ext      = pathinfo($_FILES["app_avatar"]["name"], PATHINFO_EXTENSION);
                $key      = GenerateKey();
                $filename = $dir . $_DS . $key . '.' . $ext;
                if (move_uploaded_file($_FILES["app_avatar"]["tmp_name"], $filename)) {
                    $re_app_data[ 'app_avatar' ] = 'upload/photos/' . date('Y') . '/' . date('m') . '/' . $key . '.' . $ext;
                }
            }
            $app = Secure($_POST['app_id']);
            $app_id = $db->where('id',$app)->where('app_user_id',self::ActiveUser()->id)->update('apps',$re_app_data);
            return array(
                'status' => 200
            );
        }
    }
    function acceptPermissions()
    {
        if (!empty($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0) {
            $acceptPermissions = AcceptPermissions($_POST['id']);
            if ($acceptPermissions === true) {
                $import = GenrateCode(self::ActiveUser()->id, $_POST['id']);
                $app    = urldecode($_POST['url']) . '?code=' . $import;
                return array(
                    'status' => 200,
                    'location' => $app
                );
            }
        }
        return array(
            'status' => 403,
            'message' => __('Invalid Url')
        );
    }
}