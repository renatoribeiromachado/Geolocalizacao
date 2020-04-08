<?php
class gMaps {
  private $mapsKey;
  function __construct($key = null) {
    if (!is_null($key)) {
      $this->mapsKey = $key;
    }
  }
  function carregaUrl($url) {
    if (function_exists('curl_init')) {
      $cURL = curl_init($url);
      curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
      $resultado = curl_exec($cURL);
      curl_close($cURL);
    } else {
      $resultado = file_get_contents($url);
    }
    if (!$resultado) {
      trigger_error('Não foi possível carregar o endereço: <strong>' . $url . '</strong>');
    } else {
      return $resultado;
    }
  }
  function geoLocal($endereco) {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?key={$this->mapsKey}&address=" . urlencode($endereco);
    $data = json_decode($this->carregaUrl($url));
    
    if ($data->status === 'OK') {
      return $data->results[0]->geometry->location;
    } else {
      return false;
    }
  }
}
// Instancia a classe
$gmaps = new gMaps('');//Coloque sua Key
// Pega os dados (latitude, longitude e zoom) do endereço:
$endereco = 'Santo André';
$dados = $gmaps->geoLocal($endereco);
// Exibe os dados encontrados:
echo $dados->lat. "<br>";
echo $dados->lng;
    

 
