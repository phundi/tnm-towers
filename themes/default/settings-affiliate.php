<?php
if( $config->affiliate_system == '0' ){
    echo '<script>window.location = window.site_url;</script>';
    exit();
}

$admin_mode = false;
// if( $user->admin == '1' || CheckPermission($user->permission, "manage-users") ){
//     $admin_mode = true;
// }

$target_user = route(2);
if($target_user !== ''){
    $_user = LoadEndPointResource('users');
    if( $_user ){
        if( $target_user !== '' ){
            $user = $_user->get_user_profile(Secure($target_user));
            if( !$user ){
                echo '<script>window.location = window.site_url;</script>';
                exit();
            }else{
                if( $user->admin == '1' || CheckPermission($user->permission, "manage-users") ){
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
<style>
.dt_settings_header {margin-top: -3px;display: inline-block;}
@media (max-width: 1024px){
.dt_slide_menu {
	display: none;
}
nav .header_user {
	display: block;
}
}
</style>
<!-- Settings  -->
<div class="dt_settings_header bg_gradient">
	<div class="dt_settings_circle-1"></div>
	<div class="dt_settings_circle-2"></div>
	<div class="dt_settings_circle-3"></div>
    <div class="container">
        <div class="sett_active_svg">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,6A3,3 0 0,0 9,9A3,3 0 0,0 12,12A3,3 0 0,0 15,9A3,3 0 0,0 12,6M6,8.17A2.5,2.5 0 0,0 3.5,10.67A2.5,2.5 0 0,0 6,13.17C6.88,13.17 7.65,12.71 8.09,12.03C7.42,11.18 7,10.15 7,9C7,8.8 7,8.6 7.04,8.4C6.72,8.25 6.37,8.17 6,8.17M18,8.17C17.63,8.17 17.28,8.25 16.96,8.4C17,8.6 17,8.8 17,9C17,10.15 16.58,11.18 15.91,12.03C16.35,12.71 17.12,13.17 18,13.17A2.5,2.5 0 0,0 20.5,10.67A2.5,2.5 0 0,0 18,8.17M12,14C10,14 6,15 6,17V19H18V17C18,15 14,14 12,14M4.67,14.97C3,15.26 1,16.04 1,17.33V19H4V17C4,16.22 4.29,15.53 4.67,14.97M19.33,14.97C19.71,15.53 20,16.22 20,17V19H23V17.33C23,16.04 21,15.26 19.33,14.97Z"></path></svg>
        </div>
        <div class="sett_navbar valign-wrapper">
            <ul class="tabs">
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings/<?php echo $user->username;?>" data-ajax="/settings/<?php echo $user->username;?>" target="_self"><?php echo __( 'General' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-profile/<?php echo $user->username;?>" data-ajax="/settings-profile/<?php echo $user->username;?>" target="_self"><?php echo __( 'Profile' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-privacy/<?php echo $user->username;?>" data-ajax="/settings-privacy/<?php echo $user->username;?>" target="_self"><?php echo __( 'Privacy' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-password/<?php echo $user->username;?>" data-ajax="/settings-password/<?php echo $user->username;?>" target="_self"><?php echo __( 'Password' );?></a></li>
                <?php if( $config->social_media_links == 'on' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-social/<?php echo $user->username;?>" data-ajax="/settings-social/<?php echo $user->username;?>" target="_self"><?php echo __( 'Social Links' );?></a></li><?php }?>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-blocked/<?php echo $user->username;?>" data-ajax="/settings-blocked/<?php echo $user->username;?>" target="_self"><?php echo __( 'Blocked Users' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/settings-sessions/<?php echo $user->username;?>" data-ajax="/settings-sessions/<?php echo $user->username;?>" target="_self"><?php echo __( 'Manage Sessions' );?></a></li>
                <li class="tab col s3"><a href="<?php echo $site_url;?>/my-info/<?php echo $profile->username;?>" data-ajax="/my-info/<?php echo $profile->username;?>" target="_self"><?php echo __( 'My Information' );?></a></li>
				<?php if( $config->affiliate_system == '1' ){ ?><li class="tab col s3"><a class="active" href="<?php echo $site_url;?>/settings-affiliate/<?php echo $user->username;?>" data-ajax="/settings-affiliate/<?php echo $user->username;?>" target="_self"><?php echo __( 'My affiliates' );?></a></li><?php } ?>
				<?php if( $config->invite_links_system == '1' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-links/<?php echo $user->username;?>" data-ajax="/settings-links/<?php echo $user->username;?>" target="_self"><?php echo __( 'Invitation Links' );?></a></li><?php } ?>
                <?php if( $config->two_factor == '1' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-twofactor/<?php echo $user->username;?>" data-ajax="/settings-twofactor/<?php echo $user->username;?>" target="_self"><?php echo __( 'Two-factor authentication' );?></a></li><?php } ?>
                <?php if( $config->emailNotification == '1' ){ ?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-email/<?php echo $user->username;?>" data-ajax="/settings-email/<?php echo $user->username;?>" target="_self"><?php echo __( 'Manage Notifications' );?></a></li><?php } ?>
                <?php if( $admin_mode == false && $config->deleteAccount == '1' ) {?><li class="tab col s3"><a href="<?php echo $site_url;?>/settings-delete/<?php echo $user->username;?>" data-ajax="/settings-delete/<?php echo $user->username;?>" target="_self"><?php echo __( 'Delete Account' );?></a></li><?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="container">
    <div class="dt_settings row">
        <div class="col s12 m3"></div>
        <form class="col s12 m6">
			<div class="dt_usr_affl">
						<h2 class="valign-wrapper">
							<span><?php echo __('My balance');?>: <?php echo $config->currency_symbol;?><?php echo number_format($user->aff_balance, 2);?></span>
							<a class="btn btn-small waves-effect waves-light btn_primary btn_round" href="<?php echo $site_url;?>/settings-payments/<?php echo $user->username;?>" data-ajax="/settings-payments/<?php echo $user->username;?>"><?php echo __('Request withdrawal');?></a>
						</h2>
						<div class="row mb">
							<div class="col s12 l4">
								<img src="<?php echo $theme_url;?>assets/img/affs.svg" alt="<?php echo __( 'My affiliates' );?>">
							</div>
							<div class="col s12 l8">
								<?php if($config->affiliate_type == '0'){?>
									<div class="alert alert-info"><?php echo __('Earn up to');?> <?php echo $config->currency_symbol;?><?php echo $config->amount_ref;?> <?php echo __('for each user your refer to us !');?></div>
								<?php } else if($config->affiliate_type == '1'){?>
									<div class="alert alert-info"><?php echo __('Earn up to');?> <?php echo $config->amount_percent_ref;?>% <?php echo __('for each user your refer to us and bought a pro package / Credit');?></div>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l4 bold"><?php echo __('Your affiliate link is');?></div>
							<div class="col l8">
								<div class="input-field">
									<input type="text" readonly onclick="this.select();" value="<?php echo $site_url;?>/register?ref=<?php echo $user->username;?>">
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col l4 bold"><?php echo __('Share to');?></div>
							<div class="col l8">
								<div class="social-btn-parent">
									<a href="https://twitter.com/intent/tweet?text=<?php echo $site_url;?>/register?ref=<?php echo $user->username;?>" target="_blank">
										<svg height="512" viewBox="0 0 152 152" width="512" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2"><g id="_02.twitter" data-name="02.twitter"><circle id="background" cx="76" cy="76" fill="#00a6de" r="76"></circle><path id="icon" d="m113.85 53a32.09 32.09 0 0 1 -6.51 7.15 2.78 2.78 0 0 0 -1 2.17v.25a45.58 45.58 0 0 1 -2.94 15.86 46.45 46.45 0 0 1 -8.65 14.5 42.73 42.73 0 0 1 -18.75 12.39 46.9 46.9 0 0 1 -14.74 2.29 45 45 0 0 1 -22.6-6.09 1.3 1.3 0 0 1 -.62-1.44 1.25 1.25 0 0 1 1.22-.94h1.9a30.24 30.24 0 0 0 16.94-5.14 16.42 16.42 0 0 1 -13-11.16.86.86 0 0 1 1-1.11 15.08 15.08 0 0 0 2.76.26h.35a16.43 16.43 0 0 1 -9.57-15.11.86.86 0 0 1 1.27-.75 14.44 14.44 0 0 0 3.74 1.45 16.42 16.42 0 0 1 -2.65-19.92.86.86 0 0 1 1.41-.12 42.93 42.93 0 0 0 29.51 15.78h.08a.62.62 0 0 0 .6-.67 17.36 17.36 0 0 1 .38-6 15.91 15.91 0 0 1 10.7-11.44 17.59 17.59 0 0 1 5.19-.8 16.36 16.36 0 0 1 10.84 4.09 2.12 2.12 0 0 0 1.41.54 2.15 2.15 0 0 0 .5-.07 30 30 0 0 0 8-3.31.85.85 0 0 1 1.25 1 16.23 16.23 0 0 1 -4.31 6.87 30.2 30.2 0 0 0 5.24-1.77.86.86 0 0 1 1.05 1.24z" fill="#fff"></path></g></g></svg>
									</a>
									<a rel="publisher" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $site_url;?>/register?ref=<?php echo $user->username;?>" target="_blank">
										<svg height="512" viewBox="0 0 152 152" width="512" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2"><g id="_01.facebook" data-name="01.facebook"><circle id="background" cx="76" cy="76" fill="#334c8c" r="76"></circle><path id="icon" d="m95.26 68.81-1.26 10.58a2 2 0 0 1 -2 1.78h-11v31.4a1.42 1.42 0 0 1 -1.4 1.43h-11.21a1.42 1.42 0 0 1 -1.4-1.44l.06-31.39h-8.33a2 2 0 0 1 -2-2v-10.58a2 2 0 0 1 2-2h8.28v-10.26c0-11.87 7.06-18.33 17.4-18.33h8.47a2 2 0 0 1 2 2v8.91a2 2 0 0 1 -2 2h-5.19c-5.62.09-6.68 2.78-6.68 6.8v8.85h12.31a2 2 0 0 1 1.95 2.25z" fill="#fff"></path></g></g></svg>
									</a>
									<a href="https://api.whatsapp.com/send?text=<?php echo $site_url;?>/register?ref=<?php echo $user->username;?>" data-action="share/whatsapp/share" target="_blank">
										<svg height="512" viewBox="0 0 152 152" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Layer_2" data-name="Layer 2"><g id="_08.whatsapp" data-name="08.whatsapp"><circle id="background" cx="76" cy="76" fill="#2aa81a" r="76"></circle><g id="icon" fill="#fff"><path d="m102.81 49.19a37.7 37.7 0 0 0 -60.4 43.62l-4 19.42a1.42 1.42 0 0 0 .23 1.13 1.45 1.45 0 0 0 1.54.6l19-4.51a37.7 37.7 0 0 0 43.6-60.26zm-5.94 47.37a29.56 29.56 0 0 1 -34 5.57l-2.66-1.32-11.67 2.76v-.15l2.46-11.77-1.3-2.56a29.5 29.5 0 0 1 5.43-34.27 29.53 29.53 0 0 1 41.74 0l.13.18a29.52 29.52 0 0 1 -.15 41.58z"></path><path d="m95.84 88c-1.43 2.25-3.7 5-6.53 5.69-5 1.2-12.61 0-22.14-8.81l-.12-.11c-8.29-7.74-10.49-14.19-10-19.3.29-2.91 2.71-5.53 4.75-7.25a2.72 2.72 0 0 1 4.25 1l3.07 6.94a2.7 2.7 0 0 1 -.33 2.76l-1.56 2a2.65 2.65 0 0 0 -.21 2.95 29 29 0 0 0 5.27 5.86 31.17 31.17 0 0 0 7.3 5.23 2.65 2.65 0 0 0 2.89-.61l1.79-1.82a2.71 2.71 0 0 1 2.73-.76l7.3 2.09a2.74 2.74 0 0 1 1.54 4.14z"></path></g></g></g></svg>
									</a>
									<a href="https://pinterest.com/pin/create/button/?url=<?php echo $site_url;?>/register?ref=<?php echo $user->username;?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 112.198 112.198" xml:space="preserve"> <g> <circle style="fill:#CB2027;" cx="56.099" cy="56.1" r="56.098"></circle> <g> <path style="fill:#F1F2F2;" d="M60.627,75.122c-4.241-0.328-6.023-2.431-9.349-4.45c-1.828,9.591-4.062,18.785-10.679,23.588 c-2.045-14.496,2.998-25.384,5.34-36.941c-3.992-6.72,0.48-20.246,8.9-16.913c10.363,4.098-8.972,24.987,4.008,27.596 c13.551,2.724,19.083-23.513,10.679-32.047c-12.142-12.321-35.343-0.28-32.49,17.358c0.695,4.312,5.151,5.621,1.78,11.571 c-7.771-1.721-10.089-7.85-9.791-16.021c0.481-13.375,12.018-22.74,23.59-24.036c14.635-1.638,28.371,5.374,30.267,19.14 C85.015,59.504,76.275,76.33,60.627,75.122L60.627,75.122z"></path> </g> </g></svg>
									</a>
									<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $site_url;?>/register?ref=<?php echo $user->username;?>" target="_blank">
										<svg height="512" viewBox="0 0 152 152" width="512" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2"><g id="_10.linkedin" data-name="10.linkedin"><circle id="background" cx="76" cy="76" fill="#0b69c7" r="76"></circle><g id="icon" fill="#fff"><path d="m59 48.37a10.38 10.38 0 1 1 -10.37-10.37 10.38 10.38 0 0 1 10.37 10.37z"></path><rect height="50.93" rx="2.57" width="16.06" x="40.6" y="63.07"></rect><path d="m113.75 89.47v22.17a2.36 2.36 0 0 1 -2.36 2.36h-11.72a2.36 2.36 0 0 1 -2.36-2.36v-21.48c0-3.21.93-14-8.38-14-7.22 0-8.69 7.42-9 10.75v24.78a2.36 2.36 0 0 1 -2.34 2.31h-11.34a2.35 2.35 0 0 1 -2.36-2.36v-46.2a2.36 2.36 0 0 1 2.36-2.37h11.34a2.37 2.37 0 0 1 2.41 2.37v4c2.68-4 6.66-7.12 15.13-7.12 18.73-.01 18.62 17.52 18.62 27.15z"></path></g></g></g></svg>
									</a>
								</div>
							</div>
						</div>
					</div>

            <div class="dt_usr_referres">
                <?php
					$refs = Wo_GetReferrers();
					if (count($refs) > 0) {
						foreach ($refs as $key => $wo['ref']) {
                ?>
					<div class="ref" id="<?php echo $wo['ref']['id'];?>">
						<div class="ref-image">
							<img src="<?php echo GetMedia($wo['ref']['avater']);?>" alt="Image">
						</div>
						<div class="name">
							<a href="<?php echo $site_url . '/@' . $wo['ref']['username'];?>" data-ajax="/@<?php echo $wo['ref']['username'];?>"><?php echo $wo['ref']['first_name'] . ' ' . $wo['ref']['last_name'];?><br></a>
							<div class="joined"><?php echo __('joined'); ?>: <?php echo Time_Elapsed_String($wo['ref']['registered']);?></div>
						</div>
						<div class="clear"></div>
					</div>
					<br>
                <?php } } ?>
            </div>
        </form>

        <div class="col s12 m3"></div>
    </div>
</div>
<!-- End Settings  -->