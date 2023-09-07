<?php global $site_url;?>
<div class="col l3 m6 s12 matches visit" data-liked-uid="<?php echo $row->id?>">
    <div class="card valign-wrapper">
        <div class="card-image">
            <a href="<?php echo $site_url;?>/@<?php echo $row->username?>" data-ajax="/@<?php echo $row->username?>">
                <img src="<?php echo GetMedia('',false); ?><?php echo $row->avater?>" alt="<?php echo $row->username?>">
            </a>
        </div>
        <div class="card-content">
            <a href="<?php echo $site_url;?>/@<?php echo $row->username?>" data-ajax="/@<?php echo $row->username?>"><span class="card-title"><?php echo ($row->first_name !== '' ) ? $row->first_name . ' ' . $row->last_name : $row->username;?> <?php if((int)abs(((strtotime(date('Y-m-d H:i:s')) - $row->lastseen))) < 60 && (int)$row->online == 1) { echo '<div class="useronline"></div>'; }?></span></a>
            <p><span class="time ajax-time age" title="<?php echo $row->created_at;?>"><?php echo get_time_ago( strtotime($row->created_at) );?></span></p>
			<p><?php echo $row->district ?></p>
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
               
                <button id="like_btn" class="btn waves-effect like liked" data-ajax-post="/useractions/remove_like" data-ajax-params="userid=<?php echo $row->id?>" data-ajax-callback="callback_liked_remove_like">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 18C12 19 12.25 19.92 12.67 20.74L12 21.35L10.55 20.03C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5C22 9.93 21.5 11.26 20.62 12.61C19.83 12.23 18.94 12 18 12C14.69 12 12 14.69 12 18M21.54 15.88L20.12 14.47L18 16.59L15.88 14.47L14.47 15.88L16.59 18L14.47 20.12L15.88 21.54L18 19.41L20.12 21.54L21.54 20.12L19.41 18L21.54 15.88Z"/></svg>
                </button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
