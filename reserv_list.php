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

try {

    // Requête SQL pour récupérer les réservations de l'utilisateur
    $sql = "
        SELECT 
            b.booking_id,
            h.nom AS hotel_name,
            h.hotel_img AS hotel_image,
            c.numero_chambre,
            b.date_debut,
            b.date_fin,
            b.date_creation
        FROM 
            booking b
        LEFT JOIN 
            chambre c ON b.chambre_id = c.chambre_id
        LEFT JOIN 
            hotel h ON c.hotel_id = h.hotel_id
        WHERE 
            b.client_id = :user;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user', $user_id);
    $stmt->execute([$user_id]);

    // Récupération de toutes les réservations de l'utilisateur
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $message = 'Erreur de récupération des réservations : ' . $e->getMessage(); // Gestion de / des erreurs
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Mes Réservations</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-5">
            <a class="navbar-brand" href="index.php">Booking</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels.php">Hôtels</a></li>
                    <li class="nav-item"><a class="nav-link active" href="reserv.php">Réservation</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Mes Réservations</h2>
        
        <?php if (isset($message)) : ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php if (!empty($reservations)): ?>
                <?php foreach ($reservations as $reservation): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <?php if (!empty($reservation['hotel_image'])): ?>
                                <img class="card-img-top" src="<?= htmlspecialchars($reservation['hotel_image']) ?>" alt="<?= htmlspecialchars($reservation['hotel_name']) ?>" />
                                <?php else: ?>
                                    <img src="default-image.jpg" class="card-img-top" alt="Image par défaut">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($reservation['hotel_name'] ?: 'Hôtel non spécifié') ?></h5>
                                <p class="card-text">
                                    <strong>Numéro de chambre :</strong> <?= htmlspecialchars($reservation['numero_chambre'] ?: 'Non spécifié') ?><br>
                                    <strong>Dates :</strong> <?= htmlspecialchars($reservation['date_debut']) ?> à <?= htmlspecialchars($reservation['date_fin']) ?><br>
                                    <strong>Date de création :</strong> <?= htmlspecialchars($reservation['date_creation']) ?>
                                </p>
                                <a href="reserv_delete.php?del=<?= $reservation['booking_id'] ?>" class="btn btn-primary">Annuler</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        Vous n'avez aucune réservation pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container px-4 px-lg-5">
            <p class="m-0 text-center text-white">Copyright &copy; Booking 2023</p>
        </div>
    </footer>
                
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>