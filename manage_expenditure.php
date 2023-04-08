<?php include 'db_connect.php' ?>
<?php session_start(); ?>
<?php 
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM expenditure where id=".$_GET['id']);
	foreach($qry->fetch_array() as $k => $val){
		$$k = $val;
	}
}
?>

<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-expenditure" autocomplete="off">
			<input type="hidden" name="id" id="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
            <input type="hidden" name="user" id="user" value="<?php echo $_SESSION['login_name'] ?>">
			<div class="row form-group">
				<div class="col-md-6">
                    <label for="">Purpose</label>
                    <textarea name="purpose" id="purpose" cols="30" rows="2" class="form-control" required=""><?php echo isset($purpose) ? $purpose : '' ?></textarea>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-6">
                    <label class="control-label">Expenditure Amount</label>
                    <input type="number" name="amount" class="form-control text-right" step="any" min="0" id="amount" value="<?php echo isset($amount) ? $amount : '' ?>" require>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	 $('#manage-expenditure').submit(function(e){
	 	e.preventDefault()
	 	start_load()
	 	$.ajax({
	 		url:'ajax.php?action=save_expenditure',
	 		method:'POST',
	 		data:$(this).serialize(),
	 		success:function(resp){
	 			if(resp==1){
					$('.modal').modal('hide')
	 				alert_toast("Expenditure successfully added",'success');
	 				setTimeout(function(e){
	 					location.reload()
	 				},1500)
	 			}
				else if(resp==2){
					$('.modal').modal('hide')
					alert_toast("Expenditure successfully updated",'success')
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
					alert_toast("Unable to add this Expenditure",'danger')
					setTimeout(function(){
						location.reload()
					},1500)
				}
	 		}
	 	})
	 })
</script>