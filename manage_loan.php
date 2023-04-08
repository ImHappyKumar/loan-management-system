<?php 
include('db_connect.php');
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM loan_list where id = ".$_GET['id']);
foreach($qry->fetch_array() as $k => $v){
	$$k = $v;
}
}
?>
<div class="container-fluid">
	<div class="col-lg-12">
	<form action="" id="loan-application" autocomplete="off">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
		<div class="row">
			<div class="col-md-6">
				<label class="control-label">Borrower</label>
				<?php
				$borrower = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM borrowers order by concat(firstname,' ',lastname) asc ");
				?>
				<select name="borrower_id" id="borrower_id" class="custom-select browser-default select2">
					<option value=""></option>
						<?php while($row = $borrower->fetch_assoc()): ?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($borrower_id) && $borrower_id == $row['id'] ? "selected" : '' ?>><?php echo $row['name'] . ' | Aadhaar: '.$row['aadhaar'] ?></option>
						<?php endwhile; ?>
				</select>
			</div>
			<div class="col-md-6">
				<label class="control-label">Loan Type</label>
				<?php
				$type = $conn->query("SELECT * FROM loan_types order by `type_name` desc ");
				?>
				<select name="loan_type_id" id="loan_type_id" class="custom-select browser-default select2">
					<option value=""></option>
						<?php while($row = $type->fetch_assoc()): ?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($loan_type_id) && $loan_type_id == $row['id'] ? "selected" : '' ?>><?php echo $row['type_name'] ?></option>
						<?php endwhile; ?>
				</select>
			</div>
			
		</div>

		<div class="row">
			<div class="col-md-6">
				<label class="control-label">Loan Plan</label>
				<?php
				$plan = $conn->query("SELECT * FROM loan_plan order by `days` desc ");
				?>
				<select name="plan_id" id="plan_id" class="custom-select browser-default select2">
					<option value=""></option>
						<?php while($row = $plan->fetch_assoc()): ?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($plan_id) && $plan_id == $row['id'] ? "selected" : '' ?> data-days="<?php echo $row['days'] ?>" data-interest_percentage="<?php echo $row['interest_percentage'] ?>" data-penalty_rate="<?php echo $row['penalty_rate'] ?>"><?php echo $row['days'] . ' day/s ['.$row['interest_percentage'].'%, '.$row['penalty_rate'].'%]' ?></option>
						<?php endwhile; ?>
				</select>
				<small>Days [Interest, Penalty]</small>
			</div>
		<div class="form-group col-md-6">
			<label class="control-label">Loan Amount</label>
			<input type="number" name="amount" class="form-control text-right" step="any" id="" value="<?php echo isset($amount) ? $amount : '' ?>" require>
		</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
			<label class="control-label">Purpose</label>
			<textarea name="purpose" id="" cols="30" rows="2" class="form-control"><?php echo isset($purpose) ? $purpose : '' ?></textarea>
		</div>

		<div style="margin: 2px auto;">
			<label class="control-label">&nbsp;</label>
			<button class="btn btn-primary btn-sm btn-block align-self-end" type="button" id="calculate">Calculate</button>
		</div>
		</div>
		<div id="calculation_table">
		
		</div>
		<?php if(isset($status)): ?>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">&nbsp;</label>
				<select class="custom-select browser-default" name="status">
					<?php if($status !='2' && $status !='3' && $status !='4' ): ?>
					<option value="0" <?php echo $status == 0 ? "selected" : '' ?>>For Approval</option>
					<option value="1" <?php echo $status == 1 ? "selected" : '' ?>>Approved</option>
					<?php endif ?>
					<?php if($status !='4' ): ?>
					<option value="2" <?php echo $status == 2 ? "selected" : '' ?>>Released</option>
					<?php endif ?>
					<?php if($status =='2' || $status =='3' ): ?>
					<option value="3" <?php echo $status == 3 ? "selected" : '' ?>>Completed</option>
					<?php endif ?>
					<?php if($status != '1' && $status != '2' && $status !='3' ): ?>
					<option value="4" <?php echo $status == 4 ? "selected" : '' ?>>Denied</option>
					<?php endif ?>
				</select>
			</div>
		</div>
		<hr>
	<?php endif ?>
		<!-- <div id="row-field">
			<div class="row ">
				<div class="col-md-12 text-center">
					<button class="btn btn-primary btn-sm " >Save</button>
					<button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div> -->
		
	</form>
	</div>
</div>
<script>
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})
	$('#calculate').click(function(){
		calculate()
	})
	

	function calculate(){
		start_load()
		if($('#loan_plan_id').val() == '' && $('[name="amount"]').val() == ''){
			alert_toast("Select plan and enter amount first.","warning");
			return false;
		}
		var plan = $("#plan_id option[value='"+$("#plan_id").val()+"']")
		$.ajax({
			url:"calculation_table.php",
			method:"POST",
			data:{amount:$('[name="amount"]').val(),days:plan.attr('data-days'),interest:plan.attr('data-interest_percentage'),penalty:plan.attr('data-penalty_rate')},
			success:function(resp){
				if(resp){
					
					$('#calculation_table').html(resp)
					end_load()
				}
			}

		})
	}
	$('#loan-application').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_loan',
			method:"POST",
			data:$(this).serialize(),
			success:function(resp){
				if(resp==1 ){
					$('.modal').modal('hide')
					alert_toast("Loan successfully added","success")
					setTimeout(function(){
						location.reload();
					},1500)
				}
				else if(resp==2){
					$('.modal').modal('hide')
					alert_toast("Loan successfully updated",'success')
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
					alert_toast("Unable to add this Loan",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
	$(document).ready(function(){
		if('<?php echo isset($_GET['id']) ?>' == 1)
			calculate()
	})
</script>

<!-- <style>
	#uni_modal .modal-footer{
		display: none
	}
</style> -->