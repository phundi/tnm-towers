<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<div class="container container-fluid container_new page-margin find_matches_cont">
    <div class="row r_margin">
        <div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
        <div class="col l9">
			<div class="container-fluid dt_ltst_users dt_deve_app_page">
				<div class="valign-wrapper dt_home_rand_user qd_story_head">
					<h4 class="bold"><?php echo __( 'Developers' );?></h4>
					<a class="btn btn_primary" href="<?php echo $site_url.'/create-app'; ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"></path></svg> <?php echo __( 'Create App' ) ?>
					</a>
				</div>
				
				<?php if (!empty($data['apps_data'])) { ?>
					<div class="row dev">
						<?php foreach ($data['apps_data'] as $app) { ?>
							<div class="col l3 m6 s12 random_user_item">
								<div class="card valign-wrapper">
									<div class="card-image">
										<a href="<?php echo $site_url.'/app/'.$app['id']; ?>">
											<img src="<?php echo $app['app_avatar'];?>" alt="<?php echo $app['app_name']; ?>">
										</a>
										<a class="btn" href="<?php echo $site_url.'/app/'.$app['id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="19.442" height="19.451" viewBox="0 0 19.442 19.451"> <path id="Subtraction_40" data-name="Subtraction 40" d="M5.217,19.451a.692.692,0,0,1-.492-.211L.212,14.73a.686.686,0,0,1-.2-.491A.668.668,0,0,1,.2,13.761L13.765.205A.652.652,0,0,1,14.241.01a.677.677,0,0,1,.49.2l4.51,4.513a.683.683,0,0,1,.2.491.666.666,0,0,1-.192.475L5.692,19.247A.673.673,0,0,1,5.217,19.451Zm-.756-8h0L1.679,14.239l3.273,3.273.265.264,12.3-12.3.263-.264L14.5,1.939l-.262-.263L11.458,4.458l1.5,1.5-.977.983-1.5-1.5L8.444,7.471l1.509,1.5-.982.983-1.5-1.51L5.433,10.481l1.509,1.5-.981.979-1.5-1.509Zm14.47,7.477H14.945l-2.436-2.437.975-.975,2.131,2.124.107.108H17.75V15.723l-2.231-2.232.976-.983,2.436,2.436v3.986ZM2.958,6.944v0L.2,4.181a.54.54,0,0,1-.138-.2A.792.792,0,0,1,0,3.7a.693.693,0,0,1,.057-.261A.542.542,0,0,1,.2,3.235L3.226.208A.666.666,0,0,1,3.7,0,.712.712,0,0,1,4.2.208l2.746,2.75-.981.975L3.971,1.939,3.7,1.675,1.679,3.7,3.934,5.961l-.974.981Z" fill="#86168b"/> </svg></a>
									</div>
									<div class="card-content">
										<a href="<?php echo $site_url.'/app/'.$app['id']; ?>"><span class="card-title"><?php echo $app['app_name']; ?></span></a>
										<div>App ID</div>
										<p><?php echo $app['app_id']; ?></p>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="clear"></div>
					</div>
				<?php } else {
						echo '<div class="dt_sections"><h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,3H19A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3M7,7V9H9V7H7M11,7V9H13V7H11M15,7V9H17V7H15M7,11V13H9V11H7M11,11V13H13V11H11M15,11V13H17V11H15M7,15V17H9V15H7M11,15V17H13V15H11M15,15V17H17V15H15Z" /></svg> No applications found</h5></div>';
					}
				?>
			</div>
        </div>
    </div>
</div>