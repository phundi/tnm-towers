<div class="container page-margin find_matches_cont">
    <div class="row r_margin">
        <div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
        <div class="col l9">
			<div class="valign-wrapper dt_home_rand_user qd_story_head">
				<h4 class="bold"><div style="background: #e935f8;"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M21,2H3A2,2 0 0,0 1,4V20A2,2 0 0,0 3,22H21A2,2 0 0,0 23,20V4A2,2 0 0,0 21,2M11,17.5L9.5,19L5,14.5L9.5,10L11,11.5L8,14.5L11,17.5M14.5,19L13,17.5L16,14.5L13,11.5L14.5,10L19,14.5L14.5,19M21,7H3V4H21V7Z"></path></svg></div> <?php echo __( 'Developers' );?></h4>
				<a class="btn btn-default" href="<?php echo $site_url.'/apps';?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"></path></svg> My Apps
				</a>
			</div>
			<div class="row">
				<div class="col l6 m12">
					<div class="dt_sections">
						<p class="bold no_margin_top">App Keys</p>
						<div class="app-update-alert setting-update-alert"></div>
						<div class="input-field">
							<input id="app_id" name="app_id" type="text" readonly value="<?php echo $data['app_data']['app_id']; ?>" onClick="this.select();">
							<label for="app_id">App ID</label>
							<span class="helper-text">Your application ID.</span>
						</div>
						<br>
						<div class="input-field">
							<input id="app_secret" name="app_secret" type="text" readonly value="<?php echo $data['app_data']['app_secret']; ?>" onClick="this.select();">
							<label for="app_secret">App secret</label>
							<span class="helper-text">Your application secret key.</span>
						</div>
					</div>
				</div>
				<div class="col l6 m12">
					<div class="dt_sections">
						<p class="bold no_margin_top">App Setting</p>
						<form class="app-update-form" method="post" style="margin-top: 0px;">
							<div class="alert alert-success" role="alert" style="display:none;"></div>
							<div class="alert alert-danger" role="alert" style="display:none;"></div>
							<div class="input-field">
								<input id="app_name" name="app_name" type="text" max="32" value="<?php echo $data['app_data']['app_name']; ?>">
								<label for="app_name"><?php echo __( 'Name' );?></label>
								<span class="helper-text"><?php echo __( 'Your application name. This is used to attribute the source user-facing authorization screens. 32 characters max.' );?></span>
							</div>
							<br>
							<div class="input-field">
								<input id="app_website_url" name="app_website_url" type="url" value="<?php echo $data['app_data']['app_website_url']; ?>">
								<label for="app_website_url"><?php echo __( 'Domain' );?></label>
								<span class="helper-text"><?php echo __( "Your application's publicly accessible home page." );?></span>
							</div>
							<br>
							<div class="input-field">
								<input id="app_callback_url" name="app_callback_url" type="url" value="<?php echo $data['app_data']['app_callback_url']; ?>">
								<label for="app_callback_url"><?php echo __( 'Redirect URI' );?></label>
								<span class="helper-text"><?php echo __( 'Where should we return after successfully authenticating?' );?></span>
							</div>
							<br>
							<div class="input-field">
								<textarea name="app_description" id="app_description" rows="4" class="materialize-textarea"><?php echo $data['app_data']['app_description']; ?></textarea>
								<label for="app_description"><?php echo __( 'Description' );?></label>
								<span class="helper-text"><?php echo __( 'Your application description, which will be shown in user-facing authorization screens. Between 10 and 200 characters max.' );?></span>
							</div>
							<br>
							<label><?php echo __( 'Thumbnail' );?> </label>
							<input type="file" id="app_avatar" class="hide" accept="image/x-png, image/gif, image/jpeg" name="app_avatar">
							<span class="dt_selct_avatar dt_create_apps_img" onclick="document.getElementById('app_avatar').click(); return false">
								<span class="svg-empty"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z"></svg></span>
							</span>
							<input name="app_id" id="app_s_id" type="hidden" value="<?php echo $data['app_data']['id']; ?>">
							<div class="dt_sett_footer">
								<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" name="save_setting" value="Save" id="submit_app" type="button"><?php echo __( 'Save' ); ?> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
							</div>
						</form>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
		$('.tabs').tabs();
		
		$("#app_avatar").change(function(event) {
			$(".dt_selct_avatar").html("<img src='" + window.URL.createObjectURL(this.files[0]) + "' alt='Picture'>");
		});
		
        $( document ).on( 'click', '#submit_app', function(e) {
            e.preventDefault();
            image = document.getElementById("app_avatar").files[0];
            var formData = new FormData();
                formData.append('app_name',$('#app_name').val());
                formData.append('app_website_url',$('#app_website_url').val());
                formData.append('app_callback_url',$('#app_callback_url').val());
                formData.append('app_description',$('#app_description').val());
                formData.append("app_avatar", image);
                formData.append("app_id", $('#app_s_id').val());
            url = window.ajax + 'apps/update_app' + window.maintenance_mode;
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false
            }).done(function (data) {
                if(data.status == 200){
                    location.reload()
                }
            }).fail(function (data) {
                $('form#add_new_app').find('.alert-danger').html(data.responseJSON.message).fadeIn("fast");
                setTimeout(function() {
                    $('form#add_new_app').find( '.alert-danger' ).fadeOut( "fast" );
                }, 2000);
            });

        });
    });
</script>