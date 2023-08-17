<!-- Footer  -->
    <?php if ($data['name'] !== 'login' && $data['name'] !== 'contact' && $data['name'] !== 'register' && $data['name'] !== 'forgot' && $data['name'] !== 'reset' && $data['name'] !== 'verifymail' && IS_LOGGED) { ?>
    <div class="container " style="transform: none;"><?php echo GetAd('footer');?></div>
<?php } ?>
    <footer id="footer" class="page_footer">
		<div class="container-fluid container_new">
			<div class="footer-copyright">
				<div class="valign-wrapper">
					<div>
						
						<?php if($config->social_media_links == 'on'){ ?>
						&nbsp;&nbsp;<span class="docial">
						<?php if(!empty($config->facebook_url)){ ?>
							&nbsp;&nbsp;<a href="<?php echo $config->facebook_url;?>" target="_blank">
								<svg height="512" viewBox="0 0 152 152" width="512" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><g><g><path d="m76 0a76 76 0 1 0 76 76 76 76 0 0 0 -76-76zm19.26 68.8-1.26 10.59a2 2 0 0 1 -2 1.78h-11v31.4a1.42 1.42 0 0 1 -1.4 1.43h-11.2a1.42 1.42 0 0 1 -1.4-1.44l.06-31.39h-8.33a2 2 0 0 1 -2-2v-10.58a2 2 0 0 1 2-2h8.27v-10.26c0-11.87 7.07-18.33 17.4-18.33h8.47a2 2 0 0 1 2 2v8.91a2 2 0 0 1 -2 2h-5.19c-5.62.09-6.68 2.78-6.68 6.8v8.85h12.32a2 2 0 0 1 1.94 2.24z"></path></g></g></svg>
							</a>
						<?php }?>
						<?php if(!empty($config->twitter_url)){ ?>
							&nbsp;&nbsp;<a href="<?php echo $config->twitter_url;?>" target="_blank">
								<svg height="512" viewBox="0 0 152 152" width="512" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><g><g><path d="m76 0a76 76 0 1 0 76 76 76 76 0 0 0 -76-76zm37.85 53a32.09 32.09 0 0 1 -6.51 7.15 2.78 2.78 0 0 0 -1 2.17v.25a45.58 45.58 0 0 1 -2.94 15.86 46.45 46.45 0 0 1 -8.65 14.5 42.73 42.73 0 0 1 -18.75 12.39 46.9 46.9 0 0 1 -14.74 2.29 45 45 0 0 1 -22.6-6.09 1.3 1.3 0 0 1 -.62-1.44 1.25 1.25 0 0 1 1.22-.94h1.9a30.31 30.31 0 0 0 16.94-5.14 16.45 16.45 0 0 1 -13-11.17.86.86 0 0 1 1-1.11 15.08 15.08 0 0 0 2.76.26h.35a16.42 16.42 0 0 1 -9.57-15.11.86.86 0 0 1 1.27-.75 14.44 14.44 0 0 0 3.74 1.45 16.42 16.42 0 0 1 -2.65-19.91.86.86 0 0 1 1.41-.11 43 43 0 0 0 29.51 15.77h.08a.62.62 0 0 0 .6-.67 17.39 17.39 0 0 1 .38-6 15.91 15.91 0 0 1 10.7-11.44 17.59 17.59 0 0 1 5.19-.8 16.36 16.36 0 0 1 10.84 4.09 2.12 2.12 0 0 0 1.41.54 2.15 2.15 0 0 0 .5-.07 30.3 30.3 0 0 0 8-3.3.85.85 0 0 1 1.25 1 16.23 16.23 0 0 1 -4.31 6.87 29.38 29.38 0 0 0 5.24-1.77.86.86 0 0 1 1.05 1.23z"></path></g></g></svg>
							</a>
						<?php }?>
					
						</span>
						<?php } ?>
					</div>
					
					
					<div class="dt_fotr_spn">
					<ul class="dt_footer_links">
						<li><a href="<?php echo $site_url;?>/about" data-ajax="/about"><?php echo __( 'About Us' );?></a></li>
						&nbsp;-&nbsp;<li><a href="<?php echo $site_url;?>/terms" data-ajax="/terms"><?php echo __( 'Terms' );?></a></li>
						&nbsp;-&nbsp;<li><a href="<?php echo $site_url;?>/privacy" data-ajax="/privacy"><?php echo __( 'Privacy Policy' );?></a></li>
						&nbsp;-&nbsp;<li><a href="<?php echo $site_url;?>/contact" data-ajax="/contact"><?php echo __( 'Contact' );?></a></li>
					
					</ul>
					<?php require( $theme_path . 'main' . $_DS . 'custom-page.php' );?>
					
					</div>
					
					<div><?php echo __( 'Copyright' );?> © <?php echo date( "Y" ) . " " . ucfirst( $config->site_name );?>. <?php echo __( 'All rights reserved' );?>.</div>
				</div>
			</div>
		</div>
    </footer>
<!-- End Footer  -->