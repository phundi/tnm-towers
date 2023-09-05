<?php global $site_url; ?>
<div class="mtc_usrd_content" data-id="<?php echo $matche->id;?>" <?php if($matche_first === false){?> style="display: none;"<?php }?>>
    <div class="row no_margin r_margin">
        <div class="col <?php if( $mode == 'hot' ){?>s12<?php }else{ ?>s12 m6<?php }?>">
            <div class="mtc_usrd_slider">
				<?php if( $mode == 'hot' ){?>
					<div class="row">
						<div class="col l4 m6 s12">
							<div class="valign-wrapper mtc_usrd_top">
								<div class="mtc_usrd_summary">
									<div class="usr_name">
										<?php
											$_age = getAge($matche->birthday);
											$_location = $matche->country;
										?>
										<a href="<?php echo $site_url;?>/@<?php echo $matche->username;?>" data-ajax="/@<?php echo $matche->username;?>"><?php echo ($matche->first_name !== '' ) ? $matche->first_name . ' ' . $matche->last_name : $matche->username;?></a>
									</div>
									<?php if( !empty($_age) ) {?><span class="usr_age"><?php echo $_age;?> <?php echo __('yr age');?>,</span><?php }?>
									<span class="usr_location"><?php echo $matche->gender;?></span>
									<?php if( !empty($_location) ) {?><br><span class="usr_location"><?php echo __('From');?> <?php echo $_location;?></span><?php }?>
								</div>
							</div>
							<div class="mtc_usrd_about">
								<b><?php echo __('About');?>:</b>
								<p><?php echo($matche->about); ?></p>
							</div>
							<p class="mtc_usrd_about_int">Interest on Women, between 20-35 yr age.</p>
						</div>
						<div class="col l5 m6 s12">
							<div class="qd_hot_not_media">
							<?php $x = 0;$uname = ''; foreach ($matche->mediafiles as $key => $mfile){ if( $x == 1 ) { continue; } else { $uname = $matche->username;?>
								<a href="<?php echo $mfile['full'];?>" data-id="<?php echo $mfile['id'];?>" data-fancybox class="inline" rel="group-<?php echo $uname;?>">
									<img alt="<?php echo $matche->username;?>" src="<?php echo $mfile['avater'];?>">
								</a>
							<?php }$x++;} ?>
								<div class="mtc_usrd_actions">
									<a href="<?php echo $site_url;?>" class="btn waves-effect dislike" flow="down" tooltip="<?php echo __('home');?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="27.027" height="24.141" viewBox="0 0 27.027 24.141"> <path id="Path_4856" data-name="Path 4856" d="M3439.342,1287.262a1.217,1.217,0,0,1-1.229,1.229h-17.2a1.216,1.216,0,0,1-1.229-1.229v-11.057H3416l12.69-11.536a1.22,1.22,0,0,1,1.646,0l12.691,11.536h-3.686Zm-2.457-1.229v-12.089l-7.371-6.708-7.371,6.708v12.089Zm-7.371-2.457-4.128-4.128a2.945,2.945,0,0,1-.6-.9,2.775,2.775,0,0,1,0-2.113,2.949,2.949,0,0,1,.6-.9,2.751,2.751,0,0,1,.9-.6,2.84,2.84,0,0,1,1.056-.209,2.793,2.793,0,0,1,1.057.209,2.619,2.619,0,0,1,.9.6l.221.221.221-.221a2.622,2.622,0,0,1,.9-.6,2.8,2.8,0,0,1,1.057-.209,2.84,2.84,0,0,1,1.057.209,2.751,2.751,0,0,1,.9.6,2.958,2.958,0,0,1,.6.9,2.781,2.781,0,0,1,0,2.113,2.954,2.954,0,0,1-.6.9Z" transform="translate(-3416 -1264.35)" fill="currentColor"/> </svg>
									</a>
									<?php if( Auth()->verified == "1" ) { ?>
										<button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_like_btn" data-ajax-post="/useractions/hot" data-source="hot" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=hot" data-ajax-callback="callback_hot" class="btn waves-effect like hot" flow="down" tooltip="<?php echo __('match_hot');?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="40.593" height="56.593" viewBox="0 0 40.593 56.593"> <path id="Subtraction_11" data-name="Subtraction 11" d="M406.609-26.407a20,20,0,0,1-14.131-5.769,19.541,19.541,0,0,1-4.5-6.771c-.207-.417-.416-.889-.62-1.4a19.336,19.336,0,0,1-1.265-9.061,24.623,24.623,0,0,1,2.089-7.661,30.238,30.238,0,0,1,4.571-7.281c0,.009-.407,1.931-.685,4.026-.041.267-.077.538-.11.827l.01-.011.011-.011a23.955,23.955,0,0,0-.126,2.531c.18,5.483,3.148,8.109,4.416,8.983a4.019,4.019,0,0,0,1.923.688,1.069,1.069,0,0,0,1.036-.613c.452-.915.021-2.779-.922-3.988-1.626-2.085-2.9-6.475-2.075-11.582a23.7,23.7,0,0,1,12.012-16.92,33.243,33.243,0,0,1,5.4-2.576,22.826,22.826,0,0,0-2.022,7.206,13.6,13.6,0,0,0,5.2,12.732h-.05A19.956,19.956,0,0,1,423.889-56a19.464,19.464,0,0,1,2.7,9.9,19.425,19.425,0,0,1-5.853,13.927A20,20,0,0,1,406.609-26.407Zm1.448-33.959h0l-.488,1.323a10.454,10.454,0,0,0-.687,3.165,15.418,15.418,0,0,0,1.6,7.9,5.158,5.158,0,0,1,.233,4.3c-1.445,3.631-6.769,5.726-6.823,5.747a14.77,14.77,0,0,0,6.088,1.458,8.527,8.527,0,0,0,6.97-3.262,6.315,6.315,0,0,0,1.537-5.019c-.557-3.649-4.431-6.3-4.47-6.327a11.588,11.588,0,0,1-2.042-1.9,7.3,7.3,0,0,1-1.921-4.854,14.119,14.119,0,0,0,0-2.526Z" transform="translate(-386.001 83)" fill="currentColor"/> </svg>
										</button>
										<button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_dislike_btn" data-ajax-post="/useractions/not" data-source="hot" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=hot" data-ajax-callback="callback_not" class="btn waves-effect dislike hot" flow="down" tooltip="<?php echo __('dislike');?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="23.843" height="22.065" viewBox="0 0 23.843 22.065"> <path id="Path_215778" data-name="Path 215778" d="M3312.1,8303.089h-6.936a2.182,2.182,0,0,1-2.167-2.167v-2.275a2.209,2.209,0,0,1,.162-.836l3.36-8.139a1.078,1.078,0,0,1,.39-.487,1.1,1.1,0,0,1,.607-.185h18.24a1.074,1.074,0,0,1,1.084,1.084v10.838a1.072,1.072,0,0,1-1.084,1.083h-3.771a1.122,1.122,0,0,0-.5.12,1.174,1.174,0,0,0-.39.335l-5.906,8.379a.54.54,0,0,1-.314.216.6.6,0,0,1-.368-.044l-1.972-.985a2.722,2.722,0,0,1-1.409-3.089Zm8.237-2.807v-9.114h-12.095l-3.078,7.479v2.275h6.936a2.236,2.236,0,0,1,.954.217,2.32,2.32,0,0,1,.759.617,2.088,2.088,0,0,1,.412.889,2.284,2.284,0,0,1-.022.976l-.976,3.848a.573.573,0,0,0,.022.357.512.512,0,0,0,.26.261l.716.357,5.1-7.229A3.444,3.444,0,0,1,3320.34,8300.282Zm2.167-.444h2.168v-8.67h-2.168Z" transform="translate(-3303 -8289)" fill="currentColor"/> </svg>
										</button>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="col l3 m12 s12 btnss">
							<?php if( Auth()->verified == "1" ) { ?>
								<div class="mtc_usrd_actions">
									<button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_dislike_btn" data-ajax-post="/useractions/not" data-source="hot" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=hot" data-ajax-callback="callback_not" class="btn waves-effect like hot" flow="down" tooltip="<?php echo __('match_ignore');?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="20.741" height="20.74" viewBox="0 0 20.741 20.74"> <path id="Path_6729" data-name="Path 6729" d="M3597.3,7851.161l7.521-7.521,2.143,2.142-7.521,7.521,7.521,7.521-2.143,2.142-7.521-7.521-7.521,7.521-2.143-2.142,7.521-7.521-7.521-7.521,2.143-2.142Z" transform="translate(-3586.933 -7842.934)" fill="currentColor" stroke="#fff" stroke-width="1"/> </svg>
									</button>
									<button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_dislike_btn" data-ajax-post="/useractions/not" data-source="hot" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=hot" data-ajax-callback="callback_not" class="btn waves-effect dislike hot" flow="down" tooltip="<?php echo __('next');?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="9 6 15 12 9 18"></polyline></svg>
									</button>
								</div>
							<?php } ?>
						</div>
					</div>
                    <script>
                    $('a[rel="group-<?php echo $uname;?>"]').fancybox({
                        'transitionIn'      : 'none',
                        'transitionOut'     : 'none',
                        'titlePosition'     : 'over',
                        'cyclic'            : true,
                        'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
                            return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
                        }
                    });
                    </script>
				<?php }else{ ?>
                <div class="carousel carousel-slider center match_usr_img_slidr">
                    <?php if(count($matche->mediafiles) > 1){?>
                        <span class="changer back" onclick="Previous_Picture();"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" /></svg></span>
                        <span class="changer next" onclick="Next_Picture();"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" /></svg></span>
                    <?php }else{
                        //echo '<div class="carousel-item"><img alt="'.$matche->username.'" src="'. GetMedia('',false) . $matche->avater.'"></div>';
                    }?>

                    <?php foreach ($matche->mediafiles as $key => $mfile){?>
                        <div class="carousel-item">
                            <img alt="<?php echo $matche->username;?>" src="<?php echo $mfile['avater'];?>">
                        </div>
                    <?php } ?>
                </div>
				<?php }?>
            </div>
        </div>
		<?php if( $mode == 'hot' ){?>
		<?php }else{ ?>
        <div class="col s12 m6">
            <div class="mtc_usrd_sidebar flex_btn">
				<div class="mtc_usrd_summary">
					<h5><?php echo __('About');?> <a href="<?php echo $site_url;?>/@<?php echo $matche->username;?>" data-ajax="/@<?php echo $matche->username;?>"><?php echo ($matche->first_name !== '' ) ? $matche->first_name . ' ' . $matche->last_name : $matche->username;?></a></h5>
					
					<a class="edit" href="<?php echo $site_url;?>/@<?php echo $matche->username;?>" data-ajax="/@<?php echo $matche->username;?>" title="<?php echo __('view_profile');?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="10" r="3"></circle><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path></svg>
					</a>
				</div>
                <div class="row sidebar_usr_info">
					<?php
						$_age = getAge($matche->birthday);
						$_location = $matche->country;
					?>
					<div class="col s6">
						<?php if($matche->language){?>
							<div>
								<p class="info_title"><?php echo __('Preferred Language');?></p>
								<span><?php echo __($matche->language);?></span>
							</div>
						<?php }?>
						<?php if($matche->relationship){?>
							<div>
								<p class="info_title"><?php echo __('Relationship status');?></p>
								<span><?php echo $matche->relationship;?></span>
							</div>
						<?php }?>
						<?php if($matche->height){?>
							<div>
								<p class="info_title"><?php echo __('Height');?></p>
								<span><?php echo $matche->height;?></span>
							</div>
						<?php }?>
					</div>
					<div class="col s6">
						<?php if($matche->body){?>
							<div>
								<p class="info_title"><?php echo __('Body Type');?></p>
								<span><?php echo $matche->body;?></span>
							</div>
						<?php }?>
						<?php if( !empty($_age) ) {?>
							<div>
								<p class="info_title"><?php echo __('Age');?></p>
								<span><?php echo $_age;?></span>
							</div>
						<?php }?>
						<?php if( !empty($_location) ) {?>
							<div>
								<p class="info_title"><?php echo __('Location');?></p>
								<span><?php echo $_location;?></span>
							</div>
						<?php }?>
					</div>
                </div>
                <?php if( Auth()->verified == "1" ) { ?>
				<div class="center mtc_usrd_actions">

					<button   class="btn waves-effect msg yellow_bg"  id="btn_open_private_conversation" 
						<?php
						if( Auth()->is_pro == "1"){ ?>
							href="javascript:void(0);"  data-ajax-post="/chat/open_private_conversation" 
							data-ajax-params="from=<?php echo $matche->id;?>&web_device_id=<?php echo $matche->web_device_id;?>" data-ajax-callback="open_private_conversation" 
						<?php } else { ?>
							onclick="window.location='/pro'"                        
						<?php } ?>
						
						style="margin-right: 10px;" 
						
						>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 22.5L11.2 19H6a1 1 0 0 1-1-1V7.103a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1V18a1 1 0 0 1-1 1h-5.2L14 22.5zm1.839-5.5H21V8.103H7V17H12.161L14 19.298 15.839 17zM2 2h17v2H3v11H1V3a1 1 0 0 1 1-1z"/></svg>
					</button>
									
					<button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_like_btn" data-ajax-post="/useractions/like" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=find-matches" data-ajax-callback="callback_like" class="btn waves-effect like" title="<?php echo __('Like');?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path></svg></button>
					<button href="javascript:void(0);" data-userid="<?php echo $matche->id;?>" id="matches_dislike_btn" data-ajax-post="/useractions/dislike" data-source="find-matches" data-ajax-params="userid=<?php echo $matche->id;?>&username=<?php echo $matche->username;?>&source=find-matches" data-ajax-callback="callback_dislike" class="btn waves-effect dislike" title="<?php echo __('Dislike');?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"></path></svg></button>
				</div>
                <?php } ?>
            </div>
        </div>
		<?php }?>
    </div>
</div>