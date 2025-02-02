<?php
    session_start();
    include 'db.php';

    $current_page = basename($_SERVER['PHP_SELF']); // Mendapatkan nama file saat ini


    // Pastikan hanya user yang sudah login yang bisa mengakses halaman ini
    if ($_SESSION['status_login'] != true) {
        echo '<script>window.location="login.php"</script>';
    }

    // Ambil data user berdasarkan UserID di session
    $query = mysqli_query($conn, "SELECT * FROM User WHERE UserID = '".$_SESSION['id']."'");
    if (!$query) {
        echo 'Query gagal: ' . mysqli_error($conn);
        exit;
    }
    $d = mysqli_fetch_object($query);
    if (isset($_SESSION['status_login']) && $_SESSION['status_login'] == true) {
        $userID = $_SESSION['id'];
        $query = mysqli_query($conn, "SELECT Role FROM User WHERE UserID = '$userID'");
        $user = mysqli_fetch_object($query);
        $isAdmin = ($user->Role == 'admin'); 
    }
// Proses update profil
if (isset($_POST['submit'])) {
    // Ambil data dari form input
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $user   = mysqli_real_escape_string($conn, $_POST['user']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Validasi input: semua field harus diisi
    if (empty($nama) || empty($user) || empty($email) || empty($alamat)) {
        echo '<script>alert("Semua field wajib diisi!");</script>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Validasi format email
        echo '<script>alert("Format email tidak valid!");</script>';
    } else {
        // Query untuk update data user
        $update = mysqli_query($conn, "UPDATE User SET 
            NamaLengkap = '$nama',
            Username = '$user',
            Email = '$email',
            Alamat = '$alamat'
            WHERE UserID = '$d->UserID'");

        if ($update) {
            echo '<script>alert("Profil berhasil diupdate!");</script>';
            echo '<script>window.location="profil.php";</script>';
        } else {
            // Tampilkan error jika query gagal
            echo '<script>alert("Gagal mengupdate profil: ' . mysqli_error($conn) . '");</script>';
        }
    }
}

// Proses ubah password
if (isset($_POST['ubah_password'])) {
    // Ambil data dari form input
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    // Validasi password
    if (empty($pass1) || empty($pass2)) {
        echo '<script>alert("Password tidak boleh kosong!");</script>';
    } elseif ($pass1 !== $pass2) {
        echo '<script>alert("Konfirmasi password tidak cocok!");</script>';
    } else {
        // Enkripsi password baru
        $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);

        // Query untuk update password
        $update_pass = mysqli_query($conn, "UPDATE User SET 
            Password = '$hashed_password'
            WHERE UserID = '$d->UserID'");

        if ($update_pass) {
            echo '<script>alert("Password berhasil diubah!");</script>';
            echo '<script>window.location="profil.php";</script>';
        } else {
            // Tampilkan error jika query gagal
            echo '<script>alert("Gagal mengubah password: ' . mysqli_error($conn) . '");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="Sample-gambar/logo.png" type="image/png">
    <link rel="stylesheet" href="css/navbar.css">
    <style>
         :root {
        --primary-color: #ffaf72;
        --secondary-color: #767676;
        --background-color: #fff;
        --hover-color: #f0f0f0;
    }

    body {
        background-color: var(--background-color);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Helvetica, "ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", メイリオ, Meiryo, "ＭＳ Ｐゴシック", Arial, sans-serif;
    }
        .section {
            padding: 40px 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        .box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .input-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn {
            background-color: #ffaf72;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color:rgb(245, 133, 47);
        }

        textarea.input-control {
            height: 100px;
            resize: none;
        }

        footer {
            background-color: #f8f9fa; /* Light background color */
            padding: 20px 0; /* Padding for top and bottom */
            position: relative; /* Position relative for layout */
            bottom: 0; /* Align to the bottom */
            width: 100%; /* Full width */
            text-align: center; /* Center the text */
            border-top: 1px solid #dee2e6; /* Optional: border on top */
            margin-top: 5rem;
        }

        footer small {
            font-size: 0.9rem;
        }

        /* Pinterest-style cards */
        .profile-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: linear-gradient(135deg, #ffaf72,rgb(245, 133, 47));
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
        }

        .profile-card .profile-info {
            flex: 1;
            margin-left: 20px;
        }

        .profile-card .profile-info h4 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: bold;
        }

        .profile-card .profile-info p {
            margin: 5px 0;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <!-- Brand - Kept unchanged for desktop -->
            <a class="navbar-brand" href="index.php" style="display: flex; align-items: center; padding: 10px;">
                <img src="Sample-gambar/logo.png" alt="Logo" style="max-height: 50px; width: auto; margin-right: 15px; border-radius: 100%;">
                <span style="font-size: 1.5rem; font-weight: bold; color: #333;">Galeri Nando</span>
            </a>

            <!-- Bootstrap's navbar toggler for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible content -->
            <div class="collapse navbar-collapse" id="navbarContent">

                <!-- Navigation links -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeri.php">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                    <?php if (isset($_SESSION['status_login']) && $_SESSION['status_login'] == true): ?>
                        <li class="nav-item"><a class="nav-link" href="keluar.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="registrasi.php">Registrasi</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="section">
        <div class="container">
            <!-- Profile Card -->
            <div class="profile-card">
                <img src="Sample-gambar/logo.png" alt="Profile Picture">
                <div class="profile-info">
                    <h4><?php echo htmlspecialchars($d->NamaLengkap); ?></h4>
                    <p><?php echo htmlspecialchars($d->Email); ?></p>
                </div>
            </div>

            <!-- Update Profile Section -->
            <h3>Profil Anda</h3>
            <div class="box">
            <form action="" method="POST">
                <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?php echo htmlspecialchars($d->NamaLengkap); ?>" required>
                <input type="text" name="user" placeholder="Username" class="input-control" value="<?php echo htmlspecialchars($d->Username); ?>" required>
                <input type="email" name="email" placeholder="Email" class="input-control" value="<?php echo htmlspecialchars($d->Email); ?>" required>
                <textarea name="alamat" placeholder="Alamat" class="input-control" required><?php echo htmlspecialchars($d->Alamat); ?></textarea>
                <button type="submit" name="submit" class="btn btn-primary">Ubah Profil</button>
            </form>
            </div>

            <!-- Change Password Section -->
            <h3>Ubah Password</h3>
            <div class="box">
            <form action="" method="POST">
                <input type="password" name="pass1" placeholder="Password Baru" class="input-control" required>
                <input type="password" name="pass2" placeholder="Konfirmasi Password Baru" class="input-control" required>
                <button type="submit" name="ubah_password" class="btn btn-secondary">Ubah Password</button>
            </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - Galeri Nando. All rights reserved.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
