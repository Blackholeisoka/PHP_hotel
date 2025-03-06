<?php
session_start(); 

require('db_conn.php'); 
require('hotels_class.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$hotel_id_selected = isset($_GET['hotel_id']) ? $_GET['hotel_id'] : null; 

try {
    $pdo = $database->connect();

    $hotelManager = new HotelManager($pdo);

    // Récupère tous les hôtels
    $hotels = $hotelManager->getHotels();
} catch (Exception $e) {

    // Gestion des erreurs de connexion
    die('Erreur : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Site de réservation moderne et fonctionnel." />
        <meta name="author" content="Melvin Boulefred" />
        <title>Booking - Bootstrap</title>
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
                        <li class="nav-item"><a class="nav-link active" href="#">Réservation</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container px-4 px-lg-5">
            <div class="card text-white bg-secondary my-5 py-4 text-center">
                <div class="card-body"><p class="text-white m-0">Réservez votre hôtel dès maintenant pour une expérience inoubliable à travers le monde !</p></div>
            </div>

            <div class="container my-5">
                <h2 class="text-center mb-4">Réservation d'une chambre</h2>
                <form method="POST" action="process_booking.php" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="clientName" class="form-label">Nom du client</label>
                        <input type="text" class="form-control" id="clientName" name="client_name" 
                            placeholder="Entrez votre nom" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>
                        <div class="invalid-feedback">Veuillez entrer un nom.</div>
                    </div>
                    <div class="mb-3">
                        <label for="clientEmail" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="clientEmail" name="client_email" 
                            placeholder="Entrez votre e-mail" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required>
                        <div class="invalid-feedback">Veuillez entrer une adresse e-mail valide.</div>
                    </div>

                    <div class="mb-3">
                        <label for="startDate" class="form-label">Date de début de réservation</label>
                        <input type="date" class="form-control" id="startDate" name="start_date" required>
                        <div class="invalid-feedback">Veuillez sélectionner une date de début.</div>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">Date de fin de réservation</label>
                        <input type="date" class="form-control" id="endDate" name="end_date" required>
                        <div class="invalid-feedback">Veuillez sélectionner une date de fin.</div>
                    </div>

                    <div class="mb-3">
                        <label for="hotelName" class="form-label">Nom de l'hôtel</label>
                        <select class="form-select" id="hotelName" name="hotel_id" required>
                            <option value="" selected disabled>Choisissez un hôtel</option>
                            <?php foreach ($hotels as $hotel): ?>
                                <option value="<?= htmlspecialchars($hotel->getHotelId()) ?>" <?= $hotel_id_selected == $hotel->getHotelId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($hotel->getNom()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un hôtel.</div>
                    </div>

                    <div class="mb-3">
                        <label for="roomNumber" class="form-label">Numéro de chambre</label>
                        <input type="number" class="form-control" id="roomNumber" min="2" max="20" name="room_number" placeholder="Laissez vide pour la première chambre disponible">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Réserver</button>
                    </div>
                        <a href="reserv_list.php" class="text-center nav-link mt-3">* Voir vos réservations</a>
                </form>
            </div>
        </div>

        <footer class="py-5 bg-dark">
            <div class="container px-4 px-lg-5">
                <p class="m-0 text-center text-white">Copyright &copy; Booking - Bootstrap 2023</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            (function () {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')

                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        </script>
    </body>
</html>