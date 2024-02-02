
$('#avatar').html('<img src="./img/'+localStorage.usuario_avatar+'" alt="user" class=""> <span class="hidden-md-down">'+localStorage.usuario_nombre+' &nbsp;<i class="fa fa-angle-down"></i></span>');
var tomociudad = //creo variable para cargarla luego en la creacion del clkiente y plasmarla en el comercio.

// salida
$("#salir").click(function(){
localStorage.clear();
window.location.href = "login.html";
});


  $('#agregar_cliente').click(function(){
    var apellido = $('.apellido').val();
    var nombre = $('.nombre').val();
    var tipodni = $('.tipodni option:selected').val();
    var dni = $('.dni').val();
    var sexoh = $('.sexoh').val();
    var sexom = $('.sexom').val();
    var sexo ='M';
    var ecivil = $('.ecivil option:selected').val();
    var cumple_d = $('.cumple_d').val();
    var cumple_m = $('.cumple_m').val();
    var cumple_a = $('.cumple_a').val();
    var cumple = cumple_a+'-'+cumple_m+'-'+cumple_d;
    var email = $('.email').val();
    var email2 = $('.email2').val();
    var telfijo = $('.telfijo').val();
    var celular = $('.celular').val();
    var provincia = $('.provincia option:selected').val();
    var ciudad = $('.ciudad option:selected').val();
    var direccion = $('.direccion').val();
    var numero = $('.numero').val();
    var piso = $('.piso').val();
    var depto = $('.depto').val();
    var upload = $('.upload').val();
    var string2 = "accion=add_clientes&apellido="+apellido+"&nombre="+nombre+"&tipodni="+tipodni+"&dni="+dni+"&sexo="+sexo+"&ecivil="+ecivil+"&cumple="+cumple+"&email="+email+"&email2="+email2+"&telfijo="+telfijo+"&celular="+celular+"&provincia="+provincia+"&ciudad="+ciudad+"&direccion="+direccion+"&numero="+numero+"&piso="+piso+"&depto="+depto+"&upload="+upload;
    tomociudad = ciudad;
    // console.log('datos string2: '+string2);
            $.ajax({
                type: "POST",
                url: "procesos/crud.php?",
                data: string2,
                success: function(data){
                  if(data!='FALSE'){
                    //console.log('entra al true');
                    window.location.href = "index.php?pagina=comercio_add&id="+data;
                  }
                  else {alert('Error al insertar cliente')}
                }
            });

                                      });

                                      $('#agregar_comercio').click(function(){
                                        var cliente = $('[name=cliente]').val();
                                        var razon = $('[name=razon]').val();
                                        var condicioniva = $('[name=condicioniva] option:selected').val();
                                        var cuit = $('[name=cuit]').val();
                                        var telefono = $('[name=telefono]').val();
                                        var rubro = $('[name=rubro] option:selected').val();
                                        var ciudad =$('[name=ciudad] option:selected').val();
                                        var direccion = $('[name=direccion]').val();
                                        var perifact = $('[name=perifact] option:selected').val();
                                        var dirnum = $('[name=dirnum]').val();
                                        var vendedor = $('[name=vendedor] option:selected').val();
                                        var string = "accion=add_comercio&cliente="+cliente+"&razon="+razon+"&condicioniva="+condicioniva+"&cuit="+cuit+"&telefono="+telefono+"&rubro="+rubro+"&ciudad="+ciudad+"&direccion="+direccion+"&dirnum="+dirnum+"&vendedor="+vendedor+"&perifact="+perifact;
                                                $.ajax({
                                                    type: "POST",
                                                    url: "procesos/crud.php?",
                                                    data: string,
                                                    success: function(data){
                                                      if(data!='FALSE'){
                                                        console.log('entra al true');
                                                        window.location.href = "index.php?pagina=clientes&msg=ok";
                                                      }
                                                      else {alert('Error al insertar comercio')}
                                                    }
                                                });

                                                                          });

          function modificar_comercio(){
            console.log('Entra a la funcion 1');
            var cliente = $('#cliente_id_come').val();
            var comercio = $('#comercio_id_come').val();
            var razon = $('#razon_come').val();
            var condicioniva = $('#condicioniva_come option:selected').val();
            var cuit = $('#cuit_come').val();
            var telefono = $('#telefono_come').val();
            var rubro = $('#rubro_come option:selected').val();
            var ciudad =$('#ciudad_come option:selected').val();
            var direccion = $('#direccion_come').val();
            var perifact = $('#perifact_come option:selected').val();
            var dirnum = $('#dirnum_come').val();
            var vendedor = $('#vendedor_come option:selected').val();
            var string = "accion=edita_comercio&cliente="+cliente+"&razon="+razon+"&condicioniva="+condicioniva+"&cuit="+cuit+"&telefono="+telefono+"&rubro="+rubro+"&ciudad="+ciudad+"&direccion="+direccion+"&dirnum="+dirnum+"&vendedor="+vendedor+"&id="+comercio+"&perifact="+perifact;
            console.log('Entra a la funcion 2');
                    $.ajax({
                        type: "POST",
                        url: "procesos/crud.php?",
                        data: string,
                        success: function(data){
                          if(data!='FALSE'){
                            console.log('Entra a la funcion 3');
                            console.log('entra al true');
                            window.location.href = "index.php?pagina=clientes&msg=ok";
                          }
                          else {alert('Error al insertar comercio')}
                        }
                    });

}


    function buscaciudad(){

      var prov = $('.provincia').val();
    //  console.log('provincia= '+prov);
      $.ajax({
        	type: "POST",
          url: "procesos/ciudades.php?",
          data: "p="+prov,
      //    crossDomain: true,
  			//	cache: false,
          success: function(data) {
            $('.ciudad').html(data);
            $('.ciudad').prop("disabled", false);
        //    console.log('Resultado= '+data)
          }
      });

    }
/*
  $('#agregar_pago').click(function(){

      var prov = $('.provincia').val();
      var monto = $('[name=monto_pago]').val();
      var detalle = $('[name=detalle_pago]').val();
      var comercio = $('[name=comercio_pago] option:selected').val();
      var fecha = $('[name=fecha_pago]').val();
    //  console.log('provincia= '+prov);
      $.ajax({
          type: "POST",
          url: "procesos/crud.php?",
          data: "accion=add_pago&monto="+monto+"&detalle="+detalle+"&comercio="+comercio+"&fecha="+fecha,
      //    crossDomain: true,
        //	cache: false,
          success: function(data) {

            var cantidad = Object.keys(data).length;
            var ultimo = cantidad-1;
            console.log('Cantidad: '+cantidad)
            $('.card-title').html('Confirme los Pedidos a abonar');
            var confirmapedidos = '<div class="modal-body">'
                                  +'  <div class="row">'
                                  +'    <div class="table-responsive">'
                                  +'            <table class="table">'
                                  +'              <thead>'
                                  +'                <tr>'
                                  +'                  <th>#</th>'
                                  +'                  <th>Fecha</th>'
                                  +'                  <th>Detalle</th>'
                                  +'                  <th>Monto</th>'
                                  +'                  <th>Estado</th>'
                                  +'                  <th>Cuenta</th>'
                                  +'                </tr>'
                                  +'              </thead>'
                                  +'              <tbody>';
for(i=0; i < cantidad-1; i++) {
   confirmapedidos +='<tr> <td>'+data[i].npedido+'</td><td>'+data[i].fechapedido+'</td><td>'+data[i].detalle+'</td><td>$'+data[i].monto+'</td><td>'+data[i].cubierta+'</td><td>'+data[i].saldo+'</td></tr>';
}
 confirmapedidos +=' </tbody>'
 +'            </table>'
 +'          </div>'
 +'<h4>Saldo a cuenta $'+data[ultimo].saldo+'<h4> <button type="button" >Confirmar Pago</button>'
 +'</div></div>';
            $('#add_pago_form').html(confirmapedidos);
          //  console.log('data: '+confirmapedidos)

        //    console.log('Resultado= '+data)
          }
      });

    });

    */
    function editar_cliente(id){
        var apellido = $('.apellido').val();
        var nombre = $('.nombre').val();
        var tipodni = $('.tipodni option:selected').val();
        var dni = $('.dni').val();
        var sexoh = $('.sexoh').val();
        var sexom = $('.sexom').val();
        var sexo ='M';
        var ecivil = $('.ecivil option:selected').val();
        var cumple_d = $('.cumple_d').val();
        var cumple_m = $('.cumple_m').val();
        var cumple_a = $('.cumple_a').val();
        var cumple = cumple_a+'-'+cumple_m+'-'+cumple_d;
        var email = $('.email').val();
        var email2 = $('.email2').val();
        var telfijo = $('.telfijo').val();
        var celular = $('.celular').val();
        var provincia = $('.provincia option:selected').val();
        var ciudad = $('.ciudad option:selected').val();
        var direccion = $('.direccion').val();
        var numero = $('.numero').val();
        var piso = $('.piso').val();
        var depto = $('.depto').val();
        var upload = $('.upload').val();
        var string2 = "accion=editar_clientes&id="+id+"&apellido="+apellido+"&nombre="+nombre+"&tipodni="+tipodni+"&dni="+dni+"&sexo="+sexo+"&ecivil="+ecivil+"&cumple="+cumple+"&email="+email+"&email2="+email2+"&telfijo="+telfijo+"&celular="+celular+"&provincia="+provincia+"&ciudad="+ciudad+"&direccion="+direccion+"&numero="+numero+"&piso="+piso+"&depto="+depto+"&upload="+upload;
        tomociudad = ciudad;
        // console.log('datos string2: '+string2);
                $.ajax({
                    type: "POST",
                    url: "procesos/crud.php?",
                    data: string2,
                    success: function(data){
                      if(data!='FALSE'){
                        //console.log('entra al true');
                        window.location.href = "index.php?pagina=comercio_edit&id="+id;
                      }
                      else {alert('Error al editar cliente')}
                    }
                });
    }

function elimina_c(id){
  console.log('entra a la accion con id: '+id)
    var string = "accion=elimina_clientes&id="+id;
          $.ajax({
              type: "POST",
              url: "procesos/crud.php?",
              data: string,
              success: function(data){
              if(data=='TRUE'){
                  //console.log('entra al true')
              window.location.href = "index.php?pagina=clientes";
          } else {
             // console.log('entra al false')

          }
                                      }
                });


                      }

                      function elimina_t(id,tipo){
if(tipo=='1'){var paso = 'pedidos';}else {var paso = 'pagos';}
  console.log('entra a la transaccion con id: '+id+' y tipo'+tipo+' y queda el paso='+paso)
    var string = "accion=elimina_transaccion&id="+id;
          $.ajax({
              type: "POST",
              url: "procesos/crud.php?",
              data: string,
              success: function(data){
              if(data=='TRUE'){
             console.log('entra al true');
              window.location.href = "index.php?pagina="+paso;
          } else {
             // console.log('entra al false')

          }
                                      }
                });


                      }

          function llenaselect(select,id){
          console.log('entra a llenaselect con:'+select)
            if(select=='fabricante'){var accion='fabrilist';}
            if(select=='categoria'){var accion='catelist';}

            var url ="procesos/productos.php?";
            var string ='accion='+accion;
             $.ajax({
                     type: "POST",
                     url: url,
                     data: string,
                     success: function(data){
                     if(data != 'FALSE'){
                                 $("#"+select).html(data);
                                 $("#"+select).val(id)
                           }
                      else{
                              alert('Error al cargar el combo');
                          }

                       }
             });
          }

          function liquidacion(id){
            $("#btn_confirma_liqui").prop('disabled',true);
            var acumulando_items =0;
            $(".cantidades").each(function(){
              acumulando_items = parseFloat(acumulando_items) + parseFloat($(this).val());
             });
          //  var prov = $('.provincia').val();
          //  console.log('provincia= '+prov);

            var vendedor = $('#personal_'+id).val();
            //var devoluciones = $('#cantidadprod_'+id).val();
            var montototal = $('#tot_a_cobrar_'+id).val();
            var entrega = $('#total_rendido_'+id).val();
            var fecha = $('#fecha_'+id).val();
            var observaciones = $('#observacion_liq_'+id).val();
          //var items = $('#items_'+id).val();

            $.ajax({
                type: "POST",
                url: "procesos/liquidacion.php?",
                data: "a=liquida&vendedor="+vendedor+"&montototal="+montototal+"&entrega="+entrega+"&observaciones="+observaciones+"&fecha="+fecha,
            //    crossDomain: true,
              //	cache: false,
                success: function(data) {
                  if(data=='true'){
                    window.location.href = "index.php?pagina=liquidaciones";
                    $("#btn_confirma_liqui").prop('disabled',false);
                  }else{
                    alert('Avisarle a Ale :'+data);
                    $("#btn_confirma_liqui").prop('disabled',false);
                  }
                  console.log('Resultado= '+data)
                }
            });

          }
