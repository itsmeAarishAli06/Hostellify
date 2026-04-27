<?php 
    include 'header.php'; 
 if (!(isset($_SESSION['islogin']) && $_SESSION['islogin'] )) {
    header("location:login.php");
 }
?>

        <!-- ====================================================
             DASHBOARD
        ==================================================== -->
    <?php
        require_once "../config.php";
        // $conn = new mysqli("localhost","root","","hostel");

        $stmt1 = $conn->prepare('
        SELECT 
            r.room_number, 
            h.hostel_name, 
            r.room_type, 
            b.status AS booking_status,
            b.created_at,

            -- Total complaints
            (SELECT COUNT(*) 
            FROM complaints c1 
            WHERE c1.student_id = b.student_id) AS total_complaints,

            -- Pending complaints
            (SELECT COUNT(*) 
            FROM complaints c2 
            WHERE c2.student_id = b.student_id 
            AND c2.status = "Pending") AS pending_complaints

            FROM booking b
            LEFT JOIN room r ON b.room_id = r.id        
            JOIN hostel h ON b.hostel_id = h.id         
            WHERE b.student_id = ?
        ');
            $stmt1->bind_param('i',$_SESSION['user_id']);
            $stmt1->execute();
            $res = $stmt1->get_result();
            if (mysqli_num_rows($res) > 0) {
                $user = mysqli_fetch_assoc($res);
            }else{

            }

        ?>
        <section class="section active" id="dashboard">

            <div class="section-header">
                <div class="section-label">STUDENT PORTAL</div>
                <h2 class="section-title">Dashboard</h2>
                <p class="section-description">Manage your hostel, profile, and residence information</p>
            </div>

            <div class="dashboard-tabs">
                <button class="tab-btn active" onclick="showTab('overview')">Overview</button>
                <button class="tab-btn"        onclick="showTab('my-hostel')">My Hostel</button>
                <button class="tab-btn"        onclick="showTab('profile')">Profile</button>
            </div>

            <!-- Tab: Overview -->
            <div class="dashboard-content active" id="overview">
                <div class="welcome-message">
                    <?php 
                echo "<h2>Welcome back, ".($_SESSION['user_name'] ?? "Guest")."! 👋</h2>"; 
                ?>
                    <p>Here's your hostel residence overview</p>
                </div>
                <div class="status-cards">
                    <div class="status-card">
                        <div class="card-icon"><i class="fas fa-building"></i></div>
                        <div class="card-label">Enrolled Hostel</div>
                        <div class="card-value"><?php echo  $user['hostel_name']?? "N/A" ?></div>
                    </div>
                    <div class="status-card">
                        <div class="card-icon"><i class="fas fa-door-open"></i></div>
                        <div class="card-label">Room Number</div>
                        <div class="card-value"><?php echo  $user['room_number'] ?? "N/A" ?></div>
                    </div>
                    <div class="status-card">
                        <div class="card-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="card-label">Total Complaints/Resolved Complaints</div>
                        <div class="card-value"><?php echo  $user['total_complaints'] ?? "N/A" ?> / <?php echo  $user['pending_complaints'] ?? "N/A" ?><div class="card-label"></div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Tab: My Hostel -->
            <div class="dashboard-content" id="my-hostel">
                <div class="form-section">
                    <h3 style="color: var(--text-dark); margin-bottom: 25px; font-weight: 700;">Current Enrollment</h3>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Hostel Name</th>
                                    <th>Room Type</th>
                                    <th>Room Number</th>
                                    <th>Booking Date</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo  $user['hostel_name'] ?? "N/A" ?></td>
                                    <td><?php echo  $user['room_type'] ?? "N/A"  ?></td>
                                    <td><?php echo  $user['room_number'] ?? "N/A" ?></td>
                                    <td>                                            
                                    <?php echo !empty($user['created_at']) ? date("d-m-y", strtotime($user['created_at'])) : "N/A"; ?>
                                    </td>
                                    </td>
                                    <td><?php echo  $user['start_date'] ?? "N/A" ?></td>
                                    <td><span class="badge success"><?php echo  $user['booking_status'] ?? "N/A"  ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Profile -->
            <div class="dashboard-content" id="profile">
                <form class="form-section" action="../Backend/backend.php" method="POST">
                    <h3 style="color: var(--text-dark); margin-bottom: 25px; font-weight: 700;">Personal Information</h3>
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <?php echo "<input type='text' class='form-input' name='name' value=\"" .( $_SESSION['user_name'] ?? "" ). "\" placeholder='Enter your name'> ";?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <?php echo "<input type='text' class='form-input' name='email' value=\"" .( $_SESSION['user_email'] ?? "" ) . "\" placeholder='Enter your email'>";?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <?php echo "<input type='text' class='form-input' name='password' value=\"" . ($_SESSION['user_password'] ?? "") . "\" placeholder='Password'>";?>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <?php echo "<input type='text' class='form-input' name='contact' value=\"" . ( $_SESSION['user_contact'] ?? "" ). "\" placeholder='Contact'>";?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label><?php echo "<textarea class='form-textarea' name='address' placeholder='Enter your address'>" . (($_SESSION['user_address']) ?? "") . "</textarea>";?>
                    </div>
                    <button class="form-button" name="update_profile">Update Profile</button>
               </form>
            </div>

        </section>

<?php include 'footer.php'; ?>
