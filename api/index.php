<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/notes', 'getNotes');
$app->get('/notes/:id',	'getNote');
$app->post('/notes', 'addNote');
$app->put('/notes/:id', 'updateNote');
$app->delete('/notes/:id',	'deleteNote');

$app->run();

function getNotes() {
	$sql = "select * FROM note ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$notes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		// echo '{"note": ' . json_encode($notes) . '}';
		echo json_encode($notes);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getNote($id) {
	$sql = "SELECT * FROM note WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$note = $stmt->fetchObject();
		$db = null;
		echo json_encode($note); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addNote() {
	$request = Slim::getInstance()->request();
	$note = json_decode($request->getBody());
	$sql = "INSERT INTO note (name, description, date) VALUES (:name, :description, :date)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $note->name);
		$stmt->bindParam("description", $note->description);
		$stmt->bindParam("date", $note->date);
		$stmt->execute();
		$note->id = $db->lastInsertId();
		$db = null;
		echo json_encode($note); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateNote($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$note = json_decode($body);
	$sql = "UPDATE note SET name=:name, description=:description, date=:date WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $note->name);
		$stmt->bindParam("description", $note->description);
		$stmt->bindParam("date", $note->date);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($note);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteNote($id) {
	$sql = "DELETE FROM note WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="app";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>