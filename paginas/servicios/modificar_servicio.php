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
    <title>Modificar servicio</title>
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
        //COMPRUEBO QUE PASE 'ID'. SINO REDIRIJO
        if(isset($_POST['id'])){
            $id=$_POST['id'];

            $consulta="SELECT descripcion,duracion,precio from servicio where id=$id";
            
            $resultado=$conexion->query($consulta);

            $fila_servicio=$resultado->fetch_array(MYSQLI_ASSOC);

            //RECOJO LOS DATOS ANTES DE MODIFICAR EN VARIABLES
            $descripcion=$fila_servicio["descripcion"];
            $duracion=$fila_servicio["duracion"];
            $precio=$fila_servicio["precio"];
    ?>

    <h1>Editar servicio</h1>
    <form action="#" method="POST">

    <input type='hidden' name='id' value="<?php echo $id; ?>">

    <div>
        <label for="descripcion">Descripción del servicio</label>
        <input type="text" value="<?php echo $descripcion; ?>" name="descripcion" id="descripcion" maxlength="50">
    </div>
    <div>
        <label for="duracion">Duracion del servicio (minutos)</label>
        <input type="number" value="<?php echo $duracion; ?>" name="duracion" id="duracion" min="1" max="999">
    </div>
    <div>
        <label for="precio">Precio del servicio (€)</label>
        <input type="number" value="<?php echo $precio; ?>" name="precio" id="precio" step="0.01" min="0.01" max="999.99">
    </div>
    <input type="submit" value="Actualizar" name="enviar" id="enviar">
    <input type="reset" value="Volver por defecto" name="borrar" id="borrar">
    </form>

    <?php

        }else{
            echo "<div id='acceso_denegado'>
                    <h1>Error. Este servicio no existe</h1>
                    <div>
                        <img src='../../assets/imagenes/cargando.gif' alt='cargando'>
                    </div>
                </div>";
            echo "<meta http-equiv='refresh' content='2; url=servicios.php'>";
        }   

        if(isset($_POST['enviar'])){

            $consulta_insercion="UPDATE servicio SET descripcion=?, duracion=?, precio=? WHERE id=?";
            $resultado_insercion=$conexion->prepare($consulta_insercion);
        
            //SI LOS CAMPOS LOS DEJA VACIOS O NO CUMPLEN LOS REQUISITOS LE DEJO LO QUE TUVIESE ANTES DE MODIFICAR
            if(trim($_POST['descripcion'])!="" && strlen(trim($_POST['descripcion'])) <= 50){
                $descripcion=trim($_POST['descripcion']);
            }

            if(trim($_POST['duracion'])!="" && is_numeric(trim($_POST['duracion']))){
                $duracion=trim($_POST['duracion']);
            }

            if(trim($_POST['precio'])!="" && is_numeric(trim($_POST['precio']))){
                $precio=trim($_POST['precio']);
            }

            $resultado_insercion->bind_param("sidi", $descripcion, $duracion, $precio, $id);       
            $resultado_insercion->execute();
            $resultado_insercion->close();
            echo "<meta http-equiv='refresh' content='0; url=servicios.php'>";
        
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
