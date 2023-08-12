<?php if (!isset($_POST['ajax'])) {?><?php require( $theme_path . 'main' . $_DS . 'header.php' );?>
    <?php
        if( isset( $_SESSION['JWT'] ) ){
            require( $theme_path . 'main' . $_DS . 'nav-logged-in.php' );
        }else{
            require( $theme_path . 'main' . $_DS . 'nav-not-logged-in.php' );
        }
    ?>
    <?php if ($data['name'] !== 'login' && $data['name'] !== 'contact' && $data['name'] !== 'register' && $data['name'] !== 'forgot' && $data['name'] !== 'reset' && $data['name'] !== 'verifymail' && $data['name'] !== 'index' && $data['name'] !== 'home' && IS_LOGGED) { ?>
    <div class="container" style="transform: none;"><?php echo GetAd('header');?></div>
    <?php } ?>
    <div id="container">
<?php } ?>
        <?php require($file_path);?>
<?php if (!isset($_POST['ajax'])) {?>
    </div>
    <a href="javascript:void(0);" id="ajaxRedirect" style="visibility: hidden;display: none;"></a>
    <?php require( $theme_path . 'main' . $_DS . 'full-footer.php' );?>
    <?php require( $theme_path . 'main' . $_DS . 'footer.php' );?>
<?php } ?>
<?php if ($config->filter_by_cities == 1 && !empty($config->geo_username)) { ?>
<script type="text/javascript">
    function ChangeCountryKey(self) {
        $('.city_country_key').val($(self).find(":selected").val());
    }
    function SearchForCity(self) {
        let country_code = $('.city_country_key').val();
        let city = $(self).val();
        if (city.length >= 3) {
            $.post('https://fast-dawn-89938.herokuapp.com/http://api.geonames.org/searchJSON?name='+city+'&username=<?php echo($config->geo_username); ?>&cities=cities1000&country='+country_code, {},function (data) {
                if (typeof data != 'undefined' && typeof data.geonames != 'undefined') {
                    $('.city_search_result').html('');
                    data.geonames.forEach(element => {
                        $('.city_search_result').append('<p onclick="AddCityToFilter(this)">'+element.name+'</p>');
                    });
                }
            });
        }
        //return clearTimeout(CitySearchTime);
    }
    function AddCityToFilter(self) {
        $('.city_search_result').html('');
        $('.selected_city').val($(self).text());
    }
</script>
<?php } ?>



<script type="text/javascript">
    function PayUsingWallet(type,type2 = 'hide') {
        let price = 0;
        let main_price = 0;
        if (type == 'pro') {
            price = main_price = getPrice();
        }
        else if(type == 'private_photo'){
            price = <?php echo (int)$config->lock_private_photo_fee;?>;
        }
        else if(type == 'private_video'){
            price = <?php echo (int)$config->lock_pro_video_fee;?>;
        }
        price = parseInt(price * <?php echo $config->credit_price; ?>);
        $('.pay_modal_wallet_alert').html("");
        if (type2 == 'show') {
            if (type == 'pro') {
                $('.pay_modal_wallet_text').html("<?php echo __( 'pay_to_upgrade' ); ?>");
                $('#pay_modal_wallet_btn').html("<?php echo __( 'pay' );?> "+'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="margin-top: -3px;"><path fill="currentColor" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,17V16H9V14H13V13H10A1,1 0 0,1 9,12V9A1,1 0 0,1 10,8H11V7H13V8H15V10H11V11H14A1,1 0 0,1 15,12V15A1,1 0 0,1 14,16H13V17H11Z"></path></svg> '+price);
                $('#pay_modal_wallet_btn').removeAttr('disabled');
            }
            else if(type == 'private_photo'){
                $('.pay_modal_wallet_text').html("<?php echo __( 'pay_to_unlock_private_photo' ); ?>");
                $('#pay_modal_wallet_btn').html("<?php echo __( 'pay' );?> "+'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="margin-top: -3px;"><path fill="currentColor" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,17V16H9V14H13V13H10A1,1 0 0,1 9,12V9A1,1 0 0,1 10,8H11V7H13V8H15V10H11V11H14A1,1 0 0,1 15,12V15A1,1 0 0,1 14,16H13V17H11Z"></path></svg> '+price);
                $('#pay_modal_wallet_btn').removeAttr('disabled');
            }
            else if(type == 'private_video'){
                $('.pay_modal_wallet_text').html("<?php echo __( 'pay_to_unlock_private_video' ); ?>");
                $('#pay_modal_wallet_btn').html("<?php echo __( 'pay' );?> "+'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="margin-top: -3px;"><path fill="currentColor" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,17V16H9V14H13V13H10A1,1 0 0,1 9,12V9A1,1 0 0,1 10,8H11V7H13V8H15V10H11V11H14A1,1 0 0,1 15,12V15A1,1 0 0,1 14,16H13V17H11Z"></path></svg> '+price);
                $('#pay_modal_wallet_btn').removeAttr('disabled');
            }
            $('#pay_modal_wallet_btn').attr('onclick', "PayUsingWallet('"+type+"')");
            
            if (parseInt(<?php echo (!empty($profile) && !empty($profile->balance)) ? $profile->balance : 0; ?>) < price ) {
                $('.pay_modal_wallet_alert').html('<div class="wallet_empty_state"><p><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><linearGradient xmlns="http://www.w3.org/2000/svg" id="paint0_linear_4_349" gradientUnits="userSpaceOnUse" x1="256" x2="256" y1="11.115" y2="490.464"><stop stop-opacity="1" stop-color="#ffe8e8" offset="0"/><stop stop-opacity="1" stop-color="#ffb4b4" offset="1"/></linearGradient><linearGradient xmlns="http://www.w3.org/2000/svg" id="paint1_linear_4_349" gradientUnits="userSpaceOnUse" x1="256.5" x2="256.5" y1="99" y2="414"><stop stop-opacity="1" stop-color="#ff4646" offset="0"/><stop stop-opacity="1" stop-color="#ff8585" offset="1"/></linearGradient><path xmlns="http://www.w3.org/2000/svg" d="m494.999 114.319c-1.425-25.3479-12.136-49.2826-30.088-67.2346s-41.887-28.6635-67.234-30.0893c-94.374-5.3333-188.974-5.3333-283.349 0-25.3474 1.4267-49.2822 12.1382-67.2347 30.0899-17.9524 17.9517-28.6651 41.886-30.0928 67.234-5.3333 94.376-5.3333 188.978 0 283.353 1.4278 25.348 12.1404 49.282 30.0929 67.234 17.9525 17.951 41.8873 28.663 67.2346 30.089 94.374 5.346 188.975 5.346 283.349 0 25.347-1.425 49.282-12.137 67.234-30.089 17.952-17.951 28.663-41.886 30.088-67.234 5.334-94.375 5.334-188.977 0-283.353z" fill="url(#paint0_linear_4_349)" data-original="url(#paint0_linear_4_349)" class=""/><g xmlns="http://www.w3.org/2000/svg" fill="url(#paint1_linear_4_349)"><path clip-rule="evenodd" d="m413.958 223.497.042 65.382c-.419 6.229-.958 12.45-1.619 18.66-1.105 10.385-8.784 18.761-18.427 19.946-30.202 3.711-61.456 3.711-91.659 0-9.643-1.185-17.321-9.561-18.426-19.946-3.608-33.91-3.608-68.168 0-102.078 1.105-10.385 8.783-18.761 18.426-19.946 30.203-3.711 61.457-3.711 91.659 0 9.643 1.185 17.322 9.561 18.427 19.946.639 6.002 1.164 12.016 1.577 18.036zm-65.833 5.932c-13.6 0-24.625 12.12-24.625 27.071s11.025 27.071 24.625 27.071 24.625-12.12 24.625-27.071-11.025-27.071-24.625-27.071z" fill-rule="evenodd" fill=""/><path d="m404 148.934c2.553 4.86-2.238 10.301-7.311 9.678-32.02-3.934-65.109-3.934-97.129 0-20.838 2.56-37.691 20.599-40.149 43.704-3.83 36-3.83 72.368 0 108.368 2.458 23.105 19.311 41.144 40.149 43.704 32.02 3.934 65.109 3.934 97.129 0 5.107-.628 9.937 4.852 7.359 9.738-12.781 24.234-35.569 41.148-62.05 44.211l-10.701 1.238c-54.353 6.288-109.175 5.873-163.444-1.236l-7.09-.929c-29.257-3.832-52.438-28.96-56.335-61.065-7.2375-59.633-7.2375-120.057 0-179.69 3.897-32.105 27.078-57.233 56.334-61.065l7.091-.929c54.269-7.1092 109.091-7.5239 163.444-1.236l10.701 1.238c26.503 3.066 49.255 20.005 62.002 44.271z" fill=""/></g></g></svg>&nbsp;&nbsp;&nbsp;<span><?php echo __('please_top_up_credits')?></span></p><div><a href="javascript:void(0)" onclick="SetPageCookie(\''+type+'\',\''+type+'\')" class="btn waves-effect waves-light btn_primary white-text"><?php echo __('wallet')?></a></div></div>');
                $('#pay_modal_wallet_btn').attr('disabled','true');
                $('.pay_modal_wallet_text').html("");
            }
            $('#pay-go-pro').modal('open');
        }
        else{
            $('#pay_modal_wallet_btn').html("<?php echo __('please wait');?>");
            $('#pay_modal_wallet_btn').attr('disabled','true');
            let link = '';
            if (type == 'pro') {
                link = '?pay_for=pro&price='+main_price;
            }
            else if (type == 'private_photo'){
                link = '?pay_for=private_photo';
            }
            else if (type == 'private_video'){
                link = '?pay_for=private_video';
            }
            $.get(window.ajax + 'wallet/pay' + link, function (data) {
                if (data.status == 200) {
                    location.href = data.url;
                }

            }).fail(function(data) {
                $('#pay_modal_wallet_btn').html("<?php echo __( 'pay' );?> "+'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="margin-top: -3px;"><path fill="currentColor" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,17V16H9V14H13V13H10A1,1 0 0,1 9,12V9A1,1 0 0,1 10,8H11V7H13V8H15V10H11V11H14A1,1 0 0,1 15,12V15A1,1 0 0,1 14,16H13V17H11Z"></path></svg> '+price);
                $('#pay_modal_wallet_btn').removeAttr('disabled');
                $('#payu_btn').removeAttr('disabled');
                $('.pay_modal_wallet_alert').html("<div class='alert alert-danger'>"+data.responseJSON.message+"</div>");
            });
        }
            
    }
    function SetPageCookie(type,id){
        $.get(window.ajax + 'wallet/set?type=' + type, function (data) {
            location.href = window.site_url+'/credit';
        });
    }
</script>