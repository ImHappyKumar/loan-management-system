<?php include 'db_connect.php' ?>

<div class="container-fluid" style="margin-bottom: 58px;">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>File Charge List</b>
					<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" id="new_file_charges"><i class="fa fa-plus" style="width: 20px;"></i> New File Charge</button>
				</large>

			</div>
			<div class="card-body" style="overflow-x:auto;">
				<table class="table table-bordered" id="loan-list">
					<colgroup>
						<col width="5%">
						<col width="20%">
						<col width="20%">
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
							
							$qry = $conn->query("SELECT f.*,l.ref_no,concat(b.firstname,' ',b.lastname)as name, b.contact_no, b.address from file_charges f inner join loan_list l on l.id = f.loan_id inner join borrowers b on b.id = l.borrower_id  order by f.id asc");
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
						 		<?php echo $row['collect_by'] ?>
						 	</td>
						 	<td class="text-center">
						 		<?php echo $row['date_created'] ?>
						 	</td>
							<?php if($_SESSION['login_type'] == 1): ?>
								<td class="text-center">
										<button class="btn btn-outline-primary btn-sm edit_file_charge" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
										<!-- <button class="btn btn-outline-danger btn-sm delete_file_charge" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button> -->
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
	$('#new_file_charges').click(function(){
		uni_modal("New File Charge","manage_file_charges.php",'mid-large')
	})
	$('.edit_file_charge').click(function(){
		uni_modal("Edit File Charge","manage_file_charges.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_file_charge').click(function(){
		_conf("Are you sure to delete this File Charge?","delete_file_charge",[$(this).attr('data-id')])
	})
function delete_file_charge($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_file_charge',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					$('.modal').modal('hide')
					alert_toast("File Charge successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else {
					$('.modal').modal('hide')
					alert_toast("Unable to delete this File Charge",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>