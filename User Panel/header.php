<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelHub - Premium Hostel Living</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Manrope:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Animated background shapes -->
    <div class="background-animation">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>

    <!-- Floating particles (created by JS) -->
    <div class="particles" id="particlesContainer"></div>


    <!-- ============================================================
         NAVBAR
    ============================================================ -->
    <nav class="navbar">

    <div class="logo" onclick="window.location.href='index.php'">HostelHub</div>

    <div class='nav-center'>
        <ul class='nav-menu'>
            <li class='nav-item'><a href='index.php' class='nav-link'>Home</a></li>
            <li class='nav-item'><a href='hostels.php' class='nav-link'>Hostels</a></li>
            <li class='nav-item'><a href='about.php' class='nav-link'>About</a></li>
            <li class='nav-item'><a href='contact.php' class='nav-link'>Contact</a></li>
            
            <?php if (isset($_SESSION['islogin']) && $_SESSION['islogin']): ?>
            <li class='nav-item'><a href='complaints.php' class='nav-link'>Complaints</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- NAV BUTTONS SECTION -->
    <div class="nav-buttons">
        <?php if (isset($_SESSION['islogin']) && $_SESSION['islogin']): ?>
            
            <!-- PROFILE AVATAR WITH DROPDOWN -->
            <div class="profile-dropdown-wrapper">
                <!-- Circular Avatar Button -->
                <button class="profile-avatar-btn" id="profileAvatarBtn" title="Profile Menu">
                    <span class="avatar-letter">
                        <?php echo strtoupper(($_SESSION['user_name'] ?? 'U')[0] ?? '');?>
                    </span>
                </button>

                <!-- Dropdown Menu -->
                <div class="profile-dropdown-menu" id="profileDropdownMenu">
                    
                    <!-- User Info Section -->
                    <div class="profile-dropdown-header">
                        <div class="profile-avatar-large">
                            <?php echo strtoupper(($_SESSION['user_name'] ?? 'U')[0] ?? ''); ?>
                        </div>
                        <div class="profile-user-info">
                            <h3 class="profile-user-name">
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            </h3>
                            <p class="profile-user-email">
                                <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'user@example.com'); ?>
                            </p>
                            <h3 class="profile-user-email">
                               <b> <?php echo htmlspecialchars($_SESSION['user_type'] ?? 'User'); ?></b>
                        </h3>
                        <li class='nav-item'><a href='dashboard.php' class='nav-link'>Dashboard</a></li>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="profile-dropdown-divider">
                        
                    </div>

                    <!-- Logout Button -->
                    <a href="logout.php" class="profile-logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

        <?php else: ?>
            
            <!-- NOT LOGGED IN - SHOW BUTTONS -->
            <a href='register.php'><button class='btn-register'>Register</button></a>

        <?php endif; ?>
    </div>

    <!-- HAMBURGER MENU -->
    <div class="hamburger" id="hamburgerMenu">
        <span></span>
        <span></span>
        <span></span>
    </div>

</nav>

    <!-- Mobile dropdown nav -->
     
    <div class="mobile-nav" id="mobileNav">
    <?php
      echo 
       "<a href='index.php' class='nav-link'>Home</a>
        <a href='hostels.php' class='nav-link'>Hostels</a>
        <a href='about.php' class='nav-link'>About</a>
        <a href='contact.php' class='nav-link'>Contact</a>";
        if (isset($_SESSION['islogin']) && $_SESSION['islogin']) {
            echo   "<a href='complaints.php' class='nav-link'>Complaints</a>";    
        }
    ?>
    </div>

    <!-- ============================================================
         CONTENT WRAPPER
    ============================================================ -->
    <div class="content-wrapper">
