<style>
@media (max-width: 1024px){
.dt_slide_menu {
	display: none;
}
nav .header_user {
	display: block;
}
}
</style>
<div class="container dt_terms">
    <div class="row">
        <div class="col s12 m4 l3">
            <div class="dt_terms_sidebar">
                <ul>
                    <li class=""><a href="<?php echo $site_url;?>/terms" data-ajax="/terms"><?php echo __( 'Terms of use' );?></a></li>
                    <li class=""><a href="<?php echo $site_url;?>/privacy" data-ajax="/privacy"><?php echo __( 'Privacy Policy' );?></a></li>
                    <li class=""><a href="<?php echo $site_url;?>/about" data-ajax="/about"><?php echo __( 'About us' );?></a></li>
                    <?php if ($config->developers_page == '1') { ?>
					<li class=""><a href="<?php echo $site_url;?>/developers" data-ajax="/developers"><?php echo __( 'Developers' );?></a></li>
				<?php } ?>
                    <li class="active"><a href="<?php echo $site_url;?>/faqs" data-ajax="/faqs"><?php echo __( 'faqs' );?></a></li>
                    <li class=""><a href="<?php echo $site_url;?>/refund" data-ajax="/refund"><?php echo __( 'refund' );?></a></li>
                    <hr>
                    <li><a href="<?php echo $site_url;?>/contact" data-ajax="/contact"><?php echo __( 'Contact us' );?></a></li>
                </ul>
            </div>
        </div>
        <div class="col s12 m8 l9">
            <h2 class="bold"><?php echo __( 'faqs' );?></h2>
            <div class="dt_terms_content_body dt_faq">
				<?php
					foreach ($data['faqs'] as $faq) {
					$faq->time   = Time_Elapsed_String($faq->time);
				?>
					<div class="accordion">
						<div id="faqs-<?php echo $faq->id;?>" class="card accordion-item">
							<div class="card-body">
								<h4>
									<?php echo $faq->question;?>
								</h4>
								<div class="accordion-panel" id="faq-<?php echo $faq->id;?>">
									<p><?php echo $faq->answer;?></p>
								</div>
							</div>
							<div class="btn btn-default">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" /></svg>
							</div>
						</div>
					</div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
	$(".accordion-item").click(function() {
		// Toggle the item
		$(this).toggleClass("is-active").find(".accordion-panel").slideToggle("ease-out");
	});
});
</script>