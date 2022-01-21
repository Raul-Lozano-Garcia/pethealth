<?php
    session_start();

    if(isset($_GET["cerrar_sesion"])){
        if(isset($_COOKIE['mantener'])){
            setcookie("mantener",null,time()-60,"/");
        }
        $_SESSION=array();
        session_destroy();
        header('Location: index.php');
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
    <title>Noticia</title>
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

    <?php  
        //COMPRUEBO QUE PASE 'ID'. SINO REDIRIJO
        if(isset($_POST['id'])){
            $id=$_POST['id'];
            $consulta_noticia="SELECT titulo, contenido, imagen, fecha_publicacion FROM noticia WHERE id=$id";
            $resultado=$conexion->query($consulta_noticia);

            $fila_noticia=$resultado->fetch_array(MYSQLI_ASSOC);     

            //FORMATEMOS LA FECHA PARA QUE SEA MÁS ENTENDIBLE
            $fecha_formateada=formatearFecha($fila_noticia["fecha_publicacion"]);              
            echo "<section id='noticia'>
                    <h1>$fila_noticia[titulo]</h1>
                    <div>
                        <img src='../../assets/imagenes/noticias/$fila_noticia[imagen]' alt='noticia'>
                    </div> 
                    <span>$fecha_formateada</span>
                    <p>"; echo nl2br("$fila_noticia[contenido]"); echo "</p>
                </section>";
        }else{
            echo "<h1>No hay noticias que mostrar</h1>";
            echo "<meta http-equiv='refresh' content='2; url=../../index.php'>";
        }
    ?>

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