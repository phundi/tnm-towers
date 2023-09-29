<?php global $db,$_LIBS; ?>
<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<!-- Pro Users  -->

<?php
$_SESSION['_lat'] = $profile->lat;
$_SESSION['_lng'] = $profile->lng;
$_age_from = !empty($data['find_match_data']) && !empty($data['find_match_data']['age_from']) ? $data['find_match_data']['age_from'] : 18;
$_age_to = !empty($data['find_match_data']) && !empty($data['find_match_data']['age_to']) ? $data['find_match_data']['age_to'] : 98;
$_located = !empty($data['find_match_data']) && !empty($data['find_match_data']['located']) ? $data['find_match_data']['located'] : 125;
$_gender = !empty($data['find_match_data']) && !empty($data['find_match_data']['gender']) ? $data['find_match_data']['gender'] : array();
$_location = '';
$_gender_text = '';
	$_gender_ = array();
	$gender = Dataset::load('gender');
	if(!empty($_gender) && count($_gender) != count($gender)){
		//$_gender = @implode(',', $_gender);
		foreach( $_gender as $key => $value ) {
			$_gender_[$value] = Dataset::gender()[$value];
		}
		$_gender_text = implode(',',$_gender_);
	}else{
		$_gender_text = __('All');
	}
?>

<ul class="collapsible dt_new_home_filter" id="home_filters">
	<div class="container">
		<div class="dt_home_filters_head">
			
			<button id="home_filters_close" class="btn main_fltr_close pull right">
				<?php echo __('Close');?> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
			</button>
		</div>
		<div class="filter_tabs_parent row">
			<ul class="tabs filter_tabs">
				<li class="tab">
					<a class="active" href="#basic">
						<svg xmlns="http://www.w3.org/2000/svg" width="66" height="17" viewBox="0 0 66 17"> <g id="Group_8834" data-name="Group 8834" transform="translate(-266.936 -201.15)"> <rect id="Rectangle_3373" data-name="Rectangle 3373" width="17" height="16" transform="translate(266.936 201.15)" fill="currentColor"/> <circle id="Ellipse_331" data-name="Ellipse 331" cx="8.5" cy="8.5" r="8.5" transform="translate(289.936 201.15)" fill="currentColor"/> <path id="Polygon_5" data-name="Polygon 5" d="M10,0,20,17H0Z" transform="translate(312.936 201.15)" fill="currentColor"/> </g> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Basic');?>
					</a>
				</li>
				<li class="tab" style="display: none;">
					<a href="#looks">
						<svg xmlns="http://www.w3.org/2000/svg" width="29.585" height="27.208" viewBox="0 0 29.585 27.208"> <g id="Group_8837" data-name="Group 8837" transform="translate(-580.386 -196.85)"> <circle id="Ellipse_332" data-name="Ellipse 332" cx="11" cy="11" r="11" transform="translate(584 201)" fill="currentColor" opacity="0.5"/> <path id="Path_215764" data-name="Path 215764" d="M580.386,224.058h8.733s-2.744-17.216,6.238-18.214a76.247,76.247,0,0,1,0-8.982s-11.214-.739-13.748,9.482C580.561,210.974,580.386,224.058,580.386,224.058Z" fill="currentColor"/> <path id="Path_215765" data-name="Path 215765" d="M595.356,224.058h-8.733s2.744-17.216-6.238-18.214a76.247,76.247,0,0,0,0-8.982s11.214-.739,13.748,9.482C595.182,210.974,595.356,224.058,595.356,224.058Z" transform="translate(14.614)" fill="currentColor"/> </g> </svg>&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="26.014" height="27.384" viewBox="0 0 26.014 27.384"> <g id="Group_8836" data-name="Group 8836" transform="translate(-619.841 -196.85)"> <circle id="Ellipse_333" data-name="Ellipse 333" cx="11" cy="11" r="11" transform="translate(622 202.234)" fill="currentColor" opacity="0.5"/> <path id="Path_215766" data-name="Path 215766" d="M619.612,212s-5.182-15.272,11.324-17.384c1.919,7.869,1.919,9.213,1.919,9.213l-8.637,1.344-1.152,7.869Z" transform="translate(1 2.234)" fill="currentColor"/> <path id="Path_215767" data-name="Path 215767" d="M632.084,212s5.182-15.272-11.324-17.384c-1.919,7.869-1.919,9.213-1.919,9.213l8.637,1.344,1.152,7.869Z" transform="translate(13 2.234)" fill="currentColor"/> </g> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Looks');?>
					</a>
				</li>
				<li class="tab" style="display: none;">
					<a href="#background">
						<svg xmlns="http://www.w3.org/2000/svg" width="21.405" height="23.299" viewBox="0 0 21.405 23.299"> <path id="Path_5417" data-name="Path 5417" d="M3710.164,10486.625v1.147a19.122,19.122,0,0,1-2.192,8.952l-.264.574-2.008-1.147a17.086,17.086,0,0,0,2.157-7.8l.012-.574v-1.147Zm-6.886-3.443h2.3v5.051a14.6,14.6,0,0,1-3.1,8.608l-.264.344-1.779-1.378a12.646,12.646,0,0,0,2.835-7.574l.012-.46Zm1.147-4.591a5.5,5.5,0,0,1,4.063,1.722,5.654,5.654,0,0,1,1.676,4.018h-2.3a3.443,3.443,0,0,0-6.887,0v3.442a10.6,10.6,0,0,1-2.6,6.887l-.241.229-1.664-1.606a7.844,7.844,0,0,0,2.2-5.165l.011-.345v-3.442a5.654,5.654,0,0,1,1.676-4.018A5.5,5.5,0,0,1,3704.426,10478.591Zm0-4.591a10.2,10.2,0,0,1,7.3,2.983,10.421,10.421,0,0,1,3.03,7.347v3.442a23.806,23.806,0,0,1-.688,5.739l-.161.573-2.215-.573a20.538,20.538,0,0,0,.758-5.051l.011-.688v-3.442a8.125,8.125,0,0,0-1.194-4.247,8.334,8.334,0,0,0-3.236-2.983,9.28,9.28,0,0,0-4.315-.8,7.686,7.686,0,0,0-4.1,1.606l-1.641-1.606A10.216,10.216,0,0,1,3704.426,10474Zm-8.068,3.9,1.629,1.606a8.222,8.222,0,0,0-1.6,4.591v2.525a8.235,8.235,0,0,1-.872,3.673l-.172.345-2-1.148a5.921,5.921,0,0,0,.746-2.524v-2.64A10.347,10.347,0,0,1,3696.357,10477.9Z" transform="translate(-3693.35 -10474)" fill="currentColor"/> </svg>&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="21.149" height="21.001" viewBox="0 0 21.149 21.001"> <path id="Path_4935" data-name="Path 4935" d="M3416.05,1822.683h5.824a19.048,19.048,0,0,0,3.1,9.438,10.65,10.65,0,0,1-8.927-9.438Zm0-2.125a10.65,10.65,0,0,1,8.927-9.438,19.048,19.048,0,0,0-3.1,9.438Zm21.149,0h-5.824a19.043,19.043,0,0,0-3.1-9.438,10.65,10.65,0,0,1,8.927,9.438Zm0,2.125a10.65,10.65,0,0,1-8.927,9.438,19.043,19.043,0,0,0,3.1-9.438Zm-13.2,0h5.25a4.46,4.46,0,1,1-5.25,0Zm0-2.125a4.46,4.46,0,1,1,5.25,0Z" transform="translate(-3416.05 -1811.12)" fill="currentColor"/> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Background');?>
					</a>
				</li>
				<li class="tab" style="display: none;">
					<a href="#lifestyle">
						<svg xmlns="http://www.w3.org/2000/svg" width="56.643" height="29.066" viewBox="0 0 56.643 29.066"> <g id="Group_8840" data-name="Group 8840" transform="translate(-1169.067 -190.435)"> <path id="Union_8" data-name="Union 8" d="M6381.07,24H6356l.008-.006a42.8,42.8,0,0,1,11.924-4.657V11.5h2v3h0V18.9a48.431,48.431,0,0,1,9.18-.886c.651,0,1.312.013,1.961.04V24Zm-19.049-13.372A4.345,4.345,0,0,0,6358.962,12h-.032V11a10.008,10.008,0,0,1,9-9.95V1a1.033,1.033,0,0,1,.29-.709,1.015,1.015,0,0,1,1.42,0,1.037,1.037,0,0,1,.288.709v.051a10.009,10.009,0,0,1,9,9.95v.946a5.576,5.576,0,0,0-3.417-1.228,4.639,4.639,0,0,0-3.1,1.132,4.86,4.86,0,0,0-7.062.149A5.3,5.3,0,0,0,6362.021,10.629Z" transform="translate(-5185.932 195.5)" fill="currentColor"/> <path id="Path_215769" data-name="Path 215769" d="M1170.067,188.435V194.9s6.116-.961,7.165-6.465Z" transform="translate(-1 2)" fill="currentColor"/> <path id="Path_6517" data-name="Path 6517" d="M3645.71,6525.79A3.364,3.364,0,0,0,3648,6527a3.237,3.237,0,0,0,1.24-.28l.39-.15a5.7,5.7,0,0,1,2.37-.57,5.157,5.157,0,0,1,3.49,1.58l.22.21-1.42,1.42A3.364,3.364,0,0,0,3652,6528a3.237,3.237,0,0,0-1.24.28l-.39.15a5.7,5.7,0,0,1-2.37.57,5.157,5.157,0,0,1-3.49-1.58l-.22-.21ZM3642,6511a3,3,0,0,0,6,0h6a.99.99,0,0,1,1,1v7a.99.99,0,0,1-1,1h-4v-2h3v-5h-3.42l-.01.04a4.978,4.978,0,0,1-4.35,2.95l-.22.01a5.027,5.027,0,0,1-2.72-.8,5.106,5.106,0,0,1-1.85-2.16l-.01-.04H3637v5h3v9h3v2h-4a.99.99,0,0,1-1-1v-8h-2a.99.99,0,0,1-1-1v-7a.99.99,0,0,1,1-1Zm3.71,10.79A3.364,3.364,0,0,0,3648,6523a3.237,3.237,0,0,0,1.24-.28l.39-.15a5.7,5.7,0,0,1,2.37-.57,5.157,5.157,0,0,1,3.49,1.58l.22.21-1.42,1.42A3.364,3.364,0,0,0,3652,6524a3.237,3.237,0,0,0-1.24.28l-.39.15a5.7,5.7,0,0,1-2.37.57,5.157,5.157,0,0,1-3.49-1.58l-.22-.21Z" transform="translate(-2430 -6312.15)" fill="currentColor"/> </g> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Lifestyle');?>
					</a>
				</li>
				<li class="tab" style="display: none;">
					<a href="#tab_more">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"> <path id="Path_215771" data-name="Path 215771" d="M3253,6250a10,10,0,1,1,10-10A10,10,0,0,1,3253,6250Zm2-12v4h2v-4Zm-6,0v4h2v-4Z" transform="translate(-3243 -6230)" fill="currentColor"/> </svg>&nbsp;&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"> <path id="Path_215772" data-name="Path 215772" d="M3757,6418a10,10,0,1,1,10-10A10,10,0,0,1,3757,6418Zm-1-15v4h2v-4Zm3,5v4h2v-4Zm-6,0v4h2v-4Z" transform="translate(-3747 -6398)" fill="currentColor"/> </svg>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('More');?>
					</a>
				</li>
			</ul>
			<div id="basic" class="col s12 active search_filters">
				<form onsubmit="return false;" onkeypress="return event.keyCode != 13;">
					<div class="row">
						<div class="col s12 m2">
							<h5><?php echo __('Gender');?></h5>
							<?php
								$all_gender = array();
								$gender = Dataset::load('gender');
								$all_checked = '';
								if (count($_gender) == count($gender)) {
									$all_checked = 'checked';
								}
								if (isset($gender) && !empty($gender)) {
									foreach ($gender as $key => $val) {
										$all_gender[] = $key;
										$_checked = '';
										if( !empty($_gender)){
											if(in_array($key, $_gender) && empty($all_checked)){
												$_checked = 'checked';
											}
										}
										echo '<p><label><input type="checkbox" class="_gender filled-in" value="' . $key . '" data-txt="' . $val . '" '.$_checked.'  data-vx="' . $key . '"/><span  class="_gender_text" data-txt="' . $val . '">' . $val . '</span></label></p>';
									}
								}
								if (empty($_gender)) {
									$all_checked = 'checked';
								}
							?>
							<p><label><input type="checkbox" class="_gender filled-in" value="<?php echo implode(",",$all_gender);?>" data-vx="_all_" data-txt="<?php echo __('All');?>" <?php echo $all_checked;?> /><span class="_gender_text" data-txt="<?php echo __('All');?>"><?php echo __('All');?></span></label></p>
						</div>
						
					
						
						<div class="col s12 m3">
							<h5><?php echo __('Age Range');?></h5>
							<div class="row r_margin">
								<div class="input-field col s6 no_margin_top">
									<select id="_age_from" class="_age_from" onchange="validateAge();">
										<?php for($i = 18 ; $i <= 90 ; $i++ ){
											$selected = '';
											if (!empty($data['find_match_data']) && !empty($data['find_match_data']['age_from']) && $data['find_match_data']['age_from'] == $i) {
												$selected = 'selected';
											}
											?>
											<option value="<?php echo $i;?>" <?php echo $selected; ?> ><?php echo $i;?></option>
										<?php }?>
									</select>
								</div>
								<div class="input-field col s6 no_margin_top">
									<select id="_age_to" class="_age_to" onchange="validateAge();">
										<?php for($i = 90 ; $i >= 18 ; $i-- ){
											$selected = '';
											if (!empty($data['find_match_data']) && !empty($data['find_match_data']['age_to']) && $data['find_match_data']['age_to'] == $i) {
												$selected = 'selected';
											}
											else if(empty($data['find_match_data']['age_to']) && $i == 90){
												$selected = 'selected';
											}
											?>
											<option value="<?php echo $i;?>" <?php echo $selected; ?> ><?php echo $i;?></option>
										<?php }?>
									</select>
								</div>
							</div>
						</div>
						
						<?php if($config->filter_by_country == 'ALL' || ($config->filter_by_country == 'PRO' && ($profile->is_pro == 1 || $config->pro_system == 0))){
						$active_show_me_to = $profile->show_me_to;
						?>
						<div class="col s12 m4">
							<h5><?php echo __('District');?></h5>
							<div class="valign-wrapper dt_hm_filtr_loc">
								<label style="display: none;" title="<?php echo __('My location');?>">
									<input type="checkbox" class="filled-in" id="is_my_location">
									<b><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" /></svg></b>
								</label>
								
								<select id="my_country" name="my_country" data-country="<?php echo $profile->country;?>" <?php if(!empty($data['find_match_data']) && !empty($data['find_match_data']['located'])) {?>disabled="disabled"<?php }?> <?php if($config->filter_by_cities == 1 && !empty($config->geo_username)){ ?>onchange="ChangeCountryKey(this)"<?php } ?>>
									<option value="all" data-code="all">All Districts</option>;
									<?php
										
										foreach( ["Balaka", "Blantyre", "Chikwawa", "Chiradzulo", "Chitipa", "Dedza", "Dowa", "Karonga", "Kasungu", "Likoma", "Lilongwe", "Machinga", "Mangochi", "Mchinji", "Mulanje", "Mwanza", "Mzimba", "Neno", "Nkhatabay", "Nkhotakota", "Nsanje", "Ntcheu", "Ntchisi", "Phalombe", "Rumphi", "Salima", "Thyolo", "Zomba"] as $key ){
											echo '<option value="'. $key .'" data-code="'. $key .'">'. $key .'</option>';
										}
									?>
								</select>
							</div>
							<?php if (false && $config->filter_by_cities == 1 && !empty($config->geo_username)) { ?>
							<div style="position: relative;margin-top: 10px;">
								<input type="hidden" class="city_country_key" name="city_country_key" value="<?php echo($city_country_key); ?>">
								<input type="text" name="city" placeholder="<?php echo __( 'City' );?>" <?php if($config->filter_by_cities == 1 && !empty($config->geo_username)){ ?>onkeyup="SearchForCity(this)"<?php } ?> class="selected_city" value="<?php echo(!empty($data['find_match_data']) && !empty($data['find_match_data']['city']) ? $data['find_match_data']['city'] : '') ?>">
								<div class="city_search_result"></div>
							</div>
							<?php } ?>
						</div>
						<?php } ?>

					<?php if (false) { ?>
						<div class="col s12 m3">
							<h5><?php echo __('Distance');?></h5>
							<p class="range-field">
								<input type="range" min="1" max="250" value="<?php echo !empty($data['find_match_data']) && !empty($data['find_match_data']['located']) ? $data['find_match_data']['located'] : 125 ;?>" id="_located" <?php if(!empty($data['find_match_data']) && empty($data['find_match_data']['located'])) {?>disabled="disabled"<?php }?> style="direction: ltr!important;"/>
								<b class="range"><span>0km</span><span>250km</span></b>
							</p>
						</div>

					</div>
						<div class="dt_home_filters_head" 
						style="float: right; margin-top: opx; padding-top: 0px !important; margin-right: 0px !important; padding-right: 0px !important;" >
							<p  
							><span id="gender"><?php echo $_gender_text;?></span> <?php echo __('who ages');?> <span id="age_from"><?php echo $_age_from;?></span>-<span id="age_to"><?php echo $_age_to;?></span> <p class="located_at"> &nbsp;
											<?php if (!empty($data['find_match_data']) && !empty($data['find_match_data']['located'])) { ?>
												<?php echo __('located within');?> <span id="located"><?php echo $_located;?></span> <?php echo $config->default_unit;?>
											<?php }elseif (!empty($data['find_match_data']) && !empty($data['find_match_data']['country']) && !empty(Dataset::load('countries'))) {
												if ($data['find_match_data']['country'] == 'all') { ?>
													<?php echo __('located_at');?> <span id="located"><?php echo __('all_countries');?></span>
											<?php 	}elseif (in_array($data['find_match_data']['country'], array_keys(Dataset::load('countries')))) { ?>
												<?php echo __('located_at');?> <span id="located"><?php echo Dataset::load('countries')[$data['find_match_data']['country']]['name'];?></span>
											<?php } } ?>
											</p></p>
						
						</div>
					<?php 	} ?>
						<br />
					<input type="hidden" id="_lat" value="<?php echo $profile->lat;?>">
					<input type="hidden" id="_lng" value="<?php echo $profile->lng;?>">
					<div class="btn_wrapper">
						<button onclick="page=1" class="btn waves-effect btn_glossy btn-flat btn-large waves-light btn-find-matches-search2" type="button" id="btn_search_basic2" disabled><?php echo __('Search your match');?></button>
						<button class="btn waves-effect btn_glossy btn-flat btn-small waves-light" type="button" onclick="resetSearchData()"><?php echo __('reset');?></button>
					</div>
				</form>
			</div>
			<div id="looks" class="col s12 search_filters" style="display: none;">
				<form onsubmit="return false;" onkeypress="return event.keyCode != 13;">
					<div class="row">
						<div class="col s12 m5">
							<h5><?php echo __('Height');?></h5>
							<div class="input-field col s6">
								<select class="height_from">
									<?php 
									$height = Dataset::load('height');
									if (isset($height) && !empty($height)) {
										foreach ($height as $key => $val) {
											if ($key < 170) {
												$selected = '';
												if (!empty($data['find_match_data']) && !empty($data['find_match_data']['height_from']) && $data['find_match_data']['height_from'] == $key) {
													$selected = 'selected';
												}
												else if(empty($data['find_match_data']['height_from']) && $key == 139){
													$selected = 'selected';
												}
										 ?>
											<option value="<?php echo($key) ?>" <?php echo $selected; ?>><?php echo($val) ?></option>
									<?php } } } ?>
								</select>
							</div>
							<div class="input-field col s6">
								<select class="height_to">
									<?php 
									$height = Dataset::load('height');
									if (isset($height) && !empty($height)) {
										foreach ($height as $key => $val) {
											if ($key >= 170) {
												$selected = '';
												if (!empty($data['find_match_data']) && !empty($data['find_match_data']['height_to']) && $data['find_match_data']['height_to'] == $key) {
													$selected = 'selected';
												}
												else if(empty($data['find_match_data']['height_to']) && $key == 220){
													$selected = 'selected';
												}
										 ?>
											<option value="<?php echo($key) ?>" <?php echo $selected; ?>><?php echo($val) ?></option>
									<?php } } } ?>
								</select>
							</div>
						</div>
						<div class="col s12 m1"></div>
						<div class="col s12 m6">
							<h5><?php echo __('Body type');?></h5>
							<?php
								$body = Dataset::load('body');
								if (isset($body) && !empty($body)) {
									foreach ($body as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['body']) && in_array($key, $data['find_match_data']['body'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6 m3"><label><input type="checkbox" class="_body filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
					</div>

					
					<div class="btn_wrapper">
						<button style="margin: 10px !important;" class="btn waves-effect btn_glossy btn-flat btn-large waves-light btn-find-matches-search" id="btn_search_looks" type="button" disabled><?php echo __('Search your match');?></button>
						<button style="margin: 10px !important;" class="btn waves-effect btn_glossy btn-flat btn-small waves-light" type="button" onclick="resetSearchData()"><?php echo __('reset');?></button>
					</div>
				</form>
			</div>
			<div id="background" class="col s12 search_filters" style="display: none;">
				<form onsubmit="return false;" onkeypress="return event.keyCode != 13;">
					<div class="row">
						<div class="col s12 m4" style="display: none;">
							<h5><?php echo __('Language');?></h5>
							<div class="input-field col s12">
								<select class="_language">
									<?php
										$language = Dataset::load('language');
										$lang_html = '';
										$lang_ids = array();
										if (isset($language) && !empty($language)) {
											$all = '';
											if (!empty($data['find_match_data']) && !empty($data['find_match_data']['language']) && count($data['find_match_data']['language']) >= count($language)) {
												$all = 'selected';
											}
											elseif (!empty($data['find_match_data']) && empty($data['find_match_data']['language'])) {
												$all = 'selected';
											}
											foreach ($language as $key => $val) {
												if ($config->{$key} == 1) {
													$selected = '';
													if (!empty($data['find_match_data']) && !empty($data['find_match_data']['language']) && in_array($key, $data['find_match_data']['language'])) {
														$selected = 'selected';
													}
													$lang_ids[] = $key;
													$lang_html .= '<option value="' . $key . '" data-txt="' . $val . '" '.$selected.'>';
													$lang_html .= $val;
													$lang_html .= '</option>';
												}
											}
											echo '<option value="'.@implode(',', array_keys($language)) .'" data-txt="All" '.$all.'>'. __('ALL') .'</option>';
											echo $lang_html;
										}
									?>
								</select>
							</div>
						</div>
						<div class="col s12 m4">
							<h5><?php echo __('Tribe');?></h5>
							<?php
								$ethnicity = Dataset::load('ethnicity');
								if (isset($ethnicity) && !empty($ethnicity)) {
									foreach ($ethnicity as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['ethnicity']) && in_array($key, $data['find_match_data']['ethnicity'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6"><label><input type="checkbox" class="_ethnicity filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
						<div class="col s12 m4">
							<h5><?php echo __('Religion');?></h5>
							<?php
								$religion = Dataset::load('religion');
								if (isset($religion) && !empty($religion)) {
									foreach ($religion as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['religion']) && in_array($key, $data['find_match_data']['religion'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6"><label><input type="checkbox" class="_religion filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
					</div>
					<div class="btn_wrapper">
						<button class="btn waves-effect btn_glossy btn-flat btn-large waves-light btn-find-matches-search" id="btn_search_background" type="button" disabled><?php echo __('Search your match');?></button>
						<button class="btn waves-effect btn_glossy btn-flat btn-small waves-light" type="button" onclick="resetSearchData()"><?php echo __('reset');?></button>
					</div>
				</form>
			</div>
			<div id="lifestyle" class="col s12 search_filters" style="display: none;">
				<form onsubmit="return false;" onkeypress="return event.keyCode != 13;">
					<div class="row">
						<div class="col s12 m2">
							<h5><?php echo __('Status');?></h5>
							<?php
								$relationship = Dataset::load('relationship');
								if (isset($relationship) && !empty($relationship)) {
									foreach ($relationship as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['relationship']) && in_array($key, $data['find_match_data']['relationship'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6 m12"><label><input type="checkbox" name="relationship" class="_relationship filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
						<div class="col s12 m3">
							<h5><?php echo __('Smokes');?></h5>
							<?php
								$smoke = Dataset::load('smoke');
								if (isset($smoke) && !empty($smoke)) {
									foreach ($smoke as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['smoke']) && in_array($key, $data['find_match_data']['smoke'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6 m12"><label><input type="checkbox" name="smoke" class="_smoke filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
						<div class="col s12 m7">
							<h5><?php echo __('Drinks');?></h5>
							<?php
								$drink = Dataset::load('drink');
								if (isset($drink) && !empty($drink)) {
									foreach ($drink as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['drink']) && in_array($key, $data['find_match_data']['drink'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6 m12"><label><input type="checkbox" name="drink" class="_drink filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
					</div>
					<div class="btn_wrapper">
						<button class="btn waves-effect btn_glossy btn-flat btn-large waves-light btn-find-matches-search" id="btn_search_lifestyle" type="button" disabled><?php echo __('Search your match');?></button>
						<button class="btn waves-effect btn_glossy btn-flat btn-small waves-light" type="button" onclick="resetSearchData()"><?php echo __('reset');?></button>
					</div>
				</form>
			</div>
			<div id="tab_more" class="col s12 search_filters" style="display: none;">
				<form onsubmit="return false;" onkeypress="return event.keyCode != 13;">
					<div class="row">
						<div class="col s12 m4">
							<h5><?php echo __('By Interest');?></h5>
							<div class="input-field">
								<input placeholder="<?php echo __('e.g., Singing');?>" id="interest" type="text" class="validate" value="<?php echo(!empty($data['find_match_data']) && !empty($data['find_match_data']['interest']) ? $data['find_match_data']['interest'] : '') ?>">
								<script>
									$(document).ready(function(){
										$('#interest').autocomplete({
											data: <?php echo json_encode(GetInterested());?>,
										});
									});
								</script>
							</div>
						</div>
						<div class="col s12 m5">
							<h5><?php echo __('Education');?></h5>
							<?php
								$education = Dataset::load('education');
								if (isset($education) && !empty($education)) {
									foreach ($education as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['education']) && in_array($key, $data['find_match_data']['education'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6"><label><input type="checkbox" name="education" class="_education filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
						<div class="col s12 m3" style="display: none;">
							<h5><?php echo __('Pets');?></h5>
							<?php
								$pets = Dataset::load('pets');
								if (isset($pets) && !empty($pets)) {
									foreach ($pets as $key => $val) {
										$selected = '';
										if (!empty($data['find_match_data']) && !empty($data['find_match_data']['pets']) && in_array($key, $data['find_match_data']['pets'])) {
											$selected = 'checked';
										}
										echo '<p class="col s6 m12"><label><input type="checkbox" name="pets" class="_pets filled-in" value="' . $key . '" data-txt="' . $val . '" '.$selected.'/><span>' . $val . '</span></label></p>';
									}
								}
							?>
						</div>
					</div>


					<?php
					$fields = GetUserCustomFields();
					$template = $theme_path . 'partails' . $_DS . 'profile-fields-search.php';
					$html = '';
					if (count($fields) > 0) {
						foreach ($fields as $key => $field) {
							ob_start();
							require($template);
							$html .= ob_get_contents();
							ob_end_clean();
						}
						echo '<div class="row">' . $html . '</div>';
						echo '<input name="custom_fields" type="hidden" value="1">';
					}
					?>

					<div class="btn_wrapper">
						<button class="btn waves-effect btn_glossy btn-flat btn-large waves-light btn-find-matches-search" id="btn_search_more" type="button" disabled><?php echo __('Search your match');?></button>
						<button class="btn waves-effect btn_glossy btn-flat btn-small waves-light" type="button" onclick="resetSearchData()"><?php echo __('reset');?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</ul>

<div class="container container-fluid container_new page-margin find_matches_cont">
	<?php if(IS_LOGGED && !empty($profile) && $profile->verified !== "1") { ?>
		<div class="alert alert-warning"><?php echo __('account_not_verified_text'); ?></div>
	<?php } ?>
	<div class="location_alert_update">
		<?php if(false && IS_LOGGED && !empty($profile) && (empty($profile->lat) || empty($profile->lng))) { ?>
			<div class="alert alert-warning"><?php echo __('please_enable_location'); ?></div>
		<?php } ?>
	</div>

    <?php
    if (IsThereAnnouncement() === true) {
        $announcement = GetHomeAnnouncements();
        ?>
        <div class="home-announcement">
            <div class="alert alert-success" style="background-color: white;">
                    <span class="close announcements-option" data-toggle="tooltip" onclick="Wo_ViewAnnouncement(<?php echo $announcement['id']; ?>);" title="<?php echo __('Hide');?>" style="float: right;cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"></path></svg>
                    </span>
                <?php echo $announcement['text']; ?>
            </div>
        </div>
        <!-- .home-announcement -->
    <?php } ?>

	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>

		<div class="col l6">
			<!-- Filters  -->
			<div class="dt_home_filters_prnt">
				<div class="dt_home_filters">
					<h5 id="nearme"><?php echo __('People Near Me');?></h5>
					<div class="dt_home_filters_head">
						<p><span><?php echo __('Search');?></span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14,12V19.88C14.04,20.18 13.94,20.5 13.71,20.71C13.32,21.1 12.69,21.1 12.3,20.71L10.29,18.7C10.06,18.47 9.96,18.16 10,17.87V12H9.97L4.21,4.62C3.87,4.19 3.95,3.56 4.38,3.22C4.57,3.08 4.78,3 5,3V3H19V3C19.22,3 19.43,3.08 19.62,3.22C20.05,3.56 20.13,4.19 19.79,4.62L14.03,12H14Z" /></svg></p>
					</div>
				</div>
			</div>
			<!-- End Filters  -->

            <?php
                $max_swaps = $config->max_swaps;
                $count_swipe_for_this_day = GetUserTotalSwipes(auth()->id);
                $last_swipe = $db->where('user_id', auth()->id)->orderBy('created_at','DESC')->get('likes', 1, array('created_at'));
                if(isset($last_swipe[0])) {
                    $raminhours = 24 - intval(date('H', time() - strtotime($last_swipe[0]['created_at'])));
                }else{
                    $raminhours = 24;
                }

                $warning_style='';
                $match_style='';
//                if($count_swipe_for_this_day >= $max_swaps) {
//                    $warning_style='';
//                    $match_style='hide';
//                }else{
//                    $warning_style='hide';
//                    $match_style='';
//                }
            ?>

            <!--<h5 id="max_swipes_reached" class="empty_state <?php echo $warning_style;?>">
                <svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m452 40h-24v-40h-40v40h-264v-40h-40v40h-24c-33.085938 0-60 26.914062-60 60v352c0 33.085938 26.914062 60 60 60h392c33.085938 0 60-26.914062 60-60v-352c0-33.085938-26.914062-60-60-60zm-392 40h24v40h40v-40h264v40h40v-40h24c11.027344 0 20 8.972656 20 20v48h-432v-48c0-11.027344 8.972656-20 20-20zm392 392h-392c-11.027344 0-20-8.972656-20-20v-264h432v264c0 11.027344-8.972656 20-20 20zm-216-245h40v125h-40zm0 165h40v40h-40zm0 0"/></svg>
                <p id="w_message"><?php echo str_replace('{0}',$raminhours, __('You reach the max of swipes per day. you have to wait {0} hours before you can redo likes Or upgrade to pro to for unlimited.') );?></p>
            </h5>-->

            <!-- Match Users  -->
            <div id="section_match_users" class="<?php echo $match_style;?>">
                <?php

                if (empty($data['matches'])) {
                    echo '<div class="dt_sections" style="margin: 14px 0 0;"><h5 id="_load_more" class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z"></path></svg>' . __('No users matched your search criteria') . '</h5></div>';
                } else {
                    ?>
                    <div class="dt_home_match_user">
                        <div class="valign-wrapper mtc_usr_avtr" id="avaters_item_container">
                            <?php echo $data['matches_img']; ?>
                        </div>
                        <div class="mtc_usr_details" id="match_item_container">
                            <?php echo $data['matches']; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

			<!-- End Match Users  -->

			<!-- Random Users  -->
            <?php if(!empty($data['random_users'])){ ?>
			<hr class="dt_home_rand_user_hr">
			<div class="dt_ltst_users" id="dt_ltst_users">
				<div class="dt_home_rand_user">
					<h4 class="bold" id="random_users_label"><?php echo __( 'Other users & profiles' );?></h4>
					<div class="row" id="random_users_container">
                        <?php echo $data['random_users']; ?>
					</div>
                    <?php if(!empty($data['random_users'])){ ?>
					<a href="javascript:void(0);" id="btn_load_more_random_users" data-lang-nomore="<?php echo __('No more users to show.');?>" data-ajax-post="/loadmore/random_users" data-ajax-params="page=2" data-ajax-callback="callback_load_more_random_users" class="btn waves-effect load_more"><?php echo __('Load more...');?></a>
					<a href="javascript:void(0);" onclick="nextPage();" style='display: none;' id="btn_load_more_match_users2" 
									data-lang-loadmore="<?php echo __('Load more...');?>" 
									data-lang-nomore="<?php echo __('No more users to show.');?>" 
									class="btn waves-effect load_more"><?php echo __('Load more...!');?></a>

					<?php } ?>
                </div>
			</div>
            
			<?php } else{ ?>
						
				<hr class="dt_home_rand_user_hr">
					<div class="dt_ltst_users" id="dt_ltst_users">
						<div class="dt_home_rand_user">
							<h4 class="bold" id="random_users_label"><?php echo __( 'No random users found' );?></h4>
							<div class="row" id="random_users_container">
							</div>
							<a href="javascript:void(0);" 
								style="display: none;" id="btn_load_more_random_users" data-lang-nomore="<?php echo __('No more users to show.');?>" data-ajax-post="/loadmore/random_users" data-ajax-params="page=2" data-ajax-callback="callback_load_more_random_users" class="btn waves-effect load_more"><?php echo __('Load more...');?></a>
							<a href="javascript:void(0);" onclick="nextPage();" style='display: none;' id="btn_load_more_match_users2" 
											data-lang-loadmore="<?php echo __('Load more...');?>" 
											data-lang-nomore="<?php echo __('No more users to show.');?>" 
											data-ajax-post="/loadmore/match_users?profiles=true" 
											data-ajax-params="page=2" 
											data-ajax-callback="callback_load_more_search_users" 
											class="btn waves-effect load_more"><?php echo __('Load more...!');?></a>

						</div>
					</div>				

				
			<?php } ?>
			<!-- End Random Users  -->

			<!-- Search Users  -->
			<div class="dt_ltst_users hide" id="latest_user">
				<div class="dt_home_rand_user">
					<div class="row" id="search_users_container">

					</div>
					<a href="javascript:void(0);" id="btn_load_more_search_users" data-lang-more="<?php echo __('Load more...');?>" data-lang-nomore="<?php echo __('view_no_more_to_show');?>" data-ajax-post="/loadmore/find_matches" data-ajax-params="page=2" data-ajax-callback="callback_load_more_search_users" class="btn waves-effect load_more hide"><?php echo __('Load more...');?></a>
				</div>
			</div>
		</div>
		<!-- End Search Users  -->
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
		</div>
	</div>
</div>
<script>
var page = 1;

function nextPage(){
	page += 1;
	//$("#btn_load_more_match_users2").attr('data-ajax-post', ('/loadmore/match_users?page=' + page));
	//$("#btn_load_more_match_users2").attr('data-ajax-params', ('page=' + page));
	//$("#btn_load_more_match_users2").attr('data-ajax-callback', "");

}

$(document).ready(function(){
	
	$('#my_country').on('change',() => {
		$('.located_at').html(`&nbsp;&nbsp;<?php echo __('located_at');?> <span id="located">${$("#my_country option:selected" ).text()}</span>`);
	});
	
	$( document ).on( 'change', '#_located', function(e){
        var valueSelected = this.value;
        $('.located_at').html(`&nbsp;&nbsp;<?php echo __('located within');?> <span id="located">${valueSelected}</span> <?php echo $config->default_unit;?>`);
    });

	setTimeout(function () {
		$('.btn-find-matches-search2').removeAttr('disabled');
		$("#_age_to").val(90);

	},1000);

	$('.located_at').html(`&nbsp;&nbsp;<?php echo __('located_at');?> <span id="located">${$("#my_country option:selected" ).text()}</span>`);

	$('#_located').prop("disabled", true);
	$('#_located').val( window.located );


	$('#my_country').removeAttr( 'disabled' );
	$('#my_country').prop("disabled", false);
	$('#my_country').formSelect();


	$( document ).on( 'click', '.btn-find-matches-search2, #btn_load_more_match_users2', function(e) {
            e.preventDefault();
            var formData = new FormData();
			//page = 1;

            // search_basic
            var gender = [];
            $("._gender:checked").each ( function() {
                gender.push($(this).val());
            });
            if(gender.length > 0) {
                formData.append('_gender', gender);
            }
            formData.append( '_age_from', $('._age_from').find(":selected").val() );
            formData.append( '_age_to', $('._age_to').find(":selected").val() );
            if( $('#is_my_location').prop('checked') !== false) {
                formData.append( '_located', $('#_located').val() );
                formData.append( '_lat', $('#_lat').val() );
                formData.append( '_lng', $('#_lng').val() );
            }
            else{
                formData.append( '_my_country', $('#my_country').find(":selected").val() );
            }
            formData.append('_location', '');

            // search_looks
            var body = [];
            $("._body:checked").each ( function() {
                body.push($(this).val());
            });
            if(body.length > 0) {
                formData.append('_body', body);
            }
            formData.append( '_height_from', $('.height_from').find(":selected").val() );
            formData.append( '_height_to', $('.height_to').find(":selected").val() );


            // search_background
            var ethnicity = [];
            $("._ethnicity:checked").each ( function() {
                ethnicity.push($(this).val());
            });
            var religion = [];
            $("._religion:checked").each ( function() {
                religion.push($(this).val());
            });
            if(ethnicity.length > 0) {
                formData.append('_ethnicity', ethnicity);
            }
            if(religion.length > 0) {
                formData.append('_religion', religion);
            }
            if (!$('._language').find(":selected").val().includes(",")) {
                formData.append( '_language', $('._language').find(":selected").val() );
            }
            


            // search_lifestyle
            var relationship = [];
            $("._relationship:checked").each ( function() {
                relationship.push($(this).val());
            });
            var smoke = [];
            $("._smoke:checked").each ( function() {
                smoke.push($(this).val());
            });
            var drink = [];
            $("._drink:checked").each ( function() {
                drink.push($(this).val());
            });
            if(relationship.length > 0){
                formData.append( '_relationship', relationship );
            }
            if(smoke.length > 0){
                formData.append( '_smoke', smoke );
            }
            if(drink.length > 0){
                formData.append( '_drink', drink );
            }


            // search_more
            var education = [];
            $("._education:checked").each ( function() {
                education.push($(this).val());
            });
            var pets = [];
            $("._pets:checked").each ( function() {
                pets.push($(this).val());
            });
            if(education.length > 0){
                formData.append( '_education', education );
            }
            if(pets.length > 0){
                formData.append( '_pets', pets );
            }
            formData.append( '_interest', $('#interest').val() );
            $(".profile_custom_data_field").each ( function() {
                formData.append( $(this).attr('data-name'), $(this).val() );
            });
            if( $(".profile_custom_data_field").length > 0 ){
                formData.append( 'custom_profile_data', 'true' );
            }
            if( $(".selected_city").length > 0 ){
                formData.append( 'city', $(".selected_city").val() );
            }


            formData.append( 'page', page );
            var url = window.ajax + '/loadmore/match_users?page=' + page;
            $.ajax({
                url: url,
                type: "POST",
                async: false,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                timeout: 60000,
                dataType: false,
                success: function(result) {
					
                    callback_load_more_search_users2( result );
                }
            });
        });


		
function callback_load_more_search_users2( result ) {

    window.ajaxsend = true;
    var btn_text = $('#btn_load_more_search_users').html();
    $('#btn_load_more_search_users').removeAttr('data-ajax-params');
    $('#_load_more').remove();
    if (result.status == 200) {
		$('#section_match_users').hide();
		$('#nearme').html('All Searched Users');

		if (result.html_all_matches.length == 0){

			$('#btn_load_more_random_users').hide();
			$('#btn_load_more_match_users2').html("No search users found");
			$("#random_users_container").html('');
			
		}else{
			$("#random_users_label").show();
			$('#btn_load_more_random_users').hide();
			$('#btn_load_more_match_users2').show();
			$('#btn_load_more_match_users2').html("Load more ..!");

			$("#random_users_label").hide();
			$('#btn_load_more_random_users').html('');
		
		
			//var html = $("#random_users_container").html + result.html_all_matches;
			//if (parseInt(page) > 1){
			//		html += result.html_all_matches;
			//}
			
			$("#random_users_container").show();
			if(page == 1){
				$("#random_users_container").html(result.html_all_matches);
			}else {
				$("#random_users_container").append(result.html_all_matches);
			}

		}

        $('#latest_user').removeClass('hide');
        $('#home_filters_close').trigger('click');
        let button = $('#btn_load_more_search_users');
        let container = $('#search_users_container');
        let dtemplateHtml = '';
        let listHtml = '';

        //button.removeClass('hide');
        //let params = button.attr('data-ajax-params' );
        //let search = result.page ;
        //let replacement = "_where=" + encodeURI(result.post) +"&page=" + search;
        //button.attr('data-ajax-params', replacement);

        if (result.html.length == 0) {
           // $('#section_match_users').html(`<div class="dt_sections" style="margin: 14px 0 0;"><h5 id="_load_more" class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z"></path></svg>${$('#btn_load_more_search_users').attr('data-lang-nomore')}</h5></div>`);
        } else {
            if($('#avaters_item_container').length == 0){
                //$('#section_match_users').html(`<div class="dt_home_match_user">
                //<div class="valign-wrapper mtc_usr_avtr" id="avaters_item_container">
                 //       ${result.html_imgs}
                  //  </div>
                  //  <div class="mtc_usr_details" id="match_item_container">
                   //     ${result.html}
                   // </div>
                //</div>`);
            }
            else{
                $('#avaters_item_container').html(result.html_imgs);
                $('#match_item_container').html(result.html);
            }
        }
    } else{
        if(typeof data.message !== undefined) {
            M.toast({html: data.message});
        }
    }
}



		//$.get( window.ajax + 'profile/set_data', {'show_me_to': $('#my_country').attr('data-country')} );
		
	
	/*	$( document ).on( 'change', '#is_my_location', function(e){
		
		if( $('#is_my_location').prop('checked') === false) {
        	$("#my_country").val('all');
        	$('.located_at').html(`&nbsp;&nbsp;<?php echo __('located_at');?> <span id="located">${$("#my_country option:selected" ).text()}</span>`);

            $('#_located').prop("disabled", true);
            $('#_located').val( window.located );


            $('#my_country').removeAttr( 'disabled' );
            $('#my_country').prop("disabled", false);
            $('#my_country').formSelect();
            //$.get( window.ajax + 'profile/set_data', {'show_me_to': $('#my_country').attr('data-country')} );
        }else{
        	var valueSelected = $('#_located').val();
        	$('.located_at').html(`&nbsp;&nbsp;<?php echo __('located within');?> <span id="located">${valueSelected}</span> <?php echo $config->default_unit;?>`);

            $('#_located').removeAttr( 'disabled' );
            $('#_located').val( window.located );

            $('#my_country').attr( 'disabled', 'disabled' );
            $('#my_country').prop("disabled", true);
            $('#my_country').find('option[value="'+$('#my_country').attr('data-country')+'"]').prop('selected', true);
            $('#my_country').formSelect();
            //$.get( window.ajax + 'profile/set_data', {'show_me_to': ''} );
        }
        e.preventDefault();
    });
*/

});
function resetSearchData() {
	$.get(window.ajax + 'profile/resetSearch', function (data) {
        if (data.status == 200) {
            window.location.reload();
        }
    });
}
function Wo_ViewAnnouncement(id) {
    var announcement_container = $('.home-announcement');
        $.get(window.ajax + 'useractions/UpdateAnnouncementViews', {id:id}, function (data) {
            if (data.status == 200) {
                announcement_container.slideUp(200, function () {
                    $(this).remove();
                });
            }
        });
}



function validateAge(){
	var min = parseInt($("#_age_from").val());
	var max = parseInt($("#_age_to").val());

	if (min > max){
		$("#age_modal").modal("open");
	}
}
</script>
