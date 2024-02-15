<?php
session_start();
include '../inc/conection.php';
if ($_SESSION['usuario'] != '') {

  date_default_timezone_set("America/Argentina/Buenos_Aires");
  $quien = $_SESSION['usuario'];
  $cuando = date("Y-m-d H:i:s");
  $api_key = 'AIzaSyCzlFav95MEH_UoIvMStdOEeUovVJO2mqQ';


  //notificaciones
  $email_from = "info@prompt.com.ar";
  $fromname = "Notificaciones BIG POLLO";
  $headers  = "MIME-Version: 1.0\n";
  $headers .= "Content-type: text/html; charset=utf8\n";
  $headers .= "X-Priority: 3\n";
  $headers .= "X-MSMail-Priority: Normal\n";
  $headers .= "X-Mailer: php\n";
  $headers .= "From: \"" . $fromname . "\" <" . $email_from . ">\n";


  //echo 'afuera';
  if (isset($_POST['accion']) && $_POST['accion'] == 'up_notas') {
    $id = $_POST['id'];
    $notas = $_POST['notas'];

    $inserta = $link->query("UPDATE clientes SET notas_clientes='$notas' where id_clientes='$id'");

    header('Location: ../index.php?pagina=clientes_view&id=' . $id);
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_cate') {
    $nombre = $_POST['nombre'];
    $color = $_POST['color'];
    $iconito = $_POST['iconito'];
    $foto = $_POST['foto'];

    $inserta = $link->query("iNSERT INTO categorias set titulo_categoria='$nombre', color_categoria='$color', icono_categoria='$iconito', imagen_categoria ='$foto', quien_categoria='$quien', cuando_categoria='$cuando', estado_categoria='1' ");

    if ($inserta) {
      header('Location: ../index.php?pagina=categorias');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'edit_cate') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $color = $_POST['color'];
    $iconito = $_POST['iconito'];
    $foto = $_POST['foto'];

    $inserta = $link->query("UPDATE categorias set titulo_categoria='$nombre', color_categoria='$color', icono_categoria='$iconito', imagen_categoria ='$foto', quien_categoria='$quien', cuando_categoria='$cuando', estado_categoria='1' WHERE id_categoria ='$id' ");

    if ($inserta) {
      header('Location: ../index.php?pagina=categorias');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_GET['accion']) && $_GET['accion'] == 'delcat') {
    $id = $_GET['id'];
    $update = $link->query("UPDATE categorias set estado_categoria='0' WHERE id_categoria='$id'");
    if ($update) {
      header('Location: ../index.php?pagina=categorias');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_GET['accion']) && $_GET['accion'] == 'delrub') {
    $id = $_GET['id'];
    $update = $link->query("UPDATE rubros set estado_rubros='0' WHERE id_rubros='$id'");
    if ($update) {
      header('Location: ../index.php?pagina=rubros');
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_clientes') {
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['cuit'];
    $cumple = $_POST['cumple'];
    if($cumple==''){$cumple='0000-00-00';}
    $email = $_POST['email'];
    $email2 = $_POST['email2'];
    $telfijo = $_POST['telfijo_com'];
    $celular = $_POST['celular_com'];
    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $notas = $_POST['notas'];
  //  $direccion = $_POST['direccion'];
  //  $numero = $_POST['numero'];
  //  $piso = $_POST['piso'];
  //  $depto = $_POST['depto'];
    $razon =addslashes(htmlentities($_POST['razon']));
    $cuitcuil=$_POST['cuit'];
    $condicioniva=$_POST['condicioniva'];
    $rubro = $_POST['rubro'];
  //  $telfijo_com = $_POST['telfijo_com'];
  //  $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];
    $upload = $_POST['upload'];
    $asignado= $_POST['asignado'];
    if($asignado==''){$asignado='0';}
    $financia = $_POST['financia'];
    if($financia=='' || $financia=='null'){$financia='0';}
    $limite= $_POST['limite'];
    $listap= $_POST['listap'];


    // inicio geoencode
    $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$ciudad'");
    $row= mysqli_fetch_array($consulto_datos);
    $direccion_geo= $direccion_com.' '.$numero_com.','.$row['ciudad_nombre'].','.$row['provincia_nombre'].',Argentina';
    $direccion_geo= str_replace(' ','+',$direccion_geo);
      $url = "https://maps.googleapis.com/maps/api/geocode/json?key=".$api_key."&sensor=false&address=";
      $call = $url.urlencode($direccion_geo);
      $response = json_decode(file_get_contents($call), true);
      $latitud_com = $response['results'][0]['geometry']['location']['lat'];
      $longitud_com = $response['results'][0]['geometry']['location']['lng'];
      $latitud='';
      $longitud='';

    if ($latitud != '' || $latitud != '0' ){$latitud = str_replace(",", '.', $latitud);  $longitud=str_replace(",", '.', $longitud);}
    if ($latitud_com != '' || $latitud_com != '0' ){$latitud_com = str_replace(",", '.', $latitud_com);  $longitud_com=str_replace(",", '.', $longitud_com);}

    if($latitud_com==''){$latitud_com='0';}
    if($longitud_com==''){$longitud_com='0';}
    if($latitud==''){$latitud='0';}
    if($longitud==''){$longitud='0';}

    // fin geoencode
    $inserto_cliente ="INSERT INTO clientes SET
    razon_com_clientes='$razon',
    tipodni_clientes='$tipodni',
    dni_clientes='$dni',
    condicioniva_com_clientes='$condicioniva',
    rubro_com_clientes='$rubro',
    celular_clientes='$celular',
    telefono_clientes='$telfijo',
    email_clientes='$email',
    provincia_clientes='$provincia',
    ciudad_clientes='$ciudad',
    direccion_clientes='$direccion_com',
    dirnum_clientes='$numero_com',
    lat_clientes='$latitud_com',
    lng_clientes='$longitud_com',
    piso_clientes='$piso_com',
    depto_clientes='$depto_com',
    notas_clientes='$notas',
    fechacumple_clientes='$cumple',
    asignado_clientes='$asignado',
    financiacion_com_clientes='$financia',
    topefinancia_com_clientes='$limite',
    cp_cliente='0',
    foto_clientes='$upload',
    estado_clientes='1',
    quien_clientes='$quien',
    cuando_clientes='$cuando',
    lista_precio='$listap'";
    $inserta = $link->query($inserto_cliente);
    $id_clie_ulti = mysqli_insert_id($link);
  //  $inserta = $link->query("insert INTO clientes_comercios set situacion_comclientes='activo', cliente_comclientes='$id_clie_ulti', estado_comclientes='1',	quien_comclientes='$quien',	cuando_comclientes='$cuando'");

    if($inserta){echo $id_clie_ulti.'@'.$razon.'@'.$provincia.'@'.$ciudad.'@'.$direccion_com.'@'.$numero_com;}
     else {
     $accion= "Error al Insertar cliente  (".$inserto_cliente.")";  
     echo 'FALSE';
     }
  }


  //--------------------------------------//

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_comercio') {

    $cliente = $_POST['cliente'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['cuit'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $tel = $_POST['telefono'];
    $filial = $_POST['ciudad'];
    $dir = ucwords(strtolower(addslashes(htmlentities($_POST['direccion']))));
    $numdir = $_POST['dirnum'];
    $perifact = $_POST['perifact'];
    $vendedor = $_POST['vendedor'];
    $situacion = $_POST['situacion'];
    if ($situacion == '') {
      $situacion = 'activo';
    }
    $foto = $_FILES['files']['name'];
    if ($foto != '') {
      $foton = "foto_comclientes='" . $foto . "',";
    } else {
      $foton = '';
    }
    if ($filial != '') {
      $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$filial'");
      $row = mysqli_fetch_array($consulto_datos);

      $direccion_geo = $dir . ' ' . $numdir . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
      $direccion_geo = str_replace(' ', '+', $direccion_geo);

      $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address="; //AIzaSyDNFykTVx2b0z3Fu9T9AFidLY-dRmFXKAU
      $call = $url . urlencode($direccion_geo);
      $response = json_decode(file_get_contents($call), true);
      $latitud = $response['results'][0]['geometry']['location']['lat'];
      $longitud = $response['results'][0]['geometry']['location']['lng'];
    }
    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }



    $inserta = $link->query("INSERT INTO clientes_comercios SET cliente_comclientes='$cliente',perifact_comclientes='$perifact', razon_comclientes='$razon', $foton cuitcuil_comclientes='$cuitcuil', condicioniva_comclientes='$condicioniva', rubro_comclientes='$rubro', ciudad_comclientes='$filial', direccion_comclientes='$dir', dirnum_comclientes='$numdir', telefono_comclientes='$tel', cuando_comclientes='$cuando', lat_comclientes='$latitud', lon_comclientes='$longitud', quien_comclientes='$quien', estado_comclientes='1', vendedor_comclientes='$vendedor', situacion_comclientes='$situacion'");

    if ($inserta) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'add_pedido') {
    $monto = $_POST['monto'];
    $detalle = $_POST['detalle'];
    $comercio = $_POST['comercio'];
    $tipo_p = $_POST['tipo_pedido'];
    $fecha = $_POST['fechapedido'];

    $inserta = $link->query("INSERT INTO transaccion SET cliente='$comercio', fecha='$fecha', tipo_pedido='$tipo_p', detalle='$detalle', monto='$monto', tipo='pedido', abonada='0', quien='$quien', estado='1'");

    if ($inserta) {
      header('Location: ../index.php?pagina=pedidos');
    } else {
      header('Location: ../index.php?pagina=pedidos&error=si');
    }
  }


  if (isset($_POST['accion']) && $_POST['accion'] == 'add_pago') {
    $monto = $_POST['monto_pago'];
    $detalle = $_POST['detalle_pago'];
    $comercio = $_POST['comercio_pago'];
    $tipo_p = $_POST['tipo_pedido'];
    $fecha = $_POST['fecha_pago'];
    $inserta = $link->query("INSERT INTO transaccion SET cliente='$comercio', fecha='$fecha', tipo_pedido='$tipo_p', detalle='$detalle', monto2='$monto', tipo='pago', abonada='0', quien='$quien', estado='1'");
    if ($inserta) {

      header('Location: ../index.php?pagina=pagos');
    } //cierra if inserta
    else {
      header('Location: ../index.php?pagina=pagos&error=si');
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'edit_transaccion') {
    $id = $_POST['id_trans'];
    $monto = $_POST['monto_trans'];
    $tipo = $_POST['tipo_trans'];
    $detalle = $_POST['detalle_trans'];
    $comercio = $_POST['comercio_trans'];
    $tipo_p = $_POST['tipo_pedido'];
    $fecha = $_POST['fecha_trans'];
    if ($_POST['tipo_trans'] == 'pago') {
      $trasac = "monto2='" . $monto . "'";
    } else {
      $trasac = "monto='" . $monto . "'";
    }
    $inserta = $link->query("UPDATE transaccion SET cliente='$comercio', fecha='$fecha', tipo_pedido='$tipo_p', detalle='$detalle', $trasac, tipo='$tipo', abonada='0', estado='1' where id='$id' ");
    if ($inserta) {
      header('Location: ../index.php?pagina=' . $tipo . 's');
    } else {
      header('Location: ../index.php?pagina=' . $tipo . 's&error=si');
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'editar_clientes') {
    //echo 'entro';
    $id = $_POST['id'];
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['dni'];
    $sexo = $_POST['sexo'];
    if ($sexo == '' || $sexo == 'undefined') {
      $sexo = 'H';
    }

    $ecivil = $_POST['ecivil'];
    $cumple = $_POST['cumple'];
    if ($cumple == '') {
      $cumple = '0000-00-00';
    }
    $email = $_POST['email'];
    $email2 = $_POST['email2'];
    $telfijo = $_POST['telfijo'];
    $celular = $_POST['celular'];
    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $upload = $_POST['upload'];
    $notas = $_POST['notas'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['dni'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $telfijo_com = $_POST['telfijo_com'];
    $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];
    $upload = $_POST['upload'];
    $asignado = $_POST['asignado'];
    $financia = $_POST['financia'];
    $limite = $_POST['limite'];
    if ($asignado == '') {
      $asignado = '0';
    }
    // inicio geoencode
    $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$ciudad'");
    $row = mysqli_fetch_array($consulto_datos);
    $direccion_geo = $direccion_com . ' ' . $numero_com . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo = str_replace(' ', '+', $direccion_geo);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address=";
    $call = $url . urlencode($direccion_geo);
    $response = json_decode(file_get_contents($call), true);
    $latitud = $response['results'][0]['geometry']['location']['lat'];
    $longitud = $response['results'][0]['geometry']['location']['lng'];

    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }

    if ($latitud == '') {
      $latitud = '0';
    }
    if ($longitud == '') {
      $longitud = '0';
    }
    // fin geoencode
    $sql_update_client = "UPDATE clientes SET tipodni_clientes='$tipodni',	dni_clientes='$dni',	fechacumple_clientes='$cumple',	email_clientes='$email',	celular_clientes='$celular',	telefono_clientes='$telfijo',	provincia_clientes='$provincia',	ciudad_clientes='$ciudad',	direccion_clientes='$direccion_com',	dirnum_clientes='$numero_com',
          piso_clientes='$piso_com', depto_clientes='$depto_com',	estadocivil_clientes='$ecivil',	foto_clientes='$upload',	sexo_clientes='$sexo',	notas_clientes='$notas',	estado_clientes='1',	lat_clientes='$latitud',	lng_clientes='$longitud',	 razon_com_clientes='$razon', cuitcuil_com_clientes='$cuitcuil', condicioniva_com_clientes='$condicioniva', rubro_com_clientes='$rubro', direccion_com_clientes='$direccion_com',
           quien_clientes='$quien',	cuando_clientes='$cuando', asignado_clientes='$asignado', financiacion_com_clientes='$financia', topefinancia_com_clientes='$limite'  where id_clientes = '$id' ";

    $inserta = $link->query($sql_update_client);

    if ($inserta > 0) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
      $accion = "Error al Actualizar cliente  (" . $sql_update_client . ")";
      mail('alerozasdennis@gmail.com', 'Error en Actualizador de cliente', $accion, $headers);
    }

    // echo $sql_update_client;
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'edita_comercio') {
    $comercio = $_POST['id'];
    $cliente = $_POST['cliente'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['cuit'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $tel = $_POST['telefono'];
    $filial = $_POST['ciudad'];
    $dir = ucwords(strtolower(addslashes(htmlentities($_POST['direccion']))));
    $numdir = $_POST['dirnum'];
    $vendedor = $_POST['vendedor'];
    $perifact = $_POST['perifact'];

    $situacion = $_POST['situacion'];
    if ($situacion == '') {
      $situacion = 'activo';
    }
    $foto = $_FILES['files']['name'];


    $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$filial'");
    $row = mysqli_fetch_array($consulto_datos);

    $direccion_geo = $dir . ' ' . $numdir . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo = str_replace(' ', '+', $direccion_geo);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address="; //AIzaSyDNFykTVx2b0z3Fu9T9AFidLY-dRmFXKAU
    $call = $url . urlencode($direccion_geo);
    $response = json_decode(file_get_contents($call), true);
    $latitud = $response['results'][0]['geometry']['location']['lat'];
    $longitud = $response['results'][0]['geometry']['location']['lng'];


    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }
    if ($latitud_com != '' || $latitud_com != '0') {
      $latitud_com = str_replace(",", '.', $latitud_com);
      $longitud_com = str_replace(",", '.', $longitud_com);
    }

    if ($latitud_com == '') {
      $latitud_com = '0';
    }
    if ($longitud_com == '') {
      $longitud_com = '0';
    }
    if ($latitud == '') {
      $latitud = '0';
    }
    if ($longitud == '') {
      $longitud = '0';
    }

    $inserta = $link->query("UPDATE clientes_comercios SET cliente_comclientes='$cliente', perifact_comclientes='$perifact', razon_comclientes='$razon', foto_comclientes='frente_sinfoto.jpg', cuitcuil_comclientes='$cuitcuil', condicioniva_comclientes='$condicioniva', rubro_comclientes='$rubro', ciudad_comclientes='$filial', direccion_comclientes='$dir', dirnum_comclientes='$numdir', telefono_comclientes='$tel', cuando_comclientes='$cuando', lat_comclientes='$latitud', lon_comclientes='$longitud', quien_comclientes='$quien', estado_comclientes='1', vendedor_comclientes='$vendedor' where id_comclientes ='$comercio'");

    if ($inserta) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_clientes') {
    $id = $_POST['id'];

    $update2 = $link->query("UPDATE clientes SET estado_clientes='0' where id_clientes ='$id' ");
    if ($update2) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_proveedor') {
    $id = $_POST['id'];

    $update2 = $link->query("UPDATE proveedores SET estado_proveedor='0' where id_proveedor ='$id' ");
    if ($update2) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_carga') {
    $id = $_POST['id'];
    $update = $link->query("UPDATE stock_depositos SET estado_stockd='0' where `estado_stockd` = 1 AND `idcarga_stockd` ='$id' ");
    if ($update > 0) {
      echo 'TRUE';
      $link->query("UPDATE `carga_camion` SET `estado_cargac` = '0' WHERE `carga_camion`.`id_cargac` = '$id' ");
    } else {
      echo 'FALSE';
    }
  }

  if (isset($_POST['accion']) && $_POST['accion'] == 'elimina_transaccion') {
    $id = $_POST['id'];
    $update1 = $link->query("UPDATE transaccion SET estado='0', quien='$quien' where id ='$id' ");

    if ($update1) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  // ------------------------------Proveedores ------------------------------//

  //************ Proceso Edita Proveedor **************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'editar_proveedor') {
    $id = $_POST['id'];
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['dni'];
    $sexoh = $_POST['sexoh'];
    $sexom = $_POST['sexom'];
    $ecivil = $_POST['ecivil'];
    $cumple_d = $_POST['cumple_d'];
    $cumple_m = $_POST['cumple_m'];
    $cumple_a = $_POST['cumple_a'];
    $cumple = $cumple_a . '-' . $cumple_m . '-' . $cumple_d;
    $email = $_POST['email'];
    $email2 = $_POST['email2'];
    $telfijo = $_POST['telfijo'];
    $celular = $_POST['celular'];
    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $numero = $_POST['numero'];
    $piso = $_POST['piso'];
    $depto = $_POST['depto'];
    $upload = $_POST['upload'];
    $notas = $_POST['notas'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['cuit'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $telfijo_com = $_POST['telfijo_com'];
    $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];
    $upload = $_POST['upload'];
    // inicio geoencode
    $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$ciudad'");
    $row = mysqli_fetch_array($consulto_datos);
    $direccion_geo = $direccion . ' ' . $numero . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo = str_replace(' ', '+', $direccion_geo);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address=";
    $call = $url . urlencode($direccion_geo);
    $response = json_decode(file_get_contents($call), true);
    $latitud = $response['results'][0]['geometry']['location']['lat'];
    $longitud = $response['results'][0]['geometry']['location']['lng'];

    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }
    // fin geoencode

    $inserta = $link->query("UPDATE proveedores SET apellido_proveedor='$apellido',	nombre_proveedor='$nombre',	tipodni_proveedor='$tipodni',	dni_proveedor='$dni',	fechacumple_proveedor='$cumple',	email_proveedor='$email',	email2_proveedor='$email2',	celular_proveedor='$celular',	telefono_proveedor='$telfijo',	provincia_proveedor='$provincia',	ciudad_proveedor='$ciudad',	cp_proveedor='',	direccion_proveedor='$direccion',	dirnum_proveedor='$numero',	piso_proveedor='$piso', depto_proveedor='$depto',	estadocivil_proveedor='$ecivil', foto_proveedor='$upload',	sexo_proveedor='$sexo',	facebook_proveedor='',	notas_proveedor='',	estado_proveedor='1',	lat_proveedor='$latitud',	lng_proveedor='$longitud',	 razon_com_proveedor='$razon', cuitcuil_com_proveedor='$cuitcuil', condicioniva_com_proveedor='$condicioniva', rubro_com_proveedor='$rubro', direccion_com_proveedor='$direccion_com', dirnum_com_proveedor='$numero_com', telefono_com_proveedor='$telfijo_com', celular_com_proveedor='$celular_com', lat_com_proveedor='$latitud_com', lon_com_proveedor='$longitud_com', quien_proveedor='$quien',	cuando_proveedor='$cuando' WHERE id_proveedor = '$id' ");


    if ($inserta) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }

  //************ Proceso Carga Proveedor **************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'add_proveedores') {
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $tipodni = $_POST['tipodni'];
    $dni = $_POST['dni'];
    $sexoh = $_POST['sexoh'];
    $sexom = $_POST['sexom'];
    $ecivil = $_POST['ecivil'];
    $cumple = $_POST['cumple'];
    $email = $_POST['email'];
    $email2 = $_POST['email2'];
    $telfijo = $_POST['telfijo'];
    $celular = $_POST['celular'];
    $provincia = $_POST['provincia'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $numero = $_POST['numero'];
    $piso = $_POST['piso'];
    $depto = $_POST['depto'];
    $razon = addslashes(htmlentities($_POST['razon']));
    $cuitcuil = $_POST['cuit'];
    $condicioniva = $_POST['condicioniva'];
    $rubro = $_POST['rubro'];
    $telfijo_com = $_POST['telfijo_com'];
    $celular_com = $_POST['celular_com'];
    $direccion_com = $_POST['direccion_com'];
    $numero_com = $_POST['numero_com'];
    $piso_com = $_POST['piso_com'];
    $depto_com = $_POST['depto_com'];
    $upload = $_POST['upload'];

    // inicio geoencode
    $consulto_datos = $link->query("SELECT * from ciudad INNER JOIN provincia on provincia.id_provincia = ciudad.provincia_id where ciudad.id_ciudad ='$ciudad'");
    $row = mysqli_fetch_array($consulto_datos);
    $direccion_geo = $direccion . ' ' . $numero . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo = str_replace(' ', '+', $direccion_geo);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=" . $api_key . "&sensor=false&address=";
    $call = $url . urlencode($direccion_geo);
    $response = json_decode(file_get_contents($call), true);
    $latitud = $response['results'][0]['geometry']['location']['lat'];
    $longitud = $response['results'][0]['geometry']['location']['lng'];

    // dir comercios

    $direccion_geo_com = $direccion_com . ' ' . $numero_com . ',' . $row['ciudad_nombre'] . ',' . $row['provincia_nombre'] . ',Argentina';
    $direccion_geo_com = str_replace(' ', '+', $direccion_geo_com);
    $call_com = $url . urlencode($direccion_geo_com);
    $response_com = json_decode(file_get_contents($call_com), true);
    $latitud_com = $response_com['results'][0]['geometry']['location']['lat'];
    $longitud_com = $response_com['results'][0]['geometry']['location']['lng'];

    if ($latitud != '' || $latitud != '0') {
      $latitud = str_replace(",", '.', $latitud);
      $longitud = str_replace(",", '.', $longitud);
    }
    if ($latitud_com != '' || $latitud_com != '0') {
      $latitud_com = str_replace(",", '.', $latitud_com);
      $longitud_com = str_replace(",", '.', $longitud_com);
    }
    // fin geoencode

    $inserta = $link->query("INSERT INTO proveedores SET email_proveedor='$email',	provincia_proveedor='$provincia',	ciudad_proveedor='$ciudad', notas_proveedor='',	estado_proveedor='1', razon_com_proveedor='$razon', cuitcuil_com_proveedor='$cuitcuil', condicioniva_com_proveedor='$condicioniva', rubro_com_proveedor='$rubro', direccion_com_proveedor='$direccion_com', dirnum_com_proveedor='$numero_com', telefono_com_proveedor='$telfijo_com', quien_proveedor='$quien', cuando_proveedor='$cuando'");
    $id_clie_ulti = mysqli_insert_id($link);

    if ($inserta) {
      echo $id_clie_ulti . '@' . $apellido . ', ' . $nombre . '@' . $provincia . '@' . $ciudad . '@' . $direccion . '@' . $numero;
    } else {
      echo 'FALSE';
    }
  }

  //************ Cambiar estado cliente **************//
  if (isset($_POST['accion']) && $_POST['accion'] == 'desactiva_cliente') {
    $id = $_POST['id'];

    $update = $link->query("UPDATE clientes SET estado_clientes='2' where id_clientes ='$id' ");
    if ($update) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }
  if (isset($_POST['accion']) && $_POST['accion'] == 'activa_cliente') {
    $id = $_POST['id'];

    $update = $link->query("UPDATE clientes SET estado_clientes='1' where id_clientes ='$id' ");
    if ($update) {
      echo 'TRUE';
    } else {
      echo 'FALSE';
    }
  }
}
