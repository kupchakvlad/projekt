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

		$upload_directory = "../photo/";
		$user_id = $_SESSION["user_id"];


		$product_name = trim($_POST["product_name"]);
		$product_fabric = trim($_POST["product_fabric"]);
		$product_season = trim($_POST["season"]);
		$product_size = trim($_POST["product_size"]);
		$product_price = trim($_POST["product_price"]);


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
			die("Insert statement preparation failed: " . mysqli_stmt_error($connection));
		}

		for ($i = 0; $i < count($_FILES["photo"]["name"]); $i++) {

	    	$file_name = time() . "_" . basename($_FILES["photo"]["name"][$i]);
	    	$file_tmp = $_FILES["photo"]["tmp_name"][$i];
			$file_path = $upload_directory . $file_name;
	    	$file_type = $_FILES["photo"]["type"][$i];
			$file_size = $_FILES["photo"]["size"][$i];

			if (!move_uploaded_file($file_tmp, $file_path)) {
				die("Failed to upload file: $file_name");
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