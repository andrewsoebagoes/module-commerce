<?php 

function getProvinces($apiKey = null)
{
    // Gunakan API key dari parameter atau gunakan default dari environment variable
    if ($apiKey === null) {
        $apiKey = env('RAJA_ONGKIR_KEY');
    }

    // Inisialisasi curl
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
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

    // Siapkan array untuk hasil provinsi
    $provinces = [];

    // Iterasi melalui hasil dan tambahkan provinsi ke dalam array
    foreach ($data->rajaongkir->results as $provinsi) {
        $provinces[] = [
            'id' => $provinsi->province_id,
            'name' => $provinsi->province
        ];
    }

    // Kembalikan array provinsi
    return $provinces;
}

// Contoh penggunaan fungsi
try {
    $provinces = getProvinces();
    
    echo '<option value="">Pilih Provinsi</option>';
    foreach ($provinces as $provinsi) {
        echo '<option value="'.$provinsi['id'].'-'.$provinsi['name'].'">'.$provinsi['name'].'</option>';
    }
} catch (Exception $e) {
    // Tangani error (misalnya: log error atau tampilkan pesan)
    echo 'Error: ' . $e->getMessage();
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


function getShippingCost($origin, $destination, $weight, $courier, $apiKey = null)
{
    // Gunakan API key dari parameter atau gunakan default dari environment variable
    if ($apiKey === null) {
        $apiKey = env('RAJA_ONGKIR_KEY');
    }

    // Inisialisasi curl
    $curl = curl_init();

    // Persiapkan data POST
    $postData = http_build_query([
        'origin' => $origin,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => $courier,
    ]);

    // Set opsi cURL
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            "key: $apiKey"
        ),
    ));

    // Jalankan permintaan cURL
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Tutup koneksi cURL
    curl_close($curl);

    // Periksa apakah ada error
    if ($err) {
        throw new Exception("cURL Error: " . $err);
    }

    // Decode respons JSON
    $data = json_decode($response, true);

    // Periksa apakah data pengiriman tersedia
    if (isset($data['rajaongkir']['results'][0]['costs'])) {
        return $data['rajaongkir']['results'][0]['costs'];
    } else {
        return [];
    }
}

// Contoh penggunaan fungsi
try {
    $origin = env('CODE_KABUPATEN_ASAL');
    $destination = $_POST['destination'];
    $weight = $_POST['weight'];
    $courier = $_POST['courier'];
    $apiKey = env('RAJA_ONGKIR_KEY');

    // Panggil fungsi untuk mendapatkan biaya pengiriman
    $shippingCosts = getShippingCost($origin, $destination, $weight, $courier, $apiKey);

    if (!empty($shippingCosts)) {
        foreach ($shippingCosts as $cost) {
            echo '<option value="'.$cost['service'].'-'.$cost['cost'][0]['value'].'">'.$cost['service'].' - '.number_format($cost['cost'][0]['value']).'</option>';
        }
    } else {
        echo "Tidak ada data ongkos kirim yang tersedia.";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


?>