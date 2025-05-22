<?php

class Employee {
    private $id;
    private $name;
    private $position;
    private $mongoClient;
    private $db;

    public function __construct($mongoClient) {
        $this->mongoClient = $mongoClient;
        $this->db = $this->mongoClient->selectDatabase('your_database_name'); // Remplacez par le nom de votre base de données
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function save() {
        $collection = $this->db->employees;
        $employeeData = [
            'name' => $this->name,
            'position' => $this->position
        ];

        if ($this->id) {
            $collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($this->id)], ['$set' => $employeeData]);
        } else {
            $result = $collection->insertOne($employeeData);
            $this->id = (string)$result->getInsertedId();
        }
    }

    public function suspend() {
        $collection = $this->db->employees;
        $collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($this->id)], ['$set' => ['suspended' => true]]);
    }

    public static function getAll($mongoClient) {
        $db = $mongoClient->selectDatabase('your_database_name'); // Remplacez par le nom de votre base de données
        $collection = $db->employees;
        return $collection->find()->toArray();
    }

    public static function findById($mongoClient, $id) {
        $db = $mongoClient->selectDatabase('your_database_name'); // Remplacez par le nom de votre base de données
        $collection = $db->employees;
        return $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
}