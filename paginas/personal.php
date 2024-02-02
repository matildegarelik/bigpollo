<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado del Personal</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=clientes">Personal</a></li>
        <?php if ($_GET['buscar']) {
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
          <?php if ($_GET['buscar']) {
            echo '<h4 class="card-title">Resulados de [' . $_GET['buscar'] . ']...</h4>';
          } else {
            echo '<h4 class="card-title">Listado</h4>';
          } ?>
          <a href="index.php?pagina=personal_add" class="btn btn-info btn-rounded">Cargar nuevo Personal</a>

          <h6 class="card-subtitle"></h6>
          <div class="table-responsive">
            <table id="personal_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <!--  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th> -->
                  <th>Avatar</th>
                  <th>Nombre</th>
                  <th>E-mail</th>
                  <th>Telefono</th>
                  <th>Direccion </th>
                  <th>Area </th>
                  <th>Estado </th>
                  <th>Acciones&nbsp;&nbsp;&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $busqueda = '';
                if ($_GET['buscar']) {
                  $palabra = $_GET['buscar'];
                  $busqueda = "and (apellido like '%$palabra%' or nombre like '%$palabra%' or dni like '%$palabra%' )";
                }
                //$con_clientes = $link->query("SELECT * FROM clientes inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes INNER join ciudad on ciudad.id_ciudad = clientes.ciudad_clientes where clientes_comercios.estado_comclientes ='1' order by clientes.apellido_clientes, clientes.nombre_clientes ASC ");
                $con_personal = $link->query("SELECT * FROM personal left join ciudad on ciudad.id_ciudad = personal.ciudad where estado !='0' $busqueda order by apellido ASC ");
                while ($row = mysqli_fetch_array($con_personal)) {
                ?>
                  <tr>
                    <td class="font-weight-normal">
                      <a href="index.php?pagina=personal_view&id=<?php echo $row['id'] ?>">
                        <img src="img/avatar/<?php echo $row['foto']; ?>" class="img-circle" width="50px">
                      </a>
                    </td>

                    <td class="font-weight-normal">
                      <a href="index.php?pagina=personal_view&id=<?php echo $row['id'] ?>">
                        <?php echo $row['apellido'] . ', ' . $row['nombre'] ?>
                      </a>
                    </td>

                    <td>
                      <a href="mailto:<?php echo $row['email'] ?>?subject=Big%20Pollo&body=Hola,<?php echo $row['nombre'] ?>"><?php echo $row['email'] ?>
                      </a>
                    </td>
                    <td>
                      <a href="tel:<?php if ($row['telefono'] != '') {
                                      echo $row['telefono'];
                                    } else {
                                      echo $row['celular'];
                                    } ?>">
                        <?php if ($row['celular'] != '') {
                          echo $row['celular'];
                        } else {
                          echo $row['telefono'];
                        } ?>
                      </a>
                    </td>
                    <td class="font-weight-normal"><?php echo $row['direccion'] . ', ' . $row['direccion_num'] . ' ( ' . $row['ciudad_alias'] . ' )' ?></td>
                    <td class="font-weight-normal"><?php echo $row['area'] ?></td>
                    <td class="font-weight-normal"><span class="label label-<?php if ($row['estado'] == '2') {
                                                                              echo 'danger">Inactivo';
                                                                            } else {
                                                                              echo 'success">Activo';
                                                                            } ?></span></td>
                                                <td >
                                                  <a class=" btn-pure btn-outline-success view-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=personal_view&id=<?php echo $row['id'] ?>" data-toggle="tooltip" data-original-title="Ver"><i class="ti-eye" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=personal_edit&id=<?php echo $row['id'] ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#del_<?php echo $row['id'] ?>" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                <?php echo '
                                            <div class="modal fade" id="del_' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <h4 >Seguro que desea eliminar el Personal "' . $row['nombre'] . ' ' . $row['apellido'] . '" ?</h4>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button onclick="elimina_p(' . $row['id'] . ')" class="btn btn-primary">Si, Eliminar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                } ?>
              </tbody>
              <tfoot>
                <tr>


                  <td colspan="6">
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
                          mysqli_data_seek($con_personal, 0);
                          while ($com_sul = mysqli_fetch_array($con_personal)) {
                            echo '"' . $com_sul['nombre_personal'] . '",';
                          } ?>]
    $("#buscador").autocomplete({
      source: availableTags
    });
  });
</script>