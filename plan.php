<?php include('db_connect.php');?>

<div class="container-fluid">

<?php if($_SESSION['login_type'] == 1): ?>
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4" style="margin-bottom: 20px;">
			<form action="" id="manage-plan" autocomplete="off">
				<div class="card">
					<div class="card-header">
						   Loan Plan's Form
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Numbers of days</label>
								<input type="number" name="days" id="" class="form-control text-right">
							</div>
							<div class="form-group">
								<label class="control-label">Interest</label>
								<div class="input-group">
								  <input type="number" step="any" min="0" max="100" class="form-control text-right" name="interest_percentage" aria-label="Interest">
								  <div class="input-group-append">
								    <span class="input-group-text">%</span>
								  </div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Daily overdue's penalty</label>
								<div class="input-group">
								  <input type="number" step="any" min="0" max="100" class="form-control text-right" aria-label="Penalty percentage" name="penalty_rate" value=0>
								  <div class="input-group-append">
								    <span class="input-group-text">%</span>
								  </div>
								</div>
							</div>
							
							
							
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-secondary col-sm-3" type="button" onclick="_reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8" style="margin-bottom: 58px;">
				<div class="card">
					<div class="card-body" style="overflow-x:auto;">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th class="text-center text-white">#</th>
									<th class="text-center text-white">Plan</th>
									<th class="text-center text-white">Action</th>
								</tr>
							</thead>
							<tbody style="background-color: white !important;">
								<?php 
								$i = 1;
								$plan = $conn->query("SELECT * FROM loan_plan order by id asc");
								while($row=$plan->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										 <p>Days: <b><?php echo $row['days']. " days" ?></b></p>
										 <p><small>Interest: <b><?php echo $row['interest_percentage']."%" ?></b></small></p>
										 <p><small>Overdue's Penalty: <b><?php echo $row['penalty_rate']."%" ?></b></small></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_plan" type="button" data-id="<?php echo $row['id'] ?>" data-days="<?php echo $row['days'] ?>" data-interest_percentage="<?php echo $row['interest_percentage'] ?>" data-penalty_rate="<?php echo $row['penalty_rate'] ?>" >Edit</button>
										<button class="btn btn-sm btn-danger delete_plan" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	
	<?php endif; ?>

<?php if($_SESSION['login_type'] == 2): ?>
	<div>Only admin can see the loan plans. Staff members are not allowed to see this page</div>
	<?php endif; ?>
</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	function _reset(){
		$('#cimg').attr('src','');
		$('[name="id"]').val('');
		$('#manage-plan').get(0).reset();
	}
	
	$('#manage-plan').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_plan',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Loan Plan successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else if(resp==2){
					alert_toast("Loan Plan successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else if(resp==0){
					alert_toast("Please fill out all fields of form",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else {
					alert_toast("Unable to add this Loan Plan",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
	$('.edit_plan').click(function(){
		start_load()
		var plan = $('#manage-plan')
		plan.get(0).reset()
		plan.find("[name='id']").val($(this).attr('data-id'))
		plan.find("[name='days']").val($(this).attr('data-days'))
		plan.find("[name='interest_percentage']").val($(this).attr('data-interest_percentage'))
		plan.find("[name='penalty_rate']").val($(this).attr('data-penalty_rate'))
		end_load()
	})
	$('.delete_plan').click(function(){
		_conf("Are you sure to delete this Loan Plan?","delete_plan",[$(this).attr('data-id')])
	})
	function displayImg(input,_this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	$('#cimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
	function delete_plan($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_plan',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Loan Plan successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else {
					alert_toast("Unable to delete this Loan Plan",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>