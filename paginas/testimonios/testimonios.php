<?php
    session_start();

    if(isset($_GET["cerrar_sesion"])){
        if(isset($_COOKIE['mantener'])){
            setcookie("mantener",null,time()-60,"/");
        }
        $_SESSION=array();
        session_destroy();
        header('Location: ../../index.php');
    }else{
        if(isset($_COOKIE["mantener"])){
            $_SESSION["dni"]=$_COOKIE["mantener"];
        }
    
        if(!isset($_SESSION["dni"])){
            header('Location: ../acceder/acceder.php');
        }

    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonios</title>
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

    <section id="testimonios">

    <a id="agregar" href="insertar_testimonio.php">Insertar nuevo testimonio</a>

    <?php  
        $consulta_testimonio="SELECT nombre,contenido,fecha FROM testimonio INNER JOIN dueño ON dni_autor=dni ORDER BY fecha DESC";
        $resultado=$conexion->query($consulta_testimonio);

        $filas_devueltas=$resultado->num_rows;  

        // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
        if($filas_devueltas===0){
            echo "<h2>No hay testimonios que mostrar</h2>";          
        }else{     
            echo "<table>";
            echo "<tr><th>Autor</th><th>Contenido</th><th>Fecha</th></tr>";

            while($fila_testimonio=$resultado->fetch_array(MYSQLI_ASSOC)){
                
                //FORMATEMOS LA FECHA PARA QUE SEA MÁS ENTENDIBLE
                $fecha_formateada=formatearFecha($fila_testimonio['fecha']);

                echo "<tr>
                        <td>$fila_testimonio[nombre]</td>
                        <td>"; echo nl2br("$fila_testimonio[contenido]"); echo "</td>
                        <td>$fecha_formateada</td>
                    </tr>";   
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