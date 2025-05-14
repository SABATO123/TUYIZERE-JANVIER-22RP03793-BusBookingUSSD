# TUYIZERE-JANVIER-22RP03793-BusBookingUSSD

# Bus Booking USSD Application

## Project Overview
A USSD and SMS-based bus booking system for Rwanda, developed using PHP and Africa's Talking API. This application allows users to book bus tickets through USSD without internet access and receive SMS confirmations.

HOW IT WORK BY IMAGE

1.DIAL *384*07090#
<img width="193" alt="image" src="https://github.com/user-attachments/assets/88c7f5c3-b223-41a3-89ef-fd04e5a96e76" />

2.CHOOSE OPTION TOU WONT
<img width="197" alt="image" src="https://github.com/user-attachments/assets/b1452f2f-e629-42a6-b802-1af1b3316b6d" />

3. WHEN TOU CHOOSE OPTION 1 YOU WILL GET THE LIST OF ROUTES
   <img width="230" alt="image" src="https://github.com/user-attachments/assets/7c210f3d-0083-4ec3-9e0f-b5a24383fc99" />

4. WHE YOU FINISH TO BOOK AND CONFIRM YOU WILL GET SMS
   <img width="193" alt="image" src="https://github.com/user-attachments/assets/c50b0fe8-f311-42a6-aff7-f1a93d887549" />
   IN SMS
   <img width="176" alt="image" src="https://github.com/user-attachments/assets/75960be2-0302-455a-9427-e95b58344b89" />






## Developers
- **TUYIZERE JANVIER**
  - Role: Lead Developer
  - Email: sabatoj30@gmail.com
  - Contributions: USSD Flow Development, Database Design

- **MUKARUKUNDO SOPHIE**
  - Role: Developer
  - Email: mukaru2022@gmail.com
  - Contributions: SMS Integration, Testing

## Features
- 📱 USSD Interface for easy access
- 🚌 View available bus routes in Rwanda
- 📅 Select departure date from a list
- 💺 Choose and reserve seats
- ✅ Confirm bookings
- 📨 Receive SMS confirmations
- 📋 View booking history
- 📬 SMS notifications for bookings

## Technical Requirements
- PHP 7.0 or higher
- MySQL 5.6 or higher
- Africa's Talking Account
- Web server (Apache/Nginx)
- Composer (for dependency management)
- Git (for version control)

## Installation Guide

### 1. Clone the Repository
```bash
git clone https://github.com/SABATO123/BusBookingUSSD.git
cd BusBookingUSSD
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE bus_booking;

# Import schema
mysql -u root -p bus_booking < database.sql
```

### 4. Configuration
1. Update database credentials in `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'bus_booking');
```

2. Update Africa's Talking credentials in `send_sms.php`:
```php
$username = "your_username";
$apiKey = "your_api_key";
```

### 5. Africa's Talking Setup
1. Log in to your [Africa's Talking Dashboard](https://account.africastalking.com)
2. Create a new USSD service
3. Set the callback URL to your server's URL
4. Note down the service code

## Project Structure
```
BusBookingUSSD/
├── config.php           # Database configuration
├── ussd.php            # Main USSD handler
├── send_sms.php        # SMS notification handler
├── database.sql        # Database schema
├── composer.json       # Project dependencies
├── composer.lock       # Locked dependencies
├── vendor/            # Composer dependencies
└── README.md          # Project documentation
```

## Available Routes
1. Kigali → Huye (2,500 RWF)
2. Kigali → Musanze (3,000 RWF)
3. Kigali → Rubavu (3,500 RWF)
4. Nyanza → Nyamata (2,000 RWF)
5. Kigali → Rusizi (5,000 RWF)

## Testing Instructions

### Using Africa's Talking Simulator
1. Log in to Africa's Talking Dashboard
2. Go to USSD Simulator
3. Enter your service code
4. Follow the menu prompts:
   ```
   1. View Available Routes
   2. My Bookings
   3. Help
   ```

### Using a Real Phone
1. Dial your USSD service code
2. Follow the menu prompts
3. Receive SMS confirmation

## Security Features
- Input validation for all user inputs
- Prepared statements for database queries
- Secure API key storage
- Rate limiting implementation
- HTTPS enforcement in production

## Development Workflow
1. Create feature branch
2. Make changes
3. Test thoroughly
4. Create pull request
5. Code review
6. Merge to main branch

## Support and Contact
For technical support or questions, please contact:
- TUYIZERE JANVIER
- MUKARUKUNDO SOPHIE

## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments
- Africa's Talking for USSD and SMS APIs
- PHP and MySQL communities
- All contributors and testers 
