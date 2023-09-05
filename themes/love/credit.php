<?php if( isGenderFree($profile->gender) === true ){?><script>window.location = window.site_url;</script><?php } ?>
<?php //$profile = auth();?>
<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>
<!-- Credits  -->
<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
		
		<div class="col l6">
			<?php if (file_exists($theme_path . 'third-party-payment.php')) { ?>
				<?php require( $theme_path . 'third-party-payment.php' );?>
			<?php } ?>
		
			
			<?php if(IS_LOGGED == true){ ?>
				<?php if($config->credit_earn_system == 1){?>
					<div class="dt_credits dt_sections daily_tribute">
						<h4 class="bold"><?php echo __( 'Daily Tribute' );?></h4>
						<?php
							global $db;
							if($profile->reward_daily_credit == 1){
								$dates = $db->where('user_id', $profile->id)->get('daily_credits',null,array('from_unixtime( max(created_at) ) as DaysFromNow'));
						?>
							<p class="no_margin"><?php echo __( 'Congratulation!. you login to our site for' );?> <?php echo (int)$config->credit_earn_max_days;?> <?php echo __( 'times' );?>, <?php echo __( 'and you earn' );?> <?php echo (int)$config->credit_earn_day_amount;?> <?php echo __( 'credits' );?> , <span class="time ajax-time age" title="<?php echo $dates[0]['DaysFromNow'];?>"></span></p>
						<?php } else {
								$dates = $db->where('user_id', $profile->id)->get('daily_credits',null,array('count(*) as CountDays','TIMESTAMPDIFF(DAY, from_unixtime( max(created_at) ), from_unixtime( min(created_at) )) as TotalDays','TIMESTAMPDIFF(DAY, now() , from_unixtime( min(created_at) )) as DaysFromNow'));
						?>
							<p class="no_margin"><?php echo __( 'User who logs in consecutively for' );?> <?php echo (int)$config->credit_earn_max_days;?> <?php echo __( 'times' );?>, <?php echo __( 'and you earn' );?> <?php echo (int)$config->credit_earn_day_amount;?> <?php echo __( 'credits' );?></p>
							<p class="no_margin"><?php echo __( 'You currently logged in for' );?> <?php echo $dates[0]["CountDays"];?> <?php echo __( 'times' );?></p>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>
			
			<div class="dt_credits transparent">
				<h4 class="bold"><?php echo __( 'Use your Credits to' );?></h4>
				<div class="row credit_ftr">
					<svg xmlns="http://www.w3.org/2000/svg" width="844.06" height="95.071" 
					viewBox="0 0 844.06 95.071"> <path id="Path_215799" data-name="Path 215799" 
					d="M-7845,7006s66.177,69.646,163.7,87,90.885-14.452,136.992-53.525,65.174-61.188,188.1,53.525c120.893-23.282,211.231-80.876,179.973-76.969s134.413-46.107,171.923,81.273" 
					transform="translate(7846.45 -7004.623)" 
					fill="none" stroke="#fccbf7" stroke-width="4" stroke-dasharray="15"/> </svg>
					
					<div class="col l2 m4 s12"  
					 style="cursor: pointer;" onclick=" jQuery('#boost_btn').click(); ">
						<div class="ico boost"><svg xmlns="http://www.w3.org/2000/svg" width="38.254" height="45.032" viewBox="0 0 38.254 45.032"> <path id="Subtraction_53" data-name="Subtraction 53" d="M32.077,45.032A1.1,1.1,0,0,1,31.823,45a.887.887,0,0,1-.27-.169L26.63,39.9a2.613,2.613,0,0,0-1.81-.75H13.433a2.613,2.613,0,0,0-1.811.75L6.7,44.829A.875.875,0,0,1,6.431,45a1.1,1.1,0,0,1-.254.036.542.542,0,0,1-.072,0l-.008,0a.651.651,0,0,1-.308-.128l-.01-.006a.735.735,0,0,1-.207-.242L.1,34.607l-.007-.015a.61.61,0,0,1-.083-.434.8.8,0,0,1,.219-.431L4.489,29.71l.151-.143L4.6,29.361a29.342,29.342,0,0,1-.476-5.206A27.66,27.66,0,0,1,8.208,9.527,24.161,24.161,0,0,1,19.126,0,24.155,24.155,0,0,1,30.045,9.527a27.66,27.66,0,0,1,4.081,14.628,29.242,29.242,0,0,1-.478,5.206l-.038.207,4.412,4.161a.816.816,0,0,1,.219.429.72.72,0,0,1-.073.457L32.682,44.649a.722.722,0,0,1-.209.242l-.01.006a.63.63,0,0,1-.307.128l-.006,0A.571.571,0,0,1,32.077,45.032ZM19.125,4.021l-.186.1A21.167,21.167,0,0,0,10.824,12.3,24.479,24.479,0,0,0,8.175,28.709a3.847,3.847,0,0,1-.139,1.958l0,.012a3.836,3.836,0,0,1-1.058,1.676L4.336,34.848l2.569,4.673,2.141-2.169a6.235,6.235,0,0,1,4.387-1.823H24.82a6.242,6.242,0,0,1,4.387,1.823l2.141,2.169,2.569-4.673-2.636-2.491,0,0a3.845,3.845,0,0,1-1.058-1.676l0-.012a3.852,3.852,0,0,1-.14-1.956,23.856,23.856,0,0,0,.426-4.555A24.16,24.16,0,0,0,27.438,12.3a21.114,21.114,0,0,0-8.126-8.173Zm0,19.749a3.956,3.956,0,0,1-2.822-1.18l-.008-.008a3.965,3.965,0,0,1,0-5.645l.008-.008a3.969,3.969,0,0,1,5.645,0l.008.008a3.967,3.967,0,0,1,0,5.645l-.006.008A3.956,3.956,0,0,1,19.126,23.77Z" transform="translate(0)" fill="#fff"/> </svg></div>
						<p><?php echo __( 'Boost your profile' );?></p>
					</div>
					<div class="col l2 m4 s12" style="cursor: pointer;" onclick="window.location = '/matches' ">
						<div class="ico gift"><svg xmlns="http://www.w3.org/2000/svg" width="37.986" height="34.11" viewBox="0 0 37.986 34.11"> <path id="Subtraction_52" data-name="Subtraction 52" d="M34.516,34.11H3.471a1.581,1.581,0,0,1-.589-.125,1.559,1.559,0,0,1-.489-.318,1.921,1.921,0,0,1-.351-.523,1.531,1.531,0,0,1-.1-.587V12.795H0V1.553A1.564,1.564,0,0,1,1.534,0h34.92a1.549,1.549,0,0,1,.587.126,1.558,1.558,0,0,1,.5.323,1.551,1.551,0,0,1,.342.516,1.494,1.494,0,0,1,.105.587V12.795H36.048V32.557a1.52,1.52,0,0,1-.107.589,1.858,1.858,0,0,1-.349.521,1.5,1.5,0,0,1-.488.318A1.588,1.588,0,0,1,34.516,34.11ZM5.044,12.795V31h27.9V12.795ZM3.106,3.1V9.689H34.881V3.1ZM24.421,20.546H13.565V17.44H24.421v3.105Z" transform="translate(0 0)" fill="#fff"/> </svg></div>
						<p><?php echo __( 'Send a gift' );?></p>
					</div>
					<div class="col l2 m4 s12"  style="cursor: pointer;" onclick="window.location = '/popularity' ">
						<div class="ico discover"><svg xmlns="http://www.w3.org/2000/svg" width="38.262" height="38.261" viewBox="0 0 38.262 38.261"><path id="Subtraction_51" data-name="Subtraction 51" d="M20.521,38.261h-2.78V32.879l-.337-.042A13.8,13.8,0,0,1,5.426,20.857l-.042-.337H0v-2.78H5.383l.042-.337A13.8,13.8,0,0,1,17.4,5.426l.337-.042V0h2.78V5.384l.335.042A13.805,13.805,0,0,1,32.835,17.4l.042.337h5.384v2.78H32.877l-.042.337A13.8,13.8,0,0,1,20.856,32.837l-.335.042v5.382ZM19.13,8.1a11,11,0,0,0-4.222.839,11.128,11.128,0,0,0-5.969,5.97,11,11,0,0,0,2.4,12.015,10.851,10.851,0,0,0,3.574,2.4,11.034,11.034,0,0,0,8.442,0,11.116,11.116,0,0,0,5.97-5.969,11.052,11.052,0,0,0,0-8.442,11.129,11.129,0,0,0-5.97-5.97A10.986,10.986,0,0,0,19.13,8.1Zm0,14.2a3.113,3.113,0,0,1-2.226-.932l-.008-.008a3.126,3.126,0,0,1,0-4.45L16.9,16.9a3.123,3.123,0,0,1,4.45,0l.008.008a3.123,3.123,0,0,1,0,4.45l-.008.008A3.113,3.113,0,0,1,19.13,22.3Z" fill="#fff"/></svg></div>
						<p><?php echo __( 'Get seen 100x in Discover' );?></p>
					</div>
					<div class="col l2 m4 s12" style="cursor: pointer;" onclick="window.location = '/popularity' ">
						<div class="ico search"><svg xmlns="http://www.w3.org/2000/svg" width="35.991" height="32.181" viewBox="0 0 35.991 32.181"> <path id="Subtraction_50" data-name="Subtraction 50" d="M31.836,32.181H4.155a1.4,1.4,0,0,1-.9-.335,1.322,1.322,0,0,1-.426-.835L.008,7.158a1.26,1.26,0,0,1,.133-.729L.15,6.406a1.242,1.242,0,0,1,.489-.553,1.4,1.4,0,0,1,.725-.206,1.359,1.359,0,0,1,.725.229l7.172,4.78.292.194L16.907.565l0,0a1.237,1.237,0,0,1,.463-.411A1.379,1.379,0,0,1,18,0a1.393,1.393,0,0,1,.625.15,1.234,1.234,0,0,1,.462.41l7.153,10.006.2.283L33.9,5.879l0,0a1.377,1.377,0,0,1,.726-.227,1.4,1.4,0,0,1,.725.206,1.23,1.23,0,0,1,.488.553l.011.023a1.234,1.234,0,0,1,.131.729L33.167,31l0,.012a1.322,1.322,0,0,1-.426.835A1.4,1.4,0,0,1,31.836,32.181ZM3.022,9.74h0l2.293,19.43.038.318H30.638L32.969,9.741l-7.2,4.8L18,3.659,10.226,14.537l-7.2-4.8ZM18,21.941a3.014,3.014,0,0,1-2.147-.9l-.006-.007a3.017,3.017,0,0,1,0-4.294l.006-.008a3.021,3.021,0,0,1,4.295,0l.008.008a3.017,3.017,0,0,1,0,4.294l-.006.007A3.016,3.016,0,0,1,18,21.941Z" transform="translate(0)" fill="#fff"/> </svg></div>
						<p><?php echo __( 'Put yourself First in Search' );?></p>
					</div>
					<div class="col l2 m4 s12">
						<div class="ico sticker"><svg xmlns="http://www.w3.org/2000/svg" width="39.139" height="39.14" viewBox="0 0 39.139 39.14"> <path id="Subtraction_48" data-name="Subtraction 48" d="M5.865,39.14h0L4.187,29.612l2.744-.485,1.139,6.461.057.325,6.785-1.2.483,2.745L5.866,39.14Zm23.744-4.186h0l-.484-2.743,6.46-1.14.324-.057-1.2-6.787,2.746-.483,1.679,9.53-9.529,1.68ZM19.56,29.471a7.726,7.726,0,0,1-3.51-.837l-.014-.007a7.91,7.91,0,0,1-2.456-1.747,8.348,8.348,0,0,1-1.674-2.561l2.619-.954a5.148,5.148,0,0,0,2.712,2.759,5.118,5.118,0,0,0,2.33.557,5.493,5.493,0,0,0,1.879-.337,5.249,5.249,0,0,0,3.085-2.875,5.124,5.124,0,0,0,.3-3.855l2.623-.956a8.32,8.32,0,0,1,.363,3.039,7.9,7.9,0,0,1-.757,2.917l-.007.016A7.937,7.937,0,0,1,22.4,28.96,8.292,8.292,0,0,1,19.56,29.471Zm-8.5-9.364a3.753,3.753,0,0,1-.972-.141A2.42,2.42,0,0,1,8.96,16.871a2.976,2.976,0,0,1,1.3-1.12,2.179,2.179,0,0,1,.746-.12,3.735,3.735,0,0,1,.971.14,2.414,2.414,0,0,1,1.126,3.095,2.954,2.954,0,0,1-1.3,1.121A2.2,2.2,0,0,1,11.059,20.106ZM1.678,15.4h0L0,5.867l9.527-1.68.483,2.744L3.551,8.07l-.323.056,1.2,6.787L1.679,15.4Zm22.33,0a3.771,3.771,0,0,1-.976-.141,2.42,2.42,0,0,1-1.126-3.093,2.98,2.98,0,0,1,1.3-1.121,2.2,2.2,0,0,1,.744-.12,3.814,3.814,0,0,1,.975.141,2.417,2.417,0,0,1,1.126,3.095,2.972,2.972,0,0,1-1.3,1.12A2.178,2.178,0,0,1,24.008,15.394Zm8.2-5.38h0L31.068,3.551l-.059-.323-6.783,1.2-.483-2.744L33.271,0l1.68,9.53-2.745.484Z" transform="translate(0)" fill="#fff"/> </svg></div>
						<p><?php echo __( 'Get additional Stickers' );?></p>
					</div>
				
				</div>
			</div>
			
			<hr class="border_hr"/>
			
			<div class="dt_credits transparent buy_credit">
				<h4 class="bold"><?php echo __( 'Buy Credits' );?></h4>
				<div class="credit_pln">
					<div class="dt_plans">
						<p onclick="payAirtelMoney('bag_of_credits', <?php echo (int)$config->bag_of_credits_price;?>)">
							<input type="radio" name="plans" id="plan_1" value="<?php echo __( 'Bag of Credits' ) . " " . (int)$config->bag_of_credits_amount;?>" data-price="<?php echo (int)$config->bag_of_credits_price;?>">
							
							<label for="plan_1" class="plan_1">
								<span class="title"><?php echo __( 'Bag of Credits' );?></span>
							
								<b> 
								
								<?php echo $config->currency_symbol.(int)$config->bag_of_credits_amount;?></b>


								<img src="<?php echo $theme_url;?>assets/img/credit.svg" alt="<?php echo __( 'Bag of Credits' );?>"/>
							</label>
						</p>
						<p onclick="payAirtelMoney('box_of_credits', <?php echo (int)$config->box_of_credits_price;?>)">
							<input type="radio" name="plans" id="plan_2" value="<?php echo __( 'Box of Credits' ) . " " .(int)$config->box_of_credits_amount;?>" data-price="<?php echo (int)$config->box_of_credits_price;?>">
							<label for="plan_2" class="plan_2">
								<span class="title"><?php echo __( 'Box of Credits' );?></span>
								<b>
									
								<?php echo $config->currency_symbol.(int)$config->box_of_credits_amount;?></b>
								</b>
								<img src="<?php echo $theme_url;?>assets/img/credit.svg" alt="<?php echo __( 'Box of Credits' );?>"/>
							</label>
						</p>
						<p onclick="payAirtelMoney('chest_of_credits', <?php echo (int)$config->chest_of_credits_price;?>)">
							<input type="radio" name="plans" id="plan_3" value="<?php echo __( 'Chest of Credits' ) . " " .(int)$config->chest_of_credits_amount;?>" data-price="<?php echo (int)$config->chest_of_credits_price;?>">
							<label for="plan_3" class="plan_3">
								<span class="title"><?php echo __( 'Chest of Credits' );?></span>
								<b>
									<?php echo $config->currency_symbol.(int)$config->chest_of_credits_amount;?></b>
								</b>
							</label>
						</p>
					</div>
					<div class="pay_using hidden">
						<p class="bold"><?php echo __( 'Pay Using' );?></p>
						<?php
								$method_type = 'credits';
								require( $theme_path . 'partails' . $_DS . 'modals'. $_DS .'payment_methods.php' );?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar-right.php' );?>
		</div>
	</div>
</div>
<!-- End Credits  -->

<div id="payment-notice" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
	  	<label style="font-weight: bold;font-size: 1em;color: red;text-align: center;padding-left: 5%;" 
				id="airtel-status-header"> </label>
      </div>
      <div class="modal-body">
		<form id='airtel-form'>
				<div class="row">
					
					<div class="input-field col m12 s12" style="padding: 0% !important;">
						<select id="payment-method" name="payment-method" data-errmsg="<?php echo __( 'Payment Method');?>" required>
						<?php echo DatasetGetSelect( null, "payment-method", __("Choose Payment Method") );?>
						<option>Airtel Money</option>
						<option>TNM Mpamba</option>
						</select>
					</div>
							
				</div>

				<div class="row" style="padding: 0% !important; ">
					  <label style="font-size: 1em !important;padding-left: 5%; font-weight: bold;" id="airtel-amount"
									 for="airtel-amount"></label>
				</div>

	  				<div class="row">
						<div class="input-field col m12 s12">
							<input name="airtel-number" id="airtel-number" type="text"  value=<?php echo  Auth()->phone_number;;?>
									class="validate" value="" required >
							<label for="airtel-number"><?php echo __( 'Phone Number' );?></label>
						</div>
							
					</div>
	   </div>

		</form>
      <div class="modal-footer">

	  
	  <button type="button" style="background: darkred;float: left;"
			onclick="jQuery('#payment-notice').modal('close');" 	
		 	class="btn btn-danger danger pull-left;">
          <span>Cancel</span>
      </button>
		
	  	<button type="button"  onclick="submitAirtelPayment()" data-dismiss="modal"	
		 	class="btn btn-primary">
          <span>Pay Now</span>
        </button>


      </div>
    </div>
  </div>




<a href="javascript:void(0);" id="btnProSuccess" style="visibility: hidden;display: none;"></a>

<script>

function pay_using_airtel_money() {
	$('#2checkout_modal').modal('open');
}

<?php if ($config->fluttewave_payment == 1) { ?>
	function open_fluttewave() {
		$('#fluttewave_modal').modal('open');
	}
	function SignatureFluttewave() {
		$('#fluttewave_btn').attr('disabled', true).text("<?php echo __('please_wait')?>");
		plans = document.getElementsByName('plans');
		var price = 0;
	    var description = '';
	    for (index = 0; index < plans.length; index++) {
	        if (plans[index].checked) {
	            description = plans[index].value;
	            price = plans[index].getAttribute('data-price');
	            break;
	        }
	    }
		email = $('#fluttewave_email').val();
	    $.post(window.ajax + 'fluttewave/pay?pay_type=credits', {amount:price,email:email}, function(data) {
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
<?php if ($config->aamarpay_payment == '1') { ?>
	function pay_using_aamarpay() {
		$('#aamarpay_modal').modal('open');
	}
	function AamarpayRequest() {
		$('#aamarpay_button').html("<?php echo __('please_wait');?>");
		$('#aamarpay_button').attr('disabled','true');
		plans = document.getElementsByName('plans');
		var price = 0;
	    var description = '';
	    for (index = 0; index < plans.length; index++) {
	        if (plans[index].checked) {
	            description = plans[index].value;
	            price = plans[index].getAttribute('data-price');
	            break;
	        }
	    }
		$.post(window.ajax + 'aamarpay/get',{price:price,name:$('#aamarpay_name').val(),email:$('#aamarpay_email').val(),phone:$('#aamarpay_number').val()}, function (data) {
			$('#aamarpay_button').removeAttr('disabled');
	        $('#aamarpay_button').text("<?php echo __('Pay');?>");
	        if (data.status == 200) {
	        	location.href = data.url;
	        }
	    }).fail(function(data) {
	    	$('#aamarpay_button').removeAttr('disabled');
	        $('#aamarpay_button').text("<?php echo __('Pay');?>");
    		M.toast({html: data.responseJSON.message});
		});
		
	}
<?php } ?>
<?php if ($config->ngenius_payment == '1') { ?>
	function pay_using_ngenius() {
		plans = document.getElementsByName('plans');
		var price = 0;
	    var description = '';
	    for (index = 0; index < plans.length; index++) {
	        if (plans[index].checked) {
	            description = plans[index].value;
	            price = plans[index].getAttribute('data-price');
	            break;
	        }
	    }
		$.post(window.ajax + 'ngenius/get',{price:price}, function (data) {
	        if (data.status == 200) {
	        	location.href = data.url;
	        }
	    }).fail(function(data) {
    		M.toast({html: data.responseJSON.message});
		});
	}
<?php } ?>
<?php if ($config->coinpayments == '1') { ?>
	function pay_using_coinpayments() {
		plans = document.getElementsByName('plans');
		var price = 0;
	    var description = '';
	    for (index = 0; index < plans.length; index++) {
	        if (plans[index].checked) {
	            description = plans[index].value;
	            price = plans[index].getAttribute('data-price');
	            break;
	        }
	    }
		$.post(window.ajax + 'coinpayments/get',{price:price}, function (data) {
	        if (data.status == 200) {
	        	location.href = data.url;
	        }
	    }).fail(function(data) {
    		M.toast({html: data.responseJSON.message});
		});
	}
<?php } ?>
<?php if ($config->fortumo_payment == '1' && !empty($config->fortumo_service_id)) { ?>
	function pay_using_fortumo() {
		$.post(window.ajax + 'fortumo/get', function (data) {
	        if (data.status == 200) {
	        	location.href = data.url;
	        }
	    }).fail(function(data) {
    		M.toast({html: data.responseJSON.message});
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
	    plans = document.getElementsByName('plans');
	    var price = 0;
	    var description = '';
	    for (index = 0; index < plans.length; index++) {
	        if (plans[index].checked) {
	            description = plans[index].value;
	            price = plans[index].getAttribute('data-price');
	            break;
	        }
	    }


	    price = price * 100;
	    
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
	                url: window.ajax + 'razorpay/create',
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
<?php if ($config->yoomoney_payment == '1' && !empty($config->yoomoney_wallet_id)) { ?>
	function pay_using_yoomoney() {
	    plans = document.getElementsByName('plans');
	    var price = 0;
	    var description = '';
	    for (index = 0; index < plans.length; index++) {
	        if (plans[index].checked) {
	            description = plans[index].value;
	            price = plans[index].getAttribute('data-price');
	            break;
	        }
	    }
	    $.post(window.ajax + 'yoomoney/create',{price:price,description:description}, function (data) {
	        if (data.status == 200) {
	        	$('body').append(data.html);
				document.getElementById("yoomoney_form").submit();
				$("#yoomoney_form").remove();
	        }
	    }).fail(function(data) {
    		M.toast({html: data.responseJSON.message});
		});
	}
<?php } ?>
<?php if ($config->coinbase_payment == '1' && !empty($config->coinbase_key)) { ?>
	function pay_using_coinbase() {
	    plans = document.getElementsByName('plans');
	    var price = 0;
	    var description = '';
	    for (index = 0; index < plans.length; index++) {
	        if (plans[index].checked) {
	            description = plans[index].value;
	            price = plans[index].getAttribute('data-price');
	            break;
	        }
	    }
	    $.post(window.ajax + 'coinbase/create',{price:price,description:description}, function (data) {
	        if (data.status == 200) {
	            window.location.href = data.url;
	        }
	    }).fail(function(data) {
    		M.toast({html: data.responseJSON.message});
		});
	}
<?php } ?>
<?php if ($config->securionpay_payment == 'yes') { ?>
	$(function () {
		SecurionpayCheckout.key = '<?php echo($config->securionpay_public_key); ?>';
		SecurionpayCheckout.success = function (result) {
			$.post(window.ajax + 'securionpay/handle', result, function(data, textStatus, xhr) {
				if (data.status == 200) {
					window.location.href = data.url;
				}
			}).fail(function(data) {
	    		M.toast({html: data.responseJSON.message});
			});
		};
		SecurionpayCheckout.error = function (errorMessage) {
			M.toast({html: errorMessage});
		};
	});
    function PaySecurionpay(){
    	price = getPrice();
    	$.post(window.ajax + 'securionpay/token', {type:'credit',price:price}, function(data, textStatus, xhr) {
    		if (data.status == 200) {
    			SecurionpayCheckout.open({
					checkoutRequest: data.token,
					name: 'Credits',
					description: getDescription()
				});
    		}
    	}).fail(function(data) {
    		M.toast({html: data.responseJSON.message});
		});
    }
<?php } ?>

<?php if ($config->authorize_payment == 'yes') { ?>
    function PayAuthorize(){
        $('#authorize_btn').attr('onclick', 'AuthorizeWalletRequest()');
        $('#authorize_modal').modal('open');
    }
    function AuthorizeWalletRequest() {
		$('#authorize_btn').html("<?php echo __('please_wait');?>");
	    $('#authorize_btn').attr('disabled','true');
		authorize_number = $('#authorize_number').val();
		authorize_month = $('#authorize_month').val();
		authorize_year = $('#authorize_year').val();
		authorize_cvc = $('#authorize_cvc').val();
		price = getPrice();
		$.post(window.ajax + 'authorize/pay', {type:'credit',card_number:authorize_number,card_month:authorize_month,card_year:authorize_year,card_cvc:authorize_cvc,price:price}, function(data) {
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
    function PayPaystack(){
        $('#paystack_btn').attr('onclick', 'InitializeWalletPaystack()');
        $('#paystack_wallet_modal').modal('open');
    }
    function InitializeWalletPaystack() {
		$('#paystack_btn').html("<?php echo __('please_wait');?>");
	    $('#paystack_btn').attr('disabled','true');
		email = $('#paystack_wallet_email').val();
		price = getPrice();
		$.post(window.ajax + 'paystack/initialize', {type:'credit',email:email,price:price}, function(data) {
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

<?php if ($config->checkout_payment == 'yes') { ?>
    function PayVia2Co(){
        $('#2checkout_type').val('credits');
        $('#2checkout_description').val(getDescription());
        $('#2checkout_price').val(getPrice());

        $('#2checkout_modal').modal('open');
    }
<?php } ?>

<?php if ($config->iyzipay_payment == "yes" && !empty($config->iyzipay_key) && !empty($config->iyzipay_secret_key)) { ?>
	function PayViaIyzipay(){
		$('.btn-iyzipay-payment').attr('disabled','true');

		$.post(window.ajax + 'iyzipay/createsession', {
            payType: 'credits',
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

    $('#cashfree_type').val('credits');
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
    window.location = window.ajax + 'sms/generate_credit_link?price=' + getPrice() + '00';
}
<?php } ?>
function getDescription() {
    var plans = document.getElementsByName('plans');
    for (index=0; index < plans.length; index++) {
        if (plans[index].checked) {
            return plans[index].value;
            break;
        }
    }
}

function getPrice() {
    var plans = document.getElementsByName('plans');
    for (index=0; index < plans.length; index++) {
        if (plans[index].checked) {
            return plans[index].getAttribute('data-price');
            break;
        }
    }
}
<?php if ($config->paypal_payment == '1') { ?>
document.getElementById('paypal').addEventListener('click', function(e) {
    $.post(window.ajax + 'paypal/generate_link', {description:getDescription(), amount:getPrice(), mode: "credits"}, function (data) {
        if (data.status == 200) {
            window.location.href = data.location;
        } else {
            $('.modal-body').html('<i class="fa fa-spin fa-spinner"></i> <?php echo __( 'Payment declined' );?> ');
        }
    });
        
    e.preventDefault();
});
<?php } ?>
<?php if($config->bank_payment == '1'){?>
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
        formData.append("mode", 'credits');
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
                M.toast({html: '<?php echo __('Your receipt uploaded successfully.');?>'});
                return false;
            }
        }
    });
});

<?php } ?>
<?php if ($config->stripe_payment == '1') { ?>
document.getElementById('stripe_credit').addEventListener('click', function(e) {

    $.post(window.ajax + 'stripe/createsession', {
        payType: 'credits',
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


var airtelPeriod = ""
var airtelAmount = ""
function payAirtelMoney(plan, price){
	//alert(plan)
	airtelPlan = plan;
	airtelAmount = price;

	jQuery("#payment-notice").modal("open");
	jQuery("#airtel-amount").html("Amount:  MK " + price)

}

function submitAirtelPayment(){

	var method = $("#payment-method").val();
	var phone = $("#airtel-number").val();

	if (method == "" || method == null){
		jQuery("#airtel-status-header").html("Specify payment method"); 
		return;
	}

	if(phone.length != 13){
		jQuery("#airtel-status-header").html("Invalid  phone number length"); 
		return;
	}

	if (method == "Airtel Money" && !phone.startsWith("+2659")){
		jQuery("#airtel-status-header").html("Invalid Airtel number"); 
		return;
	}

	if (method == "TNM Mpamba" && !phone.startsWith("+2658")){
		jQuery("#airtel-status-header").html("Invalid TNM number");
		return;
	}

	jQuery("#airtel-status-header").html("Please enter pin on your phone and wait ... ");
	$.post(window.ajax + 'airtelmoney/createsession', {
		payType: 'credits',
		description: getDescription(),
		pro_plan: airtelPeriod, 
		price: airtelAmount,
		phone:  jQuery("#airtel-number").val().trim()
	}, function(data) {
		if (data.status == 200) {
			console.log(data.data); //TransID

			//Ajax check success balance
			setTimeout( function(){
				$.post(window.ajax + 'airtelmoney/success', {
					payType: 'credits',
					transID: data.data
				}, function(statusData) {
					if (statusData.status == 200) {
						jQuery("#airtel-status-header").css('color', 'green');
					}else {
						jQuery("#airtel-status-header").css('color', 'green');
					}

					jQuery("#airtel-status-header").html(statusData.message);

					setTimeout(
						function(){
							window.location = "/find-matches"
						}, 
						3000)

				});
			}, 10000)
		} else {
			console.log("Error!");
		}
	});
}


</script>