<!DOCTYPE html>
<html>
<head>
    <title><?php echo $data['title'];?></title>
    <?php require( $theme_path . 'main' . $_DS . 'meta.php' );?>
    <?php require( $theme_path . 'main' . $_DS . 'style.php' );?>
    <?php require( $theme_path . 'main' . $_DS . 'custom-header-js.php' );?>
    <?php
    if($config->push == 1) {
        require($theme_path . 'main' . $_DS . 'onesignal.php');
    }
    ?>
    <?php require( $theme_path . 'main' . $_DS . 'ajax.php' );?>
    <script src="https://cdn.jsdelivr.net/gh/tigrr/circle-progress@v0.2.4/dist/circle-progress.min.js"></script>
    <?php if ($config->recaptcha == 'on' && !empty($config->recaptcha_secret_key) && !empty($config->recaptcha_site_key)) { ?>
    <script type="text/javascript" src='https://www.google.com/recaptcha/api.js'></script>
    <?php } ?>
</head>
<body class="<?php echo $data['name'];?>-page <?php echo(!empty($_COOKIE['open_slide']) && $_COOKIE['open_slide'] == 'yes') ? 'side_open' : '' ?> <?php if( isset( $_SESSION['JWT'] ) ){ ?><?php } else { ?>no-padd<?php } ?>">
	<img src="<?php echo $theme_url;?>assets/img/login-banner-mask.svg" class="body_banner_mask">
    <div id="pop_up_18" class="modal matdialog et_plus">
		<div class="modal-dialog">
			<div class="modal-content">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="120" height="120" x="0" y="0" viewBox="0 0 479.635 479.635" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g><path d="m50.516 54.186c-58.063 46.478-67.455 131.225-20.977 189.288 3.081 3.849 6.371 7.526 9.855 11.014l200.41 200.5 37.014-220.34-37.014-159.485c-46.478-58.063-131.225-67.455-189.288-20.977z" fill="#ff6378" data-original="#ff6378"></path><path d="m440.152 64.089c-55.958-56.654-150.957-51.3-200.349 11.074v379.825l200.41-200.41c52.586-52.619 52.558-137.904-.061-190.489z" fill="#fd5151" data-original="#c30047" class=""></path><path d="m174.616 169.647h19.827v134h30v-164h-49.827z" fill="#ffffff" data-original="#fbf4f4" class=""></path><path d="m337.227 213.936c6.42-7.78 10.281-17.747 10.281-28.598 0-24.813-20.187-45-45-45s-45 20.187-45 45c0 10.852 3.861 20.818 10.281 28.598-11.369 9.784-18.59 24.261-18.59 40.402 0 29.395 23.915 53.31 53.309 53.31s53.309-23.915 53.309-53.31c0-16.141-7.22-30.619-18.59-40.402zm-34.719-43.598c8.271 0 15 6.729 15 15s-6.729 15-15 15-15-6.729-15-15 6.729-15 15-15zm0 107.309c-12.853 0-23.309-10.457-23.309-23.31s10.457-23.309 23.309-23.309 23.309 10.456 23.309 23.309-10.456 23.31-23.309 23.31z" fill="#ffffff" data-original="#e9e9ee" class=""></path><path d="m140.347 180.98h-30v21.952h-21.952v30h21.952v21.951h30v-21.951h21.951v-30h-21.951z" fill="#ffffff" data-original="#fbf4f4" class=""></path></g></g></svg>
				<h4><?php echo(__('age_block_modal')) ?></h4>
				<p><?php echo(__('age_block_extra')) ?></p>

				<div class="modal-footer center">
					<button class="btn-flat waves-effect waves-light btn_primary white-text" id="pop_up_18_yes"><?php echo(__('yes')) ?></button>
					<button class="btn-flat waves-effect" id="pop_up_18_no"><?php echo(__('nopop')) ?></button>
				</div>
            </div>
		</div>
    </div>
	
    <?php 
        if (!empty($config->google_tag_code)) {
            echo openssl_decrypt($config->google_tag_code, "AES-128-ECB", 'mysecretkey1234');
        }
    ?>
    <?php if (file_exists($theme_path . 'third-party-theme.php')) { ?>
        <?php require( $theme_path . 'third-party-theme.php' );?>
    <?php } ?>
    <div id="loader" class="dt_ajax_progress"></div>
    <div class="modal modal-sm" id="authorize_modal" role="dialog" data-keyboard="false" style="overflow-y: auto;">
        <div class="modal-dialog">
            <div class="modal-content">
                <h4 class="modal-title"><?php echo __('Check out');?></h4>
                <form class="form form-horizontal" method="post" id="authorize_form" action="#">
                    <div class="modal-body authorize_modal">
                        <div id="authorize_alert"></div>
                        <div class="clear"></div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="authorize_number" type="text" class="form-control input-md" autocomplete="off" placeholder="<?php echo __('card number');?>">
                            </div>
                            <div class="input-field col s4">
                                <select id="authorize_month" name="card_month" type="text" class="browser-default" autocomplete="off" placeholder="<?php echo __('month');?> (01)">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="input-field col s4 no-padding-both">
                                <select id="authorize_year" name="card_year" type="text" class="browser-default" autocomplete="off" placeholder="<?php echo __('year');?> (2021)">
                                    <?php for ($i=date('Y'); $i <= date('Y')+15; $i++) {  ?>
                                        <option value="<?php echo($i) ?>"><?php echo($i) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="input-field col s4">
                                <input id="authorize_cvc" name="card_cvc" type="text" class="form-control input-md" autocomplete="off" placeholder="CVC" maxlength="3" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="modal-footer">
                        <div class="ball-pulse"><div></div><div></div><div></div></div>
                        <button type="button" class="waves-effect waves-light btn-flat btn_primary white-text" onclick="AuthorizeRequest()" id="authorize_btn"><?php echo __('pay');?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-sm" id="paystack_wallet_modal" role="dialog" data-keyboard="false" style="overflow-y: auto;">
        <div class="modal-dialog">
            <div class="modal-content">
                <h4 class="modal-title"><?php echo __( 'PayStack');?></h4>
                <form class="form form-horizontal" method="post" id="paystack_wallet_form" action="#">
                    <div class="modal-body twocheckout_modal">
                        <div id="paystack_wallet_alert"></div>
                        <div class="clear"></div>
                        <div class="input-field col-md-12">
                            <label class="plr15" for="paystack_wallet_email"><?php echo __( 'Email');?></label>  
                            <input id="paystack_wallet_email" type="text" class="form-control input-md" autocomplete="off" placeholder="<?php echo __( 'Email');?>">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <div class="modal-footer">
                        <div class="ball-pulse"><div></div><div></div><div></div></div>
                        <button type="button" class="waves-effect waves-light btn-flat btn_primary white-text" id="paystack_btn" onclick="InitializeWalletPaystack()"><?php echo __( 'Confirm' );?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
