<style>
footer.page_footer {display: none}
</style>

<div class="video-con">
    <?php if ($config->agora_chat_call == 1) { ?>
    <div id="full-screen-video"></div>
    <div style="position: absolute;top: 0px;width: 100%;height: 200px;">
      <div class="row">
        <div id="local-video" class="col-md-3"></div>
      </div>
    </div>
    <div style="position: absolute;bottom: 0px;width: 100%;height: 200px;">
      <div class="row" id="remote-streams"></div>
    </div>
  <?php }else{ ?>
	<div id="remote-media">
		<div class="lds-facebook"><div style="background: #a33596;"></div><div style="background: #a33596;"></div><div style="background: #a33596;"></div></div>
		<h3><?php echo __('Please wait..');?></h3>
	</div>
	<div id="controls">
		<div id="preview">
			<div id="local-media"><video id="basic-stream" class="hide videostream" autoplay=""></video></div>
		</div>
		<div id="invite-controls"></div>
		<div id="log"><p></p></div>
	</div>
    <?php } ?>
	<button class="btn end_vdo_call hide">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,9C10.4,9 8.85,9.25 7.4,9.72V12.82C7.4,13.22 7.17,13.56 6.84,13.72C5.86,14.21 4.97,14.84 4.17,15.57C4,15.75 3.75,15.86 3.5,15.86C3.2,15.86 2.95,15.74 2.77,15.56L0.29,13.08C0.11,12.9 0,12.65 0,12.38C0,12.1 0.11,11.85 0.29,11.67C3.34,8.77 7.46,7 12,7C16.54,7 20.66,8.77 23.71,11.67C23.89,11.85 24,12.1 24,12.38C24,12.65 23.89,12.9 23.71,13.08L21.23,15.56C21.05,15.74 20.8,15.86 20.5,15.86C20.25,15.86 20,15.75 19.82,15.57C19.03,14.84 18.14,14.21 17.16,13.72C16.83,13.56 16.6,13.22 16.6,12.82V9.72C15.15,9.25 13.6,9 12,9Z" /></svg> <?php echo __('End Call');?>
	</button>
</div>
<?php if ($config->agora_chat_call == 1) { ?>
<script type="text/javascript">
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    if (!navigator.getUserMedia) {
        $('#remote-media h3').text('Sorry, WebRTC is not available in your browser.');
    }

var agoraAppId = "<?php echo $config->agora_chat_app_id;?>";
var token = "<?php echo $data['video_call']['access_token'];?>";
var channelName = "<?php echo $data['video_call']['room_name'];?>";
var uid = <?php echo auth()->id;?>;

/*
 * JS Interface for Agora.io SDK
 */

// video profile settings
var cameraVideoProfile = '480p_4'; // 640 × 480 @ 30fps  & 750kbs
var screenVideoProfile = '480p_2'; // 640 × 480 @ 30fps

// create client instances for camera (client) and screen share (screenClient)
var client = AgoraRTC.createClient({mode: 'rtc', codec: 'vp8'}); 
var screenClient;

// stream references (keep track of active streams) 
var remoteStreams = {}; // remote streams obj struct [id : stream] 

var localStreams = {
  camera: {
    id: "",
    stream: {}
  },
  screen: {
    id: "",
    stream: {}
  }
};

AgoraRTC.Logger.enableLogUpload(); // auto upload logs

var mainStreamId; // reference to main stream
var screenShareActive = false; // flag for screen share 

function initClientAndJoinChannel(agoraAppId, token, channelName, uid) {
  // init Agora SDK
  client.init(agoraAppId, function () {
    console.log("AgoraRTC client initialized");
    joinChannel(channelName, uid, token); // join channel upon successfull init
  }, function (err) {
    console.log("[ERROR] : AgoraRTC client init failed", err);
  });
}


client.on('stream-published', function (evt) {
  console.log("Publish local stream successfully");
});

// connect remote streams
client.on('stream-added', function (evt) {
  var stream = evt.stream;
  var streamId = stream.getId();
  console.log("new stream added: " + streamId);
  // Check if the stream is local
  if (streamId != localStreams.screen.id) {
    console.log('subscribe to remote stream:' + streamId);
    // Subscribe to the stream.
    client.subscribe(stream, function (err) {
      console.log("[ERROR] : subscribe stream failed", err);
    });
  }
});

client.on('stream-subscribed', function (evt) {
  var remoteStream = evt.stream;
  var remoteId = remoteStream.getId();
  remoteStreams[remoteId] = remoteStream;
  console.log("Subscribe remote stream successfully: " + remoteId);
  if( $('#full-screen-video').is(':empty') ) { 
    mainStreamId = remoteId;
    remoteStream.play('full-screen-video');
    $('#main-stats-btn').show();
    $('#main-stream-stats-btn').show();
  } else if (remoteId == 49024) {
    // move the current main stream to miniview
    remoteStreams[mainStreamId].stop(); // stop the main video stream playback
    client.setRemoteVideoStreamType(remoteStreams[mainStreamId], 1); // subscribe to the low stream
    addRemoteStreamMiniView(remoteStreams[mainStreamId]); // send the main video stream to a container
    // set the screen-share as the main 
    mainStreamId = remoteId;
    remoteStream.play('full-screen-video');
  } else {

    client.setRemoteVideoStreamType(remoteStream, 1); // subscribe to the low stream
    addRemoteStreamMiniView(remoteStream);
  }
});

// remove the remote-container when a user leaves the channel
client.on("peer-leave", function(evt) {

  var streamId = evt.stream.getId(); // the the stream id
  if(remoteStreams[streamId] != undefined) {
    remoteStreams[streamId].stop(); // stop playing the feed
    delete remoteStreams[streamId]; // remove stream from list
    if (streamId == mainStreamId) {
      var streamIds = Object.keys(remoteStreams);
      var randomId = streamIds[Math.floor(Math.random()*streamIds.length)]; // select from the remaining streams
      if(remoteStreams[randomId] != undefined) {
        remoteStreams[randomId].stop(); // stop the stream's existing playback
      }
      var remoteContainerID = '#' + randomId + '_container';
      $(remoteContainerID).empty().remove(); // remove the stream's miniView container
      if(remoteStreams[randomId] != undefined) {
      remoteStreams[randomId].play('full-screen-video'); // play the random stream as the main stream
    }
      mainStreamId = randomId; // set the new main remote stream
    } else {
      var remoteContainerID = '#' + streamId + '_container';
      $(remoteContainerID).empty().remove(); // 
    }
  }
  console.log($('#full-screen-video').is(':empty'));
  console.log($('#remote-streams').is(':empty'));
  if( $('#full-screen-video').is(':empty') &&  $('#remote-streams').is(':empty')) { 
    location.href = "<?php echo($config->uri) ?>";
  }
});

// show mute icon whenever a remote has muted their mic
client.on("mute-audio", function (evt) {
  toggleVisibility('#' + evt.uid + '_mute', true);
});

client.on("unmute-audio", function (evt) {
  toggleVisibility('#' + evt.uid + '_mute', false);
});

// show user icon whenever a remote has disabled their video
client.on("mute-video", function (evt) {
  var remoteId = evt.uid;
  // if the main user stops their video select a random user from the list
  if (remoteId != mainStreamId) {
    // if not the main vidiel then show the user icon
    toggleVisibility('#' + remoteId + '_no-video', true);
  }
});

client.on("unmute-video", function (evt) {
  toggleVisibility('#' + evt.uid + '_no-video', false);
});

// join a channel
function joinChannel(channelName, uid, token) {
  client.join(token, channelName, uid, function(uid) {
      console.log("User " + uid + " join channel successfully");
      createCameraStream(uid);
      localStreams.camera.id = uid; // keep track of the stream uid 
  }, function(err) {
      console.log("[ERROR] : join channel failed", err);
  });
}

// video streams for channel
function createCameraStream(uid) {
  var localStream = AgoraRTC.createStream({
    streamID: uid,
    audio: true,
    video: true,
    screen: false
  });
  localStream.setVideoProfile(cameraVideoProfile);
  localStream.init(function() {
    console.log("getUserMedia successfully");
    // TODO: add check for other streams. play local stream full size if alone in channel
    localStream.play('local-video'); // play the given stream within the local-video div
    $('.end_vdo_call').removeClass('hide');

    // publish local stream
    client.publish(localStream, function (err) {
      console.log("[ERROR] : publish local stream error: " + err);
    });
  
    //enableUiControls(localStream); // move after testing
    localStreams.camera.stream = localStream; // keep track of the camera stream for later
  }, function (err) {
    console.log("[ERROR] : getUserMedia failed", err);
  });
}

// SCREEN SHARING
function initScreenShare(agoraAppId, channelName) {
  screenClient = AgoraRTC.createClient({mode: 'rtc', codec: 'vp8'}); 
  console.log("AgoraRTC screenClient initialized");
  var uid = 49024; // hardcoded uid to make it easier to identify on remote clients
  screenClient = AgoraRTC.createClient({mode: 'rtc', codec: 'vp8'}); 
  screenClient.init(agoraAppId, function () {
    console.log("AgoraRTC screenClient initialized");
  }, function (err) {
    console.log("[ERROR] : AgoraRTC screenClient init failed", err);
  });
  // keep track of the uid of the screen stream. 
  localStreams.screen.id = uid;  
  
  // Create the stream for screen sharing.
  var screenStream = AgoraRTC.createStream({
    streamID: uid,
    audio: false, // Set the audio attribute as false to avoid any echo during the call.
    video: false,
    screen: true, // screen stream
    screenAudio: true,
    mediaSource:  'screen', // Firefox: 'screen', 'application', 'window' (select one)
  });
  // initialize the stream 
  // -- NOTE: this must happen directly from user interaction, if called by a promise or callback it will fail.
  screenStream.init(function(){
    console.log("getScreen successful");
    localStreams.screen.stream = screenStream; // keep track of the screen stream
    screenShareActive = true;
    $("#screen-share-btn").prop("disabled",false); // enable button
    screenClient.join(token, channelName, uid, function(uid) { 
      screenClient.publish(screenStream, function (err) {
        console.log("[ERROR] : publish screen stream error: " + err);
      });
    }, function(err) {
      console.log("[ERROR] : join channel as screen-share failed", err);
    });
  }, function (err) {
    console.log("[ERROR] : getScreen failed", err);
    localStreams.screen.id = ""; // reset screen stream id
    localStreams.screen.stream = {}; // reset the screen stream
    screenShareActive = false; // resest screenShare
    toggleScreenShareBtn(); // toggle the button icon back
    $("#screen-share-btn").prop("disabled",false); // enable button
  });
  var token = generateToken();
  screenClient.on('stream-published', function (evt) {
    console.log("Publish screen stream successfully");
    if( $('#full-screen-video').is(':empty') ) { 
      $('#main-stats-btn').show();
      $('#main-stream-stats-btn').show();
    } else {
      // move the current main stream to miniview
      remoteStreams[mainStreamId].stop(); // stop the main video stream playback
      client.setRemoteVideoStreamType(remoteStreams[mainStreamId], 1); // subscribe to the low stream
      addRemoteStreamMiniView(remoteStreams[mainStreamId]); // send the main video stream to a container
    }
    mainStreamId = localStreams.screen.id;
    localStreams.screen.stream.play('full-screen-video');
  });
  
  screenClient.on('stopScreenSharing', function (evt) {
    console.log("screen sharing stopped", err);
  }); 
}

function stopScreenShare() {
  localStreams.screen.stream.disableVideo(); // disable the local video stream (will send a mute signal)
  localStreams.screen.stream.stop(); // stop playing the local stream
  localStreams.camera.stream.enableVideo(); // enable the camera feed
  localStreams.camera.stream.play('local-video'); // play the camera within the full-screen-video div
  $("#video-btn").prop("disabled",false);
  screenClient.leave(function() {
    screenShareActive = false; 
    console.log("screen client leaves channel");
    $("#screen-share-btn").prop("disabled",false); // enable button
    screenClient.unpublish(localStreams.screen.stream); // unpublish the screen client
    localStreams.screen.stream.close(); // close the screen client stream
    localStreams.screen.id = ""; // reset the screen id
    localStreams.screen.stream = {}; // reset the stream obj
  }, function(err) {
    console.log("client leave failed ", err); //error handling
  }); 
}

// REMOTE STREAMS UI
function addRemoteStreamMiniView(remoteStream){
  var streamId = remoteStream.getId();
  // append the remote stream template to #remote-streams
  $('#remote-streams').append(
    $('<div/>', {'id': streamId + '_container',  'class': 'col-md-3','style':'height:100%'}).append(
      $('<div/>', {'id': streamId + '_mute', 'class': 'mute-overlay'}).append(
          $('<i/>', {'class': 'fas fa-microphone-slash'})
      ),
      $('<div/>', {'id': streamId + '_no-video', 'class': 'no-video-overlay text-center'}).append(
        $('<i/>', {'class': 'fas fa-user'})
      ),
      $('<div/>', {'id': 'agora_remote_' + streamId, 'class': 'remote-video'})
    )
  );
  remoteStream.play('agora_remote_' + streamId); 

  var containerId = '#' + streamId + '_container';
  $(containerId).dblclick(function() {
    // play selected container as full screen - swap out current full screen stream
    remoteStreams[mainStreamId].stop(); // stop the main video stream playback
    addRemoteStreamMiniView(remoteStreams[mainStreamId]); // send the main video stream to a container
    $(containerId).empty().remove(); // remove the stream's miniView container
    remoteStreams[streamId].stop() // stop the container's video stream playback
    remoteStreams[streamId].play('full-screen-video'); // play the remote stream as the full screen video
    mainStreamId = streamId; // set the container stream id as the new main stream id
  });
}

function leaveChannel() {
  
  if(screenShareActive) {
    stopScreenShare();
  }

  client.leave(function() {
    console.log("client leaves channel");
    localStreams.camera.stream.stop() // stop the camera stream playback
    client.unpublish(localStreams.camera.stream); // unpublish the camera stream
    localStreams.camera.stream.close(); // clean up and close the camera stream
    $("#remote-streams").empty() // clean up the remote feeds
    //disable the UI elements
    // $("#mic-btn").prop("disabled", true);
    // $("#video-btn").prop("disabled", true);
    // $("#screen-share-btn").prop("disabled", true);
    // $("#exit-btn").prop("disabled", true);
    // hide the mute/no-video overlays
    // toggleVisibility("#mute-overlay", false); 
    // toggleVisibility("#no-local-video", false);
    // show the modal overlay to join
    // $("#modalForm").modal("show"); 
  }, function(err) {
    console.log("client leave failed ", err); //error handling
  });
}

// use tokens for added security
function generateToken() {
  return null; // TODO: add a token generation
}
$(document).on('click', '.end_vdo_call', function(e) {
      leaveChannel(); 
      location.href = "<?php echo($config->uri) ?>";
  });
initClientAndJoinChannel(agoraAppId, token, channelName, uid);
</script>
<style type="text/css">
  
#buttons-container {
  position: absolute;
  z-index: 2;  
  width: 100vw;
}

#full-screen-video {
  position: absolute;
  width: 100vw;
  height: 100vh;
}

#lower-video-bar {
  height: 20vh;
}

#local-stream-container { 
  position: relative; 
  display: inline-block;
}
/*
.remote-stream-container { 
  display: inline-block;
}*/

#remote-streams {
  height: 100%;
}

#local-video {
  position: absolute;
  z-index: 1;
  height: 100%;
  width: 100%;
}

.remote-video {
  position: absolute;
  z-index: 1;
  height: 100% !important;
  width: 80%;
  max-width: 500px;
}

#mute-overlay {
  position: absolute;
  z-index: 2;
  bottom: 0;
  left: 0;
  color: #d9d9d9;
  font-size: 2em;
  padding: 0 0 3px 3px;
  display: none;
} 

.mute-overlay {
  position: absolute;
  z-index: 2;
  top: 2px;
  color: #d9d9d9;
  font-size: 1.5em;
  padding: 2px 0 0 2px;
  display: none;
}

#no-local-video, .no-video-overlay {
  position: absolute;
  z-index: 3;
  width: 100%;
  top: 40%;
  color: #cccccc;
  font-size: 2.5em;
  margin: 0 auto;
  display: none;
}

.no-video-overlay {
  width: 80%;
}

#screen-share-btn-container {
  z-index: 99;
}
</style>
<?php }else{ ?>
<script>
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    if (!navigator.getUserMedia) {
        $('#remote-media h3').text('Sorry, WebRTC is not available in your browser.');
    }

    var video = document.getElementById('basic-stream');

    Twilio.Video.connect('<?php echo $data['video_call']['access_token'];?>', { name: '<?php echo $data['video_call']['room'];?>' }).then(room => {
        //console.log('Connected to Room "%s"', room.name);

        room.participants.forEach(participantConnected);
        room.on('participantConnected', participantConnected);

        room.on('participantDisconnected', participantDisconnected);
        room.once('disconnected', error => room.participants.forEach(participantDisconnected));
        $(document).on('click', 'a[data-ajax]', function(e) {
            room.disconnect();
        });
        $(document).on('click', '.end_vdo_call', function(e) {
            room.disconnect();
            $('#basic-stream').addClass('hide');
            $('.end_vdo_call').addClass('hide');
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
        if (navigator.getUserMedia) {
            navigator.mediaDevices.getUserMedia({audio: false, video: true}).then((stream) => {video.srcObject = stream});
            $('#basic-stream').removeClass('hide');
            $('.end_vdo_call').removeClass('hide');
        }
    }

    function participantDisconnected(participant) {
        //console.log('Participant "%s" disconnected', participant.identity);

        participant.tracks.forEach(trackRemoved);
        document.getElementById(participant.sid).remove();
        $('#basic-stream').addClass('hide');
        $('.end_vdo_call').addClass('hide');
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