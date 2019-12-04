<?php
	// Headers requeridos.
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Allow-Credentials: true");
	header("Content-Type: application/json; charset=UTF-8");

	include_once '../model/database.php';
	include_once '../controller/products.php';

	$database = new Database();
	$db = $database->getConnection();
	$product = new Product($db);

	//Seteo el ID del registro.
	$product->id = isset($_GET['id']) ? $_GET['id'] : die();

	// Obtengo el detalle del producto.
	$product->getOne();

	if($product->nombre!=null) {

		$product_arr = array (
			"id" =>  $product->id,
			"nombre" => $product->nombre,
			"precio" => $product->precio
		);

		// Código de Respuesta - 200 OK
		http_response_code(200);

		// Muestra la información del producto en formato JSON.
		echo json_encode($product_arr);

	} else {
		// Código de respuesta - 404 Not found.
		http_response_code(404);
	 
		// Mensaje de respuesta.
		echo json_encode(array("message" => "El producto no existe."));
	}
?>