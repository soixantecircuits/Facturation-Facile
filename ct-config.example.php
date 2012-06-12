<?php
	/** 
	 * The base configurations of the Soixante circuits comptabilité.
	 *
	 * This file has the following configurations: MySQL settings
	 */
	
	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'compta');
	
	/** MySQL database username */
	define('DB_USER', 'root');
	
	/** MySQL database password */
	define('DB_PASSWORD', '');
	
	/** MySQL hostname */
	define('DB_HOST', 'localhost');
	
	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8');
	
	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');
	
	/**#@+
	 * Authentication Unique Keys.
	 *
	 * Change these to different unique phrases!
	 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
	 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
	 *
	 * @since 2.6.0
	 */
	define('AUTH_KEY', 'put your unique phrase here');
	define('SECURE_AUTH_KEY', 'put your unique phrase here');
	define('LOGGED_IN_KEY', 'put your unique phrase here');
	define('NONCE_KEY', 'put your unique phrase here');
	/**#@-*/
	
	/**
	 * Soixante circuits comptabilité Database Table prefix.
	 *
	 * You can have multiple installations in one database if you give each a unique
	 * prefix. Only numbers, letters, and underscores please!
	 */
	$table_prefix  = 'ct_';
	
	/**
	 * WordPress Localized Language, defaults to English.
	 *
	 * Change this to localize WordPress.  A corresponding MO file for the chosen
	 * language must be installed to wp-content/languages. For example, install
	 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
	 * language support.
	 */
	define ('WPLANG', '');
	
	/* That's all, stop editing! Happy blogging. */
	
	/** WordPress absolute path to the Wordpress directory. */
	if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
	define ('TVA', '0.196');
	
	$mois = array('janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');

	date_default_timezone_set('Europe/Paris');
	$today = date("Y-m-d");

	define ('CURR_MONTH', (date("m") - 1));
	define ('CURR_YEAR', date("Y"));
	define ('FIRST_YEAR', 2008);
	
	$categories = array('Matériel projet','Main d\'œuvre projet','-','Matériel R&D' ,'Matériel prospection','Matériel atelier','Fourniture de bureau','Indemnité de gestion','Charges sociales','Note de frais','Assurance','Frais compte pro','Honoraires comptable','Loyer','Electricité','Internet','Entretien et travaux atelier','-','Encaissement projet','Aide à la production','-','TVA','IS','Impôts et taxes (indirects)' );
	$old_categories = array('Achat matière première pour projets','Indémnités de gestion','Charges sociales','Loyer','Achat matériel recherche & production','Fluides','Fourniture de bureau & travaux','Assurance','Honoraires comptable','Frais compte pro','Notes de frais','TVA','Impôts et taxes (indirects)','Frais d\'établissement','Impôt sur les sociétés','Encaissement projets','Aide à la production','Apport en capital','Apport en compte courant');
	$group = array('Charges projets','Charges projets','-','Charges Soixante circuits' ,'Charges Soixante circuits','Charges Soixante circuits','Charges Soixante circuits','Indemnité de gestion','Indemnité de gestion','Frais de fonctionnement','Frais de fonctionnement','Frais de fonctionnement','Frais de fonctionnement','Charges atelier','Charges atelier','Charges atelier','Charges atelier','-','Produit','Produit','-','Impôts et taxes','Impôts et taxes','Impôts et taxes' );

	$array = array( 'Charges projets' => array('Matériel projet','Main d\'œuvre projet'),
                'Charges Soixante circuits' => array('Matériel R&D' ,'Matériel prospection','Matériel atelier','Fourniture de bureau'),
                'Indemnité de gestion' => array('Indemnité de gestion','Charges sociales'),
                'Frais de fonctionnement' => array('Note de frais','Assurance','Frais compte pro','Honoraires comptable'),
                'Charges atelier' => array('Loyer','Electricité','Internet','Entretien et travaux atelier'),
                'Produit' => array('Encaissement projet','Aide à la production'),
                'Impôts et taxes'=> array('TVA','IS','Impôts et taxes (indirects)') );
 	


	$comptes = array('Compte','CB','Caisse');

	
?>
