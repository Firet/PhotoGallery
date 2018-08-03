<?php

function limpiarDatos($datos){
  $datos = trim($datos); //Elimina espacios, tabulaciones, saltos de linea al inicio y fin.
  $datos = htmlentities($datos, ENT_QUOTES);//Pasa los caracteres especiales a entidades HTML (ej: "Soy <b>pesado</b>" devuelve "Soy &lt;b&gt;pesado&lt;/b&gt;");
  //La constante "ENT_QUOTES" Convertirá tanto las comillas dobles como las simples.
  return $datos; //Devolvemos los datos un poco mas seguros
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES)) { //Si se envio informacion a través del metodo POST y el array FILES no esta vacio

  //La función "print_r($_FILES)" devuelve un array de los archivos pasados a través de POST (para saber que hay ahi dentro).

  $img_dir = "img/subidas/"; //Directorio donde vamos a subir las imagenes en nustro servidor.

  $img_tmp = $_FILES['imagen']["tmp_name"]; //El archivo temporal, este se almacena en nuestro servidor mientras se ejecute este mismo archivo PHP.

  $img_nombre = basename($_FILES['imagen']['name']); //Basename() devuelve el ultimo componente de nombre de una ruta (ej: De la ruta "C://Una carpeta/otra mas/archivo.tal" devuelve, "archivo.tal").
  //$_FILES['name'] devuelve el nombre original del archivo cargado (NOTA: El nombre del archivo temporal es diferente).

  $guardar = move_uploaded_file($img_tmp, $img_dir . $img_nombre); //Esta funcion guarda el archivo "$img_tmp" en el destino "$img_dir . $img_nombre" las dos variables concatenadas con el punto "img/subidas/miarchivosubido.png".
  //La funcion devuelve un TRUE o FALSE dependiendo si pudo guardar el archivo. Ese booleano lo guardamos en $guardar.

  $conexion = new PDO('mysql:host=localhost;dbname=galeriaimg', 'root', ''); //Crea una instancia de PDO que representa una conexión a una base de datos
  //HOST: direccion de nuestro servidor de base de datos. DBNAME: nombre de la base de datos. El segundo parametro ('root') representa el nombre de usuario MySQL y el tercero la contraseña.

  $titulo =  limpiarDatos($_POST['titulo']);
  $descripcion = limpiarDatos($_POST['descripcion']);
  $ruta = $img_dir . $img_nombre;
  //Guardamos y limpiamos los datos pasados por POST

  //Una vez que la imagen este subida y los datos limpios los subimos a la base de datos.

  $consulta = $conexion->prepare('INSERT INTO imagenes (titulo,descripcion,ruta) VALUES (:nombre,:descripcion,:ruta)');
  $consulta->execute(array(':nombre' => $titulo,':descripcion' => $descripcion,':ruta' => $ruta));

  /* La sentencia SQL puede contener cero o más marcadores de parámetros con nombre (:name) o signos de interrogación (?) por los cuales
  los valores reales serán sustituidos cuando la sentencia sea ejecutada. Se deben usar estos parámetros para sustituir cualquier dato
  de usuario, y no usarlos directamente en la consulta. */

  /*Si el servidor de la base de datos prepara con éxito la sentencia, PDO::prepare() devuelve un objeto PDOStatement. Si no es posible,
  PDO::prepare() devuelve FALSE o emite una excepciónPDOException (dependiendo del manejo de errores).*/

  header('Location: index.html'); //El servidor envia el encabezado al navegador y lo redirige al archivo especificado.

  //////////////////////////////////////////////////////////////////////////////
  //
  // NOTA: Seria importante tratar a fondo la limpieza de los datos, el manejo de errores de la base de datos
  // y de la subida de imagenes. Para evitar fallos de seguridad y del sistema.
  //
  // Bibliografia:
  // Función trim(): http://php.net/manual/es/function.trim.php
  // Función htmlentities(): http://php.net/manual/es/function.htmlentities.php
  // Variable (array) predefinida $_SERVER: http://php.net/reserved.variables.server
  // Variable predefinida $_FILES: http://php.net/manual/es/reserved.variables.files.php
  // Funcion basename(): http://php.net/manual/es/function.basename.php
  // Funcion move_uploaded_file(): http://php.net/manual/es/function.move-uploaded-file.php
  // Clase PDO: http://php.net/manual/es/pdo.construct.php
  // Metodo prepare de PDO: http://php.net/manual/es/pdo.prepare.php
  // Metodo execute de PDO: http://php.net/manual/es/pdostatement.execute.php
  // Funcion header(): http://php.net/manual/es/function.header.php
  //
  //////////////////////////////////////////////////////////////////////////////



}

?>
