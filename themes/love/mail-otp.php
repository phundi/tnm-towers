<?php
    $email = '';
    if( route(2) !== '' ){
        $email = strrev( base64_decode( route(2) ) );
    }
    if( $email == '' ){
        echo "<script>window.location = window.site_url + '/forgot';</script>";
    }
?>
<style>body > #container {padding: 0 !important;}#nav-not-logged-in,.page_footer:not(.dt_auth_footr_page){display: none !important;visibility: hidden !important;}</style>

<div class="dt_login_body">
	<div class="dt_login_body_inner">
		<img src="<?php echo $theme_url;?>assets/img/login-banner.png" class="login_baner">
		<img src="<?php echo $theme_url;?>assets/img/login-banner-mask.svg" class="login_banner_mask">
		<img src="<?php echo $theme_url;?>assets/img/login-banner-lines.svg" class="login_banner_lines">
		
		<nav role="navigation">
			<div class="nav-wrapper">
				<div class="left header_logo">
					<a id="logo-container" href="<?php echo $site_url;?>/" class="brand-logo"><img src="<?php echo $theme_url;?>assets/img/logo.png" /></a>
				</div>
				<ul class="right not_usr_nav">
					<li><a href="<?php echo $site_url;?>/login" data-ajax="/login" class="btn btn-flat"><?php echo __( 'Login' );?></a></li>
					<?php if ($config->user_registration == 1) { ?>
						<li><a href="<?php echo $site_url;?>/register" data-ajax="/register" class="btn-flat btn white waves-effect"><?php echo __( 'Register' );?></a></li>
					<?php } ?>
				</ul>
			</div>
		</nav>
		
		<div class="container-fluid auth_bg_img">
			<div class="container dt_login_bg">
				<div class="">
					<div class="dt_login_con">
						<div class="row dt_login login">
							<form method="POST" action="/Useractions/mailotp" class="register">
								<p><span class="bold"><?php echo __( 'Password recovery,' );?></span> <?php echo __( 'Please enter the verification code sent to your Email' );?></p>
								<div class="alert alert-success" role="alert" style="display:none;"></div>
								<div class="alert alert-danger" role="alert" style="display:none;"></div>

								<div class="center enter_otp_email" style="display: block;border: 0px;margin: -20px auto 30px;">
									<div id="otp_outer" style="margin: 0px;">
										<div id="otp_inner">
											<input id="otp_check_forget_email" name="email_code" type="text" maxlength="4" value="" pattern="\d*" title="Field must be a number." onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required  /><br><br>
											<input type="hidden" name="email" value="<?php echo $email;?>">
											<a href="<?php echo $site_url;?>/forgot" data-ajax="/forgot"><?php echo __( 'Resend' );?></a>
										</div>
									</div>
								</div>

								<div class="dt_login_footer valign-wrapper">
									<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="submit" name="action"><span><?php echo __( 'Login' );?></span></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>