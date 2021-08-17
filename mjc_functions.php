<?php
// ******************************************************************************************************* FRONT OFFICE *******************************************************************************
/*
 * Insertion du jeu coucours dans un emplacement designé par le client
 */
// Récupération du Hook action : 'shortcode' , exécution de la fonction 'mjc_shortcode'
 add_shortcode('mon_jeu_concours', 'mjc_shortcode_fctn' );

function mjc_shortcode_fctn(){
    echo "<div class='wrap'>";
    // Le joueur a cliqué sur "PARTICIPER" etape 2/4-----------------------------------------------------------------
    if(isset ($_GET['step'])){
    // Le joueur a cliqué sur "ENVOYER" 4/4----------------------------------------------------------------------
        if (isset($_POST['send_data'])){
            // connection a la BDD
            global $wpdb;
            $table = $wpdb->prefix."mjc_plugin_user";
            $datas = array(
                    'user_gender'=>$_POST['user_gender'],
                    'user_name'=>$_POST['user_name'],
                    'user_firstname'=>$_POST['user_firstname'],
                    'user_email'=>$_POST['user_email'],
                    'user_birthdate'=>$_POST['user_birthdate'],
                    'user_address'=>$_POST['user_address'],
            );
            $wpdb->insert($table,$datas,$array);
             if (!$wpdb){
                 echo "<h2>Une erreur c'est produite lors de votre inscription, veuillez recommencer.<h2><br><a href='mon_plug_in.php'>Recommencer</a>";
             }else{

// empecher de rejouer a cette emplacement


                 $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "mjc_plugin_game_settings ORDER BY id DESC LIMIT 1");
                 $results = $wpdb->get_results($query);
                 foreach ($results as $result){
                 // utilisation de la fonction htmlspecialchars pour prevenir les failles XSS
                 $user_firstname = htmlspecialchars($_POST['user_firstname']);
                 echo "<h2>Félicitation <b>".$user_firstname."</b> vous êtes inscrit au tirage au sort du concours! </h2>
                    <p>Les gagnants seront contactés à l'issue du tirage au sort qui aura lieu le <b>".$result->end_date."</b></p>
                    <form method='get'>
                    <input type='submit' name='' value='Revenir au jeu'>";
                 }
             }
    // Le joueur a cliqué sur "PARTICIPER" mais pas encore envoyer les reponses 3/4----------------------------------------------------------------------
        }else{
            echo "<h2>Pour <b>participer</b> au jeux, veuillez renseigner les informations suivantes :</h2>
            <form method='post'>
                <label for='Civilité'>Civilité :</label> 
                <input type='radio' name='user_gender' value='M' id='M_gender' /><label for='M'>Monsieur</label>
                <input type='radio' name='user_gender' value='F' id='F_gender' checked='checked'/><label for='F'>Madame</label><br>
                
                <label for='user_name'>Nom :</label><input type='text' name='user_name' id='user_name' required/><br>
                <label for='user_firstname'>Prénom :</label><input type='text' name='user_firstname' id='user_firstname' required/><br>
                <label for='user_email'>Email :</label><input type='email' name='user_email' id='user_email' required/><br>
                <label for='user_birthdate'>Date de naissance :</label><input type='date' name='user_birthdate' id='user_birthdate' required/><br>
                <label for='user_address'>Adresse :</label><input type='text' name='user_address' id='user_address' required/><br>
                <br>
                <input type='submit' name='send_data' value='Envoyer' />
            </form>";
        }  
    //  OU le joueur a cliqué sur "VOIR LE REGLEMENT" ----------------------------------------------------------------- 
    }else{
        if(isset($_GET['rules'])){
            global $wpdb;
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "mjc_plugin_game_settings ORDER BY id DESC LIMIT 1");
            $results = $wpdb->get_results($query);
            foreach($results as $result){
            
            $start_date=$result->start_date;
            $end_date=$result->end_date;
            $gifts=$result->gifts;
            $company_name=$result->company_name;
            $company_address=$result->company_address;
            $web_site=$result->web_site;

            echo "<h2>Règlement</h2>
                    
                    <h3> ARTICLE 1 - ORGANISATEUR ET DUREE DU JEU-CONCOURS<h3>
                    <p>Le présent jeu-concours est organisé par <b>$company_name</b> ,
                    domicilié(e) <b>$company_address</b> , désigné ci-après
                    « l’Organisateur ».
                    Le jeu-concours se déroulera du <b>$start_date</b>  au <b>$end_date</b> 
                    (date et heure française de connexion faisant foi).</p>;
                
                    <h3>ARTICLE 2 - CONDITIONS DE PARTICIPATION AU JEU-CONCOURS<h3>
                    <p>Le jeu-concours est ouvert à toute personne physique résidant en France
                    métropolitaine. Les participants doivent être âgés de plus de 18 ans.
                    Le jeu-concours est limité à une seule participation par personne (par
                    exemple même nom, même prénom et même adresse email). La participation
                    au jeu-concours est strictement personnelle et nominative. Il ne sera
                    attribué qu’un seul lot par personne désignée gagnante.
                    La participation au jeu-concours implique l’acceptation irrévocable et sans
                    réserve, des termes et conditions du présent règlement (le « Règlement »),
                    disponible sur le site:  <b>$web_site</b> .
                    Le non-respect des conditions de participation énoncées dans le présent
                    Règlement entraînera la nullité de la participation du Participant.
                    Le jeu est soumis à la réglementation de la loi française applicable aux jeux
                    et concours.</p>
        
                    <h3>ARTICLE 3 - PRINCIPE DE JEU / MODALITES DE PARTICIPATION<h3>
                    <p>Pour valider sa participation, chaque participant doit dûment s’inscrire répondant à
                    un formulaire mis en ligne, avant la fermeture du jeu. Chaque internaute en
                    s’inscrivant au jeu obtient une chance d’être tiré au sort.</p>
                    
                    <h3>ARTICLE 4 – DÉSIGNATION DES GAGNANTS<h3>
                    <p>L’Organisateur désignera par tirage au sort les gagnants, parmi l’ensemble des
                    personnes s’étant inscrites. Un tirage au sort sera effectué le   <b>$end_date</b>  . Un seul lot sera attribué par gagnant (même nom, même prénom, même adresse
                    email)</p>
                
                    <h3>ARTICLE 5 – DOTATIONS<h3>
                    <p>Les dotations des tirages au sort sont les suivantes :
                    <b>$gifts</b>  </p>
                    
                    <h3>ARTICLE 6 - REMISE DES DOTATIONS ET MODALITÉS
                    D’UTILISATION DES DOTATIONS<h3>
                    <p>L’Organisateur du jeu-concours contactera uniquement par courrier électronique les
                    Gagnants tirés au sort et les informera de leur dotation et des modalités à suivre
                    pour y accéder. Aucun courrier ne sera adressé aux participants n’ayant pas gagné,
                    seuls les gagnants seront contactés. Les gagnants devront répondre dans les deux
                    (2) jours suivants l’envoi de ce courrier électronique et fournir leurs coordonnées
                    complètes. Sans réponse de la part du gagnant dans les deux (2) jours suivants
                    l’envoi de ce courrier électronique, il sera déchu de son lot et ne pourra prétendre à
                    aucune indemnité, dotation ou compensation que ce soit. Dans cette hypothèse, les
                    lots seront attribués à un suppléant désignés lors d'un tirage au sort parmis les participants.
                    Les gagnants devront se conformer au présent règlement. S’il s’avérait
                    qu’ils ne répondent pas aux critères du présent règlement, leur lot ne leur sera pas
                    attribué et sera acquis par l’Organisateur. À cet effet, les participants autorisent
                    toutes les vérifications concernant leur identité, leur âge, leurs coordonnées ou la
                    loyauté et la sincérité de leur participation. Toute fausse déclaration, indication
                    d’identité ou d’adresse postale fausse entraîne l’élimination immédiate du
                    participant et l’acquisition du lot par l’Organisateur. En outre, en cas d’impossibilité
                    pour l’Organisateur de délivrer au(x) gagnant(s) la dotation remportée, et ce, quel
                    qu’en soit la cause, L’Organisateur se réserve le droit d’y substituer une dotation de
                    valeur équivalente, ce que tout participant consent. </p>
        
                    <h3>ARTICLE 7 – UTILISATION DES DONNÉES PERSONNELLES DES
                    PARTICIPANTS<h3>
                    <p>Conformément à la loi Informatique et Libertés du 6 janvier 1978, les participants
                    au jeu concours bénéficient auprès de l’Organisateur, d’un droit d’accès, de
                    rectification (c’est à-dire de complément, de mise à jour et de verrouillage) et de
                    retrait de leurs données personnelles. Les informations personnelles des
                    participants sont collectées par l’Organisateur uniquement à des fins de suivi du
                    jeu-concours, et sont indispensables pour participer à celle-ci.</p>
                
                    <h3>ARTICLE 8 – RESPONSABILITÉ<h3>
                    <p>L’Organisateur ne saurait voir sa responsabilité engagée du fait de
                    l’impossibilité de contacter chaque gagnant, de même qu’en cas de perte, de
                    vol ou de dégradation du lot lors de son acheminement. L’Organisateur ne
                    pourra non plus être responsable des erreurs éventuelles portant sur le nom,
                    l’adresse et/ou les coordonnées communiquées par les personnes ayant
                    participé au jeu-concours. Par ailleurs, l’Organisateur du jeu concours décline
                    toute responsabilité pour tous les incidents qui pourraient survenir lors de la
                    jouissance du prix attribué et/ou fait de son utilisation et/ou de ses
                    conséquences, notamment de la jouissance d’un lot par un mineur, qui reste
                    sous l’entière et totale responsabilité d’une personne ayant l’autorité
                    parentale. L’Organisateur se réserve le droit, si les circonstances l’exigent,
                    d’écourter, de prolonger, de modifier, d’interrompre, de différer ou
                    d’annuler le jeu-concours, sans que sa responsabilité ne soit engagée.
                    Toutefois, toute modification fera l’objet d’un avenant qui sera mis en ligne
                    sur le Site et adressé gratuitement à toute personne ayant fait une demande
                    de règlement par écrit conformément aux dispositions de l’article 10 cidessous. 
                    L’Organisateur se dégage de toute responsabilité en cas de
                    dysfonctionnement empêchant l’accès et/ ou le bon déroulement du jeuconcours 
                    notamment dû à des actes de malveillances externes. L’utilisation
                    de robots ou de tous autres procédés similaires permettant de participer au
                    jeu-concours de façon mécanique ou autre est proscrite, la violation de cette
                    règle entraînant l’élimination définitive de son réalisateur et/ ou utilisateur.
                    L’Organisateur pourra annuler tout ou partie du jeu-concours s’il apparaît que
                    des fraudes sont intervenues sous quelque forme que ce soit, notamment de
                    manière informatique dans le cadre de la participation au jeu-concours ou de
                    la détermination des gagnants. Il se réserve, dans cette hypothèse, le droit
                    de ne pas attribuer les dotations aux fraudeurs et/ ou de poursuivre devant
                    les juridictions compétentes les auteurs de ces fraudes. 
                    </p>
                    
                    <h3>ARTICLE 9 – ACCESSIBILITÉ DU RÈGLEMENT<h3>
                    <p>Le règlement peut être consulté librement depuis le site   <b>$web_site</b>   à tout moment ou encore,
                     envoyé gratuitement par
                    l’Organisateur sur simple demande écrite émanant de tout participant en
                    écrivant à l’adresse postale du jeu-concours visible à l’article 10 du présent
                    règlement. Le participant souhaitant obtenir le remboursement des frais
                    postaux liés à cette demande de règlement, doit le préciser dans sa demande
                    (remboursement sur la base d’une lettre simple de moins de 20 g affranchie
                    au tarif économique en vigueur).</p> 
        
                    <h3>ARTICLE 10 – ADRESSE POSTALE DU JEU-CONCOURS<h3>
                    <p>Pour toute demande, l’adresse postale destinataire des courriers est
                    mentionnée ci-après :   <b>$company_name  -   $company_address</b>  .</p>
                    
        
                    <h3>ARTICLE 11 – LOI APPLICABLE<h3>
                    <p>Les participants admettent sans réserve que le simple fait de participer à ce
                    jeu concours les soumet à la loi française. Toute contestation doit être
                    adressée à l’adresse mentionnée dans l’article 10 au plus tard le   <b>$end_date</b> 
                     inclus (cachet de la poste faisant foi).</p>
                </div>
                <form method='get'>
                <input type='submit' name='' value='Revenir au jeu'>";
            }

    // Le joueur n'a pas encore cliqué sur "PARTICIPER" etape 1/3-----------------------------------------------------------------
        }else{
            global $wpdb;
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "mjc_plugin_game_settings ORDER BY id DESC LIMIT 1");
            $results = $wpdb->get_results($query);
            foreach($results as $result){
                echo "<h2>PARTICIPEZ</h2>
                    <h2>Tentez de gagner <b>".$result->gifts."</b>!</h2>
                    <p>Tirage au sort le <b>".$result->end_date."</b></p>
                    <p> Pour jouer, c’est très simple.
                    <br> Un formulaire à remplir et nous ferons ensuite un tirage au sort ! <b><br>".$result->winners_nbr."</b> gagnant(s) sont/seront selectionné(s)<br>
                      Bonne chance à tous !</p>";
                echo "  <form method='get'>
                            <input type='submit' name='step' value='Participer'>
                        </form>
                        <form method='get'>
                            <input type='submit' name='rules' value='Voir le réglement'>
                        </form>";          
            }
            ;
        }
    }
    echo "</div class='wrap'>";
}
// ******************************************************************************************************* BACK OFFICE ************************************************************************************************************************************************************

/*
 * Ajout d'un nouveau menu dnas l'administration du backoffice de WP
 */

 // Récupération du Hook action 'admin_menu', exécution de la fonction: 'mjc_Add_My_Admin_Link()'
add_action( 'admin_menu', 'mjc_Add_My_Admin_Link' );

function mjc_Add_My_Admin_Link()
{
    add_menu_page(
        'reglage', // Title of the page
        'Mon jeu concours', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'includes/backoffice.php', // The 'slug' - file to display when clicking the link
        'mjc_backoffice_fctn'
    );
}

function mjc_backoffice_fctn() {
    // ****************** Générer ou modifier votre jeux-concours ***************************************************
    // Connection a la BDD
    echo "<div class='wrap'>";
    global $wpdb;
    // Le client a cliqué sur "ENVOYER" -> les données sont enregistrées dans la BDD
    if(isset($_POST['game_settings_bdd_data'])){
        echo "<style>
            form{
                text-align:center;
            }
            h1,p,h2{
                text-align: center;
            }
            p{
                text-align: center;
            }
            #contributor{
                justify-content: center;
            }
            </style>";
            
        echo "<h1>Mon jeux concours - réglages</h1>
        <h2>Générer ou modifier votre jeux-concours:</h2>
        <p>Atttention votre règlement et les règles du jeux visibles par les participants sont etablis dans ces champs :</p>
            <form method='post'>
            <label for='start_date'>Date de mise en place : </label><input type='date' name='start_date' id='start_date' required/><br>
            <label for='end_date'>Date du tirage au sort : </label><input type='date' name='end_date' id='end_date' required/><br>
            <label for='gifts'>Lots à gagner :</label><input type='text' name='gifts' id='gifts' required/><br>
            <label for='winners_nbr'>Nombre de gagnants :</label><input type='number' name='winners_nbr' id='winners_nbr' required/><br>
            <br>
            <label for='company_name'>Votre nom ou marque: </label><input type='text' name='company_name' id='company_name' required/><br>
            <label for='company_address'>Votre adresse postale </label><input type='text' name='company_address' id='company_address' required/><br>
            <label for='web_site'>L'adresse URL final de votre site :</label><input type='text' name='web_site' id='web_site' required/><br><br>
            <input type='submit' name='game_settings_bdd_data' value='Envoyer' class='button button-primary' />
        </form>";

        $today = date("Y-m-d");

        // preparation d'une requete preparée ***********
        $query = $wpdb->prepare("INSERT INTO ". $wpdb->prefix . "mjc_plugin_game_settings(start_date,end_date,gifts,winners_nbr,company_name,company_address,web_site) VALUES(?, ?, ?, ?, ?, ?, ?)");

        //controle des date de depart et tirage au sort
        if ($_POST['end_date']<=$_POST['start_date']){
        
            echo "<script>alert(\"La date de tirage au sort ne peut pas etre anterieur à celle de mise en place\")</script>";
            
        } elseif($_POST['end_date']<= $today ){
            echo "<script>alert(\"La date de tirage au sort ne peut pas etre anterieur à celle actuelle\")</script>";
            
        } else{
        // execution avec les valeur fournies par la methode POST
            $table = $wpdb->prefix."mjc_plugin_game_settings"; ;
            $datas = array(
            'start_date'=>$_POST['start_date'],
            'end_date'=>$_POST['end_date'],
            'gifts'=>$_POST['gifts'],
            'winners_nbr'=>$_POST['winners_nbr'],
            'company_name'=>$_POST['company_name'],
            'company_address'=>$_POST['company_address'],
            'web_site'=>$_POST['web_site']
            );
            $wpdb -> insert($table,$datas,$array);    
    }
    // le client n'a pas appuyé sur "ENVOYER" -> le formlaire s'affiche + les parametres enregistrés
    }else{
       
        echo "<style>
            form{
                text-align:center;
            }
            h1,p,h2{
                text-align: center;
            }
            p{
                text-align: center;
            }
            #contributor{
                justify-content: center;
            }
            </style>";
            
        echo "<h1>Mon jeux concours - réglages</h1>
        <h2>Générer ou modifier votre jeux-concours:</h2>
        <p>Atttention : votre règlement et les règles du jeu visibles par les participants sont definis dans ces champs :</p>
        <hr>
            <form method='post'>
            <p>La date de tirage au sort ne peut pas etre anterieur à celle de mise en place et à la date actuelle.</p>

            <label for='start_date'>Date de mise en place : </label><input type='date' name='start_date' id='start_date' required/><br>
            <label for='end_date'>Date du tirage au sort : </label><input type='date' name='end_date' id='end_date' required/><br>

            <p>Placez un article indefini ou un chiffre avant le gain : exemple une voiture / 1 voiture.</p>

            <label for='gifts'>Lots à gagner :</label><input type='text' name='gifts' id='gifts' required/><br>

            <p>Definissez ici le nombre de gagnants que l'ordinateur choisira au hasard parmis la liste des participants.</p>
            <label for='winners_nbr'>Nombre de gagnants :</label><input type='number' name='winners_nbr' id='winners_nbr' required/><br>
            <br>

            <p>Les informations suivantes seront visibles dans le règlement de votre jeu concours:</p>
            <label for='company_name'>Votre nom ou marque: </label><input type='text' name='company_name' id='company_name' required/><br>
            <label for='company_address'>Votre adresse postale </label><input type='text' name='company_address' id='company_address' required/><br>
            <label for='web_site'>L'adresse URL final de votre site :</label><input type='text' name='web_site' id='web_site' required/><br><br>
            <input type='submit' name='game_settings_bdd_data' value='Envoyer' class='button button-primary' />
        </form><hr> "; 
          
    }
    // ******************************** Données enregistrées *****************************

    echo "<h2>Vos paramètres enregistrés: </h2>";
    //  Affichage des données enregistrées dans la bdd, on demande uniquement la dernier entrée de la table en cas de mise a jour des données par l'utlisateur
    // global de la base de données de WP
    global $wpdb;
    // on prepare la requete pour contrer les injection SQL
    $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "mjc_plugin_game_settings ORDER BY id DESC LIMIT 1");
    // mets les resultats de la requete dans une variable $results 
    $results = $wpdb->get_results($query);
    if (isset($results)){
        foreach ($results as $result){
            echo "<p>Date de mise en place prévue: <b>". $result->start_date ."</b><br>Date du tirage au sort prévue: <b>"
             .$result->end_date.'</b><br>Gains en jeux: <b>'.$result->gifts."</b><br>Nombres de gagnants prévus: <b>"
             .$result->winners_nbr."</b></p><hr>";
        } 
    }
    
    // ******************************** tirage au sort*****************************
    echo "<h2>Le tirage au sort : </h2>";
   
    // Recuperaton du nombre de gagnants designé dans la bdd afin de lancer la requete qui selectionne au hasard le nombre ($winners) de gagnants.

    global $wpdb;
    $query = $wpdb->prepare("SELECT winners_nbr FROM " . $wpdb->prefix . "mjc_plugin_game_settings ORDER BY id DESC LIMIT 1");
    $results = $wpdb->get_results($query);

    foreach($results as $result){
    
        $winners= $result->winners_nbr;
    }
    
    if(isset($_POST['winner_check'])){

    // Selection au hassar par la fonction RAND(), et nombre de gagnants
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "mjc_plugin_user ORDER BY RAND() LIMIT $winners");
        $results = $wpdb->get_results($query);
        foreach($results as $result){
                echo '<p>Votre grand gagnant est <b>'.$result->user_firstname.' '
                .$result->user_name.'</b> '.'('
                .$result->user_gender.')'.'<br>'.'né(e) le <b> '
                .$result->user_birthdate.'</b><br>'.'vivant à l adresse: <b>'
                .$result->user_address.'</b><br>'.'Email: <b>'
                .$result->user_email.'</b><br>'."Date d'inscription: <b>" 
                .$result->user_timestamp.'</b><br>'.' Joueur numero: <b>'
                .$result->id.'</b></p>';
        }
            
    }
    echo "<p class='mjc_css_color:red'>Attention : cliquez une seule fois sur <b>Tirage au sort</b> et uniquement lorsque vous <b>décidez de cloturer</b> votre concours !</p>
        <p>
            <form method='post'>
            <input type='submit' name='winner_check' value='Tirage au sort' class='button button-primary'>
            </form>
        </p>";

    // Affichage des participants
    echo '<hr><h2>Liste de tous les participants(es) à votre jeu-concours :</h2>';
    // selection dans la BDD de tous les champs de User et placé dans une variable $reponse
    global $wpdb;
    $query = $wpdb->prepare(" SELECT * FROM ". $wpdb->prefix . "mjc_plugin_user ORDER by id");
    $results = $wpdb->get_results($query);

        echo '<p><table id="contributor">'; 
        echo "<th>Numero de participation</th><th>Heure d'inscription</th><th>Genre</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Date d'anniversaire</th><th>Adresse</th>"; //table headers
        foreach($results as $result){
            echo'<tr>';
            echo '<td>'.$result->id.'</td><td>'.$result->user_timestamp.'</td><td>'.$result->user_gender.'</td><td>'
                .$result->user_name.'</td><td>'.$result->user_firstname.'</td><td>'.$result->user_email.'</td>'.'</td><td>'
                .$result->user_birthdate.'</td><td>'.$result->user_address; 
            echo'</tr>';
        }
        echo '</table></p>'; 
    die();
    echo "</div class='wrap'>";
}


    // <!- ************************* copyright ***************************************
    // ************** Plug_in concours tirage au sort par Frederic Castel *************
    // ******************************************************************************** -->



    // PROBLEME DE CONNECTION A LA BDD ET AUX TABLE, UTILISATION DE LA GLOBALE $WPDB.  
