<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'webpatmaster');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'webpatmaster');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'Qm9zthbn');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'R$Z (Iozeqk{F^1wJn&}TP@y`EtZ[1>a/`fK2Gx^=qE&)y+Gc~GJ7[8].PD^K&dN');
define('SECURE_AUTH_KEY',  '*gKLLeurk(@mYWubmjs;B`N!3VUD3@QTFt/8wj*{Vb`qr9Rj13&)`LEK?#$6V,mG');
define('LOGGED_IN_KEY',    'Z/ZAS~b!EfZjI*k5/PGFFu[]wb{w}{XD4N?Rb7Vwvn(3###Y;Cu~8yk3:|E/QCtK');
define('NONCE_KEY',        '&@=|y^Zv^;3,gVe,x%gLl`bG)1FGE]vD826>K$I_k%I,(:un_6kpvWBZ`Vvz=I#v');
define('AUTH_SALT',        '.$#CALC}>9H+<U!AWtO5KpOW:U8gTFxz=+Bs&qH.TP~FDo5*@<oI,4*THm>D18V<');
define('SECURE_AUTH_SALT', ']/#pS[c{^UigXgi3m&Ij$1OGEw/L K<xQrJO}*d+Lq+~u/Sr)hhb;u_Ve:VY[xeV');
define('LOGGED_IN_SALT',   '3x=l?[,/D$bsvbCq@KK2^ 8jZiH](jeqZ5Dy3(PtmJK.pAW)<CY({# XN*uCrP_P');
define('NONCE_SALT',       '_A)EgGGDe qTz|4ts :/QLXji/(#rq1~FJ42(-rDvtQiF @gIYN1Xj-sV.siN6I)');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp19693969_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d'information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 * 
 * @link https://codex.wordpress.org/Debugging_in_WordPress 
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');