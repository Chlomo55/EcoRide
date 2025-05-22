<?php
// employees_list.php

require_once '../mongo_connect.php';
require_once '../controllers/EmployeeController.php';

$employeeController = new EmployeeController();
$employees = $employeeController->getAllEmployees();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspend'])) {
    $employeeId = $_POST['employee_id'];
    $employeeController->suspendEmployee($employeeId);
    header("Location: employees_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Employés</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Liste des Employés</h1>
    </header>
    <main>
        <section>
            <h2>Employés Actuels</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Poste</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee->id); ?></td>
                            <td><?php echo htmlspecialchars($employee->name); ?></td>
                            <td><?php echo htmlspecialchars($employee->position); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee->id); ?>">
                                    <button type="submit" name="suspend">Suspendre</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>