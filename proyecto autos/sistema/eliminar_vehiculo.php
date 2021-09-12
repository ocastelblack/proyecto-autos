<?php 
    session_start();
	if(empty($_SESSION['cargo']==1 || $_SESSION['cargo']==3)){
        header('location: ./');
    }
    include "../conexion.php";
    
    if(!empty($_POST)){
        $idvehiculo = $_POST['id'];

        $query_delete = mysqli_query($conexion,"DELETE FROM vehiculo WHERE Id_Vehiculo=$idvehiculo");

        if($query_delete){
            header("location: lista_vehiculo.php");
        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id']) ){
        header("location: lista_vehiculo.php");
    }else{

        $idvehiculo=$_REQUEST['id'];

        $query= mysqli_query($conexion, "SELECT * FROM vehiculo WHERE Id_Vehiculo = $idvehiculo");

        $resultado=mysqli_num_rows($query);

        if($resultado >0){
            while($data= mysqli_fetch_array($query)){
                $idvehiculo = $data['Id_Vehiculo'];
                $nombre = $data['Nombre_Vehiculo'];
                $descripcion = $data['Descripcion'];

            }
        }else{
            header("location: lista_vehiculo.php");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Eliminar Vehiculo</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
		<div class="dato_eliminar">
            <i class="fas fa-user-times fa-7x" style="color:red"></i>
            </br>
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Nombre Vehiculo: <span><?php echo $nombre; ?></span></p>
            <p>Descripcion: <span><?php echo $descripcion; ?></span></p>
            
            </br>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $idvehiculo; ?>">
                <a href="lista_proveedor.php" class="btn_cancelar"><i class="fas fa-ban"></i>Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fas fa-trash"></i> Eliminar</button>
            </form>

        </div>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>