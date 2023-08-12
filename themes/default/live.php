<style>footer {display: none}</style>

<div class="video-con wow_go_live_cont">
    <div class="container">
        <div id="remote-media">
            <div class="liv_vid_cont" id="main_live_video"></div>
            <h3><i class="fa fa-spin fa-spinner"></i> <?php echo __('please_wait')?></h3>
            <div class="wow_liv_counter"><span id="live_word"><?php echo __('Live'); ?></span> <span id="live_count"> 0</span></div>
            <div id="live_post_comments" class="wow_liv_comments_feed"></div>
        </div>
		<div class="valign-wrapper btn-start-end-prnt">
			<button class="btn btn-mat btn-start-end btn-success start_vdo_call wow_go_live_btn" id="publishBtn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17,14.5V11A1,1 0 0,0 16,10H4A1,1 0 0,0 3,11V21A1,1 0 0,0 4,22H16A1,1 0 0,0 17,21V17.5L21,21.5V10.5M3,3.86L4.4,5.24C7.5,2.19 12.5,2.19 15.6,5.24L17,3.86C13.14,0.05 6.87,0.05 3,3.86M5.8,6.62L7.2,8C8.75,6.5 11.25,6.5 12.8,8L14.2,6.62C11.88,4.34 8.12,4.34 5.8,6.62Z" /></svg>&nbsp;&nbsp;<?php echo __('Go Live'); ?></button>
			<a class="btn btn-mat btn-start-end end_vdo_call wow_end_live_btn hide" href="<?php echo($site_url) ?>" onclick="DeleteLive()">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z" /></svg> <?php echo  __('End Live'); ?>
			</a>
			<?php if ($config->agora_live_video == 1 && !empty($config->agora_app_id)) { ?>
				<div class="live_mic_cam_switch" style="display: none;">
					<div class="dropdown mic_drop">
						<button class="btn btn-mat dropdown-trigger" type="button" data-target="mic-list" title="<?php echo __('Mic Source'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2A3,3 0 0,1 15,5V11A3,3 0 0,1 12,14A3,3 0 0,1 9,11V5A3,3 0 0,1 12,2M19,11C19,14.53 16.39,17.44 13,17.93V21H11V17.93C7.61,17.44 5,14.53 5,11H7A5,5 0 0,0 12,16A5,5 0 0,0 17,11H19Z" /></svg></button>
						<div class="dropdown-content" id="mic-list"></div>
					</div>
					<div class="dropdown cam_drop">
						<button class="btn btn-mat dropdown-trigger" type="button" data-target="camera-list" title="<?php echo __('Cam Source'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17,10.5V7A1,1 0 0,0 16,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16A1,1 0 0,0 17,17V13.5L21,17.5V6.5L17,10.5Z" /></svg></button>
						<div class="dropdown-content" id="camera-list"></div>
					</div>
				</div>
			<?php } ?>
		</div>
    </div>
    <input type="hidden" id="live_post_id">
</div>
<script type="text/javascript">
    var rand = 0;

    function ready() {

      //Setup publish button
      let pubBtn = document.getElementById('publishBtn');
      if (pubBtn) {
        pubBtn.onclick = evt => {
          startAgoraBroadcast();
        };
      }

      //Get users camera and mic
      getMedia()
        .then(str => {
          stream     = str;
          //set cam feed to video window so user can see self.
          let vidWin = document.getElementsByTagName('video')[0];
          if (vidWin) {

            $('#basic-stream').removeClass('hidden');
            //$('.end_vdo_call').removeClass('hidden');
            vidWin.srcObject = stream;
            //startBroadcast();
          }
        })
        .catch(e => {
          $('#remote-media').html('<h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M3.27,2L2,3.27L4.73,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16C16.2,18 16.39,17.92 16.54,17.82L19.73,21L21,19.73M21,6.5L17,10.5V7A1,1 0 0,0 16,6H9.82L21,17.18V6.5Z" fill="currentColor"></path></svg> getUserMedia Error: '+e+'</h5>');
		  $('.btn-start-end').addClass('hide');
        });
    }





function getCameraDevices() {
  console.log("Checking for Camera Devices.....")
  client.getCameras (function(cameras) {
    devices.cameras = cameras; // store cameras array
    cameras.forEach(function(camera, i){
      //var name = camera.label.split('(')[0];
      var name = camera.label;
      var optionId = 'camera_' + i;
      var deviceId = camera.deviceId;
      if(i === 0 && localStreams.camera.camId === ''){
        localStreams.camera.camId = deviceId;
      }
      $('#camera-list').append('<a class="dropdown-item pointer" id="' + optionId + '">' + name + '</a>');
    });
    $('#camera-list a').click(function(event) {
      var index = event.target.id.split('_')[1];
      changeStreamSource (index, "video");
    });
  });
}

function getMicDevices() {
  console.log("Checking for Mic Devices.....")
  client.getRecordingDevices(function(mics) {
    devices.mics = mics; // store mics array
    mics.forEach(function(mic, i){
      //var name = mic.label.split('(')[0];
      var name = mic.label;
      var optionId = 'mic_' + i;
      var deviceId = mic.deviceId;
      if(i === 0 && localStreams.camera.micId === ''){
        localStreams.camera.micId = deviceId;
      }
      if(name.split('Default - ')[1] != undefined) {
        name = '[Default Device]' // rename the default mic - only appears on Chrome & Opera
      }
      $('#mic-list').append('<a class="dropdown-item pointer" id="' + optionId + '">' + name + '</a>');
    }); 
    $('#mic-list a').click(function(event) {
      var index = event.target.id.split('_')[1];
      changeStreamSource (index, "audio");
    });
  });
}
function changeStreamSource (deviceIndex, deviceType) {
  console.log('Switching stream sources for: ' + deviceType);
  var deviceId;
  var existingStream = false;
  
  if (deviceType === "video") {
    deviceId = devices.cameras[deviceIndex].deviceId
  }

  if(deviceType === "audio") {
    deviceId = devices.mics[deviceIndex].deviceId;
  }

  localStreams.camera.stream.switchDevice(deviceType, deviceId, function(){
    console.log('successfully switched to new device with id: ' + JSON.stringify(deviceId));
    // set the active device ids
    if(deviceType === "audio") {
      localStreams.camera.micId = deviceId;
    } else if (deviceType === "video") {
      localStreams.camera.camId = deviceId;
    } else {
      console.log("unable to determine deviceType: " + deviceType);
    }
  }, function(){
    console.log('failed to switch to new device with id: ' + JSON.stringify(deviceId));
  });
}


var agoraAppId = '<?php echo($config->agora_app_id) ?>'; // set app id
var channelName = "<?php echo($q['AgorachannelName']) ?>"; // set channel name

// create client instance
var client = AgoraRTC.createClient({mode: 'live', codec: 'vp8'}); // h264 better detail at a higher motion
var mainStreamId; // reference to main stream

// set video profile 
// [full list: https://docs.agora.io/en/Interactive%20Broadcast/videoProfile_web?platform=Web#video-profile-table]
var cameraVideoProfile = '720p_6'; // 960 × 720 @ 30fps  & 750kbs

// keep track of streams
var localStreams = {
  uid: rand,
  camera: {
    camId: '',
    micId: '',
    stream: {}
  }
};

// keep track of devices
var devices = {
  cameras: [],
  mics: []
}

var externalBroadcastUrl = '';

// default config for rtmp
var defaultConfigRTMP = {
  width: 640,
  height: 360,
  videoBitrate: 400,
  videoFramerate: 15,
  lowLatency: false,
  audioSampleRate: 48000,
  audioBitrate: 48,
  audioChannels: 1,
  videoGop: 30,
  videoCodecProfile: 100,
  userCount: 0,
  userConfigExtraInfo: {},
  backgroundColor: 0x000000,
  transcodingUsers: [],
};

// set log level:
// -- .DEBUG for dev 
// -- .NONE for prod
AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.DEBUG); 

// init Agora SDK
function startAgoraBroadcast() {
    client.init(agoraAppId, function () {
      console.log('AgoraRTC client initialized');
      joinChannel(); // join channel upon successfull init
    }, function (err) {
      console.log('[ERROR] : AgoraRTC client init failed', err);
    });
}


// client callbacks
client.on('stream-published', function (evt) {
  console.log('Publish local stream successfully');
});

// when a remote stream is added
client.on('stream-added', function (evt) {
  console.log('new stream added: ' + evt.stream.getId());
});

client.on('stream-removed', function (evt) {
  var stream = evt.stream;
  stream.stop(); // stop the stream
  stream.close(); // clean up and close the camera stream
  console.log("Remote stream is removed " + stream.getId());
});

//live transcoding events..
client.on('liveStreamingStarted', function (evt) {
  console.log("Live streaming started");
}); 

client.on('liveStreamingFailed', function (evt) {
  console.log("Live streaming failed");
}); 

client.on('liveStreamingStopped', function (evt) {
  console.log("Live streaming stopped");
});

client.on('liveTranscodingUpdated', function (evt) {
  console.log("Live streaming updated");
}); 

// ingested live stream 
client.on('streamInjectedStatus', function (evt) {
  console.log("Injected Steram Status Updated");
  console.log(JSON.stringify(evt));
}); 

// when a remote stream leaves the channel
client.on('peer-leave', function(evt) {
  console.log('Remote stream has left the channel: ' + evt.stream.getId());
});

// show mute icon whenever a remote has muted their mic
client.on('mute-audio', function (evt) {
  console.log('Mute Audio for: ' + evt.uid);
});

client.on('unmute-audio', function (evt) {
  console.log('Unmute Audio for: ' + evt.uid);
});

// show user icon whenever a remote has disabled their video
client.on('mute-video', function (evt) {
  console.log('Mute Video for: ' + evt.uid);
});

client.on('unmute-video', function (evt) {
  console.log('Unmute Video for: ' + evt.uid);
});

// join a channel
function joinChannel() {
  var token = generateToken();
  var userID = 0; // set to null to auto generate uid on successfull connection

  // set the role
  client.setClientRole('host', function() {
    console.log('Client role set as host.');
  }, function(e) {
    console.log('setClientRole failed', e);
  });
  
  // client.join(token, 'allThingsRTCLiveStream', 0, function(uid) {
  client.join(token, channelName, userID, function(uid) {
      createCameraStream(uid, {});
      localStreams.uid = uid; // keep track of the stream uid  
      console.log('User ' + uid + ' joined channel successfully');
      $('#main_live_video').html('')
      $('#publishBtn').removeAttr('disabled');
      $('#publishBtn').text("<?php echo(__('please_wait')); ?>");
      $('#publishBtn').addClass('hide');
      $('.end_vdo_call').removeClass('hide');

      $.post(window.ajax + 'live/create', {stream_name: channelName,token:token}, function(data, textStatus, xhr) {
                    if (data.status == 200) {
                      $('#live_post_id').val(data.post_id);
                    $('.live_mic_cam_switch').slideDown();
                    setTimeout(function () {
                      image = capture_video_frame("video"+uid, 'png');
                      var thumb   = new File([base64_2_blob(image.dataUri)], "thumb.png", {type:"image/png"});
                      var formData = new FormData();
                      formData.append('thumb',thumb);
                      formData.append('post_id',data.post_id);
                      $.ajax({
                        processData: false,
                        url: window.ajax + 'live/create_thumb',
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        contentType: false,
                      })
                      .done(function() {
                        console.log("success");
                      })
                      .fail(function() {
                        console.log("error");
                      })
                      .always(function() {
                        console.log("complete");
                      });
                    },500);
                    }
                  });
  }, function(err) {
      console.log('[ERROR] : join channel failed', err);
  });
}

// video streams for channel
function createCameraStream(uid, deviceIds) {
  console.log('Creating stream with sources: ' + JSON.stringify(deviceIds));

  var localStream = AgoraRTC.createStream({
    streamID: uid,
    audio: true,
    video: true,
    screen: false
  });
  localStream.setVideoProfile(cameraVideoProfile);

  // The user has granted access to the camera and mic.
  localStream.on("accessAllowed", function() {
    if(devices.cameras.length === 0 && devices.mics.length === 0) {
      console.log('[DEBUG] : checking for cameras & mics');
      getCameraDevices();
      getMicDevices();
    }
    console.log("accessAllowed");
  });
  // The user has denied access to the camera and mic.
  localStream.on("accessDenied", function() {
    console.log("accessDenied");
  });

  localStream.init(function() {
    console.log('getUserMedia successfully');
    $('#main_live_video').html('')
    localStream.play('main_live_video'); // play the local stream on the main div
    // publish local stream

    client.publish(localStream, function (err) {
      console.log('[ERROR] : publish local stream error: ' + err);
    });

    localStreams.camera.stream = localStream; // keep track of the camera stream for later
  }, function (err) {
    console.log('[ERROR] : getUserMedia failed', err);
  });
}

// use tokens for added security
function generateToken() {
  return "<?php echo($q['AgoraToken']); ?>";
  //return null; // TODO: add a token generation
}
</script>
<script>
  window.onbeforeunload = function() {
  DeleteLive();
 }
var main_live = setInterval(function(){ 
  data = {};
  for (var i = 0; i < $('.live_comments').length; i++) {
    if ($($('.live_comments')[i]).attr('live_comment_id')) {
      data[i] = $($('.live_comments')[i]).attr('live_comment_id');
    }
  }
  post_id = $('#live_post_id').val();
  if ($('#live_post_id').length == 0) {
    clearInterval(main_live);
  }
  $.post(window.ajax + 'live/check_comments', {post_id: post_id,ids:data,page:'live'}, function(data, textStatus, xhr) {
    if (data.status == 200) {
      $('#live_post_comments').append(data.html);
      $('#live_count').html(data.count);
      $('#live_word').html(data.word);
      var comments = $('#live_post_comments .live_comments');
      if (comments.length > 4) {
        var i;
        for (i = 0; i < comments.length; i++) {
          if ($('#live_post_comments .live_comments').length > 4) {
            comments[i].remove();
          }
        }
      }
    }
    else if(data.removed == 'yes'){
        clearInterval(main_live);
        return false;
    }
  });
}, 3000);
function DeleteLive() {
  post_id = $('#live_post_id').val();
  $.post(window.ajax + 'live/delete', {post_id: post_id}, function(data, textStatus, xhr) {});
}



navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
if (!navigator.getUserMedia) {
  $('#remote-media h3').text('Sorry, WebRTC is not available in your browser.');
}




function getMedia() {
  return new Promise((resolve, reject) => {
    /*
    //getusermedia constraints
    let constraints = {
      audio:true,
      video: {
        width:  { min: 640, max: 1920, ideal: 1280 },
        height: { min: 480, max: 1080, ideal: 720 },
        frameRate: { min: 10, max: 60, ideal: 24 },
        advanced: [
          // additional constraints go here, tried in order until something succeeds
          // can attempt high level exact constraints, slowly falling back to lower ones
          { aspectRatio: 16/9 },
          { aspectRatio:  4/3 },
        ]
      }
    } */

    let constraints = {audio: true, video: true};
    navigator.mediaDevices.getUserMedia(constraints)
      .then(str => {
        resolve(str);
        $('#remote-media h3').addClass('hide');
        $('#remote-media .liv_vid_cont').html('<video id="basic-stream" class="videostream" autoplay="" style="width: 100%;height: calc(100% - 2px);vertical-align: middle;"></video>');
      }).catch(err => {
      $('#remote-media h3').text('Could not get Media: '+err);
      reject(err);
    })
  });
}

if (navigator.getUserMedia) {
  ready();
}
function base64_2_blob(dataURI) {
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], { type:mimeString });
}
function capture_video_frame(video, format) {
    if (typeof video === 'string') {
        video = document.getElementById(video);
    }

    format = format || 'jpeg';

    if (!video || (format !== 'png' && format !== 'jpeg')) {
        return false;
    }

    var canvas = document.createElement("canvas");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    canvas.getContext('2d').drawImage(video, 0, 0);


    var dataUri = canvas.toDataURL('image/' + format);
    var data = dataUri.split(',')[1];
    var mimeType = dataUri.split(';')[0].slice(5)

    var bytes = window.atob(data);
    var buf = new ArrayBuffer(bytes.length);
    var arr = new Uint8Array(buf);

    for (var i = 0; i < bytes.length; i++) {
        arr[i] = bytes.charCodeAt(i);
    }

    var blob = new Blob([ arr ], { type: mimeType });
    return { blob: blob, dataUri: dataUri, format: format };
}
$.getScript("<?php echo $theme_url;?>assets/js/excanvas.js?version=<?php echo time(); ?>", function(data, textStatus) {});
</script>