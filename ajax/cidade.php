<?php
//    $Estado = filter_input(INPUT_GET, 'Estado', FILTER_DEFAULT);
//    $itens = array();

    $pdo = new PDO("mysql:host=localhost;dbname=intecbrasil_intec_bkp", "intecbrasil_intec", "010211admin123$#@!");
//    $read = $pdo->prepare("SELECT *FROM tb_cidades_cid WHERE uf = '$Estado' ORDER BY cidade");
//    $read->execute();
//    
//     foreach ($read->fetchAll(PDO::FETCH_ASSOC) as $rs){
//         echo "<option value='".$rs['id_cidade']."'>".$rs['cidade']."</option>";
//     } 
     
     	header( 'Cache-Control: no-cache' );
	header( 'Content-type: application/xml; charset="utf-8"', true );

//	$con = mysql_connect( 'localhost', 'root', 'root' ) ;
//	mysql_select_db( 'cadastro', $con );

	$cod_estados = mysql_real_escape_string( $_REQUEST['cod_estados'] );

	$cidades = array();

//	$sql = "SELECT cod_cidades, nome
//			FROM cidades
//			WHERE estados_cod_estados=$cod_estados
//			ORDER BY nome";
//        
        $read = $pdo->prepare("SELECT *FROM tb_cidades_cid WHERE uf = '$cod_estados' ORDER BY cidade");
        $read->execute();
//	while ( $row = mysql_fetch_assoc( $res ) ) {
//		$cidades[] = array(
//			'cod_cidades'	=> $row['cod_cidades'],
//			'nome'			=> $row['nome'],
//		);
//	}
        foreach ($read->fetchAll(PDO::FETCH_ASSOC) as $row){
         $cidades[] = array(
                        'id_cidade'=> $row['id_cidade'],
                        'nome'=> $row['cidade'],
                    );
        } 

	echo( json_encode($cidades));
        
