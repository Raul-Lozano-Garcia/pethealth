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
    <title>Insertar dueño</title>
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

    <h1>Nuevo dueño</h1>
    <form action="#" method="POST">
    <div>
        <label for="dni">DNI</label>
        <input type="text" name="dni" id="dni" minlength="9" maxlength="9" required>
    </div>
    <div>
        <label for="nombre">Nombre del dueño</label>
        <input type="text" name="nombre" id="nombre" maxlength="50" required>
    </div>
    <div>
        <label for="nick">Nick del dueño</label>
        <input type="text" name="nick" id="nick" maxlength="25" required>
    </div>
    <div>
        <label for="telefono">Teléfono (opcional)</label>
        <input type="tel" name="telefono" id="telefono" minlength="9" maxlength="9">
    </div>
    <div>
        <label for="pass">Contraseña</label>
        <input type="password" name="pass" id="pass" maxlength="50" required>
    </div>
    <input type="submit" value="Insertar" name="enviar" id="enviar">
    <input type="reset" value="Borrar" name="borrar" id="borrar">
    </form>

    <?php
        if(isset($_POST['enviar'])){

            //SI SE HA DEJADO ALGÚN CAMPO VACÍO LO REDIRIJO A LA PROPIA PAGINA. PASO DE SEGURIDAD EXTRA AL REQUIRED DEL HTML
            if(trim($_POST['dni'])==="" || trim($_POST['nombre'])==="" || trim($_POST['nick'])==="" || trim($_POST['pass'])===""){
                echo "<meta http-equiv='refresh' content='0; url=insertar_duenio.php'>";

            //SI ALGÚN CAMPO NO CUMPLE CON LOS REQUISITOS LO REDIRIJO A LA PROPIA PAGINA
            }else if(strlen(trim($_POST["dni"]))>9 || strlen(trim($_POST["dni"]))<9 || strlen(trim($_POST["nombre"]))>50 || strlen(trim($_POST["nick"]))>25 || strlen(trim($_POST["pass"]))>50){
                echo "<meta http-equiv='refresh' content='0; url=insertar_duenio.php'>";

            //SI EL TELÉFONO SE HA RELLENADO HAY QUE COMPROBAR QUE CUMPLE CON LOS REQUISITOS
            }else if(trim($_POST["telefono"])!=="" && strlen(trim($_POST["telefono"]))>9 || trim($_POST["telefono"])!=="" && strlen(trim($_POST["telefono"]))<9){
                echo "<meta http-equiv='refresh' content='0; url=insertar_duenio.php'>";

            }else{
                $consulta_insercion="INSERT INTO dueño values (?,?,?,?,?)";
                $resultado_insercion=$conexion->prepare($consulta_insercion);

                $dni=trim($_POST['dni']);
                $nombre=trim($_POST['nombre']);
                if(trim($_POST['telefono'])===""){
                    $telefono=null;
                }else{
                    $telefono=trim($_POST['telefono']);
                }
                $nick=trim($_POST['nick']);
                $pass=md5(md5(md5(md5(md5(md5(md5(trim($_POST['pass']))))))));

                $resultado_insercion->bind_param("sssss",$dni, $nombre, $telefono, $nick, $pass);
                $resultado_insercion->execute();
                $resultado_insercion->close();
                echo "<meta http-equiv='refresh' content='0; url=duenios.php'>";
                
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
