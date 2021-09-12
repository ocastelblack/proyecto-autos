<?php 
    session_start();
	if(empty($_SESSION['cargo']==1 || $_SESSION['cargo']==3)){
        header('location: ./');
    }
    include "../conexion.php";
    
    if(!empty($_POST)){
        $idvehiculo = $_POST['id'];
        $cantidad = $_POST['cantidad'];
        $actualC = $_POST['cantidad_actual'];
        $nueva = $cantidad+$actualC;

        $query_delete = mysqli_query($conexion,"UPDATE ve_entrante SET Cantidad='$nueva' WHERE Id_Vehiculo=$idvehiculo");

        if($query_delete){
            $query_upd = mysqli_query($conexion, "CALL actualizar_precio_producto($cantidad,$idvehiculo)");
            header("location: lista_vehiculo.php");
        }else{
            echo "Error actualizar cantidad";
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
                $actualC = $data['Cantidad'];

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
	<title>Agregar Cantidad Vehiculo</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
		<div class="dato_eliminar">
            <i class="fas fa-car fa-7x" style="color: blue;"></i>  
            </br>
            <h2>Agrege mas cantidad de automobiles que ingresan al negocio</h2>
            <p>Nombre Vehiculo: <span><?php echo $nombre; ?></span></p>
            <p>Descripcion: <span><?php echo $descripcion; ?></span></p>
            
            </br>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $idvehiculo; ?>">
                <input type="hidden" name="cantidad_actual" value="<?php echo $actualC; ?>">
                <input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad de Vehiculos" required></br>
                <a href="lista_vehiculo.php" class="btn_cancelar"><i class="fas fa-ban"></i>Cancelar</a>
                <button type="submit" class="btn_ok"><i class="far fa-edit"></i>Agregar</button>
            </form>

        </div>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>