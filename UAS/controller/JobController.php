<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Job.php';

class JobController {
    private $db;
    private $job;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->job = new Job($this->db);
    }

    public function handleCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_lowongan'];
            $nama_perusahaan = $_POST['nama_perusahaan'];
            $posisi = $_POST['posisi'];
            $gaji = $_POST['gaji'];
            $lokasi = $_POST['lokasi'];
            
            // Logika upload file logo
            $logo = $_FILES['logo']['name'];
            $target = "../uploads/" . basename($logo);
            
            if (!empty($logo)) {
                move_uploaded_file($_FILES['logo']['tmp_name'], $target);
            } else {
                $logo = "default.png";
            }

            if ($this->job->create($id, $nama_perusahaan, $posisi, $gaji, $lokasi, $logo)) {
                header("Location: ../view/dashboard.php?status=added");
            } else {
                header("Location: ../view/dashboard.php?status=failed");
            }
            exit();
        }
    }

    public function handleUpdate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_lowongan'];
            $nama_perusahaan = $_POST['nama_perusahaan'];
            $posisi = $_POST['posisi'];
            $gaji = $_POST['gaji'];
            $lokasi = $_POST['lokasi'];
            
            $logo = $_FILES['logo']['name'];
            if (!empty($logo)) {
                $target = "../uploads/" . basename($logo);
                move_uploaded_file($_FILES['logo']['tmp_name'], $target);
                $this->job->update($id, $nama_perusahaan, $posisi, $gaji, $lokasi, $logo);
            } else {
                $this->job->updateWithoutLogo($id, $nama_perusahaan, $posisi, $gaji, $lokasi);
            }
            header("Location: ../view/dashboard.php?status=updated");
            exit();
        }
    }

    public function handleDelete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($this->job->delete($id)) {
                header("Location: ../view/dashboard.php?status=deleted");
            }
            exit();
        }
    }

    // 🔄 SEKARANG FUNGSI RESET PASSWORD INI SUDAH BERADA DI DALAM CLASS DENGAN BENAR
    public function handleResetPassword() {
        if (isset($_GET['id_user'])) {
            $id_user = $_GET['id_user'];
            $password_baru = "123456"; 
            $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);

            $query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("si", $hashed_password, $id_user);
            
            if ($stmt->execute()) {
                header("Location: ../view/dashboard.php?status=password_reset");
            } else {
                header("Location: ../view/dashboard.php?status=reset_failed");
            }
            exit();
        }
    }
} // 👈 Kurung kurawal penutup Class JobController WAJIB di sini!

// ==========================================
// BAGIAN PEMICU AKSI (DI LUAR CLASS)
// ==========================================
$jobCtrl = new JobController();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'create') $jobCtrl->handleCreate();
    if ($_GET['action'] == 'update') $jobCtrl->handleUpdate();
    if ($_GET['action'] == 'delete') $jobCtrl->handleDelete();
    if ($_GET['action'] == 'reset_password') $jobCtrl->handleResetPassword();
}
?>