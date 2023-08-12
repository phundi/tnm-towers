<?php
$admin_mode = false;
if( $profile->admin == '1' || CheckPermission($profile->permission, "manage-users") ){
    $admin_mode = true;
}

$target_user = route(2);
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
?>
<?php //$user = auth();?>

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
							<a class="active" href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>" target="_self">
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
							<a href="<?php echo $site_url;?>/settings-sessions/<?php echo $profile->username;?>" data-ajax="/settings-sessions/<?php echo $profile->username;?>" target="_self">
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
				<form method="POST" action="/profile/save_profile_setting" class="profile">
					<div class="dt_settings_bg_wrap sett_prof_cont">
						<h2 class="dt_sett_wrap_title"><?php echo __( 'Profile' );?></h2>
						
						<div class="alert alert-success" role="alert" style="display:none;"></div>
						<div class="alert alert-danger" role="alert" style="display:none;"></div>

						<div class="row">
							<div class="input-field col s12">
								<textarea id="about" name="about" class="materialize-textarea" autofocus><?php echo $user->about;?></textarea>
								<label for="about"><?php echo __( 'About Me' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12 no_margin">
								<div id="interest" class="chips interest_chips chips-placeholder no_margin"></div>
								<input type="hidden" id="interest_entry_profile" name="interest" value="<?php echo $user->interest;?>">
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12 no_placeholder">
								<input id="ulocation" name="location" type="text" class="validate" value="<?php echo $user->location;?>">
								<label for="ulocation"><?php echo __( 'Location' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="relationship" name="relationship">
									<?php echo DatasetGetSelect( $user->relationship, "relationship",  __("Choose your Relationship status") );?>
								</select>
								<label for="relationship"><?php echo __( 'Relationship status' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="language" name="language">
									<?php echo DatasetGetSelect( $user->language, "language", __("Choose your Preferred Language") );?>
								</select>
								<label for="language"><?php echo __( 'Preferred Language' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="work" name="work_status">
									<?php echo DatasetGetSelect( $user->work_status, "work_status", __("Choose your Work status") );?>
								</select>
								<label for="work"><?php echo __( 'Work status' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="education" name="education">
									<?php echo DatasetGetSelect( $user->education, "education", __("Education Level") );?>
								</select>
								<label for="education"><?php echo __( 'Education Level' );?></label>
							</div>
						</div>
					</div>
					<br>

					<?php
					$fields = GetProfileFields('profile');
					$custom_data = UserFieldsData($profile->id);
					$template = $theme_path . 'partails' . $_DS . 'profile-fields.php';
					$html = '';
					if (count($fields) > 0) {
						foreach ($fields as $key => $field) {
							ob_start();
							require($template);
							$html .= ob_get_contents();
							ob_end_clean();
						}
						echo '<div class="dt_settings_bg_wrap sett_prof_cont"><div class="row">' . $html . '</div></div><br>';
						echo '<input name="custom_fields" type="hidden" value="1">';
					}
					?>


					<div class="dt_settings_bg_wrap sett_prof_cont">
						<!--Looks-->
						<h5><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M9,11.75A1.25,1.25 0 0,0 7.75,13A1.25,1.25 0 0,0 9,14.25A1.25,1.25 0 0,0 10.25,13A1.25,1.25 0 0,0 9,11.75M15,11.75A1.25,1.25 0 0,0 13.75,13A1.25,1.25 0 0,0 15,14.25A1.25,1.25 0 0,0 16.25,13A1.25,1.25 0 0,0 15,11.75M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,20C7.59,20 4,16.41 4,12C4,11.71 4,11.42 4.05,11.14C6.41,10.09 8.28,8.16 9.26,5.77C11.07,8.33 14.05,10 17.42,10C18.2,10 18.95,9.91 19.67,9.74C19.88,10.45 20,11.21 20,12C20,16.41 16.41,20 12,20Z"></path></svg> <?php echo __('Looks');?></h5>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="ethnicity" name="ethnicity">
									<?php echo DatasetGetSelect( $user->ethnicity, "ethnicity", __("Ethnicity") );?>
								</select>
								<label for="ethnicity"><?php echo __( 'Ethnicity' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="body" name="body">
									<?php echo DatasetGetSelect( $user->body, "body", __("Body Type") );?>
								</select>
								<label for="body"><?php echo __( 'Body Type' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="height" name="height">
									<?php echo DatasetGetSelect( $user->height, "height", __("Height") );?>
								</select>
								<label for="height"><?php echo __( 'Height' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="hair_color" name="hair_color">
									<?php echo DatasetGetSelect( $user->hair_color, "hair_color", __("Choose your Hair Color") );?>
								</select>
								<label for="hair_color"><?php echo __( 'Hair Color' );?></label>
							</div>
						</div>
					</div>
					<br>
					<div class="dt_settings_bg_wrap sett_prof_cont">
						<!--Personality-->
						<h5><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M1.5,4V5.5C1.5,9.65 3.71,13.28 7,15.3V20H22V18C22,15.34 16.67,14 14,14C14,14 13.83,14 13.75,14C9,14 5,10 5,5.5V4M14,4A4,4 0 0,0 10,8A4,4 0 0,0 14,12A4,4 0 0,0 18,8A4,4 0 0,0 14,4Z"></path></svg> <?php echo __('Personality');?></h5>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="character" name="character">
									<?php echo DatasetGetSelect( $user->character, "character", __("Character") );?>
								</select>
								<label for="character"><?php echo __( 'Character' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="children" name="children">
									<?php echo DatasetGetSelect( $user->children, "children", __("Children") );?>
								</select>
								<label for="children"><?php echo __( 'Children' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="friends" name="friends">
									<?php echo DatasetGetSelect( $user->friends, "friends", __("Friends") );?>
								</select>
								<label for="friends"><?php echo __( 'Friends' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="pets" name="pets">
									<?php echo DatasetGetSelect( $user->pets, "pets", __("Pets") );?>
								</select>
								<label for="pets"><?php echo __( 'Pets' );?></label>
							</div>
						</div>
					</div>
					<br>
					<div class="dt_settings_bg_wrap sett_prof_cont">
						<!--Lifestyle-->
						<h5><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M15,18.54C17.13,18.21 19.5,18 22,18V22H5C5,21.35 8.2,19.86 13,18.9V12.4C12.16,12.65 11.45,13.21 11,13.95C10.39,12.93 9.27,12.25 8,12.25C6.73,12.25 5.61,12.93 5,13.95C5.03,10.37 8.5,7.43 13,7.04V7A1,1 0 0,1 14,6A1,1 0 0,1 15,7V7.04C19.5,7.43 22.96,10.37 23,13.95C22.39,12.93 21.27,12.25 20,12.25C18.73,12.25 17.61,12.93 17,13.95C16.55,13.21 15.84,12.65 15,12.39V18.54M7,2A5,5 0 0,1 2,7V2H7Z"></path></svg> <?php echo __('Lifestyle');?></h5>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="live_with" name="live_with">
									<?php echo DatasetGetSelect( $user->live_with, "live_with", __("Live with") );?>
								</select>
								<label for="live_with"><?php echo __( 'I live with' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="car" name="car">
									<?php echo DatasetGetSelect( $user->car, "car", __("Car") );?>
								</select>
								<label for="car"><?php echo __( 'Car' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="religion" name="religion">
									<?php echo DatasetGetSelect( $user->religion, "religion", __("Religion") );?>
								</select>
								<label for="religion"><?php echo __( 'Religion' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="smoke" name="smoke">
									<?php echo DatasetGetSelect( $user->smoke, "smoke", __("Smoke") );?>
								</select>
								<label for="smoke"><?php echo __( 'Smoke' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<select id="drink" name="drink">
									<?php echo DatasetGetSelect( $user->drink, "drink", __("Drink") );?>
								</select>
								<label for="drink"><?php echo __( 'Drink' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<select id="travel" name="travel">
									<?php echo DatasetGetSelect( $user->travel, "travel", __("Travel") );?>
								</select>
								<label for="travel"><?php echo __( 'Travel' );?></label>
							</div>
						</div>
					</div>
					<br>
					<div class="dt_settings_bg_wrap sett_prof_cont">
						<!--Favourites-->
						<h5><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="currentColor" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z"></path></svg> <?php echo __('Favourites');?></h5>
						<div class="row">
							<div class="input-field col s6 xs12">
								<input id="music" type="text" class="validate" name="music" value="<?php echo $user->music;?>">
								<label for="music"><?php echo __( 'Music Genre' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<input id="dish" type="text" class="validate" name="dish" value="<?php echo $user->dish;?>">
								<label for="dish"><?php echo __( 'Dish' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<input id="song" type="text" class="validate" name="song" value="<?php echo $user->song;?>">
								<label for="song"><?php echo __( 'Song' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<input id="hobby" type="text" class="validate" name="hobby" value="<?php echo $user->hobby;?>">
								<label for="hobby"><?php echo __( 'Hobby' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<input id="sport" type="text" class="validate" name="sport" value="<?php echo $user->sport;?>">
								<label for="sport"><?php echo __( 'Sport' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<input id="tv" type="text" class="validate" name="tv" value="<?php echo $user->tv;?>">
								<label for="tv"><?php echo __( 'TV Show' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<input id="book" type="text" class="validate" name="book" value="<?php echo $user->book;?>">
								<label for="book"><?php echo __( 'Book' );?></label>
							</div>
							<div class="input-field col s6 xs12">
								<input id="movie" type="text" class="validate" name="movie" value="<?php echo $user->movie;?>">
								<label for="movie"><?php echo __( 'Movie' );?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s6 xs12">
								<input id="colour" type="text" class="validate" name="colour" value="<?php echo $user->colour;?>">
								<label for="colour"><?php echo __( 'Color' );?></label>
							</div>
						</div>
						<div class="dt_sett_footer valign-wrapper">
							<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="submit" name="action"><span><?php echo __( 'Save' );?></span> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
						</div>
						<?php if( $admin_mode == true ){?>
							<input type="hidden" name="targetuid" value="<?php echo strrev( str_replace( '==', '', base64_encode($user->id) ) );?>">
						<?php }?>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
<!-- End Settings  -->
