<?php global $site_url;?>

<div class="col l3 m6 s12 matches visit">
    <div class="card valign-wrapper">
        <div class="card-image">
            <a href="<?php echo $site_url;?>/@<?php echo $row->username?>" data-ajax="/@<?php echo $row->username?>">
                <img src="<?php echo GetMedia('',false); ?><?php echo $row->avater?>" alt="<?php echo $row->username?>">
            </a>
        </div>
        <div class="card-content">
            <a href="<?php echo $site_url;?>/@<?php echo $row->username?>" data-ajax="/@<?php echo $row->username?>"><span class="card-title"><?php echo ($row->first_name !== '' ) ? $row->first_name . ' ' . $row->last_name : $row->username;?> <?php if((int)abs(((strtotime(date('Y-m-d H:i:s')) - $row->lastseen))) < 60 && (int)$row->online == 1) { echo '<div class="useronline"></div>'; }?></span></a>
            <p><span><?php echo get_time_ago( strtotime($row->birthday) );?></span></p>
			<p><?php echo __( 'from' );?> <?php echo $row->country_txt?></p>
			<div class="rand_bottom_bar">
                <?php if( (int)$row->id !== (int)auth()->id ){ ?>
                <button id="like_btn<?php echo $row->id?>" class="btn waves-effect like" data-ajax-post="/useractions/like" data-ajax-params="userid=<?php echo $row->id?>&username=<?php echo $row->username?>" data-ajax-callback="callback_like_interest">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"></path></svg>
                </button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>