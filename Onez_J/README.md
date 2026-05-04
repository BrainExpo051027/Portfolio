# Registrar Queuing System

A modern, feature-rich queuing system built with PHP, MySQLi, Bootstrap, and Ajax for educational institutions. This system allows students to get queue numbers for various services and administrators to manage queues efficiently.

## 🚀 Features

### Student Features
- **Service Selection**: Choose from available services (TOR, Enrollment, ID Validation, etc.)
- **Queue Number Generation**: Automatic ticket number generation with service prefix
- **Real-time Position Tracking**: Check current position in queue without refreshing
- **Auto-refresh**: Position updates every 30 seconds
- **Responsive Design**: Works on all devices (desktop, tablet, mobile)

### Admin Features
- **Secure Login**: Role-based authentication (Admin/Registrar)
- **Dashboard Overview**: Real-time statistics and queue management
- **Queue Management**: Call next ticket, mark as complete, skip, or cancel
- **Service Management**: Add, edit, and manage available services
- **User Management**: Manage admin and registrar accounts
- **Reports**: View queue statistics and performance metrics
- **Real-time Updates**: No page refresh needed for queue operations

### Technical Features
- **OOP Architecture**: Clean, maintainable code using Object-Oriented Programming
- **AJAX Integration**: Smooth user experience with asynchronous operations
- **Responsive UI**: Bootstrap 5 + AdminLTE for beautiful interfaces
- **Security**: SQL injection prevention, password hashing, session management
- **Performance**: Optimized database queries with proper indexing

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+ with MySQLi
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **CSS Framework**: Bootstrap 5.3.0
- **Admin Template**: AdminLTE 3.2
- **Database**: MySQL 5.7+
- **Icons**: Font Awesome 6.4.0
- **Notifications**: SweetAlert2
- **Data Tables**: DataTables 1.13.6

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser with JavaScript enabled

## 🚀 Installation

### 1. Database Setup

1. Create a new MySQL database
2. Import the database schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```

### 2. File Setup

1. Clone or download the project to your web server directory
2. Configure database connection in `config/Database.php`:
   ```php
   private $host = 'localhost';
   private $username = 'your_username';
   private $password = 'your_password';
   private $database = 'registrar_queue_system';
   ```

### 3. Web Server Configuration

1. Ensure your web server can execute PHP files
2. Set proper file permissions (755 for directories, 644 for files)
3. Make sure the `api/` directory is accessible

### 4. Default Login

- **Username**: `admin`
- **Password**: `admin123`

⚠️ **Important**: Change the default password after first login!

## 📁 Project Structure

```
Onez_J/
├── admin/                 # Admin panel files
│   ├── dashboard.php     # Main admin dashboard
│   ├── login.php         # Admin login page
│   └── logout.php        # Logout functionality
├── api/                  # API endpoints
│   ├── create_ticket.php # Create new queue ticket
│   ├── check_position.php # Check ticket position
│   ├── update_ticket_status.php # Update ticket status
│   └── call_next_ticket.php # Call next ticket
├── classes/              # PHP classes (OOP)
│   ├── Database.php      # Database connection class
│   ├── User.php          # User management class
│   ├── Service.php       # Service management class
│   └── QueueTicket.php   # Queue ticket management class
├── config/               # Configuration files
│   └── Database.php      # Database configuration
├── database/             # Database files
│   └── schema.sql        # Database schema
├── index.php             # Main student interface
└── README.md             # This file
```

## 🎯 Usage Guide

### For Students

1. **Get Queue Number**:
   - Visit the main page (`index.php`)
   - Select a service (TOR, Enrollment, etc.)
   - Enter your details when prompted
   - Receive your ticket number

2. **Check Position**:
   - Your current position is displayed automatically
   - Click "Check Position" to refresh manually
   - Position updates every 30 seconds

### For Administrators

1. **Login**:
   - Navigate to `/admin/login.php`
   - Use your credentials to access the system

2. **Manage Queues**:
   - View all tickets in the dashboard
   - Call next ticket for each service
   - Mark tickets as complete, skip, or cancel
   - Monitor real-time statistics

3. **Service Management**:
   - Add new services
   - Edit existing services
   - Enable/disable services
   - Set estimated processing times

## 🔧 Configuration

### Database Configuration

Edit `config/Database.php` to match your database settings:

```php
private $host = 'localhost';
private $username = 'your_db_username';
private $password = 'your_db_password';
private $database = 'your_database_name';
```

### Service Configuration

Default services are automatically created:
- Transcript of Records (TOR)
- Enrollment (ENR)
- ID Validation (IDV)
- Certificate Request (CER)
- Grade Inquiry (GRI)

You can modify these in the admin panel or directly in the database.

## 🔒 Security Features

- **Password Hashing**: Uses PHP's `password_hash()` function
- **SQL Injection Prevention**: Prepared statements and parameter binding
- **Session Management**: Secure session handling
- **Input Validation**: Server-side validation for all inputs
- **Access Control**: Role-based access control

## 📊 Database Schema

### Tables

1. **users**: Admin and registrar accounts
2. **services**: Available queue services
3. **queue_tickets**: Queue tickets with status tracking

### Key Fields

- **Ticket Numbers**: Format: `SERVICE-DATE-SEQUENCE` (e.g., `TOR-20241201-001`)
- **Status Tracking**: waiting → called → serving → completed/skipped/cancelled
- **Priority System**: normal, priority, emergency
- **Timestamps**: Created, called, and completed times

## 🚀 Customization

### Adding New Services

1. **Via Admin Panel**:
   - Login to admin dashboard
   - Navigate to Services management
   - Add new service with name, code, and description

2. **Via Database**:
   ```sql
   INSERT INTO services (service_name, service_code, description, estimated_duration) 
   VALUES ('New Service', 'NEW', 'Service description', 20);
   ```

### Modifying UI

- **Student Interface**: Edit `index.php` and CSS styles
- **Admin Interface**: Modify admin files and AdminLTE themes
- **Colors**: Update CSS variables in the style sections

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check database credentials in `config/Database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Permission Denied**:
   - Check file permissions (755 for directories, 644 for files)
   - Ensure web server can read/write to the directory

3. **AJAX Not Working**:
   - Check browser console for JavaScript errors
   - Verify API endpoints are accessible
   - Check web server error logs

### Debug Mode

Enable error reporting in PHP for debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📈 Performance Optimization

- **Database Indexing**: Proper indexes on frequently queried columns
- **Query Optimization**: Efficient SQL queries with JOINs
- **Caching**: Consider implementing Redis/Memcached for high-traffic scenarios
- **CDN**: Use CDN for static assets (Bootstrap, Font Awesome)

## 🔄 Updates and Maintenance

### Regular Tasks

- Monitor database performance
- Review and clean old queue data
- Update service estimates based on actual processing times
- Backup database regularly

### Version Updates

- Keep PHP and MySQL versions updated
- Update Bootstrap and AdminLTE to latest versions
- Review security patches and updates

## 📞 Support

For technical support or feature requests:
- Check the troubleshooting section above
- Review PHP and MySQL error logs
- Ensure all requirements are met

## 📄 License

This project is open source and available under the MIT License.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit pull requests or open issues for bugs and feature requests.

---

**Built with ❤️ using modern web technologies**
