<?php include 'db_connect.php' ?>

<div class="container-fluid" style="margin-bottom: 58px;">

	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>Expenditure List</b>
				</large>
				<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" id="new_expenditure"><i class="fa fa-plus" style="width: 20px;"></i> New Expenditure</button>
			</div>
			<div class="card-body" style="overflow-x:auto;">
				<table class="table table-bordered" id="expenditure-list">
					<colgroup>
						<col width="5%">
						<col width="20%">
						<col width="30%">
						<col width="10%">
						<col width="15%">
						<?php if($_SESSION['login_type'] == 1): ?>
							<col width="15%">
						<?php endif; ?>
					</colgroup>
					<thead>
						<tr>
							<th class="text-center text-white">#</th>
							<th class="text-center text-white">User</th>
							<th class="text-center text-white">Purpose</th>
							<th class="text-center text-white">Amount</th>
							<th class="text-center text-white">Date</th>
							<?php if($_SESSION['login_type'] == 1): ?>
								<th class="text-center text-white">Action</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
							$qry = $conn->query("SELECT * FROM expenditure order by id asc");
							while($row = $qry->fetch_assoc()):

							?>
							<tr>
							
							<td class="text-center"><?php echo $i++ ?></td>
							<td><?php echo $row['user'] ?></td>
							<td><?php echo $row['purpose'] ?></td>
							<td><?php echo $row['amount'] ?></td>
							<td><?php echo $row['date_created'] ?></td>
							<?php if($_SESSION['login_type'] == 1): ?>
								<td class="text-center">
										<button class="btn btn-outline-primary btn-sm edit_expenditure" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
										<button class="btn btn-outline-danger btn-sm delete_expenditure" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
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
	$('#expenditure-list').dataTable()
	$('#new_expenditure').click(function(){
		uni_modal("New Expenditure","manage_expenditure.php",'mid-large')
	})
	$('.edit_expenditure').click(function(){
		uni_modal("Edit Expenditure","manage_expenditure.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_expenditure').click(function(){
		_conf("Are you sure to delete this Expenditure?","delete_expenditure",[$(this).attr('data-id')])
	})
function delete_expenditure($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_expenditure',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					$('.modal').modal('hide')
					alert_toast("Expenditure successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else {
					$('.modal').modal('hide')
					alert_toast("Unable to delete this Expenditure",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>