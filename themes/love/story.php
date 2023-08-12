<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<script src="<?php echo $theme_url . 'assets/js/tinymce/js/tinymce/tinymce.min.js'; ?>"></script>
<div class="container dt_user_profile_parent find_matches_cont">
    <!-- display gps not enable message - see header js -->
    <div class="alert alert-warning hide" role="alert" id="gps_not_enabled">
        <p><?php echo __( 'Please Enable Location Services on your browser.' );?></p>
    </div>
    <script>
        var gps_not_enabled = document.querySelector('#gps_not_enabled');
        if( window.gps_is_not_enabled == true ){
            gps_not_enabled.classList.remove('hide');
        }
    </script>

    <div class="row r_margin">
		<div class="col l1"></div>
        <div class="col l10">
			<div class="center dt_sections dt_read_story">
			<div class="qd_rstroy_quote">
				<div class="">
					<div class="qd_rstroy_thumbs">
						<div class="thumb">
							<img src="<?php echo $data['story']['user1Data']->avater->avater;?>">
						</div>
						<div class="thumb">
							<img src="<?php echo $data['story']['user2Data']->avater->avater;?>">
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" width="296.505" height="275.06" viewBox="0 0 296.505 275.06"> <path id="Path_215779" data-name="Path 215779" d="M3802.175,7988.043l.141.27-126.808,126.809-126.8-126.809.135-.27a89.681,89.681,0,0,1,126.667-126.256,89.675,89.675,0,0,1,126.667,126.256Z" transform="translate(-3527.257 -7840.061)" fill="#fccbf7"/> </svg>
						<span>&amp;</span>
					</div>
					<div class="qd_rstroy_names">
						<h5><?php echo $data['story']['user1Data']->full_name.$data['story']['user1Data']->pro_icon;?></h5>
						<h5><?php echo $data['story']['user2Data']->full_name.$data['story']['user1Data']->pro_icon;?></h5>
					</div>
				</div>
				<div class="dt_read_story_quote">
					<h2><?php echo $data['story']['quote'];?></h2>
					<time><?php echo $data['story']['story_date'] ;?></time>
				</div>
			</div>
            <div class="qd_rstroy_content">
				<?php echo br2nl( html_entity_decode( $data['story']['description'] ));?>
			</div>
			</div>
        </div>
		<div class="col l1"></div>
    </div>
</div>