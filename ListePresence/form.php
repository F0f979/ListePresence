<?php
    // inclus connexion.php
    include ("connexion.php");   

    // recuperation de l'evenement 
    $evenement = $conn->prepare("SELECT * FROM evenement ORDER BY id_evenement DESC ");
    $evenement->execute();
    $evenement->setFetchMode(PDO::FETCH_ASSOC);
    $evenements = $evenement->fetch();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrment des informations</title>
    <link rel="stylesheet" type="text/css" href="./style/form.css" >
</head>
<body>

    <!-- HEADER -->

    <header >
        <h1>simplon cote d'ivoire</h1>
    </header>
    
    <!-- MAIN -->

    <main class="container">
        <form action="traitement.php" method="POST" id="form">
                <label>details de l'evenement</label>
                <input type="text" name="titre_evenement" class="evenement" placeholder="Entrer le titre de l'evenement" value="<?php if (empty($evenements['titre_evenement'])) {} else { echo $evenements['titre_evenement']; } /*echo $evenements['titre_evenement']*/ ?>" required>
            <fieldset>
                <legend>veuillez entrer les informations du participant</legend>
                <div>
                    <input type="text" name="nom_participant" placeholder="Nom" required>
                    <input type="text" name="prenoms_participant" placeholder="Prenoms" required>
                </div>
                <div>
                    <input type="tel" name="numero_telephone_participant" pattern="[0-9]{10}" placeholder="Numero de Telephone" required>
                    <input type="mail" name="adresse_email_participant" placeholder="Adresse Email" required>
                </div>
            </fieldset>
            <div class="button">
                <input type="submit" name="enregistrer" value="enregistrer" >
                <input type="button" name="retour" value="retour a l'accueil" id="back" >
            </div>
        </form>

    </main>
    
    <!-- FOOTER -->

    <footer>
        <marquee><p>&copy; Simplon Cote d'Ivoire 2023</p></marquee>
    </footer>
    
    <!-- SCRIPT -->
    
    <script type="text/javascript" >
        const back = document.getElementById('back');
            back.addEventListener('click', ()=>{
            document.location.href='./index.php'
        });
    </script>
</body>
</html>