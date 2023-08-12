<!-- Dropdown language -->
<span class="dt_foot_langs">
	<a class="modal-trigger" href="#modal_langs">
		<svg xmlns="http://www.w3.org/2000/svg" width="16.66" height="16.66" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg> <?php echo __( 'Language' );?>
	</a>
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