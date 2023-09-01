<?php
$phone = '';
$code = '';

if( route(2) !== '' ){
    $phone = strrev( base64_decode( route(2) ) );
}

if( route(3) !== '' ){
    $code = strrev( base64_decode( route(3) ) );
}else if( isset( $_COOKIE['phone_code']) ){
	$code = Secure($_COOKIE['phone_code']);
}

if( $phone == '' ){
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
							<form method="POST" action="Useractions/resetpassword" class="login">
								<input type="hidden" name="phone_number" value="<?php echo $phone;?>">
								<input type="hidden" name="phone_code" value="<?php echo $code;?>">
								<p><span class="bold"><?php echo __( 'Password change,' );?></span> <?php echo __( 'please enter your new password to proceed.' );?></p>
								<div class="alert alert-success" role="alert" style="display:none;"></div>
								<div class="alert alert-danger" role="alert" style="display:none;"></div>
								<div class="row">
									<div class="input-field">
										<input id="password" name="password" type="password" class="validate" autofocus>
										<label for="password"><?php echo __( 'Password' );?></label>
									</div>
								</div>
								<div class="row">
									<div class="input-field">
										<input id="c_password" name="c_password" type="password" class="validate">
										<label for="c_password"><?php echo __( 'Confirm Password' );?></label>
									</div>
								</div>
								<?php if ($config->recaptcha == 'on' && !empty($config->recaptcha_secret_key) && !empty($config->recaptcha_site_key)) { ?>
	                                <div class="form-group">
	                                    <div class="g-recaptcha" data-sitekey="<?php echo($config->recaptcha_site_key) ?>"></div>
	                                </div>
	                            <?php } ?>
								<div class="dt_login_footer valign-wrapper">
									<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="submit" name="action"><span><?php echo __( 'Reset' );?></span></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	<?php if ($config->recaptcha == 'on' && !empty($config->recaptcha_secret_key) && !empty($config->recaptcha_site_key)) { ?>
        $(document).ready(function(){
            setTimeout(() => {
                if ($('.g-recaptcha').html().length == 0) {
                    window.location.reload();
                }
            },300);
        });
    <?php } ?>
	var password = document.getElementById("password"), confirm_password = document.getElementById("c_password");

	function validatePassword(){
		if(password.value != confirm_password.value) {
			confirm_password.setCustomValidity("Passwords Don't Match");
		} else {
			confirm_password.setCustomValidity('');
		}
	}

	password.onchange = validatePassword;
	confirm_password.onkeyup = validatePassword;
</script>     