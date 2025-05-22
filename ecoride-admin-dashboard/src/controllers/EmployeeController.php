<?php
// EmployeeController.php

require_once '../mongo_connect.php';
require_once '../models/Employee.php';

class EmployeeController {
    private $db;

    public function __construct() {
        global $mongoClient;
        $this->db = $mongoClient->selectDatabase('your_database_name'); // Remplacez par le nom de votre base de données
    }

    public function getEmployees() {
        $employeesCollection = $this->db->employees;
        return $employeesCollection->find()->toArray();
    }

    public function suspendEmployee($employeeId) {
        $employeesCollection = $this->db->employees;
        $result = $employeesCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($employeeId)],
            ['$set' => ['status' => 'suspended']]
        );
        return $result->getModifiedCount() > 0;
    }

    public function activateEmployee($employeeId) {
        $employeesCollection = $this->db->employees;
        $result = $employeesCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($employeeId)],
            ['$set' => ['status' => 'active']]
        );
        return $result->getModifiedCount() > 0;
    }
}
?>