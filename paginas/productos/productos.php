<?php
    session_start();

    if(isset($_GET["cerrar_sesion"])){
        if(isset($_COOKIE['mantener'])){
            setcookie("mantener",null,time()-60,"/");
        }
        $_SESSION=array();
        session_destroy();
        header('Location: ../../index.php');
    }else if(isset($_COOKIE["mantener"])){
        $_SESSION["dni"]=$_COOKIE["mantener"]; 
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <script type="text/javascript" src="../../js/app.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="icon" href="../../assets/imagenes/logo_negro.png" type="image/png"/>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>

    <!-- ENLAZO PARA PODER USAR FUNCIONES Y ME CONECTO A LA BASE DE DATOS  -->
    <?php
        require_once("../../php/funciones.php");
        $conexion=conectarServidor();
    ?>

    <!-- INSERTO EL HEADER -->
    <?php
        crearHeader('../..');
    ?>

    <main>

    <section id="productos">

    <!-- BORRO EL PRODUCTO -->
    <?php
        if(isset($_POST["id"])){
            $id=$_POST["id"];
            $consulta_borrar="DELETE FROM producto WHERE id=$id";
            $resultado=$conexion->query($consulta_borrar);
            echo "<meta http-equiv='refresh' content='0; url=productos.php'>";
        }
    ?>

    <form class="busqueda" action='#' method='GET'>
        <input type='text' name='busqueda' id='busqueda' maxlength='50'>
        <input type='submit' value='Buscar'>
        <p>*Nombre del producto o precio</p>
    </form>

    <?php
        if(isset($_SESSION["dni"]) && $_SESSION["dni"]==="000000000"){
            echo "<a id='agregar' href='insertar_producto.php'>Insertar nuevo producto</a>";
        }
    ?>

    <?php  
    
        //VEO SI SE HA REALIZADO O NO LA BUSQUEDA PARA HACER LA CONSULTA DE LA BUSQUEDA O DE TODOS LOS PRODUCTOS
        if(!isset($_GET['busqueda'])){
            $consulta_producto="SELECT id,nombre,precio FROM producto";
            $resultado=$conexion->query($consulta_producto);
        }else{
            $busqueda=trim($_GET['busqueda']);
            $busqueda="%$busqueda%";
            $consulta_producto = "SELECT id,nombre,precio FROM producto WHERE (nombre LIKE ? OR precio LIKE ?)";
            $resultado=$conexion->prepare($consulta_producto);
            $resultado->bind_param("ss", $busqueda, $busqueda);
            $resultado->bind_result($id,$nombre,$precio);
            $resultado->execute();
            $resultado->store_result();         
        }

        $filas_devueltas=$resultado->num_rows;  

        // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
        if($filas_devueltas===0){

            //SI HE REALIZADO LA BUSQUEDA ME MOSTRARÁ UN MENSAJE DISTINTO A SI ME METO DE PRIMERAS EN LA PÁGINA
            if(isset($busqueda)){
                echo "<h2>No hay productos que coincidan con la búsqueda</h2>";
            }else{
                echo "<h2>No hay productos que mostrar</h2>";
            }
        }else{
            echo "<table>";
            echo "<tr><th>Nombre</th><th>Precio</th></tr>";

            //SI HE BUSCADO MOSTRARÁ LOS RESULTADOS QUE COINCIDAN CON LA BUSQUEDA. SINO MOSTRARÁ TODOS
            if(isset($busqueda)){
                while ($resultado->fetch()){                
                    echo "<tr>
                            <td>$nombre</td>
                            <td>$precio".'€'."</td>";

                            if(isset($_SESSION["dni"]) && $_SESSION["dni"]==="000000000"){
                               echo "<td>
                               <form action='modificar_producto.php' method='POST'>
                                   <input type='hidden' name='id' value='$id'>
                                   <input class='modificar' type='submit' name='modificar' value='Modificar'>
                               </form>
                           </td>
                           <td>
                               <form action='#' method='POST'>
                                   <input type='hidden' name='id' value='$id'>
                                   <input class='borrar' type='submit' name='borrar' value='Borrar'>
                               </form>
                           </td>"; 
                            }
                            
                        echo "</tr>";    
                }
                $resultado->close();
            }else{
                while($fila_producto=$resultado->fetch_array(MYSQLI_ASSOC)){
                    echo "<tr>
                            <td>$fila_producto[nombre]</td>
                            <td>$fila_producto[precio]€</td>";

                            if(isset($_SESSION["dni"]) && $_SESSION["dni"]==="000000000"){
                                echo "<td>
                                <form action='modificar_producto.php' method='POST'>
                                    <input type='hidden' name='id' value='$fila_producto[id]'>
                                    <input class='modificar' type='submit' name='modificar' value='Modificar'>
                                </form>
                            </td>
                            <td>
                                <form action='#' method='POST'>
                                    <input type='hidden' name='id' value='$fila_producto[id]'>
                                    <input class='borrar' type='submit' value='Borrar'>
                                </form>
                            </td>";
                            }
                            
                        echo "</tr>";   
                } 
            }

            echo "</table>";
        }
    ?>

    </section>

    </main>

    <!-- INSERTO EL FOOTER -->
    <?php
        crearFooter('../..');
    ?>

    <!-- ME DESCONECTO DE LA BASE DE DATOS -->
    <?php
        $conexion->close();
    ?>
</body>
</html>