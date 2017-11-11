<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '7Ni/DmhJp]5tn/b;k[7:lVYZd^E#>D u:vHDsR+*Rd{E+de)563Xud6UDj+w|yhp');
define('SECURE_AUTH_KEY',  'eQH.%HwtG?gX@DF}w70rB&Cc0q$e@LhxMbR=w`@~OR 8v@56=TX7k{Y2~84ZFkj(');
define('LOGGED_IN_KEY',    'C^4jy&,L1WFs1Hv^>>&_0/QFlhv9w:;;wfgvRvc3jo-oMUC2V>jkZl6}u.DP2)7]');
define('NONCE_KEY',        'FO::N?`{J^,Tlg2`vjRl>*oz/1 S:LDv>LDp+OhNy)LM3r=4CPShz`&.}+5NRCR?');
define('AUTH_SALT',        'hph]vRjzDm+>rht#x>yD9WMn);xrOT*+{0n!4OD,e!O%%WHbZY~HBc(Owrf@Nt5W');
define('SECURE_AUTH_SALT', 'Ywav9/j{yg+[X1Vsv~LcZzG{h6yt/<xY,Q!5<s`omb*53M~8@:RMYTrS4pZ2,l(I');
define('LOGGED_IN_SALT',   ' y$v%A%iy12p*$X:WD6Wult5*myE@3JDReu[Z3{sTf=FawG-r[F^k!:.DH]N%B -');
define('NONCE_SALT',       'pq<YX0&,8DoA^;SLAu/!Z}Yi3khd5:B(L.5;l.>d&[-sr]e%}CCanc( }<K!~zx/');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');