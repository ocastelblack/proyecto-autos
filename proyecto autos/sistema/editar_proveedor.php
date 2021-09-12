<?php
    session_start();
    if(empty($_SESSION['cargo']==1 || $_SESSION['cargo']==3)){
        header('location: ./');
    }
	
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre_proveedor']) || empty($_POST['nombre_asesor']) || empty($_POST['telefono']) || empty($_POST['direccion'])){
            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            $idproveedor = $_POST['id'];
            $proveedor = $_POST['nombre_proveedor'];
            $asesor = $_POST['nombre_asesor'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            
                $sql_update=mysqli_query($conexion,"UPDATE proveedor SET Nombre_Proveedor='$proveedor', Nombre_Asesor='$asesor',Telefono_Proveedor='$telefono', Direccion_Proveedor='$direccion' 
                WHERE Id_Proveedor=$idproveedor");

                if($sql_update){
                    $alert='<p class="msg_guardar">Proveedor Actualizado</p>';
                }else{
                    $alert='<p class="msg_error">Error al Actualizar Proveedor</p>';
                }
            }
        }
    
    //MOSTRAR DATOS
    if(empty($_GET['id'])){
        header('Location: lista_proveedor.php');
    }
    
    $idproveedor = $_GET['id'];

    $sql=mysqli_query($conexion, "SELECT * FROM proveedor WHERE Id_Proveedor=$idproveedor");

    $resultado_sql = mysqli_num_rows($sql);

    if($resultado_sql==0){
        header('Location: lista_proveedor.php');
    }else{
        $option='';
        while($data=mysqli_fetch_array($sql)){

            $idproveedor = $data['Id_Proveedor'];
            $proveedor = $data['Nombre_Proveedor'];
            $asesor = $data['Nombre_Asesor'];
            $telefono = $data['Telefono_Proveedor'];
            $direccion = $data['Direccion_Proveedor'];
            
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Actualizar Proveedor</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">

        <div class="form_registro">
        <h1><i class="far fa-edit"></i> Actualizar Proveedor</h1>
        <hr>
            <div class="alerta"><?php echo isset($alert) ? $alert: '';?></div>
            <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $idproveedor?>">
            <label for="nombre_proveedor">Nombre Proveedor</label>
            <input type="text" name="nombre_proveedor" id="nombre_proveedor" placeholder="Nombre Proveedor" value="<?php echo $proveedor?>">
            <label for="nombre_asesor">Nombre Asesor</label>
            <input type="text" name="nombre_asesor" id="nombre_asesor" placeholder="Nombre Asesor" value="<?php echo $asesor?>">
            <label for="telefono">Telefono </label>
            <input type="text" name="telefono" id="telefono" placeholder="Telefono Proveedor" value="<?php echo $telefono?>">
            <label for="direccion">Direccion</label>
            <input type="text" name="direccion" id="direccion" placeholder="Direccion Proveedor" value="<?php echo $direccion?>">

            <button type="submit" class="btn_Guardar"><i class="far fa-edit"></i> Actualizar Proveedor</button>
         </form>
        </div>      
	</section>
	<?php include "include/footer.php"?>
</body>
</html>