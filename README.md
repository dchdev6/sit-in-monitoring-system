# Sit-In Monitoring System

## Overview
The Sit-In Monitoring System is a web-based application designed to track and manage student sit-ins in computer laboratories. It ensures accurate logging of student attendance, usage history, and provides notifications for better monitoring.

## Features
- **User Authentication**: Secure login system for students and administrators.
- **Student Profile Management**: Upload and update profile images and personal details.
- **Sit-In Logging**: Tracks student check-ins and check-outs in laboratory rooms.
- **Reservation System**: Allows students to reserve PC stations in advance.
- **Feedback Submission**: Students can submit feedback regarding lab facilities.
- **Announcements**: Admins can post announcements for students.
- **Notifications**: Real-time notifications for reservations and system updates.

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript (Bootstrap, SweetAlert2)
- **Backend**: PHP (Object-Oriented Programming)
- **Database**: MySQL (Database connection via MySQLi)
- **Session Management**: PHP Sessions for user authentication
- **Security**: Input validation and prepared statements to prevent SQL injection

## Installation
1. Clone the repository or download the source code.
2. Import the provided `ccs_system.sql` database into MySQL.
3. Configure database settings in `database_connection.php`.
4. Deploy the project in a local or online PHP server (e.g., XAMPP, LAMP, or WAMP).
5. Access the system via `http://localhost/sit-in-monitoring-system`.

## How It Works
- **Student Registration & Login**: Students register using their ID number and credentials.
- **Sit-In Monitoring**: Students log their sit-in and checkout times in labs.
- **Admin Dashboard**: Administrators manage users, view logs, and post announcements.
- **Profile Management**: Students can update their details and profile image.
- **Reservation System**: Students reserve lab slots, and admins approve or deny requests.
- **Notifications**: Students receive alerts on their reservations and updates.

## Contributing
Contributions are welcome! Please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Commit your changes with descriptive messages.
4. Push to your branch and submit a pull request.

## License
This project is open-source and available under the MIT License.

## Contact
For inquiries or support, please contact the development team.