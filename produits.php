<?php
	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];
	// Connect to database
	include("db_connect.php");
	$request_method = $_SERVER["REQUEST_METHOD"];

	// Fonction pour vérifier les informations d'identification
	function authenticate($username, $password) {
    	// Vous devriez mettre en œuvre une logique de vérification des informations d'identification ici
    	// Pour cet exemple, nous comparons avec des valeurs statiques
    	$validUsername = 'root';
    	$validPassword = 'root';

    	return $username === $validUsername && $password === $validPassword;
	}

	function getProducts()
	{
		global $conn;
		$query = "SELECT * FROM produit";
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function getProduct($id=0)
	{
		global $conn;
		$query = "SELECT * FROM produit";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function AddProduct()
	{
		global $conn;
		$name = $_POST["name"];
		$description = $_POST["description"];
		$price = $_POST["price"];
		$category = $_POST["category"];
		$created = date('Y-m-d H:i:s');
		$modified = date('Y-m-d H:i:s');
		echo $query="INSERT INTO produit(name, description, price, category_id, created, modified) VALUES('".$name."', '".$description."', '".$price."', '".$category."', '".$created."', '".$modified."')";
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Produit ajout� avec succ�s.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'ERREUR!.'. mysqli_error($conn)
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function updateProduct($id)
	{
		global $conn;
		$_PUT = array();
		parse_str(file_get_contents('php://input'), $_PUT);
		$name = $_PUT["name"];
		$description = $_PUT["description"];
		$price = $_PUT["price"];
		$category = $_PUT["category"];
		$created = 'NULL';
		$modified = date('Y-m-d H:i:s');
		$query="UPDATE produit SET name='".$name."', description='".$description."', price='".$price."', category_id='".$category."', modified='".$modified."' WHERE id=".$id;
		
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Produit mis a jour avec succes.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Echec de la mise a jour de produit. '. mysqli_error($conn)
			);
			
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function deleteProduct($id)
	{
		global $conn;
		$query = "DELETE FROM produit WHERE id=".$id;
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Produit supprime avec succes.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'La suppression du produit a echoue. '. mysqli_error($conn)
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
	{
		header('WWW-Authenticate: Basic realm="Mon API"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Authentification requise.';
		exit;
	}
	elseif(!authenticate($username, $password)) {
		header('WWW-Authenticate: Basic realm="Mon API"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Authentification échouée.';
		echo $username;
		echo $password;
		exit;
	}
	else
	{
		switch($request_method)
		{
		
			case 'GET':
			// Retrive Products
			if(!empty($_GET["id"]))
			{
				//$id=intval($_GET["id"]);
				$id=htmlspecialchars($_GET["id"]);
				//$id=$_GET["id"];
				if ($_GET["id"]!="'")
				{
					getProduct($id);
				}
				
			}
			else
			{
				getProducts();
			}
			break;
			default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
			break;
			
			case 'POST':
			// Ajouter un produit
			AddProduct();
			break;
			
			case 'PUT':
			// Modifier un produit
			$id = intval($_GET["id"]);
			updateProduct($id);
			break;
			
			case 'DELETE':
			// Supprimer un produit
			$id = intval($_GET["id"]);
			deleteProduct($id);
			break;

		}
	}
?>