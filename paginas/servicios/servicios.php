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
    <title>Servicios</title>
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

    <section id="servicios">

        <form class="busqueda" action='#' method='GET'>
            <input type='text' name='busqueda' id='busqueda' maxlength='50'>
            <input type='submit' value='Buscar'>
            <p>*Nombre del servicio</p>
        </form>

        <?php
            if(isset($_SESSION["dni"]) && $_SESSION["dni"]==="000000000"){
                echo "<a id='agregar' href='insertar_servicio.php'>Insertar nuevo servicio</a>";
            }
        ?>

    <?php  
    
        //VEO SI SE HA REALIZADO O NO LA BUSQUEDA PARA HACER LA CONSULTA DE LA BUSQUEDA O DE TODOS LOS SERVICIOS
        if(!isset($_GET['busqueda'])){
            $consulta_servicio="SELECT id,descripcion,duracion,precio FROM servicio ORDER BY precio";
            $resultado=$conexion->query($consulta_servicio);
        }else{
            $busqueda=trim($_GET['busqueda']);
            $busqueda="%$busqueda%";
            $consulta_servicio = "SELECT id,descripcion,duracion,precio FROM servicio WHERE (descripcion LIKE ?) ORDER BY precio";
            $resultado=$conexion->prepare($consulta_servicio);
            $resultado->bind_param("s", $busqueda);
            $resultado->bind_result($id,$descripcion,$duracion,$precio);
            $resultado->execute();
            $resultado->store_result();         
        }

        $filas_devueltas=$resultado->num_rows;  

        // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
        if($filas_devueltas===0){

            //SI HE REALIZADO LA BUSQUEDA ME MOSTRARÁ UN MENSAJE DISTINTO A SI ME METO DE PRIMERAS EN LA PÁGINA
            if(isset($busqueda)){
                echo "<h2>No hay servicios que coincidan con la busqueda</h2>";
            }else{
                echo "<h2>No hay servicios que mostrar</h2>";
            }
        }else{
            echo "<table>";
            echo "<tr><th>Descripción</th><th>Duración</th><th>Precio</th></tr>";

            //SI HE BUSCADO MOSTRARÁ LOS RESULTADOS QUE COINCIDAN CON LA BUSQUEDA. SINO MOSTRARÁ TODOS
            if(isset($busqueda)){
                while ($resultado->fetch()){                
                    echo "<tr>
                            <td>$descripcion</td>
                            <td>$duracion".'min'."</td>
                            <td>$precio".'€'."</td>";

                            if(isset($_SESSION["dni"]) && $_SESSION["dni"]==="000000000"){
                                echo "<td>
                                <form action='modificar_servicio.php' method='POST'>
                                    <input type='hidden' name='id' value='$id'>
                                    <input class='modificar' type='submit' name='modificar' value='Modificar'>
                                </form>
                            </td>";
                            }

                        echo "</tr>";    
                }
                $resultado->close();
            }else{
                while($fila_servicio=$resultado->fetch_array(MYSQLI_ASSOC)){
                    echo "<tr>
                            <td>$fila_servicio[descripcion]</td>
                            <td>$fila_servicio[duracion]".'min'."</td>
                            <td>$fila_servicio[precio]".'€'."</td>";

                            if(isset($_SESSION["dni"]) && $_SESSION["dni"]==="000000000"){
                                echo "<td>
                                <form action='modificar_servicio.php' method='POST'>
                                    <input type='hidden' name='id' value='$fila_servicio[id]'>
                                    <input class='modificar' type='submit' name='modificar' value='Modificar'>
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