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

	// Obtener datos.
	$data = json_decode(file_get_contents("php://input"));

	// Filtrar datos vacíos.
	if ( !empty($data->nombre) && !empty($data->precio) ) {
		// Setear valores de las propiedad del producto.
		$product->nombre = $data->nombre;
		$product->precio = $data->precio;

		// Crear producto.
		if($product->create()) {
			// Código de Respuesta - 201 creado.
			http_response_code(201);

			// Mensaje de respuesta.
			echo json_encode(array("message" => "El Producto ah sido creado de manera exitosa."));

		} else {
			// Código de Respuesta - 503 Servicio no disponible.
			http_response_code(503);

			// Mensaje de respuesta.
			echo json_encode(array("message" => "No se pudo crear el producto."));
		}

	} else {
		// Si los datos estan incopletos.
		// Código de Respuesta- 400 Solicitud Incorrecta.
		http_response_code(400);

		// Mensaje de respuesta.
		echo json_encode(array("message" => "No se puede crear el producto. Los datos están incompletos."));
	}
?>