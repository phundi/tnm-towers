<?php if( isset( $_SESSION['JWT'] ) ){?>
<script>
        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    $.post( window.ajax + 'useractions/save_user_location', {lat: position.coords.latitude, lng:position.coords.longitude}, function(data, textStatus, xhr) {
                        if ( data.status == 200) {
                            if (data.info == 'hide') {
                                $('.location_alert_update').slideUp();
                                $.post(window.ajax + 'loadmore/match_users',{page:1}, function(data) {
                                    $('#section_match_users').html(`<div class="valign-wrapper dt_home_match_user">
                                                <div class="mtc_usr_avtr" id="avaters_item_container">
                                                    ${data.html_imgs}
                                                </div>
                                                <div class="mtc_usr_details" id="match_item_container">
                                                    ${data.html}
                                                </div>
                                            </div>`);
                                })
                                .fail(function() {
                                    //alert( "error" );
                                });
                            }
                            else if(data.info == 'show'){
                                $('#section_match_users').html(`<h5 id="_load_more" class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z"></path></svg><?php echo(__('view_no_more_to_show')) ?></h5>`);
                                $('.location_alert_update').html(`<div class="alert alert-warning"><?php echo __('please_enable_location'); ?></div>`);
                            }
                            return true;
                        }
                    });
                },
                function errorCallback(error) {
                    $.getJSON("https://extreme-ip-lookup.com/json/",
                    function(result) {
                        $.post( window.ajax + 'useractions/save_user_location', {lat: result.lat, lng:result.lon}, function(data, textStatus, xhr) {
                            if ( data.status == 200) {
                                if (data.info == 'hide') {
                                    $('.location_alert_update').slideUp();
                                    $.post(window.ajax + 'loadmore/match_users',{page:1}, function(data) {
                                    $('#section_match_users').html(`<div class="valign-wrapper dt_home_match_user">
                                                <div class="mtc_usr_avtr" id="avaters_item_container">
                                                        ${data.html_imgs}
                                                    </div>
                                                    <div class="mtc_usr_details" id="match_item_container">
                                                        ${data.html}
                                                    </div>
                                                </div>`);
                                    })
                                    .fail(function() {
                                        //alert( "error" );
                                    });
                                }
                                else if(data.info == 'show'){
                                    $('#section_match_users').html(`<h5 id="_load_more" class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9,4A4,4 0 0,1 13,8A4,4 0 0,1 9,12A4,4 0 0,1 5,8A4,4 0 0,1 9,4M9,6A2,2 0 0,0 7,8A2,2 0 0,0 9,10A2,2 0 0,0 11,8A2,2 0 0,0 9,6M9,13C11.67,13 17,14.34 17,17V20H1V17C1,14.34 6.33,13 9,13M9,14.9C6.03,14.9 2.9,16.36 2.9,17V18.1H15.1V17C15.1,16.36 11.97,14.9 9,14.9M15,4A4,4 0 0,1 19,8A4,4 0 0,1 15,12C14.53,12 14.08,11.92 13.67,11.77C14.5,10.74 15,9.43 15,8C15,6.57 14.5,5.26 13.67,4.23C14.08,4.08 14.53,4 15,4M23,17V20H19V16.5C19,15.25 18.24,14.1 16.97,13.18C19.68,13.62 23,14.9 23,17Z"></path></svg><?php echo(__('view_no_more_to_show')) ?></h5>`);
                                    $('.location_alert_update').html(`<div class="alert alert-warning"><?php echo __('please_enable_location'); ?></div>`);
                                }
                                return true;
                            }
                        });
                    }).fail(function() {
                        window.gps_is_not_enabled = true
                    });
                },
                {
                    maximumAge:Infinity,
                    timeout:5000
                }
            );

        }else{
            window.gps_is_not_enabled = true
        }
    </script>
<?php } ?>
