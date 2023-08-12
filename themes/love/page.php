<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<?php if( $data['page_type'] == 0 ){?>
	<div class="container dt_terms">
		<div class="dt_settings_bg_wrap">
			<div class="dt_terms_content_body">
				<?php echo htmlspecialchars_decode($data['content']); ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="container dt_terms">
		<div class="dt_settings_bg_wrap">
			<div class="dt_terms_sidebar">
				<ul>
					<li><a href="<?php echo $site_url;?>/terms" data-ajax="/terms"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11 6H14L17.29 2.7A1 1 0 0 1 18.71 2.7L21.29 5.29A1 1 0 0 1 21.29 6.7L19 9H11V11A1 1 0 0 1 10 12A1 1 0 0 1 9 11V8A2 2 0 0 1 11 6M5 11V15L2.71 17.29A1 1 0 0 0 2.71 18.7L5.29 21.29A1 1 0 0 0 6.71 21.29L11 17H15A1 1 0 0 0 16 16V15H17A1 1 0 0 0 18 14V13H19A1 1 0 0 0 20 12V11H13V12A2 2 0 0 1 11 14H9A2 2 0 0 1 7 12V9Z" /></svg> <?php echo __( 'Terms of use' );?></a></li>
					<li class=""><a href="<?php echo $site_url;?>/privacy" data-ajax="/privacy"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14,17H7V15H14M17,13H7V11H17M17,9H7V7H17M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z" /></svg> <?php echo __( 'Privacy Policy' );?></a></li>
					<li class=""><a href="<?php echo $site_url;?>/about" data-ajax="/about"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17.9,17.39C17.64,16.59 16.89,16 16,16H15V13A1,1 0 0,0 14,12H8V10H10A1,1 0 0,0 11,9V7H13A2,2 0 0,0 15,5V4.59C17.93,5.77 20,8.64 20,12C20,14.08 19.2,15.97 17.9,17.39M11,19.93C7.05,19.44 4,16.08 4,12C4,11.38 4.08,10.78 4.21,10.21L9,15V16A2,2 0 0,0 11,18M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg> <?php echo __( 'About us' );?></a></li>
					<li class=""><a href="<?php echo $site_url;?>/contact" data-ajax="/contact"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" /></svg> <?php echo __( 'Contact us' );?></a></li>
					<li class=""><a href="<?php echo $site_url;?>/faqs" data-ajax="/faqs"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,9V3.5L18.5,9M6,2C4.89,2 4,2.89 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2H6Z"></path></svg> <?php echo __( 'faqs' );?></a></li>
					<li class=""><a href="<?php echo $site_url;?>/refund" data-ajax="/refund"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 512 512" width="24" height="24"><path d="M464 16c-17.67 0-32 14.31-32 32v74.09C392.1 66.52 327.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.89 5.5 34.88-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c50.5 0 96.26 24.55 124.4 64H336c-17.67 0-32 14.31-32 32s14.33 32 32 32h128c17.67 0 32-14.31 32-32V48C496 30.31 481.7 16 464 16zM441.8 289.6c-16.92-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-50.5 0-96.25-24.55-124.4-64H176c17.67 0 32-14.31 32-32s-14.33-32-32-32h-128c-17.67 0-32 14.31-32 32v144c0 17.69 14.33 32 32 32s32-14.31 32-32v-74.09C119.9 445.5 184.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"/></svg> <?php echo __( 'refund' );?></a></li>
				</ul>
			</div>
			<h2 class="bold"><?php echo $data['name'];?></h2>
			<div class="dt_terms_content_body">
				<?php echo htmlspecialchars_decode($data['content']); ?>
			</div>
		</div>
	</div>
<?php } ?>