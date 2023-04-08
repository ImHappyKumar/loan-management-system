<?php include 'db_connect.php' ?>
<?php 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM file_charges where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}
?>

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

$qry = $conn->query("SELECT l.*,concat(b.firstname,' ',b.lastname)as name, b.contact_no, b.email, b.aadhaar, b.pan, b.address from loan_list l inner join borrowers b on b.id = l.borrower_id  order by id asc");
while($row = $qry->fetch_assoc()):
	$daily = ($row['amount'] + ($row['amount'] * ($plan_arr[$row['plan_id']]['interest_percentage']/100))) / $plan_arr[$row['plan_id']]['days'];
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

<?php if($file_charge<=0 && $row['status']<=1): 
	$update = $conn->query("UPDATE loan_list SET status='1' WHERE id=".$row['id']); ?>
<?php endif; ?>

<?php endwhile; ?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<form id="manage-file_charge" autocomplete="off">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<div class="row">

				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">Loan Reference No.</label>
						<?php
$loan = $conn->query("SELECT * from loan_list where status = 0 ");
						?>
						<select name="loan_id" id="loan_id" class="custom-select browser-default select2">
							<option value=""></option>
							<?php 
							while($row=$loan->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($loan_id) && $loan_id == $row['id'] ? "selected" : '' ?>><?php echo $row['ref_no'] ?></option>
							<?php endwhile; ?>
						</select>
						
					</div>
				</div>
			</div>
			
			<div class="row" id="fields">
				
			</div>
		</form>
	</div>
</div>

<script>
	$('[name="loan_id"]').change(function(){
		load_file_charges()
	})
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})

	function load_file_charges(){
		start_load()
		$.ajax({
			url:'load_file_charges.php',
			method:"POST",
			data:{id:'<?php echo isset($id) ? $id : "" ?>',loan_id:$('[name="loan_id"]').val()},
			success:function(resp){
				if(resp){
					$('#fields').html(resp)
					end_load()
				}
			}
		})
	}
	
	 $('#manage-file_charge').submit(function(e){
	 	e.preventDefault()
	 	start_load()
	 	$.ajax({
	 		url:'ajax.php?action=save_file_charge',
	 		method:'POST',
	 		data:$(this).serialize(),
	 		success:function(resp){
	 			if(resp == 1){
					$('.modal').modal('hide')
	 				alert_toast("File Charge successfully added",'success');
	 				setTimeout(function(e){
	 					location.reload()
	 				},1500)
	 			}
				else if(resp==2){
					$('.modal').modal('hide')
					alert_toast("File Charge successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else if(resp==0){
					$('.modal').modal('hide')
					alert_toast("Please fill out all fields of form",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else {
					$('.modal').modal('hide')
					alert_toast("Unable to add this File Charge",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
	 		}
	 	})
	 })
	 $(document).ready(function(){
	 	if('<?php echo isset($_GET['id']) ?>' == 1)
		load_file_charges()
	 })
</script>