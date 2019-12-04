<?php
	// Headers requeridos.
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	include_once '../model/database.php';
	include_once '../controller/products.php';

	$database = new Database();
	$db = $database->getConnection();
	$product = new Product($db);

	// Obtener el id del producto a updatear.
	$data = json_decode(file_get_contents("php://input"));

	// Setear id del producto.
	$product->id = $data->id;

	// Setear propiedades del producto.
	$product->nombre = $data->nombre;
	$product->precio = $data->precio;

	// Updateo el producto.
	if($product->update()) {
		// Código de Respuesta - 200 OK
		http_response_code(200);

		//  Mensaje de respuesta.
		echo json_encode(array("message" => "El producto ah sido modificado de manera exitosa."));

	} else {
		// Código de Respuesta - 503 Servicio no disponible.
		http_response_code(503);

		// Mensaje de respuesta.
		echo json_encode(array("message" => "No se puede actualizar el producto."));
	}
?>