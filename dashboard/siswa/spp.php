<?php
// dashboard/siswa/spp.php
include '../../includes/auth.php';
include '../../config/db.php';
must_be(['siswa']);

$user_id = $_SESSION['user_id'];

// Get Student ID
$stmt = $pdo->prepare("SELECT id FROM siswa WHERE user_id = ?");
$stmt->execute([$user_id]);
$siswa = $stmt->fetch();
$siswa_id = $siswa['id'];

// Fetch SPP Bills
$stmt_spp = $pdo->prepare("SELECT * FROM spp WHERE siswa_id = ? ORDER BY id ASC");
$stmt_spp->execute([$siswa_id]);
$bills = $stmt_spp->fetchAll();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="profile">
            <span>Tagihan SPP</span>
        </div>
    </div>

    <div class="page-content">
        <h2 class="section-title">Tagihan SPP Anda</h2>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'failed'): ?>
            <div class="alert error">Pembayaran gagal atau dibatalkan.</div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($bills) > 0): ?>
                <div class="table-container">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left;">Bulan / Tahun</th>
                                <th style="padding: 12px; text-align: left;">Jumlah</th>
                                <th style="padding: 12px; text-align: center;">Status</th>
                                <th style="padding: 12px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bills as $row): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; font-weight: 500;">
                                        <?= htmlspecialchars($row['bulan']) ?> <?= htmlspecialchars($row['tahun']) ?>
                                    </td>
                                    <td style="padding: 12px; color: #555;">
                                        Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <?php if ($row['status'] == 'Paid'): ?>
                                            <span class="badge success">LUNAS</span>
                                            <div style="font-size: 0.8rem; color: #666; margin-top: 5px;">
                                                <?= date('d M Y H:i', strtotime($row['tanggal_bayar'])) ?>
                                            </div>
                                        <?php elseif ($row['status'] == 'Expired'): ?>
                                            <span class="badge danger">EXPIRED</span>
                                        <?php else: ?>
                                            <span class="badge warning">BELUM LUNAS</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <?php if ($row['status'] == 'Unpaid'): ?>
                                            <a href="process_payment.php?id=<?= $row['id'] ?>" class="btn-import" style="text-decoration: none; padding: 8px 15px; background: #2575fc; color: white; border-radius: 4px; font-size: 0.9rem;">
                                                <i class="fas fa-credit-card"></i> Bayar Sekarang
                                            </a>
                                        <?php elseif ($row['status'] == 'Paid'): ?>
                                            <a href="#" style="color: #2e7d32; text-decoration: none;">
                                                <i class="fas fa-check-circle"></i> Selesai
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="padding: 30px; text-align: center; color: #888;">
                    Belum ada tagihan SPP.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>

</html>