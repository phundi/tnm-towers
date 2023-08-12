<?php global $site_url;?>

<div class="col l3 m6 s12 success_story_item" data-uid="<?php echo $story->id;?>">
    <div class="card">
        <div class="qd_story_card_usr_imgs">
            <a href="<?php echo $site_url;?>/story/<?php echo $story->id. '_'. url_slug($story->quote);?>" data-ajax="/story/<?php echo $story->id. '_'. url_slug($story->quote);?>">
                <img src="<?php echo $story->user->avater->avater;?>" alt="<?php echo $story->user->username;?>">
            </a>
            <a href="<?php echo $site_url;?>/story/<?php echo $story->id. '_'. url_slug($story->quote);?>" data-ajax="/story/<?php echo $story->id. '_'. url_slug($story->quote);?>">
                <img src="<?php echo $story->story_user->avater->avater;?>" alt="<?php echo $story->story_user->username;?>">
            </a>
			<svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" /></svg>
        </div>
        <div class="qd_story_card_usr_info">
            <a href="<?php echo $site_url;?>/story/<?php echo $story->id. '_'. url_slug($story->quote);?>" data-ajax="/story/<?php echo $story->id . '_'. url_slug($story->quote);?>">
                <div class="qd_story_card_usr_name truncate"><?php echo ($story->user->first_name ) ;?></div>
				<svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" /></svg>
                <div class="qd_story_card_usr_name truncate"><?php echo ($story->story_user->first_name) ;?></div>
            </a>
            <!--<p><?php echo $story->quote;?></p>-->
        </div>
		<div class="time">
			<time><?php echo $story->story_date;?></time>
		</div>
		<div class="valign-wrapper qd_story_card_usr_foot">
			<a class="btn" href="<?php echo $site_url;?>/story/<?php echo $story->id. '_'. url_slug($story->quote);?>" data-ajax="/story/<?php echo $story->id. '_'. url_slug($story->quote);?>"><?php echo __( 'View Details' );?></a>
		</div>
    </div>
</div>