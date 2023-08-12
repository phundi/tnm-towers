<?php
global $db;
$payments = $db->objectbuilder()->where('user_id',$profile->id)->orderBy('id', 'DESC')->get('payments');
?>

<?php require( $theme_path . 'main' . $_DS . 'mini-sidebar.php' );?>

<!-- Settings  -->
<div class="container container-fluid container_new page-margin find_matches_cont">
	<div class="row r_margin">
        <div class="col l3">
            <?php require( $theme_path . 'main' . $_DS . 'sidebar.php' );?>
        </div>

        <div class="col l9">
            <!-- People i liked  -->
            <div class="container-fluid">
                <div class="dt_home_rand_user">
                    <h4 class="bold"><?php echo __( 'Transactions' );?></h4>
                    <div class="dt_settings no_margin_top">
						<?php
							if( empty( $payments ) ){
								echo '<div class="dt_sections"><h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M0.93,4.2L2.21,2.93L20,20.72L18.73,22L16.73,20H4C2.89,20 2,19.1 2,18V6C2,5.78 2.04,5.57 2.11,5.38L0.93,4.2M20,8V6H7.82L5.82,4H20A2,2 0 0,1 22,6V18C22,18.6 21.74,19.13 21.32,19.5L19.82,18H20V12H13.82L9.82,8H20M4,8H4.73L4,7.27V8M4,12V18H14.73L8.73,12H4Z" /></svg>' . __( 'No transactions found.' ) . '</h5></div>';
							}else{
						?>
							<table class="highlight responsive-table dt_transaction_table">
								<thead>
								<tr>
									<th><?php echo __('Date');?></th>
									<th><?php echo __('Processed By');?></th>
									<th><?php echo __('Amount');?></th>
									<th><?php echo __('Type');?></th>
									<th><?php echo __('Notes');?></th>
								</tr>
								</thead>
								<tbody>
									<?php
									foreach ($payments as $paymentlist) {
										echo '<tr>';
										echo '  <td>'.$paymentlist->date.'</td>';
										echo '  <td>'.$paymentlist->via.'</td>';
										echo '  <td>'.$config->currency_symbol . $paymentlist->amount.'</td>';
										echo '  <td class="upper">'.$paymentlist->type.'</td>';
										echo '  <td>';
										if( $paymentlist->pro_plan > 0 ){
											if( $paymentlist->pro_plan == 1 ){
												echo __('WEEKLY');
											}
											if( $paymentlist->pro_plan == 2 ){
												echo __('MONTHLY');
											}
											if( $paymentlist->pro_plan == 3 ){
												echo __('YEARLY');
											}
											if( $paymentlist->pro_plan == 4 ){
												echo __('LIFETIME');
											}
										}
										if($paymentlist->credit_amount > 0 ){
											echo $paymentlist->credit_amount .' ' . __(' Credits');
										}
										echo '  </td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						<?php } ?>
					</div>
                </div>
            </div>
            <!-- People i liked -->
        </div>
    </div>
</div>