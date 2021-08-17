<?php
  /**
 *  @package Mon_jeu_concours
 *  @version 1.0.0
 */
/*
Plugin Name: Mon jeu concours
Description: NE PAS SUPPRIMER !! Un plugin simple et léger qui vous permet de créer un jeux concours avec tirage au sort en 5 mn!
Author: Frédéric Castel
Version: 1.1.0
*/


// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/mjc_functions.php';

// ____________________________________

global $mjc_db_version;
$mjc_db_version = '1.0';

function mjc_install() {
	global $wpdb;
	global $mjc_db_version;

	$mjc_plugin_game_settings = $wpdb->prefix . 'mjc_plugin_game_settings';
  $mjc_plugin_user = $wpdb->prefix . 'mjc_plugin_user';
	
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql_1 = "CREATE TABLE $mjc_plugin_game_settings (
		id int(11) NOT NULL AUTO_INCREMENT,
    start_date date NOT NULL,
    end_date date NOT NULL,
    gifts varchar(255) NOT NULL,
    winners_nbr int(11) NOT NULL DEFAULT '1',
    company_name varchar(255) NOT NULL,
    company_address varchar(255) NOT NULL,
    web_site varchar(255) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

  $sql_2 = "CREATE TABLE $mjc_plugin_user (
    id int(255) NOT NULL AUTO_INCREMENT,
    user_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_gender enum('F','M') DEFAULT NULL,
    user_name varchar(30) DEFAULT NULL,
    user_firstname varchar(30) DEFAULT NULL,
    user_email varchar(50) DEFAULT NULL,
    user_birthdate date DEFAULT NULL,
    user_address text,
    PRIMARY KEY  (id)
  ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql_1 );
  dbDelta( $sql_2 );
	add_option( 'mjc_db_version', $mjc_db_version );
}

register_activation_hook( __FILE__, 'mjc_install' );






      