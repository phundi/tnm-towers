    <?php if(IS_LOGGED == true){ ?>
        <div id="pay_modal_wallet">
            <div class="modal modal-sm" id="pay-go-pro" role="dialog" data-keyboard="false">
				<div class="modal-content">
					<h6 class="bold"><?php echo __( 'pay_from_credits' );?></h6>

					<div class="pay_modal_wallet_alert"></div>
					<p class="pay_modal_wallet_text"></p>

					<div class="clear"></div>
					<div class="modal-footer">
						<div class="ball-pulse"><div></div><div></div><div></div></div>
						<button type="button" class="btn waves-effect waves-light btn_primary white-text btn-main" id="pay_modal_wallet_btn"><?php echo __( 'pay' );?></button>
					</div>
				</div>
            </div>
        </div>
		
		
		<div class="payments_modal modal" id="visa-modal">
            <div class="modal-dialog">
                <div class="modal-content dt_bank_trans_modal">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body  credit_pln">
                        <p id='visapay-status'></p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if($config->credit_earn_system == 1){?>
        <div class="payment_modal modal" id="reward_daily_credit_modal">
            <div class="modal-dialog">
                <div class="modal-content dt_bank_trans_modal">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo __( 'Credit Reward' );?></h5>
                    </div>
                    <div class="modal-body  credit_pln">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path></svg>
                        <p><?php echo __( 'Congratulation!. you login to our site for' );?> <?php echo (int)$config->credit_earn_max_days;?> <?php echo __( 'times' );?>, <?php echo __( 'and you earn' );?> <?php echo (int)$config->credit_earn_day_amount;?> <?php echo __( 'credits' );?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php if($config->isDailyCredit){ ?>
            <script>
                $(document).ready(function() {
                    $('#reward_daily_credit_modal').modal({
                        onCloseEnd: function(){
                            window.location.href = window.site_url+'/credit';
                        }
                    }).modal("open");

                });
            </script>
        <?php }} ?>
        <div class="payment_modalx modal modal-sm">
			<div class="modal-content">
				<h6 class="bold"><?php echo __( 'Unlock Private Photo Payment' );?></h6>
				<div class="modal-body">
					<p><?php echo __( 'To unlock private photo feature in your account, you have to pay' );?> <?php echo $config->currency_symbol . (int)$config->lock_private_photo_fee;?>.</p>

					<div class="modal-footer">
						<button onclick="PayUsingWallet('private_photo','show');" class="btn waves-effect waves-light btn-flat btn_primary white-text btn-main"><?php echo __( 'Pay Using' );?></button>
					</div>
				</div>
			</div>
        </div>
        <div class="payment_v_modalx modal modal-sm">
			<div class="modal-content">
				<h6 class="bold"><?php echo __( 'unlock_private_video_payment' );?></h6>
				<div class="modal-body">
					<p><?php echo __( 'to_unlock_private_video_feature_in_your_account__you_have_to_pay' );?> <?php echo $config->currency_symbol . (int)$config->lock_pro_video_fee;?>.</p>

					<div class="modal-footer">
						<button onclick="PayUsingWallet('private_video','show');" class="btn waves-effect waves-light btn-flat btn_primary white-text btn-main"><?php echo __( 'Pay Using' );?></button>
					</div>
				</div>
			</div>
        </div>
		
		<?php if ($config->bank_payment == '1') { ?>
		<div class="bank_transfer_modal modal modal-fixed-footer">
			<div class="modal-dialog">
			<div class="modal-content dt_bank_trans_modal">
				<div class="modal-header">
					<h5 class="modal-title"><?php echo __( 'Bank Transfer' );?></h5>
				</div>
				<div class="modal-body">
					<div class="bank_info"><?php echo htmlspecialchars_decode($config->bank_description);?></div>
					<div class="dt_user_profile hide_alert_info_bank_trans">
						<span class="valign-wrapper">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"></path></svg> <?php echo __( 'Note' );?>:
						</span>
						<ul class="browser-default dt_prof_vrfy">
							<li><?php echo __( 'Please transfer the amount of' );?> <b><span id="bank_transfer_price"></span></b> <?php echo __( 'to this bank account to buy' );?> <b>"<span id="bank_transfer_description"></span>"</b> <?php echo __( 'Plan Premium Membership' );?></li>
							<li><?php echo $config->bank_transfer_note;?></li>
						</ul>
					</div>
					<p class="dt_bank_trans_upl_rec"><a href="javascript:void(0);" onclick="$('.bank_transfer_modal').addClass('up_rec_active'); return false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13.5,16V19H10.5V16H8L12,12L16,16H13.5M13,9V3.5L18.5,9H13Z"></path></svg> <?php echo __( 'Upload Receipt' );?></a></p>
					<div class="upload_bank_receipts">
						<div onclick="document.getElementById('receipt_img').click(); return false">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13.5,16V19H10.5V16H8L12,12L16,16H13.5M13,9V3.5L18.5,9H13Z"></path></svg>
							<p><?php echo __( 'Upload Receipt' );?></p>
							<img id="receipt_img_preview" src="">
						</div>
					</div>
					<input type="file" id="receipt_img" class="hide" accept="image/x-png, image/gif, image/jpeg" name="receipt_img">
				</div>
				<!--<span style="display: block;text-align: center;" id="receipt_img_path"></span>-->
			</div>
			<div class="modal-footer">
				<div class="bank_transfr_progress hide" id="img_upload_progress">
					<div class="progress">
						<div id="img_upload_progress_bar" class="determinate progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
					</div>
				</div>
				<button class="modal-close waves-effect btn-flat"><?php echo __( 'Close' );?></button>
				<button class="waves-effect waves-green btn btn-flat bold" disabled id="btn-upload-receipt" data-selected=""><?php echo __( 'Confirm' );?></button>
			</div>
			</div>
		</div>
		<?php } ?>
		<?php if ($config->aamarpay_payment == '1') { ?>
			<div class="modal modal-sm" id="aamarpay_modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-content">
					<h6 class="bold"><?php echo __('aamarpay');?></h6>
					<div class="modal-body">
						<div id="aamarpay_alert" style="    color: red;"></div>
						<form id="aamarpay_form" method="post">
							<div class="input-field">
								<input type="text" value="<?php echo $profile->full_name;?>" id="aamarpay_name">
								<label for="aamarpay_name"><?php echo __('Name');?></label>
							</div>
							<div class="input-field">
								<input type="email" value="" data-email="<?php echo base64_encode($profile->email);?>" id="aamarpay_email">
								<label for="aamarpay_email"><?php echo __('Email');?></label>
							</div>
							<div class="input-field">
								<input id="aamarpay_number" class="form-control shop_input" type="text">
								<label for="aamarpay_number"><?php echo __('phone');?></label>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn-flat waves-effect modal-close"><?php echo __('cancel');?></button>
								<button type="button" class="btn btn-main waves-effect waves-light btn-flat btn_primary white-text" onclick="AamarpayRequest()" id="aamarpay_button"><?php echo __( 'Pay' );?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if ($config->razorpay_payment == '1' && !empty($config->razorpay_key_id)) { ?>
			<div class="modal modal-sm" id="razorpay_modal" role="dialog" data-keyboard="false" style="overflow-y: auto;">
				<div class="modal-content">
					<h6 class="bold"><?php echo __('razorpay');?></h6>

					<form class="form form-horizontal" method="post" id="razorpay_form" action="#">
						<div class="modal-body twocheckout_modal">
							<div id="razorpay_alert"></div>
							<div class="clear"></div>
							<div class="input-field">
								<input id="razorpay_name" type="text" autocomplete="off">
								<label for="razorpay_name"><?php echo __('name');?></label>
							</div>
							<div class="input-field">
								<input id="razorpay_email" type="text" autocomplete="off">
								<label for="razorpay_email"><?php echo __('email');?></label>
							</div>
							<div class="input-field">
								<input id="razorpay_phone" type="text" autocomplete="off">
								<label for="razorpay_phone"><?php echo __('phone_number');?></label>
							</div>
							<input type="hidden" name="razorpay_type" id="razorpay_type">
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
						<div class="modal-footer">
							<div class="ball-pulse"><div></div><div></div><div></div></div>
							<button type="button" class="btn-flat waves-effect modal-close"><?php echo __('cancel');?></button>
							<button type="button" class="btn btn-main waves-effect waves-light btn-flat btn_primary white-text" id="razorpay_btn" onclick="SignatureRazorpay()"><?php echo __( 'pay' );?></button>
						</div>
					</form>
				</div>
			</div>
			<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
		<?php } ?>
		<?php if ($config->fluttewave_payment == 1) { ?>
			<div class="modal modal-sm" id="fluttewave_modal" role="dialog" data-keyboard="false" style="overflow-y: auto;">
				<div class="modal-content">
					<h6 class="bold"><?php echo __('fluttewave');?></h6>

					<form class="form form-horizontal" method="post" id="fluttewave_form" action="#">
						<div class="modal-body twocheckout_modal">
							<div id="fluttewave_alert"></div>
							<div class="clear"></div>
							<div class="input-field">
								<input id="fluttewave_email" type="text" autocomplete="off">
								<label for="fluttewave_email"><?php echo __('email');?></label>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
						<div class="modal-footer">
							<div class="ball-pulse"><div></div><div></div><div></div></div>
							<button type="button" class="btn-flat waves-effect modal-close"><?php echo __('cancel');?></button>
							<button type="button" class="btn btn-main waves-effect waves-light btn-flat btn_primary white-text" id="fluttewave_btn" onclick="SignatureFluttewave()"><?php echo __( 'pay' );?></button>
						</div>
					</form>
				</div>

			</div>
		<?php } ?>


        <?php if(IS_LOGGED === true){ ?>
            <?php if ($config->checkout_payment == 'yes') { ?>
                <div class="modal modal-sm" id="2checkout_modal" role="dialog" data-keyboard="false" style="overflow-y: auto;">
                        <div class="modal-content">
                            <h6 class="bold"><?php echo __('Check out');?></h6>
                            <form class="form form-horizontal" method="post" id="2checkout_form" action="#">
                                <div class="modal-body twocheckout_modal">
                                    <div id="2checkout_alert"></div>
                                    <div class="clear"></div>
                                    <div class="row">
                                        <div class="input-field col s6 xs12">
                                            <input id="card_name" type="text" autocomplete="off" value="<?php echo $profile->full_name;?>">
											<label for="card_name"><?php echo __('name');?></label>
                                        </div>
                                        <div class="input-field col s6 xs12">
                                            <input id="card_address" type="text" autocomplete="off" value="<?php echo $profile->address;?>">
											<label for="card_address"><?php echo __('address');?></label>
                                        </div>
									</div>
									<div class="row">
                                        <div class="input-field col s6 xs12">
                                            <input id="card_city" type="text" autocomplete="off" value="<?php echo $profile->city;?>">
											<label for="card_city"><?php echo __('city');?></label>
                                        </div>
                                        <div class="input-field col s6 xs12">
                                            <input id="card_state" type="text" autocomplete="off" value="<?php echo $profile->state;?>">
											<label for="card_state"><?php echo __('state');?></label>
                                        </div>
									</div>
									<div class="row">
                                        <div class="input-field col s6 xs12">
                                            <input id="card_zip" type="text" autocomplete="off" value="<?php echo $profile->zip;?>">
											<label for="card_zip"><?php echo __('zip');?></label>
                                        </div>
                                        <div class="input-field col s6 xs12">
                                            <select id="card_country" name="card_country">
                                                <option value="" disabled selected><?php echo __( 'Choose your country' );?></option>
                                                <?php
                                                $_countries = Dataset::load('countries');
                                                foreach( $_countries as $key => $val ){
                                                    echo '<option value="'. $key .'" data-code="'. $val['isd'] .'">'. $val['name'] .'</option>';
                                                }
                                                ?>
                                            </select>
											<label for="card_country"><?php echo __('Choose your country');?></label>
                                        </div>
									</div>
									<div class="row">
                                        <div class="input-field col s6 xs12">
                                            <input id="card_email" type="text" autocomplete="off" value="<?php echo $profile->email;?>">
											<label for="card_email"><?php echo __('email');?></label>
                                        </div>
                                        <div class="input-field col s6 xs12">
                                            <input id="card_phone" type="text" autocomplete="off" value="<?php echo $profile->cc_phone_number;?>">
											<label for="card_phone"><?php echo __('phone number');?></label>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <hr class="border_hr">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="_number_" type="text" autocomplete="off">
											<label for="_number_"><?php echo __('card number');?></label>
                                            <input id="card_number" name="card_number" type="hidden" class="form-control input-md" autocomplete="off">
                                        </div>
									</div>
									<div class="row">
                                        <div class="input-field col s4">
                                            <select id="card_month" name="card_month" type="text" autocomplete="off" placeholder="<?php echo __('month');?> (01)">
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
											<label for="card_month"><?php echo __('month');?></label>
                                        </div>
                                        <div class="input-field col s4 no-padding-both">
                                            <select id="card_year" name="card_year" type="text" placeholder="<?php echo __('year');?> (2021)">
                                                <?php for ($i=date('Y'); $i <= date('Y')+15; $i++) {  ?>
                                                    <option value="<?php echo($i) ?>"><?php echo($i) ?></option>
                                                <?php } ?>
                                            </select>
											<label for="card_year"><?php echo __('year');?></label>
                                        </div>
                                        <div class="input-field col s4">
                                            <input id="card_cvc" name="card_cvc" type="text" autocomplete="off" maxlength="3" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
											<label for="card_cvc">CVC</label>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <input type="hidden" id="2checkout_type" class="hidden" name="payType">
                                    <input type="hidden" id="2checkout_description" class="hidden" name="description">
                                    <input type="hidden" id="2checkout_price" class="hidden" name="price">
                                    <input id="card_token" name="token" type="hidden" value="">
                                </div>
                                <div class="clear"></div>
                                <div class="modal-footer">
                                    <div class="ball-pulse"><div></div><div></div><div></div></div>
									<button type="button" class="btn-flat waves-effect modal-close"><?php echo __('cancel');?></button>
                                    <button type="button" class="btn btn-main waves-effect waves-light btn-flat btn_primary white-text" onclick="tokenRequest()" id="2checkout_btn"><?php echo __('pay');?></button>
                                </div>
                            </form>
                        </div>
                </div>

                <script type="text/javascript">
                    // Called when token created successfully.
                    var successCallback = function(data) {
                        var myForm = document.getElementById('2checkout_form');
                        $.post(window.ajax + 'checkout/createsession', {
                            card_number: $("#card_number").val(),
                            card_cvc: $("#card_cvc").val(),
                            card_month: $("#card_month").val(),
                            card_year: $("#card_year").val(),
                            card_name: $("#card_name").val(),
                            card_address: $("#card_address").val(),
                            card_city: $("#card_city").val(),
                            card_state: $("#card_state").val(),
                            card_zip: $("#card_zip").val(),
                            card_country: $("#card_country").val(),
                            card_email: $("#card_email").val(),
                            card_phone: $("#card_phone").val(),
                            token: data.response.token.token,
                            payType: $("#2checkout_type").val(),
                            description: $("#2checkout_description").val(),
                            price: $("#2checkout_price").val()
                        }, function(data, textStatus, xhr) {
                            $('#2checkout_btn').html("<?php echo __('pay');?>");
                            $('#2checkout_btn').attr('disabled');
                            $('#2checkout_btn').removeAttr('disabled');
                            $('#2checkout_form').find('.ball-pulse').fadeOut(100);
                            if (data.status == 200) {
                                window.location.href = data.url;
                            } else {
                                $('#2checkout_alert').html("<div class='alert alert-danger'>"+data.error+"</div>");
                                setTimeout(function () {
                                    $('#2checkout_alert').html("");
                                },3000);
                            }
                            /*optional stuff to do after success */
                        });
                    };

                        // Called when token creation fails.
                    var errorCallback = function(data) {
                        $('#2checkout_btn').html("<?php echo __('pay');?>");
                        $('#2checkout_btn').removeAttr('disabled');
                        $('#2checkout_form').find('.ball-pulse').fadeOut(100);
                        if (data.errorCode === 200) {
                            tokenRequest();
                        } else {
                            $('#2checkout_alert').html("<div class='alert alert-danger'>"+data.errorMsg+"</div>");
                            setTimeout(function () {
                                $('#2checkout_alert').html("");
                            },3000);
                        }
                    };

                    var tokenRequest = function() {
                        $('#card_number').val($('#_number_').val());
                        $('#2checkout_btn').html("<?php echo __('please wait');?>");
                        $('#2checkout_btn').attr('disabled','true');
                        if ($("#card_number").val() != '' && $("#card_cvc").val() != '' && $("#card_month").val() != '' && $("#card_year").val() != '' && $("#card_name").val() != '' && $("#card_address").val() != '' && $("#card_city").val() != '' && $("#card_state").val() != '' && $("#card_zip").val() != '' && $("#card_country").val() != 0 && $("#card_email").val() != '' && $("#card_phone").val() != '') {
                            $('#2checkout_form').find('.ball-pulse').fadeIn(100);
                            // Setup token request arguments
                            var args = {
                                sellerId: "<?php echo($config->checkout_seller_id) ?>",
                                publishableKey: "<?php echo($config->checkout_publishable_key) ?>",
                                ccNo: $("#card_number").val(),
                                cvv: $("#card_cvc").val(),
                                expMonth: $("#card_month").val(),
                                expYear: $("#card_year").val()
                            };

                            // Make the token request
                            TCO.requestToken(successCallback, errorCallback, args);
                        }
                        else{
                            $('#2checkout_btn').html("<?php echo __('pay');?>");
                            $('#2checkout_btn').removeAttr('disabled');
                            $('#2checkout_alert').html("<div class='alert alert-danger'><?php echo __('please check details');?></div>");
                            setTimeout(function () {
                                $('#2checkout_alert').html("");
                            },3000);

                        }
                    };

                    $(function() {
                        try {
                            // Pull in the public encryption key for our environment
                            TCO.loadPubKey("<?php echo($config->checkout_mode) ?>");
                        } catch(e) {
                            console.log(e.toSource());
                        }

                    });
                </script>

            <?php } 
        }?>


        <!-- Boost Modal -->
		<div id="modal_boost" class="modal modal-sm">
			<div class="modal-content">
				<?php
					$_cost = 0;
					if( $profile->is_pro == "1" ){
						$_cost = $config->pro_boost_me_credits_price;
					}else{
						$_cost = $config->normal_boost_me_credits_price;
					}
                    if ( isGenderFree($profile->gender) === true ) {
                        $_cost = 0;
                    }
				?>
				<h6 class="bold"><?php echo __('Boost me!');?></h6>
				<p><?php echo __('Get seen more by people around you in find match.');?></p>
				<p><?php
                    if ( isGenderFree($profile->gender) === true ) {
                        echo __('Boost your profile for free for') . ' ' . $config->boost_expire_time . ' ' . __('minutes') . '.';
                    }else {
                        echo __('This service costs you') . ' ' . $_cost . ' ' . __('credits and will last for') . ' ' . $config->boost_expire_time . ' ' . __('miuntes') . '.';
                    }
                    ?></p>
				<div class="modal-footer">
					<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
					<?php if($profile->balance >= $_cost ){?>
						<button data-userid="<?php echo $profile->id;?>" id="btn_boostme" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php
                            if ( isGenderFree($profile->gender) === true ) {
                                echo __( 'Boost For Free' );
                            }else{
                                echo __( 'Boost Now' );
                            }


                            ?></button>
					<?php }else{ ?>
						<a href="<?php echo $site_url;?>/credit" data-ajax="/credit" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Buy Credits' );?></a>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- End Boost Modal -->
		
		
		<!-- Message Modal Modal -->
		<div id="message_modal" class="modal modal-sm">
			<div class="modal-content">

				<h6 class="bold"><?php echo __('Chat!');?></h6>
				<p><?php echo __('Please subscribe to access chat features!!');?></p>
					
					<div class="modal-footer">
					<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
					
					<a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Subscribe' );?></a>
				</div>
			</div>
		</div>
        

		<div id="calldeny_modal" class="modal modal-sm">
			<div class="modal-content">

				<h6 class="bold"><?php echo __('Chat!');?></h6>
				<p><?php echo __('You can only make calls if subscribed and you are matches');?></p>
					
					<div class="modal-footer">
					<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
					
					<a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Subscribe' );?></a>
				</div>
			</div>
		</div>
        


		<div id="call_modal" class="modal modal-sm">
			<div class="modal-content">

				
				<p>
					<a onclick="audioCall();" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Audio Call' );?></a>
				</p>
				
				<p>
					<a onclick="videoCall();" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Video Call' );?></a>
				</p>

					
					<div class="modal-footer">
					<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
					
				</div>
			</div>
		</div>
        
        
		<div id="age_modal" class="modal modal-sm">
			<div class="modal-content">

				<h6 class="bold"><?php echo __('Age Range Problem');?></h6>
				<p><?php echo __('Please make sure minimum age does not exceed maximum age');?></p>
					
					<div class="modal-footer">
					<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
                </div>
			</div>
		</div>

				
		<!-- Message Modal Modal -->
		<div id="district_modal" class="modal modal-sm">
			<div class="modal-content">

				<h6 class="bold"><?php echo __('Send a Message!');?></h6>
				<p><?php echo __('Please subscribe to send a filter by district!!');?></p>
					
					<div class="modal-footer">
					<button type="button" class="btn-flat waves-effect modal-close"><?php echo __( 'Cancel' );?></button>
					
					<a href="<?php echo $site_url;?>/pro" data-ajax="/pro" class="modal-close waves-effect waves-light btn-flat btn_primary white-text"><?php echo __( 'Subscribe' );?></a>
				</div>
			</div>
		</div>
		<!-- End Message Modal -->


		
		<div class="sidenav_overlay" onclick="$('body').toggleClass('side_open');<?php if(!empty($_COOKIE['open_slide']) && $_COOKIE['open_slide'] == 'yes'){ ?>SlideEraseCookie('open_slide')<?php }else{ ?>SlideSetCookie('open_slide','yes',1);<?php } ?>"></div>
	<?php } ?>
		
	<!-- Scroll to top  -->
    <a href="javascript:void(0);" class="btn-floating btn-large waves-effect waves-light dt_to_top bg_gradient"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z"></path></svg></a>
    <!-- End Scroll to top  -->

	<?php //require( $theme_path . 'main' . $_DS . 'geolocation.php' );?>
    <?php require( $theme_path . 'main' . $_DS . 'custom-footer-js.php' );?>
    <?php //write_console();?>
	
	<script type="text/javascript">
		<?php if (empty($_COOKIE['pop_up_18']) && !IS_LOGGED && route( 1 ) != 'age-block' && route( 1 ) != 'login' && $config->pop_up_18 == 'on') { ?>
			$(document).ready(function(){
				$(document).on('click', '#pop_up_18_yes', function(event) {
		           event.preventDefault();
		           $.post(window.ajax + 'useractions/yes_18', function(data, textStatus, xhr) {
		            	$('#pop_up_18').modal('close');
		           });
		        });

		        $(document).on('click', '#pop_up_18_no', function(event) {
		           event.preventDefault();
		           $.post(window.ajax + 'useractions/no_18', function(data, textStatus, xhr) {
		                $('#pop_up_18').modal('close');
		                window.location.href = window.site_url
		           });
		        });
				$('#pop_up_18').modal('open');
			});
         <?php } ?>
		$(document).on('click', '.find_matches_cont > .row > .col.l3 .dt_sections [data-ajax]', function() {
			$('body').removeClass('side_open');
		});
        $('#open_slide').on('click', function(event) {
			event.preventDefault();
			$('body').toggleClass('side_open');
		});
			function SlideSetCookie(cname, cvalue, exdays) {
			  $('#open_slide').attr('onclick', "SlideEraseCookie('open_slide')");
				var d = new Date();
				d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
				var expires = "expires="+d.toUTCString();
				document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
			}
			function SlideEraseCookie(name) {
			  $('#open_slide').attr('onclick', "SlideSetCookie('open_slide','yes',1)");
				document.cookie = name + '=;path=/;Max-Age=0'
			}
		</script>

    <?php if( IS_LOGGED == true ){ ?>
        <div style="display:none">
            <audio id="notification-sound" class="sound-controls" preload="auto" style="visibility: hidden;">
                <source src="<?php echo $theme_url; ?>assets/mp3/New-notification.mp3" type="audio/mpeg">
            </audio>
            <audio id="message-sound" class="sound-controls" preload="auto" style="visibility: hidden;">
                <source src="<?php echo $theme_url; ?>assets/mp3/New-message.mp3" type="audio/mpeg">
            </audio>
            <audio id="calling-sound" class="sound-controls" preload="auto">
                <source src="<?php echo $theme_url; ?>assets/mp3/calling.mp3" type="audio/mpeg">
            </audio>
            <audio id="video-calling-sound" class="sound-controls" preload="auto">
                <source src="<?php echo $theme_url; ?>assets/mp3/video_call.mp3" type="audio/mpeg">
            </audio>
        </div>
    <?php } ?>

    <?php if(route( 1 ) !== 'find-matches'){ ?>
    <script>
        window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#ffffff",
                        "text": "#afafaf"
                    },
                    "button": {
                        "background": "#c649b8"
                    }
                },
                "theme": "edgeless",
                "position": "bottom-left",
                "content": {
                    "message": "<?php echo __('This website uses cookies to ensure you get the best experience on our website.');?>",
                    "dismiss": "<?php echo __('Got It!');?>",
                    "link": "<?php echo __('Learn More');?>",
                    "href": "<?php echo $site_url;?>/privacy"
                }
            });
        });
    </script>
    <?php } ?>

    <?php if( IS_LOGGED == true ){ ?>
    <?php if ($config->cashfree_payment == 'yes') { ?>
        <div class="modal modal-sm" id="cashfree_modal_box" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content add_money_modal add_address_modal">
                <h4 class="modal-title text-center"><?php echo __('Cashfree');?></h4>
                <div class="modal-body">
                    <div id="cashfree_alert"></div>
                    <form id="cashfree_form" method="post">
                    <div class="form-group">
                        <label class="plr15" for="cashfree_name"><?php echo __('name');?></label>
                        <input class="form-control shop_input" type="text" placeholder="<?php echo __('name');?>" value="<?php echo $profile->full_name; ?>" id="cashfree_name" name="name">
                    </div>
                    <div class="form-group">
                        <label class="plr15" for="cashfree_email"><?php echo __('email');?></label>
                        <input class="form-control shop_input" type="email" placeholder="<?php echo __('email');?>" value="<?php echo $profile->email; ?>" id="cashfree_email" name="email">
                    </div>
                    <div class="form-group">
                        <label class="plr15" for="cashfree_phone"><?php echo __('phone_number');?></label>
                        <input class="form-control shop_input" type="text" placeholder="<?php echo __('phone_number');?>" id="cashfree_phone" name="phone" value="<?php echo $profile->phone_number; ?>">
                    </div>

                    <input id="cashfree_type" name="cashfree_type" type="hidden" value="">
                    <input id="cashfree_description" name="cashfree_description" type="hidden" value="">
                    <input id="cashfree_price" name="cashfree_price" type="hidden" value="0">

                    <div class="modal-footer">
                        <button class="waves-effect waves-light btn-flat btn_primary white-text" id="cashfree_button" type="button" onclick="InitializeCashfree()"><?php echo __('pay');?></button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <script>
        $(document).ready(function(){
            $('#cashfree_modal_box').modal();
        });
        function InitializeCashfree() {
            $('#cashfree_button').html("<?php echo __('please_wait');?>");
            $('#cashfree_button').attr('disabled','true');
            name = $('#cashfree_name').val();
            phone = $('#cashfree_phone').val();
            email = $('#cashfree_email').val();
            amount = $('#cashfree_price').val();
            description = $('#cashfree_description').val();
            payType = $('#cashfree_type').val();
            $.post(window.ajax + 'cashfree/createsession', {
                name:name,
                phone:phone,
                email:email,
                description:description,
                price:amount,
                payType:payType
            }, function(data) {
            if (data.status == 200) {
                $('body').append(data.html);
                document.getElementById("redirectForm").submit();
            } else {
                $('#cashfree_alert').html("<div class='alert alert-danger'>"+data.message+"</div>");
                setTimeout(function () {
                $('#cashfree_alert').html("");
                },3000);
            }
            $('#cashfree_button').html("<?php echo __('pay');?>");
            $('#cashfree_button').removeAttr('disabled');
            });
        }
        </script>
    <?php } ?>

    <?php if ($config->iyzipay_payment === "yes" && !empty($config->iyzipay_key) && !empty($config->iyzipay_secret_key)) { ?>
        <div id="iyzipay_content"></div>
        <script>
            function unlock_photo_private_pay_via_iyzipay(){
                $('.btn-iyzipay-payment').attr('disabled','true');

                $.post(window.ajax + 'iyzipay/createsession', {
                    payType: 'unlock_private_photo',
                    description: '<?php echo __( "Unlock Private photo feature");?>',
                    price: <?php echo (int)$config->lock_private_photo_fee;?>
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
        </script>
    <?php } ?>
    <?php if ($config->securionpay_payment === "yes") { ?>
        <script type="text/javascript">
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
        function unlock_photo_private_pay_via_securionpay(){
            price = <?php echo (int)$config->lock_private_photo_fee;?>;
            $.post(window.ajax + 'securionpay/token', {type:'unlock_private_photo',price:price}, function(data, textStatus, xhr) {
                if (data.status == 200) {
                    SecurionpayCheckout.open({
                        checkoutRequest: data.token,
                        name: 'unlock private photo',
                        description: '<?php echo __( "Unlock Private photo feature");?>'
                    });
                }
            }).fail(function(data) {
                M.toast({html: data.responseJSON.message});
            });
        }
    </script>
    <?php } ?>
    <?php if ($config->authorize_payment === "yes") { ?>
        
        <script type="text/javascript">
            function unlock_photo_private_pay_via_authorize(){
                $('#authorize_btn').attr('onclick', 'AuthorizePhotoRequest()');
                $('#authorize_modal').modal('open');
            }
            function AuthorizePhotoRequest() {
                $('#authorize_btn').html("<?php echo __('please_wait');?>");
                $('#authorize_btn').attr('disabled','true');
                authorize_number = $('#authorize_number').val();
                authorize_month = $('#authorize_month').val();
                authorize_year = $('#authorize_year').val();
                authorize_cvc = $('#authorize_cvc').val();
                price = <?php echo (int)$config->lock_private_photo_fee;?>;
                $.post(window.ajax + 'authorize/pay', {type:'unlock_private_photo',card_number:authorize_number,card_month:authorize_month,card_year:authorize_year,card_cvc:authorize_cvc,price:price}, function(data) {
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
        </script>
    <?php } ?>
    <?php if ($config->paystack_payment === "yes") { ?>
        <script>
            function unlock_photo_private_pay_via_paystack(){
                $('#paystack_btn').attr('onclick', 'InitializePhotoPaystack()');
                $('#paystack_wallet_modal').modal('open');
            }
            function InitializePhotoPaystack() {
                $('#paystack_btn').html("<?php echo __('please_wait');?>");
                $('#paystack_btn').attr('disabled','true');
                email = $('#paystack_wallet_email').val();
                price = <?php echo (int)$config->lock_private_photo_fee;?>;
                $.post(window.ajax + 'paystack/initialize', {type:'unlock_private_photo',email:email,price:price}, function(data) {
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
            
           
        </script>
    <?php } ?>
<?php } ?>
</body>

<script>
	
	var message_popup = true;

	 function audioCall(){
		
		message_popup = false;
		$("#btn_open_private_conversation").click();

		setTimeout(function(){
			$("#audio_call").click();
			message_popup = true;

		}, 2000);
	}
	
	function videoCall(){
		
		message_popup = false;
		$("#btn_open_private_conversation").click();
				
		setTimeout(function(){
			$("#video_call").click();
			message_popup = true;

		}, 2000);
	}
	
</script>
</html>
