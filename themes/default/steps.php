<?php
   global $db;
   $error = "";
   if( isset( $_SESSION['JWT'] ) ){
       $profile = auth();
   }else{
       exit();
   }

   $current_step = "";
   $step = null;
   
   if($profile->start_up == 0 && $config->verification_on_signup == 1){
      $current_step = "slider-zero-active";
      $step = 0;
   }
   elseif ($profile->start_up == 1 || ($profile->start_up == 0 && $config->verification_on_signup != 1)) {
      $current_step = "slider-one-active";
      $step = 1;
   }
   elseif ($profile->start_up == 2) {
      $current_step = "slider-two-active";
      $step = 2;
   }
   else{
      $current_step = "slider-three-active";
      $step = 3;
   }

?>
    <!-- Step One  -->
    <div class="container slider_container <?php echo $current_step;?>">
        <div class="row">
            <div>
                <div class="dt_signup_steps">
                    <?php if(($config->verification_on_signup == 1 || $config->image_verification == 1 || $config->pending_verification == 1) && $profile->start_up == 3 && $profile->phone_verified == 1){ ?>
						<h5 class="empty_state">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15,3H12V6H8V3H5A2,2 0 0,0 3,5V21A2,2 0 0,0 5,23H15A2,2 0 0,0 17,21V5A2,2 0 0,0 15,3M10,8A2,2 0 0,1 12,10A2,2 0 0,1 10,12A2,2 0 0,1 8,10A2,2 0 0,1 10,8M14,16H6V15C6,13.67 8.67,13 10,13C11.33,13 14,13.67 14,15V16M11,5H9V1H11V5M14,19H6V18H14V19M10,21H6V20H10V21M19,12V7H21V12H19M19,16V14H21V16H19Z" /></svg>
							<?php echo __('Your account wait admin photo verification. Please try again later.');?>
						</h5>
                    <?php }else{ ?>
                    <div class="steps_header">
                        <div class="steps">
                            <div class="step step-one">
                                <div class="liner"></div>
                                <span><?php echo __( 'Avatar' );?></span>
                            </div>

                            <div class="step step-two" >
                                <div class="liner"></div>
                                <span><?php echo __( 'Info' );?></span>
                            </div>

                            <div class="step step-three">
                                <div class="liner"></div>
                                <span>
                                    <?php if( $config->emailValidation == "0" ) {?>
                                        <?php echo __( 'Finish' );?>
                                    <?php }else{ ?>
                                        <?php echo __( 'Verification' );?>
                                    <?php } ?>
                                </span>
                            </div>

                        </div>
                        <div class="line">
                            <div class="dot-move"></div>
                            <div class="dot zero"></div>
                            <div class="dot center"></div>
                            <div class="dot full"></div>
                        </div>
                    </div>
                    <div class="slider-ctr">
                        <div class="slider">
                            <div class="steps_alerts" style="padding: 0px 50px 0px;;width: 680px;"></div>
                            <?php if ($config->verification_on_signup == 1) { ?>
                            <form class="slider-form slider-one first_slider <?php if ($step != 0) { ?> hide <?php } ?>" id="verification_on_signup" style="padding: 0px;">
                                <div class="webcam_photo_verification <?php if( $profile->status == 0 ){ ?>hide0<?php }?>" >
                                    <h6 class="bold"><?php echo __( 'Verify your' );?> <?php echo $config->site_name;?> <?php echo __( 'account' );?>.</h6>
                                    <p><?php echo __( 'Please upload a photo with your passport / ID  & your distinct photo' );?>.</p>

                                    <div class="row">
                                        <div class="col m6 s6">

                                            <?php
                                                $vimg = get_verification_photo($profile->id);
                                                if( $vimg !== '' ){?>
                                                <span class="dt_selct_avatar qd_select_verifi_start dt_selct_avatar_vphoto_img" onclick="document.getElementById('vphoto_img').click(); return false" style="background-image: url(<?php echo GetMedia($vimg) ;?>);"></span>
                                            <?php }else{ ?>
                                                <span class="dt_selct_avatar qd_select_verifi_start dt_selct_avatar_vphoto_img" onclick="document.getElementById('vphoto_img').click(); return false">
                                                    <span class="svg-empty"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z"></svg></span>
                                                </span>
                                            <?php } ?>
                                            <input type="file" id="vphoto_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="vphoto">
                                            <div class="progress vphoto_progress qd_select_verifi_start_progress hide">
                                                <div class="determinate vphoto_determinate" style="width: 0%"></div >
                                            </div>
                                        </div>
                                        <div class="col m6 s6">

                                            <?php
                                            $pimg = get_verification_passport($profile->id);
                                            if( $pimg !== '' ){?>
                                                <span class="dt_selct_avatar qd_select_verifi_start dt_selct_avatar_vpassport_img" onclick="document.getElementById('vpassport_img').click(); return false" style="background-image: url(<?php echo GetMedia($pimg) ;?>);"></span>
                                            <?php }else{ ?>
                                                <span class="dt_selct_avatar qd_select_verifi_start dt_selct_avatar_vpassport_img" onclick="document.getElementById('vpassport_img').click(); return false">
                                                    <span class="svg-empty"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2,3H22C23.05,3 24,3.95 24,5V19C24,20.05 23.05,21 22,21H2C0.95,21 0,20.05 0,19V5C0,3.95 0.95,3 2,3M14,6V7H22V6H14M14,8V9H21.5L22,9V8H14M14,10V11H21V10H14M8,13.91C6,13.91 2,15 2,17V18H14V17C14,15 10,13.91 8,13.91M8,6A3,3 0 0,0 5,9A3,3 0 0,0 8,12A3,3 0 0,0 11,9A3,3 0 0,0 8,6Z"></svg></span>
                                                </span>
                                            <?php } ?>
                                            <input type="file" id="vpassport_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="vpassport">
                                            <div class="progress vpassport_progress qd_select_verifi_start_progress hide">
                                                <div class="determinate vpassport_determinate" style="width: 0%"></div >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $class = "hide";
                                    $verification_requests = $db->where('user_id', $profile->id)->get('verification_requests',null,array('*'));
                                    if(!empty($verification_requests[0])){
                                        if($verification_requests[0]['passport'] !== '' && $verification_requests[0]['photo'] !== ''){
                                            $class = "";
                                        }
                                    }
                                ?>
                                <div class="step_footer verification_requests_footer <?php echo $class;?>">
                        <button class="waves-effect waves-light btn btn_primary bold first next" onclick="GoToNextStep('first_slider',1)">
                           <span id="nexttext"><?php echo __( 'Next' );?></span> 
                           <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                              <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path>
                           </svg>
                        </button>
                     </div>

                            </form>
                            <?php } ?>


                            <!-- Step 1  -->
                            <form class="slider-form slider-one <?php if ($step != 1) { ?> hide <?php } ?> second_slider" id="profile_image_upload" style="<?php if( $config->image_verification == 1 && $profile->status == 3 ){ ?>padding: 0px;<?php }?>">
                                <div class="choose_photo ">
                                    <h6 class="bold"><?php echo ( $profile->full_name !== "" ? $profile->full_name.$profile->pro_icon : $profile->username ) ;?>, <?php echo __( 'people want to see what you look like!' );?></h6>
                                    <p><?php echo __( 'Upload Images to set your Profile Picture Image.' );?></p>

                                    <?php if( $profile->avater->full !== '' ){?>
                                        <span class="dt_selct_avatar" onclick="document.getElementById('avatar_img').click(); return false" style="background-image: url(<?php echo $profile->avater->full ;?>);background-repeat: no-repeat;background-size: cover;background-position: center center;">

                                        </span>
                                    <?php }else{ ?>
                                        <span class="dt_selct_avatar" onclick="document.getElementById('avatar_img').click(); return false">
                                            <span class="svg-empty"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,16V19H16V21H19V24H21V21H24V19H21V16H19Z"></svg></span>
                                        </span>
                                    <?php } ?>

                                    <input type="file" id="avatar_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="avatar">
                                    <div class="progress hide" style="width: 180px;margin: auto;margin-top: 25px;padding-top: 4px;">
                                        <div class="determinate" style="width: 0%"></div >
                                    </div>
                                </div>

                                <?php if( $config->image_verification == 1 ){ ?>
                                <div class="webcam_photo_verification" >
                                    <h6 class="bold"><?php echo __( 'Verify your' );?> <?php echo $config->site_name;?> <?php echo __( 'account' );?>.</h6>
                                    <p><?php echo __( 'You will be required to take a selfie holding the ID document next to your face, so we can compare your photo with your actual look.This is just an additional security measure' );?>.</p>
                                    <div class="no_camera hide">
                                        <h5 class="empty_state">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M3.27,2L2,3.27L4.73,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16C16.2,18 16.39,17.92 16.54,17.82L19.73,21L21,19.73M21,6.5L17,10.5V7A1,1 0 0,0 16,6H9.82L21,17.18V6.5Z" /></svg>
											<?php echo __( 'Your camera is off or disconnected, Please connect your camera and try again.' );?>.
                                            <div id="errorMsg"></div>
										</h5>
                                        <button class="btn btn_primary btn_round waves-effect waves-light" id="btn-try-again"><?php echo __('Try again');?></button>
                                    </div>
									<div class="qd_verfy_pic_wcam row">
										<div class="col m6">
											<img src="<?php echo $theme_url;?>assets/img/img_verification.jpg" id="taken_shot" class="hide">
										</div>
										<div class="col m6">
											<div id="take_snapshot" class="hide">
												<video width="400" height="170" id="video" autoplay></video>
												<button class="waves-effect waves-light btn" id="btn-take-snapshot"><?php echo __( 'Take Snapshot' );?></button>
											</div>
											<div class="hide" id="retake_snapshot">
												<canvas width="226" height="170" class="camera_2" id='camera_canves'></canvas>
												<button class="waves-effect waves-light btn bold" id="btn-retake-snapshot"><?php echo __( 'Retake Snapshot' );?></button>
											</div>
										</div>
									</div>
                                </div>

                                <script>

                                    const constraints = window.constraints = {
                                        audio: false,
                                        video: true
                                    };

                                    function handleSuccess(stream) {
                                        const video = document.querySelector('video');
                                        const videoTracks = stream.getVideoTracks();
                                        console.log('Got stream with constraints:', constraints);
                                        console.log(`Using video device: ${videoTracks[0].label}`);
                                        window.stream = stream; // make variable available to browser console
                                        video.srcObject = stream;

                                        $('.no_camera').addClass('hide');
                                        $('#take_snapshot').removeClass('hide');
                                        $('#retake_snapshot').addClass('hide');
                                        $('#taken_shot').removeClass('hide');
                                        $('#btn-upload-images').removeClass('hide');
                                        //$('.step_footer').removeClass('hide');

                                        $('.slider-one').css({'padding': 'none'});
                                        $('.choose_photo').removeClass('hide');
                                        //$('.webcam_photo_verification').addClass('hide');

                                    }

                                    function handleError(error) {
                                        if (error.name === 'ConstraintNotSatisfiedError') {
                                            const v = constraints.video;
                                            errorMsg(`The resolution ${v.width.exact}x${v.height.exact} px is not supported by your device.`);
                                        } else if (error.name === 'PermissionDeniedError') {
                                            errorMsg('Permissions have not been granted to use your camera and ' +
                                            'microphone, you need to allow the page access to your devices in ' +
                                            'order for the demo to work.');
                                        }
                                        errorMsg(`getUserMedia error: ${error.name}`, error);


                                        $('.slider-one').css({'padding': '0px'});
                                        $('.choose_photo').addClass('hide');
                                        $('.webcam_photo_verification').removeClass('hide');


                                        $('.no_camera').removeClass('hide');
                                        $('#take_snapshot').addClass('hide');
                                        $('#retake_snapshot').addClass('hide');
                                        $('#taken_shot').addClass('hide');
                                        $('#btn-upload-images').addClass('hide');
                                        $('.step_footer').addClass('hide');
                                        $('#camera_canves').addClass('hide');

                                    }

                                    function errorMsg(msg, error) {
                                        const errorElement = document.querySelector('#errorMsg');
                                        errorElement.innerHTML += `<p>${msg}</p>`;
                                        // if (typeof error !== undefined) {
                                        //     console.error(error);
                                        // }
                                    }

                                    async function init(e) {
                                        try {
                                            const stream = await navigator.mediaDevices.getUserMedia(constraints);
                                            handleSuccess(stream);
                                            //e.target.disabled = true;
                                        } catch (e) {
                                            handleError(e);
                                        }
                                    }


                                    $(document).ready(function() {
                                        window.camera_canvas = document.getElementById("camera_canves");
                                        window.camera_ctx = window.camera_canvas.getContext('2d');
                                    });

                                    // window.camera_canvas = document.getElementById("camera_canves");
                                    // window.camera_ctx = window.camera_canvas.getContext('2d');

                                    // navigator.getUserMedia = ( navigator.getUserMedia ||
                                    //     navigator.webkitGetUserMedia ||
                                    //     navigator.mozGetUserMedia ||
                                    //     navigator.msGetUserMedia);

                                    // window.camera_video;
                                    // var webcamStream;
                                    // if (navigator.getUserMedia) {
                                    //     navigator.getUserMedia (

                                    //         // constraints
                                    //         {
                                    //             video: true,
                                    //             audio: false
                                    //         },

                                    //         // successCallback
                                    //         function(localMediaStream) {
                                    //             window.camera_video = document.getElementById('video');
                                    //             //video.src = window.URL.createObjectURL(localMediaStream);
                                    //             webcamStream = localMediaStream;
                                    //             window.camera_video.srcObject = webcamStream;

                                    //             $('.no_camera').addClass('hide');
                                    //             $('#take_snapshot').removeClass('hide');
                                    //             $('#retake_snapshot').addClass('hide');
                                    //             $('#taken_shot').removeClass('hide');
                                    //             $('#btn-upload-images').removeClass('hide');
                                    //             $('.step_footer').removeClass('hide');

                                    //             $('.slider-one').css({'padding': 'none'});
                                    //             $('.choose_photo').removeClass('hide');
                                    //             $('.webcam_photo_verification').addClass('hide');

                                    //         },

                                    //         // errorCallback
                                    //         function(err) {


                                    //             $('.slider-one').css({'padding': '0px'});
                                    //             $('.choose_photo').addClass('hide');
                                    //             $('.webcam_photo_verification').removeClass('hide');


                                    //             $('.no_camera').removeClass('hide');
                                    //             $('#take_snapshot').addClass('hide');
                                    //             $('#retake_snapshot').addClass('hide');
                                    //             $('#taken_shot').addClass('hide');
                                    //             $('#btn-upload-images').addClass('hide');
                                    //             $('.step_footer').addClass('hide');
                                    //             $('#camera_canves').addClass('hide');
                                    //             console.log("" + err);
                                    //         }
                                    //     );
                                    // } else {
                                    //     alert("webRTC isn't supported in your device");
                                    // }

                                </script>
                                <?php } ?>

                                <div class="step_footer">
                        <p><?php //echo __( '1 of 3 steps to complete to access PRO upgrade, features' );?></p>
                        <button class="waves-effect waves-light btn btn_primary bold first next" id="btn-upload-images" data-pending-verification="<?php echo $config->pending_verification;?>" data-image-verification="<?php echo $config->image_verification;?>" <?php if($profile->src == 'Facebook' ) { } else { echo 'disabled'; }?> data-src="<?php echo $profile->src;?>" data-selected="<?php if($profile->src == 'Facebook' ) { echo str_replace( $config->uri . '/' , '', $profile->avater->full); } ?>" data-defaultText="<?php echo __( 'Next' );?>">
                           <span id="nexttext"><?php echo __( 'Next' );?></span> 
                           <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                              <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path>
                           </svg>
                        </button>
                     </div>
                            </form>

                            <!--
                            <form class="slider-form slider-img">

                                <div class="step_footer">
                                    <button class="waves-effect waves-light btn btn_primary bold firstimg next" id="btn-verify-image" disabled data-selected=""><?php echo __( 'Next' );?> <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path></svg></button>
                                </div>
                            </form>
                            -->

                            <!-- Step 2  -->
                            <form class="slider-form slider-two <?php if ($step != 2) { ?> hide <?php } ?> third_slider">
                                <div class="row">
                                    <div class="input-field col s6">
                                        <select id="height" name="height" data-errmsg="<?php echo __( 'Your height is required.');?>">
                                            <?php echo DatasetGetSelect( null, "height", __("Height") );?>
                                        </select>
                                        <label for="height"><?php echo __( 'Height' );?></label>
                                    </div>
                                    <div class="input-field col s6">
                                        <select id="hair" name="hair">
                                            <?php echo DatasetGetSelect( null, "hair_color", __("Choose your Hair Color") );?>
                                        </select>
                                        <label for="hair"><?php echo __( 'Hair Color' );?></label>
                                    </div>
                                </div>
                                <?php if( $config->disable_phone_field == 'on' ){ ?>
                                    <div class="input-field col s6">
                                        <input id="mobile" type="text" data-errmsg="<?php echo __( 'Your phone number is required.');?>" class="validate" title="Field must be a number." placeholder="<?php echo __('Phone number, e.g +90..');?>" <?php if($config->sms_or_email == 'sms'){?> data-validation-type="sms" required<?php }else{?> data-validation-type="mail" <?php } ?> data-p-verified="yes">
                                        <label for="mobile"><?php echo __( 'Mobile Number' );?></label>
                                    </div>
                                <?php }?>
                                    <div class="input-field col s6">
                                        <select id="country" data-errmsg="<?php echo __( 'Select your country.');?>" required>
                                            <option value="" disabled selected><?php echo __( 'Choose your country' );?></option>
                                            <?php
                                            foreach( Dataset::load('countries') as $key => $val ){
                                                echo '<option value="'. $key .'" data-code="'. $val['isd'] .'">'. $val['name'] .'</option>';
                                            }
                                            ?>
                                        </select>
                                        <label for="country"><?php echo __( 'Country' );?></label>
                                    </div>
                                    <div class="input-field col s6">
                                        <select id="gender" name="gender" data-errmsg="<?php echo __( 'Choose your Gender');?>" required>
                                            <?php echo DatasetGetSelect( null, "gender", __("Choose your Gender") );?>
                                        </select>
                                        <label for="gender"><?php echo __( 'Gender' );?></label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="birthdate" data-errmsg="<?php echo __( 'Select your Birth date.');?>" type="text" class="datepicker user_bday" required>
                                        <label for="birthdate"><?php echo __( 'Birthdate' );?></label>
                                    </div>
                                <div class="step_footer">
                        <button class="waves-effect waves-light btn btn_primary bold second next" data-src="<?php echo $profile->src;?>" data-emailvalidation="<?php echo $config->emailValidation;?>">
                           <?php echo __( 'Next' );?> 
                           <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                              <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path>
                           </svg>
                        </button>
                     </div>
                            </form>
                            <!-- Step 3  -->
                            <form class="slider-form slider-three <?php if ($step != 3 && $profile->phone_verified != 1) { ?> hide <?php } ?> forth_slider" <?php if( $config->emailValidation == "0" ) {?>style="padding:0px;"<?php } ?>>
                                <?php if( $config->emailValidation == "1" && $profile->src == 'site' ) {?>

                                    <?php if ( $config->sms_or_email == "sms" ) {?>
                                    <!-- Mobile -->
                                    <div class="otp_head">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M16,18H7V4H16M11.5,22A1.5,1.5 0 0,1 10,20.5A1.5,1.5 0 0,1 11.5,19A1.5,1.5 0 0,1 13,20.5A1.5,1.5 0 0,1 11.5,22M15.5,1H7.5A2.5,2.5 0 0,0 5,3.5V20.5A2.5,2.5 0 0,0 7.5,23H15.5A2.5,2.5 0 0,0 18,20.5V3.5A2.5,2.5 0 0,0 15.5,1Z" /></svg>
                                        <p><?php echo __( 'Phone Verification Needed' );?></p>
                                        <div class="row">
                                            <div class="col s12 m2"></div>
                                            <div class="col s12 m8">
                                                <div class="input-field inline">
                                                    <input id="mobile_validate" type="text" style="width: 200px;" value="<?php echo $profile->phone_number;?>">
                                                </div>
                                                <button class="btn waves-effect waves-light" style="margin-left: -5px;" id="send_otp"><?php echo __( 'Send OTP' );?></button>
                                            </div>
                                            <div class="col s12 m2"></div>
                                        </div>
                                    </div>
                                    <div class="enter_otp">
                                        <p><?php echo __( 'Please enter the verification code sent to your Phone' );?></p>
                                        <div id="otp_outer">
                                            <div id="otp_inner">
                                                <input id="otp_check_phone" type="text" maxlength="4" value="" pattern="\d*" title="Field must be a number." onkeyup="if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'') } if($(this).val().length == 4){verify_sms_code(this);}" required />
                                                <a href="javascript:void(0);" onclick="resendCodeSms(this)"><?php echo __( 'Resend' );?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Mobile -->
                                    <?php } ?>
                                    <?php if ( $config->sms_or_email == "mail" ) {?>
                                    <!-- Email -->
                                    <div class="otp_head">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" /></svg>
                                        <p><?php echo __( 'Email Verification Needed' );?></p>
                                        <div class="row">
                                            <div class="col s12 m2"></div>
                                            <div class="col s12 m8">
                                                <div class="input-field inline">
                                                    <input id="email" type="email" value="<?php echo strtolower($profile->email);?>" data-email="<?php echo strtolower($profile->email);?>">
                                                </div>
                                                <button class="btn waves-effect waves-light" id="send_otp_email"><?php echo __( 'Send OTP' );?></button>
                                            </div>
                                            <div class="col s12 m2"></div>
                                        </div>
                                    </div>
                                    <div class="enter_otp_email">
                                        <p><?php echo __( 'Please enter the verification code sent to your Email' );?></p>
                                        <div id="otp_outer">
                                            <div id="otp_inner">
                                                <input id="otp_check_email" type="text" maxlength="4" value="" pattern="\d*" title="Field must be a number." onkeyup="if (/\D/g.test(this.value)){ this.value = this.value.replace(/\D/g,'') } if($(this).val().length == 4){verify_email_code(this);}" required/>
                                                <a href="javascript:void(0);" onclick="resendCode(this)"><?php echo __( 'Resend' );?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Email -->
                                    <?php } ?>
                                <?php }else{ ?>

                                            <div class="dt_p_head center pro_success">
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path></svg>
                                                <h2 class="light"><?php echo __( 'Congratulations!' );?></h2>
                                                <p class="bold"><?php echo __('You have successfully registered.');?></p>
                                            </div>

                                <?php } ?>
                                <div class="step_footer">
                                    <button class="waves-effect waves-light btn btn_primary bold reset" disabled><?php echo __( 'Finish' );?> <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"></path></svg></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Step One  -->

    <!-- Images Modal -->
    <div id="modal_imgs" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h6 class="bold"><span class="count_imgs"></span> <?php echo __( 'Images Uploaded' );?></h6>
            <p class="select_profile_image" style="display:none;"><?php echo __( 'Now, select any one image that you want to set as your Profile Picture.' );?></p>
            <div id="image_holder"></div>

            <div class="progress">
                <div class="determinate" style="width: 0%"></div >
            </div>

            <div id="status"></div>

        </div>
        <div class="modal-footer">
            <div id="modal_imgs_info"></div><button class="modal-close waves-effect waves-green btn-flat bold" disabled  data-selected=""><?php echo __( 'Apply' );?></button>
        </div>
    </div>
    <!-- End Images Modal -->
<?php if( $config->image_verification == 1 ){ ?>
<style>
    .slider_container.center .line .dot-move {
        left: 50%;
        animation: .3s anim 1;
    }
</style>
<?php }?>
<script type="text/javascript">
    function resendCodeSms(self) {
      let txt = $(self).text();
      $(self).text("Please wait..").attr('disabled', true);
      $.ajax({
          type: 'GET',
          url: window.ajax + 'useractions/send_verefication_sms',
          data: {phone: $('#mobile_validate').val()},
          success: function(data){
              if( data.status == 200 ){
                $(self).text(txt).attr('disabled', true);
                showResponseAlert('.steps_alerts','success',"<?php echo(__('confirmation_code_sent')); ?>",2000);
              }else{
                $(self).text(txt).attr('disabled', true);
                showResponseAlert('.steps_alerts','danger',"Cannot send verification sms right now, try again later.",2000);
              }
          },
          error: function (data) {
            $(self).text(txt).attr('disabled', true);
            showResponseAlert('.steps_alerts','danger',data.responseJSON.message,2000);
          },
      });
   }
    function resendCode(self) {
        let default_email = $('#email').attr('data-email');
        let email = $('#email').val();
        let txt = $(self).text();
        $(self).text("Please wait..").attr('disabled', true);
        let formData = new FormData();
        formData.append("email", email );
        $.ajax({
            type: 'POST',
            url: window.ajax + '/useractions/send_verefication_email',
            data: {"email":email},
            processData: true,
            success: function(data) {
                if( data.status == 200 ){
                    $(self).text(txt).attr('disabled', true);
                    showResponseAlert('.steps_alerts','success',"<?php echo(__('confirmation_code_sent')); ?>",2000);
                    // $('.enter_otp_email').fadeIn(100);
                    // $('.enter_otp_email').find('#otp_check_email').focus();
                }
            },
            error: function (data) {
                showResponseAlert('.steps_alerts','danger',data.responseJSON.message,2000);
                setTimeout(function(){
                    $(self).text(txt).attr('disabled', null);
                    // $('#email').attr('value',default_email);
                    // $('#email').val(default_email);
                },1000);
            }
        });
    }
    function GoToNextStep(class_name,start_up) {
      for (var i = 0; i < $('.slider_container form').length; i++) {
         if ($($('.slider_container form')[i]).hasClass(class_name) && i <= ($('.slider_container form').length - 1)) {
            $($('.slider_container form')[i]).addClass('hide');
            $($('.slider_container form')[i + 1]).removeClass('hide');
         }
      }
      $.get( window.ajax + 'profile/set_data', {start_up:start_up} );
   }
   $( document ).on( 'change', '#avatar_img', function(e){
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image_holder");
            var attach = [];
            image_holder.empty();
            if(countFiles > 4) {
                showResponseAlert('.steps_alerts','danger','Please select Four Images only.',2000);
            } else if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    var formData = new FormData();
                    for (var i = 0; i < countFiles; i++) {
                        attach[i] = i;
                        var reader = new FileReader();
                        reader.onload = function(e) {

                        };
                        reader.readAsDataURL($(this)[0].files[i]);
                        formData.append("avaters"+i, $(this)[0].files[i],$(this)[0].files[i].value );
                    }
                    $('.count_imgs').text(countFiles);
                    var bar = $('.determinate');
                    var progress = $('.progress');
                    progress.removeClass('hide');
                    var status = $('#status');
                    //$('#modal_imgs').modal('open');
                    $.ajax({
                        beforeSend: function() {
                            progress.css({'display':'block'});
                            progress.removeClass('hide');
                            bar.width('0%');
                            bar.show();
                        },
                        complete: function() {
                            //     progress.css({'display':'none'});
                            //  progress.addClass('hide');
                        },
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt){
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    status.html( percentComplete + "%");
                                    bar.width(percentComplete + '%');
                                    if (percentComplete === 100) {
                                        //          bar.hide();
                                        //          progress.hide();
                                        //                                     progress.addClass('hide');
                                        //          status.hide();
                                        $( '.select_profile_image' ).show();
                                    }else{
                                        progress.removeClass('hide');
                                    }
                                }
                            }, false);
                            return xhr;
                        },
                        url: window.ajax + 'profile/upload_avater',
                        type: "POST",
                        async: true,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        timeout: 60000,
                        dataType: false,
                        success: function(result) {
                            if( result.status == 200 ){
                                // $.each( result.files, function(i) {
                                //  $("<img />", {
                                //      "src": window.media_path + result.files[i],
                                //      "id": result.files[i],
                                //      "class": "thumb-image"
                                //  }).appendTo(image_holder);
                                // })
                                // image_holder.show();
                                var css = {
                                    'background-image': 'url('+ window.media_path + result.files[0] +')'
                                };
                                $( '.dt_selct_avatar .svg-empty' ).hide();
                                $( '.dt_selct_avatar' ).css(css);

                                progress.css({'display':'none'});
                                progress.addClass('hide');
                                bar.width('0%');
                                <?php if( $config->image_verification == 1){ ?>
                           init();
                          <?php } ?>

                                $( "#btn-upload-images" ).attr('disabled', false);
                                $( '#btn-upload-images' ).attr( 'data-selected', result.files[0] );
                            }
                        }
                    });
                } else {
                    showResponseAlert('.steps_alerts','danger','Please select only Images.',2000);
                }
            } else {
                showResponseAlert('.steps_alerts','danger',"This browser does not support FileReader.",2000);
            }
        });
$( document ).on( 'click', '#btn-upload-images', function(e){
            e.preventDefault();
            <?php if( $config->image_verification == 1){ ?>
            var snapshot = $( this ).attr( 'data-snapshot' );
            if (typeof snapshot !== typeof undefined && snapshot !== false) {
                if( snapshot == "true" ){
                    var thumb   = new File([base64_2_blob(window.image_data.dataUri)], "thumb.png", {type:"image/png"});
                    var formData = new FormData();
                    formData.append('thumb',thumb);
                    var data_image_ver = $(this).attr('data-image-verification');


                    $.ajax({
                        processData: false,
                        url: window.ajax + 'profile/set_snapshotdata',
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        contentType: false,
                      })
                      .done(function() {
                        GoToNextStep('second_slider',2)
                        if( data_image_ver == "1" ){
                            //window.location.href = window.location;
                        }else {
                            //$ctr.addClass("center slider-two-active").removeClass("full slider-one-active");
                        }
                      })
                      .fail(function() {
                        console.log("error");
                      })
                      .always(function() {
                        console.log("complete");
                      });

                    if( $(this).attr('data-image-verification') == "1" ){
                        GoToNextStep('second_slider',2)
                        //window.location.href = window.location;
                    }else {
                        //$ctr.addClass("center slider-two-active").removeClass("full slider-one-active");
                    }

                }
            }
        <?php } ?>

            var id = $( this ).attr( 'data-selected' );
            if( id === '' ){
                $( '#modal_imgs_info' ).html( 'Please, choose profile image.' );
                return false;
            }else{
                $( this ).attr('disabled', false);
            }
            $.get( window.ajax + 'profile/set_avater', { id:id } );
            //$.get( window.ajax + 'profile/set', { key:"start_up", value:"1" } );
            // var data = {
            //     'status': 3,
            //     'start_up': 1
            // };
            // $.get( window.ajax + 'profile/set_data', data );

            $('.header_user').find('img').attr( 'src', window.media_path + id );
            $('#modal_imgs').modal('close');
            $org_text = $(this).attr('data-defaultText');
            $(this).find('#nexttext').text("Saving...").delay(100).queue(function(){
                if( window.image_verification == 1 ){
                //    $ctr.addClass("center2 slider-img-active").removeClass("full slider-one-active");
                    $('.choose_photo').addClass('hide');
                    $('.slider-one').css({'padding':'0px'});
                    $('.webcam_photo_verification').removeClass('hide');
                }else{
                    GoToNextStep('second_slider',2);
                    $ctr.addClass("center slider-two-active").removeClass("full slider-one-active");
                }
            });
            $(this).find('#nexttext').text($org_text);

        });

    $( document ).on( 'click', '.second', function(e){
            var Height, HairColor, MobileNumber, Country, Gender, Birthdate,emailvalidation,src;
            emailvalidation = $( this ).attr( 'data-emailvalidation' );
            src = $( this ).attr( 'data-src' );
            Height = $( '#height' ).val();
            HairColor = $( '#hair' ).val();
            MobileNumber = $( '#mobile' ).val();
            country_code = $( '#country' ).find(":selected").attr('data-code');
            Country = $( '#country' ).find(":selected").val();
            Gender = $( '#gender' ).find(":selected").val();
            Birthdate = $( '#birthdate' ).val();
            var validation_mode = $( '#mobile' ).attr('data-validation-type');

            if( Height === null ){
                showResponseAlert('.steps_alerts','danger',$( '#height' ).attr('data-errmsg'),2000);
                $( '#height' ).focus();
                return false;
            }
            if( MobileNumber === ""){
                showResponseAlert('.steps_alerts','danger',$( '#mobile' ).attr('data-errmsg'),2000);
                $( '#mobile' ).focus();
                return false;
            }
            if ($( '#mobile' ).attr('data-p-verified') == 'no') {
                showResponseAlert('.steps_alerts','danger',`<?php echo(__('This Phone number is Already exist.')); ?>`,2000);
                $( '#mobile' ).focus();
                return false;
            }
            if( Country === "" ){
                showResponseAlert('.steps_alerts','danger',$( '#country' ).attr('data-errmsg'),2000);
                $( '#country' ).focus();
                return false;
            }
            if( Gender === "" ){
                showResponseAlert('.steps_alerts','danger',$( '#gender' ).attr('data-errmsg'),2000);
                $( '#gender' ).focus();
                return false;
            }
            if( Birthdate === "" ){
                showResponseAlert('.steps_alerts','danger',$( '#birthdate' ).attr('data-errmsg'),2000);
                $( '#birthdate' ).focus();
                return false;
            }

            var data = {
                 'height': Height,
                 'hair_color': HairColor,
                 'phone_number': MobileNumber,
                 'country': Country,
                 'gender': Gender,
                 'birthday': Birthdate,
                 'phone_verified': "<?php echo($config->emailValidation == "0" ? '1' : '0') ?>",
            };
            $.get( window.ajax + 'profile/set_data', data );

            $( '#country_arecode' ).html( "+" + $( '#country' ).find(":selected").attr('data-code') );
            $( '#areacode' ).attr( 'value', "+" + $( '#country' ).find(":selected").attr('data-code') );
            $( '#mobile_validate').attr( 'value', '+' + $( '#country' ).find(":selected").attr('data-code') + MobileNumber);

            $(this).text("Saving...").delay(100).queue(function(){
                //$ctr.addClass("full slider-three-active").removeClass("center slider-two-active slider-one-active");

                <?php if (($config->verification_on_signup == 1 || $config->image_verification == 1 || $config->pending_verification == 1) && $config->emailValidation != 1) { ?>
                  $('.dt_signup_steps').html(`<h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15,3H12V6H8V3H5A2,2 0 0,0 3,5V21A2,2 0 0,0 5,23H15A2,2 0 0,0 17,21V5A2,2 0 0,0 15,3M10,8A2,2 0 0,1 12,10A2,2 0 0,1 10,12A2,2 0 0,1 8,10A2,2 0 0,1 10,8M14,16H6V15C6,13.67 8.67,13 10,13C11.33,13 14,13.67 14,15V16M11,5H9V1H11V5M14,19H6V18H14V19M10,21H6V20H10V21M19,12V7H21V12H19M19,16V14H21V16H19Z" /></svg><?php echo __('Your account wait admin photo verification. Please try again later.');?></h5>`);
                  GoToNextStep('second_slider',3);
               <?php } ?>
               <?php if ($config->verification_on_signup != 1 && $config->image_verification != 1 && $config->pending_verification != 1 && $config->emailValidation != 1) { ?>
                  $('.dt_signup_steps').html(`<div class="dt_p_head center pro_success">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                           <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle>
                           <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                        </svg>
                        <h2 class="light"><?php echo __( 'Congratulations!' );?></h2>
                        <p class="bold"><?php echo __('You have successfully registered.');?></p>
                     </div>`);
                  setTimeout(function(){
                    window.location = window.site_url;
                  }, 1000);
               <?php } ?>

               <?php if ($config->emailValidation == 1) { ?>
                  GoToNextStep('third_slider',3);
               <?php } ?>
            });
            e.preventDefault();
        });
function verify_email_code( thisx ){
    var vl = $(thisx);
    $.get( window.ajax + 'useractions/get_email_verification_code', function(data, status){
        setTimeout(() => {
            $('#otp_check_email').removeAttr('disabled');
        },1000);
        if( data.status == 200 ){
            if( vl.val() == data.code ){
                var data = {
                    'start_up': '3',
                    'phone_verified' : '1'
                };

                $.get( window.ajax + 'profile/set_data', data );
               <?php if ($config->verification_on_signup == 1 || $config->image_verification == 1 || $config->pending_verification == 1) { ?>
                  $('.dt_signup_steps').html(`<h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15,3H12V6H8V3H5A2,2 0 0,0 3,5V21A2,2 0 0,0 5,23H15A2,2 0 0,0 17,21V5A2,2 0 0,0 15,3M10,8A2,2 0 0,1 12,10A2,2 0 0,1 10,12A2,2 0 0,1 8,10A2,2 0 0,1 10,8M14,16H6V15C6,13.67 8.67,13 10,13C11.33,13 14,13.67 14,15V16M11,5H9V1H11V5M14,19H6V18H14V19M10,21H6V20H10V21M19,12V7H21V12H19M19,16V14H21V16H19Z" /></svg><?php echo __('Your account wait admin photo verification. Please try again later.');?></h5>`);
               <?php }else{ ?>
                  $('.dt_signup_steps').html(`<div class="dt_p_head center pro_success">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                           <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle>
                           <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                        </svg>
                        <h2 class="light"><?php echo __( 'Congratulations!' );?></h2>
                        <p class="bold"><?php echo __('You have successfully registered.');?></p>
                     </div>`);
                  setTimeout(function(){
                    window.location = window.site_url;
                  }, 1000);
               <?php } ?>

            }else{
                showResponseAlert('.steps_alerts','danger',"Wrong verification email code, try again later.",2000);
                vl.focus();
            }
        }else{
            showResponseAlert('.steps_alerts','danger',"Wrong verification email code, try again later.",2000);
        }
    });
}

function verify_sms_code( thisx ){
    var vl = $(thisx);
    $.get( window.ajax + 'useractions/get_sms_verification_code', function(data, status){
        setTimeout(() => {
            $('#otp_check_phone').removeAttr('disabled');
        },1000);
        if( data.status == 200 ){
            if( vl.val() == data.code ){

                var data = {
                    'start_up': '3',
                    'phone_verified' : '1'
                };

                $.get( window.ajax + 'profile/set_data', data );
               <?php if ($config->verification_on_signup == 1 || $config->image_verification == 1 || $config->pending_verification == 1) { ?>
                  $('.dt_signup_steps').html(`<h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15,3H12V6H8V3H5A2,2 0 0,0 3,5V21A2,2 0 0,0 5,23H15A2,2 0 0,0 17,21V5A2,2 0 0,0 15,3M10,8A2,2 0 0,1 12,10A2,2 0 0,1 10,12A2,2 0 0,1 8,10A2,2 0 0,1 10,8M14,16H6V15C6,13.67 8.67,13 10,13C11.33,13 14,13.67 14,15V16M11,5H9V1H11V5M14,19H6V18H14V19M10,21H6V20H10V21M19,12V7H21V12H19M19,16V14H21V16H19Z" /></svg><?php echo __('Your account wait admin photo verification. Please try again later.');?></h5>`);
               <?php }else{ ?>
                  $('.dt_signup_steps').html(`<div class="dt_p_head center pro_success">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                           <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle>
                           <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                        </svg>
                        <h2 class="light"><?php echo __( 'Congratulations!' );?></h2>
                        <p class="bold"><?php echo __('You have successfully registered.');?></p>
                     </div>`);
                  setTimeout(function(){
                    window.location = window.site_url;
                  }, 1000);
               <?php } ?>

            }else{
                showResponseAlert('.steps_alerts','danger',"Wrong verification sms code, try again later.",2000);
                vl.val('');
                vl.focus();
            }
        }else{
            showResponseAlert('.steps_alerts','danger',"Wrong verification sms code, try again later.",2000);
        }
    });
}
$( document ).on( 'input', '#otp_check_email', function(e){
            if($(this).val().length == 4 && !$('#otp_check_email').prop('disabled')) {
                $('#otp_check_email').attr('disabled',true);
                verify_email_code(this);
            } else {}
        });
        $( document ).on( 'paste', '#otp_check_email', function(e){
            var pastedData = e.originalEvent.clipboardData.getData('text');
            if(pastedData.length === 4) {
                var vl = $(this);
                vl.val(pastedData);
                verify_email_code(this);
            } else {}
            e.preventDefault();
        });
        $( document ).on( 'input', '#otp_check_phone', function(e){
            if($(this).val().length == 4 && !$('#otp_check_phone').prop('disabled')) {
                $('#otp_check_phone').attr('disabled',true);
                verify_sms_code(this);
            } else {}
        });
        $( document ).on( 'paste', '#otp_check_phone', function(e){
            var pastedData = e.originalEvent.clipboardData.getData('text');
            if(pastedData.length === 4) {
                var vl = $(this);
                vl.val(pastedData);
                $.get( window.ajax + 'useractions/get_sms_verification_code', function(data, status){
                    if( data.status == 200 ){
                        if( vl.val() == data.code ){
                            var data = {
                    'start_up': '3',
                    'phone_verified' : '1'
                };

                $.get( window.ajax + 'profile/set_data', data );
               <?php if ($config->verification_on_signup == 1 || $config->image_verification == 1 || $config->pending_verification == 1) { ?>
                  $('.dt_signup_steps').html(`<h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15,3H12V6H8V3H5A2,2 0 0,0 3,5V21A2,2 0 0,0 5,23H15A2,2 0 0,0 17,21V5A2,2 0 0,0 15,3M10,8A2,2 0 0,1 12,10A2,2 0 0,1 10,12A2,2 0 0,1 8,10A2,2 0 0,1 10,8M14,16H6V15C6,13.67 8.67,13 10,13C11.33,13 14,13.67 14,15V16M11,5H9V1H11V5M14,19H6V18H14V19M10,21H6V20H10V21M19,12V7H21V12H19M19,16V14H21V16H19Z" /></svg><?php echo __('Your account wait admin photo verification. Please try again later.');?></h5>`);
               <?php }else{ ?>
                  $('.dt_signup_steps').html(`<div class="dt_p_head center pro_success">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                           <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle>
                           <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                        </svg>
                        <h2 class="light"><?php echo __( 'Congratulations!' );?></h2>
                        <p class="bold"><?php echo __('You have successfully registered.');?></p>
                     </div>`);
                  setTimeout(function(){
                    window.location = window.site_url;
                  }, 1000);
               <?php } ?>
                        }else{
                            showResponseAlert('.steps_alerts','danger',"Wrong verification sms code, try again later.",2000);
                            vl.val('');
                            vl.focus();
                        }
                    }else{
                        showResponseAlert('.steps_alerts','danger',"Wrong verification sms code, try again later.",2000);
                    }
                });
            } else {}
            e.preventDefault();
        });
</script>