# EcoRide Admin Dashboard

## Description
EcoRide Admin Dashboard is a web application designed for administrators to manage employee accounts, visualize ride-sharing statistics, and monitor credits earned. The application utilizes MongoDB for data storage and provides a user-friendly interface for efficient management.

## Features
- **Dashboard**: View key statistics including total credits earned and daily ride-sharing counts.
- **User Management**: List, suspend, and manage user accounts.
- **Employee Management**: List, suspend, and manage employee accounts.
- **Data Visualization**: Generate charts to visualize ride-sharing statistics and credits earned.

## Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd ecoride-admin-dashboard
   ```
3. Install dependencies (if applicable):
   ```
   npm install
   ```
4. Set up your MongoDB database and update the connection settings in `src/mongo_connect.php`.

## Usage
- Start the local server (e.g., using WAMP, XAMPP, or built-in PHP server):
   ```
   php -S localhost:8000 -t public
   ```
- Access the application in your web browser at `http://localhost:8000`.

## File Structure
```
ecoride-admin-dashboard
├── src
│   ├── dashboard.php
│   ├── mongo_connect.php
│   ├── controllers
│   │   ├── UserController.php
│   │   └── EmployeeController.php
│   ├── models
│   │   ├── User.php
│   │   └── Employee.php
│   ├── views
│   │   ├── dashboard_view.php
│   │   ├── users_list.php
│   │   ├── employees_list.php
│   │   └── charts.php
│   └── types
│       └── index.php
├── public
│   ├── index.php
│   └── style.css
├── package.json
├── tsconfig.json
└── README.md
```

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any suggestions or improvements.

## License
This project is licensed under the MIT License. See the LICENSE file for details.