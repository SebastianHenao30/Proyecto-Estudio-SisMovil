<?php
include 'bd/BD.php';

header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $query="select * from servicios where id=".$_GET['id'];
        $resultado=metodoGet($query);
        echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    }else{
        $query="select * from servicios";
        $resultado=metodoGet($query);
        echo json_encode($resultado->fetchAll()); 
    }
    header("HTTP/1.1 200 OK");
    exit();
}

if(isset($_POST['METHOD'])) {
    if($_POST['METHOD']=='POST'){
        unset($_POST['METHOD']);
        $nombre = $_POST['nombre'];
       
        $query = "INSERT INTO servicios (nombre) VALUES ('$nombre')";
        $queryAutoIncrement="SELECT MAX(id) as id from servicios";
        $resultado=metodoPost($query, $queryAutoIncrement);
        
        if ($resultado) {
            echo("inserccion exitosa<br>");
            echo json_encode($resultado);
            header("HTTP/1.1 200 OK");
        } else {
            echo json_encode(array("message" => "Error al insertar datos"));
            header("HTTP/1.1 500 Internal Server Error");
        }
        exit();
    } elseif($_POST['METHOD']=='PUT'){
        unset($_POST['METHOD']);
        $id=$_GET['id'];
        $nombre = $_POST['nombre'];
       
        $query = "UPDATE servicios SET nombre='$nombre' WHERE id='$id'";
        $resultado = metodoPut($query);
        
        if ($resultado) {
            echo("actualización exitosa<br>");
            echo json_encode($resultado);
            header("HTTP/1.1 200 OK");
        } else {
            echo json_encode(array("message" => "Error al actualizar datos"));
            header("HTTP/1.1 500 Internal Server Error");
        }
        exit();
    } elseif($_POST['METHOD']=='DELETE'){
        unset($_POST['METHOD']);
        $id = $_GET['id'];
        $query = "DELETE FROM servicios WHERE id='$id'";
        $resultado = metodoDelete($query);
        if ($resultado) {
            echo("Eliminacion exitosa<br>");
            echo json_encode($resultado);
            header("HTTP/1.1 200 OK");
        } else {
            echo json_encode(array("message" => "Error al eliminar datos"));
            header("HTTP/1.1 500 Internal Server Error");
        }
        exit();
    }
} else {
    // Manejo cuando 'METHOD' no está presente en la solicitud
    echo json_encode(array("message" => "Error: Método no especificado"));
    header("HTTP/1.1 400 Bad Request");
}

?>
