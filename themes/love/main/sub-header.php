<div class="center dt_hdr_sec_links">
	<ul class="">
		<li class="<?php if($data['name'] == 'find-matches'){ echo 'active';}?>">
			<a href="<?php echo $site_url;?>/find-matches" data-ajax="/find-matches"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#EF5C88" d="M19 20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9H0l10.327-9.388a1 1 0 0 1 1.346 0L22 11h-3v9zm-8-5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" /></svg><?php echo __( 'Home' );?></a>
		</li>
		<li class="<?php if($data['name'] == 'credit'){ echo 'active';}?>">
			<?php if ( isGenderFree($profile->gender) === true ) {?>
				<a href="javascript:void(0);">
			<?php }else{ ?>
				<a href="<?php echo $site_url;?>/credit" data-ajax="/credit">
			<?php } ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FF9800" d="M14 2a8 8 0 0 1 3.292 15.293A8 8 0 1 1 6.706 6.707 8.003 8.003 0 0 1 14 2zm-3 7H9v1a2.5 2.5 0 0 0-.164 4.995L9 15h2l.09.008a.5.5 0 0 1 0 .984L11 16H7v2h2v1h2v-1a2.5 2.5 0 0 0 .164-4.995L11 13H9l-.09-.008a.5.5 0 0 1 0-.984L9 12h4v-2h-2V9zm3-5a5.985 5.985 0 0 0-4.484 2.013 8 8 0 0 1 8.47 8.471A6 6 0 0 0 14 4z"></path></svg> <span id="credit_amount"><?php
									if( isGenderFree($profile->gender) === true ){
										echo __('Free');
									}else{
										echo (int)$profile->balance;
									}
								 ?></span> <?php echo __( 'Credits' );?>
			</a>
		</li>
		<li class="<?php if($data['name'] == 'matches'){ echo 'active';}?>">
			<a href="<?php echo $site_url;?>/matches" data-ajax="/matches"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#8bc34a" d="M17.841 15.659l.176.177.178-.177a2.25 2.25 0 0 1 3.182 3.182l-3.36 3.359-3.358-3.359a2.25 2.25 0 0 1 3.182-3.182zM12 14v8H4a8 8 0 0 1 7.75-7.996L12 14zm0-13c3.315 0 6 2.685 6 6s-2.685 6-6 6-6-2.685-6-6 2.685-6 6-6z" /></svg> <?php echo __( 'Matches' );?></a>
		</li>
		<li class="<?php if($data['name'] == 'visits'){ echo 'active';}?>">
			<a href="<?php echo $site_url;?>/visits" data-ajax="/visits"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#673ab7" d="M1.181 12C2.121 6.88 6.608 3 12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9zM12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-2a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" /></svg> <?php echo __( 'Visits' );?></a>
		</li>
		<?php if( $config->connectivitySystem == '1' ){?>
			<li class="<?php if($data['name'] == 'friends'){ echo 'active';}?>">
				<a href="<?php echo $site_url;?>/friends" data-ajax="/friends"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#2196f3" d="M12 11a5 5 0 0 1 5 5v6H7v-6a5 5 0 0 1 5-5zm-6.712 3.006a6.983 6.983 0 0 0-.28 1.65L5 16v6H2v-4.5a3.5 3.5 0 0 1 3.119-3.48l.17-.014zm13.424 0A3.501 3.501 0 0 1 22 17.5V22h-3v-6c0-.693-.1-1.362-.288-1.994zM5.5 8a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zm13 0a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM12 2a4 4 0 1 1 0 8 4 4 0 0 1 0-8z" /></svg> <?php echo __( 'Friends' );?></a>
			</li>
        <?php } ?>
	</ul>
</div>