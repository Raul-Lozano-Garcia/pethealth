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
        }else if($_SESSION["dni"]!=="000000000"){
            header('Location: ../acceso_denegado.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar noticia</title>
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

    <?php
        //SACAMOS EL SIGUIENTE ID
        $id=siguienteId('noticia');
    ?>

    <main>

    <section id="insertar_modificar">

    <h1>Nueva noticia</h1>
    <form action="#" method="POST" enctype="multipart/form-data">
    <div>
        <label for="id_siguiente">Id</label>
        <input type="text" value="<?php echo $id ?>" id="id_siguiente" readonly>
    </div>
    <div>
        <label for="titulo">Título de la noticia</label>
        <input type="text" name="titulo" id="titulo" maxlength="50" required>
    </div>
    <textarea name="contenido" id="contenido" rows="15" placeholder="Contenido de la noticia" maxlength="5000" required></textarea>
    <div>
        <label for="imagen">Subir imagen</label>
        <input type="file" name="imagen" id="imagen" lang="es" required>
    </div>
    <div>
        <label for="fecha_publicacion">Fecha de publicación de la noticia</label>
        <input type="date" name="fecha_publicacion" id="fecha_publicacion" required>
    </div>
    <input type="submit" value="Insertar" name="enviar" id="enviar">
    <input type="reset" value="Borrar" name="borrar" id="borrar">
    </form>

    <?php
        if(isset($_POST['enviar'])){
            //SI SE HA DEJADO ALGÚN CAMPO VACÍO LO REDIRIJO A LA PROPIA PAGINA. PASO DE SEGURIDAD EXTRA AL REQUIRED DEL HTML
            if(trim($_POST['titulo'])==="" || trim($_POST['contenido'])==="" || trim($_POST['fecha_publicacion'])==="" || $_FILES['imagen']['tmp_name']===""){
                echo "<meta http-equiv='refresh' content='0; url=insertar_noticia.php'>";

            //SI ALGÚN CAMPO NO CUMPLE CON LOS REQUISITOS LO REDIRIJO A LA PROPIA PAGINA
            }else if(strlen(trim($_POST["titulo"]))>50 || strlen(trim($_POST["contenido"]))>5000){
                echo "<meta http-equiv='refresh' content='0; url=insertar_noticia.php'>";
            }else{
                //COMPRUEBO QUE ME HA METIDO UN FORMATO VÁLIDO PARA LA IMAGEN. SINO REDIRIJO
                $extension_imagen=extension_imagen($_FILES['imagen']['type']);
                if($extension_imagen===''){
                    echo "<meta http-equiv='refresh' content='0; url=insertar_noticia.php'>";
                }else{
                    $consulta_insercion="INSERT INTO noticia values (?,?,?,?,?)";
                    $resultado_insercion=$conexion->prepare($consulta_insercion);
        
                    $titulo=trim($_POST['titulo']);
                    $contenido=trim($_POST['contenido']);
                    $fecha_publicacion=trim($_POST['fecha_publicacion']);
    
                    //COMPRUEBO QUE EXISTE LA CARPETA DE NOTICIAS. SINO LA CREO
                    if(!file_exists("../../assets/imagenes/noticias")){
                        mkdir("../../assets/imagenes/noticias");
                    }
    
                    //COPIO LA IMAGEN CON EL NAME "IMAGEN"
                    $nombre_temporal_imagen=$_FILES['imagen']['tmp_name'];
                    $nombre_imagen="$id".$extension_imagen;
                    move_uploaded_file($nombre_temporal_imagen,"../../assets/imagenes/noticias/$nombre_imagen");
                    $imagen=$nombre_imagen;
    
                    $resultado_insercion->bind_param("issss",$id, $titulo, $contenido, $imagen, $fecha_publicacion);
                    $resultado_insercion->execute();
                    $resultado_insercion->close();
                    echo "<meta http-equiv='refresh' content='0; url=noticias.php'>";
                }
            }
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
