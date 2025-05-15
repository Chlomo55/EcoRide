<?php
require 'vendor/autoload.php'; // Composer avec mongodb/mongodb

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$mongoDb = $mongoClient->ecoride;
$historiqueCollection = $mongoDb->historique;
?>
