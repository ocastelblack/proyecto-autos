<?php
    session_start();
    if(empty($_SESSION['cargo']==1 || $_SESSION['cargo']==3)){
        header('location: ./');
    }
	
    include "../conexion.php";

    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['vehiculo']) || empty($_POST['descripcion']) || empty($_POST['precio']) || ($_POST['precio'] <= 0) ||  empty($_POST['id']) 
        ||  empty($_POST['foto_actual']) ||  empty($_POST['foto_remove'])){
            $alert='<p class="msg_error">Todos los campos son obligatorios y los numeros no pueden ser negativos</p>';
        }else{
            $vehiculo_id   = $_POST['id'];
            $vehiculo      = $_POST['vehiculo'];
            $descripcion   = $_POST['descripcion'];
            $proveedor     = $_POST['proveedor'];
            $precio        = $_POST['precio'];
            $imgVehiculo   = $_POST['foto_actual'];
            $imgRemove     = $_POST['foto_remove'];
            $usuario_id    = $_SESSION['id_usuario'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];

            $upd = '';

            if($nombre_foto != ''){
                $destino = 'img/uploads/';
                $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
                $imgVehiculo = $img_nombre.'.jpg';
                $src = $destino.$imgVehiculo;
            }else{
                if($_POST['foto_actual'] != $_POST['foto_remove']){
                    $imgVehiculo = 'img_vehiculo.png';
                }
            }

            $query_update=mysqli_query($conexion,"UPDATE vehiculo SET Nombre_Vehiculo = '$vehiculo', Descripcion = '$descripcion', Id_Proveedor='$proveedor', Precio='$precio', Foto='$imgVehiculo'
            WHERE Id_Vehiculo = $vehiculo_id ");

                    if($query_update){
                        if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_vehiculo.png' )) || ($_POST['foto_actual'] != $_POST['foto_remove'])){
                            unlink('img/uploads/'.$_POST['foto_actual']);
                        }
                        if($nombre_foto != ''){
                            move_uploaded_file($url_temp,$src);
                        }
                        $alert='<p class="msg_guardar">Vehiculo actualizado correctamente</p>';
                    }else{
                        $alert='<p class="msg_error">Error al actualizar Vehiculo</p>';
                        }
        }
    }

    //VALIDAR PRODUCTO
    if(empty($_REQUEST['id'])){
        header("location: lista_vehiculo.php");
    }else{
        $id_vehiculo=$_REQUEST['id'];
        if(!is_numeric($id_vehiculo)){
            header("location: lista_vehiculo.php");
        }

        $query_vehiculo = mysqli_query($conexion, "SELECT v.Id_Vehiculo, v.Nombre_Vehiculo, v.Descripcion, p.Id_Proveedor, p.Nombre_Proveedor, v.Precio, v.Foto FROM vehiculo v INNER JOIN proveedor p
        ON v.Id_Proveedor = p.Id_Proveedor WHERE v.Id_Vehiculo = $id_vehiculo");
        $result_vehiculo = mysqli_num_rows($query_vehiculo);

        $foto = '';
        $classRemove = 'notBlock';

        if($result_vehiculo >0){
            $data_vehiculo = mysqli_fetch_assoc($query_vehiculo);
            
            if($data_vehiculo['Foto'] !='img_vehiculo.png'){
                $classRemove = '';
                $foto = '<img id="img" src="img/uploads/'.$data_vehiculo['Foto'].'" alt="Vehiculo">';
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Registro Vehiculo</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">

        <div class="form_registro">
        <h1><i class="fas fa-car"></i> Registrar Autos</h1>
        <hr>
            <div class="alerta"><?php echo isset($alert) ? $alert: '';?></div>
            <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php  echo $data_vehiculo['Id_Vehiculo']?>">
            <input type="hidden" id="foto_actual" name="foto_actual" value="<?php  echo $data_vehiculo['Foto']?>">
            <input type="hidden" id="foto_remove" name="foto_remove" value="<?php  echo $data_vehiculo['Foto']?>">
            <label for="vehiculo">Vehiculo</label>
            <input type="text" name="vehiculo" id="vehiculo" placeholder="Nombre Vehiculo" value="<?php  echo $data_vehiculo['Nombre_Vehiculo']?>">
            <label for="descripcion">Descripcion</label>
            <textarea type="text" required name="descripcion" placeholder="Ingrese Descripcion" ><?php  echo $data_vehiculo['Descripcion']?></textarea>
    
            <label for="proveedor">Nombre Proveedor</label>
            <?php 
                $query_proveedor=mysqli_query($conexion,"SELECT Id_Proveedor, Nombre_Proveedor FROM proveedor");
                $resultado_proveedor = mysqli_num_rows($query_proveedor);
            ?>

            <select name="proveedor" id="proveedor" class="notItemOne">
                <option value="<?php  echo $data_vehiculo['Id_Proveedor']?>" selected><?php  echo $data_vehiculo['Nombre_Proveedor']?></option>
                <?php 
                    if($resultado_proveedor>0){
                        while($proveedor = mysqli_fetch_array($query_proveedor)){
                            ?>
                                <option value="<?php echo $proveedor['Id_Proveedor'];?>"><?php echo $proveedor['Nombre_Proveedor'];?></option>
                            <?php
                        }
                    }
                ?>
            </select>

            <label for="precio">Precio</label>
            <input type="text" name="precio" id="precio" placeholder="Precio comercial Vehiculo" value="<?php  echo $data_vehiculo['Precio']?>">
            <div class="photo">
                <label for="foto">Haga clic en la Imagen subir una Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto <?php echo $classRemove; ?>">X</span>
                        <label for="foto"></label>
                        <?php echo $foto; ?>
                        </div>
                        <div class="upimg">
                        <input type="file" name="foto" id="foto">
                        </div>
                    <div id="form_alert"></div>
            </div>
            <button type="submit" class="btn_Guardar"><i class="far fa-save"></i>Actualizar Vehiculo</button>
         </form>
        </div>      
	</section>
	<?php include "include/footer.php"?>
</body>
</html>