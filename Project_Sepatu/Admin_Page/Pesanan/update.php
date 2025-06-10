<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['Status_Pesanan'];
    $idPesanan = $_POST['idPesanan'];

    $stmt = $conn->prepare("UPDATE pesanan SET Status_Pesanan = ? WHERE idPesanan = ?");
    $stmt->bind_param("si", $status, $idPesanan);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Status pesanan berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui status pesanan.";
    }

    $stmt->close();
    $conn->close();
    header("Location: DeksP.php");
    exit;
}
?>
