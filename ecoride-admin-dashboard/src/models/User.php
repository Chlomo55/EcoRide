<?php

class User {
    private $id;
    private $name;
    private $email;
    private $isSuspended;

    public function __construct($id, $name, $email, $isSuspended = false) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->isSuspended = $isSuspended;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function isSuspended() {
        return $this->isSuspended;
    }

    public function suspend() {
        $this->isSuspended = true;
        // Code to update the user's status in the database
    }

    public function activate() {
        $this->isSuspended = false;
        // Code to update the user's status in the database
    }

    public static function findAll($mongoClient) {
        $db = $mongoClient->selectDatabase('your_database_name');
        $collection = $db->users;
        $users = $collection->find();
        return iterator_to_array($users);
    }

    public static function findById($mongoClient, $id) {
        $db = $mongoClient->selectDatabase('your_database_name');
        $collection = $db->users;
        return $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
}