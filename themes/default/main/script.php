<?php
    foreach ($data['jsfiles'] as $key => $js_file) {
        echo '<script src="'. $theme_url . $js_file . '" type="text/javascript" id="' . $key . '"/></script>';
    }
    if( isset( $_SESSION['JWT'] ) ){
        require_once ( $theme_path . 'main' . $_DS . 'timeago.php' );
        echo '<script src="'. $theme_url . 'assets/js/chat.js" type="text/javascript" id="chat-script"/></script>';
        //echo '<script src="https://checkout.stripe.com/checkout.js"></script>';
    }
    if ($config->agora_chat_call == 1 || $config->agora_live_video == 1) {
    	echo '<script src="'. $theme_url . 'assets/js/agora.js" type="text/javascript"/></script>';
        echo '<link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />';
        echo '<script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script>';
    }
    if ($config->securionpay_payment == 'yes') {
        echo '<script src="https://securionpay.com/checkout.js"></script>';
    }
