<?php global $site_url;?>
<div class="col l3 m6 s12 matches visit" data-likes-uid="<?php echo $row->id?>">
    <div class="card valign-wrapper">
        <div class="card-image">
            <a href="<?php echo $site_url;?>/@<?php echo $row->username?>" data-ajax="/@<?php echo $row->username?>">
                <img src="<?php echo GetMedia('',false); ?><?php echo $row->avater?>" alt="<?php echo $row->username?>">
            </a>
        </div>
        <div class="card-content">
            <a href="<?php echo $site_url;?>/@<?php echo $row->username?>" data-ajax="/@<?php echo $row->username?>"><span class="card-title"><?php echo ($row->first_name !== '' ) ? $row->first_name . ' ' . $row->last_name : $row->username;?> <?php if((int)abs(((strtotime(date('Y-m-d H:i:s')) - $row->lastseen))) < 60 && (int)$row->online == 1) { echo '<div class="useronline"></div>'; }?></span></a>
            <p><span class="time ajax-time age" title="<?php echo $row->created_at;?>"><?php echo get_time_ago( strtotime($row->created_at) );?></span></p>
			<p><? <?php echo $row->district?></p>
        </div>
        
        <div class="rand_bottom_bar">
                <?php if( (int)$row->id !== (int)auth()->id ){ ?>
					
				   <button  class="btn waves-effect yellow_bg"  id="btn_open_private_conversation" 
                    <?php 
                    if( auth()->is_pro == "1"){ ?>
                        href="javascript:void(0);"  data-ajax-post="/chat/open_private_conversation" 
                        data-ajax-params="from=<?php echo $row->id;?>&web_device_id=<?php echo $row->web_device_id;?>" data-ajax-callback="open_private_conversation" 
                    <?php } else { ?>
                        onclick="window.location='/pro'"                        
                    <?php } ?>
                    
                    style="margin-right: 10px;" 
                    
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path fill="currentColor" d="M14 22.5L11.2 19H6a1 1 0 0 1-1-1V7.103a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1V18a1 1 0 0 1-1 1h-5.2L14 22.5zm1.839-5.5H21V8.103H7V17H12.161L14 19.298 15.839 17zM2 2h17v2H3v11H1V3a1 1 0 0 1 1-1z"/></svg>
                </button>

				<button class="btn waves-effect like" id="like_btn" data-userid="<?php echo $row->id;?>" data-ajax-post="/useractions/like" data-ajax-params="userid=<?php echo $row->id;?>&username=<?php echo $row->username;?>" data-ajax-callback="callback_like">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"/></svg>
                </button>
                <button class="btn waves-effect dislike _dislike_text<?php echo $row->id;?>" data-userid="<?php echo $row->id;?>" id="dislike_btn" data-ajax-post="/useractions/dislike" data-ajax-params="userid=<?php echo $row->id;?>" data-ajax-callback="callback_dislike">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/></svg>
                </button>
             
                <?php } ?>
            </div>
    </div>
</div>
