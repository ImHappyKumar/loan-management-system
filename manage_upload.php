<?php include 'db_connect.php' ?>

<?php
// Check if the form has been submitted
if(isset($_POST['submit'])){
    // Get the form id
    $form_id = $_POST["id"];

    // Check if the file has been uploaded
    if(isset($_FILES['fileToUpload'])){
        $errors = array();

        // Define the allowed file types
        $allowed_file_types = array('jpg','jpeg');

        $file_name = $_FILES['fileToUpload']['name'];

        // Get the temporary location of the uploaded file
        $file_tmp = $_FILES['fileToUpload']['tmp_name'];

        // Get the file extension
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        // Check if the file type is allowed
        if(in_array($file_ext, $allowed_file_types) === false){
            $errors[] = "File type not allowed. Please upload 'jpg' or 'jpeg' format files only";
        }

        // Check if the file size is allowed
        if($_FILES['fileToUpload']['size'] > 200000){
            $errors[] = "File size must be less than 200kb.";

            // Compress the photo if necessary
            // $image = $_FILES['fileToUpload']['tmp_name'];
            // $quality = 60;
            // list($width, $height) = getimagesize($image);
            // $new_width = $width;
            // $new_height = $height;
            // if($_FILES['fileToUpload']['size'] > 200000){
            //     $percent = sqrt(200000/($_FILES['fileToUpload']['size']/($width*$height)))*100;
            //     $new_width = $width * $percent / 100;
            //     $new_height = $height * $percent / 100;
            //     $image_p = imagecreatetruecolor($new_width, $new_height);
            //     $image = imagecreatefromjpeg($image);
            //     imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            //     ob_start();
            //     imagejpeg($image_p, NULL, $quality);
            //     $image_data = ob_get_contents();
            //     ob_end_clean();
            // } else {
            //     $image_data = file_get_contents($image);
            // }
        }

        // Check if there are any errors
        if(empty($errors)){
            // Rename the photo to form id
            $new_name = $form_id . '.' . $file_ext;

            // Save the photo to your server
            move_uploaded_file($file_tmp, 'uploads/' . $new_name);

            // Save the photo name to your SQL database
            $sql = "UPDATE borrowers SET photo = '$new_name' WHERE id =".$_POST['id'];
            mysqli_query($conn, $sql);

            // Display success message
            echo "Photo uploaded successfully.";
            header('location:index.php?page=borrowers');
        } 
        else {
            // Display error messages
            foreach($errors as $error){
                echo $error."<br>";
            }
        }
    }
}
?>