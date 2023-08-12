<?php
$admin_mode = false;
if( $profile->admin == '1'  || CheckPermission($profile->permission, "manage-users")){
    $target_user = route(2);
    $_user = LoadEndPointResource('users');
    if( $_user ){
        if( $target_user !== '' ){
            $profile = $_user->get_user_profile(Secure($target_user));
            if( !$profile ){
                echo '<script>window.location = window.site_url;</script>';
                exit();
            }else{
                if( $profile->admin == '1' ){
                    $admin_mode = true;
                }
            }
        }
    }
}
?>

<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<!-- Settings  -->
<div class="container">
    <div class="dt_settings">
		<div class="row">
			<div class="col s12">
				<div class="dt_settings_bg_wrap dt_sett_top_menu">
					<ul class="dt_settings_side_links">
						<li>
							<a href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M6.75 2.5A4.25 4.25 0 0 1 11 6.75V11H6.75a4.25 4.25 0 1 1 0-8.5zM9 9V6.75A2.25 2.25 0 1 0 6.75 9H9zm-2.25 4H11v4.25A4.25 4.25 0 1 1 6.75 13zm0 2A2.25 2.25 0 1 0 9 17.25V15H6.75zm10.5-12.5a4.25 4.25 0 1 1 0 8.5H13V6.75a4.25 4.25 0 0 1 4.25-4.25zm0 6.5A2.25 2.25 0 1 0 15 6.75V9h2.25zM13 13h4.25A4.25 4.25 0 1 1 13 17.25V13zm2 2v2.25A2.25 2.25 0 1 0 17.25 15H15z"/></svg> <span><?php echo __( 'General' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M3 4.995C3 3.893 3.893 3 4.995 3h14.01C20.107 3 21 3.893 21 4.995v14.01A1.995 1.995 0 0 1 19.005 21H4.995A1.995 1.995 0 0 1 3 19.005V4.995zM5 5v14h14V5H5zm2.972 13.18a9.983 9.983 0 0 1-1.751-.978A6.994 6.994 0 0 1 12.102 14c2.4 0 4.517 1.207 5.778 3.047a9.995 9.995 0 0 1-1.724 1.025A4.993 4.993 0 0 0 12.102 16c-1.715 0-3.23.864-4.13 2.18zM12 13a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7zm0-2a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg> <span><?php echo __( 'Profile' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/settings-privacy/<?php echo $profile->username;?>" data-ajax="/settings-privacy/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M3.783 2.826L12 1l8.217 1.826a1 1 0 0 1 .783.976v9.987a6 6 0 0 1-2.672 4.992L12 23l-6.328-4.219A6 6 0 0 1 3 13.79V3.802a1 1 0 0 1 .783-.976zM5 4.604v9.185a4 4 0 0 0 1.781 3.328L12 20.597l5.219-3.48A4 4 0 0 0 19 13.79V4.604L12 3.05 5 4.604zM13 10h3l-5 7v-5H8l5-7v5z"/></svg> <span><?php echo __( 'Privacy' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/settings-password/<?php echo $profile->username;?>" data-ajax="/settings-password/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M19 10h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V11a1 1 0 0 1 1-1h1V9a7 7 0 1 1 14 0v1zM5 12v8h14v-8H5zm6 2h2v4h-2v-4zm6-4V9A5 5 0 0 0 7 9v1h10z"/></svg> <span><?php echo __( 'Password' );?></span>
							</a>
						</li>
						<?php if( $config->social_media_links == 'on' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-social/<?php echo $profile->username;?>" data-ajax="/settings-social/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M10 6v2H5v11h11v-5h2v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6zm11-3v8h-2V6.413l-7.793 7.794-1.414-1.414L17.585 5H13V3h8z"/></svg> <span><?php echo __( 'Social' );?></span>
								</a>
							</li>
						<?php }?>
						<li>
							<a href="<?php echo $site_url;?>/settings-blocked/<?php echo $profile->username;?>" data-ajax="/settings-blocked/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm7 6.586l2.121-2.122 1.415 1.415L20.414 19l2.122 2.121-1.415 1.415L19 20.414l-2.121 2.122-1.415-1.415L17.586 19l-2.122-2.121 1.415-1.415L19 17.586z"/></svg> <span><?php echo __( 'Blocked' );?></span>
							</a>
						</li>
						<li>
							<a class="active" href="<?php echo $site_url;?>/settings-sessions/<?php echo $profile->username;?>" data-ajax="/settings-sessions/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M12 14v2a6 6 0 0 0-6 6H4a8 8 0 0 1 8-8zm0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm2.595 7.812a3.51 3.51 0 0 1 0-1.623l-.992-.573 1-1.732.992.573A3.496 3.496 0 0 1 17 14.645V13.5h2v1.145c.532.158 1.012.44 1.405.812l.992-.573 1 1.732-.992.573a3.51 3.51 0 0 1 0 1.622l.992.573-1 1.732-.992-.573a3.496 3.496 0 0 1-1.405.812V22.5h-2v-1.145a3.496 3.496 0 0 1-1.405-.812l-.992.573-1-1.732.992-.572zM18 19.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg> <span><?php echo __( 'Sessions' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/my-info/<?php echo $profile->username;?>" data-ajax="/my-info/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M19 7h5v2h-5V7zm-2 5h7v2h-7v-2zm3 5h4v2h-4v-2zM2 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H2zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/></svg> <span><?php echo __( 'My Info' );?></span>
							</a>
						</li>
						<?php if( $config->affiliate_system == '1' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-affiliate/<?php echo $profile->username;?>" data-ajax="/settings-affiliate/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M1 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H1zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM21.548.784A13.942 13.942 0 0 1 23 7c0 2.233-.523 4.344-1.452 6.216l-1.645-1.196A11.955 11.955 0 0 0 21 7c0-1.792-.393-3.493-1.097-5.02L21.548.784zm-3.302 2.4A9.97 9.97 0 0 1 19 7a9.97 9.97 0 0 1-.754 3.816l-1.677-1.22A7.99 7.99 0 0 0 17 7a7.99 7.99 0 0 0-.43-2.596l1.676-1.22z"/></svg> <span><?php echo __( 'Affiliates' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $config->invite_links_system == '1' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-links/<?php echo $profile->username;?>" data-ajax="/settings-links/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M18.364 15.536L16.95 14.12l1.414-1.414a5 5 0 1 0-7.071-7.071L9.879 7.05 8.464 5.636 9.88 4.222a7 7 0 0 1 9.9 9.9l-1.415 1.414zm-2.828 2.828l-1.415 1.414a7 7 0 0 1-9.9-9.9l1.415-1.414L7.05 9.88l-1.414 1.414a5 5 0 1 0 7.071 7.071l1.414-1.414 1.415 1.414zm-.708-10.607l1.415 1.415-7.071 7.07-1.415-1.414 7.071-7.07z"/></svg> <span><?php echo __( 'Invitation' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $config->two_factor == '1' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-twofactor/<?php echo $profile->username;?>" data-ajax="/settings-twofactor/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M3.783 2.826L12 1l8.217 1.826a1 1 0 0 1 .783.976v9.987a6 6 0 0 1-2.672 4.992L12 23l-6.328-4.219A6 6 0 0 1 3 13.79V3.802a1 1 0 0 1 .783-.976zM5 4.604v9.185a4 4 0 0 0 1.781 3.328L12 20.597l5.219-3.48A4 4 0 0 0 19 13.79V4.604L12 3.05 5 4.604z"/></svg> <span><?php echo __( 'Two Factor' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $config->emailNotification == '1' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-email/<?php echo $profile->username;?>" data-ajax="/settings-email/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M22 20H2v-2h1v-6.969C3 6.043 7.03 2 12 2s9 4.043 9 9.031V18h1v2zM5 18h14v-6.969C19 7.148 15.866 4 12 4s-7 3.148-7 7.031V18zm4.5 3h5a2.5 2.5 0 1 1-5 0z"/></svg> <span><?php echo __( 'Notifications' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $admin_mode == false && $config->deleteAccount == '1' ) {?>
							<li>
								<a href="<?php echo $site_url;?>/settings-delete/<?php echo $profile->username;?>" data-ajax="/settings-delete/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M17 6h5v2h-2v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H2V6h5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3zm1 2H6v12h12V8zm-9 3h2v6H9v-6zm4 0h2v6h-2v-6zM9 4v2h6V4H9z"/></svg> <span><?php echo __( 'Delete' );?></span>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="col s12">
				<form class="dt_settings_bg_wrap">
					<h2 class="dt_sett_wrap_title no_margin"><?php echo __( 'Manage Sessions' );?></h2>
					<br>
					<div>
					<?php
						global $db;
						$sessions = $db->where('user_id', $profile->id)->orderBy('time', 'DESC')->get('sessions', null, array('*'));
						foreach ($sessions as $key => $session) {
							$details = unserialize($session['platform_details']);
							echo '<div class="session_row valign-wrapper qd_sett_sessions" data-id="'.$session['id'].'">';
							echo '<div class="valign-wrapper first">';
							switch (strtolower($details['platform'])) {
								case 'windows':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,15 L23,15 L23,18 C23,19.1045695 22.1045695,20 21,20 L3,20 C1.8954305,20 1,19.1045695 1,18 L1,15 L3,15 L3,6 C3,4.8954305 3.8954305,4 5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,15 Z M19,15 L19,6 L5,6 L5,15 L19,15 Z M3,17 L3,18 L21,18 L21,17 L3,17 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
								case 'linux':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,15 L23,15 L23,18 C23,19.1045695 22.1045695,20 21,20 L3,20 C1.8954305,20 1,19.1045695 1,18 L1,15 L3,15 L3,6 C3,4.8954305 3.8954305,4 5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,15 Z M19,15 L19,6 L5,6 L5,15 L19,15 Z M3,17 L3,18 L21,18 L21,17 L3,17 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
								case 'mac':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,15 L23,15 L23,18 C23,19.1045695 22.1045695,20 21,20 L3,20 C1.8954305,20 1,19.1045695 1,18 L1,15 L3,15 L3,6 C3,4.8954305 3.8954305,4 5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,15 Z M19,15 L19,6 L5,6 L5,15 L19,15 Z M3,17 L3,18 L21,18 L21,17 L3,17 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
								case 'iphone web':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,21 C19,22.1045695 18.1045695,23 17,23 L7,23 C5.8954305,23 5,22.1045695 5,21 L5,3 C5,1.8954305 5.8954305,1 7,1 Z M10,3 L7,3 L7,21 L17,21 L17,3 L14,3 C14,3.55228475 13.5522847,4 13,4 L11,4 C10.4477153,4 10,3.55228475 10,3 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
								case 'android web':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,21 C19,22.1045695 18.1045695,23 17,23 L7,23 C5.8954305,23 5,22.1045695 5,21 L5,3 C5,1.8954305 5.8954305,1 7,1 Z M10,3 L7,3 L7,21 L17,21 L17,3 L14,3 C14,3.55228475 13.5522847,4 13,4 L11,4 C10.4477153,4 10,3.55228475 10,3 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
								case 'mobile':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7,2 L17,2 C18.1045695,2 19,2.8954305 19,4 L19,20 C19,21.1045695 18.1045695,22 17,22 L7,22 C5.8954305,22 5,21.1045695 5,20 L5,4 C5,2.8954305 5.8954305,2 7,2 Z M7,4 L7,20 L17,20 L17,4 L7,4 Z M12,19 C11.4477153,19 11,18.5522847 11,18 C11,17.4477153 11.4477153,17 12,17 C12.5522847,17 13,17.4477153 13,18 C13,18.5522847 12.5522847,19 12,19 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
								case 'phone':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7,2 L17,2 C18.1045695,2 19,2.8954305 19,4 L19,20 C19,21.1045695 18.1045695,22 17,22 L7,22 C5.8954305,22 5,21.1045695 5,20 L5,4 C5,2.8954305 5.8954305,2 7,2 Z M7,4 L7,20 L17,20 L17,4 L7,4 Z M12,19 C11.4477153,19 11,18.5522847 11,18 C11,17.4477153 11.4477153,17 12,17 C12.5522847,17 13,17.4477153 13,18 C13,18.5522847 12.5522847,19 12,19 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
								case 'unknown':
									echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7 C22.1045695,7 23,7.8954305 23,9 L23,18 C23,19.1045695 22.1045695,20 21,20 L16,20 L3,20 C1.8954305,20 1,19.1045695 1,18 L1,15 L3,15 L3,6 C3,4.8954305 3.8954305,4 5,4 L19,4 C20.1045695,4 21,4.8954305 21,6 L21,7 Z M19,7 L19,6 L5,6 L5,15 L14,15 L14,9 C14,7.8954305 14.8954305,7 16,7 L19,7 Z M14,18 L14,17 L3,17 L3,18 L14,18 Z M16,9 L16,18 L21,18 L21,9 L16,9 Z" fill="#cc42bd"/></svg></span>&nbsp;&nbsp;&nbsp;&nbsp;';
									break;
							}
							echo '<div>';
							echo '<b>'.$details['platform'].'</b>';
							echo '<p>';
							echo '<svg xmlns="http://www.w3.org/2000/svg" width="19.301" height="17.301" viewBox="0 0 19.301 17.301"> <path d="M18.651,17.3H.65a.69.69,0,0,1-.463-.187A.69.69,0,0,1,0,16.65V.65A.685.685,0,0,1,.187.188.683.683,0,0,1,.65,0h18a.68.68,0,0,1,.461.188A.68.68,0,0,1,19.3.65v16a.641.641,0,0,1-.65.65ZM1.3,7.3V16H18V7.3Zm0-6V6H18V1.3Zm7,3H7V3H8.3V4.3Zm-4,0H3V3H4.3V4.3Z" fill="currentColor"/> </svg>&nbsp;&nbsp;<span>'.$details['name'].'</span>';
							echo '</p>';
							echo '</div>';
							echo '</div>';
							echo '<div>';
							echo '<p>';
							echo '<svg xmlns="http://www.w3.org/2000/svg" width="19.3" height="19.3" viewBox="0 0 19.3 19.3"> <path d="M10.21,19.3H9.091V17.467l-.313-.034a7.876,7.876,0,0,1-6.91-6.91l-.034-.312H0V9.091H1.833l.034-.312a7.875,7.875,0,0,1,6.91-6.91l.313-.035V0H10.21V1.833l.311.035a7.876,7.876,0,0,1,6.912,6.91l.034.313H19.3V10.21H17.467l-.034.313a7.877,7.877,0,0,1-6.912,6.91l-.311.034V19.3ZM9.65,2.94a6.71,6.71,0,1,0,6.71,6.71A6.718,6.718,0,0,0,9.65,2.94Zm0,10a3.29,3.29,0,1,1,3.29-3.29A3.294,3.294,0,0,1,9.65,12.94Z" fill="currentColor"/> </svg>&nbsp;&nbsp;<span>'.$session['platform'].'</span>';
							echo '</p>';
							echo '</div>';
							echo '<div>';
							echo '<p>';
							echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M1.181 12C2.121 6.88 6.608 3 12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9zM12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-2a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>&nbsp;&nbsp;<span class="ajax-time" title="'.date('c',$session['time']).'">'.Time_Elapsed_String($session['time']).'</span>';
							echo '</p>';
							echo '</div>';
							echo '<div>';
							echo '<button class="btn waves-effect waves-light delete_session" type="button" title="'.__('Delete').'" data-id="'.$session['id'].'"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"> <path id="Path_7292" data-name="Path 7292" d="M3541.5,8233.8h4.5v1.8h-1.8v13.5a.892.892,0,0,1-.9.9h-12.6a.892.892,0,0,1-.9-.9v-13.5H3528v-1.8h4.5V8232h9Zm-7.2,4.5v7.2h1.8v-7.2Zm3.6,0v7.2h1.8v-7.2Z" transform="translate(-3528 -8232)" fill="currentColor"/> </svg></button>';
							echo '</div>';
							echo '</div>';
						}
					?>
					</div>
					<br><br><br>
					<div class="dt_sett_footer valign-wrapper center-align">
						<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="button"><span><?php echo __( 'Logout all sessions' );?></span> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
<!-- End Settings  -->
<script>
$(function () {
    $('.delete_session').click(function(e){
        e.preventDefault();
        let id = $(this).attr('data-id');
        let formData = new FormData();
            formData.append("session_id", id);
        $.ajax({
            type: 'POST',
            url: window.ajax + 'profile/delete_session',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status == 200) {
                    $('.session_row[data-id="'+id+'"]').remove();
                } else {
                    alert("<?php echo __('Error while deleting session, please try again later.');?>");
                }
            }
        });
    });
});
</script>
