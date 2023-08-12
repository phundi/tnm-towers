<div class="modal fade" id="re-talking-modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content dt_call_rec_ing">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo __('Audio call');?></h4>
            </div>
            <div class="modal-body">
				<div class="dt_call_rec_ing_detal">
                    <img src="<?php echo $wo['incall']['in_call_user']->avater->avater;?>" alt="" class="hidden-mobile-image">
                    <p><?php echo __('talking with');?><b> <?php echo $wo['incall']['in_call_user']->fullname.$wo['incall']['in_call_user']->pro_icon;?></b></p>
                </div>
                <div id="me"></div>
				<div id="remote-media">
                    <h3><?php echo __('Please wait..');?></h3>
                </div>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-flat red darken-1 waves-effect decline-call" onclick="Wo_CloseCall('<?php echo $wo['incall']['id'];?>');" title="<?php echo __('Cancel');?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,9C10.4,9 8.85,9.25 7.4,9.72V12.82C7.4,13.22 7.17,13.56 6.84,13.72C5.86,14.21 4.97,14.84 4.17,15.57C4,15.75 3.75,15.86 3.5,15.86C3.2,15.86 2.95,15.74 2.77,15.56L0.29,13.08C0.11,12.9 0,12.65 0,12.38C0,12.1 0.11,11.85 0.29,11.67C3.34,8.77 7.46,7 12,7C16.54,7 20.66,8.77 23.71,11.67C23.89,11.85 24,12.1 24,12.38C24,12.65 23.89,12.9 23.71,13.08L21.23,15.56C21.05,15.74 20.8,15.86 20.5,15.86C20.25,15.86 20,15.75 19.82,15.57C19.03,14.84 18.14,14.21 17.16,13.72C16.83,13.56 16.6,13.22 16.6,12.82V9.72C15.15,9.25 13.6,9 12,9Z" /></svg></button>
            </div>
        </div>
    </div>
</div>
<?php if ($config->agora_chat_call == 1) { ?>
  <script type="text/javascript">
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    if (!navigator.getUserMedia) {
      $('#remote-media h3').text('Sorry, WebRTC is not available in your browser.');
    }
    var agoraAppId = "<?php echo $config->agora_chat_app_id;?>";
    var token = "<?php echo $wo['incall']['access_token'];?>";
    var channelName = "<?php echo $wo['incall']['room'];?>";
    var uid = <?php echo auth()->id;?>;

    // Handle errors.
    let handleError = function(err){
            console.log("Error: ", err);
    };
    // video profile settings
    var cameraVideoProfile = '480p_4'; // 640 × 480 @ 30fps  & 750kbs
    var screenVideoProfile = '480p_2'; // 640 × 480 @ 30fps
    // create client instances for camera (client) and screen share (screenClient)
    var client = AgoraRTC.createClient({mode: 'rtc', codec: 'vp8'}); 
    function initClientAndJoinChannel(agoraAppId, token, channelName, uid) {
      // init Agora SDK
      client.init(agoraAppId, function () {
        console.log("AgoraRTC client initialized");
        joinChannel(channelName, uid, token); // join channel upon successfull init
      }, function (err) {
        console.log("[ERROR] : AgoraRTC client init failed", err);
      });
    }
    // join a channel
    function joinChannel(channelName, uid, token) {
      client.join(token, channelName, uid, function(uid) {
          console.log("User " + uid + " join channel successfully");
          createCameraStream(uid);
          //localStreams.camera.id = uid; // keep track of the stream uid 
      }, function(err) {
          console.log("[ERROR] : join channel failed", err);
      });
    }

    // video streams for channel
    function createCameraStream(uid) {
      var localStream = AgoraRTC.createStream({
        streamID: uid,
        audio: true,
        video: false,
        screen: false
      });
      localStream.setVideoProfile(cameraVideoProfile);
      localStream.init(function() {
        console.log("getUserMedia successfully");
        // TODO: add check for other streams. play local stream full size if alone in channel
        localStream.play('me'); // play the given stream within the local-video div

        // publish local stream
        client.publish(localStream, function (err) {
          console.log("[ERROR] : publish local stream error: " + err);
        });
      
        //enableUiControls(localStream); // move after testing
        //localStreams.camera.stream = localStream; // keep track of the camera stream for later
      }, function (err) {
        console.log("[ERROR] : getUserMedia failed", err);
      });
    }
    client.on('stream-added', function (evt) {
      client.subscribe(evt.stream,  function (err) {
          console.log("[ERROR] : subscribe stream failed", err);
        });
    });

    client.on('stream-subscribed', function (evt) {
      var remoteStream = evt.stream;
      var remoteId = remoteStream.getId();
      $('#remote-media').empty();
      remoteStream.play('remote-media');
    });
    function leaveChannel() {

      client.leave(function() {
        // console.log("client leaves channel");
        // localStreams.camera.stream.stop() // stop the camera stream playback
        // client.unpublish(localStreams.camera.stream); // unpublish the camera stream
        // localStreams.camera.stream.close(); // clean up and close the camera stream
        $("#remote-media").empty() // clean up the remote feeds
        location.href = "<?php echo($config->uri) ?>";
      }, function(err) {
        console.log("client leave failed ", err); //error handling
      });
    }
    // remove the remote-container when a user leaves the channel
    client.on("peer-leave", function(evt) {

      location.href = "<?php echo($config->uri) ?>";
    });

    // use tokens for added security
    function generateToken() {
      return null; // TODO: add a token generation
    }
    $(document).on('click', '.decline-call', function(e) {
        leaveChannel(); 
        location.href = "<?php echo($config->uri) ?>";
    });
    
    initClientAndJoinChannel(agoraAppId, token, channelName, uid);
  </script>
<?php }else{ ?>
<script>
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    if (!navigator.getUserMedia) {
        $('#remote-media h3').text('Sorry, WebRTC is not available in your browser.');
    }


    Twilio.Video.connect('<?php echo $wo['incall']['access_token'];?>', { name: '<?php echo $wo['incall']['room'];?>', audio: true, video: false }).then(room => {
        //console.log('Connected to Room "%s"', room.name);

        room.participants.forEach(participantConnected);
        room.on('participantConnected', participantConnected);

        room.on('participantDisconnected', participantDisconnected);
        room.once('disconnected', error => room.participants.forEach(participantDisconnected));
        $(document).on('click', 'a[data-ajax]', function(e) {
            room.disconnect();
        });
        $(document).on('click', '.decline-call', function(e) {
            room.disconnect();
        });
    });

    function participantConnected(participant) {
        //console.log('Participant "%s" connected', participant.identity);

        const div = document.createElement('div');
        div.id = participant.sid;
        //div.innerText = participant.identity;

        participant.on('trackAdded', track => trackAdded(div, track));
        participant.tracks.forEach(track => trackAdded(div, track));
        participant.on('trackRemoved', trackRemoved);

        $('#remote-media').html(div);
    }

    function participantDisconnected(participant) {
        //console.log('Participant "%s" disconnected', participant.identity);

        participant.tracks.forEach(trackRemoved);
        document.getElementById(participant.sid).remove();
        alert("Connection has been lost.");

        window.location.href = window.site_url;
    }

    function trackAdded(div, track) {
        div.appendChild(track.attach());
    }

    function trackRemoved(track) {
        track.detach().forEach(element => element.remove());
    }
</script>
<?php } ?>