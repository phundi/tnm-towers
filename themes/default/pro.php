<?php if( $profile->is_pro == 1 ){?><script>window.location = window.site_url;</script><?php } ?>
<?php if( $config->pro_system == 0 ){?><script>window.location = window.site_url;</script><?php } ?>
<?php if( isGenderFree($profile->gender) === true ){?><script>window.location = window.site_url;</script><?php } ?>
<!-- Premium  -->
<div class="container page-margin find_matches_cont">
	<div class="row r_margin">
		<div class="col l3">
			<?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
		</div>
		
		<div class="col l9">
			<div class="dt_premium dt_sections dt_pro_pg">
				<?php if (file_exists($theme_path . 'third-party-payment.php')) { ?>
					<?php require( $theme_path . 'third-party-payment.php' );?>
			    <?php } ?>
				<div class="dt_p_head center">
					<h2 class="light gold-text"><?php echo __( 'Amazing' );?> <?php echo ucfirst( $config->site_name );?> <?php echo __( 'features you can’t live without' );?>.</h2>
					<p class="bold"><?php echo __( 'Activating Premium will help you meet more people, faster.' );?></p>
				</div>
				<div class="row dt_prem_row">
					<div class="col s12 m7 l5">
						<div class="dt_choose_pro">
							<h2><?php echo __( 'Choose a Plan' );?></h2>
							<p>
								<label>
									<input class="with-gap" name="pro_plan" type="radio" value="<?php echo __( 'Weekly' );?>" data-price="<?php echo (float)$config->weekly_pro_plan;?>"/>
									<span class="pln_name">
										<span class="duration"><?php echo __( 'Weekly' );?></span>
										<span class="price"><?php echo $config->currency_symbol . (float)$config->weekly_pro_plan;?></span>
									</span>
								</label>
							</p>
							<p>
								<label>
									<input class="with-gap" name="pro_plan" type="radio" value="<?php echo __( 'Monthly' );?>" data-price="<?php echo (float)$config->monthly_pro_plan;?>" checked />
									<span class="pln_name">
										<span class="pln_popular"><span><?php echo __( 'Most popular' );?></span><span class="pln_popular_tail"></span></span>
										<span class="duration"><?php echo __( 'Monthly' );?></span>
										<span class="price"><?php echo $config->currency_symbol . (float)$config->monthly_pro_plan;?></span>
									</span>
								</label>
							</p>
							<p>
								<label>
									<input class="with-gap" name="pro_plan" type="radio" value="<?php echo __( 'Yearly' );?>" data-price="<?php echo (float)$config->yearly_pro_plan;?>"/>
									<span class="pln_name">
										<span class="duration"><?php echo __( 'Yearly' );?></span>
										<span class="price"><?php echo $config->currency_symbol . (float)$config->yearly_pro_plan;?></span>
									</span>
								</label>
							</p>
							<p>
								<label>
									<input class="with-gap" name="pro_plan" type="radio" value="<?php echo __( 'Lifetime' );?>" data-price="<?php echo (float)$config->lifetime_pro_plan;?>"/>
									<span class="pln_name">
										<span class="duration"><?php echo __( 'Lifetime' );?></span>
										<span class="price"><?php echo $config->currency_symbol . (float)$config->lifetime_pro_plan;?></span>
									</span>
								</label>
							</p>
							<div class="pay_using center">
								<p class="bold"><?php echo __( 'Pay Using' );?></p>
								<?php
								$method_type = 'pro';
								require( $theme_path . 'partails' . $_DS . 'modals'. $_DS .'payment_methods.php' );?>

							</div>
						</div>
					</div>
					<div class="col s12 m5 l7">
						<div class="dt_pro_features">
							<h2><?php echo __( 'Why Choose Premium Membership' );?></h2>
							<div class="row">
								<div class="col s12 m12 l4 center">
									<span class="red darken-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5.5,2C3.56,2 2,3.56 2,5.5V18.5C2,20.44 3.56,22 5.5,22H16L22,16V5.5C22,3.56 20.44,2 18.5,2H5.5M5.75,4H18.25A1.75,1.75 0 0,1 20,5.75V15H18.5C16.56,15 15,16.56 15,18.5V20H5.75A1.75,1.75 0 0,1 4,18.25V5.75A1.75,1.75 0 0,1 5.75,4M14.44,6.77C14.28,6.77 14.12,6.79 13.97,6.83C13.03,7.09 12.5,8.05 12.74,9C12.79,9.15 12.86,9.3 12.95,9.44L16.18,8.56C16.18,8.39 16.16,8.22 16.12,8.05C15.91,7.3 15.22,6.77 14.44,6.77M8.17,8.5C8,8.5 7.85,8.5 7.7,8.55C6.77,8.81 6.22,9.77 6.47,10.7C6.5,10.86 6.59,11 6.68,11.16L9.91,10.28C9.91,10.11 9.89,9.94 9.85,9.78C9.64,9 8.95,8.5 8.17,8.5M16.72,11.26L7.59,13.77C8.91,15.3 11,15.94 12.95,15.41C14.9,14.87 16.36,13.25 16.72,11.26Z" /></svg>
									</span>
									<p><?php echo __( 'See more stickers on chat' );?></p>
								</div>
								<div class="col s12 m12 l4 center">
									<span class="pink darken-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" /></svg>
									</span>
									<p><?php echo __( 'Show in Premium bar' );?></p>
								</div>
								<div class="col s12 m12 l4 center">
									<span class="purple darken-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21M19.75,3.19L18.33,4.61C20.04,6.3 21,8.6 21,11H23C23,8.07 21.84,5.25 19.75,3.19M1,11H3C3,8.6 3.96,6.3 5.67,4.61L4.25,3.19C2.16,5.25 1,8.07 1,11Z" /></svg>
									</span>
									<p><?php echo __( 'See likes notifications' );?></p>
								</div>
							</div>
							<div class="row">
								<div class="col s12 m12 l4 center">
									<span class="green darken-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M18.65,2.85L19.26,6.71L22.77,8.5L21,12L22.78,15.5L19.24,17.29L18.63,21.15L14.74,20.54L11.97,23.3L9.19,20.5L5.33,21.14L4.71,17.25L1.22,15.47L3,11.97L1.23,8.5L4.74,6.69L5.35,2.86L9.22,3.5L12,0.69L14.77,3.46L18.65,2.85M9.5,7A1.5,1.5 0 0,0 8,8.5A1.5,1.5 0 0,0 9.5,10A1.5,1.5 0 0,0 11,8.5A1.5,1.5 0 0,0 9.5,7M14.5,14A1.5,1.5 0 0,0 13,15.5A1.5,1.5 0 0,0 14.5,17A1.5,1.5 0 0,0 16,15.5A1.5,1.5 0 0,0 14.5,14M8.41,17L17,8.41L15.59,7L7,15.59L8.41,17Z" /></svg>
									</span>
									<p><?php echo __( 'Get discount when buy boost me' );?></p>
								</div>
								<div class="col s12 m12 l4 center">
									<span class="blue darken-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,16L19.36,10.27L21,9L12,2L3,9L4.63,10.27M12,18.54L4.62,12.81L3,14.07L12,21.07L21,14.07L19.37,12.8L12,18.54Z" /></svg>
									</span>
									<p><?php echo __( 'Display first in find matches' );?></p>
								</div>
								<div class="col s12 m12 l4 center">
									<span class="teal darken-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13,15L15.5,17.5L16.92,16.08L12,11.16L7.08,16.08L8.5,17.5L11,15V21H13V15M3,3H21V5H3V3M3,7H13V9H3V7Z" /></svg>
									</span>
									<p><?php echo __( 'Display on top in random users' );?></p>
								</div>
							</div>
							<div class="row">
                                <?php if($config->video_chat == 1 && $config->audio_chat == 1){ ?>
                                    <div class="col s12 m12 l4 center">
                                        <span class="pink darken-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17,10.5L21,6.5V17.5L17,13.5V17A1,1 0 0,1 16,18H4A1,1 0 0,1 3,17V7A1,1 0 0,1 4,6H16A1,1 0 0,1 17,7V10.5M14,16V15C14,13.67 11.33,13 10,13C8.67,13 6,13.67 6,15V16H14M10,8A2,2 0 0,0 8,10A2,2 0 0,0 10,12A2,2 0 0,0 12,10A2,2 0 0,0 10,8Z"></path></svg>
                                        </span>
                                        <p><?php echo __( 'Video and Audio calls to all users' );?></p>
                                    </div>
                                <?php } ?>

                                <div class="col s12 m12 l4 center">
									<span class="indigo darken-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12,2C15.31,2 18,4.66 18,7.95C18,12.41 12,19 12,19C12,19 6,12.41 6,7.95C6,4.66 8.69,2 12,2M12,6A2,2 0 0,0 10,8A2,2 0 0,0 12,10A2,2 0 0,0 14,8A2,2 0 0,0 12,6M20,19C20,21.21 16.42,23 12,23C7.58,23 4,21.21 4,19C4,17.71 5.22,16.56 7.11,15.83L7.75,16.74C6.67,17.19 6,17.81 6,18.5C6,19.88 8.69,21 12,21C15.31,21 18,19.88 18,18.5C18,17.81 17.33,17.19 16.25,16.74L16.89,15.83C18.78,16.56 20,17.71 20,19Z"></path></svg>
									</span>
                                    <p><?php echo __( 'Find potential matches by country' );?></p>
                                </div>
								<div class="col s12 m12 l4 center"></div>
							</div>

						</div>
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

</script>
