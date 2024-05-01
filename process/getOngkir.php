<?php
// $apiKey = env('RAJA_ONGKIR_KEY');
$apiKey = 'c7564e55cb13c4cb02583cbce83a0753';

// extract($_POST);
// Ambil data POST dari formulir
$ekspedisi  = $_POST['courier'];
$distrik    = explode('-', $_POST['destination']);
$berat      = 1000;
$origin     = env('CODE_KABUPATEN_ASAL'); 

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=$origin&destination=$distrik[0]&weight=$berat&courier=$ekspedisi",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: $apiKey"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #: " . $err;
} else {
    $data = json_decode($response, true);

    if (isset($data['rajaongkir']['results'][0]['costs'])) {
        $costs = $data['rajaongkir']['results'][0]['costs'];
        foreach ($costs as $cost) {
            echo '<option value="'.$cost['service']. '-' . $cost['cost'][0]['value'] .'">'.$cost['service']. ' - ' . number_format($cost['cost'][0]['value']) .'</option>';
        }
    } else {
        echo "Tidak ada data ongkos kirim yang tersedia.";
    }
}
?>
