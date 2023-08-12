<!--chat-message-form.php-->
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