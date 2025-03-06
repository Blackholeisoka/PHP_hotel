<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit; 
}

require('db_conn.php'); // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['client_name']; 
    $email = $_POST['client_email']; 

    try {
        // Utilisation de db_conn.php pour établir la connexion
        $db = new Database();
        $pdo = $db->connect();

        // Vérifier si l'utilisateur existe dans la base de données
        $stmt = $pdo->prepare('SELECT * FROM client WHERE nom = :nom AND email = :email');
        $stmt->execute(['nom' => $name, 'email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['client_id']; 
            $_SESSION['user_name'] = $user['nom']; 
            $_SESSION['user_email'] = $user['email'];

            // Rediriger vers la page d'accueil
            header('Location: index.php'); 
            exit;
        } else {
            $error = "Nom ou email incorrect.";
        }

        $db->disconnect(); // Déconnexion explicite
    } catch (Exception $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
                    <li class="nav-item"><a class="nav-link active" href="#">Connexion</a></li>
                </ul>
            </div> 
        </div>
    </nav>

    <div class="container">
        <h2 class="my-5 text-center">Connexion</h2>

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
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <p class="mt-3 text-center">Pas encore membre ? <a href="register.php">Inscrivez-vous</a></p>
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