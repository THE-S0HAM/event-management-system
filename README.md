# Laxmi Trimbak Lawns - Wedding Venue Management System

A PHP-based wedding venue management system for Laxmi Trimbak Lawns, a wedding lawn service provider located in Waladgaon, Shrirampur, Maharashtra 413709.

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
   - Import the SQL file from `EventManagementSystems\sql\laxmi_trimbak_lawns.sql`

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

- Username: `manager`
- Password: `manager123`

## Features

### Branding & Identity
- Customized for Laxmi Trimbak Lawns with appropriate business name, address, and contact information
- Indian-style venue names (Mangal Karyalay, Vivah Hall, Lawn A)

### Online Booking System
- View real-time lawn availability
- Book dates with confirmation messages
- Select from various Indian ceremony types and decoration themes

### Event Scheduling Module
- Add slots for: Ceremony Timing, Decoration Theme, Catering, DJ
- Choose from traditional Indian wedding ceremony types

### Client Management
- Create/edit/delete client profiles
- Include fields for special instructions and full payment history
- Track client events and preferences

### Payment Management
- Record and track payments
- Support for multiple payment methods
- Payment status tracking (Advance, Partial, Completed)

### Feedback & Reports
- Allow clients to rate services and submit feedback
- Generate printable/downloadable reports for internal use

## Venue Information

### Laxmi Trimbak Lawns
- Address: JM5G+VP5, Waladgaon, Shrirampur, Maharashtra 413709
- Email: laxmitribaklawns@gmail.com

### Available Venues
1. **Mangal Karyalay** - Premium venue for large weddings (Capacity: 500)
2. **Vivah Hall** - Perfect for engagement ceremonies and smaller gatherings (Capacity: 300)
3. **Lawn A** - Spacious outdoor venue for grand celebrations (Capacity: 800)

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