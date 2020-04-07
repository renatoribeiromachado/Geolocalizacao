<?php
    $estado = filter_input(INPUT_POST, 'Estado', FILTER_DEFAULT);
    $cidade = filter_input(INPUT_POST, 'Cidade', FILTER_DEFAULT);
    require_once "conexao.php";
    $pdo = conectar();
    if(empty($cidade)){
        header("Location:index.php?exe=return");
    }else{         
        $read = $pdo->prepare("SELECT obr.id, 
                                    obr.Endereco,
                                    geo.idAdress
                                    FROM tb_obras_obr AS obr 
                                    LEFT JOIN tb_geolocation_geo AS geo
                                    ON geo.idAdress = obr.id
                                    WHERE obr.Cidade = '".$cidade."'
                                    AND obr.INDSTATUS = 1
                                    AND obr.IdFase != 3
                                    GROUP BY obr.id");
        $read->execute();
    
        require_once "class/Maps.php";
        $gmaps = new Maps('AIzaSyAjN4QPiqR4h-Aq0ebGfm5RMfba369rmZo');
        foreach($read->fetchAll(PDO::FETCH_ASSOC) AS $res){
            if($res['idAdress'] != $res['id']){
                $adress = $res['Endereco']. ' - ' .$cidade;
                $dados  = $gmaps->geoLocal($adress);
                $insert = $pdo->prepare("INSERT INTO tb_geolocation_geo (idAdress,latitude,longitude) VALUES (:id,:lat,:lng)");
                $insert->bindParam(":id",$res['id']);
                $insert->bindParam(":lat",$dados->lat);
                $insert->bindParam(":lng",$dados->lng);
                $insert->execute();
            }
        }
?>
<!DOCTYPE html >
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="shortcut icon" href="https://www.acessohost.com.br/favicon/favicon.png" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="css/ui-lightness/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/reset.css" rel="stylesheet"/>
    <link href="css/admin.css" rel="stylesheet"/>
    <title>Obras Por Localização</title>
    <style>
        #map {
          height: 100%;
        }
        html, body {
          height: 100%;
          margin: 0;
          padding: 0;
        }
    </style>
</head>
<html>
    <body>
        <div class="container">
            <p class="text-success text-center"><i class="glyphicon glyphicon-alert"></i> Total de <span class="badge badge-secondary"><?= $read->rowCount();?></span> Obra(s) encontrada(s) para:<br>
            <i class="glyphicon glyphicon-map-marker"></i> Cidade: <b><?= $cidade;?></b></p>
            <p class="text-success text-center"><a href="index.php" class="btn btn-success" title="Fazer nova pesquisa"><i class="glyphicon glyphicon-search"></i> Fazer nova Pesquisa</a></p>    
        </div>
        <!--GOOGLE MAPS-->
        <div id="map"></div>
        <?php
            $Cidade = $cidade. " - " .$estado;
            $dados  = $gmaps->geoLocal($Cidade);
            }
        ?>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            var customLabel = {
                restaurant: {
                    label: 'R'
                },
                bar: {
                    label: 'B'
                }
            };
            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(<?= $dados->lat;?>,<?= $dados->lng;?>),
                zoom: 15
            });
                var infoWindow = new google.maps.InfoWindow;
                // Change this depending on the name of your PHP or XML file
                downloadUrl('getResult.php?cidade=<?= $cidade;?>', function(data) {
                    var xml = data.responseXML;
                    var markers = xml.documentElement.getElementsByTagName('marker');
                    Array.prototype.forEach.call(markers, function(markerElem) {
                    var id = markerElem.getAttribute('id');
                    var name = markerElem.getAttribute('Projeto');
                    var address = markerElem.getAttribute('Endereco');
                    var type = markerElem.getAttribute('type');
                    var point = new google.maps.LatLng(
                        parseFloat(markerElem.getAttribute('lat')),
                        parseFloat(markerElem.getAttribute('lng')));

                    var infowincontent = document.createElement('div');
                    var strong = document.createElement('strong');
                    strong.textContent = name
                    infowincontent.appendChild(strong);
                    infowincontent.appendChild(document.createElement('br'));
                    
                    var text = document.createElement('text');
                    text.textContent = address
                    infowincontent.appendChild(text);
                    var icon = customLabel[type] || {};
                    var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    label: icon.label
                    });
                    marker.addListener('click', function() {
                        infoWindow.setContent(infowincontent);
                        infoWindow.open(map, marker);
                        });
                    });
                });
            }
            function downloadUrl(url, callback) {
                var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

                request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                } 
                };
                request.open('GET', url, true);
                request.send(null);
            }
            function doNothing() {}
        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjN4QPiqR4h-Aq0ebGfm5RMfba369rmZo&callback=initMap">
        </script>
    </body>
</html>