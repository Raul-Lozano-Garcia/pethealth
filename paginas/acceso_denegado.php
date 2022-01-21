<?php
    session_start();

    if(isset($_COOKIE["mantener"])){
        $_SESSION["dni"]=$_COOKIE["mantener"];
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceder</title>
    <script type="text/javascript" src="../js/app.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="icon" href="../assets/imagenes/logo_negro.png" type="image/png"/>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <!-- ENLAZO PARA PODER USAR FUNCIONES Y ME CONECTO A LA BASE DE DATOS  -->
    <?php
        require_once("../php/funciones.php");
        $conexion=conectarServidor();
    ?>

    <!-- INSERTO EL HEADER -->
    <?php
        crearHeader('..');
    ?>

    <main>

    <section id="acceso_denegado">

        <h1>Acceso denegado. Volviendo al inicio</h1>
        <div>
            <img src="../assets/imagenes/cargando.gif" alt="cargando">
        </div>

        <?php
            echo "<meta http-equiv='refresh' content='3; url=../index.php'>";
        ?>

    </section>

    </main>

    <!-- INSERTO EL FOOTER -->
    <?php
        crearFooter('..');
    ?>

    <!-- ME DESCONECTO DE LA BASE DE DATOS -->
    <?php
        $conexion->close();
    ?>
</body>
</html>