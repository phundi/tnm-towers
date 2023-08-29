<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<?php if( $profile->is_pro == 1 ){?><script>window.location = window.site_url;</script><?php } ?>
<?php if( $config->pro_system == 0 ){?><script>window.location = window.site_url;</script><?php } ?>
<?php if( isGenderFree($profile->gender) === true ){?><script>window.location = window.site_url;</script><?php } ?>
<!-- Premium  -->
<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
		
		<div class="col l9">
			<div class="dt_sections dt_go_pro">
				<?php if (file_exists($theme_path . 'third-party-payment.php')) { ?>
					<?php require( $theme_path . 'third-party-payment.php' );?>
				<?php } ?>
				<div class="dt_p_head">
					<h2><?php echo __( 'Discover the incredible features of' );?> <?php echo ucfirst( $config->site_name );?> <?php echo __( "that you do not want to miss out on" );?>.</h2>
					<p><?php echo __( 'Unlock Premium to Expand Your Social Circle and Connect Faster' );?></p>
				</div>
			</div>
			
			<div class="row">
				
				<div class="col l4 m6 s12">
					<label class="dt_go_pro_plan">
						<input class="with-gap" name="pro_plan" type="radio" value="<?php echo __( 'Daily' );?>" data-price="<?php echo (float)$config->weekly_pro_plan;?>"/>
						<div class="plan">
							<div class="valign-wrapper head">
								<div>
									<svg xmlns="http://www.w3.org/2000/svg" width="69.484" height="69.469" viewBox="0 0 69.484 69.469"> <path d="M79.562,61.122V55.516a1.331,1.331,0,1,1,2.663,0v5.606a1.331,1.331,0,0,1-2.663,0ZM74,62.162a1.331,1.331,0,0,0,1.849-1.916L71.83,56.362a1.331,1.331,0,0,0-1.849,1.916Zm12.855.373a1.327,1.327,0,0,0,.925-.373l4.024-3.884a1.331,1.331,0,1,0-1.849-1.916l-4.024,3.884a1.331,1.331,0,0,0,.925,2.289Zm20.615,14.629L99.008,87.2a1.331,1.331,0,1,1-2.035-1.717l6.619-7.849H93.382l-8.444,22.12,8.47-10.043a1.331,1.331,0,1,1,2.036,1.717L81.912,107.476a1.332,1.332,0,0,1-2.036,0L54.313,77.164a1.331,1.331,0,0,1-.024-1.688L57.5,71.441A1.331,1.331,0,0,1,59.587,73.1l-1.493,1.874H68.545l4.524-8.033H64.493l-1.459,1.832a1.331,1.331,0,0,1-2.083-1.659l1.859-2.334a1.331,1.331,0,0,1,1.041-.5H97.936a1.331,1.331,0,0,1,1.041.5l8.521,10.7A1.331,1.331,0,0,1,107.475,77.164ZM97.294,66.942H88.719l4.524,8.033h10.451ZM71.6,74.975H90.187l-4.524-8.033H76.125ZM58.2,77.637,76.85,99.758l-8.444-22.12Zm32.336,0H71.257l9.637,25.247Z" transform="translate(-69.831 -11.958) rotate(-21)" fill="#0ebe7e"/> </svg>
									<div>
										<h5><?php echo __( 'Daily' );?></h5>
										<p><?php echo __( 'Package' );?></p>
									</div>
								</div>
								<div class="price">
									<?php echo $config->currency_symbol . (float)(1500);?>
								</div>
							</div>
							<div class="mid">
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'See more stickers on chat' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Show in Premium bar' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'See likes notifications' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Get discount when buy boost me' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Display first in find matches' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Display on top in random users' );?></p>
								<?php if($config->video_chat == 1 && $config->audio_chat == 1){ ?>
									<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Video and Audio calls to all users' );?></p>
								<?php } ?>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Find potential matches by ditrict' );?></p>
							</div>
							<div class="foot" onclick="payAirtelMoney('daily', 1500);">
								<button type="button"  ><?php echo __( 'Make Payment' );?>
							</button>
							</div>
						</div>
					</label>
				</div>
				

				<div class="col l4 m6 s12">
					<label class="dt_go_pro_plan">
						<input class="with-gap" name="pro_plan" type="radio" value="<?php echo __( 'Weekly' );?>" data-price="<?php echo (float)$config->weekly_pro_plan;?>"/>
						<div class="plan">
							<div class="valign-wrapper head">
								<div>
									<svg xmlns="http://www.w3.org/2000/svg" width="69.484" height="69.469" viewBox="0 0 69.484 69.469"> <path d="M79.562,61.122V55.516a1.331,1.331,0,1,1,2.663,0v5.606a1.331,1.331,0,0,1-2.663,0ZM74,62.162a1.331,1.331,0,0,0,1.849-1.916L71.83,56.362a1.331,1.331,0,0,0-1.849,1.916Zm12.855.373a1.327,1.327,0,0,0,.925-.373l4.024-3.884a1.331,1.331,0,1,0-1.849-1.916l-4.024,3.884a1.331,1.331,0,0,0,.925,2.289Zm20.615,14.629L99.008,87.2a1.331,1.331,0,1,1-2.035-1.717l6.619-7.849H93.382l-8.444,22.12,8.47-10.043a1.331,1.331,0,1,1,2.036,1.717L81.912,107.476a1.332,1.332,0,0,1-2.036,0L54.313,77.164a1.331,1.331,0,0,1-.024-1.688L57.5,71.441A1.331,1.331,0,0,1,59.587,73.1l-1.493,1.874H68.545l4.524-8.033H64.493l-1.459,1.832a1.331,1.331,0,0,1-2.083-1.659l1.859-2.334a1.331,1.331,0,0,1,1.041-.5H97.936a1.331,1.331,0,0,1,1.041.5l8.521,10.7A1.331,1.331,0,0,1,107.475,77.164ZM97.294,66.942H88.719l4.524,8.033h10.451ZM71.6,74.975H90.187l-4.524-8.033H76.125ZM58.2,77.637,76.85,99.758l-8.444-22.12Zm32.336,0H71.257l9.637,25.247Z" transform="translate(-69.831 -11.958) rotate(-21)" fill="#0ebe7e"/> </svg>
									<div>
										<h5><?php echo __( 'Weekly' );?></h5>
										<p><?php echo __( 'Package' );?></p>
									</div>
								</div>
								<div class="price">
									<?php echo $config->currency_symbol . (float)$config->weekly_pro_plan;?>
								</div>
							</div>
							<div class="mid">
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'See more stickers on chat' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Show in Premium bar' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'See likes notifications' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Get discount when buy boost me' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Display first in find matches' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Display on top in random users' );?></p>
								<?php if($config->video_chat == 1 && $config->audio_chat == 1){ ?>
									<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Video and Audio calls to all users' );?></p>
								<?php } ?>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Find potential matches by district' );?></p>
							</div>
							<div class="foot" onclick="payAirtelMoney('weekly', 
																<?php echo (float)$config->weekly_pro_plan;?>

							);">
								<button type="button" ><?php echo __( 'Make Payment' );?></button>
							</div>
						</div>
					</label>
				</div>
				<div class="col l4 m6 s12">
					<label class="dt_go_pro_plan special">
						<input class="with-gap" name="pro_plan" type="radio" value="<?php echo __( 'Monthly' );?>" data-price="<?php echo (float)$config->monthly_pro_plan;?>" checked />
						<div class="plan">
							<div class="special_badge"><?php echo __( 'Special' );?></div>
							<div class="valign-wrapper head">
								<div>
									<svg xmlns="http://www.w3.org/2000/svg" width="69.484" height="69.469" viewBox="0 0 69.484 69.469"> <path d="M79.562,61.122V55.516a1.331,1.331,0,1,1,2.663,0v5.606a1.331,1.331,0,0,1-2.663,0ZM74,62.162a1.331,1.331,0,0,0,1.849-1.916L71.83,56.362a1.331,1.331,0,0,0-1.849,1.916Zm12.855.373a1.327,1.327,0,0,0,.925-.373l4.024-3.884a1.331,1.331,0,1,0-1.849-1.916l-4.024,3.884a1.331,1.331,0,0,0,.925,2.289Zm20.615,14.629L99.008,87.2a1.331,1.331,0,1,1-2.035-1.717l6.619-7.849H93.382l-8.444,22.12,8.47-10.043a1.331,1.331,0,1,1,2.036,1.717L81.912,107.476a1.332,1.332,0,0,1-2.036,0L54.313,77.164a1.331,1.331,0,0,1-.024-1.688L57.5,71.441A1.331,1.331,0,0,1,59.587,73.1l-1.493,1.874H68.545l4.524-8.033H64.493l-1.459,1.832a1.331,1.331,0,0,1-2.083-1.659l1.859-2.334a1.331,1.331,0,0,1,1.041-.5H97.936a1.331,1.331,0,0,1,1.041.5l8.521,10.7A1.331,1.331,0,0,1,107.475,77.164ZM97.294,66.942H88.719l4.524,8.033h10.451ZM71.6,74.975H90.187l-4.524-8.033H76.125ZM58.2,77.637,76.85,99.758l-8.444-22.12Zm32.336,0H71.257l9.637,25.247Z" transform="translate(-69.831 -11.958) rotate(-21)" fill="#f9bb29"/> </svg>
									<div>
										<h5><?php echo __( 'Monthly' );?></h5>
										<p><?php echo __( 'Package' );?></p>
									</div>
								</div>
								<div class="price">
									<?php echo $config->currency_symbol . (float)$config->monthly_pro_plan;?>
								</div>
							</div>
							<div class="mid">
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'See more stickers on chat' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Show in Premium bar' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'See likes notifications' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Get discount when buy boost me' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Display first in find matches' );?></p>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Display on top in random users' );?></p>
								<?php if($config->video_chat == 1 && $config->audio_chat == 1){ ?>
									<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Video and Audio calls to all users' );?></p>
								<?php } ?>
								<p><svg xmlns="http://www.w3.org/2000/svg" width="15.836" height="15.836" viewBox="0 0 15.836 15.836"> <path d="M3647.918,7687.836a7.918,7.918,0,1,1,7.918-7.918A7.921,7.921,0,0,1,3647.918,7687.836Zm-.792-4.751,5.6-5.6-1.116-1.125-4.481,4.481-2.241-2.241-1.116,1.124Z" transform="translate(-3640 -7672)" fill="#2ee93b"/> </svg> <?php echo __( 'Find potential matches district' );?></p>
							</div>
							<div class="foot" onclick="payAirtelMoney('monthly', 
									<?php echo (float)$config->monthly_pro_plan;?>
							);">
								<button type="button" ><?php echo __( 'Make Payment' );?></button>
							</div>
						</div>
					</label>
				</div>
				
			</div>
			
			<div class="dt_premium" style="display: none;">
				<div class="dt_choose_pro">
					<div class="pay_using center">
						<p class="bold"><?php echo __( 'Pay Using' );?></p>
						<?php
							$method_type = 'pro';
							require( $theme_path . 'partails' . $_DS . 'modals'. $_DS .'payment_methods.php' );?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Premium  -->
<a href="javascript:void(0);" id="btnProSuccess" style="visibility: hidden;display: none;"></a>



<script>
	<?php if ($config->fluttewave_payment == 1) { ?>
		function open_fluttewave() {
			$('#fluttewave_modal').modal('open');
		}
		function SignatureFluttewave() {
			$('#fluttewave_btn').attr('disabled', true).text("<?php echo __('please_wait')?>");
			email = $('#fluttewave_email').val();
		    $.post(window.ajax + 'fluttewave/pay', {amount:getPrice(),email:email}, function(data) {
		    	$('#fluttewave_btn').html("<?php echo(__('pay')) ?>");
			    $('#fluttewave_btn').removeAttr('disabled');
		        if (data.status == 200) {
		            window.location.href = data.url;
		        } else {
		         	$('#fluttewave_alert').html("<div class='alert alert-danger'>"+data.message+"</div>");
					setTimeout(function () {
						$('#fluttewave_alert').html("");
					},3000);
		        }
		    });
		}
	<?php } ?>
	<?php if ($config->ngenius_payment == '1') { ?>
		function pay_using_ngenius() {
			$.post(window.ajax + 'ngenius/get_pro',{price:getPrice()}, function (data) {
		        if (data.status == 200) {
		        	location.href = data.url;
		        }
		    }).fail(function(data) {
		    	showResponseAlert('.payments_alert','danger',data.responseJSON.message,2000);
			});
		}
	<?php } ?>
	<?php if ($config->razorpay_payment == '1' && !empty($config->razorpay_key_id)) { ?>
		function pay_using_razorpay() {
			$("#razorpay_alert").html('');
			$('#razorpay_modal').modal('open');
		}
		function SignatureRazorpay() {
			$('#razorpay_btn').html("<?php echo __('please_wait');?>");
			$('#razorpay_btn').attr('disabled','true');
		    var merchant_order_id = "<?php echo(round(111111,9999999)) ?>";
		    var card_holder_name_id = $('#razorpay_name').val();
		    var email = $('#razorpay_email').val();
		    var phone = $('#razorpay_phone').val();
		    var currency_code_id = "INR";

		    if (!email || !phone || !card_holder_name_id) {
		    	$('#razorpay_alert').html("<div class='alert alert-danger'><?php echo(__('please check your details')) ?></div>");
				setTimeout(function () {
					$('#razorpay_alert').html("");
				},3000);
				$('#razorpay_btn').html("<?php echo __('pay');?>");
				$('#razorpay_btn').removeAttr('disabled');
				return false;
		    }


		    price = getPrice() * 100;
		    
		    var razorpay_options = {
		        key: "<?php echo $config->razorpay_key_id; ?>",
		        amount: price,
		        name: "<?php echo $config->site_name; ?>",
		        description: getDescription(),
		        image: "<?php echo $config->sitelogo;?>",
		        netbanking: true,
		        currency: currency_code_id,
		        prefill: {
		            name: card_holder_name_id,
		            email: email,
		            contact: phone
		        },
		        notes: {
		            soolegal_order_id: merchant_order_id,
		        },
		        handler: function (transaction) {
		            jQuery.ajax({
		                url: window.ajax + 'razorpay/create_pro',
		                type: 'post',
		                data: {payment_id: transaction.razorpay_payment_id, order_id: merchant_order_id, card_holder_name_id: card_holder_name_id,  merchant_amount: price, currency: currency_code_id}, 
		                dataType: 'json',
		                success: function (data) {
		                	if (data.status == 200) {
		                		<?php if (!empty($_COOKIE['redirect_page'])) { 
		                			$redirect_page = preg_replace('/on[^<>=]+=[^<>]*/m', '', $_COOKIE['redirect_page']);
							        $redirect_page = preg_replace('/\((.*?)\)/m', '', $redirect_page);
		                			?>
		                			window.location = "<?php echo($redirect_page); ?>";
		                		<?php }else{ ?>
			                		window.location = data.url;
		                	    <?php } ?>
		                	}
		                	else{
		                		if (data.url != '') {
		                			window.location = data.url;
		                		}
		                		else{
		                			$('#razorpay_alert').html("<div class='alert alert-danger'>"+data.message+"</div>");
									setTimeout(function () {
										$('#razorpay_alert').html("");
									},3000);
									$('#razorpay_btn').html("<?php echo __('pay');?>");
									$('#razorpay_btn').removeAttr('disabled');

		                		}
		                	}
		                }
		            });
		        },
		        "modal": {
		            "ondismiss": function () {
		                // code here
		            }
		        }
		    };
		    // obj        
		    var objrzpv1 = new Razorpay(razorpay_options);
		    objrzpv1.open();
		    e.preventDefault();
		}
	<?php } ?>
	<?php if ($config->coinbase_payment == '1' && !empty($config->coinbase_key)) { ?>
		function pay_using_coinbase() {
		    $.post(window.ajax + 'coinbase/create_pro',{price:getPrice(),description:getDescription()}, function (data) {
		        if (data.status == 200) {
		            window.location.href = data.url;
		        }
		    }).fail(function(data) {
		    	showResponseAlert('.payments_alert','danger',data.responseJSON.message,2000);
			});
		}
	<?php } ?>
	<?php if ($config->aamarpay_payment == '1') { ?>
		function pay_using_aamarpay() {
			$('#aamarpay_modal').modal('open');
		}
		function AamarpayRequest() {
			$('#aamarpay_button').html("<?php echo __('please_wait');?>");
			$('#aamarpay_button').attr('disabled','true');
			$.post(window.ajax + 'aamarpay/get_pro',{price:getPrice(),name:$('#aamarpay_name').val(),email:$('#aamarpay_email').val(),phone:$('#aamarpay_number').val()}, function (data) {
				$('#aamarpay_button').removeAttr('disabled');
		        $('#aamarpay_button').text("<?php echo __('Pay');?>");
		        if (data.status == 200) {
		        	location.href = data.url;
		        }
		    }).fail(function(data) {
		    	$('#aamarpay_button').removeAttr('disabled');
		        $('#aamarpay_button').text("<?php echo __('Pay');?>");
		        showResponseAlert('.payments_alert','danger',data.responseJSON.message,2000);
			});
			
		}
	<?php } ?>
	<?php if ($config->coinpayments == '1') { ?>
		function pay_using_coinpayments() {
			$.post(window.ajax + 'coinpayments/get?pay_type=pro',{price:getPrice()}, function (data) {
		        if (data.status == 200) {
		        	location.href = data.url;
		        }
		    }).fail(function(data) {
		    	showResponseAlert('.payments_alert','danger',data.responseJSON.message,2000);
			});
		}
	<?php } ?>
	<?php if ($config->fortumo_payment == '1') { ?>
		function pay_using_fortumo() {
			$.post(window.ajax + 'fortumo/get?pay_type=pro', function (data) {
		        if (data.status == 200) {
		        	location.href = data.url;
		        }
		    }).fail(function(data) {
		    	showResponseAlert('.payments_alert','danger',data.responseJSON.message,2000);
			});
		}
	<?php } ?>
	<?php if ($config->yoomoney_payment == '1') { ?>
		function pay_using_yoomoney() {
			$.post(window.ajax + 'yoomoney/go_pro', {
	            payType: 'membership',
	            description: getDescription(),
	            price: getPrice()
	        }, function (data) {
	            if (data.status == 200) {
		        	$('body').append(data.html);
					document.getElementById("yoomoney_form").submit();
					$("#yoomoney_form").remove();
		        }
	        });
		}
	<?php } ?>
	<?php if ($config->bank_payment == '1') { ?>
	document.getElementById('bank_transfer').addEventListener('click', function(e) {
        $('#bank_transfer_price').text('<?php echo $config->currency_symbol;?>' + getPrice());
        $('#bank_transfer_description').text(getDescription());
        $('#receipt_img_path').html('');
        $('#receipt_img_preview').attr('src', '');
		$('.bank_transfer_modal').removeClass('up_rec_img_ready, up_rec_active');
        $('.bank_transfer_modal').modal('open');
    });

    document.getElementById('receipt_img').addEventListener('change', function(e) {
        let imgPath = $(this)[0].files[0].name;
        if (typeof(FileReader) != "undefined") {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#receipt_img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
        $('#receipt_img_path').html(imgPath);
		$('.bank_transfer_modal').addClass('up_rec_img_ready');
        $('#btn-upload-receipt').removeAttr('disabled');
        $('#btn-upload-receipt').removeClass('btn-flat').addClass('btn-success');
    });

    document.getElementById('btn-upload-receipt').addEventListener('click', function(e) {
        e.preventDefault();
        let bar = $('#img_upload_progress');
        let percent = $('#img_upload_progress_bar');

        let formData = new FormData();
        formData.append("description", getDescription());
        formData.append("price", getPrice());
        formData.append("mode", 'membership');
        formData.append("receipt_img", $("#receipt_img")[0].files[0], $("#receipt_img")[0].files[0].value);
        bar.removeClass('hide');
        $.ajax({
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        let percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        //status.html( percentComplete + "%");
                        percent.width(percentComplete + '%');
                        percent.html(percentComplete + '%');
                        if (percentComplete === 100) {
                            bar.addClass('hide');
                            percent.width('0%');
                            percent.html('0%');
                        }
                    }
                }, false);
                return xhr;
            },
            url: window.ajax + 'profile/upload_receipt',
            type: "POST",
            async: true,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 60000,
            dataType: false,
            data: formData,
            success: function(result) {
                if( result.status == 200 ){
                    $('.bank_transfer_modal').modal('close');
                    showResponseAlert('.payments_alert','danger',"<?php echo __('Your receipt uploaded successfully.');?>",2000);
                    return false;
                }
            }
        });
    });


    <?php } ?>
	<?php if ($config->stripe_payment == '1') { ?>
	document.getElementById('stripe_credit').addEventListener('click', function(e) {

        $.post(window.ajax + 'stripe/createsession', {
            payType: 'membership',
            description: getDescription(),
            price: getPrice()
        }, function (data) {
            if (data.status == 200) {
                stripe.redirectToCheckout({ sessionId: data.session_id });
            } else {
               // $('.modal-body').html('<i class="fa fa-spin fa-spinner"></i> <?php echo __('Payment declined');?> ');
            }
        });

    });
    <?php } ?>
	<?php if ($config->paypal_payment == '1') { ?>
	document.getElementById('paypal').addEventListener('click', function(e) {
        $.post(window.ajax + 'paypal/generate_link', {description:getDescription(), amount:getPrice(), mode: "premium-membarship"}, function (data) {
            if (data.status == 200) {
                window.location.href = data.location;
            } else {
                $('.modal-body').html('<i class="fa fa-spin fa-spinner"></i> Payment declined ');
            }
        });

        e.preventDefault();
    });
    <?php } ?>
    <?php if ($config->securionpay_payment === "yes") { ?>
        $(function () {
            SecurionpayCheckout.key = '<?php echo($config->securionpay_public_key); ?>';
            SecurionpayCheckout.success = function (result) {
                $.post(window.ajax + 'securionpay/handle', result, function(data, textStatus, xhr) {
                    if (data.status == 200) {
                        window.location.href = data.url;
                    }
                }).fail(function(data) {
                	showResponseAlert('.payments_alert','danger',data.responseJSON.message,2000);
                });
            };
            SecurionpayCheckout.error = function (errorMessage) {
            	showResponseAlert('.payments_alert','danger',errorMessage,2000);
            };
        });
        function PaySecurionpay(){
            price = getPrice();
            $.post(window.ajax + 'securionpay/token', {type:'go_pro',price:price}, function(data, textStatus, xhr) {
                if (data.status == 200) {
                    SecurionpayCheckout.open({
                        checkoutRequest: data.token,
                        name: 'membership',
                        description: getDescription()
                    });
                }
            }).fail(function(data) {
                showResponseAlert('.payments_alert','danger',data.responseJSON.message,2000);
            });
        }
    <?php } ?>
	<?php if ($config->authorize_payment === "yes") { ?>
    function PayAuthorize() {
        $('#authorize_btn').attr('onclick', 'AuthorizeProRequest()');
        $('#authorize_modal').modal('open');
    }
    function AuthorizeProRequest() {
        $('#authorize_btn').html("<?php echo __('please_wait');?>");
        $('#authorize_btn').attr('disabled','true');
        authorize_number = $('#authorize_number').val();
        authorize_month = $('#authorize_month').val();
        authorize_year = $('#authorize_year').val();
        authorize_cvc = $('#authorize_cvc').val();
        price = getPrice();
        $.post(window.ajax + 'authorize/pay', {type:'go_pro',card_number:authorize_number,card_month:authorize_month,card_year:authorize_year,card_cvc:authorize_cvc,price:price}, function(data) {
            if (data.status == 200) {
                window.location.href = data.url;
            } else {
                $('#authorize_alert').html("<div class='alert alert-danger'>"+data.message+"</div>");
                setTimeout(function () {
                    $('#authorize_alert').html("");
                },3000);
            }
            $('#authorize_btn').html("<?php echo __( 'pay' );?>");
            $('#authorize_btn').removeAttr('disabled');
        }).fail(function(data) {
            $('#authorize_alert').html("<div class='alert alert-danger'>"+data.responseJSON.message+"</div>");
            setTimeout(function () {
                $('#authorize_alert').html("");
            },3000);
            $('#authorize_btn').html("<?php echo __( 'pay' );?>");
            $('#authorize_btn').removeAttr('disabled');
        });
    }
    <?php } ?>
    <?php if ($config->paystack_payment == 'yes') { ?>
	function PayPaystack() {
		$('#paystack_btn').attr('onclick', 'InitializeProPaystack()');
        $('#paystack_wallet_modal').modal('open');
	}
	function InitializeProPaystack() {
        $('#paystack_btn').html("<?php echo __('please_wait');?>");
        $('#paystack_btn').attr('disabled','true');
        email = $('#paystack_wallet_email').val();
        price = getPrice();
        $.post(window.ajax + 'paystack/initialize', {type:'go_pro',email:email,price:price}, function(data) {
            if (data.status == 200) {
                window.location.href = data.url;
            } else {
                $('#paystack_wallet_alert').html("<div class='alert alert-danger'>"+data.message+"</div>");
                setTimeout(function () {
                    $('#paystack_wallet_alert').html("");
                },3000);
            }
            $('#paystack_btn').html("<?php echo __( 'Confirm' );?>");
            $('#paystack_btn').removeAttr('disabled');
        });
    }
    <?php } ?>

<?php if ($config->iyzipay_payment == "yes" && !empty($config->iyzipay_key) && !empty($config->iyzipay_secret_key)) { ?>
	function PayViaIyzipay(){
		$('.btn-iyzipay-payment').attr('disabled','true');

		$.post(window.ajax + 'iyzipay/createsession', {
            payType: 'membership',
            description: getDescription(),
            price: getPrice()
        }, function(data) {
			if (data.status == 200) {
				$('#iyzipay_content').html('');
				$('#iyzipay_content').html(data.html);
			} else {
				$('.btn-iyzipay').attr('disabled', false).html("Iyzipay App not set yet.");
			}
			$('.btn-iyzipay').removeAttr('disabled');
			$('.btn-iyzipay').find('span').text("<?php echo __( 'iyzipay');?>");
		});

		$('.btn-iyzipay-payment').removeAttr('disabled');
	}
	<?php } ?>

<?php if( $config->cashfree_payment === 'yes' && !empty($config->cashfree_client_key) && !empty($config->cashfree_secret_key)){?>
    function PayViaCashfree(){

        $('.cashfree-payment').attr('disabled','true');

        $('#cashfree_type').val('membership');
        $('#cashfree_description').val(getDescription());
        $('#cashfree_price').val(getPrice());

        $("#cashfree_alert").html('');
        $('.go_pro--modal').fadeOut(250);
        $('#cashfree_modal_box').modal('open');

        $('.btn-cashfree-payment').removeAttr('disabled');
    }
    <?php } ?>
    <?php if($config->paysera_payment == '1'){?>

    function PayViaSms() {
        window.location = window.ajax + 'sms/generate_pro_link?price=' + getPrice() + '00';
    }
    <?php } ?>

    function getDescription() {
        var plans = document.getElementsByName('pro_plan');
        for (index=0; index < plans.length; index++) {
            if (plans[index].checked) {
                return plans[index].value;
                break;
            }
        }
    }

    function getPrice() {
        var plans = document.getElementsByName('pro_plan');
        for (index=0; index < plans.length; index++) {
            if (plans[index].checked) {
                return plans[index].getAttribute('data-price');
                break;
            }
        }
    }

    

    <?php if ($config->checkout_payment == 'yes') { ?>
        function PayVia2Co(){
            $('#2checkout_type').val('membership');
            $('#2checkout_description').val(getDescription());
            $('#2checkout_price').val(getPrice());

            $('#2checkout_modal').modal('open');
        }
    <?php } ?>

	function payAirtelMoney(period, price){

		$.post(window.ajax + 'airtelmoney/createsession', {
            payType: 'membership',
            description: getDescription(),
            price: price
        }, function(data) {
			if (data.status == 200) {
				alert("Transaction in progress, please check balance");
				$('#iyzipay_content').html('');
				$('#iyzipay_content').html(data.html);
			} else {
				$('.btn-iyzipay').attr('disabled', false).html("Iyzipay App not set yet.");
			}
			$('.btn-iyzipay').removeAttr('disabled');
			$('.btn-iyzipay').find('span').text("<?php echo __( 'iyzipay');?>");
		});
	}

</script>