<div class="container page-margin find_matches_cont">
    <div class="row r_margin">
        <div class="col l2"></div>
        <div class="col l8">
			<div class="valign-wrapper dt_home_rand_user qd_story_head">
				<h4 class="bold"><div style="background: #e935f8;"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M7 3H5V9H7V3M19 3H17V13H19V3M3 13H5V21H7V13H9V11H3V13M15 7H13V3H11V7H9V9H15V7M11 21H13V11H11V21M15 15V17H17V21H19V17H21V15H15Z"></path></svg></div> App Permissions</h4>
				<a class="btn" href="<?php echo $data['app_data']['app_website_url'];?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"></path></svg> Back
				</a>
			</div>
			
            <div class="dt_terms_content_body">
				<div class="dt_sections dt_app_perm">
					<img class="avatar" src="<?php echo $data['app_data']['app_avatar'];?>" alt="<?php echo $data['app_data']['app_name']; ?> Profile Picture"/>
					<h2><?php echo $data['app_data']['app_name']; ?></h2>
					<p class="bold"><?php echo $data['app_data']['app_description']?></p>
					<div class="clear"></div>
					<hr class="border_hr">
					<h4><b><?php echo $data['app_data']['app_name']; ?></b> would to receive the following info: <br><small>(email, profile info)</small></h4>
					<hr class="border_hr">
					<div class="dt_sett_footer">
						<button onclick="AcceptPermissions(<?php echo $data['app_data']['id'];?>);" class="btn btn-large waves-effect waves-light bold btn_primary btn_round okdok">Accept <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
						<?php 
							$url = $data['app_data']['app_website_url'];
							if (isset($_GET['redirect_uri']) && !empty($_GET['redirect_uri'])) {
								$url = $_GET['redirect_uri'];
							} else if (!empty($data['app_data']['app_callback_url'])) {
								$url = $data['app_data']['app_callback_url'];
							}
						?>
						<input type="hidden" id="url" name="url" value="<?php echo urlencode($url);?>">
					</div>
				</div>
            </div>
        </div>
		<div class="col l2"></div>
    </div>
</div>	
<!-- .page-margin -->
<script>
  function AcceptPermissions(id) {
	  $('.okdok').addClass('disabled');
    var url = $('#url').val();
    ajax_url = window.ajax + 'apps/acceptPermissions' + window.maintenance_mode;
    $.post(ajax_url, {id:id, url:url}, function (data) {
      if (data.status == 200) {
        window.location.href = data.location;
      }
    });
  }
</script>