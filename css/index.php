<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETHEALTH - Tu veterinaria de confianza</title>
    <script type="text/javascript" src="js/app.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="icon" href="assets/imagenes/logo_negro.png" type="image/png"/>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <!-- ENLAZO PARA PODER USAR FUNCIONES Y ME CONECTO A LA BASE DE DATOS   -->
    <?php
        require_once("php/funciones.php");
        $conexion=conectarServidor();
    ?>

    <!-- INSERTO EL HEADER -->
    <?php
        crearHeader('.');
    ?>

    <main>
        <section id="banner">
            <div>
                <h1>PetHealth</h1>
                <p>Tu veterinaria de confianza</p>
            </div>
        </section>
        <section id="noticias">
            <h2>Últimas noticias</h2>
            <div id="lista_noticias">
            <?php  

                //SACO LA FECHA DE HOY
                $hoy=date("Y-m-d",time());

                //SACO LAS 3 ÚLTIMAS NOTICIAS PUBLICADAS (ES DECIR, NO PUEDEN TENER UNA FECHA DE PUBLICACIÓN MAYOR A HOY)
                $consulta_noticia="SELECT * FROM noticia WHERE fecha_publicacion<='$hoy' ORDER BY fecha_publicacion DESC LIMIT 3";

                $datos_noticia=$conexion->query($consulta_noticia);
                $filas_devueltas=$datos_noticia->num_rows;

                // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
                if($filas_devueltas===0){
                    echo "<h3>No hay noticias que mostrar</h3>";
                }else{
                    while ($fila_noticia=$datos_noticia->fetch_array(MYSQLI_ASSOC)){

                        // SI EL CONTENIDO TIENE MAS DE 20 PALABRAS LO ACORTO. SINO LO MUESTRO TAL CUAL
                        if (str_word_count($fila_noticia['contenido'], 0) > 20) {
                            $texto = acortarPalabras($fila_noticia['contenido']);
    
                            echo "<article class='noticia'>
                                    <div>
                                        <img src='assets/imagenes/noticias/$fila_noticia[imagen]' alt='noticia'>
                                    </div>
                                    <h3>$fila_noticia[titulo]</h3>
                                    <p>$texto</p>
                                    <form action='paginas/noticias/noticia.php' method='POST'>
                                        <input type='hidden' name='id' id='id' value='$fila_noticia[id]'>
                                        <input type='submit' value='Ver noticia completa'>
                                    </form>
                                </article>";
                        }else{
                            echo "<article class='noticia'>
                                    <div>
                                        <img src='assets/imagenes/noticias/$fila_noticia[imagen]' alt='noticia'>
                                    </div>
                                    <h3>$fila_noticia[titulo]</h3>
                                    <p>$fila_noticia[contenido]</p>
                                    <form action='paginas/noticias/noticia.php' method='POST'>
                                        <input type='hidden' name='id' value='$fila_noticia[id]'>
                                        <input type='submit' value='Ver noticia completa'>
                                    </form>
                                </article>";
                        }
                    }
                }

            ?>
            </div>
        </section>
        <section id="testimonio">
            <h2>Testimonios de nuestros clientes</h2>
            <?php  

                //SACO UN TESTIMONIO ALEATORIO
                $consulta_testimonio="SELECT autor,contenido FROM testimonio ORDER BY RAND() LIMIT 1";
                $datos_testimonio=$conexion->query($consulta_testimonio);
                $filas_devueltas=$datos_testimonio->num_rows;

                // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
                if($filas_devueltas===0){
                    echo "<h3>No hay testimonios que mostrar</h3>";
                }else{
                    while($fila_testimonio=$datos_testimonio->fetch_array(MYSQLI_ASSOC)){
                        echo "<h3>$fila_testimonio[autor]</h3>
                              <p>'$fila_testimonio[contenido]'</p>";  
                    } 
                }       
            ?>
        </section>
        <section id="contacto">
            <h2>¿Tienes dudas? Contacta con nosotros</h2>
            <form action="#" method="POST">
                <input type="text" name="nombre" id="nombre" placeholder="Nombre">
                <input type="email" name="correo" id="correo" placeholder="Correo electrónico">
                <textarea name="consulta" id="consulta" cols="30" rows="10" placeholder="Inserte su consulta"></textarea>
                <div>
                    <input type="submit" name="enviar" value="Enviar">
                    <input type="reset" name="borrar" value="Borrar">
                </div>
            </form>
        </section>
    </main>

    <!-- INSERTO EL FOOTER -->
    <?php
        crearFooter('.');
    ?>

    <!-- ME DESCONECTO DE LA BASE DE DATOS -->
    <?php
        $conexion->close();
    ?>
</body>
</html>
