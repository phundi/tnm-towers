<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
		<div class="col l6">
			<!-- Popularity  -->
			<div class="dt_credits dt_sections">
				<div class="dt_pop_level">
					<h2><?php echo __('Your Popularity:');?> <span><?php echo GetUserPopularity($profile->id);?></span></h2>
					<p><?php echo __('Increase your Popularity with Credits and enjoy the features.');?></p>
					
					<hr class="border_hr">
				</div>
				
				<div class="row dt_popular">
					<div class="col s12">
						<h2 class="center"><?php echo str_replace('{{sitename}}', $config->site_name, __('Meet more People with {{sitename}} Credits'));?></h2>
					</div>
					<div class="col s12 m6 l4" id="buy_more_visits">
						<div class="dt_poplrity_cont visits">
							<div class="dt_poplrity_cont_innr">
								<svg xmlns="http://www.w3.org/2000/svg" width="136.769" height="154.074" viewBox="0 0 136.769 154.074"><path id="Path_215805" data-name="Path 215805" d="M6472.355-5813.524h0l-34.6-31.141,34.6-31.137v24.574h21.817v13.132h-21.817v24.574Zm-101.816-2.755H6357.4a57.484,57.484,0,0,1,5.888-24.434l0,0a52.991,52.991,0,0,1,16.359-20.1l.036-.028a49.356,49.356,0,0,1,23.594-10.759l.056-.012a49.374,49.374,0,0,1,12.037-1.535,43.1,43.1,0,0,1,13.339,2.083v13.071a42.379,42.379,0,0,0-12.458-1.95,36.592,36.592,0,0,0-7.456.761,42.716,42.716,0,0,0-10.224,2.31,35.056,35.056,0,0,0-9.293,5.1,49.9,49.9,0,0,0-13.792,15.5l-.017.031a49.007,49.007,0,0,0-3.688,10.053,44.16,44.16,0,0,0-1.25,9.913Zm44.34-65.454a42.982,42.982,0,0,1-42.932-42.935,42.98,42.98,0,0,1,42.932-42.93,42.979,42.979,0,0,1,42.931,42.93A42.982,42.982,0,0,1,6414.879-5881.733Zm0-72.732a29.833,29.833,0,0,0-29.8,29.8,29.835,29.835,0,0,0,29.8,29.8,29.834,29.834,0,0,0,29.8-29.8A29.832,29.832,0,0,0,6414.879-5954.466Z" transform="translate(-6357.404 5967.599)" fill="#502c8f" opacity="0.2"/></svg>
								<div class="dt_pop_hdr">
									<h3><?php echo __('Increase');?> <?php echo __('visits');?></h3>
								</div>
								<div class="dt_pop_mdl">
									<h2>10X<span><?php echo __('visits');?></span></h2>
								</div>
								<div class="dt_pop_ftr">
									<?php if( $profile->user_buy_xvisits == '1' ){
										$xvisits_duration = 0;
										if( $profile->xvisits_created_at > 0 ) {
											$xvisits_duration = ( time() - $profile->xvisits_created_at ) / 60;
										}else{
											$xvisits_duration = $config->xvisits_expire_time;
										}
										$xvisits_duration = $config->xvisits_expire_time - $xvisits_duration;
									?>
										<div class="boosted_message_expire" data-message-expire="<button data-target='buy_xvisits' class='btn modal-trigger'><?php echo __('Increase');?></button>"><?php echo __('Your x10 visits will expire in');?> <span class="boosted_time" data-boosted-time="<?php echo $xvisits_duration;?>"></span> <?php echo __( 'minutes');?></div>
									<?php }else{ ?>
										<button data-target="buy_xvisits" class="btn modal-trigger"><?php echo __('Increase');?></button>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col s12 m6 l4" id="buy_more_matches">
						<div class="dt_poplrity_cont matches">
							<div class="dt_poplrity_cont_innr">
								<svg xmlns="http://www.w3.org/2000/svg" width="134.411" height="155.924" viewBox="0 0 134.411 155.924"> <path id="Subtraction_54" data-name="Subtraction 54" d="M6096.86-5811.676h0l-24.647-24.436,9.249-9.187,14.906,14.775.493.489,25.709-25.421,9.247,9.17-34.956,34.61Zm-86.329-4.8H5997.4a57.47,57.47,0,0,1,5.88-24.416,53.036,53.036,0,0,1,16.341-20.075l.035-.028a49.3,49.3,0,0,1,23.565-10.737l.059-.013a49.092,49.092,0,0,1,12.033-1.541,42.912,42.912,0,0,1,13.313,2.083v13.069a42.274,42.274,0,0,0-12.426-1.949,36.468,36.468,0,0,0-7.466.767,42.632,42.632,0,0,0-10.208,2.3,34.906,34.906,0,0,0-9.279,5.091,49.929,49.929,0,0,0-13.773,15.475l-.017.031a49.013,49.013,0,0,0-3.681,10.036,44.146,44.146,0,0,0-1.25,9.908Zm44.275-65.373a42.929,42.929,0,0,1-42.881-42.881,42.925,42.925,0,0,1,42.881-42.872,42.923,42.923,0,0,1,42.878,42.872A42.928,42.928,0,0,1,6054.807-5881.848Zm0-72.62a29.78,29.78,0,0,0-29.754,29.739,29.794,29.794,0,0,0,29.754,29.766,29.792,29.792,0,0,0,29.75-29.766A29.779,29.779,0,0,0,6054.807-5954.468Z" transform="translate(-5997.405 5967.601)" fill="#3d650d" opacity="0.2"/> </svg>
								<div class="dt_pop_hdr">
									<h3><?php echo __('Increase');?> <?php echo __('matches');?></h3>
								</div>
								<div class="dt_pop_mdl">
									<h2>3X<span><?php echo __('matches');?></span></h2>
								</div>
								<div class="dt_pop_ftr">
									<?php if( $profile->is_boosted == '1' ){
										$xmatches_duration = 0;
										if( $profile->boosted_time > 0 ) {
											$xmatches_duration = ( time() - $profile->boosted_time ) / 60;
										}else{
											$xmatches_duration = $config->boosted_time;
										}
										$xmatches_duration = $config->boost_expire_time - $xmatches_duration;
									?>
										<div class="boosted_message_expire" data-message-expire="<button data-target='buy_xmatches' class='btn modal-trigger'><?php echo __('Increase');?></button>"><?php echo __('Your x3 matches will expire in');?> <span class="boosted_time" data-boosted-time="<?php echo $xmatches_duration;?>"></span> <?php echo __( 'minutes');?></div>
									<?php }else{ ?>
										<button data-target="buy_xmatches" class="btn modal-trigger"><?php echo __('Increase');?></button>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col s12 m6 l4" id="buy_more_likes">
						<div class="dt_poplrity_cont likes">
							<div class="dt_poplrity_cont_innr">
								<svg xmlns="http://www.w3.org/2000/svg" width="131.773" height="154.775" viewBox="0 0 131.773 154.775"> <path id="Path_215803" data-name="Path 215803" d="M6460.617-5812.826v0l-24.056-24.342-.03-.033a15.215,15.215,0,0,1-4.478-10.893,14.953,14.953,0,0,1,4.478-10.149l.057-.053.054-.064a13.363,13.363,0,0,1,10.762-4.713,13.364,13.364,0,0,1,10.763,4.713l.174.207,2.3,1.274,2.128-1.248.12-.116a15.147,15.147,0,0,1,4.944-3.42,18.123,18.123,0,0,1,6-1.41,18.056,18.056,0,0,1,5.925,1.41,15.21,15.21,0,0,1,4.945,3.42,11.692,11.692,0,0,1,3.226,4.681l.017.044a15.924,15.924,0,0,1,1.238,5.425,16.6,16.6,0,0,1-1.241,6.181l-.014.041a11.648,11.648,0,0,1-3.226,4.671l-24.084,24.375Zm-91.168-.884h-12.04a58.5,58.5,0,0,1,16.45-39.351c10.088-10.511,24.286-16.718,39.979-17.478h.383v12.057a44.132,44.132,0,0,0-31.24,13.852,44.548,44.548,0,0,0-10.034,14.733,45.221,45.221,0,0,0-3.5,16.185Zm47.892-67.187h-1.668a43.4,43.4,0,0,1-43.348-43.351c0-11.181,4.089-21.4,12.15-30.372,7.415-7.611,17.9-12.222,29.525-12.981h1.672a43.4,43.4,0,0,1,43.347,43.354c0,11.176-4.09,21.395-12.153,30.37C6439.452-5886.268,6428.966-5881.658,6417.34-5880.9Zm-1.668-74.667a31.35,31.35,0,0,0-31.313,31.316,31.349,31.349,0,0,0,31.313,31.313,31.348,31.348,0,0,0,31.313-31.313A31.35,31.35,0,0,0,6415.672-5955.564Z" transform="translate(-6357.409 5967.601)" fill="#86168b" opacity="0.2"/> </svg>
								<div class="dt_pop_hdr">
									<h3><?php echo __('Increase');?> <?php echo __('likes');?></h3>
								</div>
								<div class="dt_pop_mdl">
									<h2>4X<span><?php echo __('likes');?></span></h2>
								</div>
								<div class="dt_pop_ftr">
									<?php if( $profile->user_buy_xlikes == '1' ){
										$xlikes_duration = 0;
										if( $profile->xlikes_created_at > 0 ) {
											$xlikes_duration = ( time() - $profile->xlikes_created_at ) / 60;
										}else{
											$xlikes_duration = $config->xlike_expire_time;
										}
										$xlikes_duration = $config->xlike_expire_time - $xlikes_duration;
									?>
										<div class="boosted_message_expire" data-message-expire="<button data-target='buy_xlikes' class='btn modal-trigger'><?php echo __('Increase');?></button>"><?php echo __('Your x4 likes will expire in');?> <span class="boosted_time" data-boosted-time="<?php echo $xlikes_duration;?>"></span> <?php echo __( 'minutes');?></div>
									<?php }else{ ?>
										<button data-target="buy_xlikes" class="btn modal-trigger"><?php echo __('Increase');?></button>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Popularity  -->

			<!-- Buy XVisits Modal -->
			<div id="buy_xvisits" class="modal">
				<div class="modal-content">
					<h6 class="bold">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,10.84 21.79,9.69 21.39,8.61L19.79,10.21C19.93,10.8 20,11.4 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4C12.6,4 13.2,4.07 13.79,4.21L15.4,2.6C14.31,2.21 13.16,2 12,2M19,2L15,6V7.5L12.45,10.05C12.3,10 12.15,10 12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12C14,11.85 14,11.7 13.95,11.55L16.5,9H18L22,5H19V2M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12H16A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8V6Z" /></svg> <?php echo __('x10 Visits');?>
					</h6>

                    <?php
                    $___cost = (int)$config->cost_per_xvisits;
                    if( isGenderFree($profile->gender) === true ){
                        $___cost = 0;
                    }
                    ?>
					<p><?php echo __("Promote your profile by get more visits") . ', '. __("this service will cost you") . ' ' . ' <span style="color:#a33596;font-weight: bold;">' . $___cost . '</span> ' . __( 'Credits') . ' ' . __('For') . ' ' . ' <span style="color:#a33596;font-weight: bold;">' . (int)$config->xvisits_expire_time . '</span> '. ' ' . __('Minutes');?></p>
					<div class="modal-footer">
						<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
						<?php if((int)$profile->balance >= $___cost ){?>
							<button data-userid="<?php echo $profile->id;?>" id="btn_buymore_xvisits" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Now' );?></button>
						<?php }else{ ?>
							<a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Credits' );?></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<!-- End Buy XVisits Modal -->

			<!-- Buy XMatches Modal -->
			<div id="buy_xmatches" class="modal">
				<div class="modal-content">
					<h6 class="bold">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,16L19.36,10.27L21,9L12,2L3,9L4.63,10.27M12,18.54L4.62,12.81L3,14.07L12,21.07L21,14.07L19.37,12.8L12,18.54Z" /></svg> <?php echo __('x3 Matches');?>
					</h6>
					<?php
						$__cost = 0;
						if( $profile->is_pro == "1" ){
							$__cost = $config->pro_boost_me_credits_price;
						}else{
							$__cost = $config->normal_boost_me_credits_price;
						}
                        if( isGenderFree($profile->gender) === true ){
                            $__cost = 0;
                        }
					?>
					<p><?php echo __("Shown more and rise up at the same time") . ', '. __("this service will cost you") . ' ' . ' <span style="color:#a33596;font-weight: bold;">' . (int)$__cost . '</span> ' . __( 'Credits') . ' ' . __('For') . ' ' . ' <span style="color:#a33596;font-weight: bold;">' . (int)$config->boost_expire_time . '</span> '. ' ' . __('Minutes');?></p>
					<div class="modal-footer">
						<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
						<?php if((int)$profile->balance >= (int)$__cost ){?>
							<button data-userid="<?php echo $profile->id;?>" id="btn_boostme" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Now' );?></button>
						<?php }else{ ?>
							<a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Credits' );?></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<!-- End Buy XMatches Modal -->

			<!-- Buy Xlikes Modal -->
			<div id="buy_xlikes" class="modal">
				<div class="modal-content">
					<h6 class="bold">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" /></svg> <?php echo __('x4 Likes');?>
					</h6>

                    <?php
                    $___cost__ = (int)$config->cost_per_xlike;
                    if( isGenderFree($profile->gender) === true ){
                        $___cost__ = 0;
                    }
                    ?>

					<p><?php echo __("Tell everyone you're online and be seen by users who want to chat") . ', '. __("this service will cost you") . ' ' . ' <span style="color:#a33596;font-weight: bold;">' . $___cost__ . '</span> ' . __( 'Credits') . ' ' . __('For') . ' ' . ' <span style="color:#a33596;font-weight: bold;">' . (int)$config->xlike_expire_time . '</span> '. ' ' . __('Minutes');?></p>
					<div class="modal-footer">
						<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
						<?php if((int)$profile->balance >= $___cost__ ){?>
							<button data-userid="<?php echo $profile->id;?>" id="btn_buymore_xlikes" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Now' );?></button>
						<?php }else{ ?>
							<a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Credits' );?></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<!-- End Buy XMatches Modal -->
		</div>
		
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
		</div>
	</div>
</div>