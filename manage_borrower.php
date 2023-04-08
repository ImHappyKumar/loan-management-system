<?php include 'db_connect.php' ?>
<?php 

if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM borrowers where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}

?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-borrower">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">First Name</label>
						<input name="firstname" class="form-control" required="" value="<?php echo isset($firstname) ? $firstname : '' ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">Last Name</label>
						<input name="lastname" class="form-control" required="" value="<?php echo isset($lastname) ? $lastname : '' ?>">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
							<label for="">Address</label>
							<textarea name="address" id="" cols="30" rows="2" class="form-control" required=""><?php echo isset($address) ? $address : '' ?></textarea>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-5">
					<div class="">
						<label for="">Contact No</label>
						<input type="text" class="form-control" name="contact_no" value="<?php echo isset($contact_no) ? $contact_no : '' ?>">
					</div>
				</div>
				<div class="col-md-6">
							<label for="">Email</label>
							<input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : '' ?>">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-5">
					<div class="">
						<label for="">Aadhaar No</label>
						<input type="text" class="form-control" name="aadhaar" value="<?php echo isset($aadhaar) ? $aadhaar : '' ?>">
					</div>
				</div>
				<div class="col-md-5">
					<div class="">
						<label for="">PAN</label>
						<input type="text" class="form-control" name="pan" value="<?php echo isset($pan) ? $pan : '' ?>">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	 $('#manage-borrower').submit(function(e){
	 	e.preventDefault()
	 	start_load()
	 	$.ajax({
	 		url:'ajax.php?action=save_borrower',
	 		method:'POST',
	 		data:$(this).serialize(),
	 		success:function(resp){
	 			if(resp==1){
					$('.modal').modal('hide')
	 				alert_toast("Borrower successfully added",'success');
	 				setTimeout(function(e){
	 					location.reload()
	 				},1500)
	 			}
				else if(resp==2){
					$('.modal').modal('hide')
					alert_toast("Borrower successfully updated",'success')
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
					alert_toast("Unable to add this Borrower",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
	 		}
	 	})
	 })
</script>