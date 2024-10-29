<?php

header("Access-Control-Allow-Origin: *");

// Permitir los métodos HTTP que desees
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Permitir los encabezados personalizados y otros encabezados requeridos
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Permitir cookies en solicitudes cruzadas
header("Access-Control-Allow-Credentials: true");

include 'bd/BD.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $query="select * from usuarios where id=".$_GET['id'];
        $resultado=metodoGet($query);
        echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    }else{
        $query="select * from usuarios";
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
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];
        
        $query = "INSERT INTO usuarios (nombre, correo, usuario, contraseña ) VALUES ('$nombre', '$correo', '$usuario', '$contraseña')";
        $queryAutoIncrement="SELECT MAX(id) as id from usuarios";
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
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];
        
        $query = "UPDATE usuarios SET nombre='$nombre', correo='$correo', usuario='$usuario', contraseña='$contraseña' WHERE id='$id'";
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
        $query = "DELETE FROM usuarios WHERE id='$id'";
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
