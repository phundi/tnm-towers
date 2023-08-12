<?php
$admin_mode = false;
if( $profile->admin == '1' || CheckPermission($profile->permission, "manage-users")){
    $target_user = route(2);
    $_user = LoadEndPointResource('users');
    if( $_user ){
        if( $target_user !== '' ){
            $profile = $_user->get_user_profile(Secure($target_user));
            if( !$profile ){
                echo '<script>window.location = window.site_url;</script>';
                exit();
            }else{
                $user = $profile;
                if( $profile->admin == '1' ){
                    $admin_mode = true;
                }
            }
        }
    }
}else{
    $user = auth();
}
?>
<?php //$user = auth();?>

<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<!-- Settings  -->
<div class="container">
    <div class="dt_settings">
		<div class="row">
			<div class="col s12">
				<div class="dt_settings_bg_wrap dt_sett_top_menu">
					<ul class="dt_settings_side_links">
						<li>
							<a href="<?php echo $site_url;?>/settings/<?php echo $profile->username;?>" data-ajax="/settings/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M6.75 2.5A4.25 4.25 0 0 1 11 6.75V11H6.75a4.25 4.25 0 1 1 0-8.5zM9 9V6.75A2.25 2.25 0 1 0 6.75 9H9zm-2.25 4H11v4.25A4.25 4.25 0 1 1 6.75 13zm0 2A2.25 2.25 0 1 0 9 17.25V15H6.75zm10.5-12.5a4.25 4.25 0 1 1 0 8.5H13V6.75a4.25 4.25 0 0 1 4.25-4.25zm0 6.5A2.25 2.25 0 1 0 15 6.75V9h2.25zM13 13h4.25A4.25 4.25 0 1 1 13 17.25V13zm2 2v2.25A2.25 2.25 0 1 0 17.25 15H15z"/></svg> <span><?php echo __( 'General' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/settings-profile/<?php echo $profile->username;?>" data-ajax="/settings-profile/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M3 4.995C3 3.893 3.893 3 4.995 3h14.01C20.107 3 21 3.893 21 4.995v14.01A1.995 1.995 0 0 1 19.005 21H4.995A1.995 1.995 0 0 1 3 19.005V4.995zM5 5v14h14V5H5zm2.972 13.18a9.983 9.983 0 0 1-1.751-.978A6.994 6.994 0 0 1 12.102 14c2.4 0 4.517 1.207 5.778 3.047a9.995 9.995 0 0 1-1.724 1.025A4.993 4.993 0 0 0 12.102 16c-1.715 0-3.23.864-4.13 2.18zM12 13a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7zm0-2a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg> <span><?php echo __( 'Profile' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/settings-privacy/<?php echo $profile->username;?>" data-ajax="/settings-privacy/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M3.783 2.826L12 1l8.217 1.826a1 1 0 0 1 .783.976v9.987a6 6 0 0 1-2.672 4.992L12 23l-6.328-4.219A6 6 0 0 1 3 13.79V3.802a1 1 0 0 1 .783-.976zM5 4.604v9.185a4 4 0 0 0 1.781 3.328L12 20.597l5.219-3.48A4 4 0 0 0 19 13.79V4.604L12 3.05 5 4.604zM13 10h3l-5 7v-5H8l5-7v5z"/></svg> <span><?php echo __( 'Privacy' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/settings-password/<?php echo $profile->username;?>" data-ajax="/settings-password/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M19 10h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V11a1 1 0 0 1 1-1h1V9a7 7 0 1 1 14 0v1zM5 12v8h14v-8H5zm6 2h2v4h-2v-4zm6-4V9A5 5 0 0 0 7 9v1h10z"/></svg> <span><?php echo __( 'Password' );?></span>
							</a>
						</li>
						<?php if( $config->social_media_links == 'on' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-social/<?php echo $profile->username;?>" data-ajax="/settings-social/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M10 6v2H5v11h11v-5h2v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6zm11-3v8h-2V6.413l-7.793 7.794-1.414-1.414L17.585 5H13V3h8z"/></svg> <span><?php echo __( 'Social' );?></span>
								</a>
							</li>
						<?php }?>
						<li>
							<a href="<?php echo $site_url;?>/settings-blocked/<?php echo $profile->username;?>" data-ajax="/settings-blocked/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm7 6.586l2.121-2.122 1.415 1.415L20.414 19l2.122 2.121-1.415 1.415L19 20.414l-2.121 2.122-1.415-1.415L17.586 19l-2.122-2.121 1.415-1.415L19 17.586z"/></svg> <span><?php echo __( 'Blocked' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/settings-sessions/<?php echo $profile->username;?>" data-ajax="/settings-sessions/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M12 14v2a6 6 0 0 0-6 6H4a8 8 0 0 1 8-8zm0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm2.595 7.812a3.51 3.51 0 0 1 0-1.623l-.992-.573 1-1.732.992.573A3.496 3.496 0 0 1 17 14.645V13.5h2v1.145c.532.158 1.012.44 1.405.812l.992-.573 1 1.732-.992.573a3.51 3.51 0 0 1 0 1.622l.992.573-1 1.732-.992-.573a3.496 3.496 0 0 1-1.405.812V22.5h-2v-1.145a3.496 3.496 0 0 1-1.405-.812l-.992.573-1-1.732.992-.572zM18 19.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg> <span><?php echo __( 'Sessions' );?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo $site_url;?>/my-info/<?php echo $profile->username;?>" data-ajax="/my-info/<?php echo $profile->username;?>" target="_self">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M19 7h5v2h-5V7zm-2 5h7v2h-7v-2zm3 5h4v2h-4v-2zM2 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H2zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/></svg> <span><?php echo __( 'My Info' );?></span>
							</a>
						</li>
						<?php if( $config->affiliate_system == '1' ){ ?>
							<li>
								<a class="active" href="<?php echo $site_url;?>/settings-affiliate/<?php echo $profile->username;?>" data-ajax="/settings-affiliate/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M1 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H1zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM21.548.784A13.942 13.942 0 0 1 23 7c0 2.233-.523 4.344-1.452 6.216l-1.645-1.196A11.955 11.955 0 0 0 21 7c0-1.792-.393-3.493-1.097-5.02L21.548.784zm-3.302 2.4A9.97 9.97 0 0 1 19 7a9.97 9.97 0 0 1-.754 3.816l-1.677-1.22A7.99 7.99 0 0 0 17 7a7.99 7.99 0 0 0-.43-2.596l1.676-1.22z"/></svg> <span><?php echo __( 'Affiliates' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $config->invite_links_system == '1' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-links/<?php echo $profile->username;?>" data-ajax="/settings-links/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M18.364 15.536L16.95 14.12l1.414-1.414a5 5 0 1 0-7.071-7.071L9.879 7.05 8.464 5.636 9.88 4.222a7 7 0 0 1 9.9 9.9l-1.415 1.414zm-2.828 2.828l-1.415 1.414a7 7 0 0 1-9.9-9.9l1.415-1.414L7.05 9.88l-1.414 1.414a5 5 0 1 0 7.071 7.071l1.414-1.414 1.415 1.414zm-.708-10.607l1.415 1.415-7.071 7.07-1.415-1.414 7.071-7.07z"/></svg> <span><?php echo __( 'Invitation' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $config->two_factor == '1' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-twofactor/<?php echo $profile->username;?>" data-ajax="/settings-twofactor/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M3.783 2.826L12 1l8.217 1.826a1 1 0 0 1 .783.976v9.987a6 6 0 0 1-2.672 4.992L12 23l-6.328-4.219A6 6 0 0 1 3 13.79V3.802a1 1 0 0 1 .783-.976zM5 4.604v9.185a4 4 0 0 0 1.781 3.328L12 20.597l5.219-3.48A4 4 0 0 0 19 13.79V4.604L12 3.05 5 4.604z"/></svg> <span><?php echo __( 'Two Factor' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $config->emailNotification == '1' ){ ?>
							<li>
								<a href="<?php echo $site_url;?>/settings-email/<?php echo $profile->username;?>" data-ajax="/settings-email/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M22 20H2v-2h1v-6.969C3 6.043 7.03 2 12 2s9 4.043 9 9.031V18h1v2zM5 18h14v-6.969C19 7.148 15.866 4 12 4s-7 3.148-7 7.031V18zm4.5 3h5a2.5 2.5 0 1 1-5 0z"/></svg> <span><?php echo __( 'Notifications' );?></span>
								</a>
							</li>
						<?php } ?>
						<?php if( $admin_mode == false && $config->deleteAccount == '1' ) {?>
							<li>
								<a href="<?php echo $site_url;?>/settings-delete/<?php echo $profile->username;?>" data-ajax="/settings-delete/<?php echo $profile->username;?>" target="_self">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M17 6h5v2h-2v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H2V6h5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3zm1 2H6v12h12V8zm-9 3h2v6H9v-6zm4 0h2v6h-2v-6zM9 4v2h6V4H9z"/></svg> <span><?php echo __( 'Delete' );?></span>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="col s12">
				<div class="dt_usr_pmnt_cont" style="margin-top: 0;">
					<h2 class="dt_sett_wrap_title"><?php echo __( 'Request withdrawal' );?></h2>
					<div class="dt_usr_affl">
						<h2 class="valign-wrapper">
							<span><?php echo __('My balance');?>: <?php echo $config->currency_symbol;?><?php echo number_format($profile->aff_balance, 2);?></span>
						</h2>
					</div>
					<br>
					<?php if( (float)$profile->aff_balance < (float)$config->m_withdrawal ){ ?>
					<div class="alert alert-info"><?php echo __('Your balance is');?> <?php echo $config->currency_symbol;?><?php echo number_format($profile->aff_balance, 2);?> <?php echo __(', minimum withdrawal request is');?>	<?php echo $config->currency_symbol;?><?php echo number_format($config->m_withdrawal, 2);?></div>
					<?php }?>
					<?php if( (float)$profile->aff_balance >= (float)$config->m_withdrawal ){ ?>
						<form method="post" action="/profile/request_payment">
							<div class="alert alert-success" role="alert" style="display:none;"></div>
							<div class="alert alert-danger" role="alert" style="display:none;"></div>
							<div class="row">
								<div class="input-field col s12 xs12">
									<select id="withdraw_method" name="withdraw_method" onchange="ShowWithdrawMethod(this)">
										<?php 
			                            $first = 0;
			                            foreach ($config->withdrawal_payment_method as $key => $value) { 
			                                if ($value == 1) {
			                                    if ($first == 0) {
			                                        $first = $key;
			                                    }
			                                    if ($key != 'custom') { ?>
			                                        <option value="<?php echo $key; ?>"><?php echo __($key); ?></option>
			                            <?php   }elseif(!empty($config->custom_name)){ ?>
			                                    <option value="<?php echo $key; ?>"><?php echo $config->custom_name; ?></option>
			                            <?php }}} ?>
									</select>
									<label for="withdraw_method"><?php echo __( 'Withdraw Method' );?></label>
								</div>
								<div class="input-field col s6 xs12 paypal_withdrawal" <?php echo($first == 'paypal' ? '' : 'style="display: none;"'); ?>>
									<input id="paypal_email" name="paypal_email" type="text" maxlength="30" class="validate valid" value="<?php echo $profile->paypal_email;?>" autofocus="">
									<label for="paypal_email" class="active"><?php echo __('PayPal email');?></label>
								</div>
								<div class="input-field col s6 xs12">
									<input id="amount" name="amount" type="text" maxlength="30" class="validate" value="0">
									<label for="amount" class="active"><?php echo __('Amount');?></label>
								</div>
								<div class="transfer_to_withdrawal" <?php echo(($first == 'skrill' || $first == 'custom') ? '' : 'style="display: none;"'); ?>>
			                        <div class="input-field col s6 xs12">
										<input name="transfer_to" id="transfer_to" type="text" class="validate">
										<label for="transfer_to" class="active"><?php echo __('transfer_to');?></label>
										<span class="help-block checking"></span>
									</div>
			                    </div>
								<div class="bank_withdrawal" <?php echo($first == 'bank' ? '' : 'style="display: none;"'); ?>>
									<div class="input-field col s6 xs12">
										<input id="iban" name="iban" type="text" class="validate">
										<label for="iban" class="active"><?php echo __('iban');?></label>
									</div>
									<div class="input-field col s6 xs12">
										<input id="country" name="country" type="text" class="validate">
										<label for="country" class="active"><?php echo __('Country');?></label>
									</div>
									<div class="input-field col s6 xs12">
										<input id="full_name" name="full_name" type="text" class="validate">
										<label for="full_name" class="active"><?php echo __('Full Name');?></label>
									</div>
									<div class="input-field col s6 xs12">
										<input id="swift_code" name="swift_code" type="text" class="validate">
										<label for="swift_code" class="active"><?php echo __('Swift Code');?></label>
									</div>
									<div class="input-field col s12 xs12">
										<textarea name="address" id="address" type="text" class="materialize-textarea"></textarea>
										<label for="address" class="active"><?php echo __('Address');?></label>
									</div>
								</div>
							</div>
							<div class="dt_sett_footer valign-wrapper">
								<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="submit" name="action"><span><?php echo __( 'Request withdrawal' );?></span> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
							</div>
						</form>
					<?php }?>
				</div>
				<div class="dt_usr_pmnt_cont">
					<div class="dt_usr_pmnt_hstry">
						<h5><?php echo __('Payment history'); ?></h5>
						<div class="table-responsive">
							<table class="table table-condensed dt_usr_pmnt_table">
								<thead>
								<tr>
									<th>#</th>
									<th><?php echo __('amount'); ?></th>
									<th><?php echo __('requested'); ?></th>
									<th><?php echo __('status'); ?></th>
								</tr>
								</thead>
								<tbody>
								<?php
								$get_payment = Wo_GetPaymentsHistory($profile->id);
								if (count($get_payment) > 0) {
									foreach ($get_payment as $wo['key'] => $wo['payment']) {
										$wo['key'] = ($wo['key'] + 1);
										$wo['html_class'] = 'label-warning';
										$wo['html_text'] = __('pending review');
										if ($wo['payment']['status'] == '1') {
											$wo['html_class'] = 'label-success';
											$wo['html_text'] = __('approved');
										} else if ($wo['payment']['status'] == '2') {
											$wo['html_class'] = 'label-danger';
											$wo['html_text'] = __('declined');
										} else if ($wo['payment']['status'] == '0') {
											$wo['html_class'] = 'label-danger';
											$wo['html_text'] = __('pending review');
										}
										?>
										<tr>
											<td><?php echo $wo['key']?></td>
											<td><?php echo $config->currency_symbol . $wo['payment']['amount']?></td>
											<td><?php echo $wo['payment']['time_text']?></td>
											<td><span class="label label-status <?php echo $wo['html_class']?>"><?php echo $wo['html_text'];?></span></td>
										</tr>
									<?php
									}
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<!-- End Settings  -->
<script type="text/javascript">
	function ShowWithdrawMethod(self) {
        if ($(self).val() == 'bank') {
            $('.paypal_withdrawal').slideUp();
            $('.transfer_to_withdrawal').slideUp();
            $('.bank_withdrawal').slideDown();
        }
        else if($(self).val() == 'paypal'){
            $('.bank_withdrawal').slideUp();
            $('.transfer_to_withdrawal').slideUp();
            $('.paypal_withdrawal').slideDown();
        }
        else{
            $('.bank_withdrawal').slideUp();
            $('.transfer_to_withdrawal').slideDown();
            $('.paypal_withdrawal').slideUp();
        }
    }
</script>