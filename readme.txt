=== Simple Staff List ===
Contributors: Boudjelal Yannick *Bouman*
Tags: eBot, matche, matches, match, matchs, 
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 3.8.1
License: 
License URI: https://github.com/Asso-nOStReSs/eBot-matches-viewer

Un simple widget pour intégrer les matchs de l'eBot sur votre site communautaire.
A simple widget to integrate the eBot matches on your community site.

== Description ==
Salut à tous,
Grâce à ce plugin d'affichage de résultats de matchs Counter-Strike Global Offensive (CSGO), vous n'avez plus besoin d'ajouter manuellement vos matchs CSGO dans vos panel d'admin de votre site web.
Vous avez juste à faire vos matchs sur vos serveur CSGO avec l'eBot et ce plugin récupère et affiche vos matchs sur votre site communautaire ou multi-gaming.
Simple et efficace.
////////////////////////
Hi everybody,
With this plugin display results of Counter-Strike Global Offensive (CSGO) Matches, you no longer need to manually add your matches CSGO in the admin panel of your website. 
You just have to make your games on your server with the CSGO EbOt and this plugin retrieves and displays your games on your community site or multi-gaming.
Simple and effective.

== Installation ==

1. Upload the `ebot-matches-viewer` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure options

Options "A" :  Your server Mysql eBot is different that website.

    IP-ebot           		 IP-web
     ebot                  serveur web
     ____                     ____
	|    |    Connection     |    |
	|    | <---------------> |    |
	|____|  mysql distant    |____|
	
	Step 1: Give power in phpmyadmin on Server eBot:
			SQL Command : 
				grant all privileges on *.* to user@IP-web identified by "destress";   ===> //// user = (Exemple: ebotv3)  IP-web = (Exemple: gw8.ovh.net) is your website is from OVH.
				flush privileges;
				
	Step 2: Connecting on dedicated server by SSH - Putty - :
			Command :
				nano /etc/mysql/my.cnf
			And edit :	
				Remplace : bind-address = 127.0.0.1
				to : #bind-address = 127.0.0.1
				
				//comments french:
				 Par défaut, MySQL n'écoute que localhost. . Il faudra désactiver la ligne (ajout de : '#') relative au bind-address dans le fichier de configuration mysql:
				# bind-address = 127.0.0.1
			Restart :
				/etc/init.d/mysql restart
				
Ready You can configure Widget for connect on database Distant.
				
Options "B" :  eBot online (http://ebot.esport-tools.net/) In development.

 ebot.esport-tools.net     	 IP-web
     ebot                  serveur web
     ____                     ____
	|    |    Connection     |    |
	|    | <---------------> |    |
	|____|  mysql distant    |____|
	
Options "C" :  Your server Mysql eBot and website are the same.

 IP-ebot = IP-web
 serveur web + ebot
     ________
	|        |
	|        |
	|________|
				
Ready You can configure Widget for connect on database local with user and password (Exemple : user=ebotv3 password=ebotv3).

2.Configure Number of matches to display

== Frequently Asked Questions ==

Write at : ndjbouman@gmail.com

== Screenshots ==
1. Options Widget
2. Widget Client view

== Changelog ==
= 1.5 = Preview
* Mode Option "B" eBot Online working.

= 1.0 =
Display last number matches
* Initial Plugin Launch