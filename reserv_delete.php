<?php
session_start();
require('db_conn.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$db = new Database();
$pdo = $db->connect(); 

if (isset($_GET['del'])) {
    $booking_id = $_GET['del'];

    // Supprimer une réservation correspondant à l'ID
    try {
        $sql = "DELETE FROM booking WHERE booking_id = :booking_id AND client_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: reserv_list.php');
        exit;
    } catch (Exception $e) {
        $message = 'Erreur lors de la suppression de la réservation : ' . $e->getMessage();
    }
}
?>
