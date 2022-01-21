<?php
    session_start();

    if(isset($_COOKIE["mantener"])){
        $_SESSION["dni"]=$_COOKIE["mantener"];
    }

    // SI YA ME HE LOGEADO, NO PUEDO ACCEDER AQUÍ A TRAVÉS DE LA URL
    if(isset($_SESSION["dni"])){
        header('Location: ../../index.php');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceder</title>
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

    <section id="acceder">

        <form action="#" method="POST">
            <input type="text" name="nick" id="nick" placeholder="Nick">
            <input type="password" name="pass" id="pass" placeholder="Contraseña">
            <div id="mantener">
                <input type="checkbox" name="mantener" id="manten">
                <label for="manten">No cerrar sesión</label>
            </div>
            <div>
                <input type="submit" name="enviar" value="Enviar">
                <input type="reset" name="borrar" value="Borrar">
            </div>
        </form>

    <?php
        if(isset($_POST["enviar"])){
            $nick=trim($_POST["nick"]);
            $pass=md5(md5(md5(md5(md5(md5(md5(trim($_POST['pass']))))))));
            

            $consulta_usuario="SELECT dni FROM dueño WHERE nick=? and pass=?";
            $resultado=$conexion->prepare($consulta_usuario);
            $resultado->bind_param("ss",$nick, $pass);
            $resultado->bind_result($dni);
            $resultado->execute();
            $resultado->store_result();
            
            $filas_devueltas=$resultado->num_rows;  

            // VEO SI DEVUELVE ALGUNA FILA
            if($filas_devueltas===0){
                echo "<h2>Nick o contraseña incorrectos</h2>";         
            }else{     
                $resultado->fetch();

                if(isset($_POST["mantener"])){
                    setcookie("mantener",$dni,time()+60*60*24*365,"/");
                }else{
                    $_SESSION["dni"]=$dni;
                }
         
                echo "<meta http-equiv='refresh' content='0; url=../../index.php'>";
            }

            $resultado->close();
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