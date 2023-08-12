<!-- Dropdown language -->
<span class="dt_foot_langs">
	<a class="modal-trigger" href="#modal_langs"><?php echo __( 'Language' );?> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M7,15L12,10L17,15H7Z" /></svg></a>
</span>

<div id="modal_langs" class="modal modal-sm" tabindex="0">
	<div class="modal-content">
		<h6 class="bold"><?php echo __( 'Language' );?></h6>
		<ul class="browser-default dt_lang_modal">
			<?php
			$language = Dataset::load('language');
			if (isset($language) && !empty($language)) {
				foreach ($language as $key => $val) {
					if ($config->{$key} == 1) {
					?>
				<li <?php if( GetActiveLang() == $key ){ echo 'class="active"';}?>><a href="?language=<?php echo $key;?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" /></svg> <?php echo $val;?></a></li>
			<?php } } } ?>
		</ul>
	</div>
</div>