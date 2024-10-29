<?php
include 'bd/BD.php';

header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $query="select * from ordenes where id=".$_GET['id'];
        $resultado=metodoGet($query);
        echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    }else{
        $query="select * from ordenes";
        $resultado=metodoGet($query);
        echo json_encode($resultado->fetchAll()); 
    }
    header("HTTP/1.1 200 OK");
    exit();
}

if(isset($_POST['METHOD'])) {
    if($_POST['METHOD']=='POST'){
        unset($_POST['METHOD']);
        $cliente = $_POST['cliente'];
        $tecnico = $_POST['tecnico'];
        $modelo = $_POST['modelo'];
        $estado = $_POST['estado'];
        $prioridad = $_POST['prioridad'];
        $fechaCompromiso = $_POST['fechaCompromiso'];   
        $total = $_POST['total']; 
        
        $query = "INSERT INTO ordenes (fechaCreacion, cliente, tecnico, modelo, estado, prioridad, fechaCompromiso, total ) VALUES (CURDATE(), '$cliente', '$tecnico', '$modelo', '$estado', '$prioridad', '$fechaCompromiso', '$total')";
        $queryAutoIncrement="SELECT MAX(id) as id from ordenes";
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
        $cliente = $_POST['cliente'];
        $tecnico = $_POST['tecnico'];
        $modelo = $_POST['modelo'];
        $estado = $_POST['estado'];
        $prioridad = $_POST['prioridad'];
        $fechaCompromiso = $_POST['fechaCompromiso'];   
        $total = $_POST['total']; 
        
        $query = "UPDATE ordenes SET cliente='$cliente', tecnico='$tecnico', modelo='$modelo', estado='$estado', prioridad='$prioridad', fechaCompromiso='$fechaCompromiso', total='$total' WHERE id='$id'";
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
        $query = "DELETE FROM ordenes WHERE id='$id'";
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
