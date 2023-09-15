<?php
    $OnlineUsers = 0;
?>
<input type="hidden" id="time" name="time" value="0">
<input type="hidden" id="last_decline_message" name="last_decline_message" value="">
<input type="hidden" id="timestamp" name="timestamp" value="0">
<input type="hidden" id="rts_vsdhjh98" name="rts_vsdhjh98" value="0">
<input type="hidden" id="vxd" name="vx" value="">
<input type="hidden" id="dfgetevxd" name="vbnrx" value="">
<!-- Messages  -->
<div id="message_box" class="hide dt_msg_box open_list">
    <div class="modal-content">
        <div class="msg_list"> <!-- Message List  -->
            <div class="msg_header valign-wrapper">
                <h2>
                    <?php echo __( 'Messenger' );?>
                    <?php
                    if( $OnlineUsers > 0 ){
                        echo '<div class="chat_count">'.$OnlineUsers.'</div>';
                    }else{
                        echo '<div class="chat_count hide">0</div>';
                    }
                    ?>
                </h2>
                <div class="msg_toolbar">
                    <button type="button" class="btn btn-flat close waves-effect modal-close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
                    </button>
                </div>
            </div>
            <div class="msg_container">
				<div class="valign-wrapper m_filters">
					<div class="switch">
						<p><?php echo __( 'Active Status' );?></p>
						<label>
							<b><?php echo __( 'Offline' );?></b>
							<input type="checkbox" id="chat_go_online" <?php if( $profile->online == 1 ){ echo 'checked'; }?>>
							<span class="lever"></span>
							<b class="last"><?php echo __( 'Online' );?></b>
						</label>
					</div>
					<div>
						<button type="button" class="btn btn-flat mark_read waves-effect" onclick="remove_conversationlist_active();" data-ajax-post="/chat/mark_all_messages_as_read" data-ajax-params="">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#CC42BD" d="M11.602 13.76l1.412 1.412 8.466-8.466 1.414 1.414-9.88 9.88-6.364-6.364 1.414-1.414 2.125 2.125 1.413 1.412zm.002-2.828l4.952-4.953 1.41 1.41-4.952 4.953-1.41-1.41zm-2.827 5.655L7.364 18 1 11.636l1.414-1.414 1.413 1.413-.001.001 4.951 4.951z" /></svg> <span class="hide-on-small-only"><?php echo __( 'Mark all as read' );?></span>
						</button>
					</div>
				</div>
				
                <div class="m_search">
					<div class="dt_srch_msg_progress hide" id="search-loader">
						<div class="progress">
							<div class="indeterminate"></div>
						</div>
					</div>
                    <div class="search_input">
                        <input type="search" class="browser-default" id="chat_search" name="search" placeholder="<?php echo __( 'Search for Chats' );?>" autofocus />
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11 2c4.968 0 9 4.032 9 9s-4.032 9-9 9-9-4.032-9-9 4.032-9 9-9zm0 16c3.867 0 7-3.133 7-7 0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7zm8.485.071l2.829 2.828-1.415 1.415-2.828-2.829 1.414-1.414z" /></svg>
						<div class="srch_filter hide" id="reset_chat_search">
							<button type="button" id="btn_reset_chat_search" class="btn btn-flat mark_read waves-effect" data-ajax-post="/chat/mark_all_messages_as_read" data-ajax-params="" title="<?php echo __( 'Reset' );?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z" /></svg>
							</button>
						</div>
                    </div>
                    <div class="chat_filter switch">
                        <label>
                            <input type="checkbox" id="chat_search_online">
                            <span class="lever"></span>
							<b><?php echo __( 'All' );?></b>
                            <b class="last"><?php echo __( 'Online' );?></b>
                        </label>
                    </div>
                </div>
				
				<?php if($config->message_request_system == 'on'){ ?>
                    <button type="button" class="btn btn-flat msg_requests" data-ajax-post="/chat/get_messages_requests" data-ajax-params="" data-accepted="requests" data-ajax-callback="callback_msg_request" data-text-msg-request='<span class="active"><?php echo __( 'All conversations' );?></span><span><b id="requests_count"></b> <?php echo __( 'Message requests' );?></span>' data-text-all-conversation='<span><?php echo __( 'All conversations' );?></span><span class="active"><b id="requests_count"></b> <?php echo __( 'Message requests' );?></span>'>
						<span class="active"><?php echo __( 'All conversations' );?></span>
						<span><b id="requests_count"></b> <?php echo __( 'Message requests' );?></span>
                    </button>
                <?php }?>
				
                <div class="m_body">
                    <div class="m_body_content">
                        <ul class="m_conversation" id="m_conversation_search"></ul>
                        <ul class="m_conversation" id="m_conversation"></ul>
                    </div>
                </div>
            </div>
        </div> <!-- End Message List  -->

        <div class="msg_chat"> <!-- Message Chat  -->
            <div class="chat_conversations">
                <div class="chat_header valign-wrapper">
                    <div class="chat_navigation">
                        <button type="button" class="btn btn-flat back waves-effect" id="navigateBack">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"></path></svg>
                        </button>
                    </div>
                    <div class="chat_participant">
                        <div class="c_avatar">
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACwAAAAAAQABAAA=" alt="User">
                        </div>
                        <div class="c_name">
                            <a href="" target="_blank" id="chatfromuser"><span class="name"></span></a>
                            <span class="time ajax-time last_seen" title=""></span>
                        </div>
                    </div>
                    <div class="chat_toolbar">
						<div>
						<button type="button" class="dropdown-trigger btn btn-flat close waves-effect" data-target="cht_more_opts_dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z" /></svg></button>
						<ul id="cht_more_opts_dropdown" class="dropdown-content">
							<li><a href="javascript:void(0);" id="deletechatconversations"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"></path></svg> <?php echo __('Delete chat');?></a></li>

                            <?php
                                $video_link = false;
                                $audio_link = false;

                                //$config->pro_system            ( 0,1 ) -> check if Pro system enabled
                                //$config->avcall_pro            ( 0,1 ) -> check if Video & Audio Call for pro users only
                                //$config->video_chat            ( 0,1 ) -> check if Video Call enabled
                                //$config->audio_chat            ( 0,1 ) -> check if Audio Call enabled
                                //$profile->is_pro
                                if ((int)$config->twilio_chat_call == 1 || (int)$config->agora_chat_call == 1) {
                                    if ((int)$config->pro_system == 1) {
                                        // pro system enabled
                                        if ((int)$config->avcall_pro == 1) {
                                            // Video & Audio Call for pro users only enabled
                                            if( $profile->is_pro == 1 ) {
                                                // if user is pro
                                                if ((int)$config->video_chat == 1) {
                                                    //Video Call enabled
                                                    $video_link = true;
                                                }
                                                if ((int)$config->audio_chat == 1) {
                                                    //Audio Call enabled
                                                    $audio_link = true;
                                                }
                                            }
                                        }else{
                                            // Video & Audio Call for pro users only disabled
                                            if ((int)$config->video_chat == 1) {
                                                //Video Call enabled
                                                $video_link = true;
                                            }
                                            if ((int)$config->audio_chat == 1) {
                                                //Audio Call enabled
                                                $audio_link = true;
                                            }
                                        }
                                    }else{
                                        // pro system disabled
                                        if ((int)$config->video_chat == 1) {
                                            //Video Call enabled
                                            $video_link = true;
                                        }
                                        if ((int)$config->audio_chat == 1) {
                                            //Audio Call enabled
                                            $audio_link = true;
                                        }
                                    }
                                }
                                    
                            ?>

                            <?php if ($video_link == true) { ?>
                                <li><a href="javascript:void(0);" id="video_call" onclick="Wo_GenerateVideoCall(<?php echo auth()->id;?>)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17,10.5V7A1,1 0 0,0 16,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16A1,1 0 0,0 17,17V13.5L21,17.5V6.5L17,10.5Z"></path></svg> <?php echo __('Video call');?></a></li>
                            <?php } ?>
                            <?php if ($audio_link == true) { ?>
                                <li><a href="javascript:void(0);" id="audio_call" onclick="Wo_GenerateVoiceCall(<?php echo auth()->id;?>)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"></path></svg> <?php echo __('Audio call');?></a></li>
                            <?php } ?>

						</ul>
						</div>
                        <button type="button" class="btn btn-flat close waves-effect modal-close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"></path></svg>
                        </button>
                    </div>
                </div>

                <a href="javascript:void(0);" id="btn_load_prev_chat_message" data-lang-nomore="<?php echo __('No more messages to show.');?>" class="btn waves-effect dt_chat_lod_more hide"><?php echo __('Load more...');?></a>

                <div class="chat_container">
                    <div class="chat_body">
                        <div class="chat_body_content"></div>
                    </div>
                    <div class="chat_foot">
                        <div class="chat_f_text">
                            <div class="hide dt_acc_dec_msg" id="chat_request_btns">
                                <button type="button" data-route1="<?php echo '@'.$profile->username;?>" data-route2="chat_request" id="btn_chat_accept_message" class="btn btn-flat waves-effect acc_msg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" /></svg> <?php echo __('Accept');?>
                                </button>
                                <button type="button" data-route1="<?php echo '@'.$profile->username;?>" data-route2="chat_request" id="btn_chat_decline_message" class="btn btn-flat waves-effect dec_msg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg> <?php echo __('Decline');?>
                                </button>
                            </div>
							<div class="chat_message_upload_media_imgprogress hide">
								<div class="progress">
									<div class="chat_message_upload_media_imgdeterminate determinate" style="width: 0%;"></div>
								</div>
							</div>
                            <form method="POST" action="/chat/send_message" class="valign-wrapper" id="chat_message_form">
                                <input type="hidden" name="to" value="" id="to_message"/>
								<div class="chat_f_attach">
									<span id="chat_message_upload_media" style="cursor: pointer;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26"> <g id="Group_2960" data-name="Group 2960" transform="translate(-0.446 -0.01)"> <circle id="Ellipse_352" data-name="Ellipse 352" cx="13" cy="13" r="13" transform="translate(0.446 0.01)" fill="none"/> <path id="Icon_awesome-images" data-name="Icon awesome-images" d="M16.174,15.189v.539a1.617,1.617,0,0,1-1.617,1.617H1.617A1.617,1.617,0,0,1,0,15.728V7.1A1.617,1.617,0,0,1,1.617,5.485h.539v7.009a2.7,2.7,0,0,0,2.7,2.7Zm3.235-2.7V3.867A1.617,1.617,0,0,0,17.791,2.25H4.852A1.617,1.617,0,0,0,3.235,3.867v8.626a1.617,1.617,0,0,0,1.617,1.617H17.791A1.617,1.617,0,0,0,19.408,12.493ZM8.626,5.485A1.617,1.617,0,1,1,7.009,3.867,1.617,1.617,0,0,1,8.626,5.485ZM5.391,10.337,7.262,8.466a.4.4,0,0,1,.572,0L9.165,9.8l4.566-4.566a.4.4,0,0,1,.572,0L17.252,8.18v3.774H5.391Z" transform="translate(3.741 3.101)" fill="currentColor"/> </g> </svg>
                                    </span>
                                    <span id="chat_message_gify">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26"> <g id="Group_2961" data-name="Group 2961" transform="translate(-0.336 -0.01)"> <circle id="Ellipse_352" data-name="Ellipse 352" cx="13" cy="13" r="13" transform="translate(0.336 0.01)" fill="none"/> <path id="Subtraction_18" data-name="Subtraction 18" d="M4156.957-248.087h-15.728a.909.909,0,0,1-.892-.9v-15.1a.9.9,0,0,1,.9-.9h12.107l4.5,4.554v11.429A.908.908,0,0,1,4156.957-248.087Zm-11.631-11.327a2.906,2.906,0,0,0-2.255.944,3.619,3.619,0,0,0-.853,2.51,3.415,3.415,0,0,0,.844,2.44,2.693,2.693,0,0,0,2.078.888,2.43,2.43,0,0,0,1.3-.327,3.115,3.115,0,0,0,.692-.645l.135.806h.883v-3.461h-2.657v1.077h1.475a1.689,1.689,0,0,1-.528,1.019,1.582,1.582,0,0,1-1.1.389,1.759,1.759,0,0,1-1.22-.494,2.215,2.215,0,0,1-.537-1.681,2.7,2.7,0,0,1,.5-1.767,1.591,1.591,0,0,1,1.266-.587,1.792,1.792,0,0,1,.731.145,1.263,1.263,0,0,1,.741.922h1.312a2.343,2.343,0,0,0-.846-1.555A2.939,2.939,0,0,0,4145.326-259.414Zm6.557.175h0v6.44h1.324v-2.7h2.827v-1.121h-2.827v-1.483h3.226v-1.133Zm-2.571-.008h0v6.449h1.325v-6.449Z" transform="translate(-4135.256 270.048)" fill="currentColor"/> </g> </svg>
                                    </span>
									<span id="chat_message_upload_stiker">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5.5,2C3.56,2 2,3.56 2,5.5V18.5C2,20.44 3.56,22 5.5,22H16L22,16V5.5C22,3.56 20.44,2 18.5,2H5.5M5.75,4H18.25A1.75,1.75 0 0,1 20,5.75V15H18.5C16.56,15 15,16.56 15,18.5V20H5.75A1.75,1.75 0 0,1 4,18.25V5.75A1.75,1.75 0 0,1 5.75,4M14.44,6.77C14.28,6.77 14.12,6.79 13.97,6.83C13.03,7.09 12.5,8.05 12.74,9C12.79,9.15 12.86,9.3 12.95,9.44L16.18,8.56C16.18,8.39 16.16,8.22 16.12,8.05C15.91,7.3 15.22,6.77 14.44,6.77M8.17,8.5C8,8.5 7.85,8.5 7.7,8.55C6.77,8.81 6.22,9.77 6.47,10.7C6.5,10.86 6.59,11 6.68,11.16L9.91,10.28C9.91,10.11 9.89,9.94 9.85,9.78C9.64,9 8.95,8.5 8.17,8.5M16.72,11.26L7.59,13.77C8.91,15.3 11,15.94 12.95,15.41C14.9,14.87 16.36,13.25 16.72,11.26Z" /></svg>
                                    </span>
								</div>
                                <div class="chat_f_textarea">
                                    <div class="chat_f_write">
                                        <textarea placeholder="<?php echo __('Type a message');?>" id="dt_emoji" name="text" class="hide"></textarea>
                                    </div>
                                </div>
                                <input type="file" id="chat_message_upload_media_file" class="hide" accept="image/x-png, image/gif, image/jpeg" name="avatar">
                                <div class="chat_f_send">
                                    <button type="button" id="btn_chat_f_send" class="btn-floating btn-large waves-effect waves-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2,21L23,12L2,3V10L17,12L2,14V21Z" /></svg>
                                    </button>
                                    <div class="lds-facebook hide"><div></div><div></div><div></div></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End Message Chat  -->
    </div>
</div>
<!-- End Messages  -->

<!-- Stickers -->
<div id="stiker_box" class="hide bottom-sheet">
    <div class="modal-content">
        <h5><div><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M5.5,2C3.56,2 2,3.56 2,5.5V18.5C2,20.44 3.56,22 5.5,22H16L22,16V5.5C22,3.56 20.44,2 18.5,2H5.5M5.75,4H18.25A1.75,1.75 0 0,1 20,5.75V15H18.5C16.56,15 15,16.56 15,18.5V20H5.75A1.75,1.75 0 0,1 4,18.25V5.75A1.75,1.75 0 0,1 5.75,4M14.44,6.77C14.28,6.77 14.12,6.79 13.97,6.83C13.03,7.09 12.5,8.05 12.74,9C12.79,9.15 12.86,9.3 12.95,9.44L16.18,8.56C16.18,8.39 16.16,8.22 16.12,8.05C15.91,7.3 15.22,6.77 14.44,6.77M8.17,8.5C8,8.5 7.85,8.5 7.7,8.55C6.77,8.81 6.22,9.77 6.47,10.7C6.5,10.86 6.59,11 6.68,11.16L9.91,10.28C9.91,10.11 9.89,9.94 9.85,9.78C9.64,9 8.95,8.5 8.17,8.5M16.72,11.26L7.59,13.77C8.91,15.3 11,15.94 12.95,15.41C14.9,14.87 16.36,13.25 16.72,11.26Z"></path></svg></div> <?php echo __('Stickers');?></h5>
		<div class="stiker_imgprogress hide">
			<div class="progress">
				<div class="stiker_imgdeterminate determinate" style="width: 0%"></div >
			</div>
		</div>
        <div id="stikerlist"></div>
    </div>
</div>

<!-- Gifybox -->
<div id="gify_box" class="hide bottom-sheet">
    <div class="modal-content">
        <h5>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M5.5,2C3.56,2 2,3.56 2,5.5V18.5C2,20.44 3.56,22 5.5,22H16L22,16V5.5C22,3.56 20.44,2 18.5,2H5.5M5.75,4H18.25A1.75,1.75 0 0,1 20,5.75V15H18.5C16.56,15 15,16.56 15,18.5V20H5.75A1.75,1.75 0 0,1 4,18.25V5.75A1.75,1.75 0 0,1 5.75,4M14.44,6.77C14.28,6.77 14.12,6.79 13.97,6.83C13.03,7.09 12.5,8.05 12.74,9C12.79,9.15 12.86,9.3 12.95,9.44L16.18,8.56C16.18,8.39 16.16,8.22 16.12,8.05C15.91,7.3 15.22,6.77 14.44,6.77M8.17,8.5C8,8.5 7.85,8.5 7.7,8.55C6.77,8.81 6.22,9.77 6.47,10.7C6.5,10.86 6.59,11 6.68,11.16L9.91,10.28C9.91,10.11 9.89,9.94 9.85,9.78C9.64,9 8.95,8.5 8.17,8.5M16.72,11.26L7.59,13.77C8.91,15.3 11,15.94 12.95,15.41C14.9,14.87 16.36,13.25 16.72,11.26Z"></path>
                </svg>
            </div> <?php echo __('Send Gif');?>
            <input type="text" id="gify_search" name="gify_search" style="width: 70%;float: right;display: block;text-align: left;margin-left: 50px;" placeholder="<?php echo __('Search GIFs');?>">
            <button type="button" id="reload_gifs" class="btn-floating btn-large waves-effect waves-light green" style="width: 47px;height: 35px;margin: 0 auto;    border-radius: 7%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 20 60"><path fill="currentColor" d="M2,21L23,12L2,3V10L17,12L2,14V21Z"></path></svg>
            </button>
        </h5>
        <div class="stiker_imgprogress hide">
            <div class="progress">
                <div class="stiker_imgdeterminate determinate" style="width: 0%"></div >
            </div>
        </div>
        <hr>
        <div id="gifylist"></div>
    </div>
</div>
