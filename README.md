# Event Management System

A PHP-based event management system for creating, managing, and tracking events and locations.

## Setup Instructions

### Prerequisites
- XAMPP (or similar local server environment with PHP and MySQL)
- Web browser

### Installation Steps

1. **Install XAMPP**
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Start Apache and MySQL services from the XAMPP Control Panel

2. **Set up the project**
   - Clone or download this repository to your XAMPP htdocs folder
   - The path should be: `C:\xampp\htdocs\event-management-system-master\`

3. **Set up the database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `evm_db`
   - Import the SQL file from `EventManagementSystems\sql\maharashtra_data.sql`

4. **Access the application**
   - Open your web browser and navigate to:
   - http://localhost/event-management-system-master/EventManagementSystems/

5. **Alternative: Use the setup script**
   - Run the `launch-app.bat` file included in the project root
   - Follow the instructions displayed

### Login Credentials
- Username: `admin`
- Password: `admin123`

Or

- Username: `suresh`
- Password: `suresh123`

## Troubleshooting

1. **Database Connection Issues**
   - Verify MySQL service is running in XAMPP Control Panel
   - Check database credentials in `EventManagementSystems\classes\Connection.php`
   - Ensure the database name is `evm_db`

2. **Page Not Found Errors**
   - Verify the project is in the correct folder: `C:\xampp\htdocs\event-management-system-master\`
   - Check that Apache service is running in XAMPP Control Panel
   - Make sure your project folder is accessible from the web server

3. **Permission Issues**
   - Ensure XAMPP has proper permissions to access the project folder

## Features
- Event creation and management
- Location management
- User authentication
- Responsive design