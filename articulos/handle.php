<?php
include ("../funciones/image-resize.php"); 

$upload_directory=dirname(dirname(__FILE__)).'/fotos/';
		
			$encoded_file = $_POST['file'];
			$decoded_file = base64_decode($encoded_file);
			$name = $_POST['name'];
			/* Now you can copy the uploaded file to your server. */
			
			$method='max';
			$image_loc=$upload_directory;
			$new_loc=$upload_directory;
			$width=200;
			$height=200;

			
			file_put_contents($upload_directory . $name, $decoded_file);
			resize_image('max',$upload_directory . $name,$upload_directory . $name,200,200);			
			/*move_uploaded_file($_FILES['my_file']['tmp_name'],	$upload_directory . $_FILES['my_file']['name']);*/
			echo 'exito'
			
			

?>