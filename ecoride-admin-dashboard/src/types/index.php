<?php
// src/types/index.php

// Définition des types et interfaces utilisés dans l'application

interface UserType {
    public function getId(): string;
    public function getName(): string;
    public function getEmail(): string;
}

interface EmployeeType {
    public function getId(): string;
    public function getName(): string;
    public function getPosition(): string;
}
?>