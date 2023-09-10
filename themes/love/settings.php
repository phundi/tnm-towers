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
							<a class="active" href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" target="_self">
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
							<a href="<?php echo $site_url;?>/settings-sessions/<?php echo $profile->username;?>" data-ajax="/settings-sessions/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M12 14v2a6 6 0 0 0-6 6H4a8 8 0 0 1 8-8zm0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm2.595 7.812a3.51 3.51 0 0 1 0-1.623l-.992-.573 1-1.732.992.573A3.496 3.496 0 0 1 17 14.645V13.5h2v1.145c.532.158 1.012.44 1.405.812l.992-.573 1 1.732-.992.573a3.51 3.51 0 0 1 0 1.622l.992.573-1 1.732-.992-.573a3.496 3.496 0 0 1-1.405.812V22.5h-2v-1.145a3.496 3.496 0 0 1-1.405-.812l-.992.573-1-1.732.992-.572zM18 19.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg> <span><?php echo __( 'Sessions' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/my-info/<?php echo $profile->username;?>" data-ajax="/my-info/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M19 7h5v2h-5V7zm-2 5h7v2h-7v-2zm3 5h4v2h-4v-2zM2 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H2zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/></svg> <span><?php echo __( 'My Info' );?></span>
							</a>
						</li>
					
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
				<form method="POST" action="/profile/save_general_setting" class="dt_settings_bg_wrap">
					<h2 class="dt_sett_wrap_title"><?php echo __( 'General Settings' );?></h2>

					<div class="alert alert-success" role="alert" style="display:none;"></div>
					<div class="alert alert-danger" role="alert" style="display:none;"></div>

					<div class="row">
						<div class="input-field col s6 xs12">
							<input id="first_name" name="first_name" type="text" maxlength="30" class="validate" value="<?php echo $profile->first_name;?>" autofocus>
							<label for="first_name"><?php echo __( 'First Name' );?></label>
						</div>
						<div class="input-field col s6 xs12">
							<input id="last_name" name="last_name" type="text" maxlength="30" class="validate" value="<?php echo $profile->last_name;?>">
							<label for="last_name"><?php echo __( 'Last Name' );?></label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s6 xs12">
							<input id="username" name="username" type="text" class="validate" value="<?php echo $profile->username;?>">
							<label for="username"><?php echo __( 'Username' );?></label>
						</div>
						<div class="input-field col s6 xs12">
							<input id="email" name="email" type="text" class="validate" value="<?php echo $profile->email;?>" >
							<label for="email"><?php echo __( 'Email' );?></label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s6 xs12">
							<select id="country" name="country" <?php if($config->filter_by_cities == 1 && !empty($config->geo_username)){ ?>onchange="ChangeCountryKey(this)"<?php } ?>>
								<option value="" disabled selected><?php echo __( 'Choose your nationality' );?></option>
								<?php
									$city_country_key = '';
									foreach( Dataset::load('countries') as $key => $val ){
										if ($profile->country == $key) {
		                                    $city_country_key = $key;
		                                }
										echo '<option value="'. $key .'" data-code="'. $val['isd'] .'"  '. ( ( $profile->country == $key ) ? 'selected' : '' ) .'>'. $val['name'] .'</option>';
									}
								?>
							</select>
							<label for="country"><?php echo __( 'Nationality' );?></label>
						</div>
						<div class="input-field col s6 xs12">
		                    <input type="hidden" class="city_country_key" name="city_country_key" value="<?php echo($city_country_key); ?>">
		                    <input id="city" type="text" class="validate selected_city" name="city" value="<?php echo $profile->city;?>" <?php if($config->filter_by_cities == 1 && !empty($config->geo_username)){ ?> onkeyup="SearchForCity(this)"<?php } ?>>
		                    <div class="city_search_result"></div>
		                    <label for="city"><?php echo __( 'City' );?></label>
		                </div>
						<?php if( $config->disable_phone_field == 'on' ){ ?>
						<div class="input-field col s6 xs12">
							<input id="mobile" type="tel" class="validate" name="phone_number" value="<?php echo $profile->phone_number;?>" title="Field must be a number."  >
							<label for="mobile"><?php echo __( 'Mobile Number' );?></label>
						</div>
						<?php } ?>
					</div>
					<div class="row">
						<?php if(can_change_gender($profile->gender)){ ?>
						<div class="input-field col s6 xs12">
							<select id="gender" name="gender">
								<?php echo DatasetGetSelect( $profile->gender, "gender", "Choose your Gender" );?>
							</select>
							<label for="gender"><?php echo __( 'Gender' );?></label>
						</div>
						<?php } ?>
						<div class="input-field col s6 xs12">
							<input id="birthday" name="birthday" type="text" value="<?php echo $profile->birthday;?>" class="datepicker user_bday">
							<label for="birthday"><?php echo __( 'Birth date' );?></label>
						</div>
					</div>

					<?php
					$fields = GetProfileFields('general');
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
						echo '<div class="row">' . $html . '</div>';
						echo '<input name="custom_fields" type="hidden" value="1">';
					}
					?>
					<?php if( $admin_mode == true ){?>
					<div class="row">
						<?php //if( $profile->admin !== '1' ){?>
						<div class="input-field col">
							<div class="switch">
								<label>
									<input type="hidden" name="admin" value="off" />
									<input type="checkbox" name="admin" <?php echo ( ( $profile->admin == 1 ) ? 'checked' : '' );?> >
									<div class="round_check">
										<span class="circle"></span>
										<?php echo __( 'Admin' );?>
									</div>
								</label>
							</div>
						</div>
						<?php //}?>
						<div class="input-field col">
		                    <div class="switch">
		                        <label>
		                            <input type="hidden" name="verified" value="off" />
		                            <input type="checkbox" name="verified" <?php echo ( ( $profile->verified == 1 ) ? 'checked' : '' );?> >
		                            <div class="round_check">
										<span class="circle"></span>
										<?php echo __( 'verified' );?>
									</div>
		                        </label>
		                    </div>
		                </div>

						<?php if( $config->pro_system == 1 ) {?>
						<div class="input-field col">
							<div class="switch">
								<label>
									<input type="checkbox" name="is_pro" <?php echo ( ( $profile->is_pro == 1 ) ? 'checked' : '' );?>>
									<div class="round_check">
										<span class="circle"></span>
										<?php echo __( 'Pro Member' );?>
									</div>
								</label>
							</div>
						</div>
						<?php }?>

					</div>
					<?php }?>
					<?php if( $admin_mode == true ){?>
						<br>
						<div class="row">
							<div class="input-field col s12 m6">
								<input id="balance" type="number" class="validate" name="balance" value="<?php echo (int)$profile->balance;?>" pattern="\d*" title="Field must be a number." onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" >
								<label for="balance"><?php echo __( 'Credits' );?></label>
							</div>
						</div>
					<?php }?>
					<br>
					<div class="dt_sett_footer valign-wrapper">
						<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="submit" name="action"><span><?php echo __( 'Save' );?></span> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
					</div>
					<?php if( $admin_mode == true ){?>
						<input type="hidden" name="targetuid" value="<?php echo strrev( str_replace( '==', '', base64_encode($profile->id) ) );?>">
					<?php }?>
				</form>
			</div>
		</div>
    </div>
</div>
<!-- End Settings  -->
<script>
    $(document).ready(function(){
        /***phone number format***/
        $(".phone-format").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            var curchr = this.value.length;
            var curval = $(this).val();
            if (curchr == 3 && curval.indexOf("(") <= -1) {
                $(this).val("(" + curval + ")" + "-");
            } else if (curchr == 4 && curval.indexOf("(") > -1) {
                $(this).val(curval + ")-");
            } else if (curchr == 5 && curval.indexOf(")") > -1) {
                $(this).val(curval + "-");
            } else if (curchr == 9) {
                $(this).val(curval + "-");
                $(this).attr('maxlength', '14');
            }
        });
    });
</script>