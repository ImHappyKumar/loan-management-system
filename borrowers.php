<?php include 'db_connect.php' ?>

<div class="container-fluid" style="margin-bottom: 58px;">

	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large class="card-title">
					<b>Borrower List</b>
				</large>
				<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" id="new_borrower"><i class="fa fa-plus" style="width: 20px;"></i> New Borrower</button>
			</div>
			<div class="card-body" style="overflow-x:auto;">
				<table class="table table-bordered" id="borrower-list">
					<colgroup>
						<col width="5%">
						<col width="20%">
						<col width="45%">
						<?php if($_SESSION['login_type'] == 1): ?>
							<col width="30%">
						<?php endif; ?>
					</colgroup>
					<thead>
						<tr>
							<th class="text-center text-white">#</th>
							<th class="text-center text-white">Photo</th>
							<th class="text-center text-white">Borrower</th>
							<?php if($_SESSION['login_type'] == 1): ?>
								<th class="text-center text-white">Action</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
							$qry = $conn->query("SELECT * FROM borrowers order by id asc");
							while($row = $qry->fetch_assoc()):

							?>
							<tr>
							
							<td class="text-center"><?php echo $i++ ?></td>
							<td class="text-center">
								<?php $imageURL = 'uploads/'.$row["photo"]; ?>
								<?php if(file_exists($imageURL) && $row["photo"] != ""): ?>
									<a href="<?php echo $imageURL; ?>"><img src="<?php echo $imageURL; ?>" alt="" /></a>
								<?php endif; ?>
								<?php if(!file_exists($imageURL) || $row["photo"]==""): ?>
									<button class="btn btn-outline-primary btn-sm add_photo" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-plus"></i> Add Photo</button>
								<?php endif; ?>
							</td>
							<td>
								<p >Name: <b><?php echo ucwords($row['firstname']." ".$row['lastname']) ?></b></p>
								<p ><small>Address: <b><?php echo $row['address'] ?></small></b></p>
								<p ><small>Contact No: <b><?php echo $row['contact_no'] ?></small></b></p>
								<p ><small>Email ID: <b><?php echo $row['email'] ?></small></b></p>
								<p ><small>Aadhaar No: <b><?php echo $row['aadhaar'] ?></small></b></p>
								<p ><small>PAN: <b><?php echo $row['pan'] ?></small></b></p>
								<p ><small>Date Created: <b><?php echo date("M d, Y",strtotime($row['date_created'])) ?></small></b></p>
								
							</td>
							<?php if($_SESSION['login_type'] == 1): ?>
								<td class="text-center">
										<?php if(file_exists($imageURL)): ?>
											<div class="btn-group">
												<button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 2.5px 5px">
												<i class="fa fa-edit"></i>
												</button>
													<div class="dropdown-menu">
													<button class="btn btn-outline-primary btn-sm add_photo" type="button" data-id="<?php echo $row['id'] ?>" style="margin-left: 10px !important;">Edit Photo</button>
													<div class="dropdown-divider"></div>
													<button class="btn btn-outline-primary btn-sm edit_borrower" type="button" data-id="<?php echo $row['id'] ?>" style="margin-left: 10px !important;">Edit Details</button>
													</div>
											</div>
										<?php endif; ?>
										<?php if(!file_exists($imageURL)): ?>
											<button class="btn btn-outline-primary btn-sm edit_borrower" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
										<?php endif; ?>
										<button class="btn btn-outline-danger btn-sm delete_borrower" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
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
	$('#borrower-list').dataTable()
	$('#new_borrower').click(function(){
		uni_modal("New Borrower","manage_borrower.php",'mid-large')
	})
	$('.add_photo').click(function(){
		uni_modal("Add Photo","upload.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.edit_borrower').click(function(){
		uni_modal("Edit Borrower","manage_borrower.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_borrower').click(function(){
		_conf("Are you sure to delete this Borrower?","delete_borrower",[$(this).attr('data-id')])
	})
function delete_borrower($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_borrower',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					$('.modal').modal('hide')
					alert_toast("Borrower successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
				else {
					$('.modal').modal('hide')
					alert_toast("Unable to delete this Borrower",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>