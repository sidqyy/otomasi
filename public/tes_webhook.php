<?php
$data = json_decode(file_get_contents('php://input'), true); 

$number   = $data["from"] ?? null;
$message  = $data["message"] ?? null;

if (!$number || !$message) {
    exit;
}

// Bikin log kedatangan pesan
$incomingLog = date('Y-m-d H:i:s') . " - PESAN MASUK DARI $number: $message\n";
file_put_contents(__DIR__ . '/tes_log.txt', $incomingLog, FILE_APPEND);

if (strtoupper($message) == 'MACBOOK') {
    $msg = 'MACBOOK PRO M1 Harga Rp. 20.999.000';
    $file = "https://cdn.eraspace.com/pub/media/catalog/product/m/a/macbook_pro_m1_space_gray_1_2.jpg";
    sendMessage($number, $msg, $file);
} else {
    $msg = 'Native PHP sukses membaca: ' . $message;
    sendMessage($number, $msg, null);
}

function sendMessage($number, $message, $file) {
    $url = 'https://app.whacenter.com/api/send';
    $ch = curl_init($url);
    
    $data = array(
        'device_id' => '9fe5f0d7-34e4-4924-8ea2-3e95dfcccec3', // API Key Anda yang terbaru
        'number' => $number,
        'message' => $message,
    );
    
    if ($file) {
        $data['file'] = $file;
    }
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    
    // Simpan respon dari Whacenter ke log
    file_put_contents(__DIR__ . '/tes_log.txt', "RESPON WHACENTER: " . $result . "\n-------------------------\n\n", FILE_APPEND);
}
?>
