<!-- Ici Le CSS du tableau d'affichage -->
<style>
    table.ebot tr:nth-child(odd){
        background-color:#282828 ;
    }
    table.ebot {
        border: 0pt;
    }
    th.ebot {
        border: 1pt solid black;
        -moz-border-radius:10px;
        -webkit-border-radius:10px;
        border-radius:10px;
    }

    tr.border_bottom td {
        border-bottom: 1pt solid black;
        border-right: 0pt;
    }
</style>
<!-- FIN du CSS -->

<?php

/*
Plugin Name: eBot Matches Viewer
Plugin URI: https://github.com/Asso-nOStReSs/eBot-matches-viewer
Description: Un simple widget pour intégrer les matchs de l'eBot sur votre site communautaire.
Author: Boudjelal Yannick *Bouman*
Version: 1.6
Author URI: www.boudjelalyannick.fr
*/

add_action('widgets_init','emv_init');

function emv_init(){
    register_widget("emv_widget");
}

class emv_widget extends WP_widget{
    /**
     * @var PDO
     */
    protected $connection;

    function emv_widget() {
        $options = array(
            "classname"=>"ebot-matches",
            "description"=>"Affiche les scrores, effectuer avec l'eBot sur vos serveurs."
        );
        /*	$control = array(
                "width"=>1000,
                "height"=>500
            );
        */
        $this->WP_widget("emv-ebot-matches","eBoT Matches Viewer",$options);
    }

    function initConnection($host, $name, $user, $password)
    {
        if (empty($this->connection)) {
            try {
                $this->connection = new PDO('mysql:host='.$host.';dbname='.$name, $user, $password);
            } catch (PDOException $e) {
                echo 'Connexion échouée : ' . $e->getMessage();

                return false;
            }
        }

        return true;
    }

    function getMatches($nbrMax)
    {
        try{
            $query = $this->connection->prepare('SELECT id, team_a_name, team_b_name, score_a, score_b FROM matchs ORDER BY id DESC LIMIT 0, '.$nbrMax);

            if ($query->execute()) {
                return $query->fetchAll();
            }
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
    }

    function widget($args,$d){
        extract($args);
        echo $before_widget;
        echo $before_title.$d["titre"].$after_title;

        $nbrmax = $d["nbrmax"];
        $web    = $d["web"];

        $this->initConnection($d['dbhost'], $d['dbname'], $d['dbuser'], $d['dbpassword']);

        try{
            $matches = $this->getMatches($nbrmax);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        /* TABLEAU */
        echo'<table class="ebot">';
        echo "<tr><th class='ebot'>#Id</th><th class='ebot'>Score</th></tr>";

        foreach($matches as $match){
            $team1name= $match['team_a_name'];
            $team2name= $match['team_b_name'];
            $team1scr= $match['score_a'];
            $team2src= $match['score_b'];

            echo "<tr class='border_bottom'><td>";
            echo '<a href="http://'.$web.'/eBot-CSGO/matchs/view/'.$match['id'].'" target="_blank">'.$match['id'].'</a>';
            echo '</td><td><a href="http://'.$web.'/eBot-CSGO/matchs/view/'.$match['id'].'" target="_blank">';

            if($team1scr>$team2src)
                echo '<strong>'.$team1name.'&nbsp;-&nbsp;<font color="green">'.$team1scr.'</strong></font>&nbsp;:&nbsp;<font color="red">'.$team2src.'</font>&nbsp;-&nbsp;'.$team2name.'';
            elseif($team1scr<$team2src)
                echo ''.$team1name.'&nbsp;-&nbsp;<font color="red">'.$team1scr.'</font>&nbsp;:&nbsp;<font color="green">'.$team2src.'</font>&nbsp;-&nbsp;<strong>'.$team2name.'</strong>';
            else
                echo ''.$team1name.'&nbsp;-&nbsp;<font color="bleue">'.$team1scr.'</font>&nbsp;:&nbsp;<font color="bleue">'.$team2src.'</font>&nbsp;-&nbsp;'.$team2name.'';
            echo "</a></td></tr>";
        }
        echo'</table>';
        echo $after_widget;
    }

    function update($new,$old){
        return $new;
    }

    function form($d) {
        $defaut = array(
            "titre" => "eBoT Matches",
            "nbrmax" => "5",
            "dbhost" => "localhost",
            "dbuser" => "ebotv3",
            "dbname" => "ebotv3"
        );
        $d = wp_parse_args($d,$defaut)
        ?>

        <div id="form">

            <p>

                <label for="<?php echo $this->get_field_id("titre"); ?>">Titre : </label>

                <input value="<?php echo $d["titre"];?>" name="<?php echo $this->get_field_name("titre"); ?>" id="<?php echo $this->get_field_id("titre"); ?>" type="text"/>

            </p>

            <p>

                <label for="<?php echo $this->get_field_id("nbrmax"); ?>">Nombre max de match : </label>

                <input value="<?php echo $d["nbrmax"];?>" name="<?php echo $this->get_field_name("nbrmax"); ?>" id="<?php echo $this->get_field_id("nbrmax"); ?>" type="text" maxlength="1"/>

            </p>

        </div>

        <div id="A" class="divoption">

            <p>

                <label for="<?php echo $this->get_field_id("web"); ?>">Website eBot : </label>

                <input value="<?php echo $d["web"];?>" name="<?php echo $this->get_field_name("web"); ?>" id="<?php echo $this->get_field_id("web"); ?>" type="text"/>/eBot-CSGO/

            </p>

            <p>

                <label for="<?php echo $this->get_field_id("dbhost"); ?>">Ip du Host : </label>

                <input value="<?php echo $d["dbhost"];?>" name="<?php echo $this->get_field_name("dbhost"); ?>" id="<?php echo $this->get_field_id("dbhost"); ?>" type="text"/>

            </p>

            <p>

                <label for="<?php echo $this->get_field_id("dbname"); ?>">Nom de la Base de donnée : </label>

                <input value="<?php echo $d["dbname"];?>" name="<?php echo $this->get_field_name("dbname"); ?>" id="<?php echo $this->get_field_id("dbname"); ?>" type="text"/>

            </p>

            <p>

                <label for="<?php echo $this->get_field_id("dbuser"); ?>">Utilisateur "login" : </label>

                <input value="<?php echo $d["dbuser"];?>" name="<?php echo $this->get_field_name("dbuser"); ?>" id="<?php echo $this->get_field_id("dbuser"); ?>" type="text"/>

            </p>

            <p>

                <label for="<?php echo $this->get_field_id("dbpassword"); ?>">Password : </label>

                <input value="<?php echo $d["dbpassword"];?>" name="<?php echo $this->get_field_name("dbpassword"); ?>" id="<?php echo $this->get_field_id("dbpassword"); ?>" type="text"/>

            </p>

        </div>

        <p>Merci DeStrO pour l'eBOt. Widget dev. par Bouman.</p>

        <?php

    }

}

?>
