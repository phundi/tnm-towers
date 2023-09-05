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
							<form method="POST" action="/Useractions/register" class="register">
								<p><span class="bold"><?php echo __( 'Get started,' );?></span> <?php echo __( 'please signup to continue your account.' );?></p>
								<div class="alert alert-success" role="alert" style="display:none;"></div>
								<div class="alert alert-danger" role="alert" style="display:none;"></div>
							
								<div class="row">
									<div class="input-field col m6 s12">
										<input name="username" id="username" type="text" class="validate" value="" required>
										<label for="username"><?php echo __( 'Display Name' );?></label>
									</div>
										
								</div>

								<div class="row">
									<div class="input-field col m6 s12">
										<input name="first_name" id="first_name" type="text" class="validate" value="" autofocus>
										<label for="first_name"><?php echo __( 'First Name' );?></label>
									</div>
									<div class="input-field col m6 s12">
										<input name="last_name" id="last_name" type="text" class="validate" value="">
										<label for="last_name"><?php echo __( 'Last Name' );?></label>
									</div>
								</div>
							
								<div class="row">
									<div class="input-field col m6 s12">
										<select id="gender" name="gender" data-errmsg="<?php echo __( 'Choose your Gender');?>" required>
										<?php echo DatasetGetSelect( null, "gender", __("Choose your Gender") );?>
										</select>
									</div>

									<div class="input-field col m6 s12">
										<input name="birthday" id="birthday" data-errmsg="<?php echo __( 'Select your Birth date.');?>" type="text" class="datepicker user_bday" required>
										<label for="birthday"><?php echo __( 'Birthdate' );?></label>
									</div>
								</div>

								<div class="row">
									<div class="input-field col m6 s12">
										<input name = "phone_number" id="mobile" type="text" data-errmsg="<?php echo __( 'Your phone number is required.');?>" class="validate" required title="Field must be a number." placeholder="" <?php if($config->sms_or_email == 'sms'){?> data-validation-type="sms" required<?php }else{?> data-validation-type="mail" <?php } ?> data-p-verified="yes">
										<label for="mobile"><?php echo __( 'Mobile Number' );?></label>
									</div>

									<div class="input-field col m6 s12">
										<select name = "district" id="district" required>
											<option value="" selected>Select District (Optional)</option>
											<?php
												foreach( ["Balaka", "Blantyre", "Chikwawa", "Chiradzulo", "Chitipa", "Dedza", "Dowa", "Karonga", "Kasungu", "Likoma", "Lilongwe", "Machinga", "Mangochi", "Mchinji", "Mulanje", "Mwanza", "Mzimba", "Neno", "Nkhatabay", "Nkhotakota", "Nsanje", "Ntcheu", "Ntchisi", "Phalombe", "Rumphi", "Salima", "Thyolo", "Zomba"] as $key ){
													echo '<option value="'. $key .'" data-code="'. $key .'">'. $key .'</option>';
												}
												?>
										</select>
                       		 		</div>
								</div>

							
								
								<div class="row">
									<div class="input-field col m6 s12">
										<input name="password" id="password" type="password" class="validate" value="" required>
										<label for="password"><?php echo __( 'Password' );?></label>
									</div>
									<div class="input-field col m6 s12">
										<input name="c_password" id="c_password" type="password" class="validate" value="" required>
										<label for="c_password"><?php echo __( 'Confirm Password' );?></label>
									</div>
								</div>
								<?php if(!empty($_GET['invite'])){?>
									<div class="form-group"><input type="hidden" name="invite" value="<?php echo Secure($_GET['invite']);?>"></div>
								<?php } ?>
								<?php if ($config->recaptcha == 'on' && !empty($config->recaptcha_secret_key) && !empty($config->recaptcha_site_key)) { ?>
									<div class="form-group">
										<div class="g-recaptcha" data-sitekey="<?php echo($config->recaptcha_site_key) ?>"></div>
									</div>
								<?php } ?>
								<label class="terms_check">
									<input class="filled-in" type="checkbox" onchange="activateButton(this)" />
									<span><?php echo str_replace(array('{terms}','{privacy}'),array('<a href="<?php echo $site_url;?>/terms" data-ajax="/terms">'.__('terms_of_use').'</a>','<a href="<?php echo $site_url;?>/privacy" data-ajax="/privacy">'.__('privacy_policy').'</a>'),__( 'terms_register_text' )) ;?></span>
								</label>
								<div class="dt_login_footer valign-wrapper">
									<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" id="sign_submit" type="submit" disabled><span><?php echo __( 'Register' );?></span> </button>
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

function activateButton(element) {
	if(element.checked) {
		document.getElementById("sign_submit").disabled = false;
	}
	else  {
		document.getElementById("sign_submit").disabled = true;
	}
};
</script>