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

	$sql_entrega = "SELECT entrega_liquidacion as total FROM liquidaciones WHERE DATE(liquidaciones.fecha_liquidacion)='$periodo' and estado_liquidacion=1 and vendedor_liquidacion = $personal";
	$total_entr = $link->query($sql_entrega);
	$total_entrega = mysqli_fetch_array($total_entr);

	$sql_pagos = "SELECT monto2, razon_com_clientes FROM `transaccion` INNER JOIN clientes on clientes.id_clientes = transaccion.cliente and transaccion.quien='$personal' WHERE DATE(fecha) LIKE '$periodo' AND `estado` = 1 and tipo = 'pago' and observacion not like 'Abono con transaccion:%' ";
	$pago_gral = $link->query($sql_pagos);

	$idliqui = $link->query("SELECT id_liquidacion, CONCAT(nombre,', ',apellido) as personal, cuando_liquidacion FROM liquidaciones INNER JOIN personal on personal.id = vendedor_liquidacion WHERE DATE(fecha_liquidacion) = '$periodo' AND vendedor_liquidacion = $personal AND estado_liquidacion = 1 ");
	$liquid = mysqli_fetch_array($idliqui);
	$hora_liqui = $liquid['cuando_liquidacion'];
	$comp_int = '0001-' . str_pad($liquid['id_liquidacion'], 8, "0", STR_PAD_LEFT);
	$comp_fecha = date('d/m/Y', strtotime($periodo));
	$logo = '';
	$letter = 'X';
	$type = 'LIQUIDACION #';
	$head_pdf =  "<page>";
	$head_pdf .= "<div class='border-div'>";

	$head_pdf .= "	 <table class='responsive-table table-header' >";
	$head_pdf .= "		 <tr>";
	$head_pdf .= "		   <td>";
	$head_pdf .= "        <img class='logo' src='../img/logo-liqui.png' alt='logo' >"; //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	$head_pdf .= "      </td>";
	$head_pdf .= "      <td style='width: 43%;'>"; // text-align:center
	$head_pdf .= "        <span class='type_voucher header_margin'>$type&nbsp; &nbsp;$comp_int</span><br>";
	$head_pdf .= "        <span style='width:56%;font-weight: bolder;' >Cierre de liquidacion: $hora_liqui</span><br>";
	$head_pdf .= "      </td>";
	$head_pdf .= "      <td style='width: 43%;'>";

	$head_pdf .= "        <span class='type_voucher'>Fecha:  " . $comp_fecha . "</span><br>";
	$head_pdf .= "        <span class='center-text' style='width:43%;font-weight: bolder;'>VENDEDOR: " . $liquid['personal'] . "</span><br>";
	$head_pdf .= "      </td>";
	$head_pdf .= "   </tr>";
	$head_pdf .= " </table>";
	$head_pdf .= " <table class='responsive-table table-header >";
	$head_pdf .= "   <tr>";
	$head_pdf .= "      <td style='width:43% ></td>";
	$head_pdf .= "   </tr>";
	$head_pdf .= " </table>";
	$head_pdf .= "</div>";
	// bucle items
	$plantilla = $head_pdf;
	$plantilla .= "<div >";
	$plantilla .= "<table class='responsive-table table-article' style='width:610px;display:block' border='1'  bordercolor='#CCCCCC' cellspacing='0'>";
	$plantilla .= "  <tr>";
	$plantilla .= "     <th class='center-text' style='width=29%;'> CLIENTE </th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'> POLLO </th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'> SUP </th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'> PM </th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'> AL </th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'> CONG </th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'> HUE </th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'> OTROS </th>";
	$plantilla .= "     <th class='center-text' style='width=15%;'> TOTAL. </th>";
	$plantilla .= "     <th class='center-text' style='width=15%;'> CONTADO </th>";

	$plantilla .= "  </tr> ";
	$par = 0;
	$contado = 0;
	$cc = 0;
	$total = 0;
	$total_grl = 0;

	$pollo_grl = 0;
	$sup_grl = 0;
	$pm_grl = 0;
	$al_grl = 0;
	$cong_grl = '0';
	$hue_grl = 0;
	$otro_grl = 0;
	$entrega_grl = 0;
	$xx = 0;
	$acuenta = [];
	$recorre_blucle = 0;
	$salto_obligado = 0;
	$tope_bucle = 0;

	if (mysqli_num_rows($pago_gral) > 7) {
		$tope_bucle = mysqli_num_rows($transacciones);
		$salto_obligado = 1;
	} else {
		$tope_bucle = 25;
	}


	while ($t = mysqli_fetch_array($transacciones)) {
		$id_t = $t['id'];
		$pollo = 0;
		$sup = 0;
		$pm = 0;
		$al = 0;
		$cong = '0';
		$hue = 0;
		$otro = 0;
		$entrega = 0;
		$sum_pollo = 0;
		$sum_sup = 0;
		$sum_pm = 0;
		$sum_al = 0;
		$sum_cong = '0';
		$sum_hue = 0;
		$sum_otro = 0;

		$contado = $t['pagos'];
		$cc = $t['monto'] - $t['pagos'];
		$total = $t['monto'];
		$total_grl = $total_grl + $total;

		$sql_ft = "SELECT monto2 FROM `transaccion` WHERE DATE(fecha) LIKE '$periodo' AND `estado` = 1 and tipo = 'pago'  and observacion = 'Abono con transaccion: $id_t'";
		$pagotrans = $link->query($sql_ft);
		if ($can_pago = mysqli_fetch_array($pagotrans)) {
			$entrega = $can_pago['monto2'];
			$entrega_grl = $entrega_grl + $entrega;
		}
		$cantidades_items = $link->query("SELECT cantidad_itemsp, categoria_producto, monto_itemsp FROM `items_pedidos` INNER JOIN productos on productos.id_producto = items_pedidos.prod_itemsp WHERE `pedido_itemsp` = $id_t and estado_itemsp = 1 and quien_itemsp=$personal");
		while ($can_item = mysqli_fetch_array($cantidades_items)) {
			if ($can_item['categoria_producto'] == 1) {
				$pollo = $pollo + $can_item['cantidad_itemsp'];
				$sum_pollo = $sum_pollo + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 2) {
				$sup = $sup + $can_item['cantidad_itemsp'];
				$sum_sup = $sum_sup + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 3) {
				$pm = $pm + $can_item['cantidad_itemsp'];
				$sum_pm = $sum_pm + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 6) {
				$al = $al + $can_item['cantidad_itemsp'];
				$sum_al = $sum_al + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 7) {
				$cong = $cong + $can_item['cantidad_itemsp'];
				$sum_cong = $sum_cong + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 9) {
				$hue = $hue + $can_item['cantidad_itemsp'];
				$sum_hue = $sum_hue + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
			if ($can_item['categoria_producto'] == 10) {
				$otro = $otro + $can_item['cantidad_itemsp'];
				$sum_otro = $sum_otro + ($can_item['monto_itemsp'] * $can_item['cantidad_itemsp']);
			}
		}


		$plantilla .= "  <tr >";

		$pollo_grl = $pollo_grl + $pollo;
		$sup_grl = $sup_grl + $sup;
		$pm_grl = $pm_grl + $pm;
		$al_grl = $al_grl + $al;
		$cong_grl = $cong_grl + $cong;
		$hue_grl = $hue_grl + $hue;
		$otro_grl = $otro_grl + $otro;

		if ($pollo == 0) {
			$pollo = '';
		}
		if ($sup == 0) {
			$sup = '';
		}
		if ($pm == 0) {
			$pm = '';
		}
		if ($al == 0) {
			$al = '';
		}
		if ($cong == 0) {
			$cong = '';
		}
		if ($hue == 0) {
			$hue = '';
		}
		if ($otro == 0) {
			$otro = '';
		}

		$plantilla .= "     <td rowspan='2' class='center-text' style='width=29%;'>" . $t['razon_com_clientes'] . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $pollo . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $sup . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $pm . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $al . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $cong . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $hue . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $otro . "</td>";
		$plantilla .= "     <td style='width=15%;'>$ " . number_format($total, 2, ',', '.') . "</td>";
		$plantilla .= "     <td style='width=15%;'>$ " . number_format($entrega, 2, ',', '.') . "</td>";
		$plantilla .= "     </tr>";


		$calculo_pollo = '';
		$calculo_sup = '';
		$calculo_pm = '';
		$calculo_al = '';
		$calculo_cong = '';
		$calculo_hue = '';
		$calculo_otro = '';

		if ($pollo != '') {
			$calculo_pollo = "$ " . number_format(($sum_pollo / $pollo), 0, ',', '.');
		}
		if ($sup != '') {
			$calculo_sup = "$ " . number_format(($sum_sup / $sup), 0, ',', '.');
		}
		if ($pm != '') {
			$calculo_pm = "$ " . number_format(($sum_pm / $pm), 0, ',', '.');
		}
		if ($al != '') {
			$calculo_al = "$ " . number_format(($sum_al / $al), 0, ',', '.');
		}
		if ($cong != '') {
			$calculo_cong = "$ " . number_format(($sum_cong / $cong), 0, ',', '.');
		}
		if ($hue != '') {
			$calculo_hue = "$ " . number_format(($sum_hue / $hue), 0, ',', '.');
		}
		if ($otro != '') {
			$calculo_otro = "$ " . number_format(($sum_otro / $otro), 0, ',', '.');
		}

		$plantilla .= "  <tr >";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $calculo_pollo . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $calculo_sup . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $calculo_pm . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $calculo_al . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $calculo_cong . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $calculo_hue . "</td>";
		$plantilla .= "     <td class='center-text' style='width=9%;'>" . $calculo_otro . "</td>";
		$plantilla .= "     <td colspan='2' class='center-text' style='width=15%;'></td>";
		//$plantilla.="     <td style='width=15%;'>$ </td>";

		$plantilla .= "  </tr>";
		if ($par == 1) {
			$par = 0;
		} else {
			$par++;
		}

		if ($recorre_blucle == $tope_bucle && $salto_obligado != 1) {
			$plantilla .= "</table> ";
			$plantilla .= "</div>";
			$plantilla .= '	</page>';
			$plantilla .= $head_pdf;
			$plantilla .= "<div >";
			$plantilla .= "<table class='responsive-table table-article' style='width:610px;display:block' border='1'  bordercolor='#CCCCCC' cellspacing='0'>";
			$plantilla .= "  <tr>";
			$plantilla .= "     <th class='center-text' style='width=29%;'> CLIENTE </th>";
			$plantilla .= "     <th class='center-text' style='width=9%;'> POLLO </th>";
			$plantilla .= "     <th class='center-text' style='width=9%;'> SUP </th>";
			$plantilla .= "     <th class='center-text' style='width=9%;'> PM </th>";
			$plantilla .= "     <th class='center-text' style='width=9%;'> AL </th>";
			$plantilla .= "     <th class='center-text' style='width=9%;'> CONG </th>";
			$plantilla .= "     <th class='center-text' style='width=9%;'> HUE </th>";
			$plantilla .= "     <th class='center-text' style='width=9%;'> OTROS </th>";
			$plantilla .= "     <th class='center-text' style='width=15%;'> CONTADO </th>";
			$plantilla .= "     <th class='center-text' style='width=15%;'> TOTAL. </th>";
			$plantilla .= "  </tr> ";
			$recorre_blucle = 0;
		} else {
			$recorre_blucle++;
		}
	}

	// acumula cantidades pie

	$plantilla .= "     <tr>";
	$plantilla .= "     <th rowspan='2' class='center-text' style='width=29%;'>TOTALES</th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'>" . $pollo_grl . "</th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'>" . $sup_grl . "</th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'>" . $pm_grl . "</th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'>" . $al_grl . "</th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'>" . $cong_grl . "</th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'>" . $hue_grl . "</th>";
	$plantilla .= "     <th class='center-text' style='width=9%;'>" . $otro_grl . "</th>";
	$plantilla .= "     <th style='width=15%;'>$ <span style='right:0px'>" . number_format($entrega_grl, 2, ',', '.') . "</span></th>";
	$plantilla .= "     <th style='width=15%;'>$ " . number_format($total_grl, 2, ',', '.') . "</th>";
	$plantilla .= "     </tr>";

	$plantilla .= "</table> ";
	$plantilla .= "</div>";
	if ($salto_obligado == 1) {
		$plantilla .= '	</page>';
		$plantilla .= $head_pdf;
	}
	$plantilla .= '<page_footer>';
	$plantilla .= '<div >';
	$plantilla .= '    <table class="responsive-table table-article" border="1">';
	$plantilla .= '        <tr>';
	$plantilla .= '          <th class="center-text">Gastos</th>';
	$plantilla .= '          <th class="center-text">Cobros Cuentas Corrientes</th>';
	$plantilla .= '        </tr>';
	$plantilla .= '        <tr>';
	$plantilla .= '            <td style="width:310px;">';
	$plantilla .= '    						<table class="responsive-table">';
	$plantilla .= '       					 <tr>';
	$plantilla .= '            					<td class="center-text negrita" style="width=152px;">TIPO</td>';
	$plantilla .= '            					<td class="center-text negrita" style="width=152px;">IMPORTE</td>';
	$plantilla .= '       					 </tr>';

	//bucle items gastos
	$total_gasto = 0;
	while ($g = mysqli_fetch_array($gast)) {
		$total_gasto = $total_gasto + $g['monto_gasto'];
		$plantilla .= '        <tr>';
		$plantilla .= '            <td class="center-text " style="width=152px;">' . $g['nombre_tipogasto'] . '</td>';
		$plantilla .= '            <td class="center-text " style="width=152px;">$' . $g['monto_gasto'] . '</td>';
		$plantilla .= '        </tr>';
	}

	$plantilla .= '        <tr>';
	$plantilla .= '            <td class="right-text" style="width=200px;">Total: $</td>';
	$plantilla .= '            <td class="right-text" style="width=70px;">' . $total_gasto . '</td>';
	$plantilla .= '        </tr>';
	$plantilla .= '    </table>';
	$plantilla .= '            </td>';
	$plantilla .= '            <td style="width:310px;" >';
	$plantilla .= '    <table class="responsive-table table-article">';
	$plantilla .= '        <tr>';
	$plantilla .= '            <td class="center-text negrita" style="width=152px;">CLIENTE</td>';
	$plantilla .= '            <td class="center-text negrita" style="width=152px;">IMPORTE</td>';
	$plantilla .= '        </tr>';

	$acumula_acuenta = 0;
	//for ($i=0; $i < count($acuenta) ; $i++) {


	while ($pgral = mysqli_fetch_array($pago_gral)) {
		//	$acumula_acuenta=$acumula_acuenta+$acuenta[$i]['importe'];
		$acumula_acuenta = $acumula_acuenta + $pgral['monto2'];

		$plantilla .= '        <tr>';
		$plantilla .= '            <td class="center-text negrita" style="width=152px;">' . $pgral['razon_com_clientes'] . '</td>';
		$plantilla .= '            <td class="center-text negrita" style="width=152px;">$' . number_format($pgral['monto2'], 2, ',', '.') . '</td>';
		$plantilla .= '        </tr>';
	}

	$plantilla .= '        <tr>';
	$plantilla .= '            <td class="right-text" style="width=200px;">Total: $</td>';
	$plantilla .= '            <td class="right-text" style="width=70px;">' . number_format($acumula_acuenta, 2, ',', '.') . '</td>';
	$plantilla .= '        </tr>';
	$plantilla .= '    </table>';
	$plantilla .= '            </td>';
	$plantilla .= '        </tr>';
	$plantilla .=  '    </table>';


	$plantilla .= '</div>';

	$plantilla .= ' <div class="right-text" style="padding-top:10px">';
	$plantilla .= '       Subtotal Pagos: $' . number_format($entrega_grl, 2, ',', '.') . '<br>';
	$plantilla .= '       Subtotal Cobros: $' . number_format($acumula_acuenta, 2, ',', '.') . '<br>';
	$plantilla .= '       Subtotal Gastos: -$' . number_format($total_gasto, 2, ',', '.') . '<br>';
	$plantilla .= '<span style="border-button: 1px; ">';
	$plantilla .= '       TOTAL: $' . number_format((($acumula_acuenta + $entrega_grl) - $total_gasto), 2, ',', '.');
	$plantilla .= '</span><br>-----------------------<br>';
	$plantilla .= '       Liquidacion: -$' . number_format($total_entrega['total'], 2, ',', '.') . '<br>';
	$plantilla .= '       Diferencia: $' . number_format($total_entrega['total'] - (($acumula_acuenta + $entrega_grl) - $total_gasto), 2, ',', '.') . '<br>';


	$plantilla .= '</div>';

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
