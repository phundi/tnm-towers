<?php
class Live {
    public function __construct() {
        if (empty(route(4))) {
            return json(array(
                'message' => __('Function not found'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '1',
                    'error_text' => __('Function not found')
                )
            ), 400);
        }
        if (route(4) == 'go_live') {
            json($this->go_live());
        }
        elseif (route(4) == 'create_thumb') {
            json($this->create_thumb());
        }
        elseif (route(4) == 'delete') {
            json($this->delete());
        }
        elseif (route(4) == 'check_comments') {
            json($this->check_comments());
        }
        elseif (route(4) == 'new_comment') {
            json($this->new_comment());
        }
        elseif (route(4) == 'get') {
            json($this->get());
        }
        else{
            return json(array(
                'message' => __('Function not found'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '10',
                    'error_text' => __('Function not found')
                )
            ), 400);
        }
    }
    public function go_live()
    {
        global $db, $config;
        if (empty($_POST['stream_name'])) {
            return json(array(
                'message' => 'stream_name can not be empty',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '2',
                    'error_text' => 'stream_name can not be empty'
                )
            ), 400);
        }
        else{
            $token = null;
            if (!empty($_POST['token']) && !is_null($_POST['token'])) {
                $token = Secure($_POST['token']);
            }
            $post_id = $db->insert('posts',array('user_id' => Auth()->id,
                                                 'postType' => 'live',
                                                 'agora_token' => $token,
                                                 'stream_name' => Secure($_POST['stream_name']),
                                                 'time' => time()));
            $response = array(
                        'code' => 200,
                        'post_id' => $post_id,
                        'message' => 'you are live',
                    );
            return json($response, 200);
        }
    }
    public function create_thumb()
    {
        global $config, $db, $_UPLOAD, $_DS;
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0 && !empty($_FILES['thumb'])) {
            $is_post = $db->where('id',Secure($_POST['post_id']))->where('user_id',Auth()->id)->getValue('posts','COUNT(*)');
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
                    $db->where('id',Secure($_POST['post_id']))->where('user_id',Auth()->id)->update('posts',array('image' => $org_file));
                    $response = array(
                        'code' => 200,
                        'message' => 'image uploaded',
                    );
                    return json($response, 200);
                }
                else{
                    return json(array(
                        'message' => 'image not uploaded',
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '5',
                            'error_text' => 'image not uploaded'
                        )
                    ), 400);
                }
            }
            else{
                return json(array(
                    'message' => 'post not found',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '4',
                        'error_text' => 'post not found'
                    )
                ), 400);
            }
        }
        else{
            return json(array(
                'message' => 'please check your details',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '3',
                    'error_text' => 'please check your details'
                )
            ), 400);
        }
    }
    public function delete()
    {
        global $config, $db, $_UPLOAD, $_DS;
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
            $db->where('id',Secure($_POST['post_id']))->where('user_id',Auth()->id)->update('posts',array('live_ended' => 1));
            $post = $db->where('id',Secure($_POST['post_id']))->where('user_id',Auth()->id)->objectbuilder()->getOne('posts');
            if (!empty($post)) {
                @unlink($post->image);
                DeleteFromLiveToS3($post->image);
                $db->where('id',Secure($_POST['post_id']))->where('user_id',Auth()->id)->delete('posts');
                $db->where('post_id',Secure($_POST['post_id']))->delete('comments');
                $db->where('post_id',Secure($_POST['post_id']))->delete('live_sub_users');
                $response = array(
                    'code' => 200,
                    'message' => 'video deleted',
                );
                return json($response, 200);
            }
            else{
                return json(array(
                    'message' => 'post not found',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '4',
                        'error_text' => 'post not found'
                    )
                ), 400);
            }
        }
        else{
            return json(array(
                'message' => 'please check your details',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '3',
                    'error_text' => 'please check your details'
                )
            ), 400);
        }
    }
    public function check_comments()
    {
        global $config, $db, $_UPLOAD, $_DS,$_BASEPATH,$site_url,$conn;
        $theme_path = $_BASEPATH . 'themes' . $_DS . $config->theme . $_DS;
        $template   = $theme_path . 'partails' . $_DS . 'live' . $_DS . 'live_comment.php';
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0) {
            $post_id = Secure($_POST['post_id']);
            $post_data = $db->where('id',$post_id)->objectbuilder()->getOne('posts');
            if (!empty($post_data)) {
                if ($post_data->live_ended == 0) {
                    $left_users_array = array();
                    $joined_users_array = array();
                    $comments_array = array();
                    if ($_POST['page'] == 'show') {
                        $current_id = Auth()->id;
                        $query = mysqli_query($conn, "SELECT * FROM `comments` WHERE `post_id` = {$post_id} AND `user_id` = {$current_id} ORDER BY `id` DESC");
                        if (mysqli_num_rows($query) == 1) {
                            $fetched_data         = mysqli_fetch_assoc($query);
                            $db->where('id',$fetched_data['id'],'>');
                        }
                        // $user_comment = $db->where('post_id',$post_id)->where('user_id',Auth()->id)->getOne('comments');
                        // if (!empty($user_comment)) {
                        //     $db->where('id',$user_comment['id'],'>');
                        // }
                    }
                    if (!empty($_POST['ids'])) {
                        $ids = array();
                        foreach ($_POST['ids'] as $key => $one_id) {
                            $ids[] = Secure($one_id);
                        }
                        $db->where('id',$ids,'NOT IN')->where('id',end($ids),'>');
                    }
                    if ($_POST['page'] == 'show') {
                        $db->where('user_id',Auth()->id,'!=');
                    }
                    $comments = $db->where('post_id',$post_id)->where('text','','!=')->get('comments');
                    $html = '';
                    $count = 0;
                    foreach ($comments as $key => $value) {
                        if (!empty($value['text'])) {
                            $comment = GetPostComment($value['id']);
                            if (!empty($comment)) {
                                $comments_array[] = $comment;
                            }
                        }
                    }
                    


                    
                    $word = __('offline');
                    if (!empty($post_data->live_time) && $post_data->live_time >= (time() - 10)) {
                        
                        $word = __('live');
                        $count = $db->where('post_id',$post_id)->where('time',time()-6,'>=')->getValue('live_sub_users','COUNT(*)');

                        if (Auth()->id == $post_data->user_id) {
                            $joined_users = $db->where('post_id',$post_id)->where('time',time()-6,'>=')->where('is_watching',0)->get('live_sub_users');
                            $joined_ids = array();
                            if (!empty($joined_users)) {
                                foreach ($joined_users as $key => $value) {
                                    $joined_ids[] = $value['user_id'];
                                    $user_data = userData($value['user_id']);
                                    if (!empty($user_data)) {
                                        $value['publisher'] = $user_data;
                                        $joined_users_array[] = $value;
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
                                    $user_data = userData($value['user_id']);
                                    if (!empty($user_data)) {
                                        $value['publisher'] = $user_data;
                                        $left_users_array[] = $value;
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
                    
                    // Wo_RunInBackground(array(
                    //     'status' => 200,
                    //     'html' => $html,
                    //     'count' => $count,
                    //     'word' => $word,
                    //     'still_live' => $still_live
                    // ));
                    
                    if (Auth()->id == $post_data->user_id) {
                        if ($_POST['page'] == 'live') {
                            $time = time();
                            $db->where('id',$post_id)->update('posts',array('live_time' => $time));
                        }
                    }
                    else{
                        if (!empty($post_data->live_time) && $post_data->live_time >= (time() - 10) && $_POST['page'] == 'show') {
                            $is_watching = $db->where('user_id',Auth()->id)->where('post_id',$post_id)->getValue('live_sub_users','COUNT(*)');
                            if ($is_watching > 0) {
                                $db->where('user_id',Auth()->id)->where('post_id',$post_id)->update('live_sub_users',array('time' => time()));
                            }
                            else{
                                $db->insert('live_sub_users',array('user_id' => Auth()->id,
                                                             'post_id' => $post_id,
                                                             'time' => time(),
                                                             'is_watching' => 0));
                            }
                        }
                    }
                    $response = array(
                        'code' => 200,
                        'left_users_array' => $left_users_array,
                        'joined_users_array' => $joined_users_array,
                        'comments_array' => $comments_array,
                        'count' => $count,
                        'word' => $word,
                        'still_live' => $still_live
                    );
                    return json($response, 200);
                }
                else{
                    return json(array(
                        'message' => 'video ended',
                        'code' => 400,
                        'errors'         => array(
                            'error_id'   => '5',
                            'error_text' => 'video ended'
                        )
                    ), 400);
                }
                
            }
            else{
                return json(array(
                    'message' => 'video not found',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '4',
                        'error_text' => 'video not found'
                    )
                ), 400);
            }
        }
        else{
            return json(array(
                'message' => 'please check your details',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '3',
                    'error_text' => 'please check your details'
                )
            ), 400);
        }
    }
    public function new_comment()
    {
        global $config, $db, $_UPLOAD, $_DS,$_BASEPATH,$site_url;
        if (!empty($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] > 0 && !empty($_POST['text'])) {
            $post_id = Secure($_POST['post_id']);
            $post_data = $db->where('id',$post_id)->objectbuilder()->getOne('posts');
            if (!empty($post_data)) {
                $id = $db->insert('comments',array('user_id' => Auth()->id,
                                             'post_id' => $post_id,
                                             'text' => Secure($_POST['text']),
                                             'time' => time()));
                $comment = GetPostComment($id);
                $response = array(
                    'code' => 200,
                    'data' => $comment,
                );
                return json($response, 200);
            }
            else{
                return json(array(
                    'message' => 'video not found',
                    'code' => 400,
                    'errors'         => array(
                        'error_id'   => '4',
                        'error_text' => 'video not found'
                    )
                ), 400);
            }
        }
        else{
            return json(array(
                'message' => 'please check your details',
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '3',
                    'error_text' => 'please check your details'
                )
            ), 400);
        }
    }
    public function get()
    {
        global $db, $_BASEPATH, $_DS,$_excludes,$config;
        $posts_array = array();
        $error     = '';
        $page      = 1;
        $db->pageLimit   = (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] <= 50) ? Secure($_POST['limit']) : 7;
        if (isset($_POST) && !empty($_POST) && !empty($_POST[ 'page' ]) && is_numeric($_POST[ 'page' ]) && $_POST[ 'page' ] > 0) {
            $page = (int) Secure($_POST[ 'page' ]);
        }
        $posts = $db->where('live_ended',0)->where('live_time',(time() - (60 * 5)),'>')->groupBy('user_id')->orderBy('id','DESC')->objectbuilder()->paginate('posts', $page);
        if (!empty($posts)) {
            foreach ($posts as $key => $value) {
                $posts[$key]->user_data = userData($value->user_id);
            }
        }
        $response = array(
            'code' => 200,
            'data' => $posts,
        );
        return json($response, 200);
    }
}