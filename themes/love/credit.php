<?php echo '<script src="'. $theme_url . 'assets/js/spin.js" type="text/javascript" /></script>'; ?>

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
					
				</div>

				<div class="dt_sections dt_go_pro">
					<?php if (file_exists($theme_path . 'third-party-payment.php')) { ?>
						<?php require( $theme_path . 'third-party-payment.php' );?>
					<?php } ?>
					<div class="dt_p_head">
						<h2><?php echo __( 'Use Your Credits To:' );?> </h2>
						<p><?php echo __( 'Boost Yourself and  Increase Your Popularity' );?></p>
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
				<div class="row" id='method-row'>
					
					<div class="input-field col m12 s12" style="padding: 0% !important; margin: 0% !important;">
						 			 
						<select id="payment-method" name="payment-method" onchange='checkForms(this)' data-errmsg="<?php echo __( 'Payment Method');?>" required>
						<?php echo DatasetGetSelect( null, "payment-method", __("Choose Payment Method") );?>
							<option value="Airtel Money">Airtel Money</option>
							<option value="Other Payment Method">Other Payment Method</option>
						</select>
					</div>
							
				</div>
				
				<div class="row" id='other-row' style='display:none; margin: 0px; padding: 0px;'>
					
					<div class="input-field col m12 s12" style="padding: 0% !important; margin: 0% !important;">
						 			 
						<select onchange="showBankAccount()" id="other-payment-method" name="other-payment-method" data-errmsg="<?php echo __( 'Specify Payment Method');?>" required>
						<?php echo DatasetGetSelect( null, "payment-method", __("Specify Other Payment Method") );?>
							<option>TNM Mpamba</option>
							<option>Mukuru</option>
							<option>National Bank</option>
						</select>
					</div>
						
					<div class="input-field col m12 s12"
					 style="padding: 10px; margin: 10px; display: none; " id="bank-account">
					  <label style="font-size: 1em !important;padding-left: 5%; font-weight: bold;" 
									 for="other-payment-method"></label>
					</div>	
					
					<div class="input-field col m12 s12"
					 style="padding: 10px; margin: 10px;" id="pop">
					 
					 <label style="font-size: 1em !important;padding-left: 5%; font-weight: bold;" 
									 for="payment-proof"> Enter/paste proof of payment here</label>
									 
					  <textarea id='payment-proof' style="color: black;" ></textarea>
					</div>
				</div>


				
				 
				<div class="row" id="airtel-amount" style="padding: 0% !important; ">
					  <label style="font-size: 1em !important;padding-left: 5%; font-weight: bold;" id="airtel-amount"
									 for="airtel-amount"></label>
				</div>


					<?php 
						$phone = Auth()->phone_number;
						if (str_starts_with($phone, '+2658')){
							$phone = "+265";
						}
					?>

	  				<div class="row" id='airtel-row' style="display: none;">
						<div class="input-field col m12 s12">
							<input name="airtel-number" id="airtel-number" type="text"  value=<?php echo  $phone?>
									class="validate" value="" required >
							<label for="airtel-number"><?php echo __( 'Enter Airtel number to pay' );?></label>
						</div>
							
					</div>
	   </div>

		</form>
      <div class="modal-footer">

	  
		<button type="button" id="pay-cancel" style="background: darkred;float: left;"
			onclick="jQuery('#payment-notice').modal('close');" 	
		 	class="btn btn-danger danger pull-left;">
          <span>Cancel</span>
        </button>
		
	  	<button type="button"  id="pay-now" onclick="submitAirtelPayment()" data-dismiss="modal"	
		 	class="btn btn-primary">
          <span>Pay Now</span>
        </button>


      </div>
      
      <div class="modal-footer">

		<a href="https://wa.me/+265995555626" style='background: white;cursor: pointer;padding: 5px;margin-right: 25%;' target="_blank" class="social_btn">
				Contact Us On:
								<img style='padding-top: 8px;' width='60' height='40' src="<?php echo $theme_url;?>assets/img/whatsapp_img.png">
							</a>&nbsp;&nbsp;


      </div>
    </div>
  </div>


<style>
	#spin {
		position: fixed !important;
		top: 50% !important;
		left: 52% !important;
		margin-top: -50px !important;
		margin-left: -50px !important;
		width: 100px;
		height: 100px
	}
</style>

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
		
		if(method == 'Visa Card'){
			//Submit Order
			submitVisaOrder();
			return;
		}
		
		
		var phone = $("#airtel-number").val();

		if (method == "" || method == null){
			jQuery("#airtel-status-header").html("Specify payment method"); 
			return;
		}
		
		if(method == "Airtel Money" && phone.length != 13){
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


		if(method == "Other Payment Method"  && ($('#other-payment-method').val() == '' || $('#other-payment-method').val() == null)){
			jQuery("#airtel-status-header").html("Specify Other Payment Method"); 
			return;
		}
		
		if(method == "Other Payment Method"  && ($('#payment-proof').val().trim() == '' || $('#payment-proof').val() == null)){
			jQuery("#airtel-status-header").html("Please enter/paste proof of payment"); 
			return;
		}
		
		
		if(method == 'Other Payment Method'){
			submitManualOrder();
			return;
		}
		
		
		
		jQuery("#airtel-status-header").html("Please enter pin on your phone and wait ... ");
		
		showSpinner();
		$("#pay-now, #pay-cancel").attr('disabled', true);

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


	
function submitVisaOrder(){
		
		console.log('Submitting CTECH Visa Pay Order');
		showSpinner();
		$.post(window.ajax + 'airtelmoney/createVisaSession', {
            payType: 'credits',
            description: getDescription(),
			pro_plan: airtelPeriod, 
            price: airtelAmount,
        }, function(data) {
			if (data.status == 200) {
				console.log(data);
				var url = data.data.payment_page_URL; 
				window.location = url;
			}
		});

}
	
function submitManualOrder(){
		
		console.log('Submitting Manual Pay Order');
		showSpinner();
	

		$.post(window.ajax + 'airtelmoney/createManualSession', {
            payType: 'credits',
            description: getDescription(),
			pro_plan: airtelPeriod, 
			method: $('#other-payment-method').val(), 
			payment_proof: $('#payment-proof').val(), 
            price: airtelAmount,
        }, function(data) {
			if (data.status == 200) {
				console.log(data);
				$('#manual-modal').modal('open');
			}
		});
}
	
function checkVisaOrder(){
		
		$.post(window.ajax + 'airtelmoney/checkVisa', {
			payType: 'credits',
            ref: "<?php echo  $_REQUEST['ref']; ?>",
        }, function(data) {
			console.log(data);
			
			if (data.status == 200) {
				$('#visapay-status').html("Transaction successful !!");
				$('#visa-modal').modal('open');

				window.location = "/find-matches";
			}else{
				$('#visapay-status').html("Transaction  !!");
				$('#visa-modal').modal('open');	
			}
		});

}
	
	function checkForms(node){
		var val = node.value;
		
		if (val == 'Airtel Money'){
			
			$("#airtel-row").show();
			$("#other-row").hide();
			$("#other-row").hide();
		}else if (val == 'Visa Card'){
		
			$("#airtel-row").hide();
			$("#other-row").hide();
		}else if(val == 'Other Payment Method'){
			
			$("#airtel-row").hide();
			$("#other-row").show();
			$("#method-row").hide();

			$("#pop").show();
		}
	}
	
	function showBankAccount(){
		var banks = {
				"TNM Mpamba" : 'Send <b>MK' + airtelAmount + '</b> to TNM Mpamba number: <b>0888971214 (Chiphetsa Wirima )</b> and paste Proof of Payment below',
				"Mukuru" : 'Send <b>MK' + airtelAmount + '</b> Mukuru to number: <b>+265995555626 (Chiphetsa Wirima )</b> and paste Proof of Payment below',
				"National Bank" : 'Send <b>MK' + airtelAmount + '</b> to NB Account Number: <b>546348</b> and paste Proof of Payment below'

			}

			var banks_chewa = {
				"TNM Mpamba" : '<br /><br />Tumizan <b>MK' + airtelAmount + '</b> ku <b>0888971214(Chiphetsa Wirima )</b> kenako ikani umboni mu bokosi musimu',
				"Mukuru" : '',
				"National Bank" : ''

			}
			
		var bank = $('#other-payment-method').val();
		$('#bank-account').html(banks[bank] + '' + banks_chewa[bank]);
		$('#bank-account').show();
	}


</script>




<?php 

	global $db;
	$where = "user_id=" .  $profile->id . " AND status = 0 AND type='CREDITS' AND via IN ('FDH Bank','First Capital Bank','NBS Bank','Centenary Bank','TNM Mpamba','Mukuru','National Bank','Standard Bank')";
	$requests= $db->where($where)->getOne('payment_requests');
	if (!empty($requests)) {
?>

	<script>
		setTimeout(function(){
			$("#subs-modal").modal({dismissible: false});
			$("#subs-modal").modal('open');

		}, 300);
	</script>
<?php } ?>


<?php 

if (!empty($_REQUEST['ref'])) {
	 ?>

<script>
		showSpinner();
		setTimeout(function(){
			checkVisaOrder();
		}, 2000)
		
</script>
<?php } ?>

