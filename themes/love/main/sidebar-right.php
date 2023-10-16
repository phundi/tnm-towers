<div class="dt_left_sidebar pro">
	<?php if( $config->pro_system == 1 ){ ?>
		<?php
		
			global $db;
			$where = "user_id=" .  $profile->id . " AND status = 0 AND type='PRO' AND via IN ('FDH Bank','First Capital Bank','NBS Bank','Centenary Bank','TNM Mpamba','Mukuru','National Bank','Standard Bank')";
			$requests= $db->where($where)->getOne('payment_requests');
			$sub_text = 'Subscribe';
			if (!empty($requests)) {
					$sub_text = 'Subscription Pending!';
			}
			
			$pro_users = ProUsers();
			if( count((array)$pro_users) > 0){
		?>
			<div class="dt_home_pro_usr">
				<h4 class="bold">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M12 .5l4.226 6.183 7.187 2.109-4.575 5.93.215 7.486L12 19.69l-7.053 2.518.215-7.486-4.575-5.93 7.187-2.109L12 .5zm0 3.544L9.022 8.402 3.957 9.887l3.225 4.178-.153 5.275L12 17.566l4.97 1.774-.152-5.275 3.224-4.178-5.064-1.485L12 4.044zM10 12a2 2 0 1 0 4 0h2a4 4 0 1 1-8 0h2z" /></svg>
					<?php if( $config->pro_system == 1 ) { ?>
						<?php echo __( 'Premium Users' );?>
					<?php } else{ ?>
						<?php echo __( 'Latest Users' );?>
					<?php } ?>
				</h4>
				<div class="pro_usrs_container">
					<?php
						if( $pro_users ){
							foreach ($pro_users as $key => $pro_user ){
					?>
						<div class="pro_usr">
							<a href="<?php echo $site_url;?>/@<?php echo $pro_user->username;?>" data-ajax="/@<?php echo $pro_user->username;?>" title="<?php echo $pro_user->username;?>">
								<div><img src="<?php echo GetMedia( $pro_user->avater );?>" alt="<?php echo $pro_user->username;?>" /></div>
							</a>
						</div>
					<?php } } ?>
				</div>
				<?php if(IS_LOGGED && !empty(auth()) && auth()->is_pro == 0){?>
					<div class="add_me">
						<div class="blank"></div>
						<a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="prem">
							<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M2 19h20v2H2v-2zM2 5l5 3.5L12 2l5 6.5L22 5v12H2V5zm2 3.841V15h16V8.841l-3.42 2.394L12 5.28l-4.58 5.955L4 8.84z" fill="currentColor"></path></svg></span> <?php echo __( 'go_premium' );?>
						</a>
					</div>
				<?php } ?>
			</div>
		<?php } else { ?>
			<div class="dt_home_pro_usr">
				<h4 class="bold">
					<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M12 .5l4.226 6.183 7.187 2.109-4.575 5.93.215 7.486L12 19.69l-7.053 2.518.215-7.486-4.575-5.93 7.187-2.109L12 .5zm0 3.544L9.022 8.402 3.957 9.887l3.225 4.178-.153 5.275L12 17.566l4.97 1.774-.152-5.275 3.224-4.178-5.064-1.485L12 4.044zM10 12a2 2 0 1 0 4 0h2a4 4 0 1 1-8 0h2z" /></svg>
					<?php if( $config->pro_system == 1 ) { ?>
						<?php echo __( 'Premium Users' );?>
					<?php } else { ?>
						<?php echo __( 'Latest Users' );?>
					<?php } ?>
				</h4>
				<div class="pro_usrs_container">
					<?php if(IS_LOGGED && !empty(auth()) && auth()->is_pro == 0){?>
						<div class="add_me">
							<div class="blank"></div>
							<a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="prem">
								<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M2 19h20v2H2v-2zM2 5l5 3.5L12 2l5 6.5L22 5v12H2V5zm2 3.841V15h16V8.841l-3.42 2.394L12 5.28l-4.58 5.955L4 8.84z" fill="currentColor"></path></svg></span> <?php $sub_text;?>
							</a>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
</div>

<div class="dt_sections">
	<div class="dt_right_side_story">
		<?php 
			global $db;
			$stories = $db->orderBy('RAND()')->where('status', 1)->objectbuilder()->get('success_stories',5);
			foreach ($stories as $key => $story) {
				$story->user = userData($story->user_id);
		        $story->story_user = userData($story->story_user_id);

		?>
		<div class="dt_right_side_story_list">
			<div class="images">
				<img src="<?php echo $story->user->avater->avater;?>"/>
				<img src="<?php echo $story->story_user->avater->avater;?>"/>
			</div>
			<a href="<?php echo $site_url;?>/story/<?php echo $story->id. '_'. url_slug($story->quote);?>" data-ajax="/story/<?php echo $story->id . '_'. url_slug($story->quote);?>">
				<h5><?php echo ($story->user->first_name ) ;?> & <?php echo ($story->story_user->first_name ) ;?></h5>
				<hr>
				<p><?php echo __( 'Story Begins' );?></p>
				<time><?php echo $story->story_date;?></time>
			</a>
		</div>
		<?php } ?>
	</div>
</div>

<?php if (!empty($config->native_android_url) || !empty($config->native_ios_url)) { ?>
	<div class="dt_sections">
		<div class="dt_home_pro_usr">
			<!--<h4 class="bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M17,1H7A2,2 0 0,0 5,3V21A2,2 0 0,0 7,23H17A2,2 0 0,0 19,21V3A2,2 0 0,0 17,1M17,19H7V5H17V19M16,13H13V8H11V13H8L12,17L16,13Z" /></svg>
                <?php echo __( 'apps' );?>
            </h4>-->

            <div class="dt_side_apps">
            	<?php if (!empty($config->native_android_url) || !empty($config->native_ios_url)) { ?>
	            	<?php if (!empty($config->native_android_url)) { ?>
	            		<a href="<?php echo($config->native_android_url) ?>" target="_blank">
							<img width="130" src="<?php echo $theme_url;?>assets/img/google.png"/>
	                    </a>
	            	<?php } ?>
	            	<?php if (!empty($config->native_ios_url)) { ?>
	            		<a href="<?php echo($config->native_ios_url) ?>" target="_blank">
							<img width="130" src="<?php echo $theme_url;?>assets/img/apple.png"/>
	                    </a>
	            	<?php } ?>
            	<?php } ?>
            </div>
        </div>
	</div>
<?php } ?>
<?php echo GetAd('home_side_bar');?>
