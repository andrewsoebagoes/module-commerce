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


function getCities($provinceId, $apiKey = null)
{
    // Gunakan API key dari parameter atau gunakan default dari environment variable
    if ($apiKey === null) {
        $apiKey = env('RAJA_ONGKIR_KEY');
    }

    // Inisialisasi curl
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $provinceId,
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

    // Jalankan curl dan ambil respons
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    // Periksa jika ada error
    if ($err) {
        throw new Exception("cURL Error: " . $err);
    }

    // Decode data JSON
    $data = json_decode($response);

    // Periksa jika ada masalah dengan data yang diterima
    if (!isset($data->rajaongkir->results)) {
        throw new Exception("Invalid response format from Raja Ongkir API");
    }

    // Siapkan array untuk hasil kota
    $cities = [];

    // Iterasi melalui hasil dan tambahkan kota ke dalam array
    foreach ($data->rajaongkir->results as $kabupaten) {
        $cities[] = [
            'id' => $kabupaten->city_id,
            'name' => $kabupaten->city_name
        ];
    }

    // Kembalikan array kota
    return $cities;
}

// Contoh penggunaan fungsi
try {
    // Ambil ID provinsi dari permintaan (Anda dapat mengubah ini sesuai kebutuhan Anda)
    $provinceId = $_GET['id_provinsi'];

    // Ambil data kota berdasarkan ID provinsi
    $cities = getCities($provinceId);
    
    // Cetak opsi kota
    echo '<option value="">Pilih Kabupaten</option>';
    foreach ($cities as $city) {
        echo '<option value="'.$city['id'].'-'.$city['name'].'">'.$city['name'].'</option>';
    }
} catch (Exception $e) {
    // Tangani error (misalnya: log error atau tampilkan pesan)
    echo 'Error: ' . $e->getMessage();
}
