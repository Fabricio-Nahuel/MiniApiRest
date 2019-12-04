<?php
	class Product {
		// Propiedades para la conexión a la base de datos y la tabla.
		private $conn;
		private $table_name = "productos";

		// Propiedades del producto.
		public $id;
		public $nombre;
		public $precio;

		// Constructor de la conexción de la base de datos.
		public function __construct($db) {
			$this->conn = $db;
		}

		// Este método obtiene todos los productos cargados.
		public function getAll() {
			// Query que obtiene todos los productos cargados.
			$query = "SELECT
						p.id,
						p.nombre,
						p.precio
					 FROM " . $this->table_name . " p
					 ORDER BY
						p.id ASC";

			// Preparo la declaración de la consulta.
			$stmt = $this->conn->prepare($query);

			// Ejecuto la query.
			$stmt->execute();

			return $stmt;
		}

		// Método que obtiene un producto.
		public function getOne() {
			// La query obtiene un solo registro.
			$query = "SELECT
						p.id,
						p.nombre,
						p.precio
					 FROM " . $this->table_name . " p
					 WHERE
						p.id = ?
					 LIMIT 0,1";

			// Preparo la declaración de la consulta.
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(1, $this->id);

			// Ejecuto la query.
			$stmt->execute();

			// Obtener fila.
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			// Setea valores en la propiedad del objeto.
			$this->nombre = $row['nombre'];
			$this->precio = $row['precio'];
		}

		// Método para crear un producto.
		public function create() {
			// Query que inserta el registro.
			$query = "INSERT INTO " . $this->table_name . "SET nombre=:nombre, precio=:precio";

			// Preparo la declaración de la consulta.
			$stmt = $this->conn->prepare($query);

			$this->nombre=htmlspecialchars(strip_tags($this->nombre));
			$this->precio=htmlspecialchars(strip_tags($this->precio));

			$stmt->bindParam(":nombre", $this->nombre);
			$stmt->bindParam(":precio", $this->precio);

			// Ejecuto la query.
			if($stmt->execute()) {
				return true;
			}

			return false;
		}

		// Método para actualizar un producto.
		/*
			Funcionalidades para agregar:
			Si se busca un ID que no existe en la base de datos, la consulta se ejecuta correctamente sin ningún error de sintaxis. Sin embargo, no se actualiza nada en la base de datos. 
			Para evitar esto, se necesita una validación adicional donde verifique si existe un ID en la base de datos.
		*/
		public function update() {
			// Query para actualizar un producto.
			$query = "UPDATE
						" . $this->table_name . "
					 SET
						nombre = :nombre,
						precio = :precio,
					 WHERE
						id = :id";

			// Preparo la declaración de la consulta.
			$stmt = $this->conn->prepare($query);

			$this->id=htmlspecialchars(strip_tags($this->id));
			$this->nombre=htmlspecialchars(strip_tags($this->nombre));
			$this->precio=htmlspecialchars(strip_tags($this->precio));

			$stmt->bindParam(':id', $this->id);
			$stmt->bindParam(':nombre', $this->nombre);
			$stmt->bindParam(':precio', $this->precio);

			// Ejecuto la query.
			if($stmt->execute()) {
				return true;
			}

			return false;
		}

		// Método para eliminar un producto.
		public function delete() {
			// Query para eliminar un producto.
			$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

			// Preparo la declaración de la consulta.
			$stmt = $this->conn->prepare($query);

			$this->id=htmlspecialchars(strip_tags($this->id));
			$stmt->bindParam(1, $this->id);

			// Ejecuto la query.
			if($stmt->execute()) {
				return true;
			}

			return false;
		}

		public function getCurrency($money) {
			$data = file_get_contents('../model/currency.json');
			$currencys = json_decode($data, true);
			$currency = $currencys[$money];
			return $currency;
		}
	}
?>