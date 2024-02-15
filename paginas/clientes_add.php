<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Clientes</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active">Clientes</li>
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
        <h4 class="card-title">Ingrese los datos del cliente</h4>
        <form class="form-horizontal form-material">

          <div class="row">
            <div class="col-md-6 m-b-20">
              <h3>Datos Comerciales </h3>
            </div>
            <div class="col-md-6 m-b-20 afip">
              <a href="javascriot:void(0)" class="btn-success btn btn-afip " onclick="datos_afip()" disabled><i class="fa fa-refresh"></i> AFIP</a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 m-b-20">
              <input placeholder="Nombre y Apellido / Razon Social" type="text" name="razon" class="form-control razon" required="">
            </div>

            <div class="col-md-4 m-b-20">
                <select name="tipodni" id="tipodni" class="form-control tipodni" required="">
                  <option value="" disabled selected>Seleccione Tipo documento</option>
                  <option value="DNI" <?php if (isset($row['tipodni_clientes']) && $row['tipodni_clientes'] == 'DNI') {
                                        echo ' selected';
                                      } ?>>DNI</option>
                  <option value="CUIT" <?php if ( isset($row['tipodni_clientes']) && $row['tipodni_clientes'] == 'CUIT') {
                                          echo ' selected';
                                        } ?>>CUIT</option>
                  <option value="CUIL" <?php if (isset($row['tipodni_clientes']) && $row['tipodni_clientes'] == 'CUIL') {
                                          echo ' selected';
                                        } ?>>CUIL</option>
                </select>
            </div>
            <div class="col-md-4 m-b-20">
              <input type="number" placeholder="Numero documento (sin guion)" maxlength="11" class="form-control cuit" name="cuit" />
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

            <div class="col-md-4 m-b-20">
              <input type="phone" placeholder="Telefono Fijo" class="form-control telfijo_com" name="telfijo_com">
            </div>
            <div class="col-md-4 m-b-20">
              <input type="phone" placeholder="Celular" class="form-control celular_com" name="celular_com" required="">
            </div>
            <div class="col-md-4 m-b-20">
              <input type="email" placeholder="E-mail" class="form-control email" name="email" id="email">
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
              <textarea class="form-control notas" name="notas" placeholder="Ingrese una nota" rows="5"></textarea>
            </div>


            <div class="col-md-12 m-b-20">
              <h3>Datos Adicinales</h3>
              <div class="row">

                <!--<div class="col-md-3 m-b-20">
                  <label class="radio-inline m-r-20">
                    <input style="margin-left:0px;" class="sexoh" type="radio" value="H" name="sexo" required="">&nbsp; Hombre
                  </label>
                  <label class="radio-inline">
                    <input class="sexom" style="margin-left:0px;" type="radio" value="M" name="sexo">&nbsp; Mujer
                  </label>
                </div>

                <div class="col-md-3 m-b-20">
                  <label for="ecivil">Estado Civil</label>
                  <select class="form-control ecivil" id="ecivil" name="ecivil">
                    <option value="" disabled="" selected="">Seleccione uno</option>
                    <option value="Soltero/a">Soltero/a</option>
                    <option value="Casado/a">Casado/a</option>
                    <option value="Concubinato">Concubinato</option>
                    <option value="Divorciado/a">Divorciado/a</option>
                    <option value="Viudo/a">Viudo/a</option>
                  </select>
                </div>-->
                <div class="col-md-3 m-b-20">
                  <label for="cumple">Fecha de Cumpleaños</label>
                  <input type="date" placeholder="Cumpleaños" class="form-control cumple" id="cumple" name="cumple" required="">

                </div>
                <div class="col-md-3 m-b-20">
                  <label for="ecivil">Zona / Personal</label>
                  <select class="form-control asignado" id="asignado" name="asignado">
                    <option value="" disabled="" selected="">Seleccione un personal</option>
                    <?php
                    $con_zona = $link->query("SELECT * FROM personal where estado ='1' and area='Reparto' order by nombre asc, apellido asc");
                    while ($zona  = mysqli_fetch_array($con_zona)) {
                      echo '<option value="' . $zona['id'] . '">' . $zona['nombre'] . ', ' . $zona['apellido'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-3 m-b-20">
                  <label for="ecivil">Lista de precios</label>
                  <select class="form-control listap" id="listap" name="listap">
                    <option value="" disabled="" selected="">Seleccione uno</option>
                    <option value="1">Lista 1</option>
                    <option value="2">Lista 2</option>
                    <option value="3">Lista 3</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 m-b-20">
                  <label for="financia">Habilitado Financiacion</label>
                  <select class="form-control financia" id="financia" name="financia">
                    <option value="" disabled="" selected="">Seleccione</option>
                    <option value="1">SI</option>
                    <option value="0">NO</option>
                  </select>
                </div>
                <div class="col-md-6 m-b-20">
                  <label for="ecivil">Tope de Financiacion</label>
                  <input type="number" step="any" placeholder="Indique el limite" class="form-control limite" id="limitefinancia" name="limitefinancia" required="">
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
                  <a href="javascript:void(0);" id="agregar_cliente" class="btn btn-success waves-effect">Guardar</a>
                  <a href="index.php?pagina=clientes" class="btn btn-danger waves-effect">Cancelar</a>
                </div>
              </div>
            </div>


        </form>
      </div>
    </div>
  </div>


</div>