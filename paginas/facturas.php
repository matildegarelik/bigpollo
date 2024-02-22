<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Facturas</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Facturas</a></li>
        <?php if (isset($_GET['buscar'])) {
          echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . $_GET['buscar'] . ']</a></li>';
        } ?>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block" method="get">
        <input type="hidden" name="pagina" value="clientes">
        <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?php if (isset($_GET['buscar'])) {
            echo '<h4 class="card-title">Resulados de [' . $_GET['buscar'] . ']...</h4>';
          } else {
            echo '<h4 class="card-title">Listado</h4>';
          } ?>


          <h6 class="card-subtitle"></h6>
          <div class="table-responsive">
            <table id="facturas_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <td colspan="12">
                    <div class="row">
                        <div class="col-md-2"><small class="form-control-feedback"> Desde </small>
                            <input class="form-control filtro" type="date" id="d" name="d" value="<?php if (isset($_GET['d'])) {
                                                                                                    echo $_GET['d'];
                                                                                                } else {
                                                                                                    echo date('Y-m-01');
                                                                                                } ?>">
                        </div>
                        <div class="col-md-2"><small class="form-control-feedback"> Hasta </small>
                            <input class="form-control filtro" type="date" id="h" name="h" value="<?php if (isset($_GET['h'])) {
                                                                                                    echo $_GET['h'];
                                                                                                } else {
                                                                                                    echo date('Y-m-d');
                                                                                                } ?>">
                        </div>
                        <div class="col-md-2"><small class="form-control-feedback"> Proveedor </small><br>
                            <select class="form-control" id="proveedorsel">
                            <option value='' selected>Todos</option>
                            <?php
                            $busca_prov = $link->query("SELECT razon_com_proveedor as nombre, id_proveedor as id FROM `proveedores` WHERE `estado_proveedor` LIKE '1'");
                            while ($row = mysqli_fetch_array($busca_prov)) {

                                echo '<option value="' . $row['id'] . '"';
                                if (isset($_GET['p']) && $_GET['p'] == $row['id']) {
                                echo ' selected ';
                                }
                                echo '>' . $row['nombre'] . '</option>';
                            }
                            ?>
                            </select>

                        </div>
                        <div class="col-md-2"><small class="form-control-feedback"> Saldo pendiente? </small><br>
                            <select class="form-control" id="saldosel">
                                <option value='' selected>Todos</option>
                                <option value="1">Pagadas por completo</option>
                                <option value="2">Pendientes</option>
                            </select>
                        </div>
                        <div class="col-md-2" style="align-self: center;">
                            <a href="#" onclick="filtrar_prov()" class="btn btn-info btn-lg" role="button">Filtrar</a>
                        </div>
                        <div class="col-md-2" style="align-self: center;">
                            <?php if (isset($_GET['d']) || isset($_GET['h']) || isset($_GET['p']) || isset($_GET['s']) ) { ?><a href="index.php?pagina=facturas">Quitar Filtros</a><?php } ?>
                        </div>
                        </div>
                  </td>
                </tr>
                <tr>
                  <!--  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th> -->
                  <th>Nro Factura</th>
                  <th>Proveedor</th>
                  <th>Fecha</th>
                  <th>Tipo </th>
                  <th>Monto </th>
                  <th>Saldo Pendiente</th>
                  <th>Obs.</th>
                </tr>
              </thead>
              <tbody id="lista_facturas">
                <?php
                $busqueda = '';
                if (isset($_GET['buscar'])) {
                  $palabra = $_GET['buscar'];
                  $busqueda = "and (nro_factura like '%$palabra%' or tipo like '%$palabra%' )";
                }
                if (isset($_GET['d'])&& $_GET['d']!='') {
                    $desde = $_GET['d'];
                    $busqueda = $busqueda . ' and facturas.fecha >= "' . $desde .'"';
                  } else {
                    $desde = date('Y-m-01');
                  }
                  if (isset($_GET['h'])&& $_GET['h']!='') {
                    $hasta = date('Y-m-d', strtotime($_GET['h'] . ' +1 day'));;
                    $busqueda = $busqueda . ' and facturas.fecha <= "' . $hasta .'"';
                  } else {
                    $hasta = date('Y-m-d 23:59:59');
                  }
                  if (isset($_GET['p']) && $_GET['p']!='') {
                    $busqueda = $busqueda . ' and proveedores.id_proveedor = ' . $_GET['p'];
                  } else {
                    $vendedor = '';
                  }
                  
                $con_facturas = $link->query("SELECT facturas.*,proveedores.razon_com_proveedor as proveedor,tipo_comprobantes.nombre_comprobantes
                    FROM facturas left join proveedores on facturas.id_proveedor=proveedores.id_proveedor 
                    left join tipo_comprobantes on facturas.tipo = tipo_comprobantes.id_comprobantes
                    where facturas.id>0 $busqueda order by facturas.fecha ASC ");
                    
                while ($row = mysqli_fetch_assoc($con_facturas)) {
                    $saldo = $row['monto'];
                    $id_factura= $row['id'];
                    $consulta2 = $link->query("SELECT * from facturas_pagos where id_factura='$id_factura'");
                    $rows2 = array(); // Inicializa un array para almacenar todas las rows
                    while ($row2 = mysqli_fetch_assoc($consulta2)) {
                        $saldo =$saldo-$row2['monto'];
                    }
                    $row['saldo'] = $saldo;
                    $mostrar=true;
                    if (isset($_GET['s']) && $_GET['s']!='') {
                        $mostrar=false;
                        if($_GET['s']=="2" && $saldo>0){ $mostrar=true;}
                        else if($_GET['s']=="1" && $saldo<=0){$mostrar=true; }
                    }
                    if($mostrar){
                    
                ?>
                    <tr>

                        <td>
                        <a href="#" style="color:#262626;" class="font-weight-normal"><?php echo $row['nro_factura'] ?></a>
                        </td>
                        <td><a href="#"><?php echo $row['proveedor'] ?></a></td>
                        <td><a href="#"><?php echo $row['fecha'] ?></a></td>                                                                                          
                        <td class="font-weight-normal"><?php echo $row['nombre_comprobantes'] ?></td>
                        <td class="font-weight-normal">$<?php echo $row['monto'] ?></td>
                        <td class="font-weight-normal">$<?php echo $row['saldo'] ?></td>
                        <td class="font-weight-normal"><?php echo $row['observaciones'] ?></td>
                    </tr>
                <?php }} ?>
                
              </tbody>
              <tfoot>
                <tr>


                  <td colspan="9">
                    <div class="text-right">
                      <ul class="pagination">
                        <li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li>
                        <li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li>
                        <li class="footable-page active"><a data-page="0" href="#">1</a></li>
                        <li class="footable-page"><a data-page="1" href="#">2</a></li>
                        <li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li>
                        <li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    var availableTags = [<?php
                          mysqli_data_seek($con_facturas, 0);
                          while ($com_sul = mysqli_fetch_array($con_facturas)) {
                            echo '"' . $com_sul['nro_factura'] . '",';
                          } ?>]
    $("#buscador").autocomplete({
      source: availableTags
    });
  });
  function filtrar_prov() {
      var datodesde = $('#d').val(); // get selected value
      var datohasta = $('#h').val(); // get selected value
      var datoproveedor = $('#proveedorsel option:selected').val(); // get selected value
      var datosaldo = $('#saldosel').val()
      if (datodesde) { // require a URL
        window.location = 'index.php?pagina=facturas&d=' + datodesde + '&h=' + datohasta + '&p=' + datoproveedor+ '&s=' + datosaldo; // redirect
      }
      return false;
    };
</script>