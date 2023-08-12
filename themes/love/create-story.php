<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<script src="<?php echo $config->uri . '/admin-panel/vendors/tinymce/js/tinymce/tinymce.min.js'; ?>"></script>
<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>

		<div class="col l6">
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

			<div class="dt_settings_bg_wrap">
				<form method="POST" id="add_new_story" class="profile">
					<div class="dt_home_rand_user">
						<h4 class="bold big"><?php echo __( 'Add new success stories' );?></h4>
					</div>
					<div class="sett_prof_cont">
						<div class="alert alert-success" role="alert" style="display:none;"></div>
						<div class="alert alert-danger" role="alert" style="display:none;"></div>

						<div class="row">
							<div class="input-field col m6 s12 qd_crte_stroy_usr">
								<input type="text" id="story_users" class="autocomplete" onchange="sendItem(this, event)">
								<label for="story_users">I have story with *</label>
							</div>
							<div class="input-field col m6 s12">
								<input id="start_date" name="start_date" type="text" class="datepicker">
								<label for="start_date"><?php echo __( 'Story date' );?> *</label>
							</div>
						</div>
						<div class="input-field">
							<input id="quote" type="text" class="validate" name="quote">
							<label for="quote"><?php echo __( 'Quote' );?> *</label>
						</div>
						<div class="input-field">
							<br><br>
							<textarea id="story" name="story" class="materialize-textarea"  cols="30" rows="5" ></textarea>
							<label for="story"><?php echo __( 'Story (HTML allowed)' );?> *</label>
						</div>
					</div>
					<input name="selected_user" type="hidden" id="selected_user" value="">
					<br>
					<div class="dt_sett_footer valign-wrapper">
						<button class="btn btn-large waves-effect waves-light bold btn_primary btn_round" type="button" name="action" id="submit_story"><span><?php echo __( 'Publish' );?></span> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
					</div>
				</form>
			</div>
		</div>
		
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
		</div>
	</div>
    
</div>
<!-- End Profile  -->
<a href="javascript:void(0);" id="btnStorySuccessRedirect" data-ajax="/stories" style="visibility: hidden;display: none;"></a>
<script>
    function sendItem(ele, e) {
        $('#selected_user').attr('value', ele.value );
    }

    $(document).ready(function(){

        $( document ).on( 'click', '#submit_story', function(e) {
            e.preventDefault();
            let selected_user = $('#selected_user').attr('value');
            if( selected_user === '' ){
                $('form#add_new_story').find('.alert-danger').html("<?php echo __('Please select user first.');?>").fadeIn("fast");
                setTimeout(function() {
                    $('form#add_new_story').find( '.alert-danger' ).fadeOut( "fast" );
                }, 2000);
                return false;
            }
            let story_date = $('#start_date')[0].value;
            if( story_date === '' ){
                $('form#add_new_story').find('.alert-danger').html("<?php echo __('Please select when story started.');?>").fadeIn("fast");
                setTimeout(function() {
                    $('form#add_new_story').find( '.alert-danger' ).fadeOut( "fast" );
                }, 2000);
                return false;
            }
            let quote = $('#quote').val();
            if( quote === '' ){
                $('form#add_new_story').find('.alert-danger').html("<?php echo __('Please enter quote.');?>").fadeIn("fast");
                setTimeout(function() {
                    $('form#add_new_story').find( '.alert-danger' ).fadeOut( "fast" );
                }, 2000);
                return false;
            }
            let story = tinymce.activeEditor.getContent();
            if( story === '' ){
                $('form#add_new_story').find('.alert-danger').html("<?php echo __('Please enter your story.');?>").fadeIn("fast");
                setTimeout(function() {
                    $('form#add_new_story').find( '.alert-danger' ).fadeOut( "fast" );
                }, 2000);
                return false;
            }

            var formData = new FormData();
                formData.append('selected_user',selected_user);
                formData.append('story_date',story_date);
                formData.append('quote',quote);
                formData.append('story',btoa(unescape(encodeURIComponent(story))));

            url = window.ajax + 'profile/add_new_story' + window.maintenance_mode;

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if(result.status == 200){

                        $('form#add_new_story').find('.alert-success').html(result.message).fadeIn("fast");
                        setTimeout(function() {
                            $('form#add_new_story').find( '.alert-success' ).fadeOut( "fast" );
                            $("#btnStorySuccessRedirect").click();
                        }, 3000);

                    }
                },error(data){
                    $('form#add_new_story').find('.alert-danger').html(data.responseJSON.message).fadeIn("fast");
                    return false;
                }
            });

        });

        tinymce.init({
            selector: '#story',
            height: 270,
            entity_encoding : "raw",
            paste_data_images: true,
            image_advtab: true,
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "template paste textcolor colorpicker textpattern"
            ],
        });

        $('#story_users').autocomplete({
            data: <?php echo $data['users']; ?>,
        });
    });
</script>