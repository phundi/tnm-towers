<?php
    if( !isset( $_SESSION['JWT'] ) ){
        exit();
    }
?>
    <?php //require( $theme_path . 'main' . $_DS . 'onesignal.php' );?>
    <!-- Header  -->
		<nav role="navigation" id="nav-logged-in">
            <div class="nav-wrapper container container container-fluid container_new">
				
				<span class="left dt_slide_menu hide" id="open_slide" onclick="<?php if(!empty($_COOKIE['open_slide']) && $_COOKIE['open_slide'] == 'yes'){ ?>SlideEraseCookie('open_slide')<?php }else{ ?>SlideSetCookie('open_slide','yes',1);<?php } ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 276.167 276.167"> <g fill="currentColor"><path d="M33.144,2.471C15.336,2.471,0.85,16.958,0.85,34.765s14.48,32.293,32.294,32.293s32.294-14.486,32.294-32.293 S50.951,2.471,33.144,2.471z"></path> <path d="M137.663,2.471c-17.807,0-32.294,14.487-32.294,32.294s14.487,32.293,32.294,32.293c17.808,0,32.297-14.486,32.297-32.293 S155.477,2.471,137.663,2.471z"></path> <path d="M243.873,67.059c17.804,0,32.294-14.486,32.294-32.293S261.689,2.471,243.873,2.471s-32.294,14.487-32.294,32.294 S226.068,67.059,243.873,67.059z"></path> <path d="M32.3,170.539c17.807,0,32.297-14.483,32.297-32.293c0-17.811-14.49-32.297-32.297-32.297S0,120.436,0,138.246 C0,156.056,14.493,170.539,32.3,170.539z"></path> <path d="M136.819,170.539c17.804,0,32.294-14.483,32.294-32.293c0-17.811-14.478-32.297-32.294-32.297 c-17.813,0-32.294,14.486-32.294,32.297C104.525,156.056,119.012,170.539,136.819,170.539z"></path> <path d="M243.038,170.539c17.811,0,32.294-14.483,32.294-32.293c0-17.811-14.483-32.297-32.294-32.297 s-32.306,14.486-32.306,32.297C210.732,156.056,225.222,170.539,243.038,170.539z"></path> <path d="M33.039,209.108c-17.807,0-32.3,14.483-32.3,32.294c0,17.804,14.493,32.293,32.3,32.293s32.293-14.482,32.293-32.293 S50.846,209.108,33.039,209.108z"></path> <path d="M137.564,209.108c-17.808,0-32.3,14.483-32.3,32.294c0,17.804,14.487,32.293,32.3,32.293 c17.804,0,32.293-14.482,32.293-32.293S155.368,209.108,137.564,209.108z"></path> <path d="M243.771,209.108c-17.804,0-32.294,14.483-32.294,32.294c0,17.804,14.49,32.293,32.294,32.293 c17.811,0,32.294-14.482,32.294-32.293S261.575,209.108,243.771,209.108z"></path> </g></svg>
				</span>
				
                <div class="left header_logo">
                    <a id="logo-container" href="<?php echo $site_url;?>/<?php if( $profile->verified == 1 ){?>find-matches<?php }?>" class="brand-logo">
                        <img src="<?php echo $config->sitelogo;?>" alt="" data-default="" data-light="">
                    </a>
                </div>
                <?php if( $profile->verified == 1 ){?>
                    <ul class="left header_home_link hide_go_pro_hdr_link">
						<li>
							<a href="<?php echo $site_url;?>/find-matches" data-ajax="/find-matches" class="<?php if($data['name'] == 'find-matches'){ echo 'active';}?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3.604 7.197l7.138 -3.109a0.96 .96 0 0 1 1.27 .527l4.924 11.902a1.004 1.004 0 0 1 -.514 1.304l-7.137 3.109a0.96 .96 0 0 1 -1.271 -.527l-4.924 -11.903a1.005 1.005 0 0 1 .514 -1.304z"></path><path d="M15 4h1a1 1 0 0 1 1 1v3.5"></path><path d="M20 6c.264 .112 .52 .217 .768 .315a1 1 0 0 1 .53 1.311l-2.298 5.374"></path></svg> <?php echo __( 'Find Matches' );?>
							</a>
						</li>
						<?php if( $config->pro_system == 1 ) { ?>
                            <?php if( $profile->is_pro <> 1 ) { ?>
                                <?php if( isGenderFree($profile->gender) === false ){ ?>
                                    <li>
										<a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="prem">
											<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M2 19h20v2H2v-2zM2 5l5 3.5L12 2l5 6.5L22 5v12H2V5zm2 3.841V15h16V8.841l-3.42 2.394L12 5.28l-4.58 5.955L4 8.84z" fill="currentColor"/></svg></span> <?php echo __( 'go_premium' );?>
										</a>
									</li>
                                <?php }?>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <ul class="right">
						<li class="credit">
							<?php if ( isGenderFree($profile->gender) === true ) {?>
								<a href="javascript:void(0);">
							<?php }else{ ?>
								<a href="<?php echo $site_url;?>/credit" data-ajax="/credit">
							<?php } ?>
								
								<div class="how_credtss">
									<span><?php echo __( 'Credit:' );?></span>&nbsp;&nbsp;
                                    <span style="font-weight: bold;color: #FF6600;" id="credit_amount"> MK <?php
													if( isGenderFree($profile->gender) === true ){
														echo __('Free');
													}else{
														echo number_format((int)$profile->balance);
													}
												 ?></span>
								</div>
							</a>
						</li>
                        <li class="header_msg">
                            <a href="javascript:void(0);" id="messenger_opener" class="btn-flat">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path><line x1="8" y1="9" x2="16" y2="9"></line><line x1="8" y1="13" x2="14" y2="13"></line></svg>
                                <?php
                                    $unread_messages = 0;// Message::getUnreadMessages();
                                    if( $unread_messages > 0 ){
                                        echo '<span class="badge red chat_badge" href="javascript:void(0);" id="messenger_opener">' . $unread_messages . '</span></a>';
                                    }else{
                                        echo '<span class="badge red chat_badge hide" href="javascript:void(0);" id="messenger_opener">0</span></a>';
                                    }
                                ?>
                            </a>
                        </li>
                        <li class="header_notifications">
                            <a href="javascript:void(0);" id="notificationbtn" data-ajax-post="/useractions/shownotifications" data-ajax-params="" data-ajax-callback="callback_show_notifications" data-target="notif_dropdown" class="dropdown-trigger btn-flat">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path><path d="M21 6.727a11.05 11.05 0 0 0 -2.794 -3.727"></path><path d="M3 6.727a11.05 11.05 0 0 1 2.792 -3.727"></path></svg>
								<span class="badge red notification_badge hide">0</span>
							</a>
                            <ul id="notif_dropdown" class="dropdown-content">
								<div class="valign-wrapper">
									<h3><?php echo __( 'Notifications' );?></h3>
									<button type="button" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg></button>
								</div>
                                <div class="dt_notifis_prnt">
                                    <h5 class="empty_state">
                                        <svg width="140" height="64" viewBox="0 0 140 64" xmlns="http://www.w3.org/2000/svg" fill="#e05cd1" style="width: 90px;"> <path d="M30.262 57.02L7.195 40.723c-5.84-3.976-7.56-12.06-3.842-18.063 3.715-6 11.467-7.65 17.306-3.68l4.52 3.76 2.6-5.274c3.717-6.002 11.47-7.65 17.305-3.68 5.84 3.97 7.56 12.054 3.842 18.062L34.49 56.118c-.897 1.512-2.793 1.915-4.228.9z" fill-opacity=".5"> <animate attributeName="fill-opacity" begin="0s" dur="1.4s" values="0.5;1;0.5" calcMode="linear" repeatCount="indefinite"></animate> </path> <path d="M105.512 56.12l-14.44-24.272c-3.716-6.008-1.996-14.093 3.843-18.062 5.835-3.97 13.588-2.322 17.306 3.68l2.6 5.274 4.52-3.76c5.84-3.97 13.592-2.32 17.307 3.68 3.718 6.003 1.998 14.088-3.842 18.064L109.74 57.02c-1.434 1.014-3.33.61-4.228-.9z" fill-opacity=".5"> <animate attributeName="fill-opacity" begin="0.7s" dur="1.4s" values="0.5;1;0.5" calcMode="linear" repeatCount="indefinite"></animate> </path> <path d="M67.408 57.834l-23.01-24.98c-5.864-6.15-5.864-16.108 0-22.248 5.86-6.14 15.37-6.14 21.234 0L70 16.168l4.368-5.562c5.863-6.14 15.375-6.14 21.235 0 5.863 6.14 5.863 16.098 0 22.247l-23.007 24.98c-1.43 1.556-3.757 1.556-5.188 0z"></path> </svg>
                                    </h5>
                                </div>
                            </ul>
                        </li>

                        <li class="header_notifications">
                            
                        </li>

                        <li class="header_user">
							<div class="boost-div">
								<?php
									$boost_duration = 0;
									if( $profile->boosted_time > 0 ) {
										$boost_duration = ( time() - $profile->boosted_time ) / 60;
									}else{
										$boost_duration = $config->boost_expire_time;
									}
									$boost_duration = $config->boost_expire_time - $boost_duration;
								?>
								<?php if( $profile->is_boosted == '1' && $boost_duration <= $config->boost_expire_time ){?>
									<div class="boosted_message_expire" data-message-expire="<button title='<?php echo __('Boost me!');?>' id='boost_btn' class='btn boost-me'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 493.944 493.944'><path fill='currentColor' d='M367.468,175.996c-3.368-5.469-9.317-8.807-15.734-8.807h-84.958l45.919-143.098  c1.797-5.614,0.816-11.76-2.662-16.521c-3.464-4.748-9.014-7.57-14.9-7.57h-84.446c-8.02,0-15.125,5.18-17.563,12.814  l-68.487,213.465c-1.797,5.613-0.817,11.756,2.663,16.52c3.464,4.748,9.013,7.57,14.899,7.57h14.868h68.183l-22.006,235.037  c-0.352,3.736,2.004,7.185,5.614,8.227c3.593,1.045,7.427-0.608,9.126-3.961L368.19,194.01  C371.093,188.281,370.82,181.467,367.468,175.996z' /></svg></button>">
										<span class="global_boosted_time" data-show="no" data-boosted-time="<?php echo $boost_duration;?>"></span>
										<button title='<?php echo __('Your boost will expire in');?> <?php echo __('minutes');?>' class='btn boost-running'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 493.944 493.944"><path fill="currentColor" d="M367.468,175.996c-3.368-5.469-9.317-8.807-15.734-8.807h-84.958l45.919-143.098  c1.797-5.614,0.816-11.76-2.662-16.521c-3.464-4.748-9.014-7.57-14.9-7.57h-84.446c-8.02,0-15.125,5.18-17.563,12.814  l-68.487,213.465c-1.797,5.613-0.817,11.756,2.663,16.52c3.464,4.748,9.013,7.57,14.899,7.57h14.868h68.183l-22.006,235.037  c-0.352,3.736,2.004,7.185,5.614,8.227c3.593,1.045,7.427-0.608,9.126-3.961L368.19,194.01  C371.093,188.281,370.82,181.467,367.468,175.996z" /></svg></button>
									</div>
                                    
								<?php }else { ?>
									<button title='<?php echo __('Boost me!');?>' id='boost_btn' class='btn boost-me'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 493.944 493.944"><path fill="currentColor" d="M367.468,175.996c-3.368-5.469-9.317-8.807-15.734-8.807h-84.958l45.919-143.098  c1.797-5.614,0.816-11.76-2.662-16.521c-3.464-4.748-9.014-7.57-14.9-7.57h-84.446c-8.02,0-15.125,5.18-17.563,12.814  l-68.487,213.465c-1.797,5.613-0.817,11.756,2.663,16.52c3.464,4.748,9.013,7.57,14.899,7.57h14.868h68.183l-22.006,235.037  c-0.352,3.736,2.004,7.185,5.614,8.227c3.593,1.045,7.427-0.608,9.126-3.961L368.19,194.01  C371.093,188.281,370.82,181.467,367.468,175.996z" /></svg></button>
								<?php } ?>
							</div>
							
                            <a href="javascript:void(0);" data-target="user_dropdown" class="dropdown-trigger btn-flat">
                                <img src="<?php echo $profile->avater->avater;?>" alt="<?php echo $profile->full_name;?>" /> <span><?php echo __( 'Hi,' );?> <?php echo $profile->first_name;?></span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/></svg>
                            </a>
                            <ul id="user_dropdown" class="dropdown-content">
								<?php if ( isGenderFree($profile->gender) === true ) {?>
								
								<?php }else{ ?>
								<li class="hide header_credits_small_mobi">
									<a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="waves-effect">
                                         <?php echo "MK ".(number_format((int)$profile->balance))." ".__( 'Credit' );?>
									</a>
								</li>
								<?php } ?>
								<?php if( $profile->is_pro <> 1 ) { ?>
                                <li class="header_credits_mobi">
                                    <a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2 19h20v2H2v-2zM2 5l5 3.5L12 2l5 6.5L22 5v12H2V5zm2 3.841V15h16V8.841l-3.42 2.394L12 5.28l-4.58 5.955L4 8.84z" /></svg> <?php echo __( 'Subscribe' );?></a>
                                </li>
                                <li class="divider header_credits_mobi" tabindex="-1"></li>
								<?php } ?>
								<?php if (false && $config->agora_live_video == 1) { ?>
									<li>
										<a href="<?php echo $site_url;?>/live" data-ajax="/live" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17 9.2l5.213-3.65a.5.5 0 0 1 .787.41v12.08a.5.5 0 0 1-.787.41L17 14.8V19a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v4.2zm0 3.159l4 2.8V8.84l-4 2.8v.718zM3 6v12h12V6H3zm2 2h2v2H5V8z"></path></svg> <?php echo __( 'Go Live Now' );?></a>
									</li>
									<li class="divider" tabindex="-1"></li>
								<?php } ?>
								<li>
                                    <a href="<?php echo $site_url;?>/@<?php echo $profile->username;?>" data-ajax="/@<?php echo $profile->username;?>" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H4zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z" /></svg> <?php echo __( 'Profile' );?></a>
                                </li>
								<li>
                                    <a href="<?php echo $site_url;?>/matches" data-ajax="/matches"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm5.793 8.914l3.535-3.535 1.415 1.414-4.95 4.95-3.536-3.536 1.415-1.414 2.12 2.121z" /></svg> <?php echo __( 'Matches' );?></a>
                                </li>
								<li>
                                    <a href="<?php echo $site_url;?>/visits" data-ajax="/visits"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 11a5 5 0 0 1 5 5v6h-2v-6a3 3 0 0 0-2.824-2.995L12 13a3 3 0 0 0-2.995 2.824L9 16v6H7v-6a5 5 0 0 1 5-5zm-6.5 3c.279 0 .55.033.81.094a5.947 5.947 0 0 0-.301 1.575L6 16v.086a1.492 1.492 0 0 0-.356-.08L5.5 16a1.5 1.5 0 0 0-1.493 1.356L4 17.5V22H2v-4.5A3.5 3.5 0 0 1 5.5 14zm13 0a3.5 3.5 0 0 1 3.5 3.5V22h-2v-4.5a1.5 1.5 0 0 0-1.356-1.493L18.5 16c-.175 0-.343.03-.5.085V16c0-.666-.108-1.306-.309-1.904.259-.063.53-.096.809-.096zm-13-6a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zm13 0a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zm-13 2a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1zm13 0a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1zM12 2a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" /></svg> <?php echo __( 'Visits' );?></a>
                                </li>
								<?php if( $config->connectivitySystem == '1' ){?>
									<li>
										<a href="<?php echo $site_url;?>/friends" data-ajax="/friends"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H2zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm8.284 3.703A8.002 8.002 0 0 1 23 22h-2a6.001 6.001 0 0 0-3.537-5.473l.82-1.824zm-.688-11.29A5.5 5.5 0 0 1 21 8.5a5.499 5.499 0 0 1-5 5.478v-2.013a3.5 3.5 0 0 0 1.041-6.609l.555-1.943z" /></svg> <?php echo __( 'Friends' );?></a>
									</li>
								<?php } ?>
								<li class="divider" tabindex="-1"></li>
                                <li>
                                    <a href="<?php echo $site_url;?>/likes" data-ajax="/likes" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228zm6.826 1.641c-1.5-1.502-3.92-1.563-5.49-.153l-1.335 1.198-1.336-1.197c-1.575-1.412-3.99-1.35-5.494.154-1.49 1.49-1.565 3.875-.192 5.451L12 18.654l7.02-7.03c1.374-1.577 1.299-3.959-.193-5.454z" /></svg> <?php echo __( 'Likes' );?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $site_url;?>/liked" data-ajax="/liked" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17.841 15.659l.176.177.178-.177a2.25 2.25 0 0 1 3.182 3.182l-3.36 3.359-3.358-3.359a2.25 2.25 0 0 1 3.182-3.182zM12 14v2a6 6 0 0 0-6 6H4a8 8 0 0 1 7.75-7.996L12 14zm0-13c3.315 0 6 2.685 6 6a5.998 5.998 0 0 1-5.775 5.996L12 13c-3.315 0-6-2.685-6-6a5.998 5.998 0 0 1 5.775-5.996L12 1zm0 2C9.79 3 8 4.79 8 7s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" /></svg> <?php echo __( 'People i liked' );?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $site_url;?>/disliked" data-ajax="/disliked" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm7 6.586l2.121-2.122 1.415 1.415L20.414 19l2.122 2.121-1.415 1.415L19 20.414l-2.121 2.122-1.415-1.415L17.586 19l-2.122-2.121 1.415-1.415L19 17.586z" /></svg> <?php echo __( 'People i disliked' );?></a>
                                </li>
                                <?php if( $config->connectivitySystem == '1' ){?>
                                <li>
                                    <a href="<?php echo $site_url;?>/friend-requests" data-ajax="/friend-requests"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm6 6v-3h2v3h3v2h-3v3h-2v-3h-3v-2h3z" /></svg> <?php echo __( 'Friend requests' );?></a>
                                </li>
                                <?php } ?>
								<li class="divider" tabindex="-1"></li>
								<li>
                                    <a href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2.213 14.06a9.945 9.945 0 0 1 0-4.12c1.11.13 2.08-.237 2.396-1.001.317-.765-.108-1.71-.986-2.403a9.945 9.945 0 0 1 2.913-2.913c.692.877 1.638 1.303 2.403.986.765-.317 1.132-1.286 1.001-2.396a9.945 9.945 0 0 1 4.12 0c-.13 1.11.237 2.08 1.001 2.396.765.317 1.71-.108 2.403-.986a9.945 9.945 0 0 1 2.913 2.913c-.877.692-1.303 1.638-.986 2.403.317.765 1.286 1.132 2.396 1.001a9.945 9.945 0 0 1 0 4.12c-1.11-.13-2.08.237-2.396 1.001-.317.765.108 1.71.986 2.403a9.945 9.945 0 0 1-2.913 2.913c-.692-.877-1.638-1.303-2.403-.986-.765.317-1.132 1.286-1.001 2.396a9.945 9.945 0 0 1-4.12 0c.13-1.11-.237-2.08-1.001-2.396-.765-.317-1.71.108-2.403.986a9.945 9.945 0 0 1-2.913-2.913c.877-.692 1.303-1.638.986-2.403-.317-.765-1.286-1.132-2.396-1.001zM4 12.21c1.1.305 2.007 1.002 2.457 2.086.449 1.085.3 2.22-.262 3.212.096.102.195.201.297.297.993-.562 2.127-.71 3.212-.262 1.084.45 1.781 1.357 2.086 2.457.14.004.28.004.42 0 .305-1.1 1.002-2.007 2.086-2.457 1.085-.449 2.22-.3 3.212.262.102-.096.201-.195.297-.297-.562-.993-.71-2.127-.262-3.212.45-1.084 1.357-1.781 2.457-2.086.004-.14.004-.28 0-.42-1.1-.305-2.007-1.002-2.457-2.086-.449-1.085-.3-2.22.262-3.212a7.935 7.935 0 0 0-.297-.297c-.993.562-2.127.71-3.212.262C13.212 6.007 12.515 5.1 12.21 4a7.935 7.935 0 0 0-.42 0c-.305 1.1-1.002 2.007-2.086 2.457-1.085.449-2.22.3-3.212-.262-.102.096-.201.195-.297.297.562.993.71 2.127.262 3.212C6.007 10.788 5.1 11.485 4 11.79c-.004.14-.004.28 0 .42zM12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" /></svg> <?php echo __( 'Settings' );?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $site_url;?>/transactions" data-ajax="/transactions" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19.375 15.103A8.001 8.001 0 0 0 8.03 5.053l-.992-1.737A9.996 9.996 0 0 1 17 3.34c4.49 2.592 6.21 8.142 4.117 12.77l1.342.774-4.165 2.214-.165-4.714 1.246.719zM4.625 8.897a8.001 8.001 0 0 0 11.345 10.05l.992 1.737A9.996 9.996 0 0 1 7 20.66C2.51 18.068.79 12.518 2.883 7.89L1.54 7.117l4.165-2.214.165 4.714-1.246-.719zM8.5 14H14a.5.5 0 1 0 0-1h-4a2.5 2.5 0 1 1 0-5h1V7h2v1h2.5v2H10a.5.5 0 1 0 0 1h4a2.5 2.5 0 1 1 0 5h-1v1h-2v-1H8.5v-2z" /></svg> <?php echo __( 'Transactions' );?></a>
                                </li>
                                <?php if( $profile->admin == 1){ ?>
								<li class="divider" tabindex="-1"></li>
                                <li>
                                    <a href="<?php echo $site_url;?>/admin-cp" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.75 2.5A4.25 4.25 0 0 1 11 6.75V11H6.75a4.25 4.25 0 1 1 0-8.5zM9 9V6.75A2.25 2.25 0 1 0 6.75 9H9zm-2.25 4H11v4.25A4.25 4.25 0 1 1 6.75 13zm0 2A2.25 2.25 0 1 0 9 17.25V15H6.75zm10.5-12.5a4.25 4.25 0 1 1 0 8.5H13V6.75a4.25 4.25 0 0 1 4.25-4.25zm0 6.5A2.25 2.25 0 1 0 15 6.75V9h2.25zM13 13h4.25A4.25 4.25 0 1 1 13 17.25V13zm2 2v2.25A2.25 2.25 0 1 0 17.25 15H15z" /></svg> <?php echo __( 'Admin Panel' );?></a>
                                </li>
                                <?php } ?>
                                <li class="divider" tabindex="-1"></li>
                                <li>
									<a href="javascript:void(0);" onclick="logout()" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M16.56,5.44L15.11,6.89C16.84,7.94 18,9.83 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12C6,9.83 7.16,7.94 8.88,6.88L7.44,5.44C5.36,6.88 4,9.28 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12C20,9.28 18.64,6.88 16.56,5.44M13,3H11V13H13" /></svg> <?php echo __( 'Log Out' );?></a>
                                </li>
								<li class="divider night_day" tabindex="-1"></li>
								<li>
									<a href="javascript:void(0);" id="night_mode_toggle" class="<?php echo Secure($config->nextmode) ?>" data-night-text="<?php echo __('Night mode');?>" data-light-text="<?php echo __('Day mode');?>" data-mode='<?php echo Secure($config->nextmode) ?>'>
										<div class="dayy">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z" /></svg> <?php echo __('Day mode');?>
										</div>
										<div class="nightt">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17.75,4.09L15.22,6.03L16.13,9.09L13.5,7.28L10.87,9.09L11.78,6.03L9.25,4.09L12.44,4L13.5,1L14.56,4L17.75,4.09M21.25,11L19.61,12.25L20.2,14.23L18.5,13.06L16.8,14.23L17.39,12.25L15.75,11L17.81,10.95L18.5,9L19.19,10.95L21.25,11M18.97,15.95C19.8,15.87 20.69,17.05 20.16,17.8C19.84,18.25 19.5,18.67 19.08,19.07C15.17,23 8.84,23 4.94,19.07C1.03,15.17 1.03,8.83 4.94,4.93C5.34,4.53 5.76,4.17 6.21,3.85C6.96,3.32 8.14,4.21 8.06,5.04C7.79,7.9 8.75,10.87 10.95,13.06C13.14,15.26 16.1,16.22 18.97,15.95M17.33,17.97C14.5,17.81 11.7,16.64 9.53,14.5C7.36,12.31 6.2,9.5 6.04,6.68C3.23,9.82 3.34,14.64 6.35,17.66C9.37,20.67 14.19,20.78 17.33,17.97Z" /></svg> <?php echo __('Night mode');?>
										</div>
									</a>
								</li>
                            </ul>
                        </li>
                    </ul>
                <?php }else{ ?>
                    <ul class="right">
                        <li class="header_user">
                            <a href="javascript:void(0);" data-target="user_dropdown" class="dropdown-trigger btn-flat">
                                <img src="<?php echo $profile->avater->avater;?>" alt="<?php echo $profile->full_name;?>" />
                                <span><?php echo __( 'Hi,' );?> <?php echo $profile->first_name;?></span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/></svg>
                            </a>
                            <ul id="user_dropdown" class="dropdown-content">
                                <li>
                                    <a href="javascript:void(0);" onclick="logout()" class="waves-effect"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M16.56,5.44L15.11,6.89C16.84,7.94 18,9.83 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12C6,9.83 7.16,7.94 8.88,6.88L7.44,5.44C5.36,6.88 4,9.28 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12C20,9.28 18.64,6.88 16.56,5.44M13,3H11V13H13" /></svg> <?php echo __( 'Log Out' );?></a>
                                </li>
                                <li class="divider night_day" tabindex="-1"></li>
                                <li>
                                    <a href="javascript:void(0);" id="night_mode_toggle" class="<?php echo Secure($config->nextmode) ?>" data-night-text="<?php echo __('Night mode');?>" data-light-text="<?php echo __('Day mode');?>" data-mode='<?php echo Secure($config->nextmode) ?>'>
										<div class="dayy">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z" /></svg> <?php echo __('Day mode');?>
										</div>
										<div class="nightt">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17.75,4.09L15.22,6.03L16.13,9.09L13.5,7.28L10.87,9.09L11.78,6.03L9.25,4.09L12.44,4L13.5,1L14.56,4L17.75,4.09M21.25,11L19.61,12.25L20.2,14.23L18.5,13.06L16.8,14.23L17.39,12.25L15.75,11L17.81,10.95L18.5,9L19.19,10.95L21.25,11M18.97,15.95C19.8,15.87 20.69,17.05 20.16,17.8C19.84,18.25 19.5,18.67 19.08,19.07C15.17,23 8.84,23 4.94,19.07C1.03,15.17 1.03,8.83 4.94,4.93C5.34,4.53 5.76,4.17 6.21,3.85C6.96,3.32 8.14,4.21 8.06,5.04C7.79,7.9 8.75,10.87 10.95,13.06C13.14,15.26 16.1,16.22 18.97,15.95M17.33,17.97C14.5,17.81 11.7,16.64 9.53,14.5C7.36,12.31 6.2,9.5 6.04,6.68C3.23,9.82 3.34,14.64 6.35,17.66C9.37,20.67 14.19,20.78 17.33,17.97Z" /></svg> <?php echo __('Night mode');?>
										</div>
									</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php }?>
            </div>
        </nav>
		
        <!-- End Header  -->

        <?php require( $theme_path . 'main' . $_DS . 'chat.php' );?>