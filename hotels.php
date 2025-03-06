<?php 
session_start();
require('db_conn.php');
require('hotels_class.php');

$db = new Database();
$pdo = $db->connect();  // Connexion

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
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
                        <li class="nav-item"><a class="nav-link active" href="#">Hôtels</a></li>
                        <li class="nav-item"><a class="nav-link" href="reserv.php">Réservation</a></li>
                        <?php if (isset($_SESSION['user_id'])){
                        echo '<li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>'; 
                        }?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container px-4 px-lg-5">
            <div class="card text-white bg-secondary my-5 py-4 text-center">
                <div class="card-body"><p class="text-white m-0">Réservez votre hôtel dès maintenant pour une expérience inoubliable à travers le monde !</p></div>
            </div>
        <div class="row gx-4 gx-lg-5">
            <?php
                $hotelManager = new HotelManager($pdo);
                $hotels = $hotelManager->getHotels();

                // Génération des 'Cards'
                foreach ($hotels as $hotel) {
                    ?>
                    <div class="col-md-4 mb-5">
                        <div class="card h-100">
                            <img class="card-img-top" src="<?= htmlspecialchars($hotel->getHotelImg()) ?>" alt="<?= htmlspecialchars($hotel->getNom()) ?>" />
                            <div class="card-body">
                                <h3 class="card-title"><?= htmlspecialchars($hotel->getNom()) ?></h3>
                                <h5 class="card-text">Adresse : <?= htmlspecialchars($hotel->getAdresse()) ?></h5>
                                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse officia obcaecati et possimus in earum voluptatum fugiat libero expedita, consequatur laboriosam aperiam aliquam mollitia quia quibusdam omnis eveniet distinctio! Perferendis.</p>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-primary btn-sm" href="reserv.php?hotel_id=<?= $hotel->getHotelId() ?>">Réserver</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
        </div>
        <footer class="py-5 bg-dark">
            <div class="container px-4 px-lg-5"><p class="m-0 text-center text-white">Copyright &copy; Booking - Bootstrap 2023</p></div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>