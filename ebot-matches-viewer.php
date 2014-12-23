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
Version: 1.0
Author URI: www.boudjelalyannick.fr
*/

add_action('widgets_init','emv_init');

function emv_init(){
	register_widget("emv_widget");
}

class emv_widget extends WP_widget{
	
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
	function widget($args,$d){
		extract($args);
		echo $before_widget;
		echo $before_title.$d["titre"].$after_title;
		?>
		<?
			if ($d["typeconnect"] == "A"){
				/* Connection Distante mysql */
				$host= $d["host"];
				$dbnamedist= $d["dbnamedistant"];
				$userdist= $d["userdistant"];
				$passworddist= $d["passworddistant"];
				$nbrmax= $d["nbrmax"];
					try{
					$bdd = new PDO('mysql:host='.$host.';dbname='.$dbnamedist.'', ''.$userdist.'', ''.$passworddist.'');
					$affreq = $bdd->prepare('SELECT id, team_a_name, team_b_name, score_a, score_b FROM matchs ORDER BY id DESC LIMIT 0, '.$nbrmax.'');
					$affreq->execute();
					}
					catch (Exception $e)
					{
							die('Erreur : ' . $e->getMessage());
					}			
			}
			if ($d["typeconnect"] == "B"){
				echo "Option B en developement";
			}
			if ($d["typeconnect"] == "C"){
				$dbnamelocal= $d["dbnamelocal"];
				$userlocal= $d["userlocal"];
				$passwordlocal= $d["passwordlocal"];
				$nbrmax= $d["nbrmax"];
					try{
					$bdd = new PDO('mysql:host=localhost;dbname='.$dbnamelocal.'', ''.$userlocal.'', ''.$passwordlocal.'');	
					$req = 'SELECT id, team_a_name, team_b_name, score_a, score_b FROM matchs ORDER BY id DESC LIMIT 0, '.$nbrmax.'';
					
					echo 'Connecter à la base Mysql locale. <br />';	
					}
					catch (Exception $e)
					{
							die('Erreur : ' . $e->getMessage());
					}	
			}			
					echo'<table class="ebot">';
					echo "<tr><th class='ebot'>#Id</th><th class='ebot'>Score</th></tr>";
					while($row = $affreq->fetch(PDO::FETCH_ASSOC)){
						$team1name= $row['team_a_name'];
						$team2name= $row['team_b_name'];
						$team1scr= $row['score_a'];
						$team2src= $row['score_b'];

						echo "<tr class='border_bottom'><td>";
						echo '<a href="http://server.team-nostress.com/eBot-CSGO/matchs/view/'.$row['id'].'" target="_blank">'.$row['id'].'</a>';
						echo '</td><td><a href="http://server.team-nostress.com/eBot-CSGO/matchs/view/'.$row['id'].'" target="_blank">';
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
		$bdd == null;
	}
	function update($new,$old){
		return $new;
	}
	function form($d) {
		$defaut = array(
			"titre" => "eBoT Matches",
			"nbrmax" => "5",
			"userdistant" => "ebotv3",
			"userlocal" => "ebotv3",
			"dbnamedistant" => "ebotv3",
			"dbnamelocal" => "ebotv3"
		);
		$d = wp_parse_args($d,$defaut)
		?>
			<div id="form">
			<p>
				<label for="<?php echo $this->get_field_id("titre"); ?>">Titre : </label>
				<input value="<?echo $d["titre"];?>" name="<?php echo $this->get_field_name("titre"); ?>" id="<?php echo $this->get_field_id("titre"); ?>" type="text"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id("nbrmax"); ?>">Nombre max de match : </label>
				<input value="<?echo $d["nbrmax"];?>" name="<?php echo $this->get_field_name("nbrmax"); ?>" id="<?php echo $this->get_field_id("nbrmax"); ?>" type="text" maxlength="1"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id("typeconnect"); ?>">Type de connection : </label>
				<select value="<?echo $d["typeconnect"];?>" name="<?php echo $this->get_field_name("typeconnect");?>" id="<?php echo $this->get_field_id("typeconnect"); ?>">
					<option value="A">Distant (Option:A)</option>
					<option value="B">Online (Option:B)</option>
					<option value="C">Local (Option:C)</option>
				</select>
			</p>
			</div>
			<div id="A" class="divoption">
				<p>Option A "Distant":</p>
					<p>
						<label for="<?php echo $this->get_field_id("host"); ?>">Ip du Host : </label>
						<input value="<?echo $d["host"];?>" name="<?php echo $this->get_field_name("host"); ?>" id="<?php echo $this->get_field_id("host"); ?>" type="text"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id("dbnamedistant"); ?>">Nom de la Base de donnée : </label>
						<input value="<?echo $d["dbnamedistant"];?>" name="<?php echo $this->get_field_name("dbnamedistant"); ?>" id="<?php echo $this->get_field_id("dbnamedistant"); ?>" type="text"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id("userdistant"); ?>">Utilisateur "login" : </label>
						<input value="<?echo $d["userdistant"];?>" name="<?php echo $this->get_field_name("userdistant"); ?>" id="<?php echo $this->get_field_id("userdistant"); ?>" type="text"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id("passworddistant"); ?>">Password : </label>
						<input value="<?echo $d["passworddistant"];?>" name="<?php echo $this->get_field_name("passworddistant"); ?>" id="<?php echo $this->get_field_id("passworddistant"); ?>" type="text"/>
					</p>
			</div>
			<hr>
			<div id="B" class="divoption">
				<p>Option B "Online": "Non fonctionnel"</p>
					<p> En developement !
						<!-- <label for="<?php echo $this->get_field_id("nom-team"); ?>">Nom Team à afficher : </label>
						<input value="<?echo $d["nom-team"];?>" name="<?php echo $this->get_field_name("nom-team"); ?>" id="<?php echo $this->get_field_id("nom-team"); ?>" type="text"/> -->
					</p>
			</div>
			<hr>
			<div id="C" class="divoption">
				<p>Option C "Local:</p>
					<p>
						<label for="<?php echo $this->get_field_id("dbnamelocal"); ?>">Nom de la Base de donnée : </label>
						<input value="<?echo $d["dbnamelocal"];?>" name="<?php echo $this->get_field_name("dbnamelocal"); ?>" id="<?php echo $this->get_field_id("dbnamelocal"); ?>" type="text"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id("userlocal"); ?>">Utilisateur "login" : </label>
						<input value="<?echo $d["userlocal"];?>" name="<?php echo $this->get_field_name("userlocal"); ?>" id="<?php echo $this->get_field_id("userlocal"); ?>" type="text"/>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id("passwordlocal"); ?>">Password : </label>
						<input value="<?echo $d["passwordlocal"];?>" name="<?php echo $this->get_field_name("passwordlocal"); ?>" id="<?php echo $this->get_field_id("passwordlocal"); ?>" type="text"/>
					</p>
			</div>
			<p>Merci DeStrO pour l'eBOt. Widget dev. par Bouman.</p>
		<?
	}
}	
?>