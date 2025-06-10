<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPesanan = $_POST['idPesanan'] ?? null;

    if ($idPesanan) {
        $stmt = $conn->prepare("DELETE FROM pesanan WHERE idPesanan = ?");
        $stmt->bind_param("i", $idPesanan);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Pesanan berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus pesanan.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "ID Pesanan tidak ditemukan.";
    }

    header("Location: DeksP.php");
    exit();
}
?>
