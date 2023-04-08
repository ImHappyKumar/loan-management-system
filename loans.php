<?php include 'db_connect.php' ?>
<style>
	
</style>
<div class="container-fluid" style="margin-bottom: 58px;">

<div class="col-lg-12">
	<div class="card">
		<div class="card-header" style="color: white !important;">
			<large class="card-title">
				<b>Loan List</b>
				<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" id="new_application"><i class="fa fa-plus" style="width: 20px;"></i> New Loan Application</button>
			</large>
		</div>
		<div class="card-body" style="overflow-x:auto;">
			<table class="table table-bordered" id="loan-list">
				<colgroup style="overflow-x:auto;">
					<col width="5%">
					<col width="17%">
					<col width="32%">
					<col width="15%">
					<col width="15%">
					<?php if($_SESSION['login_type'] == 1): ?>
						<col width="15%">
					<?php endif; ?>
				</colgroup>
				<thead>
					<tr>
						<th class="text-center text-white">#</th>
						<th class="text-center text-white">Borrower</th>
						<th class="text-center text-white">Loan Details</th>
						<th class="text-center text-white">Dates</th>
						<th class="text-center text-white">Status</th>
						<?php if($_SESSION['login_type'] == 1): ?>
							<th class="text-center text-white">Action</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
						
						$i=1;
						$type = $conn->query("SELECT * FROM loan_types where id in (SELECT loan_type_id from loan_list) ");
						while($row=$type->fetch_assoc()){
							$type_arr[$row['id']] = $row['type_name'];
						}
						$plan = $conn->query("SELECT *,concat(days,' day/s [ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id in (SELECT plan_id from loan_list) ");
						while($row=$plan->fetch_assoc()){
							$plan_arr[$row['id']] = $row;
						}

						$qry = $conn->query("SELECT l.*,concat(b.firstname,' ',b.lastname)as name, b.contact_no, b.email, b.address, b.aadhaar, b.pan from loan_list l inner join borrowers b on b.id = l.borrower_id  order by id asc");
						while($row = $qry->fetch_assoc()):
							$daily = ($row['amount'] + ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100))) / $plan_arr[$row['plan_id']]['days'];
							$days = $plan_arr[$row['plan_id']]['days'];	// used for finding final date
							$total = $daily * $plan_arr[$row['plan_id']]['days'];
							$penalty = $daily * ($plan_arr[$row['plan_id']]['penalty_rate']/100);
							$payments = $conn->query("SELECT * from payments where loan_id =".$row['id']);
							$paid = $payments->num_rows;
							$offset = $paid > 0 ? " offset $paid ": "";
							$sum_paid = 0;
							while($p = $payments->fetch_assoc()){
								$sum_paid += ($p['amount'] - $p['penalty_amount']);
							}
							$remain = $total-$sum_paid;
							$file_charges = $conn->query("SELECT * from file_charges where loan_id =".$row['id']);
							$file_charge=($row['amount']*2/100);
							while($f = $file_charges->fetch_assoc()) {
								$file_charge -= $f['amount'];
							}

						?>
						<tr>
						
						<td class="text-center"><?php echo $i++ ?></td>
						<td>
							<p>Name: <b><?php echo $row['name'] ?></b></p>
							<p><small>Contact No: <b><?php echo $row['contact_no'] ?></small></b></p>
							<p><small>Email ID: <b><?php echo $row['email'] ?></small></b></p>
							<p><small>Aadhaar No: <b><?php echo $row['aadhaar'] ?></small></b></p>
							<p><small>PAN: <b><?php echo $row['pan'] ?></small></b></p>
							<p><small>Address: <b><?php echo $row['address'] ?></small></b></p>
						</td>
						<td>
							<p>Reference: <b><?php echo $row['ref_no'] ?></b></p>
							<p><small>Loan type: <b><?php echo $type_arr[$row['loan_type_id']] ?></small></b></p>
							<p><small>Plan: <b><?php echo $plan_arr[$row['plan_id']]['plan'] ?></small></b></p>
							<p><small>Amount: <b><?php echo $row['amount'] ?></small></b></p>
							<p><small>Total Payable Amount: <b><?php echo number_format($total,2) ?></small></b></p>
							<p><small>Daily Payable Amount: <b><?php echo number_format($daily,2) ?></small></b></p>
							<p><small>Overdue Payable Amount: <b><?php echo number_format($penalty,2) ?></small></b></p>
							<p><small>Remaining Payable Amount: <b><?php echo number_format($remain,2) ?></small></b></p>
						</td>
						<td>
							<p><small>Date Created: <b><?php echo date("M d, Y",strtotime($row['date_created'])) ?></small></b></p>
							<?php if($row['status'] == 2 || $row['status'] == 3): ?>
							<p><small>Date Released: <b><?php echo date("M d, Y",strtotime($row['date_released'])) ?></small></b></p>
							<?php
								$date_released = date("Y-m-d",strtotime($row['date_released']));
								$date_final = new DateTime($date_released); // Y-m-d
								$date_final->add(new DateInterval('P'.$days.'D'));
							?>
							<p><small>Date Final: <b><?php echo $date_final->format('M d, Y') ?></small></b></p>
							<?php endif; ?>
						</td>
						<td class="text-center">
													
							<?php if($file_charge<=0 && $row['status']<=1): 
								$update = $conn->query("UPDATE loan_list SET status='1' WHERE id=".$row['id']); ?>
							<?php endif; ?>
							<?php if($remain <= 0 && $file_charge <= 0 && $row['status']<=3): 
								$update = $conn->query("UPDATE loan_list SET status='3' WHERE id=".$row['id']); ?>
							<?php endif; ?>
							<?php if($row['status'] == 0): ?>
								<span class="badge badge-warning">For Approval</span>
							<?php elseif($row['status'] == 1): ?>
								<span class="badge badge-info">Approved</span>
							<?php elseif($row['status'] == 2): ?>
								<span class="badge badge-primary">Released</span>
							<?php elseif($row['status'] == 3): ?>
								<span class="badge badge-success">Completed</span>
							<?php elseif($row['status'] == 4): ?>
								<span class="badge badge-danger">Denied</span>
							<?php endif; ?>
						</td>
						<?php if($_SESSION['login_type'] == 1): ?>
							<td class="text-center">
									<button class="btn btn-outline-primary btn-sm edit_loan" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
									<button class="btn btn-outline-danger btn-sm delete_loan" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
							</td>
						<?php endif; ?>
						</tr>

					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
<style>
	td p {
		margin:unset;
	}
	td img {
	    width: 8vw;
	    height: 12vh;
	}
	td{
		vertical-align: middle !important;
	}
</style>	
<script>
	$('#loan-list').dataTable()
	$('#new_application').click(function(){
		uni_modal("New Loan Application","manage_loan.php",'mid-large')
	})
	$('.edit_loan').click(function(){
		uni_modal("Edit Loan","manage_loan.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_loan').click(function(){
		_conf("Are you sure to delete this Loan?","delete_loan",[$(this).attr('data-id')])
	})
function delete_loan($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_loan',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					$('.modal').modal('hide')
					alert_toast("Loan successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else {
					$('.modal').modal('hide')
					alert_toast("Unable to delete this Loan",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>