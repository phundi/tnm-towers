<?php global $site_url;?>
<div class="col l4 m4 s6 xs12" data-liked-uid="<?php echo $value->id?>">
    <div class="dt_live_users center" onclick="RunLiveAgora('<?php echo $value->stream_name?>','post_live_video','<?php echo $value->agora_token?>',<?php echo $value->id?>,`<?php echo ($value->user_data->first_name !== '' ) ? $value->user_data->first_name . ' ' . $value->user_data->last_name : $value->user_data->username;?>`)">
        <?php if ($value->user_id == auth()->id || auth()->admin == 1) { ?>
            <!-- <button type="button" class="btn btn-flat close waves-effect modal-close pull-right" onclick="RemoveLiveVideo(<?php echo $value->id?>,'hide')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"></path></svg>
            </button> -->
        <?php } ?>
		<p class="time ajax-time age" title="<?php echo $value->created_at;?>"><?php echo get_time_ago( strtotime($value->created_at) );?></p>
		<div class="badge"><?php echo __('Live');?></div>
        <div class="card-image">
			<img src="<?php echo $value->image; ?>" alt="<?php echo $value->user_data->username?>">
        </div>
        <div class="card-content heart-particle-">
            <h3><?php echo ($value->user_data->first_name !== '' ) ? $value->user_data->first_name . ' ' . $value->user_data->last_name : $value->user_data->username;?></h3>
            <p>( <?php echo (isset(Dataset::load('countries')[$value->user_data->country]) ) ? Dataset::load('countries')[$value->user_data->country]['name'] : $value->user_data->country;?> )</p>
        </div>
    </div>
</div>