<?php
// dashboard/siswa/process_payment.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];
$spp_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$spp_id) die("Invalid Request");

// 1. Get Bill Info & Student Info
$stmt = $pdo->prepare("
    SELECT s.*, si.nama_lengkap, si.nis 
    FROM spp s
    JOIN siswa si ON s.siswa_id = si.id
    WHERE s.id = ? AND si.user_id = ?
");
$stmt->execute([$spp_id, $user_id]);
$bill = $stmt->fetch();

if (!$bill) die("Tagihan tidak ditemukan atau bukan milik Anda.");

if ($bill['status'] == 'Paid') {
    header("Location: spp.php");
    exit;
}

// 2. Check if Invoice already exists and is active?
// For simplicity, we create a new one if not paid, or reuse if URL exists/check status?
// Let's create a new one for now to avoid expiration issues, or check if URL exists.
// Ideally, check status first. But let's assume we create a unique external_id each time or reuse.

$external_id = "SPP-" . $bill['id'] . "-" . time(); // Unique ID

// 3. Xendit API Config
$api_key = 'xnd_development_T2bAs3l8hvAx59jVOoL6QqV81vCxSbrmfwevtHlTk8GJvE7T7NDVV4OwqRMf'; // From API/api.text
$url = 'https://api.xendit.co/v2/invoices';

$data_request = [
    'external_id' => $external_id,
    'amount' => (int)$bill['jumlah'],
    'payer_email' => 'siswa' . $bill['siswa_id'] . '@binainsani.sch.id',
    'description' => "Pembayaran SPP " . $bill['bulan'] . " " . $bill['tahun'] . " - " . $bill['nama_lengkap'],
    'customer' => [
        'given_names' => $bill['nama_lengkap'],
        'email' => 'siswa' . $bill['siswa_id'] . '@binainsani.sch.id'
    ],
    'success_redirect_url' => 'http://localhost/sma-bina-insani/dashboard/siswa/spp.php?status=success',
    'failure_redirect_url' => 'http://localhost/sma-bina-insani/dashboard/siswa/spp.php?status=failed'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_request));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($api_key . ':')
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($http_code == 200 || $http_code == 201) {
    if (isset($result['invoice_url'])) {
        // Update DB
        $stmt_upd = $pdo->prepare("UPDATE spp SET xendit_invoice_id = ?, xendit_payment_url = ? WHERE id = ?");
        $stmt_upd->execute([$result['id'], $result['invoice_url'], $spp_id]);

        // Redirect
        header("Location: " . $result['invoice_url']);
        exit;
    }
}

// If failed
echo "Gagal membuat pembayaran. Error: <pre>" . print_r($result, true) . "</pre>";
