<?php
/*
Plugin Name: WP Compteur
Plugin URI: http://www.resoneo.com/
Description: Compteur du nombre d'articles refusés (supprimés) pour chaque utilisateur. Quand l'utilisateur atteint le nombre de refus définit, il devient abonné et ne peut plus proposer d'articles.
Author: Julien Deneuville
Version: 1.0
Author URI: http://www.diije.fr/
*/

//nombre de refus avant passage en abonné
global $max_refus;
$max_refus = 5;

global $compteur_version;
$compteur_version = 0.2;
global $wpdb;
global $table_compteur;
$table_compteur = $wpdb->prefix . "compteur";

/* Installation/Desinstallation du plugin */
function compteur_install() {
	global $wpdb;
	global $table_compteur;
	global $compteur_version;
	
	//si la table n'existe pas
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_compteur) {
		$sql = "CREATE TABLE " . $table_compteur . " (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			user_id bigint(20) DEFAULT 0 NOT NULL,
			compteur bigint(20) DEFAULT 0 NOT NULL,
			UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		//ajout de l'option dans wp_options
		add_option('compteur_version', $compteur_version);
	}
}
register_activation_hook(__FILE__,'compteur_install');

function compteur_uninstall() {
	global $wpdb;
	global $table_compteur;
	
	//si la table existe
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_compteur) {
		$sql = "DROP TABLE " . $table_compteur;
		$wpbd->query($sql);
	}
	
	//suppression de l'option dans wp_options
	delete_option( 'compteur_version' );
}
register_uninstall_hook( __FILE__,'compteur_uninstall');

/* incrémente le compteur quand un article est refusé (donc passé à la corbeille) */
function incremente_compteur($post_id) {
	if( (  $_POST['original_post_status'] != 'publish' ) ) {
		global $wpdb;
		global $table_compteur;
		global $max_refus;
	
		$author = get_post($post_id)->post_author;
		$compteur = get_compteur($author) + 1;
	
		$wpdb->update( $table_compteur, array('compteur'=>($compteur)), array('user_id'=>$author) );
	
		if ($compteur > $max_refus) {
			block_user($author);
		}
	}
}
/*	Choisir l'action adéquate selon la présence ou non de la corbeille */
add_action('pending_to_trash', 'incremente_compteur', 10, 1);
//add_action('draft_to_trash', 'incremente_compteur', 10, 1);
//add_action('trash_post', 'incremente_compteur', 10, 1);
//add_action('delete_post', 'incremente_compteur', 10, 1);


//retourne la valeur du compteur pour l'user_id donné, ou crée le tuple dans la bdd
function get_compteur($author) {
	global $wpdb;
	global $table_compteur;
	$wpdb->show_errors();
	$sql = "SELECT compteur FROM $table_compteur WHERE user_id = $author";
	$compteur = $wpdb->get_row($sql);
	if ($compteur != null) {
		return $compteur->compteur;
	}
	else {
		$wpdb->insert($table_compteur, array('user_id' => $author, 'compteur'=>0));	
	}
	return 0;
}

// passe l'auteur en abonné
function block_user($author) {
	//ne s'applique pas aux auteurs, éditeurs, admins
	if (!user_can($author, 'publish_posts') ){
		$user = new WP_User($author);
		$user->set_role("subscriber");
	}
}

?>
