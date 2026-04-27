<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 if (!(isset($_SESSION['islogin']) && $_SESSION['islogin'])) {
    header("location:../../User Panel/login.php");
 }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Hostel Owner Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<style>
  .profile-first-letter{
    height:40px;
    padding:8px 12px;  
    background-color:#87CEFA; 
    border-radius:50%;
  }
  .profile-avatar {
    width: 40px;
    height: 40px;
    background-color: #87CEFA; /* matches sidebar sky blue */
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    font-size: 18px;
}

  /* Make profile dropdown display side-by-side */
  .nav-item.dropdown .nav-link.dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
  }
</style>
<body>


    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa  me-1"></i>HostelHub</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative profile-first-letter">
                        <h5 style="color:white;"><?php echo strtoupper(($_SESSION['owner_name'] ?? 'U')[0] ?? '');?></h5>
                    </div>
                    <div class="ms-3">
                        <h6 class='mb-0'><?php echo isset($_SESSION['owner_name']) ? htmlspecialchars($_SESSION['owner_name']) : 'N/A'; ?></h6>
                        <span>Hostel Owner</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link  p-2"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="hostel_profile.php" class="nav-item nav-link p-2"><i class="fa fa-th me-2"></i>Hostel Profile</a>
                    <a href="rooms.php" class="nav-item nav-link p-2"><i class="fa fa-keyboard me-2"></i>Rooms</a>
                    <a href="My_Students.php" class="nav-item nav-link p-2"><i class="fa fa-table me-2"></i>Students</a>
                    <a href="Application.php" class="nav-item nav-link p-2"><i class="fa fa-table me-2"></i>Application</a>
                    <a href="complaints.php" class="nav-item nav-link p-2"><i class="fa fa-table me-2"></i>Complaints</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="profile-avatar">
                              <span>
                                  <?php 
                                      $name = $_SESSION['owner_name'] ?? '';
                                      echo !empty($name) ? strtoupper($name[0]) : '?';
                                  ?>
                              </span>
                          </div>
                            <span class='d-none d-lg-inline-flex'><?php echo isset($_SESSION['owner_name']) ? htmlspecialchars($_SESSION['owner_name']) : 'N/A'; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item" onclick="document.getElementById('ownerProfileModal').style.display='flex'; return false;">
                                <i class="fas fa-user-edit me-2"></i>My Profile
                            </a>
                            
                            <a href="logout.php" class="dropdown-item">
                              <i class="fas fa-sign-out-alt me-2"></i>Log Out
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- ===================== OWNER PROFILE MODAL ===================== -->
<div id="ownerProfileModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:99999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:16px;width:100%;max-width:500px;margin:16px;box-shadow:0 24px 60px rgba(0,0,0,.25);overflow:hidden;">

    <!-- Modal Header -->
    <div style="background:linear-gradient(135deg,#0d6efd,#0a58ca);padding:22px 26px;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:14px;">
        <div style="width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.22);display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:#fff;border:2px solid rgba(255,255,255,.4);">
          <?php echo strtoupper(substr($_SESSION['owner_name'] ?? 'O', 0, 1)); ?>
        </div>
        <div>
          <h6 style="margin:0;color:#fff;font-weight:700;font-size:1rem;">My Profile</h6>
          <p style="margin:0;color:rgba(255,255,255,.75);font-size:.76rem;">Manage your personal information</p>
        </div>
      </div>
      <button onclick="document.getElementById('ownerProfileModal').style.display='none'"
        style="background:rgba(255,255,255,.18);border:none;color:#fff;width:34px;height:34px;border-radius:50%;cursor:pointer;font-size:1.1rem;line-height:1;display:flex;align-items:center;justify-content:center;">
        &times;
      </button>
    </div>

    <!-- Modal Body -->
    <form method="POST" action="../../Backend/backend.php" style="padding:26px;">

      <!-- Name + Phone -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
        <div>
          <label style="font-weight:700;font-size:.8rem;color:#344767;display:block;margin-bottom:6px;">
            <i class="fas fa-user me-1" style="color:#0d6efd;"></i>Full Name
          </label>
          <input type="text" name="owner_name"
            value="<?php echo htmlspecialchars($_SESSION['owner_name'] ?? ''); ?>"
            placeholder="Your full name"
            style="width:100%;border:1.5px solid #e0e6ed;border-radius:10px;padding:10px 13px;font-size:.85rem;outline:none;box-sizing:border-box;transition:border .2s;"
            onfocus="this.style.borderColor='#0d6efd';this.style.boxShadow='0 0 0 3px rgba(13,110,253,.12)'"
            onblur="this.style.borderColor='#e0e6ed';this.style.boxShadow='none'"/>
        </div>
        <div>
          <label style="font-weight:700;font-size:.8rem;color:#344767;display:block;margin-bottom:6px;">
            <i class="fas fa-phone me-1" style="color:#0d6efd;"></i>Phone
          </label>
          <input type="text" name="owner_contact"
            value="<?php echo htmlspecialchars($_SESSION['owner_contact'] ?? ''); ?>"
            placeholder="+92 XXX XXXXXXX"
            style="width:100%;border:1.5px solid #e0e6ed;border-radius:10px;padding:10px 13px;font-size:.85rem;outline:none;box-sizing:border-box;transition:border .2s;"
            onfocus="this.style.borderColor='#0d6efd';this.style.boxShadow='0 0 0 3px rgba(13,110,253,.12)'"
            onblur="this.style.borderColor='#e0e6ed';this.style.boxShadow='none'"/>
        </div>
      </div>

      <!-- Email -->
      <div style="margin-bottom:16px;">
        <label style="font-weight:700;font-size:.8rem;color:#344767;display:block;margin-bottom:6px;">
          <i class="fas fa-envelope me-1" style="color:#0d6efd;"></i>Email Address
        </label>
        <input type="email" name="owner_email"
          value="<?php echo htmlspecialchars($_SESSION['owner_email'] ?? ''); ?>"
          placeholder="owner@email.com"
          style="width:100%;border:1.5px solid #e0e6ed;border-radius:10px;padding:10px 13px;font-size:.85rem;outline:none;box-sizing:border-box;transition:border .2s;"
          onfocus="this.style.borderColor='#0d6efd';this.style.boxShadow='0 0 0 3px rgba(13,110,253,.12)'"
          onblur="this.style.borderColor='#e0e6ed';this.style.boxShadow='none'"/>
      </div>

      <!-- New Password -->
      <div style="margin-bottom:22px;">
        <label style="font-weight:700;font-size:.8rem;color:#344767;display:block;margin-bottom:6px;">
          <i class="fas fa-lock me-1" style="color:#0d6efd;"></i>New Password
          <span style="color:#aaa;font-weight:400;font-size:.75rem;"> — leave blank to keep current</span>
        </label>
        <div style="position:relative;">
          <input type="password" name="owner_password" id="ownerPassInput"
            placeholder="Enter new password" value = "<?php echo htmlspecialchars($_SESSION['owner_password'] ?? ''); ?>"
            style="width:100%;border:1.5px solid #e0e6ed;border-radius:10px;padding:10px 40px 10px 13px;font-size:.85rem;outline:none;box-sizing:border-box;transition:border .2s;"
            onfocus="this.style.borderColor='#0d6efd';this.style.boxShadow='0 0 0 3px rgba(13,110,253,.12)'"
            onblur="this.style.borderColor='#e0e6ed';this.style.boxShadow='none'"/>
          <!-- Toggle visibility -->
          <i class="fas fa-eye" id="togglePassIcon"
            onclick="
              var inp = document.getElementById('ownerPassInput');
              var ico = document.getElementById('togglePassIcon');
              if(inp.type==='password'){inp.type='text';ico.className='fas fa-eye-slash';}
              else{inp.type='password';ico.className='fas fa-eye';}
            "
            style="position:absolute;right:13px;top:50%;transform:translateY(-50%);color:#aaa;cursor:pointer;font-size:.85rem;">
          </i>
        </div>
      </div>

      <!-- Buttons -->
      <div style="display:flex;gap:12px;">
        <button type="submit" name="update_owner_personal"
          style="flex:1;background:#0d6efd;color:#fff;border:none;padding:12px;border-radius:10px;font-weight:700;font-size:.9rem;cursor:pointer;transition:background .2s;"
          onmouseover="this.style.background='#0a58ca'" onmouseout="this.style.background='#0d6efd'">
          <i class="fas fa-save me-2"></i>Save Changes
        </button>
        <button type="button"
          onclick="document.getElementById('ownerProfileModal').style.display='none'"
          style="flex:1;background:#f8f9fa;color:#555;border:1.5px solid #dee2e6;padding:12px;border-radius:10px;font-weight:700;font-size:.9rem;cursor:pointer;transition:background .2s;"
          onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#f8f9fa'">
          Cancel
        </button>
      </div>

    </form>
  </div>
</div>
<!-- ===================== END MODAL ===================== -->
</body>
</html>