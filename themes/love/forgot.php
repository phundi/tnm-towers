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
							<form id="passwordForm" method="POST" action="/Useractions/forget_password" class="login">
								<p><span class="bold"><?php echo __( 'Password recovery,' );?></span> 
									<?php echo __( 'Please Enter Registered Phone Number' );?></p>
								<div class="alert alert-success" role="alert" style="display:none;"></div>
								<div class="alert alert-danger" role="alert" style="display:none;"></div>
								<div class="row">
									<div class="input-field">
										<input id="phone_number" name="phone_number" type="text" class="validate" required autofocus>
										<label for="phone_number"><?php echo __( 'Phone' );?></label>
									</div>
								</div>

								<div class="enter_otp">
									<p style="color: white;"><?php echo __( 'Please enter the verification code sent to your Phone' );?></p>
									<div id="otp_outer">
									<div id="otp_inner">
										<input id="otp_check_phone" type="text" maxlength="4" value="" pattern="\d*" title="Field must be a number." 
											onkeyup="if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'') } if($(this).val().length == 4){verify_sms_code(this);}" 
											 />
									</div>
									</div>
								</div>

								
								<div class="dt_login_footer valign-wrapper">
									<button onclick="submitForm()" class="btn btn-large waves-effect waves-light bold btn_primary btn_round" 
										type="submits" name="action"><span><?php echo __( 'Proceed' );?></span></button>
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

function submitForm(){

	$('#passwordForm').submit(function() {
        $.ajax({
            type: 'POST',
            url: '/Useractions/forget_password',
            data: { phone_numer: $("#phone_number").val() },
			success: function(data, status){
				$(".enter_otp").show();
				$('#otp_check_phone').focus();
			}		
        });
        return false;
    }); 
}

function verify_sms_code( thisx ){
    var vl = $(thisx);
	var url = window.ajax + 'useractions/get_fp_sms_verification_code'

	jQuery.ajax( {
		url: url , 
		type: "GET",
		data: {
			phone_number: jQuery("#phone_number").val()
		},
		success: function(data, status){
			
			setTimeout(() => {
				$('#otp_check_phone').removeAttr('disabled');
			},1000);

			console.log(data);
			if( data != undefined && data != null && data.status == 200 ){
				if( vl.val() == data.code ){
					
					window.location = data.reset_link;
				}else{
					console.log("1")
					jQuery(".alert-danger").html("SMS code does not match");
					jQuery(".alert-danger").show();
				}
			}else{
				console.log("2")
				jQuery(".alert-danger").html("SMS verification failed");
				jQuery(".alert-danger").show();
			}
    	}
	}
	);
}

$( document ).on( 'input', '#otp_check_phone', function(e){
	if($(this).val().length == 4 && !$('#otp_check_phone').prop('disabled')) {
		$('#otp_check_phone').attr('disabled',true);
		verify_sms_code(this);
	} else {}
});

$( document ).on( 'paste', '#otp_check_phone', function(e){
	var pastedData = e.originalEvent.clipboardData.getData('text');
	if(pastedData.length === 4) {
		var vl = $(this);
		vl.val(pastedData);
		
		var url = window.ajax + 'useractions/get_fp_sms_verification_code';
		jQuery.ajax( {
			url: url , 
			type: "GET",
			data: {
				phone_number: jQuery("#phone_number").val()
			},
			success: function(data, status){
				
				setTimeout(() => {
					$('#otp_check_phone').removeAttr('disabled');
				},1000);

				if( data.status == 200 ){
					if( vl.val() == data.code ){
						
						window.location = data.reset_link;
					}else{
						jQuery(".alert-danger").html("SMS code does not match");
						jQuery(".alert-danger").show();
					}
				}else{
					jQuery("alert-danger").html("SMS verification failed");
					jQuery("alert-danger").show();
				}
			}
		}
		);
	} else {}
	e.preventDefault();
});

</script>