================================================================================================
                                    HOSTELHUB
                          Making Hostel Life Actually Manageable
================================================================================================

Hi there! 👋

Welcome to HostelHub - a project born from late-night frustration and too many missed hostel 
booking deadlines. We built this because we've lived it, and we think it can actually make 
a difference.


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
THE PROBLEM WE'RE SOLVING
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Picture this: You're a student looking for a hostel. You call 10 different places, get put 
on hold, visit physical offices during class hours, fill out the same forms repeatedly, and 
still don't know if you got a room until someone calls you back... maybe.

For hostel owners? They're drowning in phone calls, managing bookings on paper or Excel 
sheets, dealing with complaints through WhatsApp groups, and manually sending confirmation 
messages.

It's 2026. There has to be a better way.


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
OUR SOLUTION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

HostelHub is a complete hostel management platform that brings everyone together - students, 
hostel owners, and administrators - in one place. Think of it as the central nervous system 
for hostel operations.

🎓 FOR STUDENTS:
   • Browse hostels like you're shopping online (because you should be able to!)
   • See real amenities, actual prices, and available rooms - no surprises
   • Book rooms with a few clicks, not a dozen phone calls
   • Track your booking status in real-time
   • Raise complaints and actually get responses

🏢 FOR HOSTEL OWNERS:
   • Manage your entire hostel from one dashboard
   • Accept or reject bookings with email notifications sent automatically
   • Update room availability on the fly
   • Handle complaints efficiently
   • No more paper trails or lost records

👨‍💼 FOR ADMINISTRATORS:
   • Bird's eye view of all hostels and students
   • Manage user accounts and hostel listings
   • Monitor complaints and activity
   • Real-time statistics and insights


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
WHAT MAKES IT SPECIAL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✨ It's Actually Secure
   We didn't just slap a login page on this. We implemented proper prepared statements to 
   prevent SQL injection, session management that actually works, and an OTP-based password 
   recovery system with email verification. Your data is safe.

✨ It's Thoughtfully Designed
   Every feature was built with real user scenarios in mind. Students can filter by AC/Non-AC 
   rooms with dynamic pricing. Owners get email notifications when bookings come in. The admin 
   gets a live activity feed of what's happening across the platform.

✨ It's Built to Scale
   Clean database design with proper foreign keys and CASCADE constraints. Multi-role 
   architecture that keeps student, owner, and admin data properly separated. This isn't 
   a prototype - it's production-ready.

✨ It Just Works
   No complicated interfaces. No tech jargon. Just straightforward features that do what 
   they're supposed to do.


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
TECH STACK
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Frontend:  HTML5, CSS3, JavaScript, Bootstrap 5
Backend:   PHP 8 with MySQLi
Database:  MySQL
Email:     PHPMailer for automated notifications
Design:    Glassmorphism with dark theme (because 2 AM coding sessions deserve good UI)


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
GETTING STARTED
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PREREQUISITES:
• PHP 8 or higher
• MySQL 5.7 or higher
• Apache/Nginx web server
• Composer (for PHPMailer dependencies)

INSTALLATION:

1. Clone or extract this project to your web server directory
   
2. Create the database:
   • Open phpMyAdmin or MySQL command line
   • Create a new database called 'hostelhub'
   • Import the database.sql file from the /database folder

3. Configure database connection:
   • Open config/database.php
   • Update these lines with your MySQL credentials:
     
     $host = "localhost";
     $username = "your_mysql_username";
     $password = "your_mysql_password";
     $database = "hostelhub";

4. Set up email for notifications:
   • Open config/email.php
   • Add your SMTP details (Gmail recommended for testing):
     
     $mail->Host = 'smtp.gmail.com';
     $mail->Username = 'your-email@gmail.com';
     $mail->Password = 'your-app-password';

   Note: For Gmail, you'll need to generate an App Password from your Google Account

5. Set proper permissions:
   • Make sure the uploads/ folder is writable:
     chmod 755 uploads/

6. Access the application:
   • Open your browser and go to: http://localhost/hostelhub
   • You're ready to go!

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
KEY FEATURES BREAKDOWN
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

AUTHENTICATION & SECURITY:
✓ Secure registration with email verification
✓ Role-based login (Student/Owner/Admin)
✓ OTP-based password recovery via email
✓ SQL injection prevention using prepared statements
✓ Session management with auto-logout on inactivity

STUDENT PANEL:
✓ Browse hostels with filtering options
✓ Detailed hostel pages with amenities and images
✓ AC/Non-AC room selection with dynamic pricing
✓ One-click booking system
✓ Real-time booking status tracking
✓ Complaint submission and tracking
✓ Profile management

OWNER PANEL:
✓ Complete hostel profile management
✓ Upload hostel images
✓ Manage amenities (WiFi, Parking, AC, Mess, etc.)
✓ Add/edit room inventory with pricing
✓ Approve or reject bookings
✓ Automatic email notifications to students
✓ Complaint management dashboard
✓ View booking history

ADMIN PANEL:
✓ Student account management (block/unblock users)
✓ Hostel listing management
✓ Complaint oversight with quick view modals
✓ Recent activity feed across the platform
✓ Dashboard with real-time statistics
✓ User analytics and insights


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
DATABASE STRUCTURE
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Our database is designed with proper relationships and constraints:

• students - User accounts for students
• owners - User accounts for hostel owners
• hostels - Hostel information and amenities
• rooms - Room inventory with types and pricing
• bookings - Booking records with status tracking
• complaints - Complaint management system
• contact_messages - General inquiries

All tables use foreign keys with CASCADE deletes to maintain data integrity.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
FUTURE ROADMAP
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

We're just getting started. Here's what's next:

📱 Mobile App - Native apps for iOS and Android
💳 Payment Integration - Online payment processing for bookings
⭐ Reviews & Ratings - Let students review hostels
📊 Advanced Analytics - Detailed insights for owners and admins
🔔 Push Notifications - Real-time alerts for bookings and updates
🌐 Multi-language Support - Reach students everywhere
🤖 Chatbot Support - 24/7 automated assistance


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
WHY THIS MATTERS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

This isn't just about winning a hackathon (though that would be nice 😊). It's about solving 
a real problem that affects thousands of students every year.

Hostel hunting is stressful. It shouldn't be.
Communication gaps lead to missed opportunities. They shouldn't.
Managing hostels doesn't have to be chaotic. It really doesn't.

We built HostelHub because we believe technology should make life easier, not more complicated. 
We believe students deserve transparent, accessible information. We believe hostel owners 
deserve tools that respect their time.

Most importantly, we built this because we've been there. We've felt the frustration. And we 
know we can make it better.


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
TROUBLESHOOTING
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Database connection errors?
→ Double-check your credentials in config/database.php
→ Make sure MySQL service is running
→ Verify the database 'hostelhub' exists

Email not sending?
→ Verify SMTP credentials in config/email.php
→ For Gmail, enable "Less secure app access" or use App Passwords
→ Check your spam folder

Images not uploading?
→ Check uploads/ folder permissions (should be 755 or 777)
→ Verify PHP upload_max_filesize in php.ini

Still stuck?
→ Check the error logs in your web server
→ Enable error reporting in PHP for detailed messages


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CREDITS & ACKNOWLEDGMENTS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Built with ❤️ by students who understand the struggle.

Technologies: PHP, MySQL, Bootstrap, PHPMailer
Inspiration: Every student who's ever struggled to find a hostel
Motivation: Making education accessible starts with making accommodation accessible


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
CONTACT & SUPPORT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Questions? Suggestions? Just want to chat about the project?

We'd love to hear from you!

Email: hostellify.support@gmail.com
Demo: Available Soon !

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Thank you for checking out HostelHub!

Whether you're a judge, a fellow developer, or a potential user - we appreciate you taking 
the time to explore what we've built. We hope you see the passion and problem-solving that 
went into every line of code.

Here's to making hostel management actually manageable! 🚀

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Version 1.0 | Built for [Hackathon Name] | 2026
