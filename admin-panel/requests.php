<?php
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php');
require_once('function.php');
global $db,$conn;
SessionStart();
use Aws\S3\S3Client;
use Google\Cloud\Storage\StorageClient;
$f = '';
$s = '';
$p = '';
if (auth()->admin != '1') {
  $data = array('status' => 200);
  header("Content-type: application/json");
  exit();
}
if (isset($_GET['f'])) {
    $f = Secure($_GET['f'], 0);
}
if (isset($_GET['s'])) {
    $s = Secure($_GET['s'], 0);
}
if (isset($_GET['p'])) {
    $p = Secure($_GET['p'], 0);
}
$hash_id = '';
if (!empty($_POST['hash_id'])) {
    $hash_id = $_POST['hash_id'];
    unset($_POST['hash_id']);
} else if (!empty($_GET['hash_id'])) {
    $hash_id = $_GET['hash_id'];
    unset($_GET['hash_id']);
} else if (!empty($_GET['hash'])) {
    $hash_id = $_GET['hash'];
    unset($_GET['hash']);
} else if (!empty($_POST['hash'])) {
    $hash_id = $_POST['hash'];
    unset($_POST['hash']);
}
$data = array();
header("Content-type: application/json");
if ($f == 'session_status') {
    if (isset( $_SESSION['JWT'])) {
        $data = array(
            'status' => 200
        );
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!isset( $_SESSION['JWT'])) {
    exit("Please login or signup to continue.");
}
if ($s == 'download_emails') {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    // rename the file to username
    header('Content-Disposition: attachment; filename="file.csv"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('file.csv'));
    flush(); // Flush system output buffer
    readfile('file.csv');
    // delete the file
    unlink('file.csv');
}
if ($s == 'ffmpeg_debug') {
    $data['status'] = 400;
    if (!empty($_FILES['video']['tmp_name'])) {
        $file_info    = array(
            'file'    => $_FILES['video']['tmp_name'],
            'size'    => $_FILES['video']['size'],
            'name'    => $_FILES['video']['name'],
            'type'    => $_FILES['video']['type'],
            'allowed' => 'mp4,mov,webm,mpeg,3gp,avi,flv,ogg,mkv,mk3d,mks,wmv',
            'local_upload' => 1
        );

        $file_upload   = ShareFile($file_info);
        if (!empty($file_upload['filename'])) {
            $ffmpeg_b                   = $wo['config']['ffmpeg_binary'];
            $video_output_full_path_240 = dirname(__DIR__) . "/upload/videos/test_240p_converted.mp4";
            @unlink($video_output_full_path_240);
            // $video_file_full_path = dirname(__DIR__) . "/admin-panel/videos/test.mp4";
            $video_file_full_path = $file_upload['filename'];
            $shell                = shell_exec("$ffmpeg_b -y -i $video_file_full_path -vcodec libx264 -preset ultrafast -filter:v scale=426:-2 -crf 26 $video_output_full_path_240 2>&1");
            if (file_exists($video_output_full_path_240)) {
                $data['video_url'] = $wo['site_url'] . '/upload/videos/test_240p_converted.mp4';
            }
            $data['status'] = 200;
            $data['data']   = $shell;
        }
        else{
            $data['message'] = 'something went wrong when trying to upload video please try with another video';
        }
    }
    else{
        $data['message'] = 'please upload a video';
    }

    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if ($s == 'auto_user_like') {
    if (!empty($_GET['users'])) {
        $save = Wo_SaveConfig('auto_user_like', Secure($_GET['users']));
        if ($save) {
            $data['status'] = 200;
        }
    }
    else{
        $save = Wo_SaveConfig('auto_user_like', '');
        if ($save) {
            $data['status'] = 200;
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if ($s == 'delete_email') {
    $data = array(
        'status' => 400
    );
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        $db->where('id',Secure($_GET['id']))->delete('email_subscribers');
        $data['status'] = 200;
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if ($s == 'delete_multi_emails') {
    if (!empty($_POST['ids']) && !empty($_POST['type']) && in_array($_POST['type'], array('export','delete'))) {
        $cols = 0;
        $df = null;
        if ($_POST['type'] == 'export') {
            ob_start();
            $df = fopen("file.csv", 'w');
        }
        foreach ($_POST['ids'] as $key => $value) {
            if (!empty($value) && is_numeric($value) && $value > 0) {
                if ($_POST['type'] == 'delete') {
                    $db->where('id',Secure($value))->delete('email_subscribers');
                }
                else{
                    $row = $db->where('id',Secure($value))->arrayBuilder()->getOne('email_subscribers');
                    if (!empty($row)) {
                        if ($cols == 0) {
                            fputcsv($df, array_keys($row));
                            $cols = 1;
                        }
                        $cr = [];
                        foreach ($row as $key => $value) {
                            if ($key == 'time') {
                                $cr[] = date("Y-m-d H:i:s",$value);
                            }
                            else{
                                $cr[] = $value;
                            }
                        }
                        fputcsv($df, $cr);
                    } 
                }
            }
        }
        if ($_POST['type'] == 'export') {
            fclose($df);
            ob_get_clean();
        }
        
        $data = ['status' => 200];
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
if ($s == 'delete-app') {
    $data = array(
        'status' => 500
    );
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        if (DeleteApp($_GET['id'])) {
            $data['status'] = 200;
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if ($s == 'remove_multi_app') {
    if (!empty($_POST['ids'])) {
        foreach ($_POST['ids'] as $key => $value) {
            if (!empty($value) && is_numeric($value) && $value > 0) {
                DeleteApp($value);
            }
        }
        $data = ['status' => 200];
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
if ($s == 'insert-invitation') {
    $data             = array(
        'status' => 200,
        'html' => ''
    );
    $wo['invitation'] = InsertAdminInvitation();
    if ($wo['invitation'] && is_array($wo['invitation'])) {
        $data['html']   = Wo_LoadAdminPage('manage-invitation-keys/list');
        $data['status'] = 200;
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if ($s == 'rm-invitation' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $data = array(
        'status' => 304
    );
    if (DeleteAdminInvitation('id', $_GET['id'])) {
        $data['status'] = 200;
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if ($s == 'rm-user-invitation' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $data = array(
        'status' => 304
    );
    if (DeleteUserInvitation('id', $_GET['id'])) {
        $data['status'] = 200;
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if ($s == 'remove_multi_invitation') {
    if (!empty($_POST['ids'])) {
        foreach ($_POST['ids'] as $key => $value) {
            if (!empty($value) && is_numeric($value) && $value > 0) {
                DeleteUserInvitation('id', $value);
            }
        }
        $data = ['status' => 200];
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
if ($f == 'update-ads' && (auth()->admin == '1' || CheckUserPermission(auth()->id, $p))) {
    $updated = false;
    foreach ($_POST as $key => $ads) {
        if ($key != 'hash_id') {
            $ad_data = array(
                'code' => htmlspecialchars(base64_decode($ads)),
                'active' => (empty($ads)) ? 0 : 1
            );
            $update = $db->where('placement', Secure($key))->update('site_ads', $ad_data);
            if ($update) {
                $updated = true;
            }
        }
    }
    if ($updated == true) {
        $data = array(
            'status' => 200
        );
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}

if ($f == 'get_lang_key' && (auth()->admin == '1' || CheckUserPermission(auth()->id, $p))) {
    $html  = '';
    $langs = Wo_GetLangDetails($_GET['id']);
    if (count($langs) > 0) {
        foreach ($langs as $key => $wo['langs']) {
            foreach ($wo['langs'] as $wo['key_'] => $wo['lang_vlaue']) {
                $wo['is_editale'] = 0;
                if ($_GET['lang_name'] == $wo['key_']) {
                    $wo['is_editale'] = 1;
                }
                $html .= Wo_LoadAdminPage('edit-lang/form-list', false);
            }
        }
    } else {
        $html = "<h4>Keyword not found</h4>";
    }
    $data['status'] = 200;
    $data['html']   = $html;
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}

if ($f == 'get_country_lang_key' && (auth()->admin == '1' || CheckUserPermission(auth()->id, $p))) {
    $html  = '';
    $langs = Wo_GetLangDetailsByid($_GET['id'],true);
    if (count($langs) > 0) {
        foreach ($langs as $key => $wo['langs']) {
            foreach ($wo['langs'] as $wo['key_'] => $wo['lang_vlaue']) {
                $wo['is_editale'] = 0;
                if ($_GET['lang_name'] == $wo['key_']) {
                    $wo['is_editale'] = 1;
                }
                if($wo['key_'] === 'options') {
                    $html .= '<div class="form-group" style="margin-bottom: 0px;"><div class="form-lins"><label class="form-lasbel">Country Area Code</label><textarea style="resize: none;" name="options" id="options" class="form-control" cols="20" rows="2" >' . $wo['langs']['options'] . '</textarea></div></div>';
                }else if($wo['key_'] === 'lang_key'){
                    $html  .= '<div class="form-group" style="margin-bottom: 0px;"><div class="form-lins"><label class="form-lasbel">Country Code</label><textarea style="resize: none;" name="lang_key" id="lang_key" class="form-control" cols="20" rows="2" >'.$wo['langs']['lang_key'].'</textarea></div></div>';
                }else {
                    $html .= Wo_LoadAdminPage('edit-countries/form-list', false);
                }
            }
        }
    } else {
        $html = "<h4>Keyword not found</h4>";
    }
    $data['status'] = 200;
    $data['html']   = $html;
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}

if ($f == "admin_setting" && (auth()->admin == '1' || CheckUserPermission(auth()->id, $p))) {
    if ($s == 'ReadNotify') {
        $db->where('recipient_id',0)->where('admin',1)->where('seen',0)->update('notifications',array('seen' => time()));
    }
    if ($s == 'search_in_pages') {
        $keyword = Secure($_POST['keyword']);
        $html = '';

        $files = scandir('pages');
        $not_allowed_files = array('edit-custom-page','edit-lang','edit-movie','edit-profile-field','edit-terms-pages','edit-countries','edit-genders','add-genders','add-genders');
        foreach ($files as $key => $file) {
            if (file_exists('pages/'.$file.'/content.phtml') && !in_array($file, $not_allowed_files)) {

                $string = file_get_contents('pages/'.$file.'/content.phtml');
                preg_match_all("@(?s)<h2([^<]*)>([^<]*)<\/h2>@", $string, $matches1);

                if (!empty($matches1) && !empty($matches1[2])) {
                    foreach ($matches1[2] as $key => $title) {
                        if (strpos(strtolower($title), strtolower($keyword)) !== false) {
                            $page_title = '';
                            preg_match_all("@(?s)<h6([^<]*)>([^<]*)<\/h6>@", $string, $matches3);
                            if (!empty($matches3) && !empty($matches3[2])) {
                                foreach ($matches3[2] as $key => $title2) {
                                    $page_title = $title2;
                                    break;
                                }
                            }
                            $html .= '<a href="'.Wo_LoadAdminLinkSettings($file).'?highlight='.$keyword.'"><div  style="padding: 5px 2px;">'.$page_title.'</div><div><small style="color: #333;">'.$title.'</small></div></a>';
                            break;
                        }
                    }
                }

                preg_match_all("@(?s)<label([^<]*)>([^<]*)<\/label>@", $string, $matches2);
                if (!empty($matches2) && !empty($matches2[2])) {
                    foreach ($matches2[2] as $key => $lable) {
                        if (strpos(strtolower($lable), strtolower($keyword)) !== false) {
                            $page_title = '';
                            preg_match_all("@(?s)<h6([^<]*)>([^<]*)<\/h6>@", $string, $matches3);
                            if (!empty($matches3) && !empty($matches3[2])) {
                                foreach ($matches3[2] as $key => $title2) {
                                    $page_title = $title2;
                                    break;
                                }
                            }

                            $html .= '<a href="'.Wo_LoadAdminLinkSettings($file).'?highlight='.$keyword.'"><div  style="padding: 5px 2px;">'.$page_title.'</div><div><small style="color: #333;">'.$lable.'</small></div></a>';
                            break;
                        }
                    }
                }
            }
        }
        $data = array(
                    'status' => 200,
                    'html'   => $html
                );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }
    if ($s == 'remove_multi_lang') {
        if (!empty($_POST['ids'])) {
            $langs = Wo_LangsNamesFromDB();
            foreach ($_POST['ids'] as $key => $value) {
                if (in_array($value, $langs)) {
                    $lang_name = Secure($value);
                    $query     = mysqli_query($conn, "ALTER TABLE `langs` DROP COLUMN `$lang_name`");
                    if ($query) {
                    }
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_page') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                if (!empty($value) && is_numeric($value) && $value > 0) {
                     Wo_DeleteCustomPage($value);
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_ban') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                Wo_DeleteBanned(Secure($value));
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_gift') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                Wo_DeleteGift(Secure($value));
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'delete_multi_article') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                $article = $db->where('id',Secure($value))->objectbuilder()->getOne('blog');
                Wo_DeleteArticle($article->id, $article->thumbnail);
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_category') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                if (!empty($value) && in_array($value, array_keys(Dataset::blog_categories()))) {
                    $db->where('lang_key',Secure($value))->delete('langs');
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_sticker') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                if (!empty($value) && is_numeric($value)) {
                    Wo_DeleteSticker(Secure($value));
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_field') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                if (!empty($value) && is_numeric($value)) {
                    DeleteField($value);
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_country') {
        if (!empty($_POST['ids'])) {
            foreach ($_POST['ids'] as $key => $value) {
                if (in_array($value, array_keys(Dataset::countries('id')))) {
                    $db->where('id',Secure($value))->delete('langs');
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'delete_multi_report') {
        if (!empty($_POST['ids']) && !empty($_POST['type']) && in_array($_POST['type'], array('safe','delete'))) {
            foreach ($_POST['ids'] as $key => $value) {
                if (is_numeric($value) && $value > 0) {
                    $report = $db->where('id',Secure($value))->getOne('reports');
                    if ($_POST['type'] == 'delete') {
                        Wo_DeleteReport($report['id']);
                    }
                    elseif ($_POST['type'] == 'safe') {
                        Wo_DeleteReport($report['id']);
                    }
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_request') {
        if (!empty($_POST['ids']) && !empty($_POST['type']) && in_array($_POST['type'], array('paid','decline'))) {
            foreach ($_POST['ids'] as $key => $value) {
                if (!empty($value) && is_numeric($value)) {
                    if ($_POST['type'] == 'decline') {
                        $get_payment_info = Wo_GetPaymentHistory(Secure($value));
                        $get_payment_info = ToArray($get_payment_info);
                        if (!empty($get_payment_info)) {
                            $id     = $get_payment_info['id'];
                            $update = mysqli_query($conn, "UPDATE `affiliates_requests` SET status = '2' WHERE id = {$id}");
                            if ($update) {
                                $message_body = Emails::parse('emails/payment-declined', array(
                                    'name' => ($get_payment_info['user'][ 'first_name' ] !== '' ? $get_payment_info['user'][ 'first_name' ] : $get_payment_info['user'][ 'username' ]),
                                    'amount' => $get_payment_info['amount'],
                                    'site_name' => $wo['config']['siteName']
                                ));
                                $send_message_data = array(
                                    'from_email' => $wo['config']['siteEmail'],
                                    'from_name' => $wo['config']['siteName'],
                                    'to_email' => $get_payment_info['user']['email'],
                                    'subject' => 'Payment Declined | ' . $wo['config']['siteName'],
                                    'charSet' => 'utf-8',
                                    'message_body' => $message_body,
                                    'is_html' => true
                                );
                                $send_message      = SendEmail($send_message_data['to_email'], $send_message_data['subject'], $send_message_data['message_body'], false);
                                $data['status'] = 200;

                            }
                        }
                    }
                    elseif ($_POST['type'] == 'paid') {
                        $get_payment_info = Wo_GetPaymentHistory(Secure($value));
                        $get_payment_info = ToArray($get_payment_info);
                        if (!empty($get_payment_info)) {
                            $id     = $get_payment_info['id'];
                            $update = mysqli_query($conn, "UPDATE `affiliates_requests` SET status = '1' WHERE id = {$id}");
                            if ($update) {
                                $message_body = Emails::parse('emails/payment-sent', array(
                                    'name' => ($get_payment_info['user'][ 'first_name' ] !== '' ? $get_payment_info['user'][ 'first_name' ] : $get_payment_info['user'][ 'username' ]),
                                    'amount' => $get_payment_info['amount'],
                                    'site_name' => $wo['config']['siteName']
                                ));
                                $send_message_data = array(
                                    'from_email' => $wo['config']['siteEmail'],
                                    'from_name' => $wo['config']['siteName'],
                                    'to_email' => $get_payment_info['user']['email'],
                                    'to_name' => $get_payment_info['user']['first_name'],
                                    'subject' => 'Payment Declined | ' . $wo['config']['siteName'],
                                    'charSet' => 'utf-8',
                                    'message_body' => $message_body,
                                    'is_html' => true
                                );
                                $send_message      = SendEmail($send_message_data['to_email'], $send_message_data['subject'], $send_message_data['message_body'], false);
                                if ($send_message) {
                                    $data['status'] = 200;
                                }
                            }
                        }
                    }
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'remove_multi_verification') {
        if (!empty($_POST['ids']) && !empty($_POST['type']) && in_array($_POST['type'], array('verify','delete'))) {
            foreach ($_POST['ids'] as $key => $value) {
                if (!empty($value) && is_numeric($value)) {
                    if ($_POST['type'] == 'delete') {
                        $verify = $db->where('id',Secure($value))->objectbuilder()->getOne('verification_requests');
                        if (!empty($verify)) {
                            $db->where('id',$verify->user_id)->update('users',['start_up' => '0']);
                        }
                        $db->where('id',Secure($value))->delete('verification_requests');
                    }
                    elseif ($_POST['type'] == 'verify') {
                        $verify = $db->where('id',Secure($value))->getOne('verification_requests');
                        Wo_VerifyUser(Secure($value), $verify['user_id']);
                    }
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'test_backblaze') {
        $server_output = BackblazeConnect(array('apiUrl' => 'https://api.backblazeb2.com',
                                               'uri' => '/b2api/v2/b2_authorize_account',
                                            ));
        $data['status'] = 404;
        if (!empty($server_output)) {
            $result = json_decode($server_output,true);
            if (!empty($result['authorizationToken']) && !empty($result['apiUrl']) && !empty($result['accountId'])) {

                $info = BackblazeConnect(array('apiUrl' => $result['apiUrl'],
                                               'uri' => '/b2api/v2/b2_list_buckets',
                                               'accountId' => $result['accountId'],
                                               'authorizationToken' => $result['authorizationToken'],
                                        ));
                if (!empty($info)) {
                    $info = json_decode($info,true);
                    if (!empty($info) && !empty($info['buckets'])) {
                        $bucketId = '';
                        foreach ($info['buckets'] as $key => $value) {
                            if ($value['bucketId'] == $wo['config']['backblaze_bucket_id']) {
                                $db->where('option_name', 'backblaze_bucket_name')->update('options', array('option_value' => $value['bucketName']));
                                $bucketId = $value['bucketId'];
                                break;
                            }
                        }

                        if (!empty($bucketId)) {
                            $data['status'] = 200;
                            $array          = array(
                                '../upload/photos/d-avatar.jpg'
                            );
                            foreach ($array as $key => $value) {
                                $upload = Wo_UploadToS3($value, array(
                                    'delete' => 'no'
                                ));
                            }
                        }
                    }
                    else{
                        $data['status'] = 300;
                    }
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_multi_story') {
        if (!empty($_POST['ids']) && !empty($_POST['type']) && in_array($_POST['type'], array('activate','deactivate','delete'))) {
            foreach ($_POST['ids'] as $key => $value) {
                if (!empty($value) && is_numeric($value)) {
                    if ($_POST['type'] == 'delete') {
                        Wo_Deletesuccess_stories(Secure($value));
                    }
                    elseif ($_POST['type'] == 'activate') {
                        Wo_Approvesuccess_stories(Secure($value));
                    }
                    elseif ($_POST['type'] == 'deactivate') {
                        Wo_DisApprovesuccess_stories(Secure($value));
                    }
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'delete_multi_gender') {
        if (!empty($_POST['ids']) && !empty($_POST['type']) && in_array($_POST['type'], array('enable','disable','delete'))) {
            foreach ($_POST['ids'] as $key => $value) {
                if (in_array($value, array_keys(Dataset::gender()))) {
                    if ($_POST['type'] == 'delete') {
                        if((int)$value == 4526 || (int)$value == 4525 ){
                            $data['status'] = 300;
                        }else {
                            $db->where('lang_key',Secure($value))->delete('langs');
                            $data['status'] = 200;
                        }
                    }
                    elseif ($_POST['type'] == 'enable') {
                        $db->where('lang_key',Secure($value))->update('langs', array('options' => 1));
                    }
                    elseif ($_POST['type'] == 'disable') {
                        $db->where('lang_key',Secure($value))->update('langs', array('options' => NULL));
                    }
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'delete_multi_users') {
        if (!empty($_POST['ids']) && !empty($_POST['type']) && in_array($_POST['type'], array('activate','deactivate','delete'))) {
            foreach ($_POST['ids'] as $key => $value) {
                if (is_numeric($value) && $value > 0) {
                    if ($_POST['type'] == 'delete') {
                        $d_user = LoadEndPointResource('users');
                        if($d_user) {
                            $deleted = $d_user->delete_user(Secure($value));
                        }
                    }
                    elseif ($_POST['type'] == 'activate') {
                        $db->where('id', Secure($value));

                        $update_data = array('active' => '1','email_code' => '','verified' => '1','start_up' => '3','approved_at' => time());
                        $update = $db->update('users', $update_data);
                    }
                    elseif ($_POST['type'] == 'deactivate') {
                        $db->where('id', Secure($value));

                        $update_data = array('active' => '0','email_code' => '');
                        $update = $db->update('users', $update_data);
                    }
                }
            }
            $data = ['status' => 200];
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'update_user_permission'){
        if(!empty($_GET['user_id'])){
            $_id = (int)Secure($_GET['user_id']);
            $_user = $db->where('id',$_id)->getOne('users',array('*'));

            if($_user) {
                $_new_permission = array();
                $_permission = $_user['permission'];
                if( $_permission == '' ){
                    $_new_permission[Secure($_GET['permission'])] = Secure($_GET['permission_val']);
                }else{
                    $_permission = unserialize($_user['permission']);
                    $_permission[Secure($_GET['permission'])] = Secure($_GET['permission_val']);
                    $_new_permission = $_permission;
                }
                $db->where('id',$_id)->update('users', array( 'permission' => serialize($_new_permission)));

                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_user_permission_normal'){
        if(!empty($_POST['user_id'])){
            $_id = (int)Secure($_POST['user_id']);
            $_user = $db->where('id',$_id)->getOne('users',array('*'));

            if($_user) {
               $db->where('id',$_id)->update('users', array( 'permission' => serialize($_POST['permission'])));
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'permission') {
        if (!empty($_GET['user_id']) && is_numeric($_GET['user_id']) && $_GET['user_id'] > 0 && !empty($_GET['type']) && in_array($_GET['type'], array(
            'normal',
            'moderator',
            'admin'
        ))) {
            $update = array(
                'admin' => '0'
            );
            if ($_GET['type'] == 'admin') {
                $update = array(
                    'admin' => '1'
                );
            }
            if ($_GET['type'] == 'moderator') {
                $update = array(
                    'admin' => '2'
                );
            }
            $db->where('id', Secure($_GET['user_id']))->update('users', $update);
            $data = array(
                'status' => 200
            );
            header("Content-type: application/json");
            echo json_encode($data);
            exit();
        }
    }
    if ($s == 'update_moderator_permission') {
        if (!empty($_GET['permission']) && !empty($_GET['user_id']) && is_numeric($_GET['user_id']) && $_GET['user_id'] > 0 && in_array($_GET['permission_val'], array(
            0,
            1
        ))) {
            $wo['mod_pages'] = array('dashboard', 'manage-users', 'online-users', 'manage-stories', 'manage-pages', 'manage-groups', 'manage-posts', 'manage-articles', 'manage-events', 'manage-forum-threads', 'manage-forum-messages', 'manage-movies', 'manage-games', 'add-new-game', 'manage-user-ads', 'manage-reports', 'manage-third-psites', 'edit-movie','live','manage-invitation','manage-invitation-keys','manage-apps','auto-like');
            $user            = $db->objectbuilder()->where('id', Secure($_GET['user_id']))->where('admin', '2')->getOne('users');
            if (!empty($user)) {
                $wo['all_pages'] = scandir('admin-panel/pages');
                unset($wo['all_pages'][0]);
                unset($wo['all_pages'][1]);
                unset($wo['all_pages'][2]);
                if (!empty($user->permission)) {
                    $permission                                 = json_decode($user->permission, true);
                    $permission[Secure($_GET['permission'])] = Secure($_GET['permission_val']);
                } else {
                    $permission = array();
                    if (!empty($wo['all_pages'])) {
                        foreach ($wo['all_pages'] as $key => $value) {
                            if (in_array($value, $wo['mod_pages'])) {
                                $permission[$value] = 1;
                            } else {
                                $permission[$value] = 0;
                            }
                        }
                    }
                    $permission[Secure($_GET['permission'])] = Secure($_GET['permission_val']);
                }
                $permission = json_encode($permission);
                $db->where('id', Secure($_GET['user_id']))->update('users', array(
                    'permission' => $permission
                ));
            }
        }
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_gift') {
        if (!empty($_GET['gift_id'])) {
            if (Wo_DeleteGift($_GET['gift_id']) === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_sticker') {
        if (!empty($_GET['sticker_id'])) {
            if (Wo_DeleteSticker($_GET['sticker_id']) === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_photo') {
        if (!empty($_GET['photo_id'])) {
            $photo_id = Secure($_GET['photo_id']);
            $photo_file = Secure($_GET['photo_file']);
            $avater_file = str_replace('_full.','_avater.', $photo_file);
            $db->where('avater',$avater_file)->update('users',array( 'avater' => $wo['config']['userDefaultAvatar'] ));
            $deleted = false;
            Wo_DeletePhoto($photo_id);
            if (DeleteFromToS3( $photo_file ) === true) {
                $deleted = true;
            }
            if (DeleteFromToS3( $avater_file ) === true) {
                $deleted = true;
            }
            if ($deleted === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'approve_photo') {
        if (!empty($_GET['photo_id'])) {
            $photo_id = (int)Secure($_GET['photo_id']);
            Wo_ApprovePhoto($photo_id);
            $data = array(
                'status' => 200
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'disapprove_photo') {
        if (!empty($_GET['photo_id'])) {
            $photo_id = (int)Secure($_GET['photo_id']);
            Wo_DisApprovePhoto($photo_id);
            $data = array(
                'status' => 200
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'approve_all_photo') {
        Wo_ApproveAllPhoto();
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'disapprove_all_photo') {
        Wo_DisApproveAllPhoto();
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'approve_receipt') {
        if (!empty($_GET['receipt_id'])) {
            $photo_id = Secure($_GET['receipt_id']);
            $receipt = $db->where('id',$photo_id)->getOne('bank_receipts',array('*'));

            if($receipt){

                $membershipType = 0;
                $amount         = 0;
                $realprice      = (int)$receipt['price'];

                if ($receipt['mode'] == 'credits') {
                    if ($realprice == (int)$wo['config']['bag_of_credits_price']) {
                        $amount = (int)$wo['config']['bag_of_credits_amount'];
                    } else if ($realprice == (int)$wo['config']['box_of_credits_price']) {
                        $amount = (int)$wo['config']['box_of_credits_amount'];
                    } else if ($realprice == (int)$wo['config']['chest_of_credits_price']) {
                        $amount = (int)$wo['config']['chest_of_credits_amount'];
                    }
                } else if ($receipt['mode'] == 'membership') {
                    if ($realprice == (int)$wo['config']['weekly_pro_plan']) {
                        $membershipType = 1;
                    } else if ($realprice == (int)$wo['config']['monthly_pro_plan']) {
                        $membershipType = 2;
                    } else if ($realprice == (int)$wo['config']['yearly_pro_plan']) {
                        $membershipType = 3;
                    } else if ($realprice == (int)$wo['config']['lifetime_pro_plan']) {
                        $membershipType = 4;
                    }
                } else if ($receipt['mode'] == 'unlock_photo_private') {

                }


                $updated = $db->where('id',$photo_id)->update('bank_receipts',array('approved'=>1,'approved_at'=>time()));
                if ($updated === true) {

                    $Notification = LoadEndPointResource('Notifications');
                    if($Notification) {
                        $Notification->createNotification(auth()->web_device_id, auth()->id, $receipt['user_id'], 'approve_receipt', $wo['config']['currency_symbol'] . $realprice, '/#');
                    }

                    if($receipt['mode'] == 'credits'){
                        $query_one = mysqli_query($conn, "UPDATE `users` SET `balance` = `balance` + {$amount} WHERE `id` = {$receipt['user_id']}");
                    }
                    if($receipt['mode'] == 'membership'){
                        $query_one = mysqli_query($conn, "UPDATE `users` SET `pro_time` = '".time()."', `is_pro` = '1', `pro_type` = '".$membershipType."' WHERE `id` = ".$receipt['user_id']);
                    }

                    if($receipt['mode'] == 'unlock_photo_private'){
                        $query_one = mysqli_query($conn, "UPDATE `users` SET `lock_private_photo` = 0 WHERE `id` = {$receipt['user_id']}");
                    }

                    $query_one = mysqli_query($conn, "INSERT `payments`(`user_id`,`amount`,`type`,`pro_plan`,`credit_amount`,`via`) VALUES ('{$receipt['user_id']}','{$receipt['price']}','{$receipt['mode']}','{$membershipType}','{$amount}','Bank transfer');");

                    $data = array(
                        'status' => 200
                    );
                }
            }
            $data = array(
                'status' => 200,
                'data' => $receipt
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_receipt') {
        if (!empty($_GET['receipt_id'])) {
            $user_id = Secure($_GET['user_id']);
            $photo_id = Secure($_GET['receipt_id']);
            $photo_file = Secure($_GET['receipt_file']);

            $Notification = LoadEndPointResource('Notifications');
            if($Notification) {
                $Notification->createNotification(auth()->web_device_id, auth()->id, $user_id, 'disapprove_receipt', '', '/contact');
            }

            $deleted = false;
            $db->where('id',$photo_id)->delete('bank_receipts');
            if (DeleteFromToS3( $photo_file ) === true) {
                $deleted = true;
            }
            if ($deleted === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_reported_content') {
        if (!empty($_GET['id']) && !empty($_GET['type']) && !empty($_GET['report_id'])) {
            $type   = Secure($_GET['type']);
            $id     = Secure($_GET['id']);
            $report = Secure($_GET['report_id']);
            if ($type == 'user') {
                $deleteReport = Wo_DeleteReport($report);
                if ($deleteReport === true) {
                    $data = array(
                        'status' => 200,
                        'html' => Wo_CountUnseenReports()
                    );
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'mark_as_safe') {
        if (!empty($_GET['report_id'])) {
            $deleteReport = Wo_DeleteReport($_GET['report_id']);
            if ($deleteReport === true) {
                $data = array(
                    'status' => 200,
                    'html' => Wo_CountUnseenReports()
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_general_setting' && Wo_CheckSession($hash_id) === true) {
        $saveSetting = false;
        if (isset($_POST['skey'])) {
            unset($_POST['skey']);
        }
        if (isset($_POST['hash_id'])) {
            unset($_POST['hash_id']);
        }
        if (!empty($_FILES) && !empty($_FILES["cloud_file"])) {
            $fileInfo = array(
                'file' => $_FILES["cloud_file"]["tmp_name"],
                'name' => $_FILES['cloud_file']['name'],
                'size' => $_FILES["cloud_file"]["size"],
                'type' => $_FILES["cloud_file"]["type"],
                'types' => 'json',
                'local_upload' => 1
            );
            $media    = ShareFile($fileInfo);
            if (!empty($media) && !empty($media['filename'])) {
                Wo_SaveConfig('cloud_file_path', $media['filename']);
                $saveSetting = true;
            }
        }
        foreach ($_POST as $key => $value) {
            if ($key == 'smtp_password') {
                $value = openssl_encrypt($value, "AES-128-ECB", 'mysecretkey1234');
            }
            if ($key == 'google_tag_code') {
                $value = base64_decode($value);
            }
            if ($key == 'google_tag_code') {
                $value = openssl_encrypt($value, "AES-128-ECB", 'mysecretkey1234');
            }
            if ($key == 'twilio_chat_call' && $value == 1) {
                if ($wo['config']['agora_chat_call'] == 1) {
                    Wo_SaveConfig('agora_chat_call', 0);
                }
            }
            if ($key == 'agora_chat_call' && $value == 1) {
                if ($wo['config']['twilio_chat_call'] == 1) {
                    Wo_SaveConfig('twilio_chat_call', 0);
                }
            }
            if ($key == 'bank' || $key == 'p_paypal' || $key == 'skrill' || $key == 'custom') {
                if (in_array($value, array(0,1))) {
                    $p_key = $key;
                    if ($key == 'p_paypal') {
                        $p_key = 'paypal';
                    }
                    $wo['config']['withdrawal_payment_method'][$p_key] = Secure($value);
                    Wo_SaveConfig('withdrawal_payment_method', json_encode($wo['config']['withdrawal_payment_method']));
                }
            }
            if ($key == 'bulksms_provider' && $value == 1) {
                Wo_SaveConfig('twilio_provider', 0);
                Wo_SaveConfig('messagebird_provider', 0);
                Wo_SaveConfig('infobip_provider', 0);
                Wo_SaveConfig('msg91_provider', 0);
            }
            if ($key == 'twilio_provider' && $value == 1) {
                Wo_SaveConfig('bulksms_provider', 0);
                Wo_SaveConfig('messagebird_provider', 0);
                Wo_SaveConfig('infobip_provider', 0);
                Wo_SaveConfig('msg91_provider', 0);
            }
            if ($key == 'messagebird_provider' && $value == 1) {
                Wo_SaveConfig('bulksms_provider', 0);
                Wo_SaveConfig('twilio_provider', 0);
                Wo_SaveConfig('infobip_provider', 0);
                Wo_SaveConfig('msg91_provider', 0);
            }
            if ($key == 'infobip_provider' && $value == 1) {
                Wo_SaveConfig('bulksms_provider', 0);
                Wo_SaveConfig('twilio_provider', 0);
                Wo_SaveConfig('messagebird_provider', 0);
                Wo_SaveConfig('msg91_provider', 0);
            }
            if ($key == 'msg91_provider' && $value == 1) {
                Wo_SaveConfig('bulksms_provider', 0);
                Wo_SaveConfig('twilio_provider', 0);
                Wo_SaveConfig('messagebird_provider', 0);
                Wo_SaveConfig('infobip_provider', 0);
            }
            if ($key == 'amazone_s3') {
                if ($value == 1) {
                    if ($wo['config']['spaces'] == 1) {
                        $saveSetting = Wo_SaveConfig('spaces', 0);
                    }
                    if ($wo['config']['wasabi_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('wasabi_storage', 0);
                    }
                    if ($wo['config']['ftp_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('ftp_upload', 0);
                    }
                    if ($wo['config']['cloud_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('cloud_upload', 0);
                    }
                    if ($wo['config']['backblaze_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('backblaze_storage', 0);
                    }
                }
            }
            if ($key == 'spaces') {
                if ($value == 1) {
                    if ($wo['config']['amazone_s3'] == 1) {
                        $saveSetting = Wo_SaveConfig('amazone_s3', 0);
                    }
                    if ($wo['config']['wasabi_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('wasabi_storage', 0);
                    }
                    if ($wo['config']['ftp_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('ftp_upload', 0);
                    }
                    if ($wo['config']['cloud_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('cloud_upload', 0);
                    }
                    if ($wo['config']['backblaze_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('backblaze_storage', 0);
                    }
                }
            }
            if ($key == 'wasabi_storage') {
                if ($value == 1) {
                    if ($wo['config']['amazone_s3'] == 1) {
                        $saveSetting = Wo_SaveConfig('amazone_s3', 0);
                    }
                    if ($wo['config']['spaces'] == 1) {
                        $saveSetting = Wo_SaveConfig('spaces', 0);
                    }
                    if ($wo['config']['ftp_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('ftp_upload', 0);
                    }
                    if ($wo['config']['cloud_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('cloud_upload', 0);
                    }
                    if ($wo['config']['backblaze_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('backblaze_storage', 0);
                    }
                }
            }
            if ($key == 'ftp_upload') {
                if ($value == 1) {
                    if ($wo['config']['amazone_s3'] == 1) {
                        $saveSetting = Wo_SaveConfig('amazone_s3', 0);
                    }
                    if ($wo['config']['spaces'] == 1) {
                        $saveSetting = Wo_SaveConfig('spaces', 0);
                    }
                    if ($wo['config']['wasabi_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('wasabi_storage', 0);
                    }
                    if ($wo['config']['cloud_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('cloud_upload', 0);
                    }
                    if ($wo['config']['backblaze_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('backblaze_storage', 0);
                    }
                }
            }
            if ($key == 'cloud_upload') {
                if ($value == 1) {
                    if ($wo['config']['amazone_s3'] == 1) {
                        $saveSetting = Wo_SaveConfig('amazone_s3', 0);
                    }
                    if ($wo['config']['spaces'] == 1) {
                        $saveSetting = Wo_SaveConfig('spaces', 0);
                    }
                    if ($wo['config']['wasabi_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('wasabi_storage', 0);
                    }
                    if ($wo['config']['ftp_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('ftp_upload', 0);
                    }
                    if ($wo['config']['backblaze_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('backblaze_storage', 0);
                    }
                }
            }
            if ($key == 'backblaze_storage') {
                if ($value == 1) {
                    if ($wo['config']['amazone_s3'] == 1) {
                        $saveSetting = Wo_SaveConfig('amazone_s3', 0);
                    }
                    if ($wo['config']['spaces'] == 1) {
                        $saveSetting = Wo_SaveConfig('spaces', 0);
                    }
                    if ($wo['config']['wasabi_storage'] == 1) {
                        $saveSetting = Wo_SaveConfig('wasabi_storage', 0);
                    }
                    if ($wo['config']['ftp_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('ftp_upload', 0);
                    }
                    if ($wo['config']['cloud_upload'] == 1) {
                        $saveSetting = Wo_SaveConfig('cloud_upload', 0);
                    }
                }
            }
            $saveSetting = Wo_SaveConfig($key, $value);
            if( $key == 'image_verification' && $value == "1"){
                Wo_SaveConfig('image_verification_start', time());
            }elseif( $key == 'image_verification' && $value == "0"){
                Wo_SaveConfig('image_verification_start', 0);
            }
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_pages_seo' && Wo_CheckSession($hash_id) === true) {
        $config_seo = $wo['config']['seo'];

        $arr_seo = json_decode($config_seo,true);
        $arr_seo[$_POST['page_name']] = array(
            'title' => $_POST['default_title'],
            'meta_description' => $_POST['meta_description'],
            'meta_keywords' => $_POST['meta_keywords'],
        );
        $saveSetting = Wo_SaveConfig('seo', json_encode($arr_seo));
        if ($saveSetting === true) {
            $data['status'] = 200;
            $data['page'] = $_POST['page_name'];
            $data['config_seo'] = $wo['config']['seo'];
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'save-design' && Wo_CheckSession($hash_id) === true) {
        $saveSetting = false;
        if (isset($_FILES['logo']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["logo"]["tmp_name"],
                'name' => $_FILES['logo']['name'],
                'size' => $_FILES["logo"]["size"]
            );
            $media    = UploadLogo($fileInfo);
        }
        if (isset($_FILES['light-logo']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["light-logo"]["tmp_name"],
                'name' => $_FILES['light-logo']['name'],
                'size' => $_FILES["light-logo"]["size"],
                'light-logo' => true
            );
            $media    = UploadLogo($fileInfo);
        }
        if (isset($_FILES['favicon']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["favicon"]["tmp_name"],
                'name' => $_FILES['favicon']['name'],
                'size' => $_FILES["favicon"]["size"],
                'favicon' => true
            );
            $media    = UploadLogo($fileInfo);
        }

        $saveSetting = false;
        foreach ($_POST as $key => $value) {
            $saveSetting = Wo_SaveConfig($key, $value);
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }

        $data['status'] = 200;
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_user' && isset($_GET['user_id']) && Wo_CheckSession($hash_id) === true) {
        $deleted = false;
        $d_user = LoadEndPointResource('users');
        if($d_user) {
            $deleted = $d_user->delete_user(Secure($_GET['user_id']));
        }
        if ($deleted['is_delete'] === true) {
            $data['status'] = 200;
            $data['message'] = 'Deleted';
        }else{
            $data['status'] = 200;
            $data['message'] = 'Not Deleted';
        }
        $data['deleted'] = $deleted;
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_terms_setting' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_POST['lang_key'])) {
            $lang_key = Secure($_POST['lang_key']);
            $langs    = Wo_LangsNamesFromDB();
            foreach ($_POST as $key => $value) {
                if (in_array($key, $langs)) {
                    $key   = Secure($key);
                    $value = base64_decode($value);
                    $value = mysqli_real_escape_string($conn, $value);
                    $query = mysqli_query($conn, "UPDATE `langs` SET `{$key}` = '{$value}' WHERE `lang_key` = '{$lang_key}'");
                    if ($query) {
                        $data['status'] = 200;
                    }
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
        // $saveSetting = false;
        // foreach ($_POST as $key => $value) {
        //     $saveSetting = Wo_SaveConfig($key, base64_decode($value));
        // }
        // if ($saveSetting === true) {
        //     $data['status'] = 200;
        // }
        // echo json_encode($data);
        // exit();
    }
    if ($s == 'add_new_lang') {
        if (Wo_CheckSession($hash_id) === true) {
            $mysqli = Wo_LangsNamesFromDB();
            if (in_array($_POST['lang'], $mysqli)) {
                $data['status']  = 400;
                $data['message'] = 'This lang is already used.';
            } else if( !ctype_alpha($_POST['lang']) ) {
                $data['status']  = 400;
                $data['message'] = 'you can use only letters in language name.';
            } else {
                $lang_o_name = Secure($_POST['lang']);
                $lang_name = Secure($_POST['lang']);
                $lang_name = strtolower($lang_name);
                $lang_o_for_insert_name = $lang_name;
                $query     = mysqli_query($conn, "ALTER TABLE `langs` ADD `$lang_name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
                if ($query) {
                    //$content = file_get_contents('assets/languages/extra/english.php');
                    //$fp      = fopen("assets/languages/extra/$lang_name.php", "wb");
                    //fwrite($fp, $content);
                    //fclose($fp);
                    $english = Wo_LangsFromDB('english');
                    foreach ($english as $key => $lang) {
                        $lang  = Secure($lang);
                        $query = mysqli_query($conn, "UPDATE `langs` SET `{$lang_name}` = '$lang' WHERE `lang_key` = '{$key}'");
                    }
                    $data_langs = [];
                    $query = mysqli_query($conn, "SHOW COLUMNS FROM `langs`");
                    while ($fetched_data = mysqli_fetch_assoc($query)) {
                        if ($fetched_data['Field'] != "ref" && $fetched_data['Field'] != "lang_key" && $fetched_data['Field'] != "id") {
                            $data_langs[] = $fetched_data['Field'];
                        }
                    }
                    $final_query = "";
                    $implode = implode(', ', $data_langs);
                    for ($i=0; $i < count($data_langs); $i++) {
                        $text = "'$lang_name',";
                        if (($i + 1) == count($data_langs)) {
                            $text = "'$lang_name'";
                        }
                        $final_query .= $text;
                    }
                    $insert = mysqli_query($conn, "INSERT INTO `langs` (`id`, `lang_key`, $implode) VALUES (NULL, '$lang_o_for_insert_name', $final_query)");
                    $data['status'] = 200;
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_lang_key') {
        if (Wo_CheckSession($hash_id) === true) {
            if (!empty($_POST['lang_key'])) {
                $lang_key  = Secure($_POST['lang_key']);
                $mysqli    = mysqli_query($conn, "SELECT COUNT(id) as count FROM `langs` WHERE `lang_key` = '$lang_key'");
                $sql_fetch = mysqli_fetch_assoc($mysqli);
                if ($sql_fetch['count'] == 0) {
                    $mysqli = mysqli_query($conn, "INSERT INTO `langs` (`lang_key`) VALUE ('$lang_key')");
                    if ($mysqli) {
                        $_SESSION['language_changed'] = true;
                        $data['status'] = 200;
                        $data['url']    = Wo_LoadAdminLinkSettings('manage-languages');
                    }
                } else {
                    $data['status']  = 400;
                    $data['message'] = 'This key is already used, please use other one.';
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_lang') {
        $mysqli = Wo_LangsNamesFromDB();
        if (in_array($_GET['id'], $mysqli)) {
            $lang_name = Secure($_GET['id']);
            $query     = mysqli_query($conn, "ALTER TABLE `langs` DROP COLUMN `$lang_name`");
            if ($query) {
                //unlink("assets/languages/extra/$lang_name.php");
                $data['status'] = 200;
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_lang_key') {
        if (Wo_CheckSession($hash_id) === true) {
            $array_langs = array();
            $lang_key    = Secure($_POST['id_of_key']);
            $langs       = Wo_LangsNamesFromDB('english',true);
            foreach ($_POST as $key => $value) {
                if (in_array($key, $langs)) {
                    $key   = Secure($key);
                    $value = Secure($value);
                    $query = mysqli_query($conn, "UPDATE `langs` SET `{$key}` = '{$value}' WHERE `id` = '{$lang_key}'");
                    if ($query) {
                        $data['status'] = 200;
                        $_SESSION['language_changed'] = true;
                    }
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_message') {
        $send_message      = SendEmail(auth()->email,'Test Message From ' . $wo['config']['siteName'],'If you can see this message, then your SMTP configuration is working fine.');
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = 'Error while sending email.';
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_sms_message') {
        $message      = 'This is a test message from ' . $wo['config']['siteName'];
        $send_message = SendSMS($wo['config']['sms_phone_number'], $message);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = $send_message;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_bulksms_message') {
        $message      = 'This is a test message from ' . $wo['config']['siteName'];
        $send_message = SendSMS($wo['config']['bulksms_phone_number'], $message);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = $send_message;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_msg91_message') {
        $message      = 'This is a test message from ' . $wo['config']['siteName'];
        $send_message = SendSMS($wo['config']['msg91_phone_number'], $message);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = $send_message;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_infobip_message') {
        $message      = 'This is a test message from ' . $wo['config']['siteName'];
        $send_message = SendSMS($wo['config']['infobip_phone_number'], $message);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = $send_message;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_messagebird_message') {
        $message      = 'This is a test message from ' . $wo['config']['siteName'];
        $send_message = SendSMS($wo['config']['messagebird_phone'], $message);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = $send_message;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_s3') {
        require_once '../libs/s3/vendor/autoload.php';
        try {
            $s3Client = S3Client::factory(array(
                'version' => 'latest',
                'region' => $wo['config']['region'],
                'credentials' => array(
                    'key' => $wo['config']['amazone_s3_key'],
                    'secret' => $wo['config']['amazone_s3_s_key']
                )
            ));
            $buckets  = $s3Client->listBuckets();
            $result   = $s3Client->putBucketCors(array(
                'Bucket' => $wo['config']['bucket_name'], // REQUIRED
                'CORSConfiguration' => array( // REQUIRED
                    'CORSRules' => array( // REQUIRED
                        array(
                            'AllowedHeaders' => array(
                                'Authorization'
                            ),
                            'AllowedMethods' => array(
                                'POST',
                                'GET',
                                'PUT'
                            ), // REQUIRED
                            'AllowedOrigins' => array(
                                '*'
                            ), // REQUIRED
                            'ExposeHeaders' => array(),
                            'MaxAgeSeconds' => 3000
                        )
                    )
                )
            ));
            if (!empty($buckets)) {
                if ($s3Client->doesBucketExist($wo['config']['bucket_name'])) {
                    $data['status'] = 200;
                    $array          = array(
                        'upload/photos/d-avatar.jpg'
                    );
                    foreach ($array as $key => $value) {
                        $s3Client->putObject([
                            'Bucket' => $wo['config']['bucket_name'],
                            'Key'    => $value,
                            'Body'   => fopen('../'.$value, 'r+'),
                            'ACL'    => 'public-read',
                            'CacheControl' => 'max-age=3153600',
                        ]);
                        // $upload = Wo_UploadToS3($value, array(
                        //     'delete' => 'no'
                        // ));
                    }
                } else {
                    $data['status'] = 300;
                }
            } else {
                $data['status'] = 500;
            }
        }
        catch (Exception $e) {
            $data['status']  = 400;
            $data['message'] = $e->getMessage();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_spaces') {
        require_once '../libs/s3/vendor/autoload.php';
        try {
            $s3Client = S3Client::factory(array(
                'version' => 'latest',
                'region' => $wo['config']['space_region'],
                'endpoint' => 'https://' . $wo['config']['space_region'] . '.digitaloceanspaces.com',
                'credentials' => array(
                    'key' => $wo['config']['spaces_key'],
                    'secret' => $wo['config']['spaces_secret']
                )
            ));

            $buckets  = $s3Client->listBuckets();
            if (!empty($buckets)) {
                if ($s3Client->doesBucketExist($wo['config']['space_name'])) {
                    $data['status'] = 200;
                    $array          = array(
                        '../upload/photos/d-avatar.jpg'
                    );
                    foreach ($array as $key => $value) {
                        $upload = Wo_UploadToS3($value, array(
                            'delete' => 'no'
                        ));
                    }
                }

                else {
                    $data['status'] = 300;
                }
            }
            else {
                $data['status'] = 500;
            }
        }

        catch (Exception $e) {
            $data['status']  = 400;
            $data['message'] = $e->getMessage();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_wasabi') {
        include_once('../libs/s3/vendor/autoload.php');
        try {
            $s3Client = S3Client::factory(array(
                'version' => 'latest',
                'endpoint' => 'https://s3.'.$wo['config']['wasabi_bucket_region'].'.wasabisys.com',
                'region' => $wo['config']['wasabi_bucket_region'],
                'credentials' => array(
                    'key' => $wo['config']['wasabi_access_key'],
                    'secret' => $wo['config']['wasabi_secret_key']
                )
            ));
            $buckets  = $s3Client->listBuckets();

            if (!empty($buckets)) {
                if ($s3Client->doesBucketExist($wo['config']['wasabi_bucket_name'])) {
                    $data['status'] = 200;
                    $array          = array(
                        'upload/photos/d-avatar.jpg'
                    );
                    foreach ($array as $key => $filename) {
                        $s3Client->putObject(array(
                            'Bucket' => $wo['config']['wasabi_bucket_name'],
                            'Key' => $filename,
                            'Body' => fopen('../'.$filename, 'r+'),
                            'ACL' => 'public-read',
                            'CacheControl' => 'max-age=3153600'
                        ));
                        // $upload = Wo_UploadToS3($value, array(
                        //     'delete' => 'no'
                        // ));
                    }
                } else {
                    $data['status'] = 300;
                }
            } else {
                $data['status'] = 500;
            }
        }
        catch (Exception $e) {
            $data['status']  = 400;
            $data['message'] = $e->getMessage();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_ftp') {
        include_once('../libs/ftp/vendor/autoload.php');
        try {
            $ftp = new \FtpClient\FtpClient();
            $ftp->connect($wo['config']['ftp_host'], false, $wo['config']['ftp_port']);
            $login = $ftp->login($wo['config']['ftp_username'], $wo['config']['ftp_password']);
            if ($login) {
                $array = array(
                    'upload/photos/d-avatar.jpg'
                );
                $array = array(
                    'upload/photos/fdsds.png'
                );
                foreach ($array as $key => $filename) {
                    if (!empty($wo['config']['ftp_path'])) {
                        if ($wo['config']['ftp_path'] != "./") {
                            $ftp->chdir($wo['config']['ftp_path']);
                        }
                    }
                    $file_path      = substr($filename, 0, strrpos($filename, '/'));
                    $file_path_info = explode('/', $file_path);
                    $path           = '';
                    if (!$ftp->isDir($file_path)) {
                        foreach ($file_path_info as $key2 => $value) {
                            if (!empty($path)) {
                                $path .= '/' . $value . '/';
                            } else {
                                $path .= $value . '/';
                            }
                            if (!$ftp->isDir($path)) {
                                $mkdir = $ftp->mkdir($path);
                            }
                        }
                    }
                    $ftp->chdir($file_path);
                    $ftp->pasv(true);
                    if ($ftp->putFromPath($filename)) {
                        $ftp->close();
                    }
                    $ftp->close();










                    // $upload = Wo_UploadToS3($value, array(
                    //     'delete' => 'no'
                    // ));
                }
                $data['status'] = 200;
            }
            else{
                $data['status']  = 400;
                $data['message'] = 'can not login to ftp';
            }
        }
        catch (Exception $e) {
            $data['status']  = 400;
            $data['message'] = $e->getMessage();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_cloud') {
        if ($wo['config']['cloud_upload'] == 0 || empty($wo['config']['cloud_file_path']) || empty($wo['config']['cloud_bucket_name'])) {
            $data['message'] = 'Please enable Google Cloud Storage and fill all fields.';
        } elseif (!file_exists($wo['config']['cloud_file_path'])) {
            $data['message'] = 'Google Cloud File not found on your server Please upload it to your server.';
        } else {
            require_once '../libs/cloud/vendor/autoload.php';
            try {
                $storage = new StorageClient(array(
                    'keyFilePath' => $wo['config']['cloud_file_path']
                ));
                // set which bucket to work in
                $bucket  = $storage->bucket($wo['config']['cloud_bucket_name']);
                if ($bucket) {
                    $array = array(
                        '../upload/photos/d-avatar.jpg'
                    );
                    foreach ($array as $key => $value) {
                        $fileContent   = file_get_contents($value);
                        // upload/replace file
                        $storageObject = $bucket->upload($fileContent, array(
                            'name' => 'upload/photos/d-avatar.jpg'
                        ));
                    }
                    $data['status'] = 200;
                } else {
                    $data['message'] = 'Error in connection';
                }
            }
            catch (Exception $e) {
                $data['message'] = "" . $e;
                // maybe invalid private key ?
                // print $e;
                // exit();
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_s3_2') {
        require_once '../libs/s3/vendor/autoload.php';
        try {
            $s3Client = S3Client::factory(array(
                'version' => 'latest',
                'region' => $wo['config']['region_2'],
                'credentials' => array(
                    'key' => $wo['config']['amazone_s3_key_2'],
                    'secret' => $wo['config']['amazone_s3_s_key_2']
                )
            ));
            $buckets  = $s3Client->listBuckets();
            $result   = $s3Client->putBucketCors(array(
                'Bucket' => $wo['config']['bucket_name_2'], // REQUIRED
                'CORSConfiguration' => array( // REQUIRED
                    'CORSRules' => array( // REQUIRED
                        array(
                            'AllowedHeaders' => array(
                                'Authorization'
                            ),
                            'AllowedMethods' => array(
                                'POST',
                                'GET',
                                'PUT'
                            ), // REQUIRED
                            'AllowedOrigins' => array(
                                '*'
                            ), // REQUIRED
                            'ExposeHeaders' => array(),
                            'MaxAgeSeconds' => 3000
                        )
                    )
                )
            ));
            if (!empty($buckets)) {
                if ($s3Client->doesBucketExist($wo['config']['bucket_name_2'])) {
                    $data['status'] = 200;
                    $array          = array(
                        'upload/photos/d-avatar.jpg'
                    );
                    foreach ($array as $key => $value) {
                        $upload = Wo_UploadToS3($value, array(
                            'delete' => 'no'
                        ));
                    }
                } else {
                    $data['status'] = 300;
                }
            } else {
                $data['status'] = 500;
            }
        }
        catch (Exception $e) {
            $data['status']  = 400;
            $data['message'] = $e->getMessage();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'fake-users') {

        $countries = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua And Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia And Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, Democratic Republic', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island & Mcdonald Islands', 'VA' => 'Holy See (Vatican City State)', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic Of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle Of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KR' => 'Korea', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States Of', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory, Occupied', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts And Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre And Miquelon', 'VC' => 'Saint Vincent And Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome And Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia And Sandwich Isl.', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard And Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad And Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks And Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis And Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');
        $countries_key = array_keys($countries);

        require $_BASEPATH.'libs'.DIRECTORY_SEPARATOR.'fake-users'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
        $faker = Faker\Factory::create();
        if (empty($_POST['password'])) {
            $_POST['password'] = '123456789';
        }
        $count_users = $_POST['count_users'];
        $password = $_POST['password'];
        $avatar = $_POST['avatar'];

        Wo_RunInBackground(array('status' => 200));

        $Date1 = date('Y-m-d');
        $Date2 = date('Y-m-d', strtotime($Date1 . " - 19 year"));
        $users      = LoadEndPointResource('users');
        if ($users) {
            for ($i=0; $i < $count_users; $i++) {
                $genders = array("4525", "4526");
                $random_keys = array_rand($genders, 1);
                $gender = array_rand(array("male", "female"), 1);
                $gender = $genders[$random_keys];
                $re_data  = array(
                    'email' => Secure(str_replace(".", "_", $faker->userName) . '_' . rand(111, 999) . "@yahoo.com", 0),
                    'username' => Secure($faker->userName . '_' . rand(111, 999), 0),
                    'password' => Secure($password, 0),
                    'email_code' => Secure(md5($faker->userName . '_' . rand(111, 999)), 0),
                    'src' => 'Fake',
                    'gender' => Secure($gender),
                    'lastseen' => time(),
                    'verified' => 1,
                    'active' => 1,
                    'start_up' => '3',
                    'first_name' => $faker->name,
                    'last_name' => $faker->lastName,
                    'lat' => auth()->lat,
                    'lng' => auth()->lng,
                    'birthday' => $Date2,
                    'country_id' => $countries_key[array_rand($countries_key)],
                    'about' => 'Ut ab voluptas sed a nam. Sint autem inventore aut officia aut aut blanditiis. Ducimus eos odit amet et est ut eum.'
                );
                $compress = false;
                $last_file = '';

                if ($avatar == 1) {
                    
                    $re_data['avater'] = $wo['site_url'].'/upload/photos/users/'.rand(1,20).'.jpg';
                    $re_data['avater'] = $users->ImportImageFromLogin($re_data['avater'],1);
                    $imported_image = $re_data['avater'];
                    $explode2  = @end(explode('.', $imported_image));
                    $explode3  = @explode('.', $imported_image);
                    $last_file = $explode3[0] . '_full.' . $explode2;
                }

                $re_data['address']         = $faker->address;
                $re_data['facebook']        = $faker->company;
                $re_data['google']          = $faker->company;
                $re_data['twitter']         = $faker->company;
                $re_data['linkedin']        = $faker->company;
                $re_data['website']         = $faker->company;
                $re_data['instagram']       = $faker->company;
                $re_data['language']        = 'english';
                $re_data['type']            = 'user';
                $re_data['phone_number']    = $faker->phoneNumber;
                $re_data['timezone']        = 'UTC';
                $re_data['start_up']        = '3';
                $re_data['height']          = '152';
                $re_data['hair_color']      = '1';
                $re_data['interest']        = 'Sint autem inventore aut officia';
                $re_data['location']        = 'Ducimus';
                $re_data['relationship']    = '1';
                $re_data['work_status']     = '2';
                $re_data['education']       = '3';
                $re_data['ethnicity']       = '3';
                $re_data['body']            = '3';
                $re_data['character']       = '13';
                $re_data['children']        = '2';
                $re_data['friends']         = '3';
                $re_data['pets']            = '0';
                $re_data['live_with']       = '3';
                $re_data['car']             = '2';
                $re_data['religion']        = '1';
                $re_data['smoke']           = '2';
                $re_data['drink']           = '2';
                $re_data['travel']          = '2';
                $re_data['music']           = 'pop';
                $re_data['dish']            = 'meat';
                $re_data['song']            = 'song';
                $re_data['hobby']           = 'hobby';
                $re_data['city']            = 'city';
                $re_data['sport']           = 'sport';
                $re_data['book']            = 'book';
                $re_data['movie']           = 'movie';
                $re_data['colour']          = 'red';
                $re_data['tv']              = 'tv';
                $re_data['privacy_show_profile_on_google']      = 1;
                $re_data['privacy_show_profile_random_users']   = 1;
                $re_data['privacy_show_profile_match_profiles'] = 1;
                $re_data['phone_verified']                      = 1;
                $re_data['online']                              = 1;

                $regestered_user = $users->register($re_data);
                if (!empty($regestered_user) && !empty($regestered_user['userId']) && $compress && !empty($last_file)) {
                    $media                 = array();
                    $media[ 'user_id' ]    = $regestered_user['userId'];
                    $media[ 'file' ]       = $last_file;
                    $media[ 'created_at' ] = date('Y-m-d H:i:s');
                    $media[ 'is_approved' ] = 1;
                    $saved                 = $db->insert('mediafiles', $media);
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_announcement') {
        if (!empty($_POST['announcement_text'])) {
            $html = '';
            $id   = Wo_AddNewAnnouncement(base64_decode($_POST['announcement_text']));
            if ($id > 0) {
                $wo['activeAnnouncement'] = Wo_GetAnnouncement($id);
                $html .= Wo_LoadAdminPage('manage-announcements/active-list', false);
                $data = array(
                    'status' => 200,
                    'text' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_announcement') {
        if (!empty($_GET['id'])) {
            $DeleteAnnouncement = Wo_DeleteAnnouncement($_GET['id']);
            if ($DeleteAnnouncement === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'disable_announcement') {
        if (!empty($_GET['id'])) {
            $html                = '';
            $DisableAnnouncement = Wo_DisableAnnouncement(Secure($_GET['id']));
            if ($DisableAnnouncement === true) {
                $wo['inactiveAnnouncement'] = Wo_GetAnnouncement(Secure($_GET['id']));
                $html .= Wo_LoadAdminPage('manage-announcements/inactive-list', false);
                $data = array(
                    'status' => 200,
                    'html' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'activate_announcement') {
        if (!empty($_GET['id'])) {
            $html                 = '';
            $ActivateAnnouncement = Wo_ActivateAnnouncement(Secure($_GET['id']));
            if ($ActivateAnnouncement === true) {
                $wo['activeAnnouncement'] = Wo_GetAnnouncement($_GET['id']);
                $html .= Wo_LoadAdminPage('manage-announcements/active-list', false);
                $data = array(
                    'status' => 200,
                    'html' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_country') {
        if (Wo_CheckSession($hash_id) === true) {
            $insert_data = array();
            $insert_data['ref'] = 'country';
            $add = false;
            foreach (Wo_LangsNamesFromDB() as $wo['key_']) {
                if (!empty($_POST[$wo['key_']])) {
                    $insert_data[$wo['key_']] = Secure($_POST[$wo['key_']]);
                    $add = true;
                }
            }
            if ($add == true) {
                $insert_data['options'] = Secure($_POST['options']);
                $id = $db->insert('langs', $insert_data);
                if (!empty($_POST['lang_key'])) {
                    $db->where('id', $id)->update('langs', array('lang_key' => Secure($_POST['lang_key'])));
                }else{
                    $db->where('id', $id)->update('langs', array('lang_key' => $id));
                }

                $data['status'] = 200;
            } else {
                $data['status'] = 400;
                $data['message'] = 'please check details';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_country') {
        header("Content-type: application/json");
        if (!empty($_GET['key']) && in_array($_GET['key'], array_keys(Dataset::countries('id')))) {
                $db->where('id',Secure($_GET['key']))->delete('langs');
                $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_verification') {
        header("Content-type: application/json");
        if (!empty($_GET['id']) && $_GET['id'] > 0) {
            $verify = $db->where('id',Secure($_GET['id']))->objectbuilder()->getOne('verification_requests');
            if (!empty($verify)) {
                $db->where('id',$verify->user_id)->update('users',['start_up' => '0']);
            }
            $db->where('id',Secure($_GET['id']))->delete('verification_requests');
            $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'verify_user' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_GET['id'])) {
            $type = '';
            if (Wo_VerifyUser($_GET['id'], $_GET['verification_id']) === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_gender') {
        if (Wo_CheckSession($hash_id) === true) {
            $insert_data = array();
            $insert_data['ref'] = 'gender';
            $add = false;
            foreach (Wo_LangsNamesFromDB() as $wo['key_']) {
                if (!empty($_POST[$wo['key_']])) {
                    $insert_data[$wo['key_']] = Secure($_POST[$wo['key_']]);
                    $add = true;
                }
            }
            if ($add == true) {
                $id = $db->insert('langs', $insert_data);
                $db->where('id', $id)->update('langs', array('lang_key' => $id));
                $data['status'] = 200;
            } else {
                $data['status'] = 400;
                $data['message'] = 'please check details';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_gender') {
        header("Content-type: application/json");
        if (!empty($_GET['key']) && in_array($_GET['key'], array_keys(Dataset::gender()))) {
            if((int)$_GET['key'] == 4526 || (int)$_GET['key'] == 4525 ){
                $data['status'] = 300;
            }else {
                $db->where('lang_key',Secure($_GET['key']))->delete('langs');
                $data['status'] = 200;
            }
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_page' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_POST['page_name']) && !empty($_POST['page_content']) && !empty($_POST['page_title'])) {
            $page_name    = Secure($_POST['page_name']);
            $page_content = Secure($_POST['page_content']);
            $page_title   = Secure($_POST['page_title']);
            $page_type    = 0;
            if (!empty($_POST['page_type'])) {
                $page_type = 1;
            }
            if (!preg_match('/^[\w]+$/', $page_name)) {
                $data = array(
                    'status' => 400,
                    'message' => 'Invalid page name characters'
                );
                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
            $data_ = array(
                'page_name' => $page_name,
                'page_content' => $page_content,
                'page_title' => $page_title,
                'page_type' => $page_type
            );
            $add   = Wo_RegisterNewPage($data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'edit_page' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_POST['page_id']) && !empty($_POST['page_name']) && !empty($_POST['page_content']) && !empty($_POST['page_title'])) {
            $page_name    = $_POST['page_name'];
            $page_content = $_POST['page_content'];
            $page_title   = $_POST['page_title'];
            $page_type    = 0;
            if (!empty($_POST['page_type'])) {
                $page_type = 1;
            }
            if (!preg_match('/^[\w]+$/', $page_name)) {
                $data = array(
                    'status' => 400,
                    'message' => 'Invalid page name characters'
                );
                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
            $data_ = array(
                'page_name' => $page_name,
                'page_content' => $page_content,
                'page_title' => $page_title,
                'page_type' => $page_type
            );
            $add   = Wo_UpdateCustomPageData($_POST['page_id'], $data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_page' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_GET['id'])) {
            $delete = Wo_DeleteCustomPage($_GET['id']);
            if ($delete) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_field') {
        if (Wo_CheckSession($hash_id) === true && !empty($_POST['name']) && !empty($_POST['type']) && !empty($_POST['description'])) {
            $type              = Secure($_POST['type']);
            $name              = Secure($_POST['name']);
            $description       = Secure($_POST['description']);
            $registration_page = 0;
            if (!empty($_POST['registration_page'])) {
                $registration_page = 1;
            }
            $profile_page = 0;
            if (!empty($_POST['profile_page'])) {
                $profile_page = 1;
            }
            $length = 32;
            if (!empty($_POST['length'])) {
                if (is_numeric($_POST['length']) && $_POST['length'] < 1001) {
                    $length = Secure($_POST['length']);
                }
            }
            $placement_array = array(
                'profile',
                'general',
                'social',
                'none'
            );
            $placement       = 'profile';
            if (!empty($_POST['placement'])) {
                if (in_array($_POST['placement'], $placement_array)) {
                    $placement = Secure($_POST['placement']);
                }
            }
            $data_ = array(
                'name' => $name,
                'description' => $description,
                'length' => $length,
                'placement' => $placement,
                'registration_page' => $registration_page,
                'profile_page' => $profile_page,
                'active' => 1
            );
            if (!empty($_POST['options'])) {
                $options              = @explode("\n", $_POST['options']);
                $type                 = Secure(implode(',', $options));
                $data_['select_type'] = 'yes';
            }
            $data_['type'] = $type;
            $add           = RegisterNewField($data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'edit_field' && Wo_CheckSession($hash_id) === true ) {
        if (!empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['id'])) {
            $name              = Secure($_POST['name']);
            $description       = Secure($_POST['description']);
            $registration_page = 0;
            if (!empty($_POST['registration_page'])) {
                $registration_page = 1;
            }
            $profile_page = 0;
            if (!empty($_POST['profile_page'])) {
                $profile_page = 1;
            }
            $active = 0;
            if (!empty($_POST['active'])) {
                $active = 1;
            }
            $length = 32;
            if (!empty($_POST['length'])) {
                if (is_numeric($_POST['length'])) {
                    $length = Secure($_POST['length']);
                }
            }
            $placement_array = array(
                'profile',
                'general',
                'social',
                'none'
            );
            $placement       = 'profile';
            if (!empty($_POST['placement'])) {
                if (in_array($_POST['placement'], $placement_array)) {
                    $placement = Secure($_POST['placement']);
                }
            }
            $data_ = array(
                'name' => $name,
                'description' => $description,
                'length' => $length,
                'placement' => $placement,
                'registration_page' => $registration_page,
                'profile_page' => $profile_page,
                'active' => $active
            );
            if (!empty($_POST['options'])) {
                $options              = @explode("\n", $_POST['options']);
                $data_['type']        = implode($options, ',');
                $data_['select_type'] = 'yes';
            }
            $add = UpdateField($_POST['id'], $data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_field') {
        if (Wo_CheckSession($hash_id) === true && !empty($_GET['id'])) {
            $delete = DeleteField($_GET['id']);
            if ($delete) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'free_gender_enable') {
        header("Content-type: application/json");
        if (!empty($_GET['key'])) {
            $db->where('id',Secure($_GET['key']))->update('langs', array('options' => 1));
            $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'free_gender_disable') {
        header("Content-type: application/json");
        if (!empty($_GET['key'])) {
            $db->where('id',Secure($_GET['key']))->update('langs', array('options' => NULL));
            $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'edit_new_success_story' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_POST['quote']) && !empty($_POST['content']) && !empty($_POST['id'])) {

            $id             = Secure($_POST['id']);
            $quote          = Secure($_POST['quote']);
            $story          = Secure(base64_decode($_POST['content']));

            $data_ = array(
                'quote' => $quote,
                'description' => $story
            );
            $add   = $db->where('id',$id)->update('success_stories', $data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_success_stories' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_GET['id'])) {
            $delete = Wo_Deletesuccess_stories($_GET['id']);
            if ($delete) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'approve_success_stories' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_GET['id'])) {
            $delete = Wo_Approvesuccess_stories($_GET['id']);
            if ($delete) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'disapprove_success_stories' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_GET['id'])) {
            $delete = Wo_DisApprovesuccess_stories($_GET['id']);
            if ($delete) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_category') {
        if (Wo_CheckSession($hash_id) === true) {
            $insert_data = array();
            $insert_data['ref'] = 'blog_categories';
            $add = false;
            foreach (Wo_LangsNamesFromDB() as $wo['key_']) {
                if (!empty($_POST[$wo['key_']])) {
                    $insert_data[$wo['key_']] = Secure($_POST[$wo['key_']]);
                    $add = true;
                }
            }
            if ($add == true) {
                $id = $db->insert('langs', $insert_data);
                $db->where('id', $id)->update('langs', array('lang_key' => $id));
                $data['status'] = 200;
            } else {
                $data['status'] = 400;
                $data['message'] = 'please check details';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_category') {
        header("Content-type: application/json");
        if (!empty($_GET['key']) && in_array($_GET['key'], array_keys(Dataset::blog_categories()))) {
            $db->where('lang_key',Secure($_GET['key']))->delete('langs');
            $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_blog_article' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_POST['category']) && !empty($_POST['title']) && !empty($_POST['description'])) {
            $category           = Secure($_POST['category']);
            $title              = Secure($_POST['title']);
            $description        = Secure($_POST['description']);
            $tags               = Secure($_POST['tags']);
            $content            = Secure(base64_decode($_POST['content']));

            $media_file = 'upload/photos/d-blog.jpg';
            if (isset($_FILES['thumbnail'])) {
                if (!empty($_FILES['thumbnail']["tmp_name"])) {
                    $filename = "";
                    $fileInfo = array(
                        'file' => $_FILES["thumbnail"]["tmp_name"],
                        'name' => $_FILES['thumbnail']['name'],
                        'size' => $_FILES["thumbnail"]["size"],
                        'type' => $_FILES["thumbnail"]["type"],
                        'types' => 'jpg,png,gif,jpeg'
                    );
                    $media = ShareFile($fileInfo, 0, false, 'blogs');
                    if (!empty($media)) {
                        $filename = $media['filename'];
                    }
                    $media_file = Secure($filename);
                }
            }
            $data_ = array(
                'title'         => $title,
                'content'       => $content,
                'description'   => $description,
                'category'      => $category,
                'tags'          => $tags,
                'thumbnail'     => $media_file,
                'created_at'    => time()
            );
            $add   = Wo_RegisterNewBlogPost($data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'edit_blog_article' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_POST['id']) && !empty($_POST['category']) && !empty($_POST['title']) && !empty($_POST['description'])) {

            $id                 = Secure($_POST['id']);
            $category           = Secure($_POST['category']);
            $title              = Secure($_POST['title']);
            $description        = Secure($_POST['description']);
            $tags               = Secure($_POST['tags']);
            $content            = base64_decode($_POST['content']);

            $article            = Wo_GetArticle($id);
            $remove_prev_img    = false;
            $old_thumb          = $article['thumbnail'];
            if (isset($_FILES['thumbnail'])) {
                if (!empty($_FILES['thumbnail']["tmp_name"])) {
                    $filename = "";
                    $fileInfo = array(
                        'file' => $_FILES["thumbnail"]["tmp_name"],
                        'name' => $_FILES['thumbnail']['name'],
                        'size' => $_FILES["thumbnail"]["size"],
                        'type' => $_FILES["thumbnail"]["type"],
                        'types' => 'jpg,png,gif,jpeg'
                    );
                    $media = ShareFile($fileInfo, 0, false, 'blogs');
                    if (!empty($media)) {
                        $filename = $media['filename'];
                        $remove_prev_img    = true;
                    }
                    $media_file = Secure($filename);
                }
            }else{
                $media_file = $article['thumbnail'];
            }

            $data_ = array(
                'title'         => $title,
                'content'       => $content,
                'description'   => $description,
                'category'      => $category,
                'tags'          => $tags,
                'thumbnail'     => $media_file
            );
            $add   = $db->where('id',$id)->update('blog', $data_);
            if ($add) {
                if( $old_thumb !== '' && $remove_prev_img == true ) {
                    DeleteFromToS3($old_thumb);
                }
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_blog_article' && Wo_CheckSession($hash_id) === true) {
        if (!empty($_GET['id'])) {
            $delete = Wo_DeleteArticle($_GET['id'], $_GET['thumbnail']);
            if ($delete) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'approve_user') {
        if (!empty($_GET['user_id'])) {
            $_id = Secure($_GET['user_id']);
            $receipt = $db->where('id',$_id)->getOne('users',array('*'));

            if($receipt){
                $updated = $db->where('id',$_id)->update('users',array('verified'=>"1",'status'=>"1",'start_up'=>"3",'approved_at'=>time()));
                if ($updated === true) {
                    $data = array(
                        'status' => 200
                    );
                }
            }
            $data = array(
                'status' => 200,
                'data' => $receipt
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_lang_status') {
        Wo_SaveConfig($_POST['name'], $_POST['value']);

        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'decline_payment') {
        if (!empty($_GET['id']) && Wo_CheckSession($hash_id)) {
            $get_payment_info = Wo_GetPaymentHistory($_GET['id']);
            if (!empty($get_payment_info)) {
                $id     = $get_payment_info['id'];
                $update = mysqli_query($conn, "UPDATE `affiliates_requests` SET status = '2' WHERE id = {$id}");
                if ($update) {
                    $message_body = Emails::parse('emails/payment-declined', array(
                        'name' => ($user[ 'first_name' ] !== '' ? $get_payment_info['user']->first_name : $get_payment_info['user']->username),
                        'amount' => $get_payment_info['amount'],
                        'site_name' => $wo['config']['siteName']
                    ));
                    $send_message_data = array(
                        'from_email' => $wo['config']['siteEmail'],
                        'from_name' => $wo['config']['siteName'],
                        'to_email' => $get_payment_info['user']->email,
                        'subject' => 'Payment Declined | ' . $wo['config']['siteName'],
                        'charSet' => 'utf-8',
                        'message_body' => $message_body,
                        'is_html' => true
                    );
                    $send_message      = SendEmail($send_message_data['to_email'], $send_message_data['subject'], $send_message_data['message_body'], false);
                    $data['status'] = 200;

                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'mark_as_paid') {
        if (!empty($_GET['id']) && Wo_CheckSession($hash_id)) {
            $get_payment_info = Wo_GetPaymentHistory($_GET['id']);
            if (!empty($get_payment_info)) {
                $id     = $get_payment_info['id'];
                $update = mysqli_query($conn, "UPDATE `affiliates_requests` SET status = '1' WHERE id = {$id}");
                if ($update) {
                    $update_balance = Wo_UpdateBalance($get_payment_info['user_id'], $get_payment_info['amount'], '-');
                    $message_body = Emails::parse('emails/payment-sent', array(
                        'name' => ($user[ 'first_name' ] !== '' ? $get_payment_info['user'][ 'first_name' ] : $get_payment_info['user'][ 'username' ]),
                        'amount' => $get_payment_info['amount'],
                        'site_name' => $config['siteName']
                    ));
                    $send_message_data = array(
                        'from_email' => $wo['config']['siteEmail'],
                        'from_name' => $wo['config']['siteName'],
                        'to_email' => $get_payment_info['user']['email'],
                        'to_name' => $get_payment_info['user']['name'],
                        'subject' => 'Payment Declined | ' . $wo['config']['siteName'],
                        'charSet' => 'utf-8',
                        'message_body' => $message_body,
                        'is_html' => true
                    );
                    $send_message      = SendEmail($send_message_data['to_email'], $send_message_data['subject'], $send_message_data['message_body'], false);
                    if ($send_message) {
                        $data['status'] = 200;
                    }
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete-faqs') {
        $request        = (!empty($_POST['id']) && is_numeric($_POST['id']));
        $data['status'] = 400;
        if ($request === true) {
            $faq_id = Secure($_POST['id']);
            $db->where('id',$faq_id)->delete('faqs');
            $data['status'] = 200;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_faqs') {
        $data['status'] = 400;
        $faqs_title           = (!empty($_POST['faqs_title'])) ? Secure($_POST['faqs_title']) : "";
        $text           = (!empty($_POST['text'])) ? Secure($_POST['text']) : "";
        if (empty($text) || empty($faqs_title)) {
            $data['status'] = 400;
        }
        else {
            $re_data        = array(
                'question'      => $faqs_title,
                'answer'      => $text,
                'time'      => time()
            );

            $insert_id          = $db->insert('faqs',$re_data);

            if (!empty($insert_id)) {
                $data['status'] = 200;
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'get_supported_coins') {
        $result = coinpayments_api_call(array('key' => $wo['config']['coinpayments_public_key'],
                                              'version' => '1',
                                              'format' => 'json',
                                              'cmd' => 'rates',
                                              'accepted' => '1'));
        $coins = array();
        if (!empty($result) && $result['status'] == 200) {
            foreach ($result['data'] as $key => $value) {
                if ($value['accepted'] == 1 && $value['is_fiat'] == 0) {
                    $coins[$key] = $key;
                }
            }
            Wo_SaveConfig('coinpayments_coins', json_encode($coins));
            header("Content-type: application/json");
            echo json_encode(array('status' => 200));
            exit();
        }
        else{
            header("Content-type: application/json");
            echo json_encode(array('status' => 400,
                                   'message' => $result['message']));
            exit();
        }
    }
    if ($s == 'add_new_curreny') {
        if (!empty($_POST['currency']) && !empty($_POST['currency_symbol'])) {
            $wo['config']['currency_array'][]                                     = Secure($_POST['currency']);
            $wo['config']['currency_symbol_array'][Secure($_POST['currency'])] = Secure($_POST['currency_symbol']);
            $saveSetting                                                          = Wo_SaveConfig('currency_array', json_encode($wo['config']['currency_array']));
            $saveSetting                                                          = Wo_SaveConfig('currency_symbol_array', json_encode($wo['config']['currency_symbol_array']));
            $request                                                              = fetchDataFromURL("https://api.exchangerate.host/latest?base=" . $wo['config']['currency'] . "&symbols=" . implode(",", array_values($wo['config']['currency_array'])));
            $exchange                                                             = json_decode($request, true);
            if (!empty($exchange) && $exchange['success'] == true && !empty($exchange['rates'])) {
                Wo_SaveConfig('exchange', json_encode($exchange['rates']));
                Wo_SaveConfig('exchange_update', (time() + (60 * 60 * 12)));
            }
        }
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'edit_curreny') {
        if (!empty($_POST['currency']) && !empty($_POST['currency_symbol']) && in_array($_POST['currency_id'], array_keys($wo['config']['currency_array']))) {
            $wo['config']['currency_array'][$_POST['currency_id']]                = Secure($_POST['currency']);
            $wo['config']['currency_symbol_array'][Secure($_POST['currency'])] = Secure($_POST['currency_symbol']);
            $saveSetting                                                          = Wo_SaveConfig('currency_array', json_encode($wo['config']['currency_array']));
            $saveSetting                                                          = Wo_SaveConfig('currency_symbol_array', json_encode($wo['config']['currency_symbol_array']));
            $request                                                              = fetchDataFromURL("https://api.exchangerate.host/latest?base=" . $wo['config']['currency'] . "&symbols=" . implode(",", array_values($wo['config']['currency_array'])));
            $exchange                                                             = json_decode($request, true);
            if (!empty($exchange) && $exchange['success'] == true && !empty($exchange['rates'])) {
                Wo_SaveConfig('exchange', json_encode($exchange['rates']));
                Wo_SaveConfig('exchange_update', (time() + (60 * 60 * 12)));
            }
        }
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'select_currency') {
        if (!empty($_POST['currency']) && in_array($_POST['currency'], $wo['config']['currency_array'])) {
            $currency    = Secure($_POST['currency']);
            $saveSetting = Wo_SaveConfig('currency', $currency);
            if (!empty($wo['config']['currency_symbol_array']) && !empty($wo['config']['currency_symbol_array'][$currency])) {
                $saveSetting = Wo_SaveConfig('currency_symbol', $wo['config']['currency_symbol_array'][$currency]);
            }
            $request                                                              = fetchDataFromURL("https://api.exchangerate.host/latest?base=" . $currency . "&symbols=" . implode(",", array_values($wo['config']['currency_array'])));
            $exchange                                                             = json_decode($request, true);
            if (!empty($exchange) && $exchange['success'] == true && !empty($exchange['rates'])) {
                Wo_SaveConfig('exchange', json_encode($exchange['rates']));
                Wo_SaveConfig('exchange_update', (time() + (60 * 60 * 12)));
            }
            // $saveSetting = Wo_SaveConfig('ads_currency', $currency);
            // if (in_array($_POST['currency'], $wo['stripe_currency'])) {
            //     $saveSetting = Wo_SaveConfig('stripe_currency', $currency);
            // }
            // if (in_array($_POST['currency'], $wo['paypal_currency'])) {
            //     $saveSetting = Wo_SaveConfig('paypal_currency', $currency);
            // }
            // if (in_array($_POST['currency'], $wo['config']['checkout_currency'])) {
            //     $saveSetting = Wo_SaveConfig('checkout_currency', $currency);
            // }
        }
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'remove__curreny') {
        if (!empty($_POST['currency'])) {
            if (in_array($_POST['currency'], $wo['config']['currency_array'])) {
                foreach ($wo['config']['currency_array'] as $key => $currency) {
                    if ($currency == $_POST['currency']) {
                        if (in_array($currency, array_keys($wo['config']['currency_symbol_array']))) {
                            unset($wo['config']['currency_symbol_array'][$currency]);
                        }
                        unset($wo['config']['currency_array'][$key]);
                    }
                }
                if ($wo['config']['currency'] == $_POST['currency']) {
                    if (!empty($wo['config']['currency_array'])) {
                        $saveSetting = Wo_SaveConfig('currency', reset($wo['config']['currency_array']));
                        // $saveSetting = Wo_SaveConfig('ads_currency', reset($wo['config']['currency_array']));
                    }
                }
                $saveSetting = Wo_SaveConfig('currency_array', json_encode($wo['config']['currency_array']));
                $saveSetting = Wo_SaveConfig('currency_symbol_array', json_encode($wo['config']['currency_symbol_array']));
            }
        }
        $data = array(
            'status' => 200
        );
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
mysqli_close($conn);
unset($wo);
