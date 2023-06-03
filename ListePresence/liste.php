<?php
    //inclus connexion.php
    include("connexion.php");

    
    // recuperation de l'evenement 
    $evenement = $conn->prepare("SELECT * FROM evenement ORDER BY id_evenement DESC");
    $evenement->execute();
    $evenement->setFetchMode(PDO::FETCH_ASSOC);
    $evenements = $evenement->fetchAll();

    //assignation de variable
    @$page = $_GET["page"];
    @$event = $_GET["event"];
    
    if (empty($event)) {
        if (empty($evenements[0]['id_evenement'])) {
            $event = "";
        } else {
            $event = $evenements[0]['id_evenement'];
        }
    }
    
    if (empty($page)) {
        $page = 1;
    }

    // recuperation du nombre de participant
    if (!empty($event)) {
        // systeme de pagination de la liste des participants
        $nb_participant = $conn->prepare("SELECT count('id_participant') as cpt FROM  participant WHERE id_evenement = $event");
        $nb_participant->execute();
        $nb_participant->setFetchMode(PDO::FETCH_ASSOC);
        $nb_participants = $nb_participant->fetchAll();
        
        $nb_element_page = 10;
        $nb_page = ceil($nb_participants[0]['cpt'] / $nb_element_page) ; 
        $debut = ($page - 1) * $nb_element_page;

        if ($nb_participants[0]['cpt'] == 0) {
            $nb_page = 1;
        }

        if ($page == 0 || $page > $nb_page) {
            $page = 1;
        }

        //recuperation des informations des participants
        $info = $conn->prepare("SELECT * FROM participant INNER JOIN evenement ON participant.id_evenement = evenement.id_evenement WHERE evenement.id_evenement = $event LIMIT $debut, $nb_element_page");
        $info->execute();
        $info->setFetchMode(PDO::FETCH_ASSOC);
        $infos = $info->fetchAll();
    } else {
        $page = 1;
        $nb_page = 1;
    }

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des participants</title>
    <link rel="stylesheet" type="text/css" href="./style/liste.css" >
</head>
<body>

    <!-- HEADER -->

    <header >
        <h1>simplon cote d'ivoire</h1>
    </header>
    
    <!-- MAIN -->

    <main class="container">
        <div>
            <label>evenement : </label>
            <select id="event">
                <option value=<?php if(!empty($event)){echo $event;}else{} ?> name=<?php if(!empty($event)){echo $event;}else{} ?>>choisir un evenement <?php //echo $infos[0]['titre_evenement']?></option>
                <?php for ($i = 0; $i < count($evenements); $i++) { ?> 
                    <option value=<?php echo $evenements[$i]['id_evenement'] ?> name=<?php echo $evenements[$i]['id_evenement'] ?>><?php echo $evenements[$i]['titre_evenement']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="info">
            <p>total participant : <?php if(!empty($nb_participants[0]['cpt'])) {echo $nb_participants[0]['cpt'];}else{} ?></p>
            <p style="font-size:1em; text-transform: uppercase"><?php if (empty($infos[0]['titre_evenement'])) { echo ""; } else { echo $infos[0]['titre_evenement']; } ?></p>
            <p><?php echo "$page / $nb_page page(s)"; ?></p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>nom</th>
                    <th>prenoms</th>
                    <th>numero telephone</th>
                    <th>adresse email</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($infos)) { $num = 1; for ($i = 0; $i < count($infos); $i++) { ?>
                    <tr> 
                        <td><?php echo $num++ ?></td>
                        <td><?php echo $infos[$i]['nom_participant'] ?></td>
                        <td><?php echo $infos[$i]['prenoms_participant'] ?></td>
                        <td><?php echo $infos[$i]['numero_telephone_participant'] ?></td>
                        <td><?php echo $infos[$i]['adresse_email_participant'] ?></td>
                    </tr>
                <?php }} else {} ?>
            </tbody>
        </table>

        <div class="button">
            <input type="button" id="back" value="retour">
            <input type="button" id="prev" value="precedant" <?php if ($page == 1) { echo 'disabled'; }?>>
            <input type="button" id="next" value="suivant" <?php if ($page == $nb_page) { echo 'disabled'; }?>>
            <input type="button" id="print" value="imprimer">
        </div>
    </main>

    
    <!-- FOOTER -->

    <footer>
        <marquee><p>&copy; Simplon Cote d'Ivoire 2023</p></marquee>
    </footer>
    
    <!-- SCRIPT -->

    <script>
        const back = document.getElementById('back');
        back.addEventListener('click', ()=>{
            document.location.href='./index.php'
        });
        
        const prev = document.getElementById('prev');
        prev.addEventListener('click', ()=>{
            document.location.href='./liste.php?event=<?= $event?>&page=<?= --$page ?>'
        });

        const next = document.getElementById('next');
        next.addEventListener('click', ()=>{
            document.location.href='./liste.php?event=<?= $event?>&page=<?= $page +=2  ?>'
        });

        const print = document.getElementById('print');
        print.addEventListener('click', ()=>{
            event.style='display: none'
            back.style='display: none'
            prev.style='display: none'
            next.style='display: none'
            print.style='display: none'
            document.location.tagert='_blank'
            window.print()
        });

        const event = document.getElementById('event')
        event.addEventListener('change', ()=>{
            document.location.href='./liste.php?event='+ event.value +'&page=1'
        })        
    </script>
</body>
</html>