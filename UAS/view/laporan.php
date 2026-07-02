<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Lowongan_Pekerjaan.xls");

require_once '../config/Database.php';
require_once '../model/Job.php';

$database = new Database();
$db = $database->getConnection();
$jobModel = new Job($db);
$jobs = $jobModel->read();
?>
<table border="1">
    <thead>
        <tr>
            <th>ID/NIM</th>
            <th>Nama Perusahaan</th>
            <th>Posisi</th>
            <th>Gaji</th>
            <th>Lokasi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $jobs->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nama_perusahaan']; ?></td>
            <td><?php echo $row['posisi']; ?></td>
            <td><?php echo $row['gaji']; ?></td>
            <td><?php echo $row['lokasi']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>