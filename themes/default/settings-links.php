<?php
if( $config->invite_links_system == '0' ){
    echo '<script>window.location = window.site_url;</script>';
    exit();
}
$admin_mode = false;
if( $profile->admin == '1' || CheckPermission($profile->permission, "manage-users")){
    $target_user = route(2);
    $_user = LoadEndPointResource('users');
    if( $_user ){
        if( $target_user !== '' ){
            $profile = $_user->get_user_profile(Secure($target_user));
            if( !$profile ){
                echo '<script>window.location = window.site_url;</script>';
                exit();
            }else{
                $user = $profile;
                if( $profile->admin == '1' ){
                    $admin_mode = true;
                }
            }
        }
    }
}else{
    $user = auth();
}
?>
<?php //$user = auth();?>
<style>
    .dt_settings_header {margin-top: -3px;display: inline-block;}
    @media (max-width: 1024px){
        .dt_slide_menu {
            display: none;
        }
        nav .header_user {
            display: block;
        }
    }
</style>
<!-- Settings  -->
<div class="dt_settings_header bg_gradient">
    <div class="dt_settings_circle-1"></div>
    <div class="dt_settings_circle-2"></div>
    <div class="dt_settings_circle-3"></div>
    <div class="container">
        <div class="sett_active_svg">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z"></path></svg>
        </div>
        <div class="sett_navbar valign-wrapper">
            <ul class="tabs">
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" target="_self"><?php echo __( 'General' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Profile' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-privacy/<?php echo $profile->username;?>" data-ajax="/settings-privacy/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Privacy' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-password/<?php echo $profile->username;?>" data-ajax="/settings-password/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Password' );?></a></li>
                <?php if( $config->social_media_links == 'on' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-social/<?php echo $profile->username;?>" data-ajax="/settings-social/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Social Links' );?></a></li><?php }?>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-blocked/<?php echo $profile->username;?>" data-ajax="/settings-blocked/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Blocked Users' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-sessions/<?php echo $profile->username;?>" data-ajax="/settings-sessions/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Manage Sessions' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/my-info/<?php echo $profile->username;?>" data-ajax="/my-info/<?php echo $profile->username;?>" target="_self"><?php echo __( 'My Information' );?></a></li>
                <?php if( $config->affiliate_system == '1' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-affiliate/<?php echo $profile->username;?>" data-ajax="/settings-affiliate/<?php echo $profile->username;?>" target="_self"><?php echo __( 'My affiliates' );?></a></li><?php } ?>
                <?php if( $config->invite_links_system == '1' ){ ?><li class="tab col s3"><a class="active" href="<?php echo $site_url;?>/settings-links/<?php echo $profile->username;?>" data-ajax="/settings-links/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Invitation Links' );?></a></li><?php } ?>
                <?php if( $config->two_factor == '1' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-twofactor/<?php echo $profile->username;?>" data-ajax="/settings-twofactor/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Two-factor authentication' );?></a></li><?php } ?>
                <?php if( $config->emailNotification == '1' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-email/<?php echo $profile->username;?>" data-ajax="/settings-email/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Manage Notifications' );?></a></li><?php } ?>
                <?php if( $admin_mode == false && $config->deleteAccount == '1' ) {?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-delete/<?php echo $profile->username;?>" data-ajax="/settings-delete/<?php echo $profile->username;?>" target="_self"><?php echo __( 'Delete Account' );?></a></li><?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="container">
    <div class="dt_settings row">
		<div class="col s12 m3"></div>
		<form class="col s12 m6 setting-general-form">
			<?php
				global $db;

				$available_links = GetAvailableLinks($profile->id);
				if ($config->user_links_limit > 0) {
					$generated_links = $config->user_links_limit - $available_links;
				}
				else{
					$generated_links = GetGeneratedLinks($profile->id);
				}
				$used_links = GetUsedLinks($profile->id);
			?>
			<div class="earn_points">
				<div class="ep_illus">
					<div class="valign-wrapper ep_how_many comment_post">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10.59,13.41C11,13.8 11,14.44 10.59,14.83C10.2,15.22 9.56,15.22 9.17,14.83C7.22,12.88 7.22,9.71 9.17,7.76V7.76L12.71,4.22C14.66,2.27 17.83,2.27 19.78,4.22C21.73,6.17 21.73,9.34 19.78,11.29L18.29,12.78C18.3,11.96 18.17,11.14 17.89,10.36L18.36,9.88C19.54,8.71 19.54,6.81 18.36,5.64C17.19,4.46 15.29,4.46 14.12,5.64L10.59,9.17C9.41,10.34 9.41,12.24 10.59,13.41M13.41,9.17C13.8,8.78 14.44,8.78 14.83,9.17C16.78,11.12 16.78,14.29 14.83,16.24V16.24L11.29,19.78C9.34,21.73 6.17,21.73 4.22,19.78C2.27,17.83 2.27,14.66 4.22,12.71L5.71,11.22C5.7,12.04 5.83,12.86 6.11,13.65L5.64,14.12C4.46,15.29 4.46,17.19 5.64,18.36C6.81,19.54 8.71,19.54 9.88,18.36L13.41,14.83C14.59,13.66 14.59,11.76 13.41,10.59C13,10.2 13,9.56 13.41,9.17Z" /></svg>
						<b><span id="available_links"><?php echo $available_links; ?></span> <?php echo __('Available links'); ?></b>
					</div>
					<div class="valign-wrapper ep_how_many create_post">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10.6 13.4A1 1 0 0 1 9.2 14.8A4.8 4.8 0 0 1 9.2 7.8L12.7 4.2A5.1 5.1 0 0 1 19.8 4.2A5.1 5.1 0 0 1 19.8 11.3L18.3 12.8A6.4 6.4 0 0 0 17.9 10.4L18.4 9.9A3.2 3.2 0 0 0 18.4 5.6A3.2 3.2 0 0 0 14.1 5.6L10.6 9.2A2.9 2.9 0 0 0 10.6 13.4M23 18V20H20V23H18V20H15V18H18V15H20V18M16.2 13.7A4.8 4.8 0 0 0 14.8 9.2A1 1 0 0 0 13.4 10.6A2.9 2.9 0 0 1 13.4 14.8L9.9 18.4A3.2 3.2 0 0 1 5.6 18.4A3.2 3.2 0 0 1 5.6 14.1L6.1 13.7A7.3 7.3 0 0 1 5.7 11.2L4.2 12.7A5.1 5.1 0 0 0 4.2 19.8A5.1 5.1 0 0 0 11.3 19.8L13.1 18A6 6 0 0 1 16.2 13.7Z" /></svg>
						<b><span id="generated_links"><?php echo $generated_links; ?></span> <?php echo __('Generated links'); ?></b>
					</div>
					<div class="valign-wrapper ep_how_many reaction_bg">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10.6 13.4A1 1 0 0 1 9.2 14.8A4.8 4.8 0 0 1 9.2 7.8L12.7 4.2A5.1 5.1 0 0 1 19.8 4.2A5.1 5.1 0 0 1 19.8 11.3L18.3 12.8A6.4 6.4 0 0 0 17.9 10.4L18.4 9.9A3.2 3.2 0 0 0 18.4 5.6A3.2 3.2 0 0 0 14.1 5.6L10.6 9.2A2.9 2.9 0 0 0 10.6 13.4M23 18V20H15V18M16.2 13.7A4.8 4.8 0 0 0 14.8 9.2A1 1 0 0 0 13.4 10.6A2.9 2.9 0 0 1 13.4 14.8L9.9 18.4A3.2 3.2 0 0 1 5.6 18.4A3.2 3.2 0 0 1 5.6 14.1L6.1 13.7A7.3 7.3 0 0 1 5.7 11.2L4.2 12.7A5.1 5.1 0 0 0 4.2 19.8A5.1 5.1 0 0 0 11.3 19.8L13.1 18A6 6 0 0 1 16.2 13.7Z" /></svg>
						<b><span id="used_links"><?php echo $used_links; ?></span> <?php echo __('Used links'); ?></b>
					</div>
				</div>
			</div>
			<div class="dt_sett_footer valign-wrapper">
                <button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="button" onclick="GenerateLink()"><span class="btn_text"><?php echo __('Generate link'); ?></span> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
            </div>
			<form method="post" class="form-horizontal setting-profile-form" enctype="multipart/form-data">
				<div class="setting-profile-alert setting-update-alert"></div>
				<?php 
					$trans = GetMyInvitaionCodes($profile->id);
				?>
				<?php if (count($trans) > 0): ?>
					<br>
					<table class="table-responsive">
						<thead>
							<tr>
								<th><?php echo __('url'); ?></th>
								<th><?php echo __('invited user'); ?></th>
								<th><?php echo __('date'); ?></th>
							</tr>
						</thead>
						<tbody id="user-ads">
							<?php foreach ($trans as $key => $transaction): ?>
								<tr data-ad-id="<?php echo $transaction['id']; ?>">
									<td><button type="button" class="btn btn-small btn-flat btn_primary white-text copy-invitation-url" data-link="<?php echo $site_url . '/register?invite='. $transaction['code']; ?>"><?php echo __('copy'); ?></button></td>
									<td>
										<?php if (!empty($transaction['user_name'])) { ?>
											<a href="<?php echo($transaction['user_url']) ?>"><?php echo $transaction['user_name']; ?></a>
										<?php } ?>
									</td>
									<td><?php echo date('Y-m-d', $transaction['time']); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
				<input type="hidden" name="user_id" id="user-id" value="<?php echo $profile->id;?>">
			</form>
		</form>
		<div class="col s12 m3"></div>
	</div>
</div>
<!-- End Settings  -->
<script>
	function GenerateLink() {
		$('.add_wow_loader').text("<?php echo __('please_wait'); ?>");
		$('.add_wow_loader').attr('disable', 'true');
		$.ajax({
            type: 'POST',
            url: window.ajax + 'profile/add_invitation_links',
            data:{user_id:'<?php echo($profile->id); ?>'},
            success: function(data) {
            	$('.add_wow_loader').removeAttr('disable');
				$('.add_wow_loader').text("<?php echo __('Generate link'); ?>");
                if (data.status == 200) {
                    $('.setting-profile-alert').html('<div class="alert alert-success">' + data.message + '</div>');
					setTimeout(function () {
						$('.setting-profile-alert').html('');
						location.reload();
					},2000);
                }
            },
            error: function(data){
            	$('.add_wow_loader').removeAttr('disable');
				$('.add_wow_loader').text("<?php echo __('Generate link'); ?>");
                $('.setting-profile-alert').html('<div class="alert alert-danger">' + data.responseJSON.message + '</div>');
				setTimeout(function () {
					$('.setting-profile-alert').html('');
				},2000);
            }
        });
	}
	$(document).on('click', '.copy-invitation-url', function(event) {
	 event.preventDefault();
	   var $temp = $("<input>");
	   $("body").append($temp);
	   $temp.val($(this).attr('data-link')).select();
	   document.execCommand("copy");
	   $temp.remove();
	   self = this;
	   $(this).text("<?php echo __('copied'); ?>");
	   setTimeout(function () {
	   	$(self).text("<?php echo __('copy'); ?>");
	   },500);
	});
</script>