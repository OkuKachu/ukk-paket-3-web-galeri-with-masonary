<?php
session_start();
error_reporting(0);
include 'db.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true) {
    header("Location: login.php");
    exit();
}

// Validasi dan sanitasi AlbumID
$albumID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data album dengan prepared statement
$stmt = $conn->prepare("SELECT * FROM Album WHERE AlbumID = ?");
$stmt->bind_param("i", $albumID);
$stmt->execute();
$result = $stmt->get_result();
$albumData = $result->fetch_object();

// Cek keberadaan album
if (!$albumData) {
    die("Album tidak ditemukan");
}

// Cek role dan kepemilikan
$userID = $_SESSION['id'];
$isAdmin = false;
$isAlbumOwner = ($albumData->UserID == $userID);

// Cek admin
$stmt = $conn->prepare("SELECT Role FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_object();
$isAdmin = ($userData->Role == 'admin');

// Izin akses
$canAddPhotos = ($isAlbumOwner || $isAdmin);
$canDeleteAlbum = ($isAlbumOwner || $isAdmin);

// PROSES HAPUS ALBUM
if (isset($_POST['delete_album'])) {
    if (!$canDeleteAlbum) {
        die("Anda tidak memiliki izin untuk menghapus album ini");
    }

    try {
        $conn->begin_transaction();

        // Hapus foto
        $stmt = $conn->prepare("DELETE FROM Foto WHERE Album_ID = ?");
        $stmt->bind_param("i", $albumID);
        $stmt->execute();

        // Hapus album
        $stmt = $conn->prepare("DELETE FROM Album WHERE AlbumID = ?");
        $stmt->bind_param("i", $albumID);
        $stmt->execute();

        $conn->commit();
        header("Location: galeri.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}

// PROSES UPLOAD FOTO
if (isset($_POST['submit'])) {
    if (!$canAddPhotos) {
        die("Anda tidak memiliki izin untuk menambahkan foto");
    }

    $judul = htmlspecialchars($_POST['judul']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    
    try {
        // Validasi file
        if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error upload file");
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['gambar']['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception("Tipe file tidak didukung");
        }

        // Generate nama unik
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid() . '.' . $ext;
        $uploadPath = "foto/" . $newFilename;

        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadPath)) {
            throw new Exception("Gagal menyimpan file");
        }

        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO Foto (JudulFoto, Deskripsi, Gambar, Album_ID, UserID, TanggalUnggah) 
                              VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssii", $judul, $deskripsi, $newFilename, $albumID, $userID);
        $stmt->execute();

        header("Location: album.php?id=" . $albumID);
        exit();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

// Ambil foto dalam album
$stmt = $conn->prepare("SELECT * FROM Foto WHERE Album_ID = ? ORDER BY TanggalUnggah DESC");
$stmt->bind_param("i", $albumID);
$stmt->execute();
$fotos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album - <?php echo htmlspecialchars($albumData->NamaAlbum); ?></title>
    <link rel="icon" href="Sample-gambar/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/album.css">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" style="display: flex; align-items: center; padding: 10px;">
                <img src="Sample-gambar/logo.png" alt="Logo" style="max-height: 50px; width: auto; margin-right: 15px; border-radius: 100%;">
                <span style="font-size: 1.5rem; font-weight: bold; color: #333;">Galeri Nando</span>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeri.php">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                    <!-- <?php if (isset($isAdmin) && $isAdmin): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?> -->
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

     <!-- Album Header -->
     <div class="album-header">
        <h1 class="album-title"><?php echo htmlspecialchars($albumData->NamaAlbum); ?></h1>
        <p class="album-description"><?php echo htmlspecialchars($albumData->Deskripsi); ?></p>
        <p class="album-date">Dibuat pada <?php echo date('d F Y', strtotime($albumData->TanggalDibuat)); ?></p>
    </div>

    <div class="modal fade" id="addPhotoModal" tabindex="-1" aria-labelledby="addPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPhotoModalLabel">Upload New Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="upload-area" onclick="document.getElementById('gambar').click()">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <p>Click or drag image here to upload</p>
                            <input type="file" name="gambar" id="gambar" accept="image/*" style="display: none" required>
                        </div>
                        <img id="preview-image" src="#" alt="Preview">
                        <div class="mb-3">
                            <input type="text" name="judul" class="form-control" placeholder="Photo Title" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Photo Description"></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-upload w-100">Upload Photo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="action-buttons">
        <button class="floating-btn add-btn" data-bs-toggle="modal" data-bs-target="#addPhotoModal" title="Tambah Foto">
            <i class="fas fa-plus fa-lg"></i>
        </button>
        <?php if ($albumData->UserID == $_SESSION['id'] || (isset($isAdmin) && $isAdmin)): ?>
        <form method="POST" style="margin: 0;" onsubmit="return confirm('Yakin ingin menghapus album ini beserta seluruh foto di dalamnya?');">
            <button type="submit" name="delete_album" class="floating-btn delete-btn" title="Hapus Album">
                <i class="fas fa-trash fa-lg"></i>
            </button>
        </form>
        <?php endif; ?>
        <button onclick="window.location.href = document.referrer;" class="floating-btn back-btn" title="Kembali">
            <i class="fas fa-arrow-left fa-lg"></i>
        </button>
    </div>

    <!-- Photos Masonry Grid -->
    <?php if (mysqli_num_rows($fotos) > 0): ?>
    <div class="masonry-grid">
        <?php while ($foto = mysqli_fetch_array($fotos)): ?>
        <div class="pin-item">
        <a href="detail-image.php?id=<?php echo $foto['FotoID']; ?>&from=album&album_id=<?php echo $albumID; ?>">            <img src="foto/<?php echo $foto['Gambar'] ?>" alt="<?php echo htmlspecialchars($foto['JudulFoto']) ?>" class="pin-image">
                <div class="pin-overlay">
                    <div class="pin-title"><?php echo htmlspecialchars(substr($foto['JudulFoto'], 0, 30)) ?></div>
                    <div class="pin-date"><?php echo date('d F Y', strtotime($foto['TanggalUnggah'])) ?></div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-images"></i>
        <h3>Album ini masih kosong</h3>
        <p>Klik tombol + untuk menambahkan foto pertama ke album ini</p>
    </div>
    <?php endif; ?>

    <footer class="footer" style="margin-top: 10rem">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <small>Copyright &copy; 2025 - Galeri Nando. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview functionality
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview-image');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop functionality
        const uploadArea = document.querySelector('.upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('border-primary');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('border-primary');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('gambar').files = files;
            
            if (files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-image').style.display = 'block';
                }
                reader.readAsDataURL(files[0]);
            }
        }
    </script>
</body>
</html>
