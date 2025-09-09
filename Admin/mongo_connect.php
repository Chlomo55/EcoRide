<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: /Admin/login.php');
    exit;
}
require 'vendor/autoload.php'; // Assurez-vous que Composer et mongodb/mongodb sont installés

try {
    // Use Heroku MONGODB_URI if available, otherwise fallback to localhost
    $mongo_url = getenv('MONGODB_URI') ?: "mongodb://localhost:27017";
    $mongoClient = new MongoDB\Client($mongo_url);
    $db = $mongoClient->selectDatabase('admin'); // Remplacez par le nom de votre base
} catch (Exception $e) {
    die('Erreur de connexion à MongoDB : ' . $e->getMessage());
}