<?php
try {
    $db = new PDO('mysql:host=localhost;port=3306;dbname=PHP-Stats-de-pays;charset=utf8', 'root', '');

} catch(PDOException $e) {
    print "Erreur !: " . $e->getMessage();
    die();
}
?>