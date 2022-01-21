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
    <title>Insertar testimonio</title>
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

    // SACO UNA LISTA CON LOS DUEÑOS DE LA CLINICA
    $consulta_duenios="SELECT dni, nombre FROM dueño WHERE dni<>'000000000'";
    $datos_duenios=$conexion->query($consulta_duenios);
    $filas_duenios=$datos_duenios->num_rows;

    //SI NO HAY AL MENOS UN DUEÑO NO PUEDO INSERTAR TESTIMONIOS, POR LO QUE REDIRIJO A LA PÁGINA DE TESTIMONIOS
    if($filas_duenios===0){
        echo "<div id='acceso_denegado'>
                    <h1>Lo sentimos. No hay suficientes dueños para introducir testimonios</h1>
                    <div>
                        <img src='../../assets/imagenes/cargando.gif' alt='cargando'>
                    </div>
                </div>";
        echo "<meta http-equiv='refresh' content='2; url=testimonios.php'>";
    }else{
    ?>

    <?php
        //SACAMOS EL SIGUIENTE ID
        $id=siguienteId('testimonio');

        //SACO LA FECHA DE HOY
        $hoy=date("Y-m-d",time());
    ?>

    <h1>Nuevo testimonio</h1>
    <form action="#" method="POST">
    <div>
        <label for="id_siguiente">Id</label>
        <input type="text" value="<?php echo $id ?>" id="id_siguiente" readonly>
    </div>
    <div>
       <label for="autor">Autor del testimonio</label>
        <select name="autor" id="autor" required>
        <?php
        while ($fila_duenios=$datos_duenios->fetch_array(MYSQLI_ASSOC)){
            echo "<option value='$fila_duenios[dni]'>$fila_duenios[nombre]</option>";
        }
        ?>
        </select>
    </div>
    <textarea name="contenido" id="contenido" rows="5" placeholder="Contenido del testimonio" maxlength="500" required></textarea>
    <input type="submit" value="Insertar" name="enviar" id="enviar">
    <input type="reset" value="Borrar" name="borrar" id="borrar">
    </form>

    <?php
        if(isset($_POST['enviar'])){

            //SI SE HA DEJADO ALGÚN CAMPO VACÍO LO REDIRIJO A LA PROPIA PAGINA. PASO DE SEGURIDAD EXTRA AL REQUIRED DEL HTML
            if(trim($_POST['autor'])==="" || trim($_POST['contenido'])===""){
                echo "<meta http-equiv='refresh' content='0; url=insertar_testimonio.php'>";

            //SI ALGÚN CAMPO NO CUMPLE CON LOS REQUISITOS LO REDIRIJO A LA PROPIA PAGINA
            }else if(strlen(trim($_POST["autor"]))>50 || strlen(trim($_POST["contenido"])) > 500){
                echo "<meta http-equiv='refresh' content='0; url=insertar_testimonio.php'>";
            }else{

                $consulta_insercion="INSERT INTO testimonio values (?,?,?,?)";
                $resultado_insercion=$conexion->prepare($consulta_insercion);

                $autor=trim($_POST['autor']);
                $contenido=trim($_POST['contenido']);

                $resultado_insercion->bind_param("isss",$id, $contenido, $hoy, $autor);
                $resultado_insercion->execute();
                $resultado_insercion->close();
                echo "<meta http-equiv='refresh' content='0; url=testimonios.php'>";
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
