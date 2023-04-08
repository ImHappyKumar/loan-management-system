<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="icon" href="new_UI/img/logo.png">
  <title>Login | Loan Management System</title>
 	

<?php include('./header.php'); ?>
<?php include('./db_connect.php'); ?>
<link rel="stylesheet" href="new_UI/css/login.css">
<?php 
session_start();
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>

<body>


  <main id="main">
  	<h1>Loan Management System</h1>

	<div id="login-body">
		<div class="card">
			<div class="card-body">
				<div class="logo">
					<!-- <span class="fa fa-money-check-alt"></span> -->
					<img src="new_UI/img/logo.png" alt="logo">
				</div>
				<form id="login-form" autocomplete="off">
					<div class="form-group">
						<label for="username" class="control-label">Username</label>
						<input type="text" id="username" name="username" class="form-control">
					</div>
					<div class="form-group">
						<label for="password" class="control-label">Password</label>
						<input type="password" id="password" name="password" class="form-control">
					</div>
					<center><button class="btn-sm btn-block col-md-4 btn-wave btn-primary">Login</button></center>
				</form>
			</div>
		</div>
	</div>

	<footer>All rights reserved &copy; 2023 | Designed & Developed By Happy Kumar</footer>
  </main>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else if(resp == 2){
					location.href ='voting.php';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger" style="padding: 3px; margin: 5px; text-align: center; font-size: 14px;">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>