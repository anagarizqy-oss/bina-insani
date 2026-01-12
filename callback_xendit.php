<?php
// callback_xendit.php (Root Directory or public accessible)
// NOTE: This file must be reachable by Xendit servers. 
// For localhost, you might need ngrok.

include 'config/db.php';

// Get JSON Body
$json_str = file_get_contents('php://input');
$data = json_decode($json_str, true);

// Verify Token? (Optional but recommended - verify X-CALLBACK-TOKEN header)
// $callback_token = $_SERVER['HTTP_X_CALLBACK_TOKEN'] ?? '';
// if ($callback_token !== 'YOUR_XENDIT_CALLBACK_TOKEN') { http_response_code(403); exit; }

if (isset($data['status']) && isset($data['external_id'])) {
    $status = $data['status'];
    $external_id = $data['external_id'];

    // Parse ID from "SPP-{id}-{timestamp}"
    $parts = explode('-', $external_id);
    if (count($parts) >= 2 && $parts[0] == 'SPP') {
        $spp_id = (int)$parts[1];

        if ($status == 'PAID' || $status == 'SETTLED') {
            $paid_at = $data['paid_at'] ?? date('c'); // ISO 8601

            // Update DB
            $stmt = $pdo->prepare("UPDATE spp SET status = 'Paid', tanggal_bayar = ? WHERE id = ?");
            $today = date('Y-m-d H:i:s', strtotime($paid_at));
            $stmt->execute([$today, $spp_id]);

            echo json_encode(["message" => "Payment successfully recorded for SPP ID $spp_id"]);
        } else {
            // Handle Expired?
            if ($status == 'EXPIRED') {
                $stmt = $pdo->prepare("UPDATE spp SET status = 'Expired' WHERE id = ?");
                $stmt->execute([$spp_id]);
            }
            echo json_encode(["message" => "Status updated to $status"]);
        }
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Invalid payload"]);
}
