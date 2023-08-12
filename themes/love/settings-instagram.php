<?php
if($config->instagram_importer != 1){
    header('Location: ' . $config->uri);
    exit();
}
$admin_mode = false;
if( $profile->admin == '1' || CheckPermission($profile->permission, "manage-users") ){
    $admin_mode = true;
}

$target_user = route(2);
if (empty($target_user)) {
    $target_user = $profile->username;
}
if($target_user !== ''){
    $_user = LoadEndPointResource('users');
    if( $_user ){
        if( $target_user !== '' ){
            $user = $_user->get_user_profile(Secure($target_user));
            if( !$user ){
                echo '<script>window.location = window.site_url;</script>';
                exit();
            }else{
                if( $user->admin == '1' ){
                    $admin_mode = true;
                }
            }
        }
    }
}else{
    $user = auth();
}
$alert = '';
$redirect = false;
if (!empty($_GET['code'])) {
    $code = Secure($_GET['code']);
    $result = GetInstagramAccessToken($code);
    if (empty($result)) {
        $alert = '<div class="alert alert-danger">'.__('Something went wrong, please try again later.').'</div>';
    }
    elseif (!empty($result) && $result['status'] == 400) {
        $alert = '<div class="alert alert-danger">'.$result['message'].'</div>';
    }
    elseif (!empty($result) && $result['status'] == 200) {
        $alert = '<div class="alert alert-success">'.$result['message'].'</div>';
    }
    $redirect = true;
}
?>
<?php //$user = auth();?>

<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<!-- Settings  -->
<div class="container">
    <div class="dt_settings row">
		<div class="col s12 m3"></div>
		<div class="col s12 m6 dt_usr_pmnt_cont">
			<div class="center dt_insta_import">
				<svg height="80" viewBox="0 0 152 152" width="80" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="linear-gradient" gradientUnits="userSpaceOnUse" x1="22.26" x2="129.74" y1="22.26" y2="129.74"><stop offset="0" stop-color="#fae100"/><stop offset=".15" stop-color="#fcb720"/><stop offset=".3" stop-color="#ff7950"/><stop offset=".5" stop-color="#ff1c74"/><stop offset="1" stop-color="#6c1cd1"/></linearGradient><g id="Layer_2" data-name="Layer 2"><g id="Circle"><g id="_03.Instagram" data-name="03.Instagram"><rect id="Background" fill="url(#linear-gradient)" height="152" rx="76" width="152"/><g fill="#fff"><path id="Shade" d="m133.2 26c-11.08 20.34-26.75 41.32-46.33 60.9s-40.56 35.22-60.87 46.3q-1.91-1.66-3.71-3.46a76 76 0 1 1 107.45-107.48q1.8 1.8 3.46 3.74z" opacity=".1"/><g id="Icon"><path d="m94 36h-36a22 22 0 0 0 -22 22v36a22 22 0 0 0 22 22h36a22 22 0 0 0 22-22v-36a22 22 0 0 0 -22-22zm15 54.84a18.16 18.16 0 0 1 -18.16 18.16h-29.68a18.16 18.16 0 0 1 -18.16-18.16v-29.68a18.16 18.16 0 0 1 18.16-18.16h29.68a18.16 18.16 0 0 1 18.16 18.16z"/><path d="m90.59 61.56-.19-.19-.16-.16a20.16 20.16 0 0 0 -14.24-5.88 20.52 20.52 0 0 0 -20.38 20.67 20.75 20.75 0 0 0 6 14.61 20.19 20.19 0 0 0 14.42 6 20.73 20.73 0 0 0 14.55-35.05zm-14.59 28a13.56 13.56 0 1 1 13.37-13.56 13.46 13.46 0 0 1 -13.37 13.56z"/><path d="m102.43 54.38a4.88 4.88 0 0 1 -4.85 4.92 4.81 4.81 0 0 1 -3.42-1.43 4.93 4.93 0 0 1 3.43-8.39 4.82 4.82 0 0 1 3.09 1.12l.1.1a3.05 3.05 0 0 1 .44.44l.11.12a4.92 4.92 0 0 1 1.1 3.12z"/></g></g></g></g></g></svg>
				<h4><?php echo __( 'link_insta' );?></h4>
				<p><?php echo __( 'link_insta_desc' );?></p>
                <div class="inst_alert"></div>
				
				<?php echo($alert); ?>
				
				<?php if (!$redirect) { ?>
					<?php if (empty($_COOKIE['instagram_access_token']) || empty($_COOKIE['instagram_user_id'])) { ?>
						<a class="btn waves-effect bold btn_round" href="https://api.instagram.com/oauth/authorize?client_id=<?php echo($config->instagram_importer_app_id) ?>&redirect_uri=<?php echo $site_url;?>/settings-instagram&scope=user_profile,user_media&response_type=code"><span><?php echo __( 'link_instagram_account' );?></span></a>
					<?php } ?>
					<?php if (!empty($_COOKIE['instagram_access_token']) && !empty($_COOKIE['instagram_user_id'])) { ?>
						<button class="btn waves-effect bold btn_round instagram_import" type="button" onclick="ImportFromInstagram()"><span><?php echo __( 'start_import' );?></span></button>
					<?php } ?>
				<?php }?>
			</div>

			<a class="hidden" href="<?php echo $site_url;?>/settings-instagram/<?php echo $profile->username;?>" data-ajax="/settings-instagram/<?php echo $profile->username;?>" target="_self" id="instagram_redirect"></a>
			<a class="hidden" href="<?php echo $site_url;?>/@<?php echo $profile->username;?>" data-ajax="/@<?php echo $profile->username;?>" target="_self" id="profile_redirect"></a>
			
			<div class="insta_post_all_imp">
				<label for="check-all" id="check-all" style="display: none;">
					<input type="checkbox" class="filled-in check-all">
					<span></span>
				</label>
				<button type="button" class="btn btn_primary waves-effect import-selected d-block" disabled style="display: none;"><?php echo(__('import')); ?><span></span></button>
			</div>
			
			<div class="clearfix"></div>
			
			<div class="row instagram_posts">
				
			</div>
			
			<a href="javascript:void(0);" id="btn_load_more_instagram_posts" class="btn waves-effect load_more" style="display: none;" onclick="ImportFromInstagram()"><?php echo __('Load more...');?></a>
			<input type="hidden" id="instagram_after">
		</div>
		<div class="col s12 m3"></div>
    </div>
</div>
<!-- End Settings  -->
<script type="text/javascript">
    function checkbox_changed() {
        $('.import-selected').attr('disabled', false);
        $('.import-selected').find('span').text(' (' + $('.import-checkbox:checked').length + ')');
        if ($('.import-checkbox:checked').length == 0) {
            $('.import-selected').attr('disabled', true);
        }
    }
    $('.check-all').on('click', function(event) {
      $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $('.check-all').change(function(event) {
        $('.import-selected').attr('disabled', false);
        $('.import-selected').find('span').text(' (' + $('.import-checkbox:checked').length + ')');
        if ($('.import-checkbox:checked').length == 0) {
            $('.import-selected').attr('disabled', true);
        }
    });
    $('.import-selected').on('click', function(event) {
        event.preventDefault();
        data = new Array();
        $('.import-checkbox:checked').each(function () {
            data.push($(this).attr('data-id'));
        });
        let self = this;
        $(self).text("<?php echo(__('please_wait..')); ?>");
        $(self).attr('disabled', true);
        $.post(window.ajax + 'profile/import_instagram_posts', {ids:data},function (data) {
            $(self).html("<?php echo(__('import')); ?><span></span>");
            $(self).attr('disabled', false);
            if (data.status == 200) {
                let text = "<?php echo(__('imported')); ?>";
                if (typeof data.imported_ids != 'undefined') {
                    data.imported_ids.forEach((item) => {
                        $('#check-data-'+item).remove();
                        $('#btn_import_'+item).replaceWith('<a href="javascript:void(0)" class="btn btn-secondary" disabled>'+text+'</a>');

                    });
                }
                if (typeof data.message != 'undefined' && data.message != '') {
                    showResponseAlert('.inst_alert','danger',data.message,2000);
                    setTimeout(function () {
                        $('#profile_redirect').click();
                    },2000);
                }
            }
        }).fail(function(data) {
            $(self).html("<?php echo(__('import')); ?><span></span>");
            $(self).attr('disabled', false);
            showResponseAlert('.inst_alert','danger',data.responseJSON.message,2000);
        });
    });
    function ImportFromInstagram() {
        let instagram_after = $('#instagram_after').val();
        $.post(window.ajax + 'profile/get_instagram_posts', {instagram_after:instagram_after},function (data) {
            if (data.status == 200) {
                $('.instagram_import').slideUp();
                $('.instagram_posts').append(data.html);
                if (data.next === true) {
                    $('#btn_load_more_instagram_posts').slideDown();
                    $('#check-all').slideDown();
                    $('.import-selected').slideDown();
                    $('#instagram_after').val(data.instagram_after);
                }
                else{
                    $('#btn_load_more_instagram_posts').slideUp();
                    $('#check-all').slideDown();
                    $('.import-selected').slideDown();
                    $('#instagram_after').val('');
                }
            }
            else{
                $('#instagram_redirect').click();
            }
        }).fail(function(data) {
            showResponseAlert('.inst_alert','danger',data.responseJSON.message,2000);
            setTimeout(function () {
                $('#instagram_redirect').click();
            },2000);
        });
    }
    function ImportInstagramPost(self,id) {
        $(self).text("<?php echo(__('please_wait..')); ?>");
        $.post(window.ajax + 'profile/import_instagram_post', {id:id},function (data) {
            $(self).text("<?php echo(__('import')); ?>");
            if (data.status == 200) {
                let text = "<?php echo(__('imported')); ?>";
                $(self).replaceWith('<a href="javascript:void(0)" class="btn btn-secondary" disabled>'+text+'</a>');
            }
        }).fail(function(data) {
            $(self).text("<?php echo(__('import')); ?>");
            showResponseAlert('.inst_alert','danger',data.responseJSON.message,2000);
        });
    }
    <?php if ($redirect) { ?>
        setTimeout(function () {
            $('#instagram_redirect').click();
        },2000);
    <?php } ?>
</script>