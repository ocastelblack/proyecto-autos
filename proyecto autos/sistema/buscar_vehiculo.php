<?php 
	session_start();
	include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Lista Autos Meteoro</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
        <?php 
            $busqueda = '';
            $search_proveedor = '';
            if(empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor'])){
                header("location: lista_vehiculo.php");
            }
            if(!empty($_REQUEST['busqueda'])){
                $busqueda = strtolower($_REQUEST['busqueda']);
                $where = "(v.Id_Vehiculo LIKE '%$busqueda%' OR v.Nombre_Vehiculo LIKE '%$busqueda%')";
                $buscar = 'busqueda='.$busqueda;
            }
            if(!empty($_REQUEST['proveedor'])){
                $search_proveedor = $_REQUEST['proveedor'];
                $where = "v.Id_Proveedor LIKE $search_proveedor";
                $buscar = 'proveedor='.$search_proveedor;
            }

        ?>
        <h1><i class="fas fa-car"></i>Lista de Autos</h1>
		<a href="registro_vehiculo.php" class="btn_usuario">Ingresar Vehiculo</a>

		<form action="buscar_vehiculo.php" method="GET" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda ?>">
			<button type="submit"  class="btn_buscar"><i class="fas fa-search-plus"></i></button>
		</form>
		
		<table>
			<tr>
				<th>Id Vehiculo</th>
				<th>Nombre Auto</th>
                <th>Descripcion</th>
                <th>
                <?php
                    $pro = 0;
                    if(!empty($_REQUEST['proveedor'])){
                        $pro = $_REQUEST['proveedor'];
                    } 
                    $query_proveedor=mysqli_query($conexion,"SELECT Id_Proveedor, Nombre_Proveedor FROM proveedor");
                    $resultado_proveedor = mysqli_num_rows($query_proveedor);
                ?>

                <select name="proveedor" id="search_proveedor">
                    <option value="" selected>PROVEEDOR</option>
                    <?php 
                        if($resultado_proveedor>0){
                            while($proveedor = mysqli_fetch_array($query_proveedor)){
                                if($pro == $proveedor['Id_Proveedor']){
                             ?>
                                <option value="<?php echo $proveedor['Id_Proveedor'];?>" selected><?php echo $proveedor['Nombre_Proveedor'];?></option>
                            <?php
                             }else{
                            ?>
                                <option value="<?php echo $proveedor['Id_Proveedor'];?>"><?php echo $proveedor['Nombre_Proveedor'];?></option>
                            <?php
                             }
                        }
                    }
                    ?>
                </select>
                </th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Foto</th>
				<th>Operaciones</th>
			</tr>
			<?php 

				//PAGINADOR
				$sql_registro= mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM vehiculo as v WHERE $where");
				$resultado_registro = mysqli_fetch_array($sql_registro);
                $total_registro = $resultado_registro['total_registro'];

				$por_pagina = 2;

				if(empty($_GET['pagina'])){
					$pagina=1;
				}else{
					$pagina=$_GET['pagina'];
				}

				$desde= ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);




				$query = mysqli_query($conexion,"SELECT v.Id_Vehiculo, v.Nombre_Vehiculo, v.Descripcion, p.Nombre_Proveedor, v.Precio, v.Cantidad, v.Foto 
                FROM vehiculo v INNER JOIN proveedor p ON v.Id_Proveedor = p.Id_Proveedor WHERE $where
                ORDER BY Id_Vehiculo ASC LIMIT $desde, $por_pagina");

				$resultado = mysqli_num_rows($query);

				if($resultado > 0){
					while($data = mysqli_fetch_array($query)){
                        if($data['Foto'] != 'img_vehiculo.png'){
                            $foto = 'img/uploads/'.$data['Foto'];
                        }else{
                            $foto = 'img/'.$data['Foto'];
                        }
						?>
						<tr>
							<td><?php echo $data["Id_Vehiculo"] ?></td>
							<td><?php echo $data["Nombre_Vehiculo"] ?></td>
							<td><?php echo $data["Descripcion"] ?></td>
                            <td><?php echo $data["Nombre_Proveedor"] ?></td>
                            <td><?php echo $data["Precio"] ?></td>
                            <td><?php echo $data["Cantidad"] ?></td>
                            <td class="imagen_vehiculos"><img src="<?php echo $foto; ?>" alt="<?php echo $data["Nombre_Vehiculo"] ?>"></td>

								<?php 
									if($_SESSION['cargo']==1 || $_SESSION['cargo']==3){
								?>
							<td>
								<a class="link_add add_product" product="<?php echo $data["Id_Vehiculo"] ?> "href="#"><i class="fas fa-plus"></i>Agregar</a>
								|
								<a class="link_edit" href="editar_vehiculo.php?id=<?php echo $data["Id_Vehiculo"] ?>"><i class="far fa-edit"></i>Editar</a>
								|
								<a class="link_delete" href="eliminar_vehiculo.php?id=<?php echo $data["Id_Vehiculo"] ?>"><i class="far fa-trash-alt"></i>Eliminar</a>
							</td>
							<?php }?>
						</tr>
			<?php
					}
				}
			?>
        </table>
        <?php 
            if($total_paginas !=0){
        ?>
		<div class="paginador">
				<ul>
					<?php 
						if($pagina !=1){

						
					?>
					<li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-backward"></i></a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>&<?php echo $buscar; ?>"><i class="fas fa-caret-left"></i></a></li>
					<?php
						} 
						for($i=1; $i <= $total_paginas; $i++){
							if($i == $pagina){
								echo '<li class="pageSelected">'.$i.'</li>';
							}else{
								echo '<li><a href="?pagina='.$i.'&'.$buscar.'">'.$i.'</a></li>';
							}
						}
						if($pagina != $total_paginas){
					?>


					<li><a href="?pagina=<?php echo $pagina + 1;?>&<?php echo $buscar; ?>"><i class="fas fa-caret-right"></i></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-forward"></i></a></li>
						<?php } ?>
				</ul>
        </div>
                        <?php }?>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>