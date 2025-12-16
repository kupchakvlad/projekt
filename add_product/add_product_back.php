<?php

session_start();

$host = "localhost";
$username = "kupchvla";
$password = "webove aplikace";
$database = "kupchvla";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: \n". mysqli_connect_error());
}

$photos = $_FILES["photo"];
if (!is_array($photos['tmp_name'])) {
    $photos['tmp_name'] = [$photos['tmp_name']];
    $photos['name']     = [$photos['name']];
    $photos['type']     = [$photos['type']];
    $photos['size']     = [$photos['size']];
    $photos['error']    = [$photos['error']];
}

if (isset($_POST["submit"])) {

	foreach ($photos["tmp_name"] as $index => $tmpName) {


		$upload_directory = "/home/kupchvla/www/projekt/photo/";

		$user_id = $_SESSION["user_id"];
    	$file_name = basename($photos['name'][$index]);
		$file_path = $upload_directory . $file_name;
    	$file_type = $photos['type'][$index];
		$file_size = $photos['size'][$index];

		$product_name = trim($_POST["product_name"]);
		$product_fabric = trim($_POST["product_fabric"]);
		$product_season = trim($_POST["season"]);
		$product_size = (int) trim($_POST["product_size"]);
		$product_price = (int) trim($_POST["product_price"]);

		if (!move_uploaded_file($tmpName, $file_path)) {
        	die("File upload failed for $file_name");
        	continue;
    	}

		$insert_product_query = "INSERT INTO products (
			user_id,
			file_path,
			file_type,
			file_size,
			name,
			fabric,
			season,
			size,
			price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare($connection, $insert_product_query);

		if (!$stmt) {
			die("FATAL: Insert statement preparation failed: " . mysqli_stmt_error($connection));
		}

		mysqli_stmt_bind_param($stmt,
		"ississsii",
		$user_id,
		$file_path,
		$file_type,
		$file_size,
		$product_name,
		$product_fabric,
		$product_season,
		$product_size,
		$product_price);

		if (!mysqli_stmt_execute($stmt)) {
			die("Execution failed" . mysqli_stmt_error($stmt));
		}	
	}


	header("Location: ../main/main.php");
	exit;

}

?>