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
    <?php
        if($_SESSION["dni"]==="000000000"){
            echo "<title>Modificar dueño</title>";
        }else{
            echo "<title>Modificar mis datos</title>";
        }
    ?>
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
        //DIFERENCIO DE SI HE ACCEDIDO POR URL COMO USUARIO O POR EL BOTON DE MODIFICAR COMO ADMIN
        if($_SESSION['dni']==="000000000" && isset($_POST["dni"]) || $_SESSION['dni']!=="000000000"){

            if($_SESSION['dni']==="000000000" && isset($_POST["dni"])){
                $dni=$_POST['dni'];
            }else{
                $dni=$_SESSION['dni'];
            }
                $consulta="SELECT nombre,telefono,nick,pass from dueño where dni='$dni'";      
                $resultado=$conexion->query($consulta);

                $fila_duenio=$resultado->fetch_array(MYSQLI_ASSOC);

                //RECOJO LOS DATOS ANTES DE MODIFICAR EN VARIABLES
                $nombre=$fila_duenio["nombre"];
                $telefono=$fila_duenio["telefono"];
                $nick=$fila_duenio["nick"];
                $pass=$fila_duenio["pass"]; 
            
            
        
    ?>

    <h1>Editar dueño</h1>
    <form action="#" method="POST">

    <?php

    echo "<input type='hidden' name='dni' value='$dni'>";
    if($_SESSION["dni"]==="000000000"){
        echo "<div>
            <label for='dni'>DNI</label>
            <input type='text' value='$dni' id='dni' readonly>
        </div>
        <div>
            <label for='nombre'>Nombre del dueño</label>
            <input type='text' value='$nombre' name='nombre' id='nombre' maxlength='50'>
        </div>
        <div>
            <label for='nick'>Nick del dueño</label>
            <input type='text' value='$nick' name='nick' id='nick' maxlength='25'>
        </div>";
    }else{
        echo "<div>
            <label for='dni'>DNI</label>
            <input type='text' value='$dni' id='dni' readonly>
        </div>
        <div>
            <label for='nombre'>Nombre del dueño</label>
            <input type='text' value='$nombre' id='nombre' readonly>
        </div>
        <div>
            <label for='nick'>Nick del dueño</label>
            <input type='text' value='$nick' id='nick' readonly>
        </div>";
    }  

    echo "<div>
        <label for='telefono'>Telefono (opcional)</label>
        <input type='text' value='$telefono' name='telefono' id='telefono' minlength='9' maxlength='9'>
    </div>
    <div>
        <label for='pass'>Nueva contraseña</label>
        <input type='password' name='pass' id='pass' maxlength='50'>
    </div>
    <input type='submit' value='Actualizar' name='enviar' id='enviar'>
    <input type='reset' value='Volver por defecto' name='borrar' id='borrar'>";

    ?>

    </form>

    <?php

        }else{
            echo "<div id='acceso_denegado'>
                    <h1>Error. Este dueño no existe</h1>
                    <div>
                        <img src='../../assets/imagenes/cargando.gif' alt='cargando'>
                    </div>
                </div>";
            echo "<meta http-equiv='refresh' content='2; url=duenios.php'>";
        }   

        if(isset($_POST['enviar'])){

            $consulta_insercion="UPDATE dueño SET nombre=?, telefono=?, nick=?, pass=? WHERE dni=?";
            $resultado_insercion=$conexion->prepare($consulta_insercion);
        
            //SI LOS CAMPOS LOS DEJA VACIOS O NO CUMPLEN CON LOS REQUISITOS LE DEJO LO QUE TUVIESE ANTES DE MODIFICAR
            if(isset($_POST['nombre']) && trim($_POST['nombre'])!="" && strlen(trim($_POST['nombre']))<=50){
                $nombre=trim($_POST['nombre']);
            }

            if(strlen(trim($_POST['telefono']))===9){
                $telefono=trim($_POST['telefono']);
            }
            
            if(isset($_POST['nick']) && trim($_POST['nick'])!="" && strlen(trim($_POST['nick']))<=25){
                $nick=trim($_POST['nick']);
            }

            if(trim($_POST['pass'])!="" && strlen(trim($_POST['pass']))<=50){
                $pass=md5(md5(md5(md5(md5(md5(md5(trim($_POST['pass']))))))));
            }

            $resultado_insercion->bind_param("sssss", $nombre, $telefono, $nick, $pass, $dni);  
            $resultado_insercion->execute();
            $resultado_insercion->close();
            echo "<meta http-equiv='refresh' content='0; url=duenios.php'>";   
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
