<?php
session_start();
require('hotels_class.php');
require('db_conn.php');

$client_name = $_POST['client_name'];
$client_email = $_POST['client_email'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$hotel_id = $_POST['hotel_id'];
$room_number = $_POST['room_number'];

$db = new Database();
$pdo = $db->connect();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = "";

try {
    // Vérification de l'existence de l'hôtel
    $sql = "SELECT * FROM hotel WHERE hotel_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hotel_id]);
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hotel) {
        throw new Exception('Désolé, cet hôtel n\'existe pas.');
    }

    // Vérification de l'existence de la chambre
    $sql = "SELECT chambre_id FROM chambre WHERE hotel_id = ? AND numero_chambre = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hotel_id, $room_number]);
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chambre) {
        throw new Exception('Désolé, cette chambre n\'existe pas dans cet hôtel.');
    }

    $chambre_id = $chambre['chambre_id'];

    // Gestion du client existant ou création d'un nouveau client
    $sql = "SELECT * FROM client WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$client_email]);
    $existingClient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingClient) {
        $client_id = $existingClient['client_id'];
    } else {
        $sql = "INSERT INTO client (nom, email) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$client_name, $client_email]);
        $client_id = $pdo->lastInsertId();
    }

    // Utilisation de la classe HotelManager pour créer la réservation
    $hotelManager = new HotelManager($pdo);
    $message = $hotelManager->makeReservation($client_id, $chambre_id, $start_date, $end_date);

} catch (Exception $e) {
    $message = $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Confirmation de réservation</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <a class="navbar-brand" href="index.php">Booking</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link" href="hotels.php">Hôtels</a></li>
                        <li class="nav-item"><a class="nav-link" href="reserv.php">Réservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="container my-5">
            <div class="alert <?= $message === 'Réservation réussie !' ? 'alert-success' : 'alert-danger' ?> text-center" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
            <div class="text-center">
                <a href="reserv.php" class="btn btn-primary">Retour à la page de réservation</a>
                
                <?php if (isset($message) && $message == 'Réservation réussie !'): ?>
                    <a href="reserv_list.php" class="btn btn-success">Voir vos réservations</a>
                <?php endif; ?>
            </div>
        </div>

        <footer class="py-5 bg-dark">
            <div class="container px-4 px-lg-5">
                <p class="m-0 text-center text-white">Copyright &copy; Booking 2023</p>
            </div>
        </footer>
    </body>
</html>