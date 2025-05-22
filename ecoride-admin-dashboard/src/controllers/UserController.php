<?php

require_once '../mongo_connect.php';
require_once '../models/User.php';

class UserController {
    private $db;

    public function __construct() {
        global $mongoClient;
        $this->db = $mongoClient->selectDatabase('your_database_name'); // Remplacez par le nom de votre base de données
    }

    public function getUsers() {
        $usersCollection = $this->db->users;
        return $usersCollection->find()->toArray();
    }

    public function suspendUser($userId) {
        $usersCollection = $this->db->users;
        $result = $usersCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($userId)],
            ['$set' => ['status' => 'suspended']]
        );
        return $result->getModifiedCount() > 0;
    }

    public function activateUser($userId) {
        $usersCollection = $this->db->users;
        $result = $usersCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($userId)],
            ['$set' => ['status' => 'active']]
        );
        return $result->getModifiedCount() > 0;
    }
}

?>