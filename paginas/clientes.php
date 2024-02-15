<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Clientes</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Clientes</a></li>
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
            <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <td colspan="6">
                    <a href="index.php?pagina=clientes_add" class="btn btn-info btn-rounded">Cargar nuevo Cliente</a>
                  </td>
                </tr>
                <tr>
                  <!--  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th> -->
                  <th>Cliente</th>
                  <th>Telefono</th>
                  <th>Direccion </th>
                  <th>E-mail</th>
                  <th>Estado </th>
                  <th>Acciones&nbsp;&nbsp;&nbsp;</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $busqueda = '';
                if (isset($_GET['buscar'])) {
                  $palabra = $_GET['buscar'];
                  $busqueda = "and (apellido_clientes like '%$palabra%' or nombre_clientes like '%$palabra%' or cuitcuil_com_clientes like '%$palabra%' or dni_clientes like '%$palabra%' or razon_com_clientes like '%$palabra%' or direccion_clientes like '%$palabra%' )";
                }
                //$con_clientes = $link->query("SELECT * FROM clientes inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes INNER join ciudad on ciudad.id_ciudad = clientes.ciudad_clientes where clientes_comercios.estado_comclientes ='1' order by clientes.apellido_clientes, clientes.nombre_clientes ASC ");
                $con_clientes = $link->query("SELECT * FROM clientes left join ciudad on ciudad.id_ciudad = clientes.ciudad_clientes where estado_clientes !='0' $busqueda order by apellido_clientes ASC ");
                
                while ($row = mysqli_fetch_array($con_clientes)) {
                ?>
                  <tr>
                    <!--  <td><span class="footable-toggle"></span><?php echo $row['id_comclientes'] ?></td> -->
                    <td class="font-weight-normal">
                      <a href="index.php?pagina=clientes_view&id=<?php echo $row['id_clientes'] ?>">

                        <?php
                        echo mb_strtoupper(utf8_encode($row['razon_com_clientes']));
                        ?>
                      </a>
                    </td>

                    <td>
                      <a href="tel:<?php echo $row['celular_clientes']; ?>">
                        <?php echo $row['celular_clientes']; ?>
                      </a>
                    </td>
                    <td class="font-weight-normal"><?php echo utf8_encode($row['direccion_clientes']) . ', ' . $row['dirnum_clientes'] . ' ( ' . $row['ciudad_alias'] . ' )' ?></td>
                    <td>
                      <a href="mailto:<?php echo $row['email_clientes'] ?>?subject=Big%20Pollo&body=Hola,<?php echo $row['razon_com_clientes'] ?>"><?php echo $row['email_clientes'] ?>
                      </a>
                    </td>
                    <td class="font-weight-normal"><span class="label label-<?php if ($row['estado_clientes'] == '2') {
                                                                              echo 'danger">Inactivo';
                                                                            } else {
                                                                              echo 'success">Activo';
                                                                            } ?></span></td>
                                                <td >
                                                  <a class=" btn-pure btn-outline-success view-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=clientes_view&id=<?php echo $row['id_clientes'] ?>" data-toggle="tooltip" data-original-title="Ver"><i class="ti-eye" aria-hidden="true"></i></a>
                        <?php if ($_SESSION['tipo'] != 'User' && $row['id_clientes'] != '1') { ?> &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=clientes_edit&id=<?php echo $row['id_clientes'] ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                          &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#del_<?php echo $row['id_clientes'] ?>" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                        <?php } ?> </td>
                      <td class="font-weight-normal">
                        <?php if($row['estado_clientes'] =='1'){ ?>
                          <button class="switch on" onclick="desactivarCliente(<?=$row['id_clientes']?>)"></button>
                        <?php }else{ ?>
                          <button class="switch" onclick="activarCliente(<?=$row['id_clientes']?>)"></button>
                        <?php } ?>
                      </td>
                  </tr>
                <?php echo '
                                            <div class="modal fade" id="del_' . $row['id_clientes'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <h4 >Seguro que desea eliminar el cliente "' . $row['razon_com_clientes'] . '" ?</h4>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button onclick="elimina_c(' . $row['id_clientes'] . ')" class="btn btn-primary">Si, Eliminar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                } ?>
              </tbody>
              <tfoot>
                <tr>


                  <td colspan="7">
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
                          mysqli_data_seek($con_clientes, 0);
                          while ($com_sul = mysqli_fetch_array($con_clientes)) {
                            echo '"' . $com_sul['razon_comclientes'] . '",';
                          } ?>]
    $("#buscador").autocomplete({
      source: availableTags
    });
  });
</script>