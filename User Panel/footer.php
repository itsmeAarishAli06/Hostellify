    </div><!-- end .content-wrapper -->


    <!-- ============================================================
         FOOTER
    ============================================================ -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>HostelHub</h3>
                <p style="color: var(--text-light); line-height: 1.8; margin-bottom: 18px; font-weight: 400; font-size: 0.95rem;">
                    Your trusted platform for safe and affordable hostel living. Find your perfect home away from home.
                </p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="hostels.php">Hostels</a></li>
                    <li><a href="about.php">About</a></li>
                    <?php if(isset($_SESSION['islogin']) && $_SESSION['islogin']){
                    echo "<li><a href='dashboard.php'>Dashboard</a></li>
                    <li><a href='complaints.php'>Complaints</a></li>";
                    }
                    ?>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <div class="footer-contact"><i class="fas fa-phone-alt"></i><span>03172873743</span></div>
                <div class="footer-contact"><i class="fas fa-envelope"></i><span>support@hostelhub.com</span></div>
                <div class="footer-contact"><i class="fas fa-map-marker-alt"></i><span>Qasimabad, Hyderabad</span></div>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                <a style="text-decoration:none; " href="https://www.facebook.com/profile.php?id=61574825833558" target="_blank">        
                    <div class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></div>
                </a>    
                    <div class="social-icon" title="Twitter"><i class="fab fa-twitter"></i></div>
                <a style="text-decoration:none; " href="https://www.instagram.com/aarish_ali_memon/" target="_blank">    
                    <div class="social-icon" title="Instagram"><i class="fab fa-instagram"></i>
                </a>    
                </div>
                <a style="text-decoration:none; " href="https://linkedin.com/in/aarish-ali-a20704367" target="_blank">
                 <div class="social-icon" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                 </div>
                </a>
                </div>
            </div>
        </div>
        <div class="footer-divider"></div>
        <div class="footer-bottom">
            <p>&copy; 2025 HostelHub. All Rights Reserved | Designed for Students</p>
        </div>
    </footer>

    <script src="script.js"></script>

</body>
</html>
