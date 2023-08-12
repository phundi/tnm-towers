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
					<li class="active"><a href="<?php echo $site_url;?>/developers" data-ajax="/developers"><?php echo __( 'Developers' );?></a></li>
					<li class=""><a href="<?php echo $site_url;?>/faqs" data-ajax="/faqs"><?php echo __( 'faqs' );?></a></li>
					<li class=""><a href="<?php echo $site_url;?>/refund" data-ajax="/refund"><?php echo __( 'refund' );?></a></li>
                    <hr>
                    <li><a href="<?php echo $site_url;?>/contact" data-ajax="/contact"><?php echo __( 'Contact us' );?></a></li>
                </ul>
            </div>
        </div>
        <div class="col s12 m8 l9">
            <h2 class="bold"><?php echo __( 'Developers' );?> <a class="btn btn_primary waves-effect waves-light btn-flat btn-small white-text" data-ajax="/create-app" href="<?php echo $site_url;?>/create-app"><?php echo __( 'Create App' ) ?></a> <a class="btn btn_primary waves-effect waves-light btn-flat btn-small white-text" data-ajax="/apps" href="<?php echo $site_url;?>/apps"><?php echo __( 'My Apps' ) ?></a></h2>
            <div class="dt_terms_content_body">
                <div class="dt_usr_pmnt_cont">
					<p class="no_margin_top">Our API allows you to retrieve informations from our website via GET request and supports the following query parameters: </p>
					<table class="responsive-table highlight">
						<thead>
							<tr>
								<th>Name</th>
								<th>Meaning</th>
								<th>Values</th>
								<th>Description</th>
								<th>Required</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><b>type</b></td>
								<td>Query type.</td>
								<td>get_user_data, posts_data</td>
								<td>This parameter specify the type of the query.</td>
								<td><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" /></svg></td>
							</tr>
							<tr>
								<td><b>limit</b></td>
								<td>Limit of items.</td>
								<td>LIMIT</td>
								<td>This parameter specify the limit of items. Max:100 | Default:20</td>
								<td><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg></td>
							</tr>
						</tbody>
					</table>
					<br>
					<h5>How to start?</h5>
					<hr class="border_hr">
					<ol>
						<li>Create a <a href="<?php echo $site_url;?>/create-app" class="main">development application</a>.<br><br></li>
						<li>Once you have created the app, you'll get APP_ID, and APP_SECRET. <br>Example: <br><br><img src="<?php echo $theme_url;?>assets/img/developers.png" alt=""><br><br></li>
						<li>To start the Oauth process, use the link <?php echo $site_url; ?>/oauth?app_id={YOUR_APP_ID}<br><br></li>
						<li>Once the end user clicks this link, he/she will be redirected to the authorization page.<br><br></li>
						<li>Once the end user authorization the app, he/she will be redirected to your domain name with a GET parameter "code", example: http://yourdomain/?code=XXX<br><br></li>
						<li>In your code, to retrieve the authorized user info, you need to generate an access code, please use the code below:<br><br>
                            PHP:
                            <code>
<?php 
$code = '<?php
	$app_id = \'YOUR_APP_ID\'; // your application app id
	$app_secret = \'YOUR_APP_SECRET\'; your application app secret
	$code = $_GET[\'code\']; // the GET parameter you got in the callback: http://yourdomain/?code=XXX

	$get = file_get_contents("' . $site_url . '/authorize?app_id={$app_id}&app_secret={$app_secret}&code={$code}");


	Respond:
	{
		"id": "1",
		"verified_final": true,
		"fullname": "admin",
		"country_txt": "Algeria",
		"age": 0,
		"profile_completion": 57,
		"profile_completion_missing": [
			"first_name",
			"last_name",
			"facebook",
			"google",
			"twitter",
			"linkedin",
			"instagram",
			"phone_number",
			"interest",
			"pets",
			"live_with",
			"car",
			"religion",
			"smoke",
			"drink",
			"travel"
		]
	}
?>';
echo '
<pre>' . htmlspecialchars($code) . '</pre>
';
?>
                            </code>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Terms 