<?php
	/*
		El siguiente código muestra encabezados sobre quién puede leer este archivo y qué tipo de contenido devolverá.
		En este caso, puede ser leído por cualquier persona.
		Devolverá datos en formato JSON.
	*/

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

	// Obtengo el nombre y unidad de la moneda deseada.
	$currency = $product->getCurrency('USD');
	$name_currency = $currency['name'];
	$united_currency = $currency['united'];

	$stmt = $product->getAll();
	$num = $stmt->rowCount();

	// Verifica si se encontraron más de 0 registros.
	if($num > 0) {
		// Array de productos.
		$products_arr = array();
		$products_arr["records"] = array();

		// Recupera los registros de la tabla Productos.
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			$product_item = array (
				"id" => $id,
				"nombre" => $nombre,
				"precio" => $precio,
				"Precio en ".$name_currency."" => (bcdiv(($precio / $united_currency), '1', 2) + 0)
			);

			array_push($products_arr["records"], $product_item);
		}

		// Código de respuesta - 200 OK.
		http_response_code(200);

		// Muestra la información de los productos en formato JSON.
		echo json_encode($products_arr);
	
	} else {
		// Código de respuesta - 404 Not found.
		http_response_code(404);
		
		// Mensaje de respuesta.
		echo json_encode (
			array("message" => "No se encuentran productos cargados.")
		);
	}
?>