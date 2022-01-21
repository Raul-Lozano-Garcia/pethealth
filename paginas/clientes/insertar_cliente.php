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
    <title>Insertar cliente</title>
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

    // SACO UNA LISTA CON LOS DUEÑOS DE LA CLINICA MENOS EL ADMINISTRADOR
    $consulta_duenios="SELECT dni, nombre FROM dueño WHERE dni<>'000000000'";
    $datos_duenios=$conexion->query($consulta_duenios);
    $filas_duenios=$datos_duenios->num_rows;

    //SI NO HAY AL MENOS UN DUEÑO NO PUEDO INSERTAR CLIENTES, POR LO QUE REDIRIJO A LA PÁGINA DE CLIENTES
    if($filas_duenios===0){
        echo "<div id='acceso_denegado'>
                    <h1>Lo sentimos. No hay suficientes dueños para introducir clientes</h1>
                    <div>
                        <img src='../../assets/imagenes/cargando.gif' alt='cargando'>
                    </div>
                </div>";
        echo "<meta http-equiv='refresh' content='2; url=clientes.php'>";
    }else{
    ?>

    <!-- SACAMOS EL SIGUIENTE ID -->
    <?php
        $id=siguienteId('cliente');
    ?>

    <h1>Nuevo cliente</h1>
    <form action="#" method="POST" enctype="multipart/form-data">
    <div>
        <label for="id_siguiente">Id</label>
        <input type="text" value="<?php echo $id ?>" id="id_siguiente" readonly>
    </div>
    <div>
        <label for="tipo">Tipo de animal</label>
        <input type="text" name="tipo" id="tipo" maxlength="50" required>
    </div>
    <div>
        <label for="nombre">Nombre del animal</label>
        <input type="text" name="nombre" id="nombre" maxlength="50" required>
    </div>
    <div>
        <label for="edad">Edad del animal (años)</label>
        <input type="number" name="edad" id="edad" value="1" min="1" max="999" required>
    </div>
    <div>
        <label for="duenio">Dueño</label>
        <select name="duenio" id="duenio" required>
        <?php
        while ($fila_duenios=$datos_duenios->fetch_array(MYSQLI_ASSOC)){
            echo "<option value='$fila_duenios[dni]'>$fila_duenios[nombre]</option>";
        }
        ?>
        </select>
    </div>
    <div>
        <label for="foto">Subir foto</label>
        <input type="file" name="foto" id="foto" lang="es" required> 
    </div>
    <input type="submit" value="Insertar" name="enviar" id="enviar">
    <input type="reset" value="Borrar" name="borrar" id="borrar">
    </form>

    <?php
        if(isset($_POST['enviar'])){

            //SI SE HA DEJADO ALGÚN CAMPO VACÍO LO REDIRIJO A LA PROPIA PAGINA. PASO DE SEGURIDAD EXTRA AL REQUIRED DEL HTML
            if(trim($_POST['tipo'])==="" || trim($_POST['nombre'])==="" || trim($_POST['edad'])==="" || trim($_POST['duenio'])==="" || $_FILES['foto']['tmp_name']===""){
                echo "<meta http-equiv='refresh' content='0; url=insertar_cliente.php'>";

            //SI ALGÚN CAMPO NO CUMPLE CON LOS REQUISITOS LO REDIRIJO A LA PROPIA PAGINA
            }else if(strlen(trim($_POST["tipo"]))>50 || strlen(trim($_POST["nombre"]))>50 || !is_numeric(trim($_POST["edad"]))){
                echo "<meta http-equiv='refresh' content='0; url=insertar_cliente.php'>";
            }else{

                //COMPRUEBO QUE ME HA METIDO UN FORMATO VÁLIDO PARA LA FOTO. SINO REDIRIJO
                $extension_imagen=extension_imagen($_FILES['foto']['type']);
                if($extension_imagen===''){
                    echo "<meta http-equiv='refresh' content='0; url=insertar_cliente.php'>";
                }else{
                    $consulta_insercion="INSERT INTO cliente values (?,?,?,?,?,?)";
                    $resultado_insercion=$conexion->prepare($consulta_insercion);

                    $tipo=trim($_POST['tipo']);
                    $nombre=trim($_POST['nombre']);
                    $edad=trim($_POST['edad']);
                    $duenio=trim($_POST['duenio']);
    
                    //COMPRUEBO QUE EXISTE LA CARPETA DE CLIENTES. SINO LA CREO
                    if(!file_exists("../../assets/imagenes/clientes")){
                        mkdir("../../assets/imagenes/clientes");
                    }
    
                    //COPIO LA IMAGEN CON EL NAME "FOTO"
                    $nombre_temporal_imagen=$_FILES['foto']['tmp_name'];
                    $nombre_imagen="$id".$extension_imagen;
                    move_uploaded_file($nombre_temporal_imagen,"../../assets/imagenes/clientes/$nombre_imagen");
                    $foto=$nombre_imagen;
    
                    $resultado_insercion->bind_param("ississ",$id, $tipo, $nombre, $edad, $foto, $duenio);
                    $resultado_insercion->execute();
                    $resultado_insercion->close();
                    echo "<meta http-equiv='refresh' content='0; url=clientes.php'>";
                }
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