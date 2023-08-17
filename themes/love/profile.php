<?php
    $profile = LoadEndPointResource( 'users' )->get_user_profile(strtolower(substr(route(1), 1)));
    if( $profile->verified !== "1" ) {
        ?>
        <script>
            window.location = window.site_url + '/find-matches';
        </script>
        <?php
    }
?>
<?php if($data['name'] == 'profile' && $profile->privacy_show_profile_on_google == '1'){ ?>
    <script>
        var meta = document.createElement('meta');
            meta.name = "robots";
            meta.content = "noindex";
            document.getElementsByTagName('head')[0].appendChild(meta);

        var meta1 = document.createElement('meta');
            meta1.name = "googlebot";
            meta1.content = "noindex";
            document.getElementsByTagName('head')[0].appendChild(meta1);
    </script>
<?php } ?>
<?php $user = ( isset( $_SESSION['JWT'] ) ) ? auth() : null ;
$matched = false;
global $db;

$matched_count = array(['cnt' => 0]);
if(!empty($user) ) {
	$matched_count = $db->rawQuery('SELECT count(id) as cnt FROM `likes` WHERE ( (`like_userid` = ' . auth()->id . ' AND `user_id` = ' . $profile->id . ') OR (`like_userid` = ' . $profile->id . ' AND `user_id` = ' . auth()->id . ') )');
}
if($matched_count[0]['cnt'] == 2){
	$matched = true;
}
?>
<!-- Profile  -->

<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<div class="container container-fluid container_new find_matches_cont dt_user_profile_parent">
	<div class="row r_margin">
		
		<div class="col l3 profile_menu">
			<div class="dt_left_sidebar dt_profile_side">
				<div class="avatar">
					<a class="inline" href="<?php echo $profile->avater->full;?>" id="avater_profile_img">
						<img src="<?php echo $profile->avater->avater;?>" alt="<?php echo $profile->full_name;?>" class="responsive-img" />
						<?php if((int)abs(((strtotime(date('Y-m-d H:i:s')) - $profile->lastseen))) < 60 && (int)$profile->online == 1) { echo '<div class="useronline" style="top: 10px;left: 10px;"></div>'; }?>
					</a>
					<?php if( $user !== null ){ ?>
						<?php if( $user->admin == 1 ){ ?>
							<div class="dt_chng_avtr">
								<span class="btn-upload-image" onclick="document.getElementById('admin_profileavatar_img').click(); return false">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,17H7V3H21M21,1H7A2,2 0 0,0 5,3V17A2,2 0 0,0 7,19H21A2,2 0 0,0 23,17V3A2,2 0 0,0 21,1M3,5H1V21A2,2 0 0,0 3,23H19V21H3M15.96,10.29L13.21,13.83L11.25,11.47L8.5,15H19.5L15.96,10.29Z" /></svg> <?php echo __( 'Change Photo' );?>
								</span>
								<input type="file" id="admin_profileavatar_img" data-username="<?php echo $profile->username;?>" data-userid="<?php echo $profile->id;?>" class="hide" accept="image/x-png, image/gif, image/jpeg" name="avatar">
							</div>
							<div class="dt_avatar_progress hide">
								<div class="admin_avatar_imgprogress progress">
									<div class="admin_avatar_imgdeterminate determinate" style="width: 0%"></div >
								</div>
							</div>
							<div class="admin_avatar_imgprogress progress hide">
								<div class="admin_avatar_imgdeterminate determinate" style="width: 0%"></div >
							</div>
						<?php } ?>
					<?php } ?>
				</div>
				<div class="dt_othr_ur_info">
					<h2>
						<?php echo $profile->full_name.$profile->pro_icon;?><?php echo ( $profile->age  > 0 ) ? ", ". $profile->age : "";?>
						<?php if( verifiedUser($profile) ){ ?>
							<span tooltip="<?php echo __( 'This profile is Verified' );?>" flow="down">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#2196F3" d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z" /></svg>
							</span>
						<?php }else{ ?>
							<?php if($config->emailValidation == "0"){?>
								<span tooltip="<?php echo __( 'This profile is Verified' );?>" flow="down">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#2196F3" d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z" /></svg>
								</span>
							<?php }else{ ?>
								<span tooltip="<?php echo __( 'This profile is Not verified' );?>" flow="down">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#e18805" d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M17,15.59L15.59,17L12,13.41L8.41,17L7,15.59L10.59,12L7,8.41L8.41,7L12,10.59L15.59,7L17,8.41L13.41,12L17,15.59Z" /></svg>
								</span>
							<?php } ?>
						<?php } ?>
					</h2>
					<p><?php echo __( 'Popularity' );?>: <b><?php echo GetUserPopularity($profile->id);?></b></p>
				</div>
				<?php if(!empty($user) ) {?>
					<div class="dt_usr_opts_mnu">
						<?php if( $config->connectivitySystem == "1" && !( Wo_IsFollowing($profile->id, $user->id) || Wo_IsFollowing($user->id, $profile->id) ) && !( Wo_IsFollowRequested($profile->id, (int) $user->id) || Wo_IsFollowRequested( (int) $user->id , $profile->id ) ) && (int)Wo_CountFollowing($user->id) < (int)$config->connectivitySystemLimit ){ ?>
							<a href="javascript:void(0);" id="btn_add_friend" data-ajax-post="/profile/add_friend" data-ajax-params="to=<?php echo $profile->id;?>" data-ajax-callback="callback_add_friend" class="green_bg tooltipped" data-position="bottom" data-tooltip="<?php echo __( 'Add Friend' );?>">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm6 6v-3h2v3h3v2h-3v3h-2v-3h-3v-2h3z"/></svg>
							</a>
						<?php } ?>
						<?php if( $config->connectivitySystem == "1" && ( Wo_IsFollowing($profile->id, $user->id) || Wo_IsFollowing($user->id, $profile->id) ) ){ ?>
							<a href="javascript:void(0);" id="btn_delete_friend" data-ajax-post="/profile/add_friend" data-ajax-params="to=<?php echo $profile->id;?>" data-ajax-callback="callback_add_friend" class="red_bg tooltipped" data-position="bottom" data-tooltip="<?php echo __( 'UnFriend' );?>">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm7 6.586l2.121-2.122 1.415 1.415L20.414 19l2.122 2.121-1.415 1.415L19 20.414l-2.121 2.122-1.415-1.415L17.586 19l-2.122-2.121 1.415-1.415L19 17.586z"/></svg>
							</a>
						<?php } ?>
						<?php if( $config->connectivitySystem == "1" && ( Wo_IsFollowRequested($profile->id, (int) $user->id) || Wo_IsFollowRequested( (int) $user->id , $profile->id ) ) ){ ?>
							<?php
							$flip = '';
								$_title_icon = __( 'Friend request sent' );
								if( Wo_IsFollowRequested($profile->id, (int) $user->id) === true && Wo_IsFollowRequested( (int) $user->id , $profile->id ) === false ){
									$_title_icon = __( 'Friend request received' );
									$flip = ' style="display: block; transform: scale(-1,1);" ';
								}
							?>
							<a href="javascript:void(0);" class="green_bg tooltipped" data-position="bottom" data-tooltip="<?php echo $_title_icon;?>">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm5.793 8.914l3.535-3.535 1.415 1.414-4.95 4.95-3.536-3.536 1.415-1.414 2.12 2.121z"/></svg>
							</a>
						<?php } ?>
						<?php if( $profile->src !== 'Fake' ){?>
						<a href="javascript:void(0);" class="yellow_bg tooltipped" id="btn_open_private_conversation" data-ajax-post="/chat/open_private_conversation" data-ajax-params="from=<?php echo $profile->id;?>&web_device_id=<?php echo $profile->web_device_id;?>" data-ajax-callback="open_private_conversation" data-position="bottom" data-tooltip="<?php echo __( 'Message' );?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 22.5L11.2 19H6a1 1 0 0 1-1-1V7.103a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1V18a1 1 0 0 1-1 1h-5.2L14 22.5zm1.839-5.5H21V8.103H7V17H12.161L14 19.298 15.839 17zM2 2h17v2H3v11H1V3a1 1 0 0 1 1-1z"/></svg>
						</a>
						<?php }?>
						<?php //if(isGenderFree($user->gender) === true ){?>
							<a href="javascript:void(0);" class="blue_bg tooltipped" data-ajax-post="/profile/open_gift_model" data-ajax-params="" data-ajax-callback="callback_open_gift_model" data-position="bottom" data-tooltip="<?php echo __( 'Send a gift' );?>">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.001a.996.996 0 0 1-.993.999H3.993A.996.996 0 0 1 3 20.001V10zm16 0H5v9h14v-9zM4 5v3h16V5H4zm5 7h6v2H9v-2z"/></svg>
							</a>
						<?php //}?>
						<?php if (auth()->admin == '1' || $profile->admin !== '1') { ?>
						
						<a href="javascript:void(0);" data-target="user_prof_dropdown" class="dropdown-trigger">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M12 3c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 14c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-7c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
						</a>
						<ul id="user_prof_dropdown" class="dropdown-content" tabindex="0">
							<?php if( isset( $_COOKIE[ 'JWT' ] ) && !empty( $_COOKIE[ 'JWT' ] ) && $profile->admin !== '1' ){ ?>
								<li>
									<a href="javascript:void(0);" data-ajax-post="/useractions/<?php if( isUserBlocked( $profile->id ) ) { echo 'unblock'; } else { echo 'block'; }?>" data-ajax-params="userid=<?php echo $profile->id;?>&web_device_id=<?php echo $profile->web_device_id;?>" data-ajax-callback="<?php if( isUserBlocked( $profile->id ) ) { echo 'callback_unblock'; } else { echo 'callback_block'; }?>" class="block_text">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,0A12,12 0 0,1 24,12A12,12 0 0,1 12,24A12,12 0 0,1 0,12A12,12 0 0,1 12,0M12,2A10,10 0 0,0 2,12C2,14.4 2.85,16.6 4.26,18.33L18.33,4.26C16.6,2.85 14.4,2 12,2M12,22A10,10 0 0,0 22,12C22,9.6 21.15,7.4 19.74,5.67L5.67,19.74C7.4,21.15 9.6,22 12,22Z" /></svg>&nbsp;&nbsp;&nbsp;<?php if( isUserBlocked( $profile->id ) ) { echo __( 'Unblock' ); } else { echo __( 'Block User' ); }?>
									</a>
								</li>
								<?php if( !isUserReported( $profile->id ) ) { ?>
									<li>
										<a class="report_text modal-trigger" href="#modal_report">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,14H11V10H13M13,18H11V16H13M1,21H23L12,2L1,21Z" /></svg>&nbsp;&nbsp;&nbsp;<?php echo __( 'Report User' );?>
										</a>
									</li>
								<?php }else{ ?>
									<li>
										<a href="javascript:void(0);" data-ajax-post="/useractions/unreport" data-ajax-params="userid=<?php echo $profile->id;?>&web_device_id=<?php echo $profile->web_device_id;?>" data-ajax-callback="callback_unreport" class="report_text">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M13,9H18.5L13,3.5V9M10,14.59L7.88,12.46L6.46,13.88L8.59,16L6.46,18.12L7.88,19.54L10,17.41L12.12,19.54L13.54,18.12L11.41,16L13.54,13.88L12.12,12.46L10,14.59Z" /></svg>&nbsp;&nbsp;&nbsp;<?php echo __( 'Remove report' );?>
										</a>
									</li>
								<?php } ?>
							<?php } ?>
							<?php if( isset( $_COOKIE[ 'JWT' ] ) && !empty( $_COOKIE[ 'JWT' ] ) && auth()->admin == '1' ){ ?>
								<li>
									<a href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" class="dt_edt_prof_link">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" /></svg>&nbsp;&nbsp;&nbsp;<?php echo __( 'Edit' );?>
									</a>
								</li>
							<?php } ?>
						</ul>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="home_usr_stats">
					<div>
						<div>
							<b><?php echo($q['views_count']) ?></b>
							<p><?php echo __( 'Visitors' );?></p>
						</div>
						<div>
							<b><?php echo($q['likes_count']) ?></b>
							<p><?php echo __( 'Likes' );?></p>
						</div>
						<div>
							<b><?php echo($q['following_count']) ?></b>
							<p><?php echo __( 'Friends' );?></p>
						</div>
					</div>
				</div>
				<?php if( !empty( $profile->interest ) ) {?>
					<div class="dt_profile_side_interest">
						<h5><?php echo __( 'Interests' );?></h5>
						<div class="dt_intrst_chip_prnt">
							<?php
								$chips = explode( "," , $profile->interest );
								if( !empty( $chips ) ) {
									foreach ($chips as $key => $value) {
										$interest = trim( ucfirst( $value ) );
										if( $interest !== "" ){
											echo '<a href="'.$site_url.'/interest/'.strtolower($interest).'" data-ajax="/interest/'.strtolower($interest).'"><div class="chip dt_intrst_chip">'.$interest.'</div></a>';
										}
									}
								}
							?>
						</div>
					</div>
				<?php } ?>
				
				<?php if( $config->social_media_links == 'on' ){?>
					<?php if( !empty( $profile->facebook ) || !empty( $profile->twitter ) || !empty( $profile->google ) || !empty( $profile->instagram ) || !empty( $profile->linkedin ) || !empty( $profile->website ) ) {?>
						<div class="dt_user_social">
							<h5><?php echo __( 'Social accounts' );?></h5>
							<ul>
								<?php if( !empty( $profile->facebook ) ) {?>
								<li class="fb">
									<a href="https://www.facebook.com/<?php echo $profile->facebook;?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="#1877F2" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
									</a>
								</li>
								<?php } ?>
								<?php if( !empty( $profile->twitter ) ) {?>
								<li class="twit">
									<a href="https://twitter.com/<?php echo $profile->twitter;?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="#1D9BF0" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>
									</a>
								</li>
								<?php } ?>
								<?php if( !empty( $profile->google ) ) {?>
								<li class="gplus">
									<a href="https://vk/<?php echo $profile->google;?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#2787F5" d="M21.579 6.855c.14-.465 0-.806-.662-.806h-2.193c-.558 0-.813.295-.953.619 0 0-1.115 2.719-2.695 4.482-.51.513-.743.675-1.021.675-.139 0-.341-.162-.341-.627V6.855c0-.558-.161-.806-.626-.806H9.642c-.348 0-.558.258-.558.504 0 .528.79.65.871 2.138v3.228c0 .707-.127.836-.407.836-.743 0-2.551-2.729-3.624-5.853-.209-.607-.42-.852-.98-.852H2.752c-.627 0-.752.295-.752.619 0 .582.743 3.462 3.461 7.271 1.812 2.601 4.363 4.011 6.687 4.011 1.393 0 1.565-.313 1.565-.853v-1.966c0-.626.133-.752.574-.752.324 0 .882.164 2.183 1.417 1.486 1.486 1.732 2.153 2.567 2.153h2.192c.626 0 .939-.313.759-.931-.197-.615-.907-1.51-1.849-2.569-.512-.604-1.277-1.254-1.51-1.579-.325-.419-.231-.604 0-.976.001.001 2.672-3.761 2.95-5.04z"></path></svg>
									</a>
								</li>
								<?php } ?>
								<?php if( !empty( $profile->instagram ) ) {?>
								<li class="insta">
									<a href="https://www.instagram.com/<?php echo $profile->instagram;?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="#E81E2A" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
									</a>
								</li>
								<?php } ?>
								<?php if( !empty( $profile->linkedin ) ) {?>
								<li class="lin">
									<a href="https://www.linkedin.com/in/<?php echo $profile->linkedin;?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="#0066C5" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
									</a>
								</li>
								<?php } ?>
								<?php if( !empty( $profile->website ) ) {?>
								<li>
									<a href="<?php echo $profile->website;?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm7.931 9h-2.764a14.67 14.67 0 0 0-1.792-6.243A8.013 8.013 0 0 1 19.931 11zM12.53 4.027c1.035 1.364 2.427 3.78 2.627 6.973H9.03c.139-2.596.994-5.028 2.451-6.974.172-.01.344-.026.519-.026.179 0 .354.016.53.027zm-3.842.7C7.704 6.618 7.136 8.762 7.03 11H4.069a8.013 8.013 0 0 1 4.619-6.273zM4.069 13h2.974c.136 2.379.665 4.478 1.556 6.23A8.01 8.01 0 0 1 4.069 13zm7.381 6.973C10.049 18.275 9.222 15.896 9.041 13h6.113c-.208 2.773-1.117 5.196-2.603 6.972-.182.012-.364.028-.551.028-.186 0-.367-.016-.55-.027zm4.011-.772c.955-1.794 1.538-3.901 1.691-6.201h2.778a8.005 8.005 0 0 1-4.469 6.201z"></path></svg>
									</a>
								</li>
								<?php } ?>
								<?php
								$social_fields = GetProfileFields('social');
								$social_custom_data = UserFieldsData($profile->id);
								if (count($social_fields) > 0) {
									foreach ($social_fields as $key => $field) {
										if($field['profile_page'] == 1) {
											if( isset($social_custom_data[$field['fid']]) && $social_custom_data[$field['fid']] !== null ) {
												echo '<li>';
												echo '    <a href="' . $social_custom_data[$field['fid']] . '" target="_blank">';
												echo '    <div class="soc_icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zM4.069 13h2.974c.136 2.379.665 4.478 1.556 6.23A8.01 8.01 0 0 1 4.069 13zm2.961-2H4.069a8.012 8.012 0 0 1 4.618-6.273C7.704 6.618 7.136 8.762 7.03 11zm5.522 8.972c-.183.012-.365.028-.552.028-.186 0-.367-.016-.55-.027-1.401-1.698-2.228-4.077-2.409-6.973h6.113c-.208 2.773-1.117 5.196-2.602 6.972zM9.03 11c.139-2.596.994-5.028 2.451-6.974.172-.01.344-.026.519-.026.179 0 .354.016.53.027 1.035 1.364 2.427 3.78 2.627 6.973H9.03zm6.431 8.201c.955-1.794 1.538-3.901 1.691-6.201h2.778a8.005 8.005 0 0 1-4.469 6.201zM17.167 11a14.67 14.67 0 0 0-1.792-6.243A8.014 8.014 0 0 1 19.931 11h-2.764z"/></svg></div>';
												echo '    <div class="soc_info"><p>' . $field['name'] . '</p><span>' . $social_custom_data[$field['fid']] . '</span></div></a>';
												echo '</li>';
											}
										}
									}
								}
								?>
							</ul>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		
		<div class="col l6">
			<div class="mtc_usrd_content dt_profile_about">
				<div class="row no_margin r_margin">
					<div class="col s12 m6">
						<div class="mtc_usrd_slider">
				            <figure class="dt_cover_photos">
								<div class="dt_cp_photos_list">
									<?php
										$i = 0;
										$media_count = count( (array)$profile->mediafiles );
										$gallery = array();
										$gallery['visable'][0] = null;
										$gallery['visable'][1] = null;
										$gallery['visable'][2] = null;
										$gallery['visable'][3] = null;

										for( $i == 0 ; $i < $media_count ; $i++ ){
											$gallery['visable'][$i] = $profile->mediafiles[$i];
										}

										

										foreach ($gallery['visable'] as $key => $value) {
											if( !empty($value) && $value['is_video'] == "1" && $value['is_confirmed'] == "0" ){

											} else {
												if (!empty($value)) {
													$private = 'false';
													if ($value['is_private'] == 1) {
														$private = 'true';
													}
													$is_avater = 'false';
													if ($value['avater'] == $profile->avater->avater) {
														$is_avater = 'true';
													}
													$full = $value['full'];
													$avater = $value['avater'];
													$video_file = $value['video_file'];
													if ($value['is_private'] == 1 && $matched === false) {
														$full = $value['private_file_full'];
														$avater = $value['private_file_avater'];
													}
													if($config->review_media_files == '1' && $value['is_approved'] == 1){
														echo '<div class="dt_cp_l_photos">';
														if( $value['is_video'] == "1" ){

															if ($value['is_private'] == 1 && $matched === false) {
																echo '<a class="inline" href="' . $value['full'] . '" data-fancybox="gallery" data-id="' . $value['id'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';
															}else{
																echo '<a class="inline" href="#myVideo_'.$value['id'].'" data-fancybox="gallery" data-id="' . $value['id'] . '" data-video="' . $value['is_video'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';
																echo '<video width="800" height="550" controls id="myVideo_'.$value['id'].'" style="display:none;">';
																echo '    <source src="'.$video_file.'" type="video/mp4">';
					//                                          echo '    <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.webm" type="video/webm">';
					//                                          echo '    <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.ogv" type="video/ogg">';
																echo '    Your browser doesn\'t support HTML5 video tag.';
																echo '</video>';
															}

															echo '<div class="dt_prof_vid_play_ico"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10,16.5V7.5L16,12M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg></div>';
														}else{
															echo '<a class="inline" href="' . $value['full'] . '" data-fancybox="gallery" data-id="' . $value['id'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';
														}
														echo '<img src="' . $avater . '" alt="' . $profile->username . '">';
														echo '</a>';
														echo '</div>';
													} else {
														if($config->review_media_files == '0' && $value['is_approved'] == 1) {
															echo '<div class="dt_cp_l_photos">';
															if( $value['is_video'] == "1" ){

																if ($value['is_private'] == 1 && $matched === false) {
																	echo '<a class="inline" href="' . $avater . '" data-fancybox="gallery" data-id="' . $value['id'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';
																}else{
																	echo '<a class="inline" href="#myVideo_'.$value['id'].'" data-fancybox="gallery" data-id="' . $value['id'] . '" data-video="' . $value['is_video'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';
																	echo '<video width="800" height="550" controls id="myVideo_'.$value['id'].'" style="display:none;">';
																	echo '    <source src="'.$video_file.'" type="video/mp4">';
																	//                                          echo '    <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.webm" type="video/webm">';
																	//                                          echo '    <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.ogv" type="video/ogg">';
																	echo '    Your browser doesn\'t support HTML5 video tag.';
																	echo '</video>';
																}

																echo '<div class="dt_prof_vid_play_ico"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10,16.5V7.5L16,12M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg></div>';
															}else{
																if ($value['is_private'] == 0){
																	$avater = $value['full'];
																}
																echo '<a class="inline" href="' . $avater . '" data-fancybox="gallery" data-id="' . $value['id'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';
															}
															echo '<img src="' . $avater . '" alt="' . $profile->username . '">';
															echo '</a>';
															echo '</div>';
														}
													}
												}else{
													echo '<div class="dt_cp_l_photos">';
													echo '<div class="inline"></div>';
													echo '</div>';
												}
											}
										}
									?>
								</div>
							</figure>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="mtc_usrd_sidebar dt_profile_about_side">
							<div class="mtc_usrd_summary">
								<h5><?php echo __( 'About' );?> <?php echo $profile->full_name;?></h5>
							</div>
							<div class="sidebar_usr_info dt_profile_about_info">
								<?php if( !empty( $profile->about ) ) {?>
									<div class="desc"><?php echo nl2br($profile->about);?></div>
								<?php } ?>
								
								<div class="row">
									<?php if( !empty( $profile->location ) ) {?>
										<div class="col s6">
											<div>
												<p class="info_title"><?php echo __( 'Location' );?></p>
												<span><?php echo $profile->location;?></span>
											</div>
										</div>
									<?php } ?>

									<?php if( $profile->district !== '' ){?>
										<div class="col s6">
											<div>
												<p class="info_title"><?php echo __( 'District' );?></p>
												<span><?php echo $profile->district;?></span>
											</div>
										</div>
									<?php } ?>

									<?php if( $profile->country !== '' ){?>
										<div class="col s6">
											<div>
												<p class="info_title"><?php echo __( 'Country' );?></p>
												<span><?php echo $profile->country_txt;?></span>
											</div>
										</div>
									<?php } ?>

								

								</div>
							</div>
							<?php if( isset( $_COOKIE[ 'JWT' ] ) && !empty( $_COOKIE[ 'JWT' ] ) ){ ?>
								<div class="center mtc_usrd_actions dt_profile_about_action">
									<div class="like">
										<a href="javascript:void(0);" id="like_btn" data-replace-text="<?php echo __('Liked');?>" data-replace-dom=".like_text" data-ajax-post="/useractions/<?php if( isUserLiked( $profile->id ) ) { echo 'remove_like'; } else { echo 'like'; }?>" data-ajax-params="email_on_profile_like=<?php echo $profile->email_on_profile_like;?>&username=<?php echo $profile->username;?>" data-ajax-callback="callback_<?php if( isUserLiked( $profile->id ) ) { echo 'remove_like" class="lk_active'; } else { echo 'like'; }?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path></svg>
											<span class="bold like_text" ><?php if( isUserLiked( $profile->id ) ) { echo __( 'Liked' ); } else { echo __( 'Like' ); }?></span>
										</a>
									</div>
									<div class="dislike">
										<a href="javascript:void(0);" id="dislike_btn" data-replace-text="<?php echo __('Disliked');?>" data-replace-dom=".dislike_text" data-ajax-post="/useractions/<?php if( isUserDisliked( $profile->id ) ) { echo 'remove_dislike'; } else { echo 'dislike'; }?>" data-ajax-params="username=<?php echo $profile->username;?>" data-ajax-callback="callback_<?php if( isUserDisliked( $profile->id ) ) { echo 'remove_dislike" class="dk_active'; } else { echo 'dislike'; }?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"></path></svg>
											<span class="bold dislike_text"><?php if( isUserDisliked( $profile->id ) ) { echo __( 'Disliked' ); } else { echo __( 'Dislike' ); }?></span>
										</a>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

            <!-- Right Main Area -->

                <div class="dt_user_about">
                	<?php if ($q['private_count'] > 0 && !$matched) { ?>
						<div class="alert alert-warning alert_not_matched"><?php echo(str_replace('{X}', $profile->full_name, __('you_have_to_match_with_media'))); ?></div>
					<?php } ?>
                    
                    <div class="about_block"> <!-- Profile Info -->
                        <h4><?php echo __( 'Profile Info ' );?></h4>
						<div class="row">
							<?php if( !empty( $profile->language ) || !empty( $profile->relationship ) || !empty( $profile->work_status ) || !empty( $profile->education ) ) {?>
								<div class="col m6">
									<div class="dt_profile_info">
										<h5><svg xmlns="http://www.w3.org/2000/svg" width="66" height="17" viewBox="0 0 66 17"> <g id="Group_8834" data-name="Group 8834" transform="translate(-266.936 -201.15)"> <rect id="Rectangle_3373" data-name="Rectangle 3373" width="17" height="16" transform="translate(266.936 201.15)" fill="currentColor"></rect> <circle id="Ellipse_331" data-name="Ellipse 331" cx="8.5" cy="8.5" r="8.5" transform="translate(289.936 201.15)" fill="currentColor"></circle> <path id="Polygon_5" data-name="Polygon 5" d="M10,0,20,17H0Z" transform="translate(312.936 201.15)" fill="currentColor"></path> </g> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __( 'Basic' );?></h5>
										
										<?php if( !empty( $profile->gender ) ) {?>
											<div class="row">
												<div class="col s6">
													<p class="info_title"><?php echo __( 'Gender' );?></p>
												</div>
												<div class="col s6">
													<p><?php echo __($profile->gender);?></p>
												</div>
											</div>
										<?php } ?>
										<?php if( !empty( $profile->language ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Preferred Language' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo __($profile->language);?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->relationship ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Relationship status' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->relationship_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->work_status ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Work status' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->work_status_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->education ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Education Level' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->education_txt;?></p>
											</div>
										</div>
										<?php } ?>

										<?php
										$general_fields = GetProfileFields('general');
										$general_custom_data = UserFieldsData($profile->id);
										if (count($general_fields) > 0) {
											foreach ($general_fields as $key => $field) {
												if($field['profile_page'] == 1) {
													if( isset($general_custom_data[$field['fid']]) && $general_custom_data[$field['fid']] !== '' ) {
														echo '<div class="row">';
														echo '    <div class="col s6"><p class="info_title">' . $field['name'] . '</p></div>';
														if ($field['select_type'] == 'yes') {
															$options = @explode(',', $field['type']);
															array_unshift($options,"");
															unset($options[0]);
															if (isset($options[$general_custom_data[$field['fid']]])) {
																echo '    <div class="col s6"><p>' . $options[$general_custom_data[$field['fid']]] . '</p></div>';
															} else {
																echo '    <div class="col s6"><p>' . $general_custom_data[$field['fid']] . '</p></div>';
															}
														} else {
															echo '    <div class="col s6"><p>' . $general_custom_data[$field['fid']] . '</p></div>';
														}
														echo '</div>';
													}
												}
											}
										}
										?>

									</div>
								</div>
							<?php } ?>
							
							<?php if( !empty( $profile->ethnicity ) || !empty( $profile->body ) || !empty( $profile->height ) || !empty( $profile->hair_color ) ) {?>
								<div class="col m6">
									<div class="dt_profile_info">
										<h5><svg xmlns="http://www.w3.org/2000/svg" width="29.585" height="27.208" viewBox="0 0 29.585 27.208"> <g id="Group_8837" data-name="Group 8837" transform="translate(-580.386 -196.85)"> <circle id="Ellipse_332" data-name="Ellipse 332" cx="11" cy="11" r="11" transform="translate(584 201)" fill="currentColor" opacity="0.5"></circle> <path id="Path_215764" data-name="Path 215764" d="M580.386,224.058h8.733s-2.744-17.216,6.238-18.214a76.247,76.247,0,0,1,0-8.982s-11.214-.739-13.748,9.482C580.561,210.974,580.386,224.058,580.386,224.058Z" fill="currentColor"></path> <path id="Path_215765" data-name="Path 215765" d="M595.356,224.058h-8.733s2.744-17.216-6.238-18.214a76.247,76.247,0,0,0,0-8.982s11.214-.739,13.748,9.482C595.182,210.974,595.356,224.058,595.356,224.058Z" transform="translate(14.614)" fill="currentColor"></path> </g> </svg>&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="26.014" height="27.384" viewBox="0 0 26.014 27.384"> <g id="Group_8836" data-name="Group 8836" transform="translate(-619.841 -196.85)"> <circle id="Ellipse_333" data-name="Ellipse 333" cx="11" cy="11" r="11" transform="translate(622 202.234)" fill="currentColor" opacity="0.5"></circle> <path id="Path_215766" data-name="Path 215766" d="M619.612,212s-5.182-15.272,11.324-17.384c1.919,7.869,1.919,9.213,1.919,9.213l-8.637,1.344-1.152,7.869Z" transform="translate(1 2.234)" fill="currentColor"></path> <path id="Path_215767" data-name="Path 215767" d="M632.084,212s5.182-15.272-11.324-17.384c-1.919,7.869-1.919,9.213-1.919,9.213l8.637,1.344,1.152,7.869Z" transform="translate(13 2.234)" fill="currentColor"></path> </g> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __( 'Looks' );?></h5>

										<?php if( !empty( $profile->ethnicity ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Ethnicity' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->ethnicity_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->body ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Body Type' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->body_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->height ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Height' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->height;?>cm</p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->hair_color ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Hair color' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->hair_color_txt;?></p>
											</div>
										</div>
										<?php } ?>

									</div>
								</div>
							<?php } ?>
							
							<?php if( !empty( $profile->character ) || !empty( $profile->children ) || !empty( $profile->friends ) || !empty( $profile->pets ) ) {?>
								<div class="col m6">
									<div class="dt_profile_info">
										<h5><svg xmlns="http://www.w3.org/2000/svg" width="21.405" height="23.299" viewBox="0 0 21.405 23.299"> <path id="Path_5417" data-name="Path 5417" d="M3710.164,10486.625v1.147a19.122,19.122,0,0,1-2.192,8.952l-.264.574-2.008-1.147a17.086,17.086,0,0,0,2.157-7.8l.012-.574v-1.147Zm-6.886-3.443h2.3v5.051a14.6,14.6,0,0,1-3.1,8.608l-.264.344-1.779-1.378a12.646,12.646,0,0,0,2.835-7.574l.012-.46Zm1.147-4.591a5.5,5.5,0,0,1,4.063,1.722,5.654,5.654,0,0,1,1.676,4.018h-2.3a3.443,3.443,0,0,0-6.887,0v3.442a10.6,10.6,0,0,1-2.6,6.887l-.241.229-1.664-1.606a7.844,7.844,0,0,0,2.2-5.165l.011-.345v-3.442a5.654,5.654,0,0,1,1.676-4.018A5.5,5.5,0,0,1,3704.426,10478.591Zm0-4.591a10.2,10.2,0,0,1,7.3,2.983,10.421,10.421,0,0,1,3.03,7.347v3.442a23.806,23.806,0,0,1-.688,5.739l-.161.573-2.215-.573a20.538,20.538,0,0,0,.758-5.051l.011-.688v-3.442a8.125,8.125,0,0,0-1.194-4.247,8.334,8.334,0,0,0-3.236-2.983,9.28,9.28,0,0,0-4.315-.8,7.686,7.686,0,0,0-4.1,1.606l-1.641-1.606A10.216,10.216,0,0,1,3704.426,10474Zm-8.068,3.9,1.629,1.606a8.222,8.222,0,0,0-1.6,4.591v2.525a8.235,8.235,0,0,1-.872,3.673l-.172.345-2-1.148a5.921,5.921,0,0,0,.746-2.524v-2.64A10.347,10.347,0,0,1,3696.357,10477.9Z" transform="translate(-3693.35 -10474)" fill="currentColor"></path> </svg>&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="21.149" height="21.001" viewBox="0 0 21.149 21.001"> <path id="Path_4935" data-name="Path 4935" d="M3416.05,1822.683h5.824a19.048,19.048,0,0,0,3.1,9.438,10.65,10.65,0,0,1-8.927-9.438Zm0-2.125a10.65,10.65,0,0,1,8.927-9.438,19.048,19.048,0,0,0-3.1,9.438Zm21.149,0h-5.824a19.043,19.043,0,0,0-3.1-9.438,10.65,10.65,0,0,1,8.927,9.438Zm0,2.125a10.65,10.65,0,0,1-8.927,9.438,19.043,19.043,0,0,0,3.1-9.438Zm-13.2,0h5.25a4.46,4.46,0,1,1-5.25,0Zm0-2.125a4.46,4.46,0,1,1,5.25,0Z" transform="translate(-3416.05 -1811.12)" fill="currentColor"></path> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __( 'Personality' );?></h5>

										<?php if( !empty( $profile->character ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Character' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->character_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->children ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Children' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->children_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->friends ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Friends' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->friends_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->pets ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Pets' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->pets_txt;?></p>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							
							<?php if( !empty( $profile->live_with ) || !empty( $profile->car ) || !empty( $profile->religion ) || !empty( $profile->smoke ) || !empty( $profile->drink ) || !empty( $profile->travel ) ) {?>
								<div class="col m6">
									<div class="dt_profile_info">
										<h5><svg xmlns="http://www.w3.org/2000/svg" width="56.643" height="29.066" viewBox="0 0 56.643 29.066"> <g id="Group_8840" data-name="Group 8840" transform="translate(-1169.067 -190.435)"> <path id="Union_8" data-name="Union 8" d="M6381.07,24H6356l.008-.006a42.8,42.8,0,0,1,11.924-4.657V11.5h2v3h0V18.9a48.431,48.431,0,0,1,9.18-.886c.651,0,1.312.013,1.961.04V24Zm-19.049-13.372A4.345,4.345,0,0,0,6358.962,12h-.032V11a10.008,10.008,0,0,1,9-9.95V1a1.033,1.033,0,0,1,.29-.709,1.015,1.015,0,0,1,1.42,0,1.037,1.037,0,0,1,.288.709v.051a10.009,10.009,0,0,1,9,9.95v.946a5.576,5.576,0,0,0-3.417-1.228,4.639,4.639,0,0,0-3.1,1.132,4.86,4.86,0,0,0-7.062.149A5.3,5.3,0,0,0,6362.021,10.629Z" transform="translate(-5185.932 195.5)" fill="currentColor"></path> <path id="Path_215769" data-name="Path 215769" d="M1170.067,188.435V194.9s6.116-.961,7.165-6.465Z" transform="translate(-1 2)" fill="currentColor"></path> <path id="Path_6517" data-name="Path 6517" d="M3645.71,6525.79A3.364,3.364,0,0,0,3648,6527a3.237,3.237,0,0,0,1.24-.28l.39-.15a5.7,5.7,0,0,1,2.37-.57,5.157,5.157,0,0,1,3.49,1.58l.22.21-1.42,1.42A3.364,3.364,0,0,0,3652,6528a3.237,3.237,0,0,0-1.24.28l-.39.15a5.7,5.7,0,0,1-2.37.57,5.157,5.157,0,0,1-3.49-1.58l-.22-.21ZM3642,6511a3,3,0,0,0,6,0h6a.99.99,0,0,1,1,1v7a.99.99,0,0,1-1,1h-4v-2h3v-5h-3.42l-.01.04a4.978,4.978,0,0,1-4.35,2.95l-.22.01a5.027,5.027,0,0,1-2.72-.8,5.106,5.106,0,0,1-1.85-2.16l-.01-.04H3637v5h3v9h3v2h-4a.99.99,0,0,1-1-1v-8h-2a.99.99,0,0,1-1-1v-7a.99.99,0,0,1,1-1Zm3.71,10.79A3.364,3.364,0,0,0,3648,6523a3.237,3.237,0,0,0,1.24-.28l.39-.15a5.7,5.7,0,0,1,2.37-.57,5.157,5.157,0,0,1,3.49,1.58l.22.21-1.42,1.42A3.364,3.364,0,0,0,3652,6524a3.237,3.237,0,0,0-1.24.28l-.39.15a5.7,5.7,0,0,1-2.37.57,5.157,5.157,0,0,1-3.49-1.58l-.22-.21Z" transform="translate(-2430 -6312.15)" fill="currentColor"></path> </g> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __( 'Lifestyle' );?></h5>

										<?php if( !empty( $profile->live_with ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'I live with' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->live_with_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->car ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Car' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->car_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->religion ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Religion' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->religion_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->smoke ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Smoke' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->smoke_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->drink ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Drink' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->drink_txt;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->travel ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Travel' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->travel_txt;?></p>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							
							<?php if( !empty( $profile->music ) || !empty( $profile->dish ) || !empty( $profile->song ) || !empty( $profile->hobby ) || !empty( $profile->city ) || !empty( $profile->sport ) || !empty( $profile->book ) || !empty( $profile->movie ) || !empty( $profile->colour ) || !empty( $profile->tv ) ) {?>
								<div class="col m6">
									<div class="dt_profile_info">
										<h5><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0H24V24H0z"/><path fill="currentColor" d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228z"/></svg>&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14.6 8H21a2 2 0 0 1 2 2v2.104a2 2 0 0 1-.15.762l-3.095 7.515a1 1 0 0 1-.925.619H2a1 1 0 0 1-1-1V10a1 1 0 0 1 1-1h3.482a1 1 0 0 0 .817-.423L11.752.85a.5.5 0 0 1 .632-.159l1.814.907a2.5 2.5 0 0 1 1.305 2.853L14.6 8zM7 10.588V19h11.16L21 12.104V10h-6.4a2 2 0 0 1-1.938-2.493l.903-3.548a.5.5 0 0 0-.261-.571l-.661-.33-4.71 6.672c-.25.354-.57.644-.933.858zM5 11H3v8h2v-8z"/></svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __( 'Favourites' );?></h5>

										<?php if( !empty( $profile->music ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Music Genre' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->music;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->dish ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Dish' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->dish;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->song ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Song' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->song;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->hobby ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Hobby' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->hobby;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->city ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'City' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->city;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->sport ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Sport' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->sport;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->book ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Book' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->book;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->movie ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Movie' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->movie;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->colour ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'Color' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->colour;?></p>
											</div>
										</div>
										<?php } ?>
										<?php if( !empty( $profile->tv ) ) {?>
										<div class="row">
											<div class="col s6">
												<p class="info_title"><?php echo __( 'TV Show' );?></p>
											</div>
											<div class="col s6">
												<p><?php echo $profile->tv;?></p>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							
							<div class="col m6">
                            <?php
                            $is_show_title = false;
                            $_profile_custom_data = '';
                            $profile_fields = GetProfileFields('profile');
                            $profile_custom_data = UserFieldsData($profile->id);
                            if (count($profile_fields) > 0) {
                                foreach ($profile_fields as $key => $field) {
                                    if($field['profile_page'] == 1) {
                                        if( isset($profile_custom_data[$field['fid']]) && $profile_custom_data[$field['fid']] !== null ) {
                                            $is_show_title = true;
                                            if( !empty( $profile_custom_data[$field['fid']] ) ) {
												$_profile_custom_data .= '<div class="dt_profile_info">';
                                                $_profile_custom_data .= '<div class="row">';
                                                $_profile_custom_data .= '    <div class="col s6"><p class="info_title">' . __( $field['name'] ) . '</p></div>';
                                                if ($field['select_type'] == 'yes') {
                                                    $profile_options = @explode(',', $field['type']);
                                                    array_unshift($profile_options, "");
                                                    unset($profile_options[0]);
                                                    if (isset($profile_options[$profile_custom_data[$field['fid']]])) {
                                                        $_profile_custom_data .= '    <div class="col s6"><p>' . $profile_options[$profile_custom_data[$field['fid']]] . '</p></div>';
                                                    } else {
                                                        $_profile_custom_data .= '    <div class="col s6"><p>' . $profile_custom_data[$field['fid']] . '</p></div>';
                                                    }
                                                } else {
                                                    $_profile_custom_data .= '    <div class="col s6"><p>' . $profile_custom_data[$field['fid']] . '</p></div>';
                                                }
                                                $_profile_custom_data .= '</div>';
												$_profile_custom_data .= '</div>';
                                            }
                                        }
                                    }
                                }
                            }
							
                            if($is_show_title == true){
                                echo '<h5><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"> <path id="Path_215771" data-name="Path 215771" d="M3253,6250a10,10,0,1,1,10-10A10,10,0,0,1,3253,6250Zm2-12v4h2v-4Zm-6,0v4h2v-4Z" transform="translate(-3243 -6230)" fill="currentColor"></path> </svg>&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"> <path id="Path_215772" data-name="Path 215772" d="M3757,6418a10,10,0,1,1,10-10A10,10,0,0,1,3757,6418Zm-1-15v4h2v-4Zm3,5v4h2v-4Zm-6,0v4h2v-4Z" transform="translate(-3747 -6398)" fill="currentColor"></path> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. __( 'Other' ) .'</h5>';
                            }
                            echo $_profile_custom_data;
                            ?>

						</div>
						</div>
                    </div>
                </div>
            <!-- End Right Main Area -->

		</div>
		
		<div class="col l3">
			<?php //if (!IS_LOGGED || (IS_LOGGED && !empty(auth()) && auth()->is_pro == 0)) { ?>
				<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
			<?php //} ?>
		</div>
	</div>
</div>
<!-- End Profile  -->

<?php if( isset($_GET['accepted']) ) {?>
<script>
    $( document ).ready(function() {
        $('#btn_open_private_conversation').trigger('click');
    });
    $( window ).on( "load", function() {
        $('#btn_open_private_conversation').trigger('click');
    });
</script>
<?php }?>

<?php if( route(2) == 'chat_request' ) {
global $db;

$is_request_exist = (int)$db->where('url','/' . route(1) . '/' . route(2))->where('recipient_id',auth()->id)->getOne('notifications','id')['id'];
if($is_request_exist > 0){
?>
<script>
    $( document ).ready(function() {
        chat_request_mode();
    });
    $( window ).on( "load", function() {
        chat_request_mode();
    });
</script>
<?php }}?>

<div id="modal_report" class="modal modal-sm">
    <div class="modal-content">
		<h6 class="bold no_margin_top"><?php echo __( 'Report user.' );?></h6>
		<textarea id="report_content" name="report_content" class="materialize-textarea report_user_report" autofocus placeholder="<?php echo __( 'Type here why you want to report this user.' );?>"></textarea>
		<div class="modal-footer">
			<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
			<button id="send_report_btn" data-userid="<?php echo $profile->id;?>" data-webdeviceid="<?php echo $profile->web_device_id;?>" class="waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Report' );?></button>
		</div>
    </div>
</div>

<!-- Gifts Modal -->
<div id="modal_gifts" class="modal modal-lg modal-fixed-footer">
    <div class="modal-content">
        <h6 class="bold"><?php echo __( 'Send gift costs: ' ) . ' ' . $config->cost_per_gift . ' ' . __( 'credits' );?></h6>
        <?php if($user->balance >= $config->cost_per_gift || isGenderFree($user->gender) === true){?>
        <div id="gifts_container" <?php if($user->balance >= $config->cost_per_gift || isGenderFree($user->gender) === true ){}else{echo 'class="hide"';}?>></div>
        <?php }else{ ?>
        <div id="buy_credits_gift" <?php if($user->balance >= $config->cost_per_gift  || isGenderFree($user->gender) === true ){echo 'class="hide"';}else{}?>>
            <div class="credit_bln" style="margin-top: 130px;">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,17V16H9V14H13V13H10A1,1 0 0,1 9,12V9A1,1 0 0,1 10,8H11V7H13V8H15V10H11V11H14A1,1 0 0,1 15,12V15A1,1 0 0,1 14,16H13V17H11Z"></path></svg>
                    <h2><?php echo __( 'Your' );?> <?php echo ucfirst( $config->site_name );?> <?php echo __( 'Credits balance' );?></h2>
                    <p><span>0</span> <?php echo __( 'Credits' );?></p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php if($user->balance >= $config->cost_per_gift  || isGenderFree($user->gender) === true ){?>
    <div class="modal-footer" id="send_gift_footer">
        <button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
        <button data-to="<?php echo $profile->id;?>" class="waves-effect waves-green btn-flat bold" disabled id="btn-send-gift" data-selected=""><?php echo __( 'Send' );?></button>
    </div>
    <?php } else { ?>
    <div class="modal-footer" id="send_gift_footer">
        <button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
        <a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Credits' );?></a>
    </div>
    <?php } ?>
</div>
<!-- End Gifts Modal -->

    <!-- gift Modal -->
<?php
if( route(2) == 'opengift' && is_numeric(route(3)) ) {
    global $db;
    $gifts = $db->objectBuilder()
        ->where('ug.id', (int)route(3) )
        ->join('gifts g', 'ug.gift_id=g.id', 'LEFT')
        ->get('user_gifts ug', 1, array('ug.id', 'ug.`from`', 'ug.gift_id', 'g.media_file'));
    if ($gifts) {
        $gift_sender = null;
        foreach ($gifts as $key => $value) {
            $gift_sender = userData($value->from, array('first_name', 'last_name', 'username', 'avater'));
            ?>
            <div class="received_gift_modal hide" data-gift-id="<?php echo $value->id; ?>">
                <div class="modal-content">
                    <h6 class="bold no_margin">
                        <?php echo '' . $gift_sender->username . ' <span>' . __('send a gift to you.') . '</span>'; ?>
                    </h6>
                    <div id="gifts_container" style="height: calc(100% - 20px);">
                        <img src="<?php echo GetMedia($value->media_file); ?>" alt="<?php echo $gift_sender->first_name . ' ' . $gift_sender->last_name; ?>" style="height: 100%;margin: auto;border-radius: 5px;object-fit: cover;max-height: 305px;"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);"
                       class="modal-close waves-effect btn-flat"><?php echo __('Close'); ?></a>
                </div>
            </div>
            <?php
        }
    }
}
?>

<?php if( route(2) == 'story' && is_numeric(route(3)) ) {
    $story      = $db->where('id', Secure((int)route(3)) )->getOne('success_stories',array('*'));
    if( $story ){
        $userData = userData($story['user_id']);
?>
    <div id="story_approval" class="modal modal-fixed-footer" style="width: 30%;">
        <div class="modal-content">
            <h6 class="bold center"><?php echo __('You have story with') . ' ' . $userData->fullname . ' ' . __('on') . ' ' . $story['story_date'];?></h6>
            <p class="center"><?php echo br2nl( html_entity_decode( $story['quote'] ));?></p>
            <div class="storydesc"><?php echo br2nl( html_entity_decode( $story['description'] ));?></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-flat waves-effect modal-close left"><?php echo __( 'Cancel' );?></button>
            <a href="javascript:void(0);" id="disapprove_story" data-storyid="<?php echo route(3);?>" data-story-userid="<?php echo $user->id;?>" data-story-to-userid="<?php echo $profile->id;?>" class="modal-close waves-effect waves-light btn-flat grey darken-1 white-text"><?php echo __( 'Disapprove story' );?></a>&nbsp;&nbsp;
            <a href="javascript:void(0);" id="approve_story" data-storyid="<?php echo route(3);?>" data-story-userid="<?php echo $user->id;?>" data-story-to-userid="<?php echo $profile->id;?>" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Approve story' );?></a>
        </div>
    </div>
<?php }}?>

    <!-- End gift Modal -->

    <!-- Buy Chat Credits Modal -->
    <div id="buy_chat_credits" class="modal" style="width: 30%;">
        <div class="modal-content">
            <h6 class="bold"><?php echo __('Chat');?></h6>
            <?php
            $lastchat = GetLastChat($user->id);
            if( $lastchat > 0 ){
                $plusday = ( $lastchat + ( 60 * 60 * 24 ) ) - time();
            }
            ?>
            <p><?php echo __("You have reached your daily limit") . ', '. __("you can chat to new people after") . ' ' . '<span id="chat_time" data-chat-time="'.$plusday.'" style="color:#a33596;font-weight: bold;"></span>' .', '. __("can't wait? this service costs you") . ' <span style="color:#a33596;font-weight: bold;">' . (int)$config->not_pro_chat_credit . '</span> ' . __( 'Credits') . '.';?></p>
            <div class="modal-footer">
                    <button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
                    <?php if((int)$user->balance >= (int)$config->not_pro_chat_credit ){?>
                        <button data-userid="<?php echo $user->id;?>" data-chat-userid="<?php echo $profile->id;?>" id="btn_buymore_chat_credit" class="waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Now.' );?></button>
                    <?php }else{ ?>
                        <a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Credits' );?></a>
                    <?php } ?>
                </div>
        </div>
    </div>
    <!-- End Buy Chat Credits Modal -->

<?php

//    ignore_user_abort();
//    flush();
//    session_write_close();
//    if (is_callable('fastcgi_finish_request')) {
//        fastcgi_finish_request();
//    }
    if( $user !== null ) {
        global $db, $config;
        $lastTime = $db->objectBuilder()
                        ->where('user_id', $user->id)
                        ->where('view_userid', $profile->id)
                        ->orderBy('created_at', 'DESC')
                        ->getOne('views', array('TIMESTAMPDIFF(MINUTE,views.created_at,NOW())%60 as lastTime'));
        $can_insert = false;
        if (isset($lastTime->lastTime) && $lastTime->lastTime > $config->profile_record_views_minute) {
            $can_insert = true;
        }
        if ($lastTime === null) {
            $can_insert = true;
        }
        if ($can_insert === true) {
            if ($user->id !== $profile->id) {
                if( $user->id !== '' && $profile->id !== '' ){
                             $db->where('user_id' , $user->id)->where('view_userid' , $profile->id)->delete('views');
                             $db->where('notifier_id' , $user->id)->where('recipient_id' , $profile->id)->where('type' , 'visit')->delete('notifications');
                    $saved = $db->insert('views', array('user_id' => $user->id, 'view_userid' => $profile->id, 'created_at' => date('Y-m-d H:i:s')));
                    if( $saved ) {
                        $Notification = LoadEndPointResource('Notifications');
                        if($Notification) {
                            $Notification->createNotification($profile->web_device_id, $user->id, $profile->id, 'visit', '', '/@' . $user->username);
                        }
                    }
                }
            }
        }
    }



    if(( Wo_IsFollowRequested( $profile->id, (int) $user->id  ) ) ){
    ?>
        <div id="story_approval" class="modal" style="width: 30%;">
            <div class="modal-content">
				<div class="center">
					<svg version="1.1" xml:space="preserve" width="80" height="80" viewBox="0 0 682.66669 682.66669" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs1366"><clipPath clipPathUnits="userSpaceOnUse" id="clipPath1376"><path d="M 0,512 H 512 V 0 H 0 Z" id="path1374" /></clipPath></defs><g id="g1368" transform="matrix(1.3333333,0,0,-1.3333333,0,682.66667)"><g id="g1370"><g id="g1372" clip-path="url(#clipPath1376)"><g id="g1378" transform="translate(453.1699,99.5674)"><path d="m 0,0 c 30.053,40.677 47.83,90.977 47.83,145.433 0,135.309 -109.69,245 -245,245 -135.309,0 -245,-109.691 -245,-245 0,-45.685 12.518,-88.44 34.291,-125.047 z" style="fill:#fce453;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1380" /></g><g id="g1382" transform="translate(411.5215,172.1045)"><path d="m 0,0 0.006,0.014 c -1.358,0.646 -3.166,1.598 -6.31,2.83 -64.67,28.749 -72.122,27.459 -78.677,29.647 0.198,-0.194 0.375,-0.411 0.505,-0.671 -0.231,-1.939 1.012,-1.621 -3.543,-13.331 -9.441,-23.426 -26.606,-43.999 -46.5,-59.73 -2.008,-1.587 -4.79,-1.693 -6.92,-0.27 -3.431,2.291 -9.47,-19.35 -9.143,-25.491 0.116,-3.685 0.028,0.699 0.06,-49.368 0,-1.973 -1.635,-3.578 -3.644,-3.578 h -20.419 c -2.008,0 -3.643,1.605 -3.643,3.578 0.031,50.066 -0.056,45.688 0.06,49.368 0.002,0.044 0.001,0.088 -0.002,0.132 -0.841,10.825 -6.04,27.43 -9.142,25.359 -2.13,-1.423 -4.911,-1.317 -6.92,0.27 -19.893,15.731 -37.06,36.304 -46.5,59.73 -4.556,11.71 -3.311,11.392 -3.543,13.331 0.131,0.26 0.308,0.477 0.505,0.671 -6.556,-2.188 -14.006,-0.898 -78.676,-29.647 -3.144,-1.232 -4.954,-2.184 -6.311,-2.83 L -328.75,0 c -21.146,-10.886 -32.302,-30.993 -38.09,-51.121 42.574,-72.388 121.265,-120.983 211.319,-120.983 81.282,0 153.304,39.592 197.873,100.532 C 38.812,-45.956 28.437,-14.64 0,0" style="fill:#e779b5;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1384" /></g><g id="g1386" transform="translate(113.5234,88.125)"><path d="M 0,0 C 3.352,11.063 11.209,20.201 21.644,25.174 L 79.745,52.861 V 65.88 c -9.355,11.049 -17.149,23.363 -22.479,36.589 -4.555,11.71 -3.311,11.391 -3.542,13.331 0.13,0.26 0.307,0.476 0.505,0.671 -6.556,-2.189 -14.007,-0.899 -78.677,-29.648 -3.144,-1.231 -4.953,-2.183 -6.311,-2.83 l 0.007,-0.014 c -21.145,-10.885 -32.301,-30.993 -38.09,-51.121 15.22,-25.879 35.059,-48.714 58.381,-67.379 z" style="fill:#e25ca5;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1388" /></g><g id="g1390" transform="translate(187.2915,221.1045)"><path d="M 0,0 1.084,-5.97 1.118,-6.111 C 1.432,-7.483 1.79,-8.865 2.18,-10.217 l 0.061,-0.205 c 0.131,-0.45 0.269,-0.909 0.41,-1.358 l 0.086,-0.273 c 0.15,-0.471 0.305,-0.95 0.473,-1.451 l 0.057,-0.164 c 3.571,-10.488 11.373,-25.775 28.247,-39.04 4.374,-3.438 9.153,-6.6 14.207,-9.395 2.269,-1.254 3.923,-3.163 4.782,-5.519 0.057,-0.156 0.144,-0.301 0.254,-0.426 2.59,-2.915 5.066,-6.12 7.357,-9.526 0.495,-0.733 1.708,-0.733 2.2,0 2.282,3.394 4.763,6.604 7.374,9.541 0.112,0.124 0.197,0.268 0.254,0.425 0.863,2.35 2.515,4.254 4.776,5.505 36.286,20.071 44.089,49.654 45.749,62.32 -4.44,10.168 -5.859,22.739 -6.191,32.387 C 106.917,26.362 101.292,21.143 95.918,16.969 85.448,8.839 72.528,4.422 59.221,4.422 45.913,4.422 32.993,8.839 22.523,16.969 17.148,21.143 11.524,26.363 6.165,32.605 5.833,22.956 4.414,10.385 -0.027,0.216 Z" style="fill:#fca870;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1392" /></g><g id="g1394" transform="translate(209.8145,238.0732)"><path d="m 0,0 c -5.375,4.174 -10.999,9.395 -16.358,15.637 -0.331,-9.65 -1.751,-22.221 -6.192,-32.39 l 0.027,-0.216 1.085,-5.969 0.033,-0.142 c 0.315,-1.372 0.671,-2.754 1.062,-4.106 l 0.061,-0.205 c 0.131,-0.45 0.269,-0.909 0.41,-1.358 l 0.086,-0.272 c 0.15,-0.472 0.305,-0.951 0.473,-1.452 l 0.057,-0.164 c 3.572,-10.488 11.373,-25.775 28.247,-39.04 1.375,-1.081 2.804,-2.124 4.256,-3.148 14.796,24.917 19.544,48.056 21.067,60.328 C 21.854,-12.003 9.844,-7.644 0,0" style="fill:#f9955f;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1396" /></g><g id="g1398" transform="translate(228.7676,505.4287)"><path d="m 0,0 c -53.285,-7.261 -58.349,-37.472 -58.373,-48.556 -0.005,-2.324 -1.293,-4.478 -3.383,-5.495 -27.717,-13.483 -12.773,-78.468 -12.773,-78.468 2.783,-0.185 5.696,-1.313 7.87,-2.365 0.532,3.312 1.4,6.572 2.627,9.723 0.935,2.401 2.103,4.827 3.564,7.119 2.535,3.977 3.689,8.667 3.345,13.357 -0.996,13.557 -0.517,40.271 17.378,48.323 15.161,6.821 33.757,2.871 45.617,-1.073 7.701,-2.561 16.044,-2.561 23.745,0 11.86,3.944 30.456,7.894 45.616,1.073 17.844,-8.03 18.372,-34.615 17.388,-48.206 -0.343,-4.727 0.78,-9.466 3.335,-13.473 1.508,-2.366 2.704,-4.875 3.654,-7.352 1.18,-3.079 2.02,-6.26 2.538,-9.491 2.74,1.327 6.655,2.773 10.003,2.299 0.411,14.209 1.722,29.527 1.722,29.527 C 129.664,-25.719 57.373,7.816 0,0" style="fill:#0052a9;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1400" /></g><g id="g1402" transform="translate(228.7676,505.4287)"><path d="m 0,0 c -53.285,-7.261 -58.349,-37.472 -58.373,-48.556 -0.005,-2.324 -1.293,-4.478 -3.382,-5.495 -27.718,-13.483 -12.774,-78.468 -12.774,-78.468 2.783,-0.185 5.696,-1.313 7.87,-2.365 0.532,3.312 1.4,6.572 2.627,9.723 0.935,2.401 2.103,4.827 3.564,7.119 2.535,3.977 3.689,8.667 3.345,13.357 -0.996,13.557 -0.516,40.271 17.379,48.323 13.673,6.152 30.139,3.542 41.939,0.077 -0.121,0.265 -0.234,0.53 -0.36,0.796 C -21.065,-6.994 15.983,0.766 17.678,1.094 11.705,1.147 5.776,0.787 0,0" style="fill:#003370;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1404" /></g><g id="g1406" transform="translate(346.9072,370.998)"><path d="m 0,0 c -4.149,3.804 -12.417,1.876 -15.991,-0.453 0.783,-4.876 0.828,-9.865 0.151,-14.791 l -4.218,-30.721 c 0,-2.476 -0.078,-4.906 -0.221,-7.294 10.285,-3.101 15.394,6.132 16.255,12.469 0.371,2.727 0.976,5.416 1.816,8.04 C 0.528,-24.205 10.398,-9.529 0,0" style="fill:#fca870;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1408" /></g><g id="g1410" transform="translate(337.3857,342.3867)"><path d="m 0,0 c -2.858,0 -5.54,0.743 -7.874,2.038 l -2.663,-19.392 c 0,-2.476 -0.077,-4.906 -0.221,-7.293 10.285,-3.102 15.395,6.131 16.255,12.468 0.37,2.728 0.977,5.416 1.816,8.04 0.74,2.308 1.999,5.064 3.238,8.044 C 7.711,1.476 4.03,0 0,0" style="fill:#f9955f;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1412" /></g><g id="g1414" transform="translate(146.1162,370.998)"><path d="m 0,0 c 4.15,3.804 12.417,1.876 15.991,-0.453 -0.783,-4.876 -0.827,-9.865 -0.151,-14.791 l 4.219,-30.721 c 0,-2.476 0.078,-4.906 0.221,-7.294 C 9.995,-56.36 4.885,-47.127 4.025,-40.79 3.654,-38.063 3.048,-35.374 2.208,-32.75 -0.528,-24.205 -10.398,-9.529 0,0" style="fill:#fca870;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1416" /></g><g id="g1418" transform="translate(163.374,345.4306)"><path d="m 0,0 c -2.664,-1.908 -5.919,-3.044 -9.446,-3.044 -2.96,0 -5.727,0.804 -8.117,2.187 0.987,-2.322 1.919,-4.468 2.514,-6.326 0.84,-2.624 1.445,-5.312 1.816,-8.04 0.86,-6.337 5.97,-15.57 16.255,-12.468 -0.143,2.387 -0.221,4.817 -0.221,7.294 z" style="fill:#f9955f;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1420" /></g><g id="g1422" transform="translate(328.9961,380.2724)"><path d="m 0,0 c -1.048,2.736 -2.298,5.249 -3.713,7.471 -2.422,3.798 -3.57,8.439 -3.234,13.069 1.07,14.778 0.184,40.776 -17.777,48.858 -15.084,6.787 -33.392,3.177 -46.094,-1.047 -7.523,-2.504 -15.809,-2.503 -23.332,0 -12.703,4.224 -31.011,7.837 -46.095,1.047 -7.724,-3.475 -20.302,-14.46 -17.768,-48.976 0.335,-4.547 -0.817,-9.147 -3.241,-12.952 -1.376,-2.158 -2.595,-4.592 -3.624,-7.234 -3.012,-7.74 -3.987,-16.328 -2.819,-24.839 l 4.213,-30.636 c 0,-50.738 30.698,-80.052 43.896,-90.3 10.534,-8.182 23.712,-12.688 37.104,-12.688 13.392,0 26.57,4.506 37.104,12.688 13.198,10.248 43.896,39.563 43.889,90.209 l 4.219,30.722 C 3.885,-16.184 2.941,-7.673 0,0" style="fill:#f9bd91;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1424" /></g><g id="g1426" transform="translate(244.8506,234.7334)"><path d="m 0,0 c -9.389,10.248 -31.227,39.563 -31.227,90.3 l -2.997,30.637 c -0.83,8.51 -0.137,17.098 2.006,24.838 0.732,2.642 1.599,5.077 2.577,7.234 1.725,3.805 2.545,8.405 2.307,12.952 -1.803,34.517 7.145,45.501 12.639,48.976 0.148,0.094 0.299,0.179 0.447,0.269 -12.327,3.559 -28.374,5.798 -41.852,-0.269 -7.724,-3.475 -20.302,-14.459 -17.767,-48.976 0.334,-4.547 -0.817,-9.147 -3.242,-12.952 -1.375,-2.157 -2.595,-4.592 -3.623,-7.234 -3.013,-7.74 -3.988,-16.328 -2.819,-24.838 L -79.338,90.3 c 0,-50.737 30.697,-80.052 43.896,-90.3 10.533,-8.182 23.711,-12.688 37.104,-12.688 4.885,0 9.74,0.607 14.451,1.766 C 10.279,-8.904 4.759,-5.197 0,0" style="fill:#fca870;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1428" /></g><g id="g1430" transform="translate(247.4434,142.8603)"><path d="m 0,0 c -7.232,11.178 -14.362,17.047 -14.362,17.047 l -18.423,-29.589 c 13.272,-8.543 15.643,-29.412 16.065,-36.136 C -15.619,-27.069 -7.435,-10.646 0,0" style="fill:#c6237c;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1432" /></g><g id="g1434" transform="translate(262.207,51.0771)"><path d="m 0,0 v 40.969 c 0,0 0.23,28.021 16.157,38.272 L -2.266,108.83 c 0,0 -29.356,-24.121 -29.356,-67.861 V 0 Z" style="fill:#dd3f95;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1436" /></g><g id="g1438" transform="translate(233.6543,160.1621)"><path d="m 0,0 c -36.742,20.32 -43.823,50.573 -45.182,62.161 -0.24,2.052 -2.616,3.067 -4.282,1.845 l -24.87,-18.235 c -1.328,-0.973 -1.965,-2.653 -1.564,-4.249 9.895,-39.385 41.705,-68.143 55.727,-79.23 3.569,-2.822 8.775,-2.004 11.315,1.771 3.592,5.338 8.673,12.889 12.866,19.121 C 7.926,-10.998 6.137,-3.396 0,0" style="fill:#e25ca5;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1440" /></g><g id="g1442" transform="translate(219.6255,169.4365)"><path d="m 0,0 c -24.607,19.343 -29.989,42.961 -31.153,52.887 -0.241,2.051 -2.616,3.066 -4.282,1.843 L -60.306,36.497 c -1.327,-0.974 -1.964,-2.653 -1.563,-4.249 6.332,-25.204 21.636,-46.045 35.512,-60.698 C -15.342,7.712 -3.055,2.111 0,0" style="fill:#dd3f95;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1444" /></g><g id="g1446" transform="translate(220.3481,168.872)"><path d="M 0,0 Z" style="fill:#dd3f95;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1448" /></g><g id="g1450" transform="translate(333.7031,205.9336)"><path d="m 0,0 -24.87,18.234 c -1.666,1.222 -4.041,0.207 -4.282,-1.844 -1.359,-11.588 -8.441,-41.841 -45.182,-62.161 -6.138,-3.396 -7.926,-10.999 -4.011,-16.817 4.194,-6.231 9.275,-13.782 12.866,-19.12 2.541,-3.775 7.747,-4.594 11.316,-1.771 14.021,11.086 45.832,39.844 55.726,79.23 C 1.965,-2.653 1.327,-0.974 0,0" style="fill:#e25ca5;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1452" /></g><g id="g1454" transform="translate(254.1089,103.9922)"><path d="m 0,0 c 0,-3.432 -2.781,-6.213 -6.212,-6.213 -3.432,0 -6.213,2.781 -6.213,6.213 0,3.431 2.781,6.212 6.213,6.212 C -2.781,6.212 0,3.431 0,0" style="fill:#e25ca5;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1456" /></g><g id="g1458" transform="translate(252.7236,73.1777)"><path d="m 0,0 c 0,-3.432 -2.781,-6.213 -6.212,-6.213 -3.431,0 -6.212,2.781 -6.212,6.213 0,3.431 2.781,6.212 6.212,6.212 C -2.781,6.212 0,3.431 0,0" style="fill:#e25ca5;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1460" /></g><g id="g1462" transform="translate(230.585,52.4619)"><path d="m 0,0 v -11.608 c 0,-2.907 2.387,-5.261 5.331,-5.261 h 20.961 c 2.943,0 5.33,2.354 5.33,5.261 V 0 Z" style="fill:#c6237c;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1464" /></g><g id="g1466" transform="translate(482.0488,426.2734)"><path d="m 0,0 c 0,-47.345 -38.381,-85.727 -85.727,-85.727 -47.345,0 -85.726,38.382 -85.726,85.727 0,47.346 38.381,85.727 85.726,85.727 C -38.381,85.727 0,47.346 0,0" style="fill:#e25ca5;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1468" /></g><g id="g1470" transform="translate(463.5303,373.0898)"><path d="M 0,0 C -55.102,-2.656 -54.292,40.268 -54.292,40.268 L -80.124,66.1 c -48.23,25.62 -23.945,64.046 -23.543,64.676 -29.11,-13.702 -49.268,-43.287 -49.268,-77.592 0,-47.345 38.381,-85.727 85.727,-85.727 27.247,0 51.506,12.728 67.208,32.543" style="fill:#dd3f95;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1472" /></g><g id="g1474" transform="translate(432.0352,439.1894)"><path d="m 0,0 h -22.797 v 22.797 c 0,7.133 -5.783,12.916 -12.916,12.916 -7.133,0 -12.916,-5.783 -12.916,-12.916 V 0 h -22.797 c -7.133,0 -12.916,-5.783 -12.916,-12.916 0,-7.134 5.783,-12.916 12.916,-12.916 h 22.797 v -22.797 c 0,-7.133 5.783,-12.916 12.916,-12.916 7.133,0 12.916,5.783 12.916,12.916 v 22.797 H 0 c 7.133,0 12.916,5.782 12.916,12.916 C 12.916,-5.783 7.133,0 0,0" style="fill:#ebf1f7;fill-opacity:1;fill-rule:nonzero;stroke:none" id="path1476" /></g></g></g></g></svg>
				</div>
				<p></p>
                <h6 class="bold center"><?php echo $profile->full_name.$profile->pro_icon . ' ' . __('request your friendship.');?></h6>
                <p class="center"></p>
            </div>
            <div class="modal-footer friend_req_btn">
                <button type="button" class="btn-flat waves-effect modal-close hide"><?php echo __( 'Cancel' );?></button>
				<a href="javascript:void(0);" id="approve_friend_request" data-friend-request-userid="<?php echo $user->id;?>" data-friend-request-to-userid="<?php echo $profile->id;?>" class="modal-close waves-effect waves-light btn-flat btn_primary"><?php echo __( 'Accept request' );?></a>
                <a href="javascript:void(0);" id="disapprove_friend_request" data-friend-request-userid="<?php echo $user->id;?>" data-friend-request-to-userid="<?php echo $profile->id;?>" class="modal-close waves-effect waves-light btn-flat"><?php echo __( 'Decline request' );?></a>
            </div>
        </div>
    <?php
    }