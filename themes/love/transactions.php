
<?php echo '<script src="'. $theme_url . 'assets/js/spin.js" type="text/javascript" /></script>'; ?>


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
			
			
						<?php if( $profile->admin == 1){ ?>    
				
			<?php
				$requests = $db->objectbuilder()->where("status = 0 AND via IN ('FDH Bank','First Capital Bank','NBS Bank','Centenary Bank','TNM Mpamba','Mukuru','National Bank','Standard Bank') ")->orderBy('id', 'DESC')->get('payment_requests');
			?>
            <div class="container-fluid">
                <div class="dt_home_rand_user">
                    <h4 class="bold"><?php echo __( 'Pending Transactions Approvals' );?></h4>
                    <div class="dt_settings no_margin_top">
						<?php
							if( empty( $requests ) ){
								echo '<div  class="dt_sections"><h5 class="empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M0.93,4.2L2.21,2.93L20,20.72L18.73,22L16.73,20H4C2.89,20 2,19.1 2,18V6C2,5.78 2.04,5.57 2.11,5.38L0.93,4.2M20,8V6H7.82L5.82,4H20A2,2 0 0,1 22,6V18C22,18.6 21.74,19.13 21.32,19.5L19.82,18H20V12H13.82L9.82,8H20M4,8H4.73L4,7.27V8M4,12V18H14.73L8.73,12H4Z" /></svg>' . __( 'No pending transactions found.' ) . '</h5></div>';
							}else{
						?>
							<table class="approvals-table highlight responsive-table dt_transaction_table">
								<thead>
								<tr>
									<th><?php echo __('Date & Name </br> & Phone');?></th>
									<th><?php echo __('Via');?></th>
									<th><?php echo __('Amount');?></th>
									<th><?php echo __('Type');?></th>
									<th><?php echo 'Category';?></th>
									<th><?php echo __('POP');?></th>
									<th><?php echo 'Actions';?></th>

								</tr>
								</thead>
								<tbody>
									<?php
									foreach ($requests as $request) {
										$user = $db->where('id', $request->user_id)->getOne('users');
										$name = $user['first_name'] . ' ' . $user['last_name'];
										
										echo '<tr>';
										echo '  <td>'.$request->created_at. '</br>' . $name.'</br>'.$request->phone_number .'</td>';
										echo '  <td>'.$request->via.'</td>';
										echo '  <td>'.$config->currency_symbol . $request->amount.'</td>';
										echo '  <td class="upper">'.$request->type.'</td>';
										echo '  <td>';
										if( $request->pro_plan > 0 ){
											if( $request->pro_plan == 1 ){
												echo __('WEEKLY');
											}
											if( $request->pro_plan == 2 ){
												echo __('MONTHLY');
											}
											if( $request->pro_plan == 3 ){
												echo __('YEARLY');
											}
											if( $request->pro_plan == 4 ){
												echo __('LIFETIME');
											}
											if( $request->pro_plan == 5 ){
												echo __('DAILY');
											}
										}
										if($request->pro_plan == 0 ){
											echo $request->amount .' ' . __(' Credits');
										}
										echo '  </td>';	
										
										echo '  <td>'.$request->transaction_id.'</td>';
									
										echo '  <td>';
										
												echo "<button onclick='openRejectModal($request->id);' class='btn btn-danger' style='margin: 5px;width: 100px;height: 35px; background: tomato;'>Reject</button>";
												echo "<button onclick='openConfirmModal($request->id);' style='margin: 5px;width: 100px;height: 35px;' class='btn btn-primary'>Confirm</button>";

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
			<?php } ?>

            
            
            <!-- People i liked  -->
            <div class="container-fluid">
                <div class="dt_home_rand_user">
                    <h4 class="bold"><?php echo __( 'My Transactions' );?></h4>
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
									<th><?php echo __('Processed Via');?></th>
									<th><?php echo __('Amount');?></th>
									<th><?php echo __('Type');?></th>
									<th><?php echo 'Category';?></th>
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
											if( $paymentlist->pro_plan == 5 ){
												echo __('DAILY');
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



<div class="modal" id="confirm-modal">
            <div class="modal-dialog">
                <div class="modal-content dt_bank_trans_modal">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body  credit_pln">
                        <p>Are you sure you want to CONFIRM the transaction!!</p>
                    </div>
                    
                     <div class="modal-footer">
						<button onclick=" $('#confirm-modal').modal('close'); " style="background: tomato;"
						class="btn waves-effect waves-light btn-flat btn_primary white-text btn-main"><?php echo __( 'Cancel' );?></button>
						
						<button onclick="confirmPayment(); " 
						class="btn waves-effect waves-light btn-flat btn_primary white-text btn-main"><?php echo __( 'Confirm' );?></button>
					</div>
			
		</div>
	</div>
</div>

<div class="modal" id="reject-modal">
            <div class="modal-dialog">
                <div class="modal-content dt_bank_trans_modal">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body  credit_pln">
                        <p>Are you sure you want to REJECT the transaction!!</p>
                    </div>
                    
                     <div class="modal-footer">
						<button onclick=" $('#reject-modal').modal('close'); " style="background: tomato;"
						class="btn waves-effect waves-light btn-flat btn_primary white-text btn-main"><?php echo __( 'Cancel' );?></button>
						
						<button onclick="rejectPayment(); " 
						class="btn waves-effect waves-light btn-flat btn_primary white-text btn-main"><?php echo __( 'Reject' );?></button>
					</div>
			
		</div>
	</div>
</div>
        
<style>
	table th, table td, table button{
		font-size: 0.9em !important;
	}
		
	#spin {
		position: fixed !important;
		top: 50% !important;
		left: 52% !important;
		margin-top: -50px !important;
		margin-left: -50px !important;
		width: 100px;
		height: 100px;
	}
</style>

<script>
	
	var transID;
	function openConfirmModal(id){
		$('#confirm-modal').modal('open');
		transID = id;
	}
	
	function openRejectModal(id){
		$('#reject-modal').modal('open');
		transID = id;
	}
	
	function confirmPayment(){
		
		showSpinner();

		$.post(window.ajax + 'airtelmoney/updateManualSession', {
            transID: transID,
			actionToDo: 'Confirm'
        }, function(data) {
			if (data.status == 200) {
				console.log(data);
				window.location = window.location;
			}
		});
	}
	
	function rejectPayment(){
		
		showSpinner();

		$.post(window.ajax + 'airtelmoney/updateManualSession', {
            transID: transID,
			actionToDo: 'Reject'
        }, function(data) {
			if (data.status == 200) {
				console.log(data);
				window.location = window.location;
			}
		});
	}
</script>
