<?php
    global $db;
    $views_count = 0;
    $views = $db->objectBuilder()
                ->where('v.view_userid', $profile->id)
                ->groupBy('v.user_id')
                ->orderBy('v.created_at', 'DESC')
                ->get('views v', null, array('COUNT(DISTINCT v.user_id) AS views'));
    if( $views !== null ){
        $views_count = COUNT($views);
    }
    $likes_count = $db->where('like_userid',$profile->id)->getOne('likes','count(id) as likes')['likes'];

?>
<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>



<div class="container container-fluid container_new find_matches_cont dt_user_profile_parent">
    <!-- display gps not enable message - see header js -->
    <div class="alert alert-warning hide" role="alert" id="gps_not_enabled">
        <p><?php echo __( 'Please Enable Location Services on your browser.' );?></p>
    </div>
    <script>
        var gps_not_enabled = document.querySelector('#gps_not_enabled');
        if( window.gps_is_not_enabled == true ){
            gps_not_enabled.classList.remove('hide');
        }
    </script>
	
	<div class="row r_margin">
		<div class="col l3 profile_menu">
			<div class="dt_left_sidebar dt_profile_side">
				<div class="avatar">
					<?php
					$is_avatar_approved = is_avatar_approved($profile->id, str_replace(array(GetMedia('', false)),array(''),$profile->avater->full));
					if($is_avatar_approved) { ?>
						<a class="inline" href="<?php echo $profile->avater->full; ?>" id="avater_profile_img">
							<img src="<?php echo $profile->avater->avater; ?>" alt="<?php echo $profile->full_name; ?>" class="responsive-img"/>
							<?php if ((int)abs(((strtotime(date('Y-m-d H:i:s')) - $profile->lastseen))) < 60 && (int)$profile->online == 1) {
								echo '<div class="useronline" style="top: 10px;left: 10px;"></div>';
							} ?>
						</a>
					<?php } else {?>
						<div class="dt_usr_undr_rvw">
							<img src="<?php echo $config->uri . '/upload/photos/d-blog.jpg'; ?>" alt="<?php echo $profile->full_name; ?>" class="responsive-img"/>
							<span><?php echo __('Under Review');?></span>
						</div>
					<?php } ?>
					<div class="dt_chng_avtr">
						<span class="btn-upload-image" onclick="document.getElementById('profileavatar_img').click(); return false">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,17H7V3H21M21,1H7A2,2 0 0,0 5,3V17A2,2 0 0,0 7,19H21A2,2 0 0,0 23,17V3A2,2 0 0,0 21,1M3,5H1V21A2,2 0 0,0 3,23H19V21H3M15.96,10.29L13.21,13.83L11.25,11.47L8.5,15H19.5L15.96,10.29Z" /></svg> <?php echo __( 'Change Photo' );?>
						</span>
						<input type="file" id="profileavatar_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="avatar">
					</div>
					<div class="dt_avatar_progress hide">
						<div class="avatar_imgprogress progress">
							<div class="avatar_imgdeterminate determinate" style="width: 0%"></div >
						</div>
					</div>
				</div>
				<div class="dt_othr_ur_info">
					<h2>
						<?php echo $profile->full_name.$profile->pro_icon;?><?php echo ( $profile->age  > 0 ) ? ", ". $profile->age : "";?>
						<?php if( verifiedUser($profile) ){ ?>
							<span tooltip="<?php
							if( $config->image_verification == 1 && $profile->approved_at > 0 ){
								echo __( 'This profile is Verified by photos' );
							}else{
								echo __( 'This profile is Verified by phone' );
							}
							?>" flow="down">
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
				
				<div class="dt_user_pro_info">
                    <ul>
                        <li>
                            <a href="<?php echo $site_url;?>/popularity" data-ajax="/popularity">
                                <?php echo __( 'Increase' );?> <?php echo __( 'Popularity' );?>
                            </a>
                        </li>
                        <?php if( $profile->is_pro == 0 && $config->pro_system == 1 && isGenderFree($profile->gender) === false ){?>
							<li>
								<a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="prem">
									<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M2 19h20v2H2v-2zM2 5l5 3.5L12 2l5 6.5L22 5v12H2V5zm2 3.841V15h16V8.841l-3.42 2.394L12 5.28l-4.58 5.955L4 8.84z" fill="currentColor"/></svg></span> <?php echo __( 'go_premium' );?>
								</a>
							</li>
                        <?php } ?>
                    </ul>
                </div>
				
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
										$interest = trim(  $value  );
										if( $interest !== "" ){
											echo '<a href="'.$site_url.'/interest/'.strtolower($interest).'" data-ajax="/interest/'.strtolower($interest).'"><div class="chip dt_intrst_chip">'.$interest.'</div></a>';
										}
									}
								}
							?>
						</div>
					</div>
				<?php } ?>
				
				<div class="dt_user_prof_complt dt_main_prof_prog">
                    <h5><?php echo __( 'Profile Completion' );?></h5>
					<div class="progresss"></div>
				</div>
				
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
										<a href="https://vk.com/<?php echo $profile->google;?>" target="_blank">
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
			<?php if( $config->image_verification == 0 ){ ?>
				<?php if( verifiedUser($profile) == false ){ ?>
                    <?php if($config->emailValidation == "1"){?>
                    <div class="dt_sections dt_how_to_verfy_alrt">
                        <b>
                            <span><svg enable-background="new 0 0 52 52" height="512" viewBox="0 0 52 52" width="512" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><g id="_x33_3"><path d="m47.8 45.8c1.9-2 3-4.8 2.9-7.5s-1.3-5.4-3.3-7.2-4.6-2.7-7.2-2.5c2.5.2 4.9 1.4 6.5 3.2s2.5 4.2 2.4 6.5-1.1 4.6-2.7 6.2-3.9 2.5-6.2 2.5-4.5-.9-6.2-2.5-2.6-3.8-2.7-6.2c-.1-2.3.8-4.7 2.4-6.5s4-3 6.5-3.2c-.2 0-.3 0-.5 0-1.9-2.2-4.3-3.9-7-5.2 1.5-1.6 2.6-3.6 3-5.7.5-2.3.2-4.8-.6-7-.5-1.1-1.1-2.1-1.8-3.1-.8-.9-1.7-1.7-2.6-2.4-1-.7-2.1-1.2-3.3-1.6-1-.4-2.2-.5-3.4-.6-1.2 0-2.4.2-3.6.5-1.2.4-2.3.9-3.4 1.5-1 .7-1.9 1.5-2.7 2.4s-1.4 2-1.9 3.1c-1.8 4.3-1.2 9 1.6 12.6-3.3 1.6-6.2 4-8.4 6.9-2.5 3.3-4 7.3-4.4 11.3v.1c0 .4.4.8.8.8 4.9-.1 9.7-.2 14.6-.3 4.6-.2 9.2-.3 13.7-.5.5 1.6 1.3 3.1 2.5 4.3 1.9 2 4.7 3.2 7.5 3.2 2.8.1 5.6-1.1 7.5-3.1zm-31.2-4.8c-4.6-.1-9.1-.2-13.7-.3.6-3.5 2-6.8 4.2-9.5 2.3-3 5.4-5.4 8.9-6.8.6-.3.8-1.1.3-1.6-1.4-1.5-2.4-3.4-2.8-5.4s-.1-4.2.6-6.1c1.6-3.8 5.6-6.6 9.8-6.6 2.1 0 4.2.6 6 1.7.9.6 1.7 1.3 2.4 2.1s1.3 1.7 1.7 2.7c1.8 3.9 1.1 8.8-1.9 12.1-.2.2-.1.4.1.5 2.8 1 5.4 2.7 7.7 4.8-2.4-.1-4.8.8-6.7 2.5-2 1.8-3.2 4.4-3.3 7.2 0 1.1.1 2.1.4 3.2-4.6-.2-9.1-.3-13.7-.5z"/><path d="m38.7 42.3.2-.1c2.1-1.6 4-3.5 5.1-5.1.5-.7.9-1.3 1.3-2.1-.8.3-1.5.7-2.2 1.2-.7.4-1.3.9-1.9 1.4-1 .8-1.9 1.7-2.8 2.7-.6-.7-1.3-1.4-2.1-1.9.3 1.3.8 2.5 1.3 3.7.2.4.7.5 1.1.2z"/></g></svg></span> <?php echo __( 'To get your profile verified you have to verify these.');?>
                        </b>
						<ul class="browser-default dt_prof_vrfy">
                            <?php if($config->emailValidation == "1"){?>
                                <?php if( $config->sms_or_email === 'mail' ){?>
                                    <?php if( $profile->active === "0" ){?>
                                        <li><?php echo __( 'Please verify your email address' );?> <a href="<?php echo $site_url;?>/verifymail" data-ajax="/verifymail"><?php echo __( 'Verify Now' );?></a>.</li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if( $config->sms_or_email == 'sms' ){?>
                                    <?php if( !empty( $profile->phone_number ) && $profile->phone_verified == "0" ){?>
                                        <li><?php echo __( 'Please verify your phone number' );?> <a href="<?php echo $site_url;?>/verifyphone" data-ajax="/verifyphone"><?php echo __( 'Verify Now' );?></a>.</li>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if(count($profile->mediafiles) < 5){ ?>
							<li><?php echo __( 'Upload at least 5 image.');?></li>
                            <?php }?>
						</ul>
                    </div>
                    <?php }?>
                <?php } ?>
			<?php } ?>
				
			<div class="mtc_usrd_content dt_profile_about">
				<div class="row no_margin r_margin">
					<div class="col s12 m2">
						<div class="my_profile_add_btns">
							<div class="dt_cp_bar_add_photos" onclick="document.getElementById('avatar_profileimg').click(); return false"> <!-- Add Photo -->
								<div class="inline">
									<svg xmlns="http://www.w3.org/2000/svg" width="23.445" height="21.253" viewBox="0 0 23.445 21.253"> <path id="Path_215786" data-name="Path 215786" d="M12346.792-5943.8h-1.759v-3.251h-3.252v-1.754h3.252v-3.249h1.759v3.249h3.253v1.754h-3.253v3.25Zm-7.591-2.167h-11.733a.837.837,0,0,1-.608-.259.806.806,0,0,1-.26-.619v-17.331a.841.841,0,0,1,.26-.616.84.84,0,0,1,.608-.262h19.541a.844.844,0,0,1,.867.878v9.542h-1.758v-8.664h-17.76v15.865l11.049-11.037,3.048,3.044v2.452l-2.9-2.9-.146-.145-8.269,8.294h8.064v1.755Zm-6.3-10.831a1.861,1.861,0,0,1-1.383-.586,2.017,2.017,0,0,1-.583-1.376,2.017,2.017,0,0,1,.585-1.375,1.854,1.854,0,0,1,1.381-.584,1.851,1.851,0,0,1,1.379.586,2,2,0,0,1,.583,1.374,2.006,2.006,0,0,1-.587,1.378A1.843,1.843,0,0,1,12332.9-5956.8Z" transform="translate(-12326.6 5965.053)" fill="currentColor"/> </svg>
									<b><?php echo __( 'Add Photo' );?></b>
								</div>
							</div>
							<div class="dt_cp_bar_add_videos" onclick="$('#upload_video').modal('open');$('#btn_video_upload').removeClass('hide');$('#video_form').hide();"> <!-- Add Photo -->
								<div class="inline">
									<svg xmlns="http://www.w3.org/2000/svg" width="23.106" height="16.675" viewBox="0 0 23.106 16.675"> <path id="Path_215787" data-name="Path 215787" d="M12342.544-6119.278h-15.015a.8.8,0,0,1-.828-.829v-15.015a.8.8,0,0,1,.828-.832h15.015a.8.8,0,0,1,.829.832v4.968l5.97-4.125.021-.018a.322.322,0,0,1,.2-.09c.025,0,.105,0,.175.129l.006.01a.372.372,0,0,1,.061.2v12.871a.278.278,0,0,1-.293.293h-.232l-5.909-4.2v4.975A.8.8,0,0,1,12342.544-6119.278Zm-14.186-15.013v13.353h13.357v-13.353Zm19.791,2.776h0l-4.673,3.27-.1.073v1.113l4.394,3.074.383.267v-7.8Zm-12.282,7.946h-1.66v-4.291h-2.874l3.7-3.7,3.7,3.7h-2.873v4.29Z" transform="translate(-12326.701 6135.953)" fill="currentColor"/> </svg>
									<b><?php echo __( 'Add Video' );?></b>
								</div>
							</div>
							<?php if( $config->instagram_importer == '1' ){ ?>
								<div class="dt_cp_bar_add_photos">
									<div class="inline">
										<a href="<?php echo $site_url;?>/settings-instagram/<?php echo $profile->username;?>" data-ajax="/settings-instagram/<?php echo $profile->username;?>" style="display: contents;color: inherit;">
											<svg xmlns="http://www.w3.org/2000/svg" width="23.106" height="16.675" viewBox="0 0 23.106 16.675"> <path id="Path_215787" data-name="Path 215787" d="M12342.544-6119.278h-15.015a.8.8,0,0,1-.828-.829v-15.015a.8.8,0,0,1,.828-.832h15.015a.8.8,0,0,1,.829.832v4.968l5.97-4.125.021-.018a.322.322,0,0,1,.2-.09c.025,0,.105,0,.175.129l.006.01a.372.372,0,0,1,.061.2v12.871a.278.278,0,0,1-.293.293h-.232l-5.909-4.2v4.975A.8.8,0,0,1,12342.544-6119.278Zm-14.186-15.013v13.353h13.357v-13.353Zm19.791,2.776h0l-4.673,3.27-.1.073v1.113l4.394,3.074.383.267v-7.8Zm-12.282,7.946h-1.66v-4.291h-2.874l3.7-3.7,3.7,3.7h-2.873v4.29Z" transform="translate(-12326.701 6135.953)" fill="currentColor"/> </svg>
											<b><?php echo __( 'Import' );?></b>
										</a>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col s12 m5">
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
										if( !empty( $value ) ){
											if( $value['is_video'] == "1" && $value['is_confirmed'] == "0" ){

											}else {
												$private = 'false';
												$img_path = $value['avater'];
												$video_file = $value['video_file'];
												if ($value['is_private'] == 1) {
													$private = 'true';
													$img_path = $value['private_file_avater'];
												}
												$is_avater = 'false';
												if ($value['avater'] == $profile->avater->avater) {
													$is_avater = 'true';
												}
												echo '<div class="dt_cp_l_photos">';
												if( $value['is_video'] == "1" ){
													echo '<a class="inline user_video" href="#myVideo_'.$value['id'].'" data-fancybox="gallery" data-id="' . $value['id'] . '" data-video="' . $value['is_video'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';

					//                                        echo '<div class="dt_chng_avtr">
					//                                                    <span class="btn-upload-image" onclick="alert(\'lock\'); return false;" style="width: 30px;float: left;">
					//                                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 25px;height: 25px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
					//                                                    </span>
					//
					//                                                    <span class="btn-upload-image" onclick="alert(\'UNlock\'); return false;" style="width: 30px;float: left;">
					//                                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 25px;height: 25px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
					//                                                    </span>
					//
					//                                                    <span class="btn-upload-image" onclick="alert(\'delete\'); return false;" style="width: 30px;float: right;">
					//                                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 25px;height: 25px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
					//                                                    </span>
					//
					//                                                </div>';

													echo '<video width="800" height="550" controls id="myVideo_'.$value['id'].'" style="display:none;">';
													echo '    <source src="'.$video_file.'" type="video/mp4">';
					//                                          echo '    <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.webm" type="video/webm">';
					//                                          echo '    <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.ogv" type="video/ogg">';
													echo '    Your browser doesn\'t support HTML5 video tag.';
													echo '</video>';
													echo '<div class="dt_prof_vid_play_ico"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10,16.5V7.5L16,12M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg></div>';
												}else{
													echo '<a class="inline" href="' . $value['full'] . '" data-fancybox="gallery" data-id="' . $value['id'] . '" data-private="' . $private . '" data-avater="' . $is_avater . '">';
												}
												if($value['is_approved'] === 0 && $config->review_media_files == '1' ){
													echo '<div class="dt_usr_undr_rvw_mini"><img src="' . $config->uri . '/upload/photos/d-blog.jpg" alt="' . $profile->username . '"><span>'.__('Under Review').'</span></div>';
												}else{
													echo '<img src="' . $img_path . '" alt="' . $profile->username . '">';
												}

												echo '</a>';
												echo '</div>';
											}
										}else{
											echo '<div class="dt_cp_l_photos">';
											echo '<div class="inline"></div>';
											echo '</div>';
										}
									}
									?>
									<input type="file" id="avatar_profileimg" class="hide" accept="image/x-png, image/gif, image/jpeg" name="profile_images" multiple="multiple">
								</div>
							</figure>
						</div>
					</div>
					<div class="col s12 m5">
						<div class="mtc_usrd_sidebar dt_profile_about_side">
							<div class="mtc_usrd_summary">
								<h5><?php echo __( 'About' );?> <?php echo $profile->full_name;?></h5>
								<a class="edit" href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" title="<?php echo __( 'Edit' );?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="15.295" height="15.302" viewBox="0 0 15.295 15.302"> <path id="Subtraction_40" data-name="Subtraction 40" d="M4.1,15.3a.545.545,0,0,1-.387-.166L.167,11.588A.54.54,0,0,1,.01,11.2a.526.526,0,0,1,.15-.376L10.828.161A.513.513,0,0,1,11.2.008a.533.533,0,0,1,.385.156l3.548,3.551a.538.538,0,0,1,.158.386.524.524,0,0,1-.151.374L4.477,15.142A.529.529,0,0,1,4.1,15.3ZM3.509,9.01h0L1.32,11.2,3.9,13.777l.208.208,9.674-9.677.207-.208L11.409,1.525,11.2,1.318,9.013,3.507l1.18,1.183-.769.773L8.248,4.282l-1.606,1.6L7.83,7.057l-.772.773L5.877,6.643l-1.6,1.6L5.461,9.428l-.771.77L3.509,9.01Zm11.384,5.882H11.757L9.84,12.976l.767-.767,1.677,1.671.084.085h1.6V12.369l-1.755-1.756.768-.773,1.917,1.917v3.135ZM2.327,5.463h0L.156,3.289a.425.425,0,0,1-.109-.158A.623.623,0,0,1,0,2.913a.545.545,0,0,1,.045-.205.427.427,0,0,1,.111-.163L2.538.164A.524.524,0,0,1,2.913,0,.56.56,0,0,1,3.3.164l2.16,2.163-.771.767L3.124,1.525l-.211-.207L1.32,2.913,3.094,4.689l-.767.772Z" transform="translate(0)" fill="currentColor"/> </svg>
								</a>
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
												<p class="info_title"><?php echo __( 'district' );?></p>
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
						</div>
					</div>
				</div>
			</div>
			
			<div class="dt_user_about">
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
												<p><?php echo $profile->language;?></p>
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
												if( isset($general_custom_data[$field['fid']]) && $general_custom_data[$field['fid']] !== null ) {
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
                                            if( !empty($profile_custom_data[$field['fid']]) ) {
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
		</div>
		
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
		</div>
	</div>
</div>
<!-- End Profile  -->

<div id="upload_images" class="modal" style="width: 30%;">
    <div class="modal-content">
        <div class="dt_user_prof_complt">
            <h5 class="valign-wrapper"><?php echo __( 'Upload Completion' );?><span id="c_perc">0%</span></h5>
            <div class="progress" id="c_prog">
                <div class="determinate" id="c_det" style="width: 0%"></div>
            </div>
        </div>

    </div>
</div>


<a href="javascript:void(0);" class="btn-floating btn-large waves-effect waves-light bg_gradient only-mobile" style="bottom: 100px;position: fixed;right: 32px;" onclick="document.getElementById('avatar_profileimg').click(); return false">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-7 -6 40 40"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z"></path></svg>
</a>


<div id="upload_video" class="modal" style="width: 60%;">
    <div class="modal-content">
        <h6 class="bold" style="margin-top: 0;"><?php echo __( 'Add Video' );?></h6>
        <?php if ($config->lock_pro_video == '1' && $profile->lock_pro_video == '0' && $profile->is_pro == '1'){ ?>
            <a href="javascript:void(0);" id="btn_video_upload" onclick="document.getElementById('avatar_profilevideo').click(); return false" class="btn_upld_prf_vid waves-effect">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z"></path></svg> <?php echo __( 'Upload' );?>
            </a>
            <input type="file" id="avatar_profilevideo" class="hide" accept="video/*" name="profile_videos">
        <?php } elseif ($config->lock_pro_video == '1' && $profile->lock_pro_video == '1' && $profile->is_pro == '1') { ?>
            <p><?php echo __('To unlock video upload feature in your account, you have to pay');?>  <?php echo $config->currency_symbol . (int)$config->lock_pro_video_fee;?>.</p>
            <div class="modal-body  credit_pln">
                <p class="bold"><?php echo __( 'Pay Using' );?></p>
                <div class="pay_using">
                    <div class="pay_u_btns valign-wrapper">
                    	<button onclick="PayUsingWallet('private_video','show');" class="btn valign-wrapper">
                            <span><?php echo __( 'pay' );?></span>
                        </button>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <a href="javascript:void(0);" id="btn_video_upload" onclick="document.getElementById('avatar_profilevideo').click(); return false" class="btn_upld_prf_vid waves-effect">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z"></path></svg> <?php echo __( 'Upload' );?>
            </a>
            <input type="file" id="avatar_profilevideo" class="hide" accept="video/*" name="profile_videos">
        <?php } ?>
        <div class="dt_user_prof_complt hide" style="margin: 50px 5px;">
            <h5 class="valign-wrapper"><?php echo __( 'Upload Completion' );?><span id="c_percx">0%</span></h5>
            <div class="progress" id="c_progx">
                <div class="determinate" id="c_detx" style="width: 0%"></div>
            </div>
        </div>
		<div class="dt_prof_upldd_vid_ldng center hide">
			<p><?php echo __('Please wait..');?></p>
			<svg width="120" height="30" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor"> <circle cx="15" cy="15" r="15"> <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite" /> <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite" /> </circle> <circle cx="60" cy="15" r="9" fill-opacity="0.3"> <animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite" /> <animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite" /> </circle> <circle cx="105" cy="15" r="15"> <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite" /> <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite" /> </circle> </svg>
		</div>
        <div id="video_form" class="hide">
            <div class="form-group">
                <label class="col-md-12" for="privacy"><?php echo __( 'Privacy');?></label>
                <div class="col-md-12">
                    <select name="privacy" id="privacy" class="form-control">
                        <option value="0" selected><?php echo __( 'Public');?></option>
                        <option value="1"><?php echo __( 'Private');?></option>
                    </select>
                </div>
            </div>
			<br>
			<input type="file" id="video_thumbnail" class="hide" accept="image/x-png, image/gif, image/jpeg" name="video_thumbnail">
            <div class="item active form-group" onclick="document.getElementById('video_thumbnail').click(); return false">
                <label class="col-md-12" for="privacy"><?php echo __( 'Thumbnail');?></label>
                <img id="video_thumbnail_image" src="<?php echo $config->uri;?>/upload/photos/video-placeholder.jpg" class="dt_prof_upldd_vid_thmb">
                <input type="file" id="video_thumbnail" class="hide" accept="image/x-png, image/gif, image/jpeg" name="video_thumbnail">
            </div>
            <input type="hidden" name="media_id" id="media_id">
        </div>
    </div>
    <div class="modal-footer">
        <div class="video_upload_form_progress hide" id="img_upload_progress">
            <div class="progress">
                <div id="img_upload_progress_bar" class="determinate progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
        </div>
        <button class="modal-close waves-effect btn-flat"><?php echo __( 'Close' );?></button>
        <button class="waves-effect waves-light btn-flat btn_primary white-text" disabled id="btn-upload-video-file" data-selected=""><?php echo __( 'Upload' );?></button>
    </div>
</div>

<div class="bank_transfer_modal modal modal-fixed-footer">
	<div class="modal-dialog">
    <div class="modal-content dt_bank_trans_modal">
		<div class="modal-header">
			<h5 class="modal-title"><?php echo __( 'Bank Transfer' );?></h5>
		</div>
        <div class="modal-body">
            <div class="bank_info"><?php echo htmlspecialchars_decode($config->bank_description);?></div>
			<div class="dt_user_profile hide_alert_info_bank_trans">
                <span class="valign-wrapper">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path></svg> <?php echo __( 'Note' );?>:
                </span>
				<ul class="browser-default dt_prof_vrfy">
					<li><?php echo __( 'Please transfer the amount of' );?> <b><span id="bank_transfer_price"></span></b> <?php echo __( 'to this bank account to buy' );?> <b>"<span id="bank_transfer_description"></span>"</b></li>
					<li><?php echo $config->bank_transfer_note;?></li>
				</ul>
            </div>
			<p class="dt_bank_trans_upl_rec"><a href="javascript:void(0);" onclick="$('.bank_transfer_modal').addClass('up_rec_active'); return false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13.5,16V19H10.5V16H8L12,12L16,16H13.5M13,9V3.5L18.5,9H13Z"></path></svg> <?php echo __( 'Upload Receipt' );?></a></p>
            <div class="upload_bank_receipts">
                <div onclick="document.getElementById('receipt_img').click(); return false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13.5,16V19H10.5V16H8L12,12L16,16H13.5M13,9V3.5L18.5,9H13Z"></path></svg>
                    <p><?php echo __( 'Upload Receipt' );?></p>
					<img id="receipt_img_preview" src="">
                </div>
            </div>
            <input type="file" id="receipt_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="receipt_img">
        </div>
        <!--<span style="display: block;text-align: center;" id="receipt_img_path"></span>-->
    </div>
    <div class="modal-footer">
		<div class="bank_transfr_progress hide" id="img_upload_progress">
			<div class="progress">
				<div id="img_upload_progress_bar" class="determinate progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
			</div>
		</div>
		<button class="modal-close waves-effect btn-flat"><?php echo __( 'Close' );?></button>
        <button class="waves-effect waves-green btn btn-flat bold" disabled id="btn-upload-receipt" data-selected=""><?php echo __( 'Confirm' );?></button>
    </div>
	</div>
</div>


<script>
new CircleProgress('.progresss', {
		max: 100,
		value: <?php echo $profile->profile_completion;?>,
		textFormat: 'percent',
	});

	<?php if ($config->securionpay_payment === "yes") { ?>
        $(function () {
            SecurionpayCheckout.key = '<?php echo($config->securionpay_public_key); ?>';
            SecurionpayCheckout.success = function (result) {
                $.post(window.ajax + 'securionpay/handle', result, function(data, textStatus, xhr) {
                    if (data.status == 200) {
                        window.location.href = data.url;
                    }
                }).fail(function(data) {
                    M.toast({html: data.responseJSON.message});
                });
            };
            SecurionpayCheckout.error = function (errorMessage) {
                M.toast({html: errorMessage});
            };
        });
        function lock_pro_video_pay_via_securionpay(){
            price = <?php echo (int)$config->lock_pro_video_fee;?>;
            $.post(window.ajax + 'securionpay/token', {type:'lock_pro_video',price:price}, function(data, textStatus, xhr) {
                if (data.status == 200) {
                    SecurionpayCheckout.open({
                        checkoutRequest: data.token,
                        name: 'lock pro video',
                        description: '<?php echo __( "Unlock Upload video feature");?>'
                    });
                }
            }).fail(function(data) {
                M.toast({html: data.responseJSON.message});
            });
        }
    <?php } ?>
	<?php if ($config->authorize_payment === "yes") { ?>
    function lock_pro_video_pay_via_authorize() {
        $('#authorize_btn').attr('onclick', 'AuthorizeVideoRequest()');
        $('#authorize_modal').modal('open');
    }
    function AuthorizeVideoRequest() {
        $('#authorize_btn').html("<?php echo __('please_wait');?>");
        $('#authorize_btn').attr('disabled','true');
        authorize_number = $('#authorize_number').val();
        authorize_month = $('#authorize_month').val();
        authorize_year = $('#authorize_year').val();
        authorize_cvc = $('#authorize_cvc').val();
        price = <?php echo (int)$config->lock_pro_video_fee;?>;
        $.post(window.ajax + 'authorize/pay', {type:'lock_pro_video',card_number:authorize_number,card_month:authorize_month,card_year:authorize_year,card_cvc:authorize_cvc,price:price}, function(data) {
            if (data.status == 200) {
                window.location.href = data.url;
            } else {
                $('#authorize_alert').html("<div class='alert alert-danger'>"+data.message+"</div>");
                setTimeout(function () {
                    $('#authorize_alert').html("");
                },3000);
            }
            $('#authorize_btn').html("<?php echo __( 'pay' );?>");
            $('#authorize_btn').removeAttr('disabled');
        }).fail(function(data) {
            $('#authorize_alert').html("<div class='alert alert-danger'>"+data.responseJSON.message+"</div>");
            setTimeout(function () {
                $('#authorize_alert').html("");
            },3000);
            $('#authorize_btn').html("<?php echo __( 'pay' );?>");
            $('#authorize_btn').removeAttr('disabled');
        });
    }
    <?php } ?>
	function lock_pro_video_pay_via_paystack() {
        $('#paystack_btn').attr('onclick', 'InitializeVideoPaystack()');
        $('#paystack_wallet_modal').modal('open');
    }
    function InitializeVideoPaystack() {
        $('#paystack_btn').html("<?php echo __('please_wait');?>");
        $('#paystack_btn').attr('disabled','true');
        email = $('#paystack_wallet_email').val();
        price = <?php echo (int)$config->lock_pro_video_fee;?>;
        $.post(window.ajax + 'paystack/initialize', {type:'lock_pro_video',email:email,price:price}, function(data) {
            if (data.status == 200) {
                window.location.href = data.url;
            } else {
                $('#paystack_wallet_alert').html("<div class='alert alert-danger'>"+data.message+"</div>");
                setTimeout(function () {
                    $('#paystack_wallet_alert').html("");
                },3000);
            }
            $('#paystack_btn').html("<?php echo __( 'Confirm' );?>");
            $('#paystack_btn').removeAttr('disabled');
        });
    }

    function lock_pro_video_pay_via_2co(){
        $('#2checkout_type').val('lock_pro_video');
        $('#2checkout_description').val('<?php echo __( "Unlock Upload video feature");?>');
        $('#2checkout_price').val(<?php echo (int)$config->lock_pro_video_fee;?>);

        $('#2checkout_modal').modal('open');
    }
    function lock_pro_video_pay_via_iyzipay(){
        $('.btn-iyzipay-payment').attr('disabled','true');

        $.post(window.ajax + 'iyzipay/createsession', {
            payType: 'lock_pro_video',
            description: '<?php echo __( "Unlock Upload video feature");?>',
            price: <?php echo (int)$config->lock_pro_video_fee;?>
        }, function(data) {
            if (data.status == 200) {
                $('#iyzipay_content').html('');
                $('#iyzipay_content').html(data.html);
            } else {
                $('.btn-iyzipay').attr('disabled', false).html("Iyzipay App not set yet.");
            }
            $('.btn-iyzipay').removeAttr('disabled');
            $('.btn-iyzipay').find('span').text("<?php echo __( 'iyzipay');?>");
        });

        $('.btn-iyzipay-payment').removeAttr('disabled');

    }

    function lock_pro_video_pay_via_cashfree(){
        $('.cashfree-payment').attr('disabled','true');

        $('#cashfree_type').val('lock_pro_video');
        $('#cashfree_description').val('<?php echo __( 'Unlock Upload video feature' );?>');
        $('#cashfree_price').val(<?php echo (int)$config->lock_pro_video_fee;?>);

        $("#cashfree_alert").html('');
        $('.go_pro--modal').fadeOut(250);
        $('#cashfree_modal_box').modal('open');

        $('.btn-cashfree-payment').removeAttr('disabled');
    }


    function unlock_photo_private_pay_via_bank(amount){
        $('#bank_transfer_price').text('<?php echo $config->currency_symbol;?> <?php echo (int)$config->lock_private_photo_fee;?>');
        $('#bank_transfer_description').text('<?php echo __( 'Unlock Private Photo Payment' );?>');
        $('#receipt_img_path').html('');
        $('#receipt_img_preview_unlock_photo_private').attr('src', '');
        $('.bank_transfer_modal').removeClass('up_rec_img_ready, up_rec_active');
        $('.bank_transfer_modal').modal('open');
    }

    $(document).ready(function(){

        document.getElementById('receipt_img').addEventListener('change', function(e) {
            let imgPath = $(this)[0].files[0].name;
            if (typeof(FileReader) != "undefined") {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#receipt_img_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
            $('#receipt_img_path').html(imgPath);
            $('.bank_transfer_modal').addClass('up_rec_img_ready');
            $('#btn-upload-receipt').removeAttr('disabled');
            $('#btn-upload-receipt').removeClass('btn-flat').addClass('btn-success');
        });

        document.getElementById('btn-upload-receipt').addEventListener('click', function(e) {
            e.preventDefault();
            let bar = $('#img_upload_progress');
            let percent = $('#img_upload_progress_bar');

            let formData = new FormData();
            formData.append("description", '<?php echo __( 'Unlock Private Photo Payment' );?>');
            formData.append("price", <?php echo (int)$config->lock_private_photo_fee;?>);
            formData.append("mode", 'unlock_photo_private');
            formData.append("receipt_img", $("#receipt_img")[0].files[0], $("#receipt_img")[0].files[0].value);
            bar.removeClass('hide');
            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt){
                        if (evt.lengthComputable) {
                            let percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            //status.html( percentComplete + "%");
                            percent.width(percentComplete + '%');
                            percent.html(percentComplete + '%');
                            if (percentComplete === 100) {
                                bar.addClass('hide');
                                percent.width('0%');
                                percent.html('0%');
                            }
                        }
                    }, false);
                    return xhr;
                },
                url: window.ajax + 'profile/upload_receipt',
                type: "POST",
                async: true,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 60000,
                dataType: false,
                data: formData,
                success: function(result) {
                    if( result.status == 200 ){
                        $('.bank_transfer_modal').modal('close');
                        $('.payment_modalx').modal('close');
                        M.toast({html: '<?php echo __('Your receipt uploaded successfully.');?>'});
                        return false;
                    }
                }
            });
        });


        $( document ).on( 'click', '#btn-upload-video-file', function(e){
            var formData = new FormData();
                formData.append("privacy", $('#privacy').val() );
                formData.append("media_id", $('#media_id').val() );
                if(typeof $('#video_thumbnail')[0].files[0] !== "undefined") {
                    formData.append("video_thumbnail", $('#video_thumbnail')[0].files[0], $('#video_thumbnail')[0].files[0].value);
                }
                console.log(formData);

            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt){
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            xstatus.html( percentComplete + "%");
                            xbar.css({'width': percentComplete + '%'});
                            if (percentComplete === 100) {
                                xbar.hide();
                                xbar.width('0%');
                                xstatus.html( "0%");
                                $('.dt_user_prof_complt').addClass('hide');
                            }
                        }
                    }, false);
                    return xhr;
                },
                url: window.ajax + 'profile/confirm_upload_video',
                type: "POST",
                async: true,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                timeout: 60000,
                dataType: false,
                success: function(result) {
                    if( result.status == 200 ){
                    	if (typeof result.lock_private_photo != 'undefined') {
                            if (result.lock_private_photo == 'on') {
                                $('.payment_v_modalx').modal('open');
                                return false;
                            }
                        }
                        // $('#video_form').removeClass('hide');
                        // $('#media_id').val(result.media_id);
                        // $('#btn-upload-video-file').attr('disabled', false);
                        // e.preventDefault();
                        $('#upload_video').modal('close');
                        setTimeout(function() {
                            $("#ajaxRedirect").attr("data-ajax", '/' + window.loggedin_user );
                            $("#ajaxRedirect").click();
                        }, 500);

                    }
                },
                error(data){
                    $('#upload_video').modal('close');
                }
            });

        });

        $( document ).on( 'change', '#video_thumbnail', function(e){
            if (typeof(FileReader) != "undefined") {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#video_thumbnail_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        $( document ).on( 'change', '#avatar_profilevideo', function(e){

            <?php
                if($profile->is_pro == '0'){
                    $user_image_count = (int)$db->where('user_id', $profile->id)->getValue('mediafiles','count(id)');
                    $config_max_image = (int)$config->max_photo_per_user;
                    if( $user_image_count >= $config_max_image ) {?>
                        M.toast({html: '<?php echo __('You reach to limit of media uploads.');?>'});
                        $('#upload_video').modal('close');
                        return false;
            <?php }} ?>

            $('#btn_video_upload').addClass('hide');
            $('.dt_user_prof_complt').removeClass('hide');
            var xbar = $('#c_detx');
            var xstatus = $('#c_percx');
            var formData = new FormData();
                formData.append("video", $(this)[0].files[0],$(this)[0].files[0].value );
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt){
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            xstatus.html( percentComplete + "%");
                            xbar.css({'width': percentComplete + '%'});
                            if (percentComplete === 100) {
                                xbar.hide();
                                xbar.width('0%');
                                xstatus.html( "0%");
                                $('.dt_user_prof_complt').addClass('hide');
                                $('.dt_prof_upldd_vid_ldng').removeClass('hide');
                            }
                        }
                    }, false);
                    return xhr;
                },
                url: window.ajax + 'profile/upload_video',
                type: "POST",
                async: true,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                timeout: 0,
                dataType: false,
                success: function(result) {
                    if( result.status == 200 ){
                        $('.dt_prof_upldd_vid_ldng').addClass('hide');
                        $('#video_form').removeClass('hide');
                        $('#video_form').show();
                        $('#media_id').val(result.media_id);
                        if(typeof result.thumb !== "undefined") {
                            $('#video_thumbnail_image').attr('src', result.thumb);
                        }
                        $('#btn-upload-video-file').attr('disabled', false);
                        e.preventDefault();
                    }
                },
                error(result){
                    M.toast({html: result.responseJSON.message});
                    $('#upload_video').modal('close');
                }
            });
        });

    });
</script>
