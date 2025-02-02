<?php
// session_start();
// include 'db.php';

// // Check if user is logged in
// if (!isset($_SESSION['id'])) {
//     header('Location: login.php');
//     exit();
// }

// // Check if foto_id is set
// if (isset($_POST['foto_id'])) {
//     $foto_id = (int)$_POST['foto_id'];
//     $user_id = $_SESSION['id'];
//     $tanggal_like = date('Y-m-d H:i:s'); // Use datetime for more precision

//     // Insert a new like (regardless of whether the user has liked it before)
//     mysqli_query($conn, "INSERT INTO `Like` (FotoID, UserID, TanggalLike) VALUES ('$foto_id', '$user_id', '$tanggal_like')");

//     // Redirect back to the gallery page
//     header("Location: index.php");
//     exit();
// } else {
//     // If foto_id is not set, redirect to the gallery
//     header("Location: index.php");
//     exit();
// }
?>