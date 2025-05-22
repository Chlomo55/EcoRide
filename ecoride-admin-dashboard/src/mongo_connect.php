<?php
// mongo_connect.php

require 'vendor/autoload.php'; // Assurez-vous d'avoir installé le package MongoDB via Composer

$mongoClient = new MongoDB\Client("mongodb://localhost:27017"); // Remplacez par votre URI MongoDB
?>