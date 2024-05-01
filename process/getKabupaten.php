<?php
$id_provinsi = explode('-', $_GET['id_provinsi']);
$curl = curl_init();
$apiKey = env('RAJA_ONGKIR_KEY');

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=".$id_provinsi[0],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "key: $apiKey"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$data = json_decode($response);

// echo '<option value="">Pilih Kabupaten</option>';
foreach($data->rajaongkir->results as $kabupaten){
    echo '<option value="'.$kabupaten->city_id.'-'.$kabupaten->city_name.'">'.$kabupaten->city_name.'</option>';

}