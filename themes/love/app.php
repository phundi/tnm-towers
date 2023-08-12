<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<div class="container container-fluid container_new page-margin find_matches_cont">
    <div class="row r_margin">
        <div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
        <div class="col l6 dt_deve_app_page">
			<div class="valign-wrapper dt_home_rand_user qd_story_head">
				<h4 class="bold"><?php echo __( 'Developers' );?></h4>
				<a class="btn btn-default" href="<?php echo $site_url.'/apps';?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"></path></svg> My Apps
				</a>
			</div>

			<div class="dt_sections">
				<p class="bold no_margin_top">App Keys</p><br>
				<div class="app-update-alert setting-update-alert"></div>
				<div class="row">
					<div class="col m6">
						<div class="input-field">
							<input id="app_id" name="app_id" type="text" readonly value="<?php echo $data['app_data']['app_id']; ?>" onClick="this.select();">
							<label for="app_id">App ID</label>
							<span class="helper-text">Your application ID.</span>
						</div>
					</div>
					<div class="col m6">
						<div class="input-field">
							<input id="app_secret" name="app_secret" type="text" readonly value="<?php echo $data['app_data']['app_secret']; ?>" onClick="this.select();">
							<label for="app_secret">App secret</label>
							<span class="helper-text">Your application secret key.</span>
						</div>
					</div>
				</div>
			</div>

			<div class="dt_sections">
				<p class="bold no_margin_top">App Setting</p><br>
				<form class="app-update-form" method="post" style="margin-top: 0px;">
					<div class="alert alert-success" role="alert" style="display:none;"></div>
					<div class="alert alert-danger" role="alert" style="display:none;"></div>
					<div class="input-field">
						<input id="app_name" name="app_name" type="text" max="32" value="<?php echo $data['app_data']['app_name']; ?>">
						<label for="app_name"><?php echo __( 'Name' );?></label>
						<span class="helper-text"><?php echo __( 'Your application name. This is used to attribute the source user-facing authorization screens. 32 characters max.' );?></span>
					</div>
					<br>
					
					<div class="row">
						<div class="col m6">
							<div class="input-field">
								<input id="app_website_url" name="app_website_url" type="url" value="<?php echo $data['app_data']['app_website_url']; ?>">
								<label for="app_website_url"><?php echo __( 'Domain' );?></label>
								<span class="helper-text"><?php echo __( "Your application's publicly accessible home page." );?></span>
							</div>
						</div>
						<div class="col m6">
							<div class="input-field">
								<input id="app_callback_url" name="app_callback_url" type="url" value="<?php echo $data['app_data']['app_callback_url']; ?>">
								<label for="app_callback_url"><?php echo __( 'Redirect URI' );?></label>
								<span class="helper-text"><?php echo __( 'Where should we return after successfully authenticating?' );?></span>
							</div>
						</div>
					</div>
					
					<div class="input-field">
						<textarea name="app_description" id="app_description" rows="4" class="materialize-textarea"><?php echo $data['app_data']['app_description']; ?></textarea>
						<label for="app_description"><?php echo __( 'Description' );?></label>
						<span class="helper-text"><?php echo __( 'Your application description, which will be shown in user-facing authorization screens. Between 10 and 200 characters max.' );?></span>
					</div>
					<br>
					<input type="file" id="app_avatar" class="hide" accept="image/x-png, image/gif, image/jpeg" name="app_avatar">
					<span class="dt_selct_avatar dt_create_apps_img" onclick="document.getElementById('app_avatar').click(); return false">
						<span class="svg-empty"><svg xmlns="http://www.w3.org/2000/svg" width="23.445" height="21.253" viewBox="0 0 23.445 21.253"><path id="Path_215786" data-name="Path 215786" d="M12346.792-5943.8h-1.759v-3.251h-3.252v-1.754h3.252v-3.249h1.759v3.249h3.253v1.754h-3.253v3.25Zm-7.591-2.167h-11.733a.837.837,0,0,1-.608-.259.806.806,0,0,1-.26-.619v-17.331a.841.841,0,0,1,.26-.616.84.84,0,0,1,.608-.262h19.541a.844.844,0,0,1,.867.878v9.542h-1.758v-8.664h-17.76v15.865l11.049-11.037,3.048,3.044v2.452l-2.9-2.9-.146-.145-8.269,8.294h8.064v1.755Zm-6.3-10.831a1.861,1.861,0,0,1-1.383-.586,2.017,2.017,0,0,1-.583-1.376,2.017,2.017,0,0,1,.585-1.375,1.854,1.854,0,0,1,1.381-.584,1.851,1.851,0,0,1,1.379.586,2,2,0,0,1,.583,1.374,2.006,2.006,0,0,1-.587,1.378A1.843,1.843,0,0,1,12332.9-5956.8Z" transform="translate(-12326.6 5965.053)" fill="currentColor"/></svg> <?php echo __( 'Add Thumbnail' );?></span>
					</span>
					<input name="app_id" id="app_s_id" type="hidden" value="<?php echo $data['app_data']['id']; ?>">
					<div class="dt_sett_footer">
						<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" name="save_setting" value="Save" id="submit_app" type="button"><?php echo __( 'Save' ); ?> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
					</div>
				</form>
			</div>
        </div>
		
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
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