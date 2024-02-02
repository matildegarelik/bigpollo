<?php
include '../inc/conection.php';
date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_ALL, "es_ES");
session_start();


if (!$link) {
	die('No se ha podido conectar a la base de datos');
}


if ($_SESSION['usuario'] != "") {

	$quien = $_SESSION['id'];
	$cuando = date("Y-m-d H:i:s");

	function safe_json_encode($value, $options = 0, $depth = 512)
	{
		$encoded = json_encode($value, $options, $depth);
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = utf8ize($value);
				return safe_json_encode($clean, $options, $depth);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()
		}
	}

	function utf8ize($mixed)
	{
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = utf8ize($value);
			}
		} else if (is_string($mixed)) {
			return utf8_encode($mixed);
		}
		return $mixed;
	}

	include_once('../lib/visor-pdf/visor.php');

	$personal = $_GET['u'];
	$periodo = $_GET['p'];
	$sql_liqui = "SELECT * FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and transaccion.quien='$personal' INNER JOIN liquidaciones on DATE(fecha_liquidacion) = DATE(fecha) and liquidaciones.vendedor_liquidacion=$personal WHERE DATE(fecha) LIKE '$periodo' AND `estado` = 1 and tipo = 'pedido' ORDER BY `transaccion`.`id` ASC";
	$transacciones = $link->query($sql_liqui);
	$sql_gastos = "SELECT * FROM `gastos` INNER JOIN tipo_gastos on tipo_gastos.id_tipogasto = gastos.tipo_gasto WHERE estado_gasto = 1 and DATE(fecha_gasto) LIKE '$periodo' and personal_gasto ='$personal' ";
	$gast = $link->query($sql_gastos);

	$idliqui = $link->query("SELECT id_liquidacion, CONCAT(nombre,', ',apellido) as personal, cuando_liquidacion FROM liquidaciones INNER JOIN personal on personal.id = vendedor_liquidacion WHERE DATE(fecha_liquidacion) = '$periodo' AND vendedor_liquidacion = $personal AND estado_liquidacion = 1 ");
	$liquid = mysqli_fetch_array($idliqui);
	$hora_liqui = $liquid['cuando_liquidacion'];
	$comp_int = '0001-' . str_pad($liquid['id_liquidacion'], 8, "0", STR_PAD_LEFT);
	$comp_fecha = date('d/m/Y', strtotime($periodo));
	$logo = '';
	$letter = 'X';
	$type = 'LIQUIDACION #';
	$plantilla =  "<page>";
	$plantilla .= "<div class='border-div'>";

	$plantilla .= "	 <table class='responsive-table table-header' >";
	$plantilla .= "		 <tr>";
	$plantilla .= "		   <td>";
	$plantilla .= "        <img class='logo' src='../img/logo-liqui.png' alt='logo' >"; //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	$plantilla .= "      </td>";
	$plantilla .= "      <td style='width: 43%;'>"; // text-align:center
	$plantilla .= "        <span class='type_voucher header_margin'>$type&nbsp; &nbsp;$comp_int</span><br>";
	$plantilla .= "        <span style='width:56%;font-weight: bolder;' >Cierre de liquidacion: $hora_liqui</span><br>";
	$plantilla .= "      </td>";
	$plantilla .= "      <td style='width: 43%;'>";
	$plantilla .= "        <span class='type_voucher'>Fecha:  " . $comp_fecha . "</span><br>";
	$plantilla .= "        <span class='center-text' style='width:43%;font-weight: bolder;'>VENDEDOR: " . $liquid['personal'] . "</span><br>";
	$plantilla .= "      </td>";
	$plantilla .= "   </tr>";
	$plantilla .= " </table>";
	$plantilla .= " <table class='responsive-table table-header >";
	$plantilla .= "   <tr>";
	$plantilla .= "      <td style='width:43% ></td>";
	$plantilla .= "   </tr>";
	$plantilla .= " </table>";
	$plantilla .= "</div>";

	// bucle items
	$plantilla .= "<div >";
	$plantilla .= "<table class='responsive-table table-article' style='width:610px;' border='1'  bordercolor='#CCCCCC' cellspacing='0'>";
	$plantilla .= "  <tr>";
	$plantilla .= "     <th class='center-text' style='width=25%;'> CLIENTE </th>";
	$plantilla .= "     <th class='center-text' style='width=15%;'> UNIDAD </th>";
	$plantilla .= "     <th class='center-text' style='width=45%;'> PRODUCTO </th>";
	$plantilla .= "     <th class='center-text' style='width=15%;'> PRECIO U. </th>";
	$plantilla .= "     <th class='center-text' style='width=25%;'> TOTAL. </th>";
	$plantilla .= "  </tr> ";

	$xx = 0;
	$acuenta = [];
	while ($t = mysqli_fetch_array($transacciones)) {
		$id_t = $t['id'];
		$busco_items_trans = "SELECT * FROM items_pedidos INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE estado_itemsp = 1 and pedido_itemsp = '$id_t' ";
		$items = $link->query($busco_items_trans);


		$contado = $t['pagos'];
		$cc = $t['monto'] - $t['pagos'];
		$total = $t['monto'];
		$total_grl = $total_grl + $total;

		while ($it = mysqli_fetch_array($items)) {
			$plantilla .= "  <tr >";
			$plantilla .= " 	<td class='center-text' style='width=25%;'>" . $t['razon_com_clientes'] . "</td>";
			$plantilla .= "   <td class='center-text' style='width=15%;'>" . $it['cantidad_itemsp'] . "</td>";
			$plantilla .= "   <td class='center-text' style='width=45%;'>" . $it['descripcion_producto'] . "</td>";
			$plantilla .= "   <td class='center-text' style='width=15%;'>$ " . number_format($it['monto_itemsp'], 2, ',', '.') . "</td>";
			$plantilla .= "   <td class='center-text' style='width=25%;'>$ " . number_format($it['monto_itemsp'] * $it['cantidad_itemsp'], 2, ',', '.') . "</td>";
			$plantilla .= " </tr>";
		} //cierra while items

	} //cierra while transaccion




	$plantilla .= "</table> ";
	$plantilla .= "</div>";

	$plantilla .= '<page_footer>';

	$plantilla .= ' <div >';
	$plantilla .= '       <div class="piechico"><span >Reporte generado por Prompt Software el ' . $cuando . '</span></div>';
	$plantilla .= ' </div>';
	$plantilla .= '</page_footer>';
	$plantilla .= "</page>";

	$pdf = new PDFVoucher($voucher, $config);

	$pdf->genera_PDF($plantilla, $logo);

	//nombre del archivo
	$name_file = "rto_i" . $comp_int;
	$pdf->Output($name_file . ".pdf");
}
///////
