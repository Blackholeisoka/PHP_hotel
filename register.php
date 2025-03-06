<?php
session_start();

require_once 'db_conn.php';  
require_once 'User.php';

// Initialisation de la connexion 
$db = new Database();
$pdo = $db->connect();

// Gestion du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['client_name'];
    $email = $_POST['client_email'];

    $user = new User($pdo);

    // Enregistrer utilisateur
    if ($user->register($name, $email)) {
        header('Location: login.php');
        exit;
    } else {
        $error = "Cet email est déjà utilisé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-5">
            <a class="navbar-brand" href="index.php">Booking</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels.php">Hôtels</a></li>
                    <li class="nav-item"><a class="nav-link" href="reserv.php">Réservation</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Inscription</a></li> 
                </ul>
            </div> 
        </div>
    </nav>

    <div class="container">
        <h2 class="my-5 text-center">Inscription</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="client_name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="client_name" name="client_name" required>
            </div>
            <div class="mb-3">
                <label for="client_email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="client_email" name="client_email" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>

        <p class="mt-3 text-center">Vous êtes déjà membre ? <a href="login.php">Connectez-vous</a></p>
    </div>

    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container px-4 px-lg-5">
            <p class="m-0 text-center text-white">Copyright &copy; Booking - Bootstrap 2023</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>