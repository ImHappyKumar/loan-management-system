<form action="manage_upload.php" method="post" enctype="multipart/form-data" id="upload-form">
  <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
  <input type="file" name="fileToUpload"><br><br>
  <input type="submit" name="submit" value="Upload" id="upload">
</form>

<style>
	#uni_modal .modal-footer #submit{
		display: none;
	}
</style>