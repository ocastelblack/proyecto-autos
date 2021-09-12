<?php 
    session_start();
	if(empty($_SESSION['cargo']==1 || $_SESSION['cargo']==3)){
        header('location: ./');
    }
    include "../conexion.php";
    
    if(!empty($_POST)){
        $idproveedor = $_POST['id'];

        $query_delete = mysqli_query($conexion,"DELETE FROM proveedor WHERE Id_Proveedor=$idproveedor");

        if($query_delete){
            header("location: lista_proveedor.php");
        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id']) ){
        header("location: lista_proveedor.php");
    }else{

        $idproveedor=$_REQUEST['id'];

        $query= mysqli_query($conexion, "SELECT * FROM proveedor WHERE Id_Proveedor = $idproveedor");

        $resultado=mysqli_num_rows($query);

        if($resultado >0){
            while($data= mysqli_fetch_array($query)){
                $idproveedor = $data['Id_Proveedor'];
                $nombre = $data['Nombre_Proveedor'];
                $asesor = $data['Nombre_Asesor'];
                $telefono = $data['Telefono_Proveedor'];
                $direccion = $data['Direccion_Proveedor'];
            }
        }else{
            header("location: lista_proveedor.php");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Eliminar Proveedor</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
		<div class="dato_eliminar">
            <i class="fas fa-user-times fa-7x" style="color:red"></i>
            </br>
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Nombre Proveedor: <span><?php echo $nombre; ?></span></p>
            <p>Nombre Asesor: <span><?php echo $asesor; ?></span></p>
            <p>Telefono Proveedor: <span><?php echo $telefono; ?></span></p>
            <p>Direccion Proveedor: <span><?php echo $direccion; ?></span></p>
            
            </br>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $idproveedor; ?>">
                <a href="lista_proveedor.php" class="btn_cancelar"><i class="fas fa-ban"></i>Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fas fa-trash"></i> Eliminar</button>
            </form>

        </div>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>