
<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>

		<div class="col l6">
			<div class="dt_settings_bg_wrap">
				<form method="POST" id="add_new_app" class="profile">
					<div class="dt_home_rand_user">
						<h4 class="bold big"><?php echo __( 'Create new App' );?></h4>
					</div>
					<div class="sett_prof_cont">
						<div class="alert alert-success" role="alert" style="display:none;"></div>
						<div class="alert alert-danger" role="alert" style="display:none;"></div>

						<div class="row">
							<div class="input-field col m6 s12">
								<input type="text" id="app_name" class="autocomplete">
								<label for="app_name"><?php echo __( 'Name' );?></label>
							</div>
							<div class="input-field col m6 s12">
								<input id="app_website_url" name="app_website_url" type="text" class="datepicker">
								<label for="app_website_url"><?php echo __( 'Domain' );?> </label>
							</div>
						</div>
						<div class="input-field">
							<input id="app_callback_url" type="url" class="validate" name="app_callback_url">
							<label for="app_callback_url"><?php echo __( 'Redirect URI' );?> </label>
						</div>
						<br>
						<div class="input-field">
							<textarea id="app_description" name="app_description" class="materialize-textarea"  cols="30" rows="5" ></textarea>
							<label for="app_description"><?php echo __( 'Description' );?> </label>
						</div>
						<br>
						<input type="file" id="app_avatar" class="hide" accept="image/x-png, image/gif, image/jpeg" name="app_avatar">
						<span class="dt_selct_avatar dt_create_apps_img" onclick="document.getElementById('app_avatar').click(); return false">
							<span class="svg-empty"><svg xmlns="http://www.w3.org/2000/svg" width="23.445" height="21.253" viewBox="0 0 23.445 21.253"><path id="Path_215786" data-name="Path 215786" d="M12346.792-5943.8h-1.759v-3.251h-3.252v-1.754h3.252v-3.249h1.759v3.249h3.253v1.754h-3.253v3.25Zm-7.591-2.167h-11.733a.837.837,0,0,1-.608-.259.806.806,0,0,1-.26-.619v-17.331a.841.841,0,0,1,.26-.616.84.84,0,0,1,.608-.262h19.541a.844.844,0,0,1,.867.878v9.542h-1.758v-8.664h-17.76v15.865l11.049-11.037,3.048,3.044v2.452l-2.9-2.9-.146-.145-8.269,8.294h8.064v1.755Zm-6.3-10.831a1.861,1.861,0,0,1-1.383-.586,2.017,2.017,0,0,1-.583-1.376,2.017,2.017,0,0,1,.585-1.375,1.854,1.854,0,0,1,1.381-.584,1.851,1.851,0,0,1,1.379.586,2,2,0,0,1,.583,1.374,2.006,2.006,0,0,1-.587,1.378A1.843,1.843,0,0,1,12332.9-5956.8Z" transform="translate(-12326.6 5965.053)" fill="currentColor"/></svg> <?php echo __( 'Add Thumbnail' );?></span>
						</span>
					</div>
					<input name="selected_user" type="hidden" id="selected_user" value="">
					<div class="dt_sett_footer valign-wrapper">
						<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="button" name="action" id="submit_app"><span><?php echo __( 'Publish' );?></span> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
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
            url = window.ajax + 'apps/add_new_app' + window.maintenance_mode;
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false
            }).done(function (data) {
                if(data.status == 200){
                    window.location.href = data.location;
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