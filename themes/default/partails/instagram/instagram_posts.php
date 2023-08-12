<div class="col s12 m6">
    <div class="insta_post">
        <img class="card-img-top" src="<?php echo($post['media_image']) ?>" alt="Card image cap" width="100%">
        <div class="card-body">
            <?php if ($post['media_type'] == 'VIDEO') { ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="dt_prof_ply_ico"><path fill="currentColor" d="M10,16.5V7.5L16,12M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path></svg>
            <?php } ?>
            <h5 class="card-title"><?php echo(__('type').': '.($post['media_type'] == 'IMAGE' ? __('image') : ($post['media_type'] == 'VIDEO' ? __('video') : __('album')))); ?></h5>
			<div class="foot">
				<?php if (!empty($post['imported']) && $post['imported'] > 0) { ?>
					<a href="javascript:void(0)" class="btn btn-secondary" disabled><?php echo(__('imported')); ?></a>
				<?php }else{ ?>
					<a href="javascript:void(0)" id="btn_import_<?php echo($post['id']) ?>" class="btn btn_primary" onclick="ImportInstagramPost(this,'<?php echo($post['id']) ?>')"><?php echo(__('import')); ?></a>
					<div>
						<label for="check-data-<?php echo($post['id']) ?>">
							<input type="checkbox" id="check-data-<?php echo($post['id']) ?>" onchange="checkbox_changed()" class="import-checkbox filled-in" data-id="<?php echo($post['id']) ?>">
							<span></span>
						</label>
					</div>
				<?php } ?>
			</div>
        </div>
    </div>
</div>