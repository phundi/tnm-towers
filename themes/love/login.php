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
							<form method="POST" action="/Useractions/login" class="login">
								<p><span class="bold"><?php echo __( 'Welcome back,' );?></span> <?php echo __( 'please login to your account.' );?></p>
								<div class="alert alert-success" role="alert" style="display:none;"></div>
								<div class="alert alert-danger" role="alert" style="display:none;"></div>
								<div class="row">
									<div class="input-field">
										<input name="username" id="username" type="text" class="validate" required >
										<label for="username"><?php echo __( 'Username' );?></label>
									</div>
								</div>
								<div class="row">
									<div class="input-field">
										<input name="password" id="password" type="password" class="validate" required>
										<label for="password"><?php echo __( 'Password' );?></label>
									</div>
								</div>
								<?php if(!empty( $_GET['last_url'])){?>
									<div class="form-group"><input type="hidden" name="last_url" value="<?php echo urldecode(Secure($_GET['last_url']));?>"></div>
								<?php } ?>
								
								<div class="dt_login_footer">
									<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="submit" name="action"><span><?php echo __( 'Login' );?></span></button>
									<a href="<?php echo $site_url;?>/forgot" data-ajax="/forgot"><?php echo __( 'Forgot Password?' );?></a>
								</div>

								<div class="dt_social_login">
									<?php if($config->facebookLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=Facebook" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13.397,20.997v-8.196h2.765l0.411-3.209h-3.176V7.548c0-0.926,0.258-1.56,1.587-1.56h1.684V3.127	C15.849,3.039,15.025,2.997,14.201,3c-2.444,0-4.122,1.492-4.122,4.231v2.355H7.332v3.209h2.753v8.202H13.397z"/></svg>&nbsp;&nbsp;<?php echo __( 'Login with Facebook' );?></a><?php } ?>
									<?php if($config->twitterLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=Twitter" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19.633,7.997c0.013,0.175,0.013,0.349,0.013,0.523c0,5.325-4.053,11.461-11.46,11.461c-2.282,0-4.402-0.661-6.186-1.809	c0.324,0.037,0.636,0.05,0.973,0.05c1.883,0,3.616-0.636,5.001-1.721c-1.771-0.037-3.255-1.197-3.767-2.793	c0.249,0.037,0.499,0.062,0.761,0.062c0.361,0,0.724-0.05,1.061-0.137c-1.847-0.374-3.23-1.995-3.23-3.953v-0.05	c0.537,0.299,1.16,0.486,1.82,0.511C3.534,9.419,2.823,8.184,2.823,6.787c0-0.748,0.199-1.434,0.548-2.032	c1.983,2.443,4.964,4.04,8.306,4.215c-0.062-0.3-0.1-0.611-0.1-0.923c0-2.22,1.796-4.028,4.028-4.028	c1.16,0,2.207,0.486,2.943,1.272c0.91-0.175,1.782-0.512,2.556-0.973c-0.299,0.935-0.936,1.721-1.771,2.22	c0.811-0.088,1.597-0.312,2.319-0.624C21.104,6.712,20.419,7.423,19.633,7.997z"/></svg>&nbsp;&nbsp;<?php echo __( 'Login with Twitter' );?></a><?php } ?>
									<?php if($config->googleLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=Google" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20.283,10.356h-8.327v3.451h4.792c-0.446,2.193-2.313,3.453-4.792,3.453c-2.923,0-5.279-2.356-5.279-5.28	c0-2.923,2.356-5.279,5.279-5.279c1.259,0,2.397,0.447,3.29,1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233	c-4.954,0-8.934,3.979-8.934,8.934c0,4.955,3.979,8.934,8.934,8.934c4.467,0,8.529-3.249,8.529-8.934	C20.485,11.453,20.404,10.884,20.283,10.356z"/></svg>&nbsp;&nbsp;<?php echo __( 'Login with Google' );?></a><?php } ?>
									<?php if($config->VkontakteLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=Vkontakte" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20.8,7.74C20.93,7.32 20.8,7 20.18,7H18.16C17.64,7 17.41,7.27 17.28,7.57C17.28,7.57 16.25,10.08 14.79,11.72C14.31,12.19 14.1,12.34 13.84,12.34C13.71,12.34 13.5,12.19 13.5,11.76V7.74C13.5,7.23 13.38,7 12.95,7H9.76C9.44,7 9.25,7.24 9.25,7.47C9.25,7.95 10,8.07 10.05,9.44V12.42C10.05,13.08 9.93,13.2 9.68,13.2C9,13.2 7.32,10.67 6.33,7.79C6.13,7.23 5.94,7 5.42,7H3.39C2.82,7 2.7,7.27 2.7,7.57C2.7,8.11 3.39,10.77 5.9,14.29C7.57,16.7 9.93,18 12.08,18C13.37,18 13.53,17.71 13.53,17.21V15.39C13.53,14.82 13.65,14.7 14.06,14.7C14.36,14.7 14.87,14.85 16.07,16C17.45,17.38 17.67,18 18.45,18H20.47C21.05,18 21.34,17.71 21.18,17.14C21,16.57 20.34,15.74 19.47,14.76C19,14.21 18.29,13.61 18.07,13.3C17.77,12.92 17.86,12.75 18.07,12.4C18.07,12.4 20.54,8.93 20.8,7.74Z"/></svg>&nbsp;&nbsp;<?php echo __( 'Login with VK' );?></a><?php } ?>
									<?php if($config->qqLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=QQ" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 98.802 98.803" fill="currentColor"> <g> <g> <path d="M95.568,57.454c-1.74-4.367-3.976-8.49-6.733-12.316c-0.295-0.408-0.602-0.729-0.329-1.404 c1.326-3.281,0.896-6.463-0.798-9.515c-0.763-1.376-1.668-2.684-1.755-4.342c-0.127-2.393-0.734-4.692-1.356-6.994 c-2.17-8.031-6.494-14.449-13.937-18.479c-4.224-2.287-8.764-3.589-13.545-4.115C52.19-0.253,47.321-0.04,42.472,0.987 c-8.02,1.701-13.92,6.429-18.489,12.984c-3.001,4.308-5.137,8.993-5.776,14.3c-0.123,1.021,0.25,2.146-0.41,3.085 c-0.573,0.812-0.9,1.724-1.063,2.675c-0.245,1.425-0.573,2.778-1.304,4.073c-0.888,1.57-1.127,3.374-0.764,5.138 c0.157,0.758-0.005,1.153-0.531,1.548c-3.109,2.327-5.68,5.131-7.84,8.373c-3.077,4.616-4.894,9.619-5.189,15.16 c-0.119,2.225,0.15,4.398,0.933,6.505c0.379,1.02,0.88,1.498,2.084,1.148c1.013-0.293,1.878-0.748,2.645-1.423 c1.6-1.404,2.905-3.04,3.769-5.004c0.1-0.228,0.074-0.579,0.439-0.561c0.332,0.016,0.363,0.306,0.42,0.573 c0.518,2.398,1.633,4.556,2.829,6.659c1.276,2.247,3.105,4.056,5.017,5.75c0.667,0.592,1.614,0.868,1.987,1.871 c-1.38-0.002-2.656,0.194-3.863,0.609c-2.062,0.711-3.895,1.764-4.372,4.145c-0.456,2.275-0.613,4.522,1.467,6.206 c0.823,0.666,1.734,1.195,2.716,1.614c3.463,1.477,7.142,1.956,10.837,2.194c4.568,0.294,9.156,0.404,13.635-0.838 c2.596-0.722,4.999-1.891,7.251-3.366c0.213-0.14,0.354-0.46,0.658-0.372c1.79,0.518,3.677-0.02,5.49,0.687 c2.91,1.136,5.917,2.001,9.02,2.501c4.605,0.744,9.227,1.093,13.874,0.502c3.149-0.401,6.235-1.094,8.993-2.768 c3.546-2.153,3.654-5.891,0.326-8.31c-1.64-1.192-3.38-2.186-5.205-3.05c-0.472-0.223-0.991-0.376-1.364-0.893 c3.672-3.374,5.523-7.843,7.374-12.409c1.054,1.952,2.08,3.805,3.441,5.433c1.449,1.731,2.711,1.69,4.132-0.04 c0.566-0.69,0.981-1.451,1.239-2.315C98.51,67.896,97.619,62.604,95.568,57.454z M55.018,22.695 c-0.062-2.094,0.374-4.126,1.512-5.984c2.2-3.594,5.927-3.671,8.122-0.082c1.899,3.109,1.954,7.003,0.982,10.438 c-0.47,1.66-1.153,3.151-2.801,3.994c-2.239,1.145-4.307,0.692-5.812-1.331C55.482,27.662,54.927,25.299,55.018,22.695z M40.416,15.943c2.095-2.708,5.158-2.722,7.237-0.017c1.574,2.05,2.052,4.435,2.091,7.159c-0.076,2.407-0.588,4.892-2.398,6.899 c-2.086,2.315-4.877,2.194-6.817-0.231C37.729,26.254,37.674,19.486,40.416,15.943z M31.089,39.146 c3.005-2.065,6.387-3.264,9.902-4.027c7.729-1.682,15.478-1.892,23.2,0.086c3.134,0.803,6.169,1.89,8.897,3.668 c1.692,1.104,1.673,1.513-0.021,2.552c-1.81,1.109-3.694,2.027-6.063,2.02c0.854-0.947,1.935-1.479,2.597-2.923 c-11.517,7.921-22.792,8.559-34.122,0.353c0.501,0.808,1.002,1.614,1.618,2.606c-2.195-0.55-4.16-1.071-5.952-2.04 C29.729,40.672,29.748,40.068,31.089,39.146z M45.498,94.378c-1.388,1.356-3.231,1.805-4.997,2.193 c-6.68,1.475-13.408,1.794-20.09,0.042c-2.074-0.543-4.159-1.262-5.741-2.864c-1.172-1.185-1.151-2.205,0.02-3.421 c0.726-0.755,1.572-1.359,2.358-2.14c-0.603,0.107-1.211,0.196-1.808,0.337c-0.297,0.069-0.646,0.303-0.824-0.039 c-0.122-0.235-0.103-0.648,0.025-0.892c0.29-0.544,0.689-1.041,1.236-1.357c0.763-0.443,1.53-0.892,2.332-1.255 c1.908-0.865,3.584-0.936,5.472,0.514c3.637,2.791,7.861,4.532,12.245,5.885c3.109,0.96,6.28,1.586,9.487,2.072 c0.244,0.038,0.583-0.093,0.711,0.2C46.091,94.035,45.705,94.175,45.498,94.378z M81.455,84.153 c1.248,0.611,2.564,1.141,4.022,2.31c-1.181,0.092-2.198,0.127-3.067,0.681c-0.171,0.106-0.416,0.311-0.405,0.454 c0.028,0.373,0.373,0.263,0.621,0.262c1.151-0.001,2.304-0.059,3.452,0.001c2.125,0.109,3.197,1.731,2.403,3.692 c-1.039,2.568-3.396,3.5-5.763,4.248c-7.481,2.366-14.902,1.625-22.27-0.625c-0.812-0.249-1.776-0.215-2.169-1.324 c7.716-1.221,14.533-4.239,20.361-9.354C79.717,83.552,80.247,83.559,81.455,84.153z M84.223,68.128 c-0.26,4.43-1.97,8.329-4.652,11.788c-5.173,6.673-11.993,10.796-20.188,12.656c-3.104,0.706-6.256,0.349-9.376,0.045 c-4.791-0.465-9.515-1.327-13.972-3.219c-2.77-1.177-5.435-2.546-7.813-4.473c-4.629-3.753-8.246-8.165-9.446-14.146 c-1.086-5.412-0.645-10.715,1.674-15.791c0.164-0.358,0.373-0.696,0.543-1.052c0.414-0.856,0.823-1.223,1.793-0.484 c1.042,0.791,2.265,1.348,3.431,1.966c0.447,0.237,0.563,0.432,0.49,1.003c-0.504,4.039-0.938,8.08-0.483,12.171 c0.272,2.438,1.731,3.976,3.747,4.851c2.783,1.207,5.785,1.057,8.735,0.577c1.204-0.195,2.569-1.76,2.516-3.548l-0.192-8.102 l-0.069-1.684c3.209,0.899,6.507,1.185,9.782,1.263c7.792,0.186,15.094-1.702,22.083-5.021c2.072-0.983,4.073-2.088,5.977-3.359 c0.473-0.315,0.655-0.347,1.007,0.171C82.755,58.09,84.538,62.793,84.223,68.128z M36.888,64.798l-0.091-3.047 c0.059-0.565-0.266-1.596,0.643-1.748c1.124-0.188,2.169,0.613,2.277,1.747c0.269,2.827,0.451,5.684,0.349,8.552 c-0.049,1.381-0.726,2.211-2.281,2.291c-2.221,0.117-4.431,0.192-6.611-0.293c-3.059-0.683-4.14-2.181-4.231-5.647 c-0.087-3.265,0.691-6.405,1.279-9.576c0.094-0.508,0.288-0.49,0.706-0.312c1.94,0.832,3.841,1.771,5.895,2.308 c0.619,0.161,0.524,0.587,0.541,1.025c0.076,2.042,0.341,4.055,1.032,5.99c0.113,0.316,0.279,0.617,0.525,1.172L36.888,64.798z M87.863,41.959c-0.713,3.928-2.98,6.794-6.25,8.828c-6.996,4.354-14.417,7.735-22.591,9.235 c-4.74,0.869-9.478,0.834-14.262,0.222c-5.7-0.728-11.113-2.364-16.314-4.708c-4.34-1.956-8.464-4.3-11.461-8.165 c-2.191-2.824-2.488-5.776-0.475-8.403c0.613,3.759,2.714,6.468,5.648,8.647c-1.113-1.906-2.246-3.8-3.333-5.72 c-1.16-2.046-1.057-4.28-0.949-6.513c0.127-0.013,0.255-0.054,0.276-0.023c3.985,5.908,9.673,9.502,16.248,11.818 c8.313,2.933,16.929,3.846,25.633,2.862c8.854-1,16.799-4.403,23.481-10.46c0.426-0.385,0.882-0.734,1.218-1.014 c-1.527,6.333-6.051,10.371-11.515,13.634c7.514-2.716,11.403-8.663,14.022-15.749C88.027,37.638,88.234,39.91,87.863,41.959z"></path> <path d="M57.824,24.385c0.522,0.103,0.59-0.406,0.691-0.783c0.194-0.719,0.302-1.658,1.196-1.672 c0.82-0.011,0.854,0.921,0.957,1.529c0.082,0.484,0.37,0.993,0.901,0.919c0.674-0.094,0.597-3.508-1.134-4.097 c-1.595-0.601-3.162,0.939-3.122,3.106C57.321,23.776,57.325,24.288,57.824,24.385z"></path> <path d="M46.776,26.242c0.833,0.062,1.306-0.495,1.617-1.142c0.776-1.614,0.754-3.243-0.183-4.788 c-0.681-1.121-1.811-1.173-2.591-0.158c-0.619,0.805-0.779,1.753-0.757,2.742c0.015,0.705,0.073,1.401,0.379,2.056 C45.552,25.621,45.975,26.179,46.776,26.242z"></path> </g> </g></svg>&nbsp;&nbsp;<?php echo __( 'Login with QQ' );?></a><?php } ?>
									<?php if($config->WeChatLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=WeChat" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M9.5,4C5.36,4 2,6.69 2,10C2,11.89 3.08,13.56 4.78,14.66L4,17L6.5,15.5C7.39,15.81 8.37,16 9.41,16C9.15,15.37 9,14.7 9,14C9,10.69 12.13,8 16,8C16.19,8 16.38,8 16.56,8.03C15.54,5.69 12.78,4 9.5,4M6.5,6.5A1,1 0 0,1 7.5,7.5A1,1 0 0,1 6.5,8.5A1,1 0 0,1 5.5,7.5A1,1 0 0,1 6.5,6.5M11.5,6.5A1,1 0 0,1 12.5,7.5A1,1 0 0,1 11.5,8.5A1,1 0 0,1 10.5,7.5A1,1 0 0,1 11.5,6.5M16,9C12.69,9 10,11.24 10,14C10,16.76 12.69,19 16,19C16.67,19 17.31,18.92 17.91,18.75L20,20L19.38,18.13C20.95,17.22 22,15.71 22,14C22,11.24 19.31,9 16,9M14,11.5A1,1 0 0,1 15,12.5A1,1 0 0,1 14,13.5A1,1 0 0,1 13,12.5A1,1 0 0,1 14,11.5M18,11.5A1,1 0 0,1 19,12.5A1,1 0 0,1 18,13.5A1,1 0 0,1 17,12.5A1,1 0 0,1 18,11.5Z"></path></svg>&nbsp;&nbsp;<?php echo __( 'Login with WeChat' );?></a><?php } ?>
									<?php if($config->DiscordLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=Discord" class="btn_social" onclick="clickAndDisable(this);"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><g><path d="m484.629 225.058c-20.379-70.018-43.361-106.196-43.732-106.845-1.31-1.599-33.786-40.463-112.059-69.209l-10.343 28.16c37.237 13.676 63.655 30.36 78.184 41.14-43.376-12.953-94.977-20.886-140.679-20.886s-97.303 7.933-140.679 20.886c14.528-10.779 40.946-27.464 78.183-41.14l-10.343-28.16c-78.271 28.746-110.747 67.61-112.057 69.209-.371.649-23.354 36.827-43.732 106.845-19.64 67.476-27.101 162.665-27.372 166.482 1.75 2.736 42 71.456 151.693 71.456l27.804-40.262c-31.15-8.271-60.853-21.609-88.043-39.708l16.623-24.973c43.865 29.197 95.016 44.631 147.923 44.631s104.058-15.434 147.923-44.631l16.623 24.973c-27.189 18.099-56.893 31.437-88.043 39.708l27.804 40.262c109.693 0 149.943-68.72 151.693-71.456-.271-3.817-7.732-99.006-27.371-166.482zm-297.987 78.482h-30v-50h30zm168.716 0h-30v-50h30z"></path></g></svg>&nbsp;&nbsp;<?php echo __( 'Login with Discord' );?></a><?php } ?>
									<?php if($config->MailruLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=Mailru" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M12,15C12.81,15 13.5,14.7 14.11,14.11C14.7,13.5 15,12.81 15,12C15,11.19 14.7,10.5 14.11,9.89C13.5,9.3 12.81,9 12,9C11.19,9 10.5,9.3 9.89,9.89C9.3,10.5 9,11.19 9,12C9,12.81 9.3,13.5 9.89,14.11C10.5,14.7 11.19,15 12,15M12,2C14.75,2 17.1,3 19.05,4.95C21,6.9 22,9.25 22,12V13.45C22,14.45 21.65,15.3 21,16C20.3,16.67 19.5,17 18.5,17C17.3,17 16.31,16.5 15.56,15.5C14.56,16.5 13.38,17 12,17C10.63,17 9.45,16.5 8.46,15.54C7.5,14.55 7,13.38 7,12C7,10.63 7.5,9.45 8.46,8.46C9.45,7.5 10.63,7 12,7C13.38,7 14.55,7.5 15.54,8.46C16.5,9.45 17,10.63 17,12V13.45C17,13.86 17.16,14.22 17.46,14.53C17.76,14.84 18.11,15 18.5,15C18.92,15 19.27,14.84 19.57,14.53C19.87,14.22 20,13.86 20,13.45V12C20,9.81 19.23,7.93 17.65,6.35C16.07,4.77 14.19,4 12,4C9.81,4 7.93,4.77 6.35,6.35C4.77,7.93 4,9.81 4,12C4,14.19 4.77,16.07 6.35,17.65C7.93,19.23 9.81,20 12,20H17V22H12C9.25,22 6.9,21 4.95,19.05C3,17.1 2,14.75 2,12C2,9.25 3,6.9 4.95,4.95C6.9,3 9.25,2 12,2Z"></path></svg>&nbsp;&nbsp;<?php echo __( 'Login with Mailru' );?></a><?php } ?>
									<?php if($config->wowonder_login == '1' ){ ?><a href="<?php echo $config->wowonder_domain_uri.'/oauth?app_id='.$config->wowonder_app_ID;?>" class="btn_social" onclick="clickAndDisable(this);"><img src="<?php echo $config->wowonder_domain_icon;?>" style="width: 20px;">&nbsp;&nbsp;<?php echo __( 'Login with Wowonder' );?></a><?php } ?>
									<?php if($config->linkedinLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=LinkedIn" class="btn_social" onclick="clickAndDisable(this);"><svg height="512" viewBox="0 0 152 152" width="512" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2"><g id="_10.linkedin" data-name="10.linkedin"><circle id="background" cx="76" cy="76" fill="#0b69c7" r="76"/><g id="icon" fill="#fff"><path d="m59 48.37a10.38 10.38 0 1 1 -10.37-10.37 10.38 10.38 0 0 1 10.37 10.37z"/><rect height="50.93" rx="2.57" width="16.06" x="40.6" y="63.07"/><path d="m113.75 89.47v22.17a2.36 2.36 0 0 1 -2.36 2.36h-11.72a2.36 2.36 0 0 1 -2.36-2.36v-21.48c0-3.21.93-14-8.38-14-7.22 0-8.69 7.42-9 10.75v24.78a2.36 2.36 0 0 1 -2.34 2.31h-11.34a2.35 2.35 0 0 1 -2.36-2.36v-46.2a2.36 2.36 0 0 1 2.36-2.37h11.34a2.37 2.37 0 0 1 2.41 2.37v4c2.68-4 6.66-7.12 15.13-7.12 18.73-.01 18.62 17.52 18.62 27.15z"/></g></g></g></svg>&nbsp;&nbsp;<?php echo __( 'login_with_linkedin' );?></a><?php } ?>
									<?php if($config->OkLogin == '1' ){ ?><a href="<?php echo $site_url;?>/social-login.php?provider=OkRu" class="btn_social" onclick="clickAndDisable(this);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g id="surface1"><path style=" stroke:none;fill-rule:nonzero;fill:rgb(88.627451%,49.411765%,20.784314%);fill-opacity:1;" d="M 24 12 C 24 24 24 24 12 24 C 0 24 0 24 0 12 C 0 0 0 0 12 0 C 24 0 24 0 24 12 Z M 24 12 "/><path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,100%,100%);fill-opacity:1;" d="M 13.878906 16.082031 C 14.828125 15.863281 15.738281 15.488281 16.578125 14.960938 C 17.210938 14.5625 17.40625 13.722656 17.003906 13.085938 C 16.601562 12.449219 15.765625 12.253906 15.125 12.65625 C 13.222656 13.855469 10.773438 13.851562 8.871094 12.65625 C 8.234375 12.253906 7.394531 12.449219 6.992188 13.085938 C 6.59375 13.71875 6.785156 14.5625 7.421875 14.960938 C 8.257812 15.488281 9.171875 15.863281 10.117188 16.082031 L 7.523438 18.675781 C 6.992188 19.210938 6.992188 20.070312 7.523438 20.601562 C 7.789062 20.867188 8.136719 21 8.484375 21 C 8.832031 21 9.179688 20.867188 9.449219 20.601562 L 12 18.050781 L 14.550781 20.601562 C 15.085938 21.132812 15.945312 21.132812 16.476562 20.601562 C 17.007812 20.070312 17.007812 19.207031 16.476562 18.675781 L 13.878906 16.082031 M 12 5.722656 C 13.0625 5.722656 13.925781 6.585938 13.925781 7.648438 C 13.925781 8.707031 13.0625 9.570312 12 9.570312 C 10.941406 9.570312 10.074219 8.707031 10.074219 7.648438 C 10.074219 6.585938 10.941406 5.722656 12 5.722656 Z M 12 12.289062 C 14.5625 12.289062 16.644531 10.207031 16.644531 7.648438 C 16.644531 5.082031 14.5625 3 12 3 C 9.4375 3 7.355469 5.082031 7.355469 7.644531 C 7.355469 10.207031 9.4375 12.289062 12 12.289062 Z M 12 12.289062 "/></g></svg>&nbsp;&nbsp;<?php echo __( 'login_with_okru' );?></a><?php } ?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>