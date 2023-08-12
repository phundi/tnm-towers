<?php global $db,$_LIBS; ?>
<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
	
		<div class="col l6">
			<!-- People i liked  -->
			<div class="container-fluid dt_ltst_users">
				<?php if (!empty($data['live_users'])) { ?>
				<div class="dt_home_match_user dt_live_users_top">
					<div class="valign-wrapper mtc_usr_avtr">
						<?php $i = 0; foreach ($data['live_users'] as $key => $value) { 
							if ($i < 7) {
						?>
						<div class="usr_thumb isActive">
							<img src="<?php echo $value->user_data->avater->avater;?>">
							<div class="badge"><?php echo __('Live');?></div>
						</div>
						<?php } $i++; } ?>
					</div>
				</div>
				<?php } ?>
					
				<div class="dt_home_rand_user">
					<div class="row" id="live_users_container">
                        <?php
                            if(empty($data['live_users_html'])){
                                echo '<div class="col s12"><div class="dt_sections"><h5 id="_load_more" class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z"></path></svg>'.__('no_currently_live') .'</h5></div></div>';
                            }else {
                                echo $data['live_users_html'];
                            }
                        ?>
					</div>
                    <?php if(!empty($data['live_users_html'])){ ?>
                        <a href="javascript:void(0);" id="btn_load_more_live_users" data-lang-nomore="<?php echo __('No more users to show.');?>" data-ajax-post="/loadmore/LoadLiveUsers" data-ajax-params="page=2" data-ajax-callback="callback_load_more_live_users" class="btn waves-effect load_more"><?php echo __('Load more...');?></a>
                    <?php }?>
                </div>
			</div>
			<!-- People i liked -->
		</div>
		
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
		</div>
	</div>
</div>

<div id="modal_show_live" class="modal dt_who_live">
    <div class="modal-content">
        <h6 class="bold valign-wrapper"><span class="was_live_text"><?php echo (__( 'Is Live' )); ?></span><button class="btn-floating btn-small btn-flat close_live_video"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg></button></h6>
		<div class="row r_margin" style="margin-bottom: 0">
			<div class="col m8 s12">
				<div class="embed-responsive embed-responsive-4by3" id="post_live_video"></div>
			</div>
			<div class="col m4 s12">
				<div class="dt_home_rand_user">
					<h4 class="bold"><div><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M20 2H4C2.89 2 2 2.89 2 4V16C2 17.11 2.9 18 4 18H8V21C8 21.55 8.45 22 9 22H9.5C9.75 22 10 21.9 10.2 21.71L13.9 18H20C21.1 18 22 17.1 22 16V4C22 2.89 21.1 2 20 2M9.08 15H7V12.91L13.17 6.72L15.24 8.8L9.08 15M16.84 7.2L15.83 8.21L13.76 6.18L14.77 5.16C14.97 4.95 15.31 4.94 15.55 5.16L16.84 6.41C17.05 6.62 17.06 6.96 16.84 7.2Z"></path></svg></div> <?php echo __('Write a Comment');?></h4>
				</div>
				<div class="input-field">
					<textarea class="comment-textarea textarea live_video_comment_text" placeholder="<?php echo __('Write a Comment');?>" type="text" onkeyup="LiveComment(this.value,event,'');" dir="auto"></textarea>
				</div>
				<div class="dt_home_rand_user">
					<h4 class="bold"><div><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M12,23A1,1 0 0,1 11,22V19H7A2,2 0 0,1 5,17V7A2,2 0 0,1 7,5H21A2,2 0 0,1 23,7V17A2,2 0 0,1 21,19H16.9L13.2,22.71C13,22.89 12.76,23 12.5,23H12M3,15H1V3A2,2 0 0,1 3,1H19V3H3V15Z"></path></svg></div> <?php echo __('Discussion');?></h4>
				</div>
				<div id="live_post_comments" class="wow_liv_comments_feed"></div>
			</div>
		</div>
    </div>
</div>

<script type="text/javascript">
jQuery.rnd = function(m,n) {
      m = parseInt(m);
      n = parseInt(n);
      return Math.floor( Math.random() * (n - m + 1) ) + m;
}

function hearts() {
   $.each($(".heart-particle"), function(){
      var heartcount = ($(this).width()/50)*5;
      for(var i = 0; i <= heartcount; i++) {
         var size = ($.rnd(60,120)/10);
         $(this).append('<span class="particle" style="top:' + $.rnd(20,80) + '%; left:' + $.rnd(0,95) + '%;width:' + size + 'px; height:' + size + 'px;animation-delay: ' + ($.rnd(0,30)/10) + 's;"></span>');
      }
   });
}

hearts();

	var post_live ;
	var client = AgoraRTC.createClient({mode: 'live', codec: 'vp8'}); 

	function RunLiveAgora(channelName,DIV_ID,token,id,name) {
		$('.live_video_comment_text').removeAttr('disabled');
		$('.live_video_comment_text').attr('onkeyup', 'LiveComment(this.value,event,'+id+');');
		$('.close_live_video').attr('onclick', 'CloseLiveVideo('+id+');');
		clearInterval(post_live);
		$('#live_post_comments').html('');
		$('.was_live_text').html(name + ' <?php echo (__( 'Is Live' )); ?>');
		$('#post_live_video').html('');
		client.leave(function() {
		    console.log("client leaves channel");
		  }, function(err) {
		    console.log("client leave failed ", err); //error handling
		  });
		$("#modal_show_live").modal('open');
	  var agoraAppId = '<?php echo($config->agora_app_id) ?>'; 
	  var token = token;

	  
	  client.init(agoraAppId, function () {


	      client.setClientRole('audience', function() {
	    }, function(e) {
	    });
	    
	    client.join(token, channelName, Math.floor(Math.random() * 999999) + 100, function(uid) {
	    }, function(err) {
	    });
	    }, function (err) {
	    });

	    client.on('stream-added', function (evt) {
	    var stream = evt.stream;
	    var streamId = stream.getId();
	    
	    client.subscribe(stream, function (err) {
	    });
	  });
	  client.on('stream-subscribed', function (evt) {
	    var remoteStream = evt.stream;
	    remoteStream.play(DIV_ID);
	    $('#player_'+remoteStream.getId()).addClass('embed-responsive-item');
	  });

	  post_live = setInterval(function(){ 
	      data = {};
	      for (var i = 0; i < $('.live_comments').length; i++) {
	        if ($($('.live_comments')[i]).attr('live_comment_id')) {
	          data[i] = $($('.live_comments')[i]).attr('live_comment_id');
	        }
	      }
	      $.post(window.ajax + 'live/check_comments', {post_id: id,ids:data,page:"show"}, function(data, textStatus, xhr) {
	        if (data.status == 200) {
	          if (data.still_live == 'offline') {
	            $('#live_post_comments').remove();
	            $('.was_live_text').html(name + " <?php echo(__( 'Was Live' )) ?>");
	            $('.live_video_comment_text').attr('disabled');
	            return false;
	          }
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
	      }).fail(function (data) {
	      	if (data.responseJSON.removed == 'yes') {
	      		clearInterval(post_live);
	            $('#live_count').html(0);
	            $('#live_post_comments').html("<h3 class='end_video_text'>stream has ended</h3>");
	            $('.was_live_text').html(name + " <?php echo(__( 'Was Live' )) ?>");
	            $('.live_video_comment_text').attr('disabled','true');
	            return false;

	      	}
	      	else{
	            clearInterval(post_live);
	            $('#live_count').html(0);
	            $('#live_post_comments').html("<h3 class='end_video_text'>stream has ended</h3>");
	            $('.was_live_text').html(name + " <?php echo(__( 'Was Live' )) ?>");
	            $('.live_video_comment_text').attr('disabled','true');
	            return false;
	        }
	        });
	   }, 3000);
	}
	function CloseLiveVideo(id) {
		clearInterval(post_live);
		$("#modal_show_live").modal('close');
		$('#live_post_comments').html('');
		$('.was_live_text').html('<?php echo (__( 'Is Live' )); ?>');
		$('#post_live_video').html('');
		client.leave(function() {
		    console.log("client leaves channel");
		  }, function(err) {
		    console.log("client leave failed ", err); //error handling
		  });
	}
	function LiveComment(text,event,post_id,insert = 0) {
	  text = $('.comment-textarea').val();
	  if (text && (event.keyCode == 13 || insert == 1)) {
	    if ($('#live_post_comments .live_comments').length >= 4) {
	      $('#live_post_comments .live_comments').first().remove();
	    }
	      $('#live_post_comments').append('<div class="live_comments" live_comment_id=""><a class="pull-left" href="<?php echo $site_url;?>/@<?php echo auth()->username?>"><img class="live_avatar pull-left" src="<?php echo(auth()->avater->avater) ?>" alt="avatar"></a><div class="comment-body" style="float: left;"><div class="comment-heading"><span><a href="<?php echo $site_url;?>/@<?php echo auth()->username?>" data-ajax="/@<?php echo auth()->username?>" ><h4 class="live_user_h"> <?php echo (auth()->first_name !== '' ) ? auth()->first_name . ' ' . auth()->last_name : auth()->username;?> </h4></a></span><div class="comment-text">'+text+'</div></div></div><div class="clear"></div></div>');
	      $('.comment-textarea').val('');
	      $.post(window.ajax + 'live/new_comment', {post_id: post_id,text:text}, function(data, textStatus, xhr) {
	      	/*optional stuff to do after success */
	      }).fail(function (data) {

	      });

	  }
	}


</script>