<?php
    header("Content-type: text/xml");
    function parseToXML($htmlStr){
        $xmlStr = str_replace('<','&lt;',$htmlStr);
        $xmlStr = str_replace('>','&gt;',$xmlStr);
        $xmlStr = str_replace('"','&quot;',$xmlStr);
        $xmlStr = str_replace("'",'&#39;',$xmlStr);
        $xmlStr = str_replace("&",'&amp;',$xmlStr);
        return $xmlStr;
    }
    require_once "conexao.php";
    $pdo    = conectar();
    $cidade = filter_input(INPUT_GET, 'cidade', FILTER_DEFAULT);          
    $read   = $pdo->prepare("SELECT obr.id,
                                    obr.CodigoAntigo,
                                    obr.Projeto, 
                                    obr.DescProj1,
                                    obr.Endereco, 
                                    obr.numero,
                                    geo.idAdress,
                                    geo.latitude,
                                    geo.longitude
                                    FROM tb_obras_obr AS obr 
                                        LEFT JOIN tb_geolocation_geo AS geo
                                    ON geo.idAdress = obr.id
                                    WHERE obr.Cidade = '".$cidade."'
                                    AND obr.INDSTATUS = 1
                                    AND obr.IdFase != 3");
    $read->execute();

    //Start XML file, echo parent node
    echo '<markers>';
       foreach($read->fetchAll(PDO::FETCH_ASSOC) AS $res){
            // Add to XML document node
            echo '<marker ';
                echo 'Projeto ="' . parseToXML($res['CodigoAntigo']) . ' - ' . parseToXML($res['Projeto']) . ' - ' . parseToXML($res['DescProj1']) .'" ';
                echo 'Endereco ="' . parseToXML($res['Endereco']) . ' ' . $res['numero'] .'" ';
                echo 'lat="' . $res['latitude']. '" ';
                echo 'lng="' . $res['longitude'] . '" ';
            echo '/>';
        }
    echo '</markers>';