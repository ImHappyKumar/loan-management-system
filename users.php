<?php 

?>

<div class="container-fluid" style="margin-bottom: 58px;">
<?php if($_SESSION['login_type'] == 1): ?>
	<div class="row">
	<div class="col-lg-12">
			<button class="btn btn-primary float-right btn-sm" id="new_user"><i class="fa fa-plus"></i> New User</button>
	</div>
	</div>
	<br>
	<div class="card col-lg-12">
		<div class="card-body" style="overflow-x:auto;">
			<table class="table-striped table-bordered col-md-12">
				<colgroup>
					<col width="10%">
					<col width="35%">
					<col width="35%">
					<col width="20%">
				</colgroup>
		<thead>
			<tr>
				<th class="text-center text-white" style="padding: 5px 10px !important">#</th>
				<th class="text-center text-white" style="padding: 5px 10px !important">Name</th>
				<th class="text-center text-white" style="padding: 5px 10px !important">Username</th>
				<th class="text-center text-white" style="padding: 5px 10px !important">Action</th>
			</tr>
		</thead>
		<tbody style="background-color: white !important; color: black;">
			<?php
				include 'db_connect.php';
				$users = $conn->query("SELECT * FROM users order by name asc");
				$i = 1;
				while($row= $users->fetch_assoc()):
				?>
				<tr>
				<td class="text-center" style="padding: 2px 10px !important">
					<?php echo $i++ ?>
				</td>
				<td class="text-center" style="padding: 2px 10px !important">
					<?php echo $row['name'] ?>
				</td>
				<td class="text-center" style="padding: 2px 10px !important">
					<?php echo $row['username'] ?>
				</td>
				<td>
					<center>
						<div class="btn-group">
							<button type="button" class="btn btn-primary" style="margin: 2px 0px !important; margin-left: 10px !important;">Action</button>
							<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin: 2px 0px !important; margin-right: 10px !important;">
							<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu">
							<a class="dropdown-item edit_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Edit</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item delete_user" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Delete</a>
							</div>
						</div>
					</center>
				</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
		</div>
	</div>
<?php endif; ?>

<?php if($_SESSION['login_type'] == 2): ?>
	<div>Only admin can see the users. Staff members are not allowed to see this page</div>
	<?php endif; ?>
</div>

<script>
$('#new_user').click(function(){
	uni_modal('New User','manage_user.php')
})
$('.edit_user').click(function(){
	uni_modal('Edit User','manage_user.php?id='+$(this).attr('data-id'))
})
$('.delete_user').click(function(){
		_conf("Are you sure to delete this User?","delete_user",[$(this).attr('data-id')])
	})
function delete_user($id){
	start_load()
	$.ajax({
		url:'ajax.php?action=delete_user',
		method:'POST',
		data:{id:$id},
		success:function(resp){
			if(resp==1){
				$('.modal').modal('hide')
				alert_toast("User successfully deleted",'success')
				setTimeout(function(){
					location.reload()
				},1500)
			}
			else {
				$('.modal').modal('hide')
				alert_toast("Unable to delete this User",'danger')
				setTimeout(function(){
					location.reload()
				},1500)
			}
		}
	})
}
</script>