<?php

    $alert='';
    session_start();

    if(!empty($_SESSION['activo'])){
        header('location: sistema/index.php');
    }else{
        if(!empty($_POST)){
          
            if(empty ($_POST['usuario']) || empty($_POST['clave'])){
                $alert ='Ingrese su usuario y su clave';
            }else{
                include("conexion.php");
    
                $user = $_POST['usuario'];
                $pass = $_POST['clave'];
    
                $query= mysqli_query($conexion, "SELECT * FROM usuario WHERE Usuario='$user' AND Contraseña='$pass'" );
                mysqli_close($conexion);
                $resultado= mysqli_num_rows($query);
    
                if($resultado){
                    $data = mysqli_fetch_array($query);
                    $_SESSION['activo'] = true;
                    $_SESSION['id_usuario'] = $data['id_usuario'];
                    $_SESSION['Nombre'] = $data['Nombre'];
                    $_SESSION['Apellido'] = $data['Apellidp'];
                    $_SESSION['Edad'] = $data['Edad'];
                    $_SESSION['Telefono'] = $data['Telefono'];
                    $_SESSION['Correo'] = $data['Correo'];
                    $_SESSION['Usuario'] = $data['Usuario'];
                    $_SESSION['cargo'] = $data['cargo'];
    
                    header('location: sistema/');
                }else{
                    $alert ='El usuario o la clave son incorrectos';
                    session_destroy();
                }
    
            }
          }
        
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Concesionario Meteoro</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <section id="container">
        <form action="" method="POST">
            <h3>Iniciar sesión</h3>
            <img src="img/icon_login.png" width="150" width="160">
            <input type="text" name="usuario" placeholder="Usuario">
            <input type="password" name="clave" placeholder="Contraseña">
            <div class="alert"><?php echo isset($alert) ? $alert:''; ?></div>
            <input type="submit" value="Ingresar">
        </form>
    </section>
</body>
</html>