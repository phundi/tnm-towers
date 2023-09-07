<div class="dt_notifs <?php if($notification->seen == 0 ){ echo 'noti_not_seen'; }else{ echo ''; }?>">
    <a href="javascript:void(0);" data-ajax="<?php echo $notification->url;?>" class="valign-wrapper">
        <div class="avatar">
            <?php if( ((int)$pro != 1 ) || in_array( $notification->type ,array('approve_receipt','disapprove_receipt','coinpayments_canceled','coinpayments_approved') ) ) {?>
                <img src="<?php echo $theme_url;?>assets/img/icon.png" />
            <?php }else{ ?>
                <img src="<?php echo $avater;?>" alt="<?php echo $username;?>" />
            <?php } ?>
        </div>
        <div class="n_detail">
            <p>
                <?php 
                if ((int)$pro != 1 ){$username = "Some One";}
                if( !in_array( $notification->type ,array('approve_receipt','disapprove_receipt','coinpayments_canceled','coinpayments_approved') ) ) {?><b <?php echo $style;?>><?php echo $username;?></b><br><?php }?>

                <?php echo $text;?>
            </p>
        </div>
		<div class="time ajax-time" title="<?php echo date('c', $notification->created_at);?>"><?php echo get_time_ago($notification->created_at);?></div>
    </a>
</div>
