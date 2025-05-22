<?php
// index.php

require_once '../src/mongo_connect.php';
require_once '../src/controllers/UserController.php';
require_once '../src/controllers/EmployeeController.php';
require_once '../src/views/dashboard_view.php';

// Initialisation des contrôleurs
$userController = new UserController();
$employeeController = new EmployeeController();

// Gestion des requêtes
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'users':
        $users = $userController->getUsers();
        include '../src/views/users_list.php';
        break;

    case 'employees':
        $employees = $employeeController->getEmployees();
        include '../src/views/employees_list.php';
        break;

    case 'charts':
        include '../src/views/charts.php';
        break;

    case 'dashboard':
    default:
        $statistics = getStatistics();
        include '../src/views/dashboard_view.php';
        break;
}
?>