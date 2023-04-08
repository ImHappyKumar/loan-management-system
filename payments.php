<?php include 'db_connect.php' ?>

<div class="container-fluid" style="margin-bottom: 58px;">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>Payment List</b>
					<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" id="new_payments"><i class="fa fa-plus" style="width: 20px;"></i> New Payment</button>
				</large>

			</div>
			<div class="card-body" style="overflow-x:auto;">
				<table class="table table-bordered" id="loan-list">
					<colgroup>
						<col width="5%">
						<col width="15%">
						<col width="15%">
						<col width="10%">
						<col width="10%">
						<col width="15%">
						<col width="20%">
						<?php if($_SESSION['login_type'] == 1): ?>
							<col width="25%">
						<?php endif; ?>
					</colgroup>
					<thead>
						<tr>
							<th class="text-center text-white">#</th>
							<th class="text-center text-white">Loan Reference No</th>
							<th class="text-center text-white">Payee</th>
							<th class="text-center text-white">Amount</th>
							<th class="text-center text-white">Penalty</th>
							<th class="text-center text-white">Collect By</th>
							<th class="text-center text-white">Date & Time</th>
							<?php if($_SESSION['login_type'] == 1): ?>
								<th class="text-center text-white">Action</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
							
							$i=1;
							
							$qry = $conn->query("SELECT p.*,l.ref_no,concat(b.firstname,' ',b.lastname)as name, b.contact_no, b.address from payments p inner join loan_list l on l.id = p.loan_id inner join borrowers b on b.id = l.borrower_id  order by p.id asc");
							while($row = $qry->fetch_assoc()):
								

						 ?>
						 <tr>
						 	
						 	<td class="text-center"><?php echo $i++ ?></td>
						 	<td>
						 		<?php echo $row['ref_no'] ?>
						 	</td>
						 	<td>
						 		<?php echo $row['payee'] ?>
						 	</td>
						 	<td>
						 		<?php echo number_format($row['amount'],2) ?>
						 		
						 	</td>
						 	<td class="text-center">
						 		<?php echo number_format($row['penalty_amount'],2) ?>
						 	</td>
						 	<td class="text-center">
						 		<?php echo $row['collect_by'] ?>
						 	</td>
						 	<td class="text-center">
						 		<?php echo $row['date_created'] ?>
						 	</td>
							<?php if($_SESSION['login_type'] == 1): ?>
								<td class="text-center">
										<button class="btn btn-outline-primary btn-sm edit_payment" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
										<button class="btn btn-outline-danger btn-sm delete_payment" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
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
	$('#new_payments').click(function(){
		uni_modal("New Payment","manage_payment.php",'mid-large')
	})
	$('.edit_payment').click(function(){
		uni_modal("Edit Payment","manage_payment.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_payment').click(function(){
		_conf("Are you sure to delete this Payment?","delete_payment",[$(this).attr('data-id')])
	})
function delete_payment($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_payment',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					$('.modal').modal('hide')
					alert_toast("Payment successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else {
					$('.modal').modal('hide')
					alert_toast("Unable to delete this Payment",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>