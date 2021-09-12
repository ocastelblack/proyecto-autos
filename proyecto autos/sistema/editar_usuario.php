<?php
    session_start();
	if(empty($_SESSION['cargo']==1)){
        header('location: ./');
    }
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['edad']) || empty($_POST['direccion'])
        || empty($_POST['telefono']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['contraseña'])){
            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            $idusuario = $_POST['id_usuario'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $edad = $_POST['edad'];
            $direccion = $_POST['direccion'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $usuario = $_POST['usuario'];
            $contraseña = $_POST['contraseña'];
            $cargo = $_POST['cargo'];

            $query = mysqli_query($conexion,"SELECT * FROM usuario WHERE (Usuario='$usuario' AND id_usuario != $idusuario) 
            OR (Correo='$correo' AND id_usuario != $idusuario)");
            $resultado= mysqli_fetch_array($query);

            if($resultado > 0){
                $alert='<p class="msg_error">El correo o el usuario ya existe</p>';
            }else{

                $sql_update=mysqli_query($conexion,"UPDATE usuario SET Nombre='$nombre', Apellido='$apellido', Edad='$edad', Direccion='$direccion', Telefono='$telefono',
                Correo='$correo', Usuario='$usuario', Contraseña='$contraseña', cargo='$cargo' WHERE id_usuario=$idusuario");

                if($sql_update){
                    $alert='<p class="msg_guardar">Usuario Actualizado</p>';
                }else{
                    $alert='<p class="msg_error">Error al Actualizar usuario</p>';
                }
            }
        }
    }
    //MOSTRAR DATOS
    if(empty($_GET['id'])){
        header('Location: lista_usuarios.php');
    }
    
    $iduser = $_GET['id'];

    $sql=mysqli_query($conexion, "SELECT u.id_usuario, u.Nombre , u.Apellido, u.Edad, u.Direccion, u.Telefono, u.Correo, u.Usuario , u.Contraseña, (u.cargo) as id_cargo,(c.cargo) AS cargo 
    FROM usuario u INNER JOIN cargo c ON u.cargo = c.id_cargo WHERE id_usuario=$iduser");

    $resultado_sql = mysqli_num_rows($sql);

    if($resultado_sql==0){
        header('Location: lista_usuarios.php');
    }else{
        $option= '';
        while($data=mysqli_fetch_array($sql)){

            $idusuario = $data['id_usuario'];
            $nombre = $data['Nombre'];
            $apellido = $data['Apellido'];
            $edad = $data['Edad'];
            $direccion = $data['Direccion'];
            $telefono = $data['Telefono'];
            $correo = $data['Correo'];
            $usuario = $data['Usuario'];
            $contraseña = $data['Contraseña'];
            $idcargo = $data['id_cargo'];
            $cargo = $data['cargo'];

            if($idcargo == 1){
                $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
            }else if($idcargo ==2){
                $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
            }else if($idcargo ==3){
                $option = '<option value="'.$idcargo.'"select>'.$cargo.'</option>';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Actualizar Usuario</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">

        <div class="form_registro">
        <h1><i class="far fa-edit"></i> Actualizar Usuario</h1>
        <hr>
            <div class="alerta"><?php echo isset($alert) ? $alert: '';?></div>
            <form action="" method="POST">
            <label for="nombre">Nombre</label>
            <input type="hidden" name="id_usuario" value="<?php echo $idusuario; ?>">
            <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" placeholder="Apellido" value="<?php echo $apellido; ?>">
            <label for="edad">Edad</label>
            <input type="text" name="edad" id="edad" placeholder="Edad" value="<?php echo $edad; ?>">
            <label for="direccion">Direccion</label>
            <input type="text" name="direccion" id="direccion" placeholder="Direccion" value="<?php echo $direccion; ?>">
            <label for="telefono">Telefono</label>
            <input type="text" name="telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono; ?>">
            <label for="correo">Correo Electronico</label>
            <input type="text" name="correo" id="correo" placeholder="Correo Electronico" value="<?php echo $correo; ?>">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" placeholder="usuario" value="<?php echo $usuario; ?>">
            <label for="contraseña">Contraseña</label>
            <input type="text" name="contraseña" id="contraseña" placeholder="contraseña" value="<?php echo $contraseña; ?>">

                <?php
                    $query_rol = mysqli_query($conexion, "SELECT * FROM cargo");
                    $resultado_rol = mysqli_num_rows($query_rol);
                    
                ?>


            <label for="cargo" id="cargo">Tipo de Usuario</label>
            <select name="cargo" id="cargo" class="notItemOne">
                <?php 
                    echo $option;
                     if($resultado_rol > 0){
                        while($rol=mysqli_fetch_array($query_rol)){
                ?>
                        <option value="<?php echo $rol["id_cargo"]; ?>"><?php echo $rol["cargo"]?></option>
                <?php
                        }
                    }
                ?>
            </select>
            <input type="submit" value="Actualizar Usuario" class="btn_Guardar">
         </form>
        </div>      
	</section>
	<?php include "include/footer.php"?>
</body>
</html>