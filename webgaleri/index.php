<?php
include 'db.php';
session_start();

$current_page = basename($_SERVER['PHP_SELF']); // Mendapatkan nama file saat ini

// cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// mendapatkan jumlah like
function getLikesCount($fotoID, $conn) {
    $likes = mysqli_query($conn, "SELECT COUNT(*) AS total_likes FROM `Like` WHERE FotoID = '$fotoID'");
    return mysqli_fetch_object($likes)->total_likes;
}

// cek apakah user sudah like foto
function getUserLikes($fotoID, $userID, $conn) {
    $user_likes_query = mysqli_query($conn, "SELECT COUNT(*) AS user_likes FROM `Like` WHERE FotoID = '$fotoID' AND UserID = '$userID'");
    return mysqli_fetch_object($user_likes_query)->user_likes;
}

// logika aksi like
if (isset($_GET['like'])) {
    $foto_id = $_GET['like'];
    $user_id = $_SESSION['id'];
    $tanggal_like = date('Y-m-d');
    
    mysqli_query($conn, "INSERT INTO `Like` (FotoID, UserID, TanggalLike) VALUES ('$foto_id', '$user_id', '$tanggal_like')");
    
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}


// Fetch all photos from the database with search functionality
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Fetch all albums
$albums_query = mysqli_query($conn, "SELECT Album.*, User.Username 
                                      FROM Album 
                                      JOIN User ON Album.UserID = User.UserID 
                                      ORDER BY Album.TanggalDibuat DESC");

// Fetch photos based on album filter (optional)
$album_filter = isset($_GET['album']) ? (int)$_GET['album'] : null;

if ($album_filter) {
    // Query for photos in a specific album
    $foto = mysqli_query($conn, "SELECT Foto.*, User.Username, Album.NamaAlbum 
                                  FROM Foto 
                                  JOIN User ON Foto.UserID = User.UserID 
                                  JOIN Album ON Foto.Album_ID = Album.AlbumID 
                                  WHERE Album_ID = $album_filter 
                                  ORDER BY Foto.FotoID DESC");
} else {
    // Query for all photos without album filter
    if ($search_query) {
        $foto = mysqli_query($conn, "SELECT Foto.*, User.Username, Album.NamaAlbum 
                                      FROM Foto 
                                      JOIN User ON Foto.UserID = User.UserID 
                                      JOIN Album ON Foto.Album_ID = Album.AlbumID 
                                      WHERE Foto.JudulFoto LIKE '%$search_query%' 
                                      OR Foto.Deskripsi LIKE '%$search_query%' 
                                      ORDER BY Foto.FotoID DESC");
    } else {
        $foto = mysqli_query($conn, "SELECT Foto.*, User.Username, Album.NamaAlbum 
                                      FROM Foto 
                                      JOIN User ON Foto.UserID = User.UserID 
                                      JOIN Album ON Foto.Album_ID = Album.AlbumID 
                                      ORDER BY Foto.FotoID DESC");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
    <link rel="icon" href="Sample-gambar/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>    
<body>
    <!-- header -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="index.php">
                <img src="Sample-gambar/logo.png" alt="Logo">
                <span>Galeri Nando</span>
            </a>

            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Desktop Search Form -->
                <form class="d-none d-lg-flex search-form" action="index.php" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="search" placeholder="Search" aria-label="Search">
                        <button class="btn search-btn" type="submit">Search</button>
                    </div>
                </form>

                <!-- Navigation Links -->
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

    <!-- Mobile Search Form -->
    <div class="search-form-container d-lg-none">
        <form class="search-form" action="index.php" method="GET">
            <div class="input-group">
                <input class="form-control" type="search" name="search" placeholder="Search" aria-label="Search">
                <button class="btn search-btn" type="submit">Search</button>
            </div>
        </form>
    </div>

    <div class="album-filter">
        <div class="container-fluid">
            <div class="album-chips">
                <a href="index.php" class="album-chip <?php echo !$album_filter ? 'active' : ''; ?>">
                    Semua Foto
                </a>
                <?php while ($album = mysqli_fetch_array($albums_query)): ?>
                <a href="index.php?album=<?php echo $album['AlbumID']; ?>" 
                   class="album-chip <?php echo $album_filter == $album['AlbumID'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($album['NamaAlbum']); ?>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

     <!-- Container Foto -->
     <div class="masonry-grid">
        <?php if(mysqli_num_rows($foto) > 0): ?>
            <?php while($p = mysqli_fetch_array($foto)): 
                $user_likes = getUserLikes($p['FotoID'], $_SESSION['id'], $conn);
            ?>
            <div class="pin-item">
                <a href="detail-image.php?id=<?php echo $p['FotoID']; ?>&from=index">
                    <img src="foto/<?php echo $p['Gambar'] ?>" alt="<?php echo $p['JudulFoto']; ?>" class="pin-image">
                    <div class="pin-overlay">
                        <div class="pin-content">
                            <h3 class="pin-title"><?php echo htmlspecialchars($p['JudulFoto']); ?></h3>
                            <div class="pin-meta">
                                <span class="uploader">
                                    <i class="fas fa-user"></i>
                                    <?php echo $p['Username']; ?>
                                </span>
                                <span class="likes">
                                    <i class="fas fa-heart"></i>
                                    <?php echo getLikesCount($p['FotoID'], $conn); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="like-container">
                    <a href="?like=<?php echo $p['FotoID']; ?>" class="btn-like <?php echo $user_likes > 0 ? 'liked' : ''; ?>">
                        <i class="fas fa-heart"></i>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h3>Tidak ada foto ditemukan</h3>
                <p>Coba gunakan kata kunci lain atau filter album berbeda</p>
            </div>
        <?php endif; ?>
    </div>


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
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script> -->
    <script>
    //   // Inisialisasi Masonry
    //   document.addEventListener('DOMContentLoaded', function() {
    //         const masonryGrid = document.querySelector('.masonry-grid');
    //         if(masonryGrid) {
    //             masonryGrid.style.opacity = '0';
    //             setTimeout(() => {
    //                 masonryGrid.style.opacity = '1';
    //                 masonryGrid.style.transition = 'opacity 0.3s';
    //             }, 100);
    //         }
    //     });
    // </script>
    </script>
</body>
</html>