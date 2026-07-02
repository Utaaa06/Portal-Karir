<?php if(isset($_GET['status'])): ?>
    <?php if($_GET['status'] == 'username_taken'): ?>
        <div class='alert alert-danger small py-2'>⚠️ Username sudah digunakan! Silakan gunakan nama lain.</div>
    <?php elseif($_GET['status'] == 'password_too_short'): ?>
        <div class='alert alert-warning small py-2'>⚠️ Password terlalu pendek! Minimal harus 6 karakter.</div>
    <?php elseif($_GET['status'] == 'failed'): ?>
        <div class='alert alert-danger small py-2'>Gagal mendaftarkan akun.</div>
    <?php endif; ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="card mx-auto" style="width: 400px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="card-body p-4">
            <h3 class="text-center mb-4 fw-bold text-secondary">Registrasi Akun</h3>
            <form action="../controller/AuthController.php?action=register" method="POST">
                <div class="mb-3"><label class="form-label small fw-bold">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" required></div>
                <div class="mb-3"><label class="form-label small fw-bold">Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="mb-3"><label class="form-label small fw-bold">Password</label><input type="password" name="password" class="form-control" minlength="6" placeholder="Minimal 6 karakter" required></div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2">Daftar Akun</button>
                <p class="text-center mt-3 small">Sudah punya akun? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>