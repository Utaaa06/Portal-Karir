<?php
class Job {
    private $conn;
    private $table = "lowongan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($search = "") {
        if ($search != "") {
            $query = "SELECT * FROM " . $this->table . " WHERE nama_perusahaan LIKE ? OR lokasi LIKE ? ORDER BY id DESC";
            $stmt = $this->conn->prepare($query);
            $searchParam = "%" . $search . "%";
            $stmt->bind_param("ss", $searchParam, $searchParam);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
            return $this->conn->query($query);
        }
    }

    public function create($id, $nama_perusahaan, $posisi, $gaji, $lokasi, $logo) {
        $query = "INSERT INTO " . $this->table . " (id, nama_perusahaan, posisi, gaji, lokasi, logo_perusahaan) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssisss", $id, $nama_perusahaan, $posisi, $gaji, $lokasi, $logo);
        return $stmt->execute();
    }

    // 🛠️ UPDATE JIKA GANTI LOGO
    public function update($id, $nama_perusahaan, $posisi, $gaji, $lokasi, $logo) {
        $query = "UPDATE " . $this->table . " SET nama_perusahaan = ?, posisi = ?, gaji = ?, lokasi = ?, logo_perusahaan = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssisss", $nama_perusahaan, $posisi, $gaji, $lokasi, $logo, $id);
        return $stmt->execute();
    }

    // 🛠️ UPDATE JIKA TIDAK GANTI LOGO (Mengatasi error updateWithoutLogo)
    public function updateWithoutLogo($id, $nama_perusahaan, $posisi, $gaji, $lokasi) {
        $query = "UPDATE " . $this->table . " SET nama_perusahaan = ?, posisi = ?, gaji = ?, lokasi = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssiss", $nama_perusahaan, $posisi, $gaji, $lokasi, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id);
        return $stmt->execute();
    }
}
?>