<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/Database.php';
require_once '../model/Job.php';

$database = new Database();
$db = $database->getConnection();
$jobModel = new Job($db);
$jobs = $jobModel->read();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Lowongan Kerja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            padding: 30px; 
            background-color: white;
        }
        .kop-surat { 
            text-align: center; 
            border-bottom: 3px double #000; 
            padding-bottom: 10px; 
            margin-bottom: 30px; 
        }
        .kop-surat h3 { margin: 0; font-weight: bold; font-size: 18px; }
        .kop-surat h5 { margin: 5px 0; font-weight: bold; font-size: 14px; }
        .kop-surat p { margin: 0; font-size: 11px; font-style: italic; }
        
        @media print {
            body { padding: 0; margin: 0; }
            @page { size: A4; margin: 1.5cm; }
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h3>UNIVERSITAS PAMULANG</h3>
        <h5>FAKULTAS ILMU KOMPUTER - SISTEM INFORMASI S-1</h5>
        <p>Jl. Puspitek Raya No. 10, Serpong, Tangerang Selatan | Telp. (021) 742 7010</p>
    </div>

    <h5 class="text-center mb-4 text-uppercase fw-bold">Laporan Data Lowongan Pekerjaan</h5>
    
    <div class="d-flex justify-content-between mb-3" style="font-size: 12px;">
        <span><strong>Dicetak Oleh:</strong> <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <span><strong>Tanggal Cetak:</strong> <?php echo date('d-m-Y H:i'); ?></span>
    </div>

    <table class="table table-bordered align-middle" style="font-size: 13px;">
        <thead class="table-light text-center">
            <tr>
                <th style="width: 20%;">ID / NIM</th>
                <th style="width: 25%;">Nama Perusahaan</th>
                <th style="width: 20%;">Posisi Jabatan</th>
                <th style="width: 20%;">Estimasi Gaji</th>
                <th style="width: 15%;">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($jobs && $jobs->num_rows > 0): ?>
                <?php while ($row = $jobs->fetch_assoc()): ?>
                <tr>
                    <td class="text-center fw-bold"><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?></td>
                    <td><?php echo htmlspecialchars($row['posisi']); ?></td>
                    <td class="text-end">Rp <?php echo number_format($row['gaji'], 0, ',', '.'); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['lokasi']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center text-muted">Tidak ada data lowongan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="row mt-5" style="font-size: 13px;">
        <div class="col-12 text-end">
            <p>Tangerang Selatan, <?php echo date('d F Y'); ?></p>
            <br><br><br>
            <p class="fw-bold" style="text-decoration: underline;">( <?php echo htmlspecialchars($_SESSION['nama']); ?> )</p>
            <p class="text-muted" style="font-size: 11px; margin-top: -15px;">Mahasiswa</p>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
        });
    </script>
</body>
</html>