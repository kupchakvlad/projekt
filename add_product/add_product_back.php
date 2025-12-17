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


if (isset($_POST["submit"])) {

		$upload_directory = "/home/kupchvla/www/projekt/photo/";

		$user_id = $_SESSION["user_id"];
    	$file_name = basename($_FILES["photo"]["name"]);
		$file_path = $upload_directory . $file_name;
    	$file_type = $_FILES["photo"]["type"];
		$file_size = $_FILES["photo"]["size"];

		$product_name = trim($_POST["product_name"]);
		$product_fabric = trim($_POST["product_fabric"]);
		$product_season = trim($_POST["season"]);
		$product_size = trim($_POST["product_size"]);
		$product_price = trim($_POST["product_price"]);

		if (!move_uploaded_file($tmpName, $file_path)) {
        	die("File upload failed for $file_name");
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

	header("Location: ../main/main.php");
	exit;

}

?>