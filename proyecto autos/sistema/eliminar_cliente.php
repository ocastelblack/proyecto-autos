<?php 
    session_start();
	if(empty($_SESSION['cargo']==1 || $_SESSION['cargo']==3)){
        header('location: ./');
    }
    include "../conexion.php";
    
    if(!empty($_POST)){
        $idcliente = $_POST['id_cliente'];

        $query_delete = mysqli_query($conexion,"DELETE FROM cliente WHERE id_cliente=$idcliente");

        if($query_delete){
            header("location: lista_clientes.php");
        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id']) ){
        header("location: lista_clientes.php");
    }else{

        $idcliente=$_REQUEST['id'];

        $query= mysqli_query($conexion, "SELECT * FROM cliente WHERE id_cliente = $idcliente");

        $resultado=mysqli_num_rows($query);

        if($resultado >0){
            while($data= mysqli_fetch_array($query)){
                $idcliente = $data['id_cliente'];
                $nombre = $data['Nombre'];
                $apellido = $data['Apellido'];
                $tipo = $data['Tipo'];
                $identificacion = $data['Identificacion'];
                $telefono = $data['Telefono'];
                $direccion = $data['Direccion'];
                $edad = $data['Edad'];
            }
        }else{
            header("location: lista_clientes.php");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Eliminar Cliente</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
		<div class="dato_eliminar">
            <i class="fas fa-user-times fa-7x" style="color:red"></i>
            </br>
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>Apellido: <span><?php echo $apellido; ?></span></p>
            <p>Tipo de identificacion: <span><?php echo $tipo; ?></span></p>
            <p>Numero Identificacion: <span><?php echo $identificacion; ?></span></p>
            <p>Telefono: <span><?php echo $telefono; ?></span></p>
            <p>Direccion: <span><?php echo $direccion; ?></span></p>
            <p>Edad: <span><?php echo $edad; ?></span></p>
            
            </br>
            <form method="POST" action="">
                <input type="hidden" name="id_cliente" value="<?php echo $idcliente; ?>">
                <a href="lista_clientes.php" class="btn_cancelar"><i class="fas fa-ban"></i>Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fas fa-trash"></i> Eliminar</button>
            </form>

        </div>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>