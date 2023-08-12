<?php global $db,$_LIBS; ?>
<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
	
		<div class="col l9">
			<!-- People i disliked  -->
			<div class="container-fluid dt_ltst_users">
				<div class="dt_home_rand_user">
					<h4 class="bold"><?php echo __( 'People i disliked' );?></h4>
					<div class="row" id="disliked_users_container">
                        <?php
                        if(empty($data['disliked'])){
                            echo '<div class="col s12"><div class="dt_sections"><h5 id="_load_more" class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z"></path></svg>'.__('No more users to show.') .'</h5></div></div>';
                        }else {
                            echo $data['disliked'];
                        }
                        ?>
					</div>
                    <?php if(!empty($data['disliked'])){ ?>
                        <a href="javascript:void(0);" id="btn_load_more_disliked_users" data-lang-nomore="<?php echo __('No more users to show.');?>" data-ajax-post="/loadmore/disliked_users" data-ajax-params="page=2" data-ajax-callback="callback_load_more_disliked_users" class="btn waves-effect load_more"><?php echo __('Load more...');?></a>
                    <?php }?>
                </div>
			</div>
			<!-- People i disliked -->
		</div>
	</div>
</div>