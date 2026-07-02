<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal Loker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="card mx-auto" style="width: 400px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="card-body p-4">
            <h3 class="text-center mb-4 fw-bold text-secondary">Login Pengguna</h3>
            <?php if(isset($_GET['status']) && $_GET['status'] == 'wrong') echo "<div class='alert alert-danger'>Username/Password salah!</div>"; ?>
            <?php if(isset($_GET['status']) && $_GET['status'] == 'success_reg') echo "<div class='alert alert-success'>Registrasi sukses! Silakan login.</div>"; ?>
            <form action="../controller/AuthController.php?action=login" method="POST">
                <div class="mb-3"><label class="form-label small fw-bold">Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="mb-3"><label class="form-label small fw-bold">Password</label><input type="password" name="password" class="form-control" required></div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Masuk Aplikasi</button>
                <p class="text-center mt-3 small">Belum punya akun? <a href="register.php">Registrasi di sini</a></p>
            </form>
        </div>
    </div>
</body>
</html>