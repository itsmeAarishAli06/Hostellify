<?php

    require '../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    session_start();    
    require_once "../config.php";
    

// Adding Accounts in the database.

if (isset($_POST['reg_btn'])) {
    
    $_SESSION['islogin'] = false ;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $user_type = $_POST['user_type'];
    $otp = rand(10000000, 99999999);

    // Single optimized query — checks all 3 tables at once
    $stmt = $conn->prepare("
        SELECT email FROM student WHERE email = ?
        UNION
        SELECT email FROM admin   WHERE email = ?
        UNION
        SELECT email FROM owner   WHERE email = ?
        LIMIT 1
    ");
    $stmt->bind_param("sss", $email, $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['email_taken'] = true; // Block form — show error below
        header("location:../User Panel/register.php");
        exit();
    }else{
        $_SESSION['email_taken'] = false;
    }
    $stmt->close();

// Now the whole otp setup

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 0 ;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                 //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $_ENV['MAIL_USER'];                     //SMTP username
    $mail->Password   = $_ENV['MAIL_PASS'];                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($_ENV['MAIL_USER'], 'Hostel Account');
    $mail->addAddress($email, 'Hostel Web Application');     //Add a recipient
    
    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Your HostelHub Verification Code';
    $mail->Body = ($user_type == 'student') ? 'Dear User,<br><br>

    Your password for verification is: <b>'.$otp.'</b><br><br>
    Please enter this OTP into your password field to complete your verification.<br>
    You can change this OTP / Password from your profile Dashboard<br>
    Do not share this OTP with anyone.<br><br>
    Best Regards,<br>
    Hostel Management System
    '
    :
    'Dear Hostel Owner,<br><br>

    Your OTP for verification is: <b>'.$otp.'</b><br><br>

    Please enter this code to complete your verification.<br>
    Do not share this OTP with anyone.<br><br>

    Best Regards,<br>
    Hostel Management System';
    $mail->send();
}

catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
    // Insert the record in the DataBase.
    if ($user_type == 'student') {

        $query = "INSERT INTO `student`(`name`,`email`,`contact`,`address`,`password`) 
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $name, $email, $contact, $address, $otp);
        $stmt->execute();
        header("location:../User Panel/register.php?otp=sent");// Go to the login page and then it will go 
        exit();
    }
    if ($user_type == 'owner') {

        $query = "INSERT INTO `owner`(`name`,`email`,`contact`,`address`,`password`) VALUES 
        (?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss",$name,$email,$contact,$address,$otp);
        $stmt->execute();
        header("location:../User Panel/register.php?otp=sent");// Same as above if.
        exit();
    }
}

// For logining in.
if (isset($_POST['login_btn'])){
        
        $email     = $_POST['email'];
        $password  = $_POST['password'];
        $user_type = $_POST['user_role'];

        if ($user_type=="admin") {

            $query = "SELECT * FROM admin WHERE `email` = ? AND `password` = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss",$email,$password);
            $stmt->execute();
            $res = $stmt->get_result();

            if (mysqli_num_rows($res) > 0) {  
                $user = mysqli_fetch_assoc($res);
                
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_password'] = $user['password'];
                $_SESSION['admin_address'] = $user['address'];
                $_SESSION['admin_contact'] = $user['contact'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['user_type'] = "Admin";
                $_SESSION['islogin'] = true;

                header("location:../Admin Pannel Dashboard/admin/index.php");
                exit();
            } else{
                header("location:../User Panel/login.php");
                exit();
            }
        }
        else if($user_type=="student"){

            $status = "active";
            $query = "SELECT * FROM student WHERE `email` = ? AND `password` = ? AND `status` = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss",$email,$password,$status);
            $stmt->execute();
            $res = $stmt->get_result();

            if (mysqli_num_rows($res) > 0) {  
                $user = mysqli_fetch_assoc($res);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_password'] = $user['password'];
                $_SESSION['user_address'] = $user['address'];
                $_SESSION['user_contact'] = $user['contact'];
                $_SESSION['user_type'] = "Student";
                $_SESSION['islogin'] = true;

                header("location:../User Panel/index.php");
                exit();
            } else{
                header("location:../User Panel/login.php?status=blocked");
                exit();
            }
        }  
        else if ($user_type=="hostel_owner") {

            $query = "SELECT * FROM owner WHERE `email` = ? AND `password` = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss",$email,$password);
            $stmt->execute();
            $res = $stmt->get_result();

            if (mysqli_num_rows($res) > 0) {  
                $user = mysqli_fetch_assoc($res);

                $_SESSION['owner_id'] = $user['id'];
                $_SESSION['owner_name'] = $user['name'];
                $_SESSION['owner_email'] = $user['email'];
                $_SESSION['owner_password'] = $user['password'];
                $_SESSION['owner_address'] = $user['address'];
                $_SESSION['owner_contact'] = $user['contact'];
                $_SESSION['user_type'] = "Hostel Owner";
                $_SESSION['islogin'] = true;

                $query = "SELECT * FROM hostel WHERE  `owner_id` = ?";
                $stm = $conn->prepare($query);
                $stm->bind_param("i",$_SESSION['owner_id']);
                $stm->execute();
                $res = $stm->get_result();

            if (mysqli_num_rows($res) > 0) {  
            
                $user = mysqli_fetch_assoc($res);
                
                $_SESSION['hostel_det_complete'] = true;
                $_SESSION['hostel_id'] = $user['id'];
                $_SESSION['hostel_name'] = $user['hostel_name'];
                $_SESSION['hostel_city'] = $user['city'];
                $_SESSION['hostel_address'] = $user['address'];
                $_SESSION['hostel_contact'] = $user['contact'];
                $_SESSION['hostel_email'] = $user['email'];
                $_SESSION['hostel_type'] = $user['hostel_type'];
                $_SESSION['hostel_capacity'] = $user['capacity'];
                $_SESSION['hostel_description'] = $user['description'];
            }else{
                $_SESSION['hostel_det_complete'] = false;
            }
        header("location:../Hostel Owner Dashboard/admin/index.php");   
        exit();
        }
        else{
            header("location:../User Panel/login.php");
            exit();
        }
      }
    }
    
// To update the user profile
if (isset($_POST['update_profile'])) {

    $id       = $_SESSION['user_id'];
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $contact  = $_POST['contact'];
    $address  = $_POST['address'];

    $changed = (
        $name     != $_SESSION['user_name']     ||
        $email    != $_SESSION['user_email']    ||
        $password != $_SESSION['user_password'] ||
        $contact  != $_SESSION['user_contact']  ||
        $address  != $_SESSION['user_address']
    );

    if ($changed) {

        $query = "UPDATE `student` SET `name`=?, `email`=?, `password`=?, `contact`=?, `address`=? WHERE `id`=?";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("sssssi", $name, $email, $password, $contact, $address, $id);
        $stmt->execute();

        if ($email != $_SESSION['user_email'] || $password != $_SESSION['user_password']) {
            header("location:../User Panel/logout.php");
        } else {
            header("location:../User Panel/dashboard.php");
        }

        // Update sessions BEFORE redirecting
        $_SESSION['user_name']     = $name;
        $_SESSION['user_email']    = $email;
        $_SESSION['user_password'] = $password;
        $_SESSION['user_contact']  = $contact;
        $_SESSION['user_address']  = $address;

        // If sensitive credentials changed, force re-login
        
        exit();
    }
    // Nothing changed — just go back
    header("location:../User Panel/dashboard.php");
    exit();
}

// Owner Profile Update
if (isset($_POST['update_owner_personal'])) {
    $owner_id = $_SESSION['owner_id'];
    $name     = $_POST['owner_name'];
    $phone    = $_POST['owner_contact'];
    $email    = $_POST['owner_email'];
    $password = $_POST['owner_password'];

    $changed = (
        $name != $_SESSION['owner_name'] ||
        $email != $_SESSION['owner_email'] ||
        $password != $_SESSION['owner_password'] || 
        $phone != $_SESSION['owner_contact'] 
    );
    if ($changed) {
        $stmt = $conn->prepare("UPDATE owner SET name=?, contact=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $phone, $email, $password, $owner_id);
        $stmt->execute();

        if ($password != $_SESSION['owner_password'] || $email != $_SESSION['owner_email']) {
            header("location:../Hostel Owner Dashboard/admin/logout.php");
        }else{
            header("location:../Hostel Owner Dashboard/admin/index.php");
        }
        $_SESSION['owner_name']  = $name;
        $_SESSION['owner_contact'] = $phone;
        $_SESSION['owner_email'] = $email;
        $_SESSION['owner_password'] = $password;
        
        exit();
    }
    header("location:../Hostel Owner Dashboard/admin/index.php");
    exit();
}

// MINE CODE
// To Update / Insert the Hostel Data 

  if (isset($_POST['update_owner_profile'])) {

        $owner_id = $_SESSION['owner_id'];
        $hostel_name = $_POST['hostel_name'];
        $city = $_POST['city'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $hostel_type = $_POST['hostel_type'];
        $capacity = $_POST['capacity'];
        $amenities = isset($_POST['amenities']) ? implode(',', $_POST['amenities']) : '';;
        $description = $_POST['description'];

        // Session Variables.

        $_SESSION['hostel_name']= $hostel_name;
        $_SESSION['hostel_city']= $city;
        $_SESSION['hostel_address']= $address;
        $_SESSION['hostel_contact']= $contact;
        $_SESSION['hostel_email'] = $email;
        $_SESSION['hostel_type']= $hostel_type;
        $_SESSION['hostel_capacity']= $capacity;
        $_SESSION['hostel_amenities']= $amenities;
        $_SESSION['hostel_description']= $description;
        

    // If the details are not complete INSERT ! => This $_SESSION['hostel_det_complete'] Var is created in the login part of the owner profile ! 

    // Handle front image upload

    $img_path = $_SESSION['hostel_img'] ?? ''; // keep old if no new upload
    if (isset($_FILES['hostel_img']) && $_FILES['hostel_img']['error'] === 0) {
        $upload_dir = '../Hostel Images/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $ext = pathinfo($_FILES['hostel_img']['name'], PATHINFO_EXTENSION);
        $filename = 'hostel_' . $owner_id . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['hostel_img']['tmp_name'], $upload_dir . $filename);
        $img_path =  $filename;
        $_SESSION['hostel_img'] = $img_path;
    }

    if (!$_SESSION['hostel_det_complete']) {
    
        // Amenities Are Reamining.
        $query = "INSERT INTO `hostel` (`owner_id`,`hostel_name`,`city`,`address`,`contact`,`email`,`hostel_type`,`capacity`,`description`,`amenities`,`image_path`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssssisss", $owner_id,$hostel_name,$city,$address,$contact,$email,$hostel_type,$capacity,$description,$amenities,$img_path);
        $stmt->execute();
        $_SESSION['hostel_det_complete'] = true;

        header("location:../Hostel Owner Dashboard/admin/hostel_profile.php");
    }  
    else{
        if (!($hostel_name == $_SESSION['hostel_name']) || !($city == $_SESSION['hostel_city'])  || !($address==$_SESSION['hostel_address'])|| !($contact == $_SESSION['hostel_contact'])|| !($email == $_SESSION['hostel_email']) || ($hostel_type == $_SESSION['hostel_type']) || !($capacity == $_SESSION['hostel_capacity']) || !($description == $_SESSION['hostel_description']) ) {

        // To get the owner id of this particular hostel
        $query = "SELECT * FROM hostel WHERE `owner_id` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $owner_id);  
        $stmt->execute();
        $res = $stmt->get_result();

        while ($user = $res->fetch_assoc()) {
            $_SESSION['hostel_id'] = $user['id'];
        }
        $query1 = "UPDATE `hostel` SET `hostel_name`=?,`city`=?,`address`=?,`contact`=?,`email`=?,`hostel_type`=?,`capacity`=?,`description`=?,`amenities`=?,`image_path`=? WHERE `owner_id`=?";
        $stmt = $conn->prepare($query1);
        $stmt->bind_param("ssssssisssi", $hostel_name,$city,$address,$contact,$email,$hostel_type,$capacity,$description,$amenities,$img_path,$_SESSION['owner_id']);
        $stmt->execute();        
         
        header("location:../Hostel Owner Dashboard/admin/hostel_profile.php");
        }
    }
    
    
}

// Now we have to save the room of the particular hostel in the data base !
if (isset($_POST['save_room'])) {

    $stmt = $conn->prepare("SELECT id FROM hostel WHERE owner_id = ?"); // Removed extra )
    $stmt->bind_param('i', $_SESSION['owner_id']);
    $stmt->execute();

    $result = $stmt->get_result();
    $hostel = $result->fetch_assoc();

    $hostel_id = $hostel['id'] ?? null; 

    $room_no = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $capacity = $_POST['capacity'];
    $price = $_POST['price_monthly'];
    $status = $_POST['status'];

    $query1 = "INSERT INTO `room`(`hostel_id`,`room_number`,`room_type`,`capacity`,`price_monthly`,`status`) VALUES (?,?,?,?,?,?)";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param('iisiis',$hostel_id,$room_no,$room_type,$capacity,$price,$status);
    $stmt1->execute();
    header("location:../Hostel Owner Dashboard/admin/rooms.php");
    exit();
}
// Update the room data !

    if (isset($_POST['room_data_update'])) {
        
        $room_id  = $_POST['room_id'];
        $room_type    = $_POST['room_type'];
        $capacity = $_POST['capacity'];
        $price_monthly  = $_POST['price_monthly'];
        $status  = $_POST['status'];

        $stmt0 = $conn->prepare("SELECT capacity , occupied from room WHERE `id` = ? ");
        $stmt0->bind_param('i',$room_id);
        $stmt0->execute();
        $res = $stmt0->get_result();
        if ($row = $res->fetch_assoc() ) {
            $cap = $row['capacity'];
            $occ = $row['occupied'];
        }

        if ($occ > $capacity) {
            header("location:../Hostel Owner Dashboard/admin/rooms.php?cap=wrong_cap");
            exit();
        }

        $stmt0->close();

        $stmt = $conn->prepare("UPDATE `room` SET `room_type` = ? , `capacity` = ? ,`price_monthly` = ? , `status` = ? WHERE `id` = ? ");
        $stmt->bind_param('siisi',$room_type,$capacity,$price_monthly,$status,$room_id);
        $stmt->execute();
        $stmt->close();

        $stmt1 = $conn->prepare("
            UPDATE room 
            SET status = CASE 
                WHEN occupied >= capacity THEN 'full'
                ELSE 'available'
            END
            WHERE id = ?
        ");
        $stmt1->bind_param('i', $room_id);
        $stmt1->execute();
        $stmt1->close();


        header("location: ../Hostel Owner Dashboard/admin/rooms.php");
        exit();
    }
    // Update the room data !

    if (isset($_POST['room_data_update'])) {
        
        $room_id  = $_POST['room_id'];
        $room_type    = $_POST['room_type'];
        $capacity = $_POST['capacity'];
        $price_monthly  = $_POST['price_monthly'];
        $status  = $_POST['status'];

        $stmt = $conn->prepare("UPDATE `room` SET `room_type` = ? , `capacity` = ? ,`price_monthly` = ? , `status` = ? WHERE `id` = ? ");
        $stmt->bind_param('siisi',$room_type,$capacity,$price_monthly,$status,$room_id);
        $stmt->execute();
        $stmt->close();

        header("location: ../Hostel Owner Dashboard/admin/rooms.php");
        exit();
    }
// Update the room data !

    if (isset($_POST['room_data_update'])) {
        
        $room_id  = $_POST['room_id'];
        $room_type    = $_POST['room_type'];
        $capacity = $_POST['capacity'];
        $price_monthly  = $_POST['price_monthly'];
        $status  = $_POST['status'];

        $stmt = $conn->prepare("UPDATE `room` SET `room_type` = ? , `capacity` = ? ,`price_monthly` = ? , `status` = ? WHERE `id` = ? ");
        $stmt->bind_param('siisi',$room_type,$capacity,$price_monthly,$status,$room_id);
        $stmt->execute();
        $stmt->close();

        header("location: ../Hostel Owner Dashboard/admin/rooms.php");
        exit();
    }

    // Now we have to del the room !
    if (isset($_GET['del_room'])) {
    

    $room_id = (int)$_GET['del_room'];

    $stmt = $conn->prepare("DELETE FROM room WHERE id = ?");
    $stmt->bind_param("i", $room_id);

    if ($stmt->execute()) {
        header("Location: ../Hostel Owner Dashboard/admin/rooms.php?msg=Room Deleted Successfully");
    } else {
        echo "Error deleting room: " . $conn->error;
    }

    $stmt->close();
    exit();
}



// Contact form for the users !
if(isset($_POST['contact_sub_btn'])){

    $name    = trim($_POST['c_name']    ?? '');
    $email   = trim($_POST['c_email']   ?? '');
    $phone   = trim($_POST['c_phone']   ?? '');
    $subject = trim($_POST['c_subject'] ?? '');
    $message = trim($_POST['c_message'] ?? '');

    $stmt = $conn->prepare(
        "INSERT INTO `contact_messages` (`full_name`, `email`, `phone`, `subject`, `message`) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
    $stmt->execute();
    $stmt->close();

    header("Location: ../User Panel/contact.php?success=1");
    exit;
}

// Booking of the hostel
if(isset($_POST['booking_btn'])){
    
        $conn = new mysqli("localhost","root","","hostel");
        if (!(isset($_SESSION['islogin']) && $_SESSION['islogin'])) {
            header("location:../User Panel/login.php");
        } 
        else {
        $hostel_id = $_POST['hostel_id'];
        $room_type = $_POST['room_type_selected'];
        $today_readable = date("d-m-Y"); 
        // $price = $_POST['price'];

        $stmt1 = $conn->prepare("SELECT * from `booking` where `student_id` = ? ");
        $stmt1->bind_param('i',$_SESSION['user_id']);
        $stmt1->execute();
        $res = $stmt1->get_result();
        
        // if the user has not booked an hostel ! 
        if (mysqli_num_rows($res) == 0) {
            $stmt2 = $conn->prepare(
                "INSERT INTO `booking`(`student_id`,`hostel_id`,`room_type`,`start_date`) VALUES (?,?,?,?)"
            );
            $stmt2->bind_param('iiss',$_SESSION['user_id'],$hostel_id,$room_type,$today_readable);
            $stmt2->execute();
            $stmt2->close(); 
            $_SESSION['hasBooked'] = true;

            header("location:../User Panel/hostels.php?success=1");        
            exit();
        }    
        header("location:../User Panel/hostels.php");
        exit();
        }
    }

// 'Approved' or 'Rejected' the student by the owner !

if (isset($_POST['booking_id'])) {
 
    $booking_id = (int) $_POST['booking_id'];   
    $status     = $_POST['status'];             // 'Approved' or 'Rejected'
    $room_type  = $_POST['bookingRoom'];        
    $room_type = str_replace('Non-AC', 'Non AC', $room_type);

    $stmt = $conn->prepare("SELECT id FROM hostel WHERE owner_id = ?"); // Removed extra )
    $stmt->bind_param('i', $_SESSION['owner_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $hostel_data = $result->fetch_assoc();
    $hostel_id = $hostel_data['id'] ?? 0; // Extract the actual number
    $stmt->close();
    
 
    if ($status === 'Approved' || $status === 'Rejected') {
        $stmt = $conn->prepare("UPDATE booking SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $booking_id);
        $stmt->execute();
        $stmt->close();
    }

    //   BRANCH A => Owner clicked "Approved"
    if ($status === 'Approved') {
        // ── A1. Find an available room of the requested type ──
        //    "occupied < capacity" = at least one free bed
        $stmt1 = $conn->prepare("
            SELECT id, price_monthly
            FROM   room
            WHERE  hostel_id = ?
              AND  room_type = ?
              AND  occupied  < capacity
            LIMIT  1
        ");
        $stmt1->bind_param('is', $hostel_id, $room_type);
        $stmt1->execute();
        $room = $stmt1->get_result()->fetch_assoc();
        $stmt1->close();
 
        // If no room is free, stop and show an error
        if (!$room) {
            echo "sorry no room is available";
            header("Location:../Hostel Owner Dashboard/admin/application.php");
            exit();
        }
 
        // 2. Link that room to this booking + save the monthly price ──
        $stmt2 = $conn->prepare("
            UPDATE booking
            SET    room_id       = ?,
                   monthly_price = ?
            WHERE  id            = ?
        ");
        $stmt2->bind_param('idi', $room['id'], $room['price_monthly'], $booking_id);
        $stmt2->execute();
        $stmt2->close();
 
        // 3. Increment the occupied count for that room ──
        $stmt3 = $conn->prepare("
            UPDATE room
            SET    occupied = occupied + 1
            WHERE  id = ?
        ");
        $stmt3->bind_param('i', $room['id']);
        $stmt3->execute();
        $stmt3->close();
 
        // ── A4. Fetch student name, student email, hostel name ──
        //    * was TWO separate queries (stmt3 + stmt4); one JOIN is enough
        //    * student email comes from DB, not $_SESSION['user_email']
        //         ($_SESSION holds the hostel OWNER's email, not the student's)
        $stmt4 = $conn->prepare("
            SELECT  b.room_type,
                    s.name        AS student_name,
                    s.email       AS student_email,
                    h.hostel_name ,
                    h.address  AS hostel_address 
            FROM    booking b
            JOIN    student s ON s.id = b.student_id
            JOIN    hostel  h ON h.id = b.hostel_id
            WHERE   b.id = ?
        ");
        $stmt4->bind_param('i', $booking_id);
        $stmt4->execute();
        $details = $stmt4->get_result()->fetch_assoc();
        $stmt4->close();
 
        // ── A5. Send confirmation email to the student ──
        require 'PHPMailer/Exception.php';
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
 
        $mail = new PHPMailer(true);
 
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USER'];
            $mail->Password   = $_ENV['MAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->SMTPDebug  = 0;
 
            // From & To
            $mail->setFrom($_ENV['MAIL_USER'], 'Hostel Management');
            $mail->addAddress($details['student_email'], $details['student_name']);
 
            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Hostel Booking Confirmed ✅';
            $mail->Body    = '
                Dear ' . htmlspecialchars($details['student_name']) . ',<br><br>
 
                Your hostel booking has been <b>successfully confirmed</b> 🎉<br><br>
 
                <b>Booking Details:</b><br>
                Hostel Name : ' . htmlspecialchars($details['hostel_name']) . '<br>
                Room Type   : ' . htmlspecialchars($details['room_type'])   . '<br>
                Hostel Address : ' . htmlspecialchars($details['hostel_address']) . '<br><br>
 
                Please arrive on time and carry a valid ID.<br>
                For any questions, feel free to contact us.<br><br>
 
                Best Regards,<br>
                Hostel Management System
            ';
            $mail->send();

        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }

    }
 
    // Rejection email.
    elseif ($status === 'Rejected') {

    // B1. Check if a room was already assigned and decrement it
    $stmt5 = $conn->prepare("SELECT room_id FROM booking WHERE id = ?");
    $stmt5->bind_param('i', $booking_id);
    $stmt5->execute();
    $existing = $stmt5->get_result()->fetch_assoc();
    $stmt5->close();

    if (!empty($existing['room_id'])) {
        $stmt6 = $conn->prepare("UPDATE room SET occupied = occupied - 1 WHERE id = ?");
        $stmt6->bind_param('i', $existing['room_id']);
        $stmt6->execute();
        $stmt6->close();
    }

    // B2. Fetch student details before deleting the booking
    $stmt7 = $conn->prepare("
        SELECT s.name     AS student_name,
               s.email    AS student_email,
               h.hostel_name,
               b.room_type
        FROM   booking b
        JOIN   student s ON s.id = b.student_id
        JOIN   hostel  h ON h.id = b.hostel_id
        WHERE  b.id = ?
    ");
    $stmt7->bind_param('i', $booking_id);
    $stmt7->execute();
    $rejected = $stmt7->get_result()->fetch_assoc();
    $stmt7->close();

    // B3. Send rejection email
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER'];
        $mail->Password   = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->SMTPDebug  = 0;

        $mail->setFrom($_ENV['MAIL_USER'], 'Hostel Management');
        $mail->addAddress($rejected['student_email'], $rejected['student_name']);

        $mail->isHTML(true);
        $mail->Subject = 'Hostel Booking Request Rejected ❌';
        $mail->Body    = '
            Dear ' . htmlspecialchars($rejected['student_name']) . ',<br><br>

            We regret to inform you that your booking request for
            <b>' . htmlspecialchars($rejected['hostel_name']) . '</b>
            has been <b>rejected</b>.<br><br>

            <b>Booking Details:</b><br>
            Hostel Name : ' . htmlspecialchars($rejected['hostel_name']) . '<br>
            Room Type   : ' . htmlspecialchars($rejected['room_type'])   . '<br><br>

            You may apply to other available hostels.<br>
            We apologize for the inconvenience.<br><br>

            Best Regards,<br>
            Hostel Management System
        ';
        $mail->send();

    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }

    // B4. Delete the booking record from the table
    $stmt8 = $conn->prepare("DELETE FROM booking WHERE id = ?");
    $stmt8->bind_param('i', $booking_id);
    $stmt8->execute();
    $stmt8->close();
}
    header("Location:../Hostel Owner Dashboard/admin/application.php");
    exit();
}
// Del hostel form the admin 
if (isset($_POST['del_hostel'])) {

    $hostel_id = $_POST['hostel_id'];
    $stmt = $conn->prepare("DELETE FROM hostel WHERE id = ?");
    $stmt->bind_param("i", $hostel_id);

    if ($stmt->execute()) {
        header("Location: ../Admin Pannel Dashboard/admin/hostels.php?msg=Hostel Deleted Successfully");  
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
    exit();
}
// Complaint Submit Button

if (isset($_POST['conplaint_sub_btn'])) {

    // Get the User Id !
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['title'];
    $message = $_POST['message'];
    $level = $_POST['priority'];
    
    // Get  the hostel Id !
    $stmt0 = $conn->prepare("SELECT hostel_id from booking where student_id = ?");
    $stmt0->bind_param("i", $user_id);
    $stmt0->execute();
    $row = $stmt0->get_result();

    // echo "Not Sent !";    
    if ($row->num_rows == 0) {

        $_SESSION['success'] = false;
        header("Location: ../User Panel /complaints.php?msg=Complaint Subbmited Successfully");
        exit();    
        echo "not sent";
    } 
    // echo "Sent!";
    else{
        $data = $row->fetch_assoc(); 
        $hostel_id = $data['hostel_id'] ?? null;
        $status = "pending";

        $stmt1 = $conn->prepare("INSERT INTO `complaints`(`student_id`,`hostel_id`,`subject`,`message`,`status`,`level`) VALUES (?,?,?,?,?,?)");
        $stmt1->bind_param("iissss",$user_id,$hostel_id,$subject,$message,$status,$level);
        $stmt1->execute();
        $_SESSION['success'] = true;
        header("Location: ../User Panel /complaints.php?msg=Complaint Subbmited Successfully");
        exit();
    }
}
// Hostel Owner Blocks the Studnet 
if (isset($_POST['block'])) {

    $h_id = $_POST['hostel_id'];
    $s_id = $_POST['student_id'];
    
    // 1. Pehle Room ID hasil karein (Delete karne se pehle)
    $stmt0 = $conn->prepare('SELECT room_id FROM booking WHERE student_id = ? AND hostel_id = ?');
    $stmt0->bind_param('ii', $s_id, $h_id);
    $stmt0->execute();
    $result = $stmt0->get_result();
    $row = $result->fetch_assoc();
    $room_id = $row['room_id']; // Ab aapke paas room_id agaya
    $stmt0->close();

if ($room_id) {
    // 2. Booking delete karein
    $stmt1 = $conn->prepare('DELETE FROM booking WHERE student_id = ? AND hostel_id = ?');
    $stmt1->bind_param('ii', $s_id, $h_id);
    $stmt1->execute();
    $stmt1->close();

    // 3. Room ki occupancy kam karein (ID mein room_id jayega)
    $stmt2 = $conn->prepare('UPDATE room SET occupied = occupied - 1 WHERE id = ?');
    $stmt2->bind_param('i', $room_id); // Sirf ek 'i' kyunki sirf room_id hai
    $stmt2->execute();
    $stmt2->close();
    
    header('location:../Hostel Owner Dashboard/admin/My_students.php');
} else {
    header('location:../Hostel Owner Dashboard/admin/My_students.php');
}
}

// Owner complaint resolve !

if (isset($_POST['resolve_complaint'])) {

    $complaint_id = intval($_POST['complaint_id']);

    $stmt = $conn->prepare("UPDATE complaints SET status = 'resolved', updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $complaint_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Complaint resolved successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to resolve complaint.";
    }
    $stmt->close();
    header("Location:../Hostel Owner Dashboard/admin/complaints.php");
    exit();
}

if (isset($_POST['add_student'])) {

    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $room_type = $_POST['room_type'];    

    // Check that if the email and pass is used

    $query = "SELECT email FROM student WHERE email = ?";
    $stmt0 = $conn->prepare($query);
    $stmt0->bind_param("s", $email);
    $stmt0->execute();

    $res1 = $stmt0->get_result();

    if ($res1->num_rows > 0) {
        header("location:../Hostel Owner Dashboard/admin/My_students.php?error=1");
        exit;
    }        

    // Data added in the studnet table 
    $stmt1 = $conn->prepare("INSERT INTO `student`(`name`,`email`,`password`,`contact`,`address`) VALUES(?,?,?,?,?)");
    $stmt1->bind_param("sssss",$name,$email,$password,$contact,$address);
    $stmt1->execute();
    $stmt1->close();

    // Get the Std_id
    $query = "SELECT id FROM student WHERE `email` = ? AND `password` = ?";
    $stmt2 = $conn->prepare($query);
    $stmt2->bind_param("ss",$email,$password);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $user1 = mysqli_fetch_assoc($res2);
    $std_id = $user1['id'];

    // Now insert the data in the booking
    $stmt3 = $conn->prepare("INSERT INTO `booking`(`student_id`,`hostel_id`,`room_type`) VALUES(?,?,?)");
    $stmt3->bind_param("sss",$std_id,$_SESSION['hostel_id'],$room_type);
    $stmt3->execute();
    $stmt3->close();

    header("Location:../Hostel Owner Dashboard/admin/My_students.php");
    exit();
}

// Block and Active the User By Admin !

if($_POST['action'] == 'get_student_details') {
    $student_id = intval($_POST['student_id']);
    
    $stmt = $conn->prepare(
        "SELECT s.*, 
                COUNT(b.id) as total_bookings
         FROM student s
         LEFT JOIN booking b ON s.id = b.student_id
         WHERE s.id = ?
         GROUP BY s.id"
    );
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        
        // Format the date
        $student['created_at_formatted'] = !empty($student['created_at']) 
            ? date("M d, Y", strtotime($student['created_at'])) 
            : "N/A";
        
        echo json_encode([
            'success' => true,
            'student' => $student
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Student not found'
        ]);
    }
    exit;
}
 
// Block student
if($_POST['action'] == 'block_student') {
    $student_id = intval($_POST['student_id']);
    
    $stmt = $conn->prepare("UPDATE student SET status = 'blocked' WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    
    if($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Student blocked successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to block student'
        ]);
    }
    exit;
}
 
// Unblock student
if($_POST['action'] == 'unblock_student') {
    $student_id = intval($_POST['student_id']);
    
    $stmt = $conn->prepare("UPDATE student SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    
    if($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Student unblocked successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to unblock student'
        ]);
    }
    exit;
}
// ============================================
// COMPLAINT View (for Admin)
// ============================================

// Get complaint details for view modal
if($_POST['action'] == 'get_complaint_details') {
    $complaint_id = intval($_POST['complaint_id']);
    
    $stmt = $conn->prepare(
        "SELECT c.*, 
                s.name as student_name, 
                h.hostel_name as hostel_name
         FROM complaints c
         JOIN student s ON c.student_id = s.id
         JOIN hostel h ON c.hostel_id = h.id
         WHERE c.id = ?"
    );
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $complaint = $result->fetch_assoc();
        
        // Format the date
        $complaint['created_at_formatted'] = !empty($complaint['created_at']) 
            ? date("M d, Y", strtotime($complaint['created_at'])) 
            : "N/A";
        
        echo json_encode([
            'success' => true,
            'complaint' => $complaint
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Complaint not found'
        ]);
    }
    exit;
}


// ============================================================
//  FORGOT PASSWORD 
// ============================================================

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── STEP 1: Send OTP 
if ($action === 'send_otp') {

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['fp_error']   = 'Please enter a valid email address.';
        $_SESSION['fp_prefill'] = $email;
        header("Location: ../User Panel/forgot_password.php");
        exit;
    }
    // Check if email exists — admin table uses admin_id, others use id
    $found = false;
    $checks = [
        'student' => 'email'
    ];
    $sta = "blocked";
    foreach ($checks as $table => $eml) {
        $stmt = $conn->prepare("SELECT $eml FROM $table WHERE status = ? AND email = ? LIMIT 1");
        $stmt->bind_param('ss',$sta,$email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) { 
            header("location:../User Panel/login.php?status=blocked");
            exit();
        }
        $stmt->close();
    }

    foreach ($checks as $table => $pk) {
        $stmt = $conn->prepare("SELECT $pk FROM $table WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) { $found = true; }
        $stmt->close();
        if ($found) break;
    }

    if (!$found) {
        $_SESSION['fp_error']   = 'No account found with that email address.';
        $_SESSION['fp_prefill'] = $email;
        header("Location: ../User Panel/forgot_password.php");
        exit;
    }

    // Generate plain 6-digit OTP
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store in session
    $_SESSION['otp']        = $otp;           // plain — compared plain later
    $_SESSION['otp_email']  = $email;         // FIX: was missing!
    $_SESSION['otp_expiry'] = time() + 600;   // 10 minutes
    unset($_SESSION['otp_verified']);          // reset any previous verification

    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER'];
        $mail->Password   = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->SMTPDebug  = 0;

        $mail->setFrom($_ENV['MAIL_USER'], 'HostelHub');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your HostelHub Password Reset OTP';
        $mail->Body    = getOtpEmailTemplate($otp, $email);
        $mail->AltBody = "Your HostelHub OTP is: $otp — expires in 10 minutes.";
        $mail->send();

        header("Location: ../User Panel/verify_otp.php");
        exit;

    } catch (Exception $e) {
        unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expiry']);
        $_SESSION['fp_error']   = 'Failed to send OTP. Please try again.';
        $_SESSION['fp_prefill'] = $email;
        header("Location: ../User Panel/forgot_password.php");
        exit;
    }
}

// ── STEP 2: Verify OTP ────────────────────────────────────
if ($action === 'verify_otp') {

    // Must have a session with OTP details
    if (!isset($_SESSION['otp_email'], $_SESSION['otp'], $_SESSION['otp_expiry'])) {
        header("Location: ../User Panel/forgot_password.php");
        exit;
    }

    // Check expiry
    if (time() > $_SESSION['otp_expiry']) {
        unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expiry'], $_SESSION['otp_verified']);
        $_SESSION['fp_error'] = 'OTP has expired. Please request a new one.';
        header("Location: ../User Panel/forgot_password.php");
        exit;
    }

    $entered_otp = trim($_POST['otp'] ?? '');

    // FIX: plain string compare — NOT password_verify (OTP stored plain)
    if ($entered_otp !== $_SESSION['otp']) {
        $_SESSION['otp_error'] = 'Incorrect OTP. Please try again.';
        header("Location: ../User Panel/verify_otp.php");
        exit;
    }

    // Mark verified, consume OTP
    $_SESSION['otp_verified'] = true;
    unset($_SESSION['otp'], $_SESSION['otp_expiry']); // OTP used up

    header("Location: ../User Panel/verify_otp.php");
    exit;
}

// ── STEP 3: Reset Password ────────────────────────────────
if ($action === 'reset_password') {

    // Must be verified
    if (empty($_SESSION['otp_verified']) || empty($_SESSION['otp_email'])) {
        header("Location: ../User Panel/forgot_password.php");
        exit;
    }

    $new_pwd     = $_POST['new_password']     ?? '';
    $confirm_pwd = $_POST['confirm_password'] ?? '';

    if (strlen($new_pwd) < 6 || $new_pwd !== $confirm_pwd) {
        $_SESSION['otp_error'] = 'Invalid password. Please check and try again.';
        header("Location: ../User Panel/verify_otp.php");
        exit;
    }

    $email      = $_SESSION['otp_email'];
    $pwd        = $new_pwd;

    $updated = false;
    $tables  = ['student', 'owner', 'admin'];
    foreach ($tables as $table) {
        $stmt = $conn->prepare("UPDATE $table SET password = ? WHERE email = ?");
        $stmt->bind_param('ss', $pwd, $email);
        $stmt->execute();
        if ($stmt->affected_rows > 0) { $updated = true; }
        $stmt->close();
        if ($updated) break;
    }

    // Clear all forgot-password session data
    unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expiry'], $_SESSION['otp_verified']);

    if ($updated) {
        $_SESSION['login_success'] = 'Password updated successfully! Please sign in.';
        header("Location: ../User Panel/login.php");
    } else {
        $_SESSION['fp_error'] = 'Something went wrong. Please try again.';
        header("Location: ../User Panel/forgot_password.php");
    }
    exit;
}

// ── Resend OTP (GET request from JS) ─────────────────────
if ($action === 'resend_otp') {
    $email = $_SESSION['otp_email'] ?? '';
    unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expiry'], $_SESSION['otp_verified']);

    if ($email) {
        // Re-trigger send_otp via POST simulation
        $_POST['action'] = 'send_otp';
        $_POST['email']  = $email;
        // Redirect to a GET handler that will POST internally — simplest: just go to forgot page
        $_SESSION['fp_prefill'] = $email;
        $_SESSION['fp_error']   = 'Session reset. Please enter your email again to resend OTP.';
    }
    header("Location: ../User Panel/forgot_password.php");
    exit;
}

// ============================================================
//  EMAIL TEMPLATE — put this function somewhere in backend.php
//  outside any if/switch block (global scope)
// ============================================================

function getOtpEmailTemplate(string $otp, string $email): string {
    $boxes = '';
    foreach (str_split($otp) as $d) {
        $boxes .= "<span style='display:inline-block;width:44px;height:52px;line-height:52px;text-align:center;font-size:22px;font-weight:800;color:#1a56db;background:#eff6ff;border:2px solid #bfdbfe;border-radius:10px;margin:0 4px;'>$d</span>";
    }
    return "<!DOCTYPE html><html><head><meta charset='UTF-8'></head>
    <body style='margin:0;padding:0;background:#f1f5fd;font-family:Segoe UI,Arial,sans-serif;'>
    <div style='max-width:560px;margin:40px auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);'>
      <div style='background:linear-gradient(135deg,#0f2b6e 0%,#1a56db 100%);padding:32px 40px;text-align:center;'>
        <h1 style='color:#fff;font-size:22px;margin:0;'>Password Reset OTP</h1>
        <p style='color:rgba(255,255,255,.65);font-size:13px;margin:6px 0 0;'>HostelHub Security</p>
      </div>
      <div style='padding:36px 40px;'>
        <p style='color:#0c1a3a;font-size:15px;margin:0 0 8px;'>Hi there,</p>
        <p style='color:#475569;font-size:14px;line-height:1.6;margin:0 0 28px;'>
          We received a password reset request for <strong style='color:#1a56db;'>$email</strong>.
          Use the OTP below. <strong>Do not share it with anyone.</strong>
        </p>
        <div style='text-align:center;margin-bottom:28px;'>
          <p style='font-size:12px;font-weight:700;color:#94a3b8;letter-spacing:.08em;text-transform:uppercase;margin-bottom:14px;'>Your One-Time Password</p>
          $boxes
        </div>
        <div style='background:#fef9c3;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;margin-bottom:24px;'>
          <p style='color:#92400e;font-size:13px;margin:0;'>⏱️ &nbsp;This OTP expires in <strong>10 minutes</strong>.</p>
        </div>
        <p style='color:#94a3b8;font-size:12px;line-height:1.6;margin:0;border-top:1px solid #f1f5fd;padding-top:20px;'>
          If you did not request this, ignore this email — your password will not change.<br><br>— The HostelHub Team
        </p>
      </div>
    </div>
    </body></html>";
}
?>
        
