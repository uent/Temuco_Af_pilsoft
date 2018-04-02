<?php
require_once 'config.php';
require_once 'fpdf/fpdf.php';

setlocale(LC_ALL, '');
$userID = $_SESSION['userid'];
$fecha_modificacion = date("Y-m-d H:i:s");
$area_bd_temuco = ( $_SESSION['emp'] == 'municipal' ) ? 'MUNICIPALIDAD' : strtoupper($_SESSION['emp']);

if( !empty($_GET) ){

$opc = array_shift($_GET); // extrae la opción a realizar (ver/generar)

if(isset($_GET["opc2"]))
{
$opc2 = array_shift($_GET);	
}
else $opc2 = "";

$ids_reub = $_GET;

//obtiene los act_id relacionado con las reubicaciones en ids_reub
	$query = "SELECT act_id FROM reubicaciones_activos WHERE ";
foreach($ids_reub AS $idR)
{
	$query .= "reub_id =" . $idR . " OR "; 
}
$query = substr($query, 0, -3); //elimina el "OR" sobrante al final del query
	//echo $query;
	
$res = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
$i = 0;

while( $aux = mysqli_fetch_assoc($res) ){
	$ids[$i] = $aux["act_id"];
	$i++;
} 
mysqli_free_result($res);
//var_dump($ids_reub);

//var_dump( $ids);
//echo $ids_reub[0];

$raiz = '';
$ruta = 'copias_pdf_generados/decretosTraslado/';

if($opc2 == "")
{
	
	$sql = "SELECT * FROM param_decreto_traslado LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$org = mysqli_fetch_assoc($res);
	mysqli_free_result($res);

	$sql = "SELECT vtras_id, vtras_descripcion FROM param_vistos_traslado ORDER BY vtras_id ASC";	
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

	$vistos = array();

	while( $visto = mysqli_fetch_assoc($res) ){
		$vistos[] = $visto['vtras_descripcion'];
	}

	mysqli_free_result($res);

	$sql = "SELECT dtras_id, dtras_descripcion FROM param_distri_traslado ORDER BY dtras_id ASC";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

	$distribucion = array();

	while( $distri = mysqli_fetch_assoc($res) ){
		$distribucion[] = $distri['dtras_descripcion'];
	}

	mysqli_free_result($res);
}else if($opc2 == "ReGenerarInforme")
{
		
	$sql = "SELECT * FROM datos_decreto_traslado WHERE dadetr_id= " . $ids_reub[0] . " LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$datosDecretoBajaRealizado = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	
	$vistos = explode(".|", $datosDecretoBajaRealizado["dadetr_descripVistos"]);
	
	$distribucion = explode(".|", $datosDecretoBajaRealizado["dadetr_descripDistri"]);
	
	$org["dtras_rutaLogo"] = $datosDecretoBajaRealizado["dadetr_rutaLogo"];
	$org["dtras_nombreOrg"] = $datosDecretoBajaRealizado["dadetr_nombreOrg"]; 
	$org["dtras_nombreSecre"] = $datosDecretoBajaRealizado["dadetr_nombreSecre"];
	$org["dtras_nombreAlcalde"] = $datosDecretoBajaRealizado["dadetr_nombreAlcalde"];   
	$org["dtras_cargoFirma1"] = $datosDecretoBajaRealizado["dadetr_cargoFirma1"];
	$org["dtras_cargoFirma2"] = $datosDecretoBajaRealizado["dadetr_cargoFirma2"];
	$org["dtras_porOrdenFirma1"] = $datosDecretoBajaRealizado["dadetr_porOrdenFirma1"];
	$org["dtras_porOrdenFirma2"] = $datosDecretoBajaRealizado["dadetr_porOrdenFirma2"];
	$org["dtras_iniciales"] = $datosDecretoBajaRealizado["dadetr_iniciales"];
	$org["dtras_nombreDireccion"] = $datosDecretoBajaRealizado["dadetr_nombreDireccion"];
}
	
class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        global $org, $opc;
        // Logo
        if( !empty($org['dtras_rutaLogo']) ){
            $this->Image($org['dtras_rutaLogo'],25,10,20,0);
        }
        // Arial bold 15
        $this->SetFont('Arial','BU',12);
        // Título        
        $this->Cell(0,20,( $opc=='preview' ) ? 'Borrador' : '',0,0,'C');
        // Salto de línea
        $this->Ln(25);
    }
    
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',7);
        // Número de página
        //$this->Cell(0,8,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

if($opc2 == "")
{

// Obtiene el último folio de traslado de la BD para crear el nuevo
$sql = "SELECT tras_folio FROM decretos_traslado ORDER BY tras_folio DESC LIMIT 1";
$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
$num = mysqli_fetch_assoc($res);
$folio = ( empty($num['tras_folio']) ) ? 1 : ( (int) $num['tras_folio'] ) + 1;
mysqli_free_result($res);
                                                                                               


	$query = "SELECT AF.act_id, act_codigo, act_descripcionDetallada, aneg_descripcion, cond_descripcion, reub_id
         FROM activos_fijos AF 
           JOIN areas_negocios AN 
             ON AF.aneg_id=AN.aneg_id 
               JOIN centros_costo CC 
                 ON AF.ccos_id=CC.ccos_id 
                   LEFT JOIN condiciones_activos CA 
                     ON AF.cond_id=CA.cond_id 
						JOIN reubicaciones_activos AS REU
						  ON REU.act_id = AF.act_id
                       WHERE ";

	foreach( $ids as $key => $actID ){
		if( $key == 0 ){
			$query.= " (AF.act_id=".$actID." AND ";
			$query.= " REU.reub_id=".$ids_reub[$key].") ";
		}else{
			$query.= " OR (AF.act_id=".$actID." AND ";
			$query.= " REU.reub_id=".$ids_reub[$key].") ";
		}
	}
	
	$query .= " ORDER BY AF.act_id ASC";

	$resGeneral = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	
}	
elseif($opc2 == "ReGenerarInforme")
{
	
	$folio = $datosDecretoBajaRealizado["tras_folio"];
	

	
	$query .= " ORDER BY AF.act_id ASC";

	
	
	
	$query = "SELECT 
    AF.act_id,
    AF.act_codigo,
    AF.act_descripcionDetallada,
    AN.aneg_descripcion,
    CA.cond_descripcion,
    reub_id
FROM
    datos_decreto_traslado AS DDT
        JOIN
    decretos_traslado DT ON DDT.tras_folio = DT.tras_folio
        JOIN
    activos_fijos AF ON DT.act_id = AF.act_id
        JOIN
    areas_negocios AN ON AF.aneg_id = AN.aneg_id
        LEFT JOIN
    condiciones_activos CA ON AF.cond_id = CA.cond_id
WHERE
    dadetr_id = '".$ids_reub[0]."'
ORDER BY AF.act_id ASC;";
		
		//echo $query;
	$resGeneral = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
		
	$resDetalle = mysqli_query($conexion, $query) or die(mysqli_error($conexion));	
	

}



// Creación del objeto de la clase heredada
$pdf = new PDF('P','mm','Legal');
//Define márgen izq, sup, y der.
$pdf->SetMargins(25, 10, 15);
$pdf->AliasNbPages();

// Página #1
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial','B',10);
$pdf->Cell(130, 8, '', 0); // $org['dtras_nombreDireccion']
$pdf->Cell(40, 8, 'DECRETO N°_______________/', 0, 0, 'R');
$pdf->Ln();

$pdf->Cell(0, 8, 'Decreto Traslado  N° '.$folio);
$pdf->Ln();

//$fecha_hoy = date('d ').ucwords(strftime('%b')).date(' Y');
$pdf->Cell(0, 8, $org['dtras_nombreOrg'].', ', 0, 0, 'C');
$pdf->Ln();

// Enunciado
$pdf->Cell(0, 8, 'VISTOS:');
$pdf->Ln();

// Detalle enunciado
$w=0; $h=5; $b=0; $t=10;    
$pdf->SetFont('Arial','',9);

foreach( $vistos as $item ){
    $pdf->Cell($t);
    $pdf->MultiCell($w, $h, $item, $b, 'L');
}

$pdf->SetFont('Arial','B',9);
$pdf->Cell(0, 8, 'CONSIDERANDO:');
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$pdf->Write($h, '1.- Que los bienes se encuentran aún en buen estado.');
$pdf->Ln();

$pdf->SetFont('Arial','B',9);    
$pdf->Cell(0, 8, 'DECRETO:');
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$pdf->Write($h, '1.- Apruébese el traslado definitivo de los bienes que a continuación se señalan:');
$pdf->Ln(10);

// Títulos del General
$h=4; $b=0;
$pdf->SetFont('Arial','B',9);
$pdf->Cell(45, $h, 'Especificación', $b);
$pdf->Cell(25, $h, 'Cód. Inventario', $b);
$pdf->Cell(15, $h, 'Estado', $b, '', 'C');
$pdf->Cell(30, $h, 'Oficina Origen', $b, '', 'C');
$pdf->Cell(30, $h, 'Oficina Destino', $b, '', 'C');
$pdf->Cell(25, $h, 'Ubicación', $b, 1, 'C');

$pdf->SetFont('Arial','',8);

$height=4; // Alto de las celdas dinámicas

// Dibuja tabla con datos generales de c/u activo 
while( $data = mysqli_fetch_assoc($resGeneral) ){

	//extrae la ubicacion correspondiente a al reub_id recibido
    $sql1 = "SELECT reub_id, reub_fecha, reub_hora, aneg_descripcion, ccos_descripcion, NV1.ubi_descripcion AS desc_ubi_nivel_1, 
            NV1.ubi_codigo AS ubi_codigo_nv1, NV2.ubi_descripcion AS desc_ubi_nivel_2, NV2.ubi_codigo AS ubi_codigo_nv2, 
            NV3.ubi_descripcion, NV3.ubi_codigo AS ubi_codigo_nv3 FROM reubicaciones_activos RA JOIN ubicaciones NV1 
            ON NV1.ubi_codigo=CONCAT(SUBSTR(NV1.ubi_codigo, 1, 2), '-00-00') JOIN ubicaciones NV2 
            ON NV2.ubi_codigo=CONCAT(SUBSTR(NV2.ubi_codigo, 1, 5), '-00') JOIN ubicaciones NV3 
            ON RA.ubi_id=NV3.ubi_id LEFT JOIN areas_negocios AN 
            ON RA.aneg_id=AN.aneg_id LEFT JOIN centros_costo CC 
            ON RA.ccos_id=CC.ccos_id 
            WHERE RA.act_id='".$data['act_id']."' 
			AND reub_id = '".$data['reub_id']."' 
            AND CONCAT(SUBSTR(NV1.ubi_codigo, 1, 2), '-00-00')=CONCAT(SUBSTR(NV3.ubi_codigo, 1, 2), '-00-00') 
            AND CONCAT(SUBSTR(NV2.ubi_codigo, 1, 5), '-00')=CONCAT(SUBSTR(NV3.ubi_codigo, 1, 5), '-00')"; 
			
		   
	//extrae la ubicacion anterior a la señalada por el reub_id recibido
    $res1  = mysqli_query($conexion, $sql1) or die(mysqli_error($conexion));
    $ultRE = mysqli_fetch_assoc($res1);
    
    $sql2 = "SELECT reub_id, reub_fecha, reub_hora, aneg_descripcion, ccos_descripcion, NV1.ubi_descripcion AS desc_ubi_nivel_1, 
            NV1.ubi_codigo AS ubi_codigo_nv1, NV2.ubi_descripcion AS desc_ubi_nivel_2, NV2.ubi_codigo AS ubi_codigo_nv2, 
            NV3.ubi_descripcion, NV3.ubi_codigo AS ubi_codigo_nv3 FROM reubicaciones_activos RA JOIN ubicaciones NV1 
            ON NV1.ubi_codigo=CONCAT(SUBSTR(NV1.ubi_codigo, 1, 2), '-00-00') JOIN ubicaciones NV2 
            ON NV2.ubi_codigo=CONCAT(SUBSTR(NV2.ubi_codigo, 1, 5), '-00') JOIN ubicaciones NV3 
            ON RA.ubi_id=NV3.ubi_id JOIN areas_negocios AN 
            ON RA.aneg_id=AN.aneg_id JOIN centros_costo CC 
            ON RA.ccos_id=CC.ccos_id 
            WHERE RA.act_id='".$data['act_id']."' 
			AND reub_id < '".$data['reub_id']."' 
            AND CONCAT(SUBSTR(NV1.ubi_codigo, 1, 2), '-00-00')=CONCAT(SUBSTR(NV3.ubi_codigo, 1, 2), '-00-00') 
            AND CONCAT(SUBSTR(NV2.ubi_codigo, 1, 5), '-00')=CONCAT(SUBSTR(NV3.ubi_codigo, 1, 5), '-00') 
            order by reub_id desc limit 1"; 

    $res2  = mysqli_query($conexion, $sql2) or die(mysqli_error($conexion));
    $antRE = mysqli_fetch_assoc($res2);
    
    // vars permiten obtener y asignar posiciones para usar luego en ubicar las columnas
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    $pdf->MultiCell(45, $height, $data['act_descripcionDetallada'], $b, 'L'); // desde $query ($data) muestra Descripción Detallada
    
//    $x = $pdf->GetX();
//    $H = $pdf->GetY();
//    $height = $H-$y;    
    $pdf->SetXY($x+45,$y);
    
    $pdf->MultiCell(25, $height, $data['act_codigo'], $b); // desde $query ($data) muestra Código del Inventario (AF)
    
//    $x = $pdf->GetX();
//    $H = $pdf->GetY();
//    $height = $H-$y;    
    $pdf->SetXY($x+70,$y);
    
    $pdf->MultiCell(15, $height, $data['cond_descripcion'], $b, 'C'); // desde $query ($data) muestra el Estado (Condición de AF)
    
//    $x = $pdf->GetX();
//    $H = $pdf->GetY();
//    $height = $H-$y;    
    $pdf->SetXY($x+85,$y);
    
    $oficinaOrigen = $antRE['desc_ubi_nivel_1'].' - '.$antRE['desc_ubi_nivel_2'].' - '.$antRE['ubi_descripcion'];       
    //$numLineas = NbLines($x+85, $oficinaOrigen, $pdf);
    //$pdf->Write($height, $oficinaOrigen);
    $pdf->MultiCell(30, $height, $oficinaOrigen, $b, 'C'); // desde $sql2 ($antRE) extrae penúltima ub, cc, an
    
//    $x = $pdf->GetX();
//    $H = $pdf->GetY();
//    $height = $H-$y;    
    $pdf->SetXY($x+115,$y); 
       
    $oficinaDestino = $ultRE['desc_ubi_nivel_1'].' - '.$ultRE['desc_ubi_nivel_2'].' - '.$ultRE['ubi_descripcion'];
    
    $pdf->MultiCell(30, $height, $oficinaDestino, $b, 'C'); // desde $sql1 ($ultRE) extrae últ. UB, CC, AN
    
//    $x = $pdf->GetX();
//    $H = $pdf->GetY();
//    $height = $H-$y;    
    $pdf->SetXY($x+145,$y);
    // El String "\n\n\n\n" fuerza a la últ. columna a contar con 1 salto de línea más la representación de 3 filas.
    $pdf->MultiCell(25, $height, $data['aneg_descripcion']."\n\n\n\n", $b, 'C'); // desde $query ($data) muestra la Unidad
    
    mysqli_free_result($res1);
    mysqli_free_result($res2);
}

mysqli_free_result($resGeneral);

$pdf->Ln(5);

$pdf->SetFont('Arial','',9);

$pdf->MultiCell(0, 5, '2.- La Unidad de Inventarios, dependiente del Departamento de Gestión de Abastecimiento efectuará las regularizaciones necesarias en los registros correspondientes.');
$pdf->Ln(5);
//$pdf->Write($h, '3.- Linea texto disponible....');
//$pdf->Ln(12);

$pdf->Cell(0, 5, 'ANOTESE, COMUNIQUESE Y ARCHIVESE.');
$pdf->Ln(12);

// Firmas y Timbres
$pdf->SetFont('Arial','B',9);
$h=5;
$pdf->Cell(80, $h, $org['dtras_porOrdenFirma1'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['dtras_porOrdenFirma2'], 0, 1, 'C');        
$pdf->Cell(80, $h, $org['dtras_nombreSecre'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['dtras_nombreAlcalde'], 0, 1, 'C');    
$pdf->Cell(80, $h, $org['dtras_cargoFirma1'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['dtras_cargoFirma2'], 0, 0, 'C');
$pdf->Ln(8);

$pdf->SetFont('Arial','I',9);
$pdf->Cell(0, 5, $org['dtras_iniciales']);
$pdf->Ln(7);

$w=0; $h=5; $b=0;
$pdf->SetFont('Arial','I',9);
$pdf->Cell($w, $h, 'DISTRIBUCIÓN:', 0, 1);

$pdf->SetFont('Arial','',9);

foreach( $distribucion as $destino ){
    $pdf->Cell($w, $h, $destino, 0, 1);
}

// Genera PDF según $opc
if( $opc == 'preview' ){
    // Sale PDF con todos los decretos en "vista previa"
    $pdf->Output('I', 'Decreto_de_Traslado_N_'.$folio.'.pdf');
}else{
    
	if($opc2 == "")
	{
		
		foreach( $ids as $key => $actID ){
			// Registra trazabilidad de núm. dectreto => activo
			$sql = "INSERT INTO decretos_traslado(tras_id, tras_folio, reub_id, act_id, tras_creador) VALUES(NULL, '".$folio."','".$ids_reub[$key]. 
											"','".$actID."', '".$userID."')";
											
			$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
			
			/*$sql = "UPDATE reubicaciones_activos SET 
			reub_decretado=1, reub_modificador='".$userID."', reub_modificacion='".$fecha_modificacion."' WHERE reub_id = ".$ids_reub[$key]." LIMIT 1";
			$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));    */
		}
		
		//se unen todos los string que forman el arreglo $Vistos, estos seran separados con el elemento .|
		$stringVistos = "";
		foreach( $vistos as $item )
		{
			if($stringVistos == "") $stringVistos = $item;
			else{
				$stringVistos .= ".|" . $item;
			}	
		}	
	
		//se unen todos los string que forman el arreglo $Distribucion, estos seran separados con el elemento .|
		$stringDistrib = "";
		foreach( $distribucion as $destino )
		{
			if($stringDistrib == "") $stringDistrib = $destino;
			else{
				$stringDistrib .= ".|" . $destino;
			}	
		}	
		
		$sql = "INSERT INTO datos_decreto_traslado (tras_folio, dadetr_nombreOrg, dadetr_nombreDireccion, 
											dadetr_nombreSecre, dadetr_porOrdenFirma1, dadetr_cargoFirma1,dadetr_nombreAlcalde,
											dadetr_porOrdenFirma2, dadetr_cargoFirma2, dadetr_iniciales, dadetr_rutaLogo,
											dadetr_descripDistri, dadetr_descripVistos, dadetr_creador)
									VALUES ('".$folio."', '".$org['dtras_nombreOrg']."', '".$org['dtras_nombreDireccion']."',
											'".$org['dtras_nombreSecre']."', '".$org['dtras_porOrdenFirma1']."', 
											'".$org['dtras_cargoFirma1']."','".$org['dtras_nombreAlcalde']."',
											'".$org['dtras_porOrdenFirma2']."', '".$org['dtras_cargoFirma2']."', 
											'".$org['dtras_iniciales']."', '".$org['dtras_rutaLogo']."',
											'".$stringDistrib."', '".$stringVistos."', '".$userID."')";
	
		$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
 
	}
	
		
    // Sale PDF con todos los decretos en "modo descarga"
    $pdf->Output('D', 'Decreto_de_Traslado_N_'.$folio.'.pdf');
    // Guarda copia del PDF en "directorio local"
    $pdf->Output('F', $raiz.$ruta.'Decreto_de_Traslado_N_'.$folio.'.pdf');
}

mysqli_close($conexion);
}
else
{
    exit("No existen datos para ser procesados...");
}
?>