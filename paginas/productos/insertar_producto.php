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
    <title>Insertar producto</title>
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

    <section id="insertar_modificar">

    <?php
        //SACAMOS EL SIGUIENTE ID
        $id=siguienteId('producto');
    ?>

    <h1>Nuevo producto</h1>
    <form action="#" method="POST">
    <div>
        <label for="id_siguiente">Id</label>
        <input type="text" value="<?php echo $id ?>" id="id_siguiente" readonly>
    </div>
    <div>
        <label for="nombre">Nombre del producto</label>
        <input type="text" name="nombre" id="nombre" maxlength="50" required>
    </div>
    <div>
        <label for="precio">Precio del producto (€)</label>
        <input type="number" name="precio" id="precio" value="0.01" step="0.01" min="0.01" max="999.99" required>
    </div>
    <input type="submit" value="Insertar" name="enviar" id="enviar">
    <input type="reset" value="Borrar" name="borrar" id="borrar">
    </form>

    <?php
        if(isset($_POST['enviar'])){

            //SI SE HA DEJADO ALGÚN CAMPO VACÍO LO REDIRIJO A LA PROPIA PAGINA. PASO DE SEGURIDAD EXTRA AL REQUIRED DEL HTML
            if(trim($_POST['nombre'])==="" || trim($_POST['precio'])===""){
                echo "<meta http-equiv='refresh' content='0; url=insertar_producto.php'>";
                
            //SI ALGÚN CAMPO NO CUMPLE CON LOS REQUISITOS LO REDIRIJO A LA PROPIA PAGINA
            }else if(strlen(trim($_POST["nombre"]))>50 || !is_numeric(trim($_POST["precio"]))){
                echo "<meta http-equiv='refresh' content='0; url=insertar_producto.php'>";
            }else{

                $consulta_insercion="INSERT INTO producto values (?,?,?)";
                $resultado_insercion=$conexion->prepare($consulta_insercion);

                $nombre=trim($_POST['nombre']);
                $precio=trim($_POST['precio']);

                $resultado_insercion->bind_param("isd",$id, $nombre, $precio);
                $resultado_insercion->execute();
                $resultado_insercion->close();
                echo "<meta http-equiv='refresh' content='0; url=productos.php'>";
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
