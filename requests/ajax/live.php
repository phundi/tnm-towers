<?php
Class Live extends Aj {
	public function create()
	{
		global $config, $db;
		if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
		if (empty($_POST['stream_name'])) {
    		return array(
                'status' => 401,
                'message' => __('please_check_details')
            );
    	}
    	else{
    		$token = null;
            if (!empty($_POST['token']) && !is_null($_POST['token'])) {
                $token = Secure($_POST['token']);
            }
            $post_id = $db->insert('posts',array('user_id' => self::ActiveUser()->id,
                                                 'postType' => 'live',
                                                 'agora_token' => $token,
                                                 'stream_name' => Secure($_POST['stream_name']),
                                                 'time' => time()));
            // if ($config->agora_live_video == 1 && !empty($config->agora_app_id) && !empty($config->agora_customer_id) && !empty($config->agora_customer_certificate) && $config->live_video_save == 1) {

            //     if ($config->amazone_s3_2 == 1 && !empty($config->bucket_name_2) && !empty($config->amazone_s3_key_2) && !empty($config->amazone_s3_s_key_2) && !empty($config->region_2)) {

            //         $region_array = array('us-east-1' => 0,'us-east-2' => 1,'us-west-1' => 2,'us-west-2' => 3,'eu-west-1' => 4,'eu-west-2' => 5,'eu-west-3' => 6,'eu-central-1' => 7,'ap-southeast-1' => 8,'ap-southeast-2' => 9,'ap-northeast-1' => 10,'ap-northeast-2' => 11,'sa-east-1' => 12,'ca-central-1' => 13,'ap-south-1' => 14,'cn-north-1' => 15,'us-gov-west-1' => 17);

            //         if (in_array(strtolower($config->region_2),array_keys($region_array) )) {

            //             StartCloudRecording(1,$region_array[strtolower($config->region_2)],$config->bucket_name_2,$config->amazone_s3_key_2,$config->amazone_s3_s_key_2,$_POST['stream_name'],12,$post_id,$token);
            //         }
                    
            //     }
            // }
            return array(
                    'status' => 200,
                    'post_id' => $post_id
                );
    	}
	}
	public function create_thumb()
	{
		global $config, $db, $_UPLOAD, $_DS;
		if (self::ActiveUser() == NULL) {
            return array(
                'status' => 403,
                'message' => __('Forbidden')
            );
        }
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0 && !empty($_FILES['thumb'])) {
            $is_post = $db->where('id',Secure($_POST['post_id']))->where('user_id',self::ActiveUser()->id)->getValue('posts','COUNT(*)');
            if ($is_post > 0) {
                $fileInfo = array(
                    'tmp_name' => $_FILES["thumb"]["tmp_name"],
                    'name' => $_FILES['thumb']['name'],
                    'size' => $_FILES["thumb"]["size"],
                    'type' => $_FILES["thumb"]["type"]
                );
                if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y'))) {
		            mkdir($_UPLOAD . 'photos' . $_DS . date('Y'), 0777, true);
		        }
		        if (!file_exists($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'))) {
		            mkdir($_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m'), 0777, true);
		        }
		        $dir = $_UPLOAD . 'photos' . $_DS . date('Y') . $_DS . date('m');
		        $ext      = pathinfo($fileInfo[ 'name' ], PATHINFO_EXTENSION);
	            $key      = GenerateKey();
	            $filename = $dir . $_DS . $key . '.' . $ext;
	            if (move_uploaded_file($fileInfo[ 'tmp_name' ], $filename)) {
	            	$org_file  = 'upload' . $_DS . 'photos' . $_DS . date('Y') . $_DS . date('m') . $_DS . $key . '.' . $ext;
	            	UploadToS3($org_file);
	            	$db->where('id',Secure($_POST['post_id']))->where('user_id',self::ActiveUser()->id)->update('posts',array('image' => $org_file));
	            	return array(
	                    'status' => 200
	                );
	            }
            }
        }
        else{
        	return array(
                'status' => 401,
                'message' => __('please_check_details')
            );
        }
	}
    public function delete()
    {
        global $config, $db, $_UPLOAD, $_DS;
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
            $db->where('id',Secure($_POST['post_id']))->where('user_id',self::ActiveUser()->id)->update('posts',array('live_ended' => 1));
            $post = $db->where('id',Secure($_POST['post_id']))->where('user_id',self::ActiveUser()->id)->objectbuilder()->getOne('posts');
            // if ($config->live_video_save == 0) {
                if (!empty($post)) {
                    @unlink($post->image);
                    DeleteFromLiveToS3($post->image);
                    $db->where('id',Secure($_POST['post_id']))->where('user_id',self::ActiveUser()->id)->delete('posts');
                    $db->where('post_id',Secure($_POST['post_id']))->delete('comments');
                    $db->where('post_id',Secure($_POST['post_id']))->delete('live_sub_users');
                }
            // }
            // else{
            //     if ($config->agora_live_video == 1 && !empty($config->agora_app_id) && !empty($config->agora_customer_id) && !empty($config->agora_customer_certificate) && $config->live_video_save == 1) {
                    
            //         if (!empty($post)) {
            //             StopCloudRecording(array('resourceId' => $post->agora_resource_id,
            //                                      'sid' => $post->agora_sid,
            //                                      'cname' => $post->stream_name,
            //                                      'post_id' => $post->id,
            //                                      'token' => $post->agora_token,
            //                                      'uid' => 12));
            //         }
            //     }
            //     if ($config->agora_live_video == 1 && $config->amazone_s3_2 != 1) {
            //         try {
            //             if (!empty($post)) {
            //                 @unlink($post->image);
            //                 DeleteFromLiveToS3($post->image);
            //                 $db->where('id',Secure($_POST['post_id']))->where('user_id',self::ActiveUser()->id)->delete('posts');
            //                 $db->where('post_id',Secure($_POST['post_id']))->delete('comments');
            //             }
            //         } catch (Exception $e) {
                        
            //         }
            //     }
            // }
        }
        return array('status' => 200);
    }
    public function remove_video(){
        global $config, $db, $_UPLOAD, $_DS;
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
            $post = $db->where('id',Secure($_POST['post_id']))->objectbuilder()->getOne('posts');
            if (!empty($post) && ($post->user_id == auth()->id || auth()->admin == 1)) {
                @unlink($post->image);
                DeleteFromLiveToS3($post->image);
                $db->where('post_id',Secure($_POST['post_id']))->delete('comments');
                $db->where('id',Secure($_POST['post_id']))->delete('posts');
                $db->where('post_id',Secure($_POST['post_id']))->delete('live_sub_users');
            }
        }
        return array('status' => 200);
    }
    public function remove_video_comment()
    {
        global $config, $db, $_UPLOAD, $_DS;
        if (!empty($_POST['comment_id']) && is_numeric($_POST['comment_id']) && $_POST['comment_id'] > 0) {
            $comment = $db->where('id',Secure($_POST['comment_id']))->objectbuilder()->getOne('comments');
            if (!empty($comment)) {
                $post = $db->where('id',$comment->post_id)->objectbuilder()->getOne('posts');
                if (!empty($post) && ($comment->user_id == auth()->id || $post->user_id == auth()->id || auth()->admin == 1)) {
                    $db->where('id',$comment->id)->delete('comments');
                }
            }
        }
        return array('status' => 200);
    }
    public function check_comments()
    {
        global $config, $db, $_UPLOAD, $_DS,$_BASEPATH,$site_url;
        $theme_path = $_BASEPATH . 'themes' . $_DS . self::Config()->theme . $_DS;
        $template   = $theme_path . 'partails' . $_DS . 'live' . $_DS . 'live_comment.php';
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
            $post_id = Secure($_POST['post_id']);
            $post_data = $db->where('id',$post_id)->objectbuilder()->getOne('posts');
            if (!empty($post_data)) {
                if ($post_data->live_ended == 0) {
                    if ($_POST['page'] == 'show') {
                        $user_comment = $db->where('post_id',$post_id)->where('user_id',self::ActiveUser()->id)->getOne('comments');
                        if (!empty($user_comment)) {
                            $db->where('id',$user_comment['id'],'>');
                        }
                    }
                    if (!empty($_POST['ids'])) {
                        $ids = array();
                        foreach ($_POST['ids'] as $key => $one_id) {
                            $ids[] = Secure($one_id);
                        }
                        $db->where('id',$ids,'NOT IN')->where('id',end($ids),'>');
                    }
                    if ($_POST['page'] == 'show') {
                        $db->where('user_id',self::ActiveUser()->id,'!=');
                    }
                    $comments = $db->where('post_id',$post_id)->where('text','','!=')->get('comments');
                    $html = '';
                    $count = 0;
                    foreach ($comments as $key => $value) {
                        if (!empty($value['text'])) {
                            $comment = GetPostComment($value['id']);
                            if (!empty($comment)) {
                                ob_start();
                                include $template;
                                $html .= ob_get_contents();
                                ob_end_clean();
                                $count = $count + 1;
                                if ($count == 4) {
                                  break;
                                }
                            }
                        }
                    }


                    
                    $word = __('offline');
                    if (!empty($post_data->live_time) && $post_data->live_time >= (time() - 10)) {
                        
                        $word = __('live');
                        $count = $db->where('post_id',$post_id)->where('time',time()-6,'>=')->getValue('live_sub_users','COUNT(*)');

                        if (self::ActiveUser()->id == $post_data->user_id) {
                            $joined_users = $db->where('post_id',$post_id)->where('time',time()-6,'>=')->where('is_watching',0)->get('live_sub_users');
                            $joined_ids = array();
                            if (!empty($joined_users)) {
                                foreach ($joined_users as $key => $value) {
                                    $joined_ids[] = $value['user_id'];
                                    $comment = array('id' => '',
                                                     'text' => 'joined live video');
                                    $user_data = userData($value['user_id']);
                                    if (!empty($user_data)) {
                                        $comment['publisher'] = $user_data;
                                        ob_start();
                                        include $template;
                                        $html .= ob_get_contents();
                                        ob_end_clean();
                                    }
                                }
                                if (!empty($joined_ids)) {
                                    $db->where('post_id',$post_id)->where('user_id',$joined_ids,'IN')->update('live_sub_users',array('is_watching' => 1));
                                }
                            }

                            $left_users = $db->where('post_id',$post_id)->where('time',time()-10,'<')->where('is_watching',1)->get('live_sub_users');
                            $left_ids = array();
                            if (!empty($left_users)) {
                                foreach ($left_users as $key => $value) {
                                    $left_ids[] = $value['user_id'];
                                    $comment = array('id' => '',
                                                           'text' => 'left live video');
                                    $user_data = userData($value['user_id']);
                                    if (!empty($user_data)) {
                                        $comment['publisher'] = $user_data;
                                        ob_start();
                                        include $template;
                                        $html .= ob_get_contents();
                                        ob_end_clean();
                                    }
                                }
                                if (!empty($left_ids)) {
                                    $db->where('post_id',$post_id)->where('user_id',$left_ids,'IN')->delete('live_sub_users');
                                }
                            }
                        }
                    }
                    $still_live = 'offline';
                    if (!empty($post_data) && $post_data->live_time >= (time() - 10)){
                        $still_live = 'live';
                    }
                    $data = array(
                        'status' => 200,
                        'html' => $html,
                        'count' => $count,
                        'word' => $word,
                        'still_live' => $still_live
                    );
                    
                    // Wo_RunInBackground(array(
                    //     'status' => 200,
                    //     'html' => $html,
                    //     'count' => $count,
                    //     'word' => $word,
                    //     'still_live' => $still_live
                    // ));
                    
                    if (self::ActiveUser()->id == $post_data->user_id) {
                        if ($_POST['page'] == 'live') {
                            $time = time();
                            $db->where('id',$post_id)->update('posts',array('live_time' => $time));
                        }
                    }
                    else{
                        if (!empty($post_data->live_time) && $post_data->live_time >= (time() - 10) && $_POST['page'] == 'show') {
                            $is_watching = $db->where('user_id',self::ActiveUser()->id)->where('post_id',$post_id)->getValue('live_sub_users','COUNT(*)');
                            if ($is_watching > 0) {
                                $db->where('user_id',self::ActiveUser()->id)->where('post_id',$post_id)->update('live_sub_users',array('time' => time()));
                            }
                            else{
                                $db->insert('live_sub_users',array('user_id' => self::ActiveUser()->id,
                                                             'post_id' => $post_id,
                                                             'time' => time(),
                                                             'is_watching' => 0));
                            }
                        }
                    }
                    return $data;
                }
                else{
                    return array(
                        'status' => 400
                    );
                }
                
            }
            else{
                return array(
                    'status' => 400,
                    'removed' => 'yes'
                );
            }
        }
        else{
            return array(
                'status' => 400
            );
        }
    }
    public function new_comment()
    {
        global $config, $db, $_UPLOAD, $_DS,$_BASEPATH,$site_url;
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0 && !empty($_POST['text'])) {
            $post_id = Secure($_POST['post_id']);
            $post_data = $db->where('id',$post_id)->objectbuilder()->getOne('posts');
            if (!empty($post_data)) {
                $db->insert('comments',array('user_id' => self::ActiveUser()->id,
                                             'post_id' => $post_id,
                                             'text' => Secure($_POST['text']),
                                             'time' => time()));
                return array(
                        'status' => 200
                    );
            }
            else{
                return array(
                    'status' => 400
                );
            }
        }
        else{
            return array(
                'status' => 400
            );
        }
    }
}