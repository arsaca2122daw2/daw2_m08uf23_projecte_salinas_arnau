<?php
require 'vendor/autoload.php';

use Laminas\Ldap\Attribute;
use Laminas\Ldap\Ldap;

session_start();
if (isset($_SESSION['admin'])) {
    ini_set('display_errors', 0);
    ?>
    <!DOCTYPE html>
    <html lang="cat">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar Usuari</title>
        <style>
            .noMostrar{
            	display:none;
            }
            header{
            	display:flex;
            	justify-content:space-around;
            }
        </style>
    </head>
    
    <body>
    <header>
		<a href="http://zend-arsaca.fjeclot.net/projecte/menu.php"> Inici </a><br>
		<h2>Examen</h2>
		<a href="http://zend-arsaca.fjeclot.net/projecte/tancarSessio.php"> Tancar Sessió </a>
    </header>
        <div>
            <?php
            if ($_POST['method'] == "PUT") {
                if ($_POST['uid'] && $_POST['ou'] && $_POST['radioValue'] && $_POST['nouContingut']) {

                    $atribut = $_POST['radioValue'];
                    $nou_contingut = $_POST['nouContingut'];

                    $uid = $_POST['uid'];
                    $ou = $_POST['ou'];
                    $dn = 'uid=' . $uid . ',ou=' . $ou . ',dc=fjeclot,dc=net';

                    $opcions = [
                        'host' => 'zend-arsaca.fjeclot.net',
                        'username' => 'cn=admin,dc=fjeclot,dc=net',
                        'password' => 'fjeclot',
                        'bindRequiresDn' => true,
                        'accountDomainName' => 'fjeclot.net',
                        'baseDn' => 'dc=fjeclot,dc=net',
                    ];

                    $ldap = new Ldap($opcions);
                    $ldap->bind();
                    $entrada = $ldap->getEntry($dn);
                    if ($entrada) {
                        Attribute::setAttribute($entrada, $atribut, $nou_contingut);
                        $isModificat = true;
                        $ldap->update($dn, $entrada);
                        echo "Modificat correctament<br />";
                    } else {
                        echo "<b>No existeix</b><br />";
                    }
                }
            } else {
            ?>
                <div>
                    <div>
                        <form action="http://zend-arsaca.fjeclot.net/projecte/examen.php" method="POST" autocomplete="off">
                            <br><input type="text" name="method" value="PUT" class="noMostrar"><br>
                            <input type="text" name="ou" placeholder="Unitat Organitzativa" required /><br><br>
                            <input type="text" name="uid" placeholder="Usuari" required /> <br><br>
                            <input type="radio" name="radioValue" value="uidNumber" checked="checked"/><span class="formLabel">UID Number</span><br><br>
                            <input type="text" name="nouContingut" placeholder="Contigut a modificar" required /><br><br>
                            <input type="submit" class="button" value="Modificar"/><br>
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </body>

    </html>
<?php
} else {
    header("Location: http://zend-arsaca.fjeclot.net/projecte/");
}
?>