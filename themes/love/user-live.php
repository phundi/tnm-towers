<?php global $db,$_LIBS; ?>
<div class="container page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
	
		<div class="col l9">
			<!-- People i liked  -->
			<div class="container-fluid dt_ltst_users">
				<div class="dt_home_rand_user">
					<h4 class="bold"><div style="background: #8BC34A;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17,10.5L21,6.5V17.5L17,13.5V17A1,1 0 0,1 16,18H4A1,1 0 0,1 3,17V7A1,1 0 0,1 4,6H16A1,1 0 0,1 17,7V10.5M14,16V15C14,13.67 11.33,13 10,13C8.67,13 6,13.67 6,15V16H14M10,8A2,2 0 0,0 8,10A2,2 0 0,0 10,12A2,2 0 0,0 12,10A2,2 0 0,0 10,8Z"></path></svg></div> <?php echo __( 'Live Videos' );?></h4>
					<div class="row" id="liked_users_container">
                        <?php
                            if(empty($data['live_users_html'])){
                                echo '<h5 id="_load_more" class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z"></path></svg>'.__('No more videos to show.') .'</h5>';
                            }else {
                                echo $data['live_users_html'];
                            }
                        ?>
					</div>
                    <?php if(!empty($data['live_users_html'])){ ?>
                        <a href="javascript:void(0);" id="btn_load_more_liked_users" data-lang-nomore="<?php echo __('No more videos to show.');?>" data-ajax-post="/loadmore/LoadUserLive" data-ajax-params="page=2&user_id=<?php echo($data['user']->id); ?>" data-ajax-callback="callback_load_more_liked_users" class="btn waves-effect load_more"><?php echo __('Load more...');?></a>
                    <?php }?>
                </div>
			</div>
			<!-- People i liked -->
		</div>
	</div>
</div>
<div id="modal_remove_live" class="modal">
    <div class="modal-content">
        <h6 class="bold" style="margin-top: 0px;"><?php echo __( 'Are you sure you want to remove the video' );?></h6>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Confirm' );?></button>
    </div>
</div>
<script type="text/javascript">
	function RemoveLiveVideo(id,type = 'show') {
		if (type == 'hide') {
		    $('#modal_remove_live').find('.btn_primary').attr('onclick', "RemoveLiveVideo('"+id+"')");
		    $('#modal_remove_live').modal('open');
		    return false;
		}
		$.post(window.ajax + 'live/remove_video', {post_id: id}, function(data, textStatus, xhr) {
			location.reload()
		});
	}
</script>