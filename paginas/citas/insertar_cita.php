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
    <title>Insertar cita</title>
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

        // SACO UNA LISTA CON LOS CLIENTES Y SERVICIOS DE LA CLINICA
        $consulta_clientes="SELECT id, nombre FROM cliente";
        $datos_clientes=$conexion->query($consulta_clientes);
        $filas_clientes=$datos_clientes->num_rows;

        $consulta_servicios="SELECT id, descripcion FROM servicio";
        $datos_servicios=$conexion->query($consulta_servicios);
        $filas_servicios=$datos_servicios->num_rows;

        //SI NO HAY AL MENOS UN SERVICIO O UN CLIENTE NO PUEDO CONCERTAR CITAS, POR LO QUE REDIRIJO A LA PÁGINA DE CITAS
        if($filas_clientes===0 || $filas_servicios===0){
            echo "<div id='acceso_denegado'>
                    <h1>Lo sentimos. No hay suficientes clientes y/o servicios para concertar citas</h1>
                    <div>
                        <img src='../../assets/imagenes/cargando.gif' alt='cargando'>
                    </div>
                </div>";
            echo "<meta http-equiv='refresh' content='2; url=citas.php'>";
        }else{
            //SACO LA FECHA DE HOY
            $hoy=date("Y-m-d",time());
            //SACO LA HORA ACTUAL
            $hora=date("H:i:s",time());
    ?>

    <h1>Nueva cita</h1>
    <form action="#" method="POST">
    <div>
        <label for="cliente">Cliente</label>
        <select name="cliente" id="cliente" required>
        <?php
        while ($fila_clientes=$datos_clientes->fetch_array(MYSQLI_ASSOC)){
            echo "<option value='$fila_clientes[id]'>$fila_clientes[nombre]</option>";
        }
        ?>
        </select>
    </div>
    <div>
        <label for="servicio">Servicio</label>
        <select name="servicio" id="servicio" required>
        <?php
        while ($fila_servicios=$datos_servicios->fetch_array(MYSQLI_ASSOC)){
            echo "<option value='$fila_servicios[id]'>$fila_servicios[descripcion]</option>";
        }
        ?>
        </select>
    </div>
    <div>
        <label for="fecha">Fecha de la cita</label>
        <input type="date" name="fecha" id="fecha" min="<?php echo $hoy?>" required>
    </div>
    <div>
        <label for="hora">Hora de la cita</label>
        <input type="time" name="hora" id="hora" required>
    </div>
    <input type="submit" value="Insertar" name="enviar" id="enviar">
    <input type="reset" value="Borrar" name="borrar" id="borrar">
    </form>

    <?php
        if(isset($_POST['enviar'])){

            //SI SE HA DEJADO ALGÚN CAMPO VACÍO LO REDIRIJO A LA PROPIA PAGINA. PASO DE SEGURIDAD EXTRA AL REQUIRED DEL HTML
            if(trim($_POST['fecha'])==="" || trim($_POST['hora'])===""){
                echo "<meta http-equiv='refresh' content='0; url=insertar_cita.php'>";
            //COMPRUEBO SI LA CITA SE HA INSERTADO PARA UNA FECHA Y HORAS POSIBLES
            }else if(trim($_POST['fecha'])>=$hoy &&  trim($_POST['hora'])>=$hora){
                $consulta_insercion="INSERT INTO citas values (?,?,?,?)";
                $resultado_insercion=$conexion->prepare($consulta_insercion);
    
                $cliente=trim($_POST['cliente']);
                $servicio=trim($_POST['servicio']);
                $fecha=trim($_POST['fecha']);
                $hora=trim($_POST['hora']);

                $resultado_insercion->bind_param("iiss",$cliente, $servicio, $fecha, $hora);
                $resultado_insercion->execute();
                $resultado_insercion->close();
                echo "<meta http-equiv='refresh' content='0; url=citas.php'>";
            }else{
                echo "<meta http-equiv='refresh' content='0; url=insertar_cita.php'>"; 
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
