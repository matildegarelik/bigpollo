<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Proveedores</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active">Proveedores</li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Ingrese los datos del Proveedor</h4>
        <form class="form-horizontal form-material">

          <div class="row">
            <div class="col-md-6 m-b-20">
              <h3>Datos Personales</h3>
              <div class="row">
                <div class="col-md-6 m-b-20">
                  <input placeholder="Apellido" type="text" name="apellido" class="form-control apellido" required="">
                </div>
                <div class="col-md-6 m-b-20">
                  <input placeholder="Nombre" type="text" name="nombre" class="form-control nombre" required="">
                </div>

                <div class="col-md-3 m-b-20">
                  <select name="tipodni" id="tipodni" class="form-control tipodni" required="">
                    <option value="" disabled="" selected="">Tipo Documento</option>
                    <option value="DNI">DNI</option>
                    <option value="LE">LE</option>
                    <option value="LC">LC</option>
                    <option value="CI-PFA">CI-PFA</option>
                    <option value="PASAPORTE">PASAPORTE</option>
                    <option value="DNI-Tarjeta">DNI-Tarjeta</option>
                  </select>
                </div>
                <div class="col-md-3 m-b-20">
                  <input type="number" maxlength="8" placeholder="DNI" onchange="activa_afip()" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control dni" name="dni" required="">
                </div>

                <div class="col-md-6 m-b-20">
                  <label class="radio-inline m-r-20">
                    <input style="margin-left:0px;" class="sexoh" type="radio" value="H" name="sexo" required="">&nbsp; Hombre
                  </label>
                  <label class="radio-inline">
                    <input class="sexom" style="margin-left:0px;" type="radio" value="M" name="sexo">&nbsp; Mujer
                  </label>
                </div>

                <div class="col-md-6 m-b-20">
                  <input type="email" placeholder="E-mail" class="form-control email" name="email" id="email">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="email" placeholder="E-mail secundario" class="form-control email2" name="email2">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="phone" placeholder="Telefono Fijo" class="form-control telfijo" name="telfijo">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="phone" placeholder="Celular" class="form-control celular" name="celular" required="">
                </div>
                <div class="col-md-6 m-b-20">
                  <select class="form-control provincia" onchange="buscaciudad()" name="provincia" id="provincia" required="">
                    <option value="" disabled="" selected="">Seleccione una Provincia</option>
                    <?php $consul_provincia = $link->query("SELECT * FROM provincia order by provincia_nombre asc") or die(mysqli_error());
                    while ($provincia = mysqli_fetch_array($consul_provincia)) {
                      echo '
                                <option value="' . $provincia['id_provincia'] . '">' . $provincia['provincia_nombre'] . '</option>';
                    } ?>
                  </select>
                </div>
                <div class="col-md-6 m-b-20">
                  <select id="ciudad" name="ciudad" class="form-control ciudad" required="" disabled="disabled">
                    <?php $consul_ciudades2 = $link->query("SELECT * FROM ciudad order by ciudad_nombre asc") or die(mysqli_error());
                    echo '<option value="" disabled="" selected="">Seleccione una Localidad</option>';
                    while ($ciudad = mysqli_fetch_array($consul_ciudades2)) {
                      echo '<option value="' . $ciudad['id_ciudad'] . '">' . $ciudad['ciudad_nombre'] . '</option>';
                    } ?>
                  </select>
                </div>
                <div class="col-md-8 m-b-20">
                  <input type="text" placeholder="Dirección" class="form-control direccion" name="direccion" value="" required="">
                </div>
                <div class="col-md-4 m-b-20">
                  <input type="number" placeholder="Numero" class="form-control numero" name="numero" value="" required="">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="text" placeholder="Piso" class="form-control piso" name="piso" value="">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="text" placeholder="Depto" class="form-control depto" name="depto" value="">
                </div>
              </div>
            </div>

            <div class="col-md-6 m-b-20">
              <div class="row">
                <div class="col-md-6 m-b-20">
                  <h3>Datos Comerciales </h3>
                </div>
                <div class="col-md-6 m-b-20 afip">
                  <a href="javascriot:void(0)" class="btn-success btn btn-afip " onclick="datos_afip()" disabled><i class="fa fa-refresh"></i> AFIP</a>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 m-b-20">
                  <input placeholder="Razon Social" type="text" name="razon" class="form-control razon" required="">
                </div>
                <div class="col-md-6 m-b-20">
                  <select class="form-control rubro" name="rubro">
                    <option value="" disabled selected>Seleccione un rubro comercial</option>
                    <?php
                    $con_rubro = $link->query("SELECT * FROM rubros where estado_rubros ='1' order by nombre_rubros asc");
                    while ($rubro  = mysqli_fetch_array($con_rubro)) {
                      echo '
                                <option value="' . $rubro['id_rubros'] . '">' . $rubro['nombre_rubros'] . '</option>';
                    }
                    ?>
                    <option value='2545'>Otros</option>
                  </select>
                </div>

                <div class="col-md-6 m-b-20">
                  <select class="form-control condicioniva" name="condicioniva" required>
                    <option value="" disabled selected>Seleccione condicion IVA</option>
                    <option value="cf">Consumidor Final</option>
                    <option value="ri">Responsable Inscripto</option>
                    <option value="nor">No Responsable</option>
                    <option value="m">Responsable Monotributista</option>
                    <option value="e">Excento</option>
                  </select>
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="number" placeholder="CUIT/CUIL (sin guion)" maxlength="11" class="form-control cuit" name="cuit" />
                </div>

                <div class="col-md-6 m-b-20">
                  <input type="phone" placeholder="Telefono Fijo" class="form-control telfijo_com" name="telfijo_com">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="phone" placeholder="Celular" class="form-control celular_com" name="celular_com" required="">
                </div>
                <div class="col-md-8 m-b-20">
                  <input type="text" placeholder="Dirección" class="form-control direccion_com" name="direccion_com" value="" required="">
                </div>
                <div class="col-md-4 m-b-20">
                  <input type="number" placeholder="Numero" class="form-control numero_com" name="numero_com" value="" required="">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="text" placeholder="Piso" class="form-control piso_com" name="piso_com" value="">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="text" placeholder="Depto" class="form-control depto_com" name="depto_com" value="">
                </div>
                <div class="col-md-12 m-b-20">
                  <textarea class="form-control notas" name="notas" placeholder="Ingrese una nota"></textarea>
                </div>

              </div>
            </div>
            <div class="col-md-12 m-b-20">
              <h3>Datos Adicinales</h3>
              <div class="row">
                <div class="col-md-6 m-b-20">
                  <label for="ecivil">Estado Civil</label>
                  <select class="form-control ecivil" id="ecivil" name="ecivil">
                    <option value="" disabled="" selected="">Seleccione uno</option>
                    <option value="Soltero/a">Soltero/a</option>
                    <option value="Casado/a">Casado/a</option>
                    <option value="Concubinato">Concubinato</option>
                    <option value="Divorciado/a">Divorciado/a</option>
                    <option value="Viudo/a">Viudo/a</option>
                  </select>
                </div>
                <div class="col-md-6 m-b-20">
                  <label for="cumple">Fecha de Cumpleaños</label>
                  <input type="date" placeholder="Cumpleaños" class="form-control cumple" id="cumple" name="cumple" required="">

                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12 m-b-20">
                  <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
                    <span><i class="ion-upload m-r-5"></i>Cargar Imagen</span>
                    <input type="file" class="upload">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12 m-b-20" style="text-align:right">
                  <a href="javascript:void(0);" id="agregar_proveedor" class="btn btn-success waves-effect">Guardar</a>
                  <a href="index.php?pagina=proveedores" class="btn btn-danger waves-effect">Cancelar</a>
                </div>
              </div>
            </div>


        </form>
      </div>
    </div>
  </div>

</div>




<div id="add_comercio" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="display: inline;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Ingrese los datos del comercio</h4>
      </div>
      <form class="form-horizontal form-material">
        <div class="col-md-12">
          <div class="form-body pal">
            <div>

              <div class="col-md-12 m-b-20">
                <input placeholder="Nombre del Comercio" type="text" value="<?php echo $row['razon_comclientes']; ?>" name="razon" class="form-control" required />
              </div>
              <div class="col-md-12 m-b-20">
                <select class="form-control" name="condicioniva" required>
                  <option value="" disabled selected>Seleccione condicion IVA</option>
                  <option value="cf">Consumidor Final</option>
                  <option value="ri">Responsable Inscripto</option>
                  <option value="nor">No Responsable</option>
                  <option value="m">Responsable Monotributista</option>
                  <option value="e">Excento</option>
                </select>
              </div>
              <div class="col-md-12 m-b-20">
                <input type="number" placeholder="CUIT/CUIL (sin guion)" maxlength="11" class="form-control" name="cuit" />
              </div>
            </div>
            <div>
              <div class="col-md-12 m-b-20">
                <input type="phone" placeholder="Telefono comercial" class="form-control" name="telefono" />
              </div>
              <div class="col-md-12 m-b-20">
                <select class="form-control" name="rubro">
                  <option value="" disabled selected>Seleccione un rubro comercial</option>
                  <?php
                  $con_rubro = $link->query("SELECT * FROM rubros where estado_rubros ='1' order by nombre_rubros asc");
                  while ($rubro  = mysqli_fetch_array($con_rubro)) {
                    echo '
                    <option value="' . $rubro['id_rubros'] . '">' . $rubro['nombre_rubros'] . '</option>';
                  }
                  ?>
                  <option value='2545'>Otros</option>
                </select>
              </div>
            </div>
            <div>
              <h4>Direccion Comercial</h4>
              <div class="col-md-6 m-b-20">
                <select class="form-control provincia" onchange="buscaciudad()" name="provincia" id="provincia" required="">
                  <option value="" disabled="" selected="">Seleccione una Provincia</option>
                  <?php $consul_provincia1 = $link->query("SELECT * FROM provincia order by provincia_nombre asc") or die(mysql_error());
                  while ($provincia = mysqli_fetch_array($consul_provincia1)) {
                    echo '
                    <option value="' . $provincia['id_provincia'] . '">' . $provincia['provincia_nombre'] . '</option>';
                  } ?>
                </select>
              </div>
              <div class="col-md-6 m-b-20">
                <select id="ciudadcom" name="ciudad" class="form-control ciudad" required="" disabled="disabled">
                  <?php $consul_ciudades3 = $link->query("SELECT * FROM ciudad order by ciudad_nombre asc") or die(mysql_error());
                  echo '<option value="" disabled="" selected="">Seleccione una Localidad</option>';
                  while ($ciudad = mysqli_fetch_array($consul_ciudades3)) {
                    echo '<option value="' . $ciudad['id_ciudad'] . '">' . $ciudad['ciudad_nombre'] . '</option>';
                  } ?>
                </select>
              </div>
              <div class="col-md-12 m-b-20">
                <input type="text" placeholder="Calle Nombre" class="form-control" name="direccion" value="<?php echo  $row['direccion_comclientes'] ?>" required />
              </div>
              <div class="col-md-12 m-b-20">
                <input type="number" placeholder="Numero" class="form-control" name="dirnum" value="<?php echo  $row['dirnum_comclientes'] ?>" required />
              </div>
            </div>

            <div>
              <h4>Asignar a</h4>
              <div class="col-md-12 m-b-20">
                <select class="form-control" name="vendedor">
                  <option value="" disabled selected>Seleccione un Personal</option>
                  <?php $con_vendedor = $link->query("SELECT * FROM usuarios WHERE usuarios.estado_usuarios = '1' and id !='0' order by nombre ASC");
                  while ($vendedor  = mysqli_fetch_array($con_vendedor)) {
                    echo '
                    <option value="' . $vendedor['id'] . '">' . $vendedor['nombre'] . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <hr />

      </form>
      <div class="modal-footer">
        <button type="button" id="agregar_comercio" class="btn btn-info waves-effect">Guardar</button>
        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancelar</button>
      </div>


    </div>
    <!-- /.modal-dialog -->
  </div>
</div>
</div>