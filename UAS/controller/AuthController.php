<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Auth.php';

class AuthController {
    private $db;
    private $auth;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->auth = new Auth($this->db);
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = $_POST['nama_lengkap'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // 🛑 1. Validasi Panjang Password (Minimal 6 Karakter)
            if (strlen($password) < 6) {
                header("Location: ../view/register.php?status=password_too_short");
                exit();
            }

            // 🛑 2. Validasi Username Ganda (Mencegah Duplicate Entry)
            if ($this->auth->checkUsernameExists($username)) {
                header("Location: ../view/register.php?status=username_taken");
                exit();
            }
            
            // 3. Eksekusi Pendaftaran
            if ($this->auth->register($nama, $username, $password)) {
                header("Location: ../view/login.php?status=success_reg");
            } else {
                header("Location: ../view/register.php?status=failed");
            }
            exit();
        }
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->auth->login($username, $password);
            if ($user) {
                $_SESSION['user'] = $user['username'];
                $_SESSION['nama'] = $user['nama_lengkap'];
                header("Location: ../view/dashboard.php");
            } else {
                header("Location: ../view/login.php?status=wrong");
            }
            exit();
        }
    }

    public function handleLogout() {
        session_destroy();
        header("Location: ../view/login.php");
        exit();
    }
}

$authCtrl = new AuthController();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'register') $authCtrl->handleRegister();
    if ($_GET['action'] == 'login') $authCtrl->handleLogin();
    if ($_GET['action'] == 'logout') $authCtrl->handleLogout();
}
?>