<?php 
    include 'header.php'; 
?>

        <!-- ====================================================
             HOSTELS
        ==================================================== -->
        <section class="section active" id="hostels">

            <div class="section-header">
                <div class="section-label">FEATURED PROPERTIES</div>
                <h2 class="section-title">Premium Hostels</h2>
                <p class="section-description">Discover the most sought-after hostels with exceptional amenities and unbeatable locations</p>
            </div>

            <div class="search-section">
                <form action="" method="get">
                 <div class="search-box">
                    <i class="fas fa-map-marker-alt search-icon"></i>
                    <input type="text" id="search" name="city" placeholder="Search by city (Mumbai, Delhi, Bangalore...)" value = "<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>"  >
                    <button class="search-btn" id="search_btn" type = "submit" name="search_btn"><i class="fas fa-search"></i> Search</button>
                 </div>
                </form>
            </div>

            <div class="hostel-grid">

            <?php
            require_once "../config.php";
            if(isset($_GET['success']) && $_GET['success'] == 1){
                echo "<script>alert('You have successfully booked the hostel. You will receive a confirmation email soon.!');</script>";
            }
            if (isset($_GET['city']) && $_GET['city'] !== '') {
                $city = $_GET['city'];
                $search = '%'.$city.'%';
                $query = "SELECT * FROM hostel where city like ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s',$search);
                $stmt->execute();
                $res = $stmt->get_result();
            } else {
                $query = "SELECT * FROM hostel";
                $res = mysqli_query($conn, $query);
            }
            if (mysqli_num_rows($res) > 0) {
            while ($user = mysqli_fetch_assoc($res)) {  

                //  Each hostel has its OWN id from the database row
                $id          = $user['id'];
                $name        = $user['hostel_name'];
                $city        = $user['city'];
                $img         = $user['image_path'];
                $description = $user['description'];
            
             // Build amenities HTML BEFORE the echo
            $amenities = $user['amenities'] ;
            $amenities_html = "";
            if (!empty($amenities)) {
                $amenity_list = explode(',', $amenities); // split the string into array
                foreach ($amenity_list as $amenity) {
                    $amenity = trim($amenity); // remove any extra spaces
                    $amenities_html .= "<div class='feature-tag'>$amenity</div>";
                }
            } else {
                $amenities_html = "<div class='feature-tag'>No amenities listed</div>";
            }

            // Now embed it cleanly inside the echo
            echo "<div class='hostel-card'>
                <div class='hostel-image-wrapper'>
                    <img src='../Hostel Images/$img'   alt='Hostel' class='hostel-image'>
                    <div class='rating-badge'><i class='fas fa-star'></i>Hostel</div>
                </div>
                <div class='hostel-content'>
                    <div class='hostel-location'><i class='fas fa-map-marker-alt'></i> $city</div>
                    <h3 class='hostel-name'>$name</h3>
                    <p class='hostel-description'>$description</p>
                    <div class='hostel-features'>
                        $amenities_html
                    </div>
                    <div class='hostel-actions' style='width:100%;'>
                        <a href='details.php?id=$id' style='width:100%; display:block;'>
                            <button class='btn-detail' style='width:100%;'>Details</button>
                        </a>
                    </div>
                </div>
            </div>";}}
            else{
                echo "<h2 style='width: 90vw; text-align:center;'>No Hostels Found !</h2>";
            }
        ?>
            </div>


        </section>

<script>
    // $(docuemnt).ready(fuction(){
    //     var search = $("#search").val();
        

    //     $("search_btn").click(function(){
    //         alert("search");
    //     })
    // })
</script>
<?php include 'footer.php'; ?>
