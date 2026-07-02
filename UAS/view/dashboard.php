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

$search = isset($_GET['search']) ? $_GET['search'] : "";
$jobs = $jobModel->read($search);

// Logika mengambil data untuk mode Edit
$editData = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $query = "SELECT * FROM lowongan WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editData = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Portal Lowongan Kerja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="text-center fw-bold mb-4">Portal Karir</h4>
                <p class="text-center small opacity-75">Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?></p>
                <hr>
                <ul class="nav flex-column mb-auto">
                    <li class="nav-item"><a href="dashboard.php" class="nav-link active">🏠 Dashboard</a></li>
                    <li class="nav-item"><a href="laporan_pdf.php" target="_blank" class="nav-link">📕 Cetak Laporan PDF</a></li>
                    <li class="nav-item"><a href="laporan.php" class="nav-link">📊 Cetak Laporan Excel</a></li>
                    
                    <li class="nav-item mt-4 px-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="darkModeToggle" style="cursor: pointer;">
                            <label class="form-check-label small fw-bold text-white" for="darkModeToggle" id="themeLabel">🌙 Dark Mode</label>
                        </div>
                    </li>
                </ul>
                <hr>
                <a href="../controller/AuthController.php?action=logout" class="btn btn-danger w-100 btn-sm fw-bold">🚪 Logout</a>
            </div>

            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4 fw-bold text-secondary">Manajemen Lowongan Pekerjaan</h2>

                <?php if(isset($_GET['status'])): ?>
                    <?php if($_GET['status'] == 'added'): ?>
                        <div class="alert alert-success">Data lowongan berhasil ditambahkan!</div>
                    <?php elseif($_GET['status'] == 'updated'): ?>
                        <div class="alert alert-success">Data lowongan berhasil diperbarui!</div>
                    <?php elseif($_GET['status'] == 'deleted'): ?>
                        <div class="alert alert-warning">Data lowongan berhasil dihapus!</div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="d-flex gap-2 mb-4">
                    <button type="button" id="btnInputView" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                        ➕ Menu Input Data
                    </button>
                    <button type="button" id="btnTableView" class="btn btn-outline-primary px-4 py-2 fw-bold shadow-sm">
                        📋 Menu Lihat Lowongan
                    </button>
                </div>

                <div id="sectionInputData" class="card p-4 shadow-sm border-0 mb-4">
                    <h5 class="fw-bold text-primary mb-3"><?php echo $editData ? '✏️ Edit Data Lowongan' : '➕ Tambah Data Lowongan Baru'; ?></h5>
                    
                    <?php $formAction = $editData ? "../controller/JobController.php?action=update" : "../controller/JobController.php?action=create"; ?>
                    <form action="<?php echo $formAction; ?>" method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="small fw-bold mb-1">ID Lowongan / NIM</label>
                                <input type="text" name="id_lowongan" class="form-control" placeholder="Isi NIM pada baris ke-1" value="<?php echo $editData ? htmlspecialchars($editData['id']) : ''; ?>" <?php echo $editData ? 'readonly' : 'required'; ?>>
                                <?php if($editData): ?><span class="text-danger" style="font-size:11px;">*ID dikunci</span><?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small fw-bold mb-1">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan" class="form-control" value="<?php echo $editData ? htmlspecialchars($editData['nama_perusahaan']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small fw-bold mb-1">Posisi Jabatan</label>
                                <input type="text" name="posisi" class="form-control" value="<?php echo $editData ? htmlspecialchars($editData['posisi']) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="small fw-bold mb-1">Estimasi Gaji (Rp)</label>
                                <input type="number" name="gaji" class="form-control" value="<?php echo $editData ? htmlspecialchars($editData['gaji']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small fw-bold mb-1">Lokasi Kerja</label>
                                <input type="text" name="lokasi" class="form-control" value="<?php echo $editData ? htmlspecialchars($editData['lokasi']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small fw-bold mb-1">Logo Perusahaan</label>
                                <input type="file" name="logo" class="form-control">
                                <?php if($editData): ?>
                                    <span class="text-muted d-block mt-1" style="font-size:11px;">File aktif: <?php echo $editData['logo_perusahaan']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="text-end mt-2">
                            <?php if($editData): ?>
                                <a href="dashboard.php" class="btn btn-secondary px-4 fw-bold me-2">Batal</a>
                            <?php endif; ?>
                            <button type="submit" class="btn <?php echo $editData ? 'btn-success' : 'btn-primary'; ?> px-5 fw-bold">
                                <?php echo $editData ? 'Perbarui Data' : 'Simpan Lowongan'; ?>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="sectionTableData" style="display: none;">
                    
                    <div class="card p-3 mb-3 shadow-sm border-0">
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama perusahaan atau lokasi kerja..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-secondary px-4 fw-bold">Cari</button>
                        </form>
                    </div>

                    <div class="table-responsive bg-white rounded p-3 shadow-sm">
                        <table class="table table-bordered align-middle text-center mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 12%;">ID/NIM</th>
                                    <th style="width: 8%;">Logo</th>
                                    <th style="width: 25%;">Nama Perusahaan</th>
                                    <th style="width: 20%;">Posisi Jabatan</th>
                                    <th style="width: 15%;">Estimasi Gaji</th>
                                    <th style="width: 10%;">Lokasi</th>
                                    <th style="width: 10%;">Aksi Pengelola</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($jobs && $jobs->num_rows > 0): ?>
                                    <?php while ($row = $jobs->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-bold text-primary"><?php echo $row['id']; ?></td>
                                        <td><img src="../uploads/<?php echo $row['logo_perusahaan']; ?>" width="45" height="45" class="img-thumbnail" onerror="this.src='../uploads/default.png'"></td>
                                        <td class="text-start"><strong><?php echo $row['nama_perusahaan']; ?></strong></td>
                                        <td class="text-start"><?php echo $row['posisi']; ?></td>
                                        <td class="text-end fw-semibold">Rp <?php echo number_format($row['gaji'], 0, ',', '.'); ?></td>
                                        <td><span class="badge bg-light text-dark border px-2 py-1"><?php echo $row['lokasi']; ?></span></td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="dashboard.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning fw-bold text-white px-2">Edit</a>
                                                <a href="../controller/JobController.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger fw-bold px-2" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data lowongan pekerjaan yang tersedia.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>

    <script>
        const btnInputView = document.getElementById('btnInputView');
        const btnTableView = document.getElementById('btnTableView');
        const sectionInputData = document.getElementById('sectionInputData');
        const sectionTableData = document.getElementById('sectionTableData');

        // Fungsi Membuka Menu Input Data
        function showInputView() {
            sectionInputData.style.display = 'block';
            sectionTableData.style.display = 'none';
            btnInputView.className = 'btn btn-primary px-4 py-2 fw-bold shadow-sm';
            btnTableView.className = 'btn btn-outline-primary px-4 py-2 fw-bold shadow-sm';
            localStorage.setItem('activeDashboardTab', 'input');
        }

        // Fungsi Membuka Menu Lihat Lowongan
        function showTableView() {
            sectionInputData.style.display = 'none';
            sectionTableData.style.display = 'block';
            btnInputView.className = 'btn btn-outline-primary px-4 py-2 fw-bold shadow-sm';
            btnTableView.className = 'btn btn-primary px-4 py-2 fw-bold shadow-sm';
            localStorage.setItem('activeDashboardTab', 'table');
        }

        btnInputView.addEventListener('click', showInputView);
        btnTableView.addEventListener('click', showTableView);

        // Otomatisasi agar saat dalam mode EDIT atau PENCARIAN CARI DATA, tab yang benar tetap terbuka setelah reload
        <?php if ($editData || !empty($search)): ?>
            <?php if (!empty($search)): ?>
                showTableView();
            <?php else: ?>
                showInputView();
            <?php endif; ?>
        <?php else: ?>
            // Cek pilihan tab terakhir pengguna di memori browser
            const savedTab = localStorage.getItem('activeDashboardTab');
            if (savedTab === 'table') {
                showTableView();
            } else {
                showInputView();
            }
        <?php endif; ?>
    </script>
</body>
</html>