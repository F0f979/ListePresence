<?php
    // inclus connexion.php
    include ("connexion.php");
    
    // recuperation de l'evenement 
    $evenement = $conn->prepare("SELECT * FROM evenement ORDER BY id_evenement DESC");
    $evenement->execute();
    $evenement->setFetchMode(PDO::FETCH_ASSOC);
    $evenements = $evenement->fetch();

    // Envoie des donnees dans la base de donnee
    if (isset($_POST["enregistrer"])) {
        
        //assignation aux variables
        $nom_participant = htmlspecialchars($_POST["nom_participant"]);
        $prenoms_participant = htmlspecialchars($_POST["prenoms_participant"]);
        $numero_telephone_participant = htmlspecialchars($_POST["numero_telephone_participant"]);
        $adresse_email_participant = htmlspecialchars($_POST["adresse_email_participant"]);
        $titre_evenement = htmlspecialchars($_POST["titre_evenement"]);
        if(!empty($evenements)) {$id_evenement = intval($evenements['id_evenement']);}else{};
        
        //nouvel evenement
        if ($evenements['titre_evenement'] !== $titre_evenement) {
            //format de la date
            $jour = date('d');
            $mois = date('m');
            $annee = "20".date('y');
            $date_evenement = "$annee/$mois/$jour ";
            $id_evenement = intval($evenements['id_evenement']) + 1;
            echo "$date_evenement";

            //insertion d'un nouvel evenement
            $stmt = $conn->prepare("INSERT INTO evenement (titre_evenement, date_evenement) VALUES (:titre_evenement, :date_evenement)");
            $stmt->bindParam(':titre_evenement', $titre_evenement);
            $stmt->bindParam(':date_evenement', $date_evenement);
            $stmt->execute();
        }

        //insertion des donnees d'un participant à l'evenement
        $stmt = $conn->prepare("INSERT INTO participant (nom_participant, prenoms_participant, numero_telephone_participant, adresse_email_participant, id_evenement) VALUES (:nom_participant, :prenoms_participant, :numero_telephone_participant, :adresse_email_participant, :id_evenement)");
        $stmt->bindParam(':nom_participant', $nom_participant);
        $stmt->bindParam(':prenoms_participant', $prenoms_participant);
        $stmt->bindParam(':numero_telephone_participant', $numero_telephone_participant);
        $stmt->bindParam(':adresse_email_participant', $adresse_email_participant);
        $stmt->bindParam(':id_evenement', $id_evenement);
        $stmt->execute();

        // pop-up
        echo "<div style=\"display: flex; width:50%; height: 50vh; margin: 50px auto; padding: 30px; border: 2px solid #15e116; border-radius: 15px; background-color: #23f1a1\"><p style=\"font-size: 2em; text-transform: uppercase; font-weight: bold; color: #000000;\">le participant <span style=\"color: #ffffff; \">$nom_participant $prenoms_participant</span> est inscrit sur la list de presence de l'evenement <span style=\"color: #f43214; \">$titre_evenement</span>!!!</p></div>";

        //redirection
        header("Refresh: 5; URL=form.php");
    }
?>