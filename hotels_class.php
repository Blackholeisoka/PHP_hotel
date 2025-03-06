<?php 
class Hotel
{
    private $hotel_id;
    private $nom;
    private $adresse;
    private $hotel_img;

    public function __construct($hotel_id, $nom, $adresse, $hotel_img)
    {
        $this->hotel_id = $hotel_id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->hotel_img = $hotel_img;
    }

    public function getHotelId() {
        return $this->hotel_id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getHotelImg() {
        return $this->hotel_img;
    }
}

class HotelManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo; // Initialisation de la connexion
    }

    public function isRoomAvailable($chambre_id, $check_in_date, $check_out_date)
    {
        $sql = "SELECT * FROM booking 
                WHERE chambre_id = :chambre_id 
                AND ((date_debut <= :check_out_date) AND (date_fin >= :check_in_date))";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':chambre_id', $chambre_id);
        $stmt->bindParam(':check_in_date', $check_in_date);
        $stmt->bindParam(':check_out_date', $check_out_date);
        
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return false;  
        }
        
        return true; 
    }

    public function makeReservation($client_id, $chambre_id, $check_in_date, $check_out_date)
    {
        if ($this->isRoomAvailable($chambre_id, $check_in_date, $check_out_date)) {

            // Effectue la réservation si la chambre est disponible
            $sql = "INSERT INTO booking (client_id, chambre_id, date_debut, date_fin, date_creation) 
                    VALUES (:client_id, :chambre_id, :check_in_date, :check_out_date, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':chambre_id', $chambre_id);
            $stmt->bindParam(':check_in_date', $check_in_date);
            $stmt->bindParam(':check_out_date', $check_out_date);
            
            $stmt->execute();
            
            return "Réservation effectuée avec succès!";
        } else {
            return "Désolé, cette chambre est déjà réservée pour ces dates.";
        }
    }

    public function getHotels()
    {
        $sql = "SELECT * FROM hotel";
        $stmt = $this->pdo->query($sql);
        $hotels = [];

        // Récupère les hôtels depuis la base de données
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $hotels[] = new Hotel($row['hotel_id'], $row['nom'], $row['adresse'], $row['hotel_img']);
        }

        return $hotels;
    }
}
?>