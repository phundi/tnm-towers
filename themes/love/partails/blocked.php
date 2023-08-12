<?php global $site_url;?>
<div class="unblock_card" id="blocked_user_<?php echo $row->id;?>">
	<div class="avatar">
		<img src="<?php echo GetMedia($row->avater);?>" alt="<?php echo $row->full_name;?>" class="circle">
	</div>

	<div class="info">
		<span class="black-text truncate bold"><?php echo $row->full_name;?></span>

		<a class="btn waves-effect btn_primary unblock" data-ajax-post="/useractions/unblock" data-ajax-params="userid=<?php echo $row->id;?>" data-ajax-callback="callback_unblock_hide" class="block_text"><?php echo __( 'Unblock' );?></a>
	</div>
</div>