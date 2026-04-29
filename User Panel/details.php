<?php 
    include 'header.php';
    require_once "../config.php";

    // ─── 1. Fetch hostel details ──────────────────────────────────────────────
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM hostel WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res  = $stmt->get_result();

    if (mysqli_num_rows($res) > 0) {
        $user        = mysqli_fetch_assoc($res);
        $hostel_name = $user['hostel_name'];
        $city        = $user['city'];
        $address     = $user['address'];
        $contact     = $user['contact'];
        $email       = $user['email'];
        $hostel_type = $user['hostel_type'];
        $capacity    = $user['capacity'];
        $img    = $user['image_path'];
        $description = $user['description'];
        $amenities = $user['amenities'];
    }
    $stmt->close();

    // ─── 2. Get TOTAL CAPACITY per room type ─────────────────────────────────
    $stmt_cap_ac = $conn->prepare("SELECT COALESCE(SUM(capacity), 0) FROM room WHERE hostel_id = ? AND room_type = 'AC'");
    $stmt_cap_ac->bind_param('i', $id);
    $stmt_cap_ac->execute();
    $stmt_cap_ac->bind_result($ac_total_capacity);
    $stmt_cap_ac->fetch();
    $stmt_cap_ac->close();

    $stmt_cap_nonac = $conn->prepare("SELECT COALESCE(SUM(capacity), 0) FROM room WHERE hostel_id = ? AND room_type = 'Non AC'");
    $stmt_cap_nonac->bind_param('i', $id);
    $stmt_cap_nonac->execute();
    $stmt_cap_nonac->bind_result($nonac_total_capacity);
    $stmt_cap_nonac->fetch();
    $stmt_cap_nonac->close();

    // ─── 3. Get REAL PRICES ───────────────────────────────────────────────────
    $stmt_price_nonac = $conn->prepare("SELECT price_monthly FROM room WHERE hostel_id = ? AND room_type = 'Non AC' LIMIT 1");
    $stmt_price_nonac->bind_param('i', $id);
    $stmt_price_nonac->execute();
    $stmt_price_nonac->bind_result($nonac_price);
    $stmt_price_nonac->fetch();
    $stmt_price_nonac->close();

    $stmt_price_ac = $conn->prepare("SELECT price_monthly FROM room WHERE hostel_id = ? AND room_type = 'AC' LIMIT 1");
    $stmt_price_ac->bind_param('i', $id);
    $stmt_price_ac->execute();
    $stmt_price_ac->bind_result($ac_price);
    $stmt_price_ac->fetch();
    $stmt_price_ac->close();

    $nonac_price = $nonac_price ?? 0;
    $ac_price    = $ac_price    ?? 0;
    $min_price = min($nonac_price ?: PHP_INT_MAX, $ac_price ?: PHP_INT_MAX);
    if ($min_price === PHP_INT_MAX) $min_price = 0;

    // ─── 4. Count ACTIVE bookings ─────────────────────────────────────────────
    $stmt_ac = $conn->prepare("SELECT COUNT(*) FROM booking WHERE hostel_id = ? AND room_type = 'AC' AND status IN ('Pending', 'Approved')");
    $stmt_ac->bind_param('i', $id);
    $stmt_ac->execute();
    $stmt_ac->bind_result($ac_booked);
    $stmt_ac->fetch();
    $stmt_ac->close();

    $stmt_nonac = $conn->prepare("SELECT COUNT(*) FROM booking WHERE hostel_id = ? AND room_type = 'Non AC' AND status IN ('Pending', 'Approved')");
    $stmt_nonac->bind_param('i', $id);
    $stmt_nonac->execute();
    $stmt_nonac->bind_result($nonac_booked);
    $stmt_nonac->fetch();
    $stmt_nonac->close();

    // ─── 5. Calculate seats left ──────────────────────────────────────────────
    $ac_left    = max(0, $ac_total_capacity    - $ac_booked);
    $nonac_left = max(0, $nonac_total_capacity - $nonac_booked);

    // ─── 6. Check if student has active booking ───────────────────────────────
    $stmt_check = $conn->prepare("SELECT id FROM booking WHERE student_id = ? AND status IN ('Pending', 'Approved')");
    $stmt_check->bind_param('i', $_SESSION['user_id']);
    $stmt_check->execute();
    $res_check  = $stmt_check->get_result();
    $hasBtn     = (mysqli_num_rows($res_check) === 0);
    $stmt_check->close();
?>

<section class="detail-section">
    <div class="detail-header">
        <a href="hostels.php" class="back-link">
            <i class="fas fa-chevron-left"></i> Back to Hostels
        </a>
    </div>

    <div class="detail-container">
        <div class="detail-left">
            <div class="image-box">
                <img src="<?php echo "../Hostel Images/$img"; ?>" 
                     alt="<?php echo $hostel_name; ?>" class="hostel-image">
                <div class="rating-badge"><i class="fas fa-star"></i> 4.8</div>
            </div>

            <h2 class="hostel-name"><?php echo $hostel_name; ?></h2>
            <div class="hostel-location">
                <i class="fas fa-map-marker-alt"></i> <?php echo $city; ?>
            </div>

            <div class="features-box">
                <?php 
                if (!empty($amenities)) {
                    $amenityList = explode(',', $amenities);
                    foreach ($amenityList as $amenity) {
                        $amenity = trim($amenity);
                        if (!empty($amenity)) {
                            echo '<span class="feature-tag">' . htmlspecialchars($amenity) . '</span>';
                        }
                    }
                } else {
                    echo '<span class="feature-tag">No amenities listed</span>';
                }
                ?>
            </div>
            <div class="features-box">
                <span style = ""> <b><h3> <?php echo strtoupper($hostel_type) ?> Hostel</h3></b></span>
            </div>


            <h3 class="about-title">About This Hostel</h3>
            <p class="about-text"><?php echo $description; ?></p>
        </div>

        <div class="detail-right">
            <!-- PRICE CARD - Shows minimum price only -->
            <div class="price-card">
                <p class="price-label">Starting From</p>
                <p class="price-amount">Rs <?php echo $min_price; ?></p>
                <p class="price-unit">Per Month</p>
            </div>

            <!-- ROOM TYPE SELECTION -->
            <div class="room-type-card">
                <h4 class="contact-title">Select Room Type</h4>

                <!-- NON-AC OPTION -->
                <label class="room-option">
                    <input type="radio" name="room_type_display" value="Non AC" 
                           id="radio-nonac" checked>
                    <div class="room-option-inner">
                        <div class="room-option-left">
                            <i class="fas fa-fan"></i>
                            <div>
                                <p class="room-option-name">Non-AC Room</p>
                                <p class="room-option-desc">Fan, natural ventilation</p>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <span class="room-option-price">
                                Rs <?php echo $nonac_price; ?><small>/Month</small>
                            </span>
                            <p style="font-size:.73rem; margin:0;
                               color:<?php echo $nonac_left > 0 ? '#28a745' : '#dc3545'; ?>;">
                                <?php echo $nonac_left > 0 ? $nonac_left . ' Beds left' : 'Full'; ?>
                            </p>
                        </div>
                    </div>
                </label>

                <!-- AC OPTION -->
                <label class="room-option">
                    <input type="radio" name="room_type_display" value="AC" 
                           id="radio-ac">
                    <div class="room-option-inner">
                        <div class="room-option-left">
                            <i class="fas fa-snowflake"></i>
                            <div>
                                <p class="room-option-name">AC Room</p>
                                <p class="room-option-desc">Air conditioned, premium</p>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <span class="room-option-price">
                                Rs <?php echo $ac_price; ?><small>/Month</small>
                            </span>
                            <p style="font-size:.73rem; margin:0;
                               color:<?php echo $ac_left > 0 ? '#28a745' : '#dc3545'; ?>;">
                                <?php echo $ac_left > 0 ? $ac_left . ' Beds left' : 'Full'; ?>
                            </p>
                        </div>
                    </div>
                </label>
            </div>

            <!-- CONTACT CARD -->
            <div class="contact-card">
                <h4 class="contact-title">Contact Information</h4>
                <div class="contact-item">
                    <p class="contact-label"><i class="fas fa-map-marker-alt"></i> Address</p>
                    <p class="contact-info"><?php echo $address; ?></p>
                </div>
                <div class="contact-item">
                    <p class="contact-label"><i class="fas fa-phone"></i> Phone</p>
                    <a href="tel:<?php echo $contact; ?>" class="contact-link"><?php echo $contact; ?></a>
                </div>
                <div class="contact-item">
                    <p class="contact-label"><i class="fas fa-envelope"></i> Email</p>
                    <a href="mailto:<?php echo $email; ?>" class="contact-link"><?php echo $email; ?></a>
                </div>
            </div>

            <!-- BOOK NOW FORM -->
            <form action="../Backend/backend.php" method="POST" onsubmit="return validateBookingSubmit(event)">
                <input type="hidden" name="hostel_id" value="<?php echo $id; ?>">
                <input type="hidden" name="room_type_selected" id="selected-type" value="Non AC">
                <input type="hidden" name="roomprice" id="selected-price" value="<?php echo $nonac_price; ?>">

                <?php if ($hasBtn): ?>
                    <button id="bookbtn" class="btn-detail" name="booking_btn" type="submit" 
                            style="width:100%; margin-top:15px;">
                        Book Now
                    </button>
                    <div id="fullmsg" class="btn-detail"
                         style="width:100%; margin-top:15px; background:#dc3545;
                                border:none; color:white; display:none; text-align:center;">
                        No Available Beds in This Room Type
                    </div>
                <?php else: ?>
                    <div class="btn-detail"
                         style="width:100%; margin-top:15px; border:black   ; background:black; color:white; cursor:not-allowed;">
                        You Already Have a Booking
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>

<script>
    // Prevent conflicts with script.js by using unique function names
    (function() {
        'use strict';
        
        console.log('=== HOSTEL DETAIL PAGE DEBUG ===');
        
        // Data from PHP
        const seatsLeft = {
            'AC': <?php echo $ac_left; ?>,
            'Non AC': <?php echo $nonac_left; ?>
        };

        const prices = {
            'AC': <?php echo $ac_price; ?>,
            'Non AC': <?php echo $nonac_price; ?>
        };

        console.log('Seats Available:', seatsLeft);
        console.log('Prices:', prices);

        // Update hidden fields and UI when room type changes
        function handleRoomSelection(roomType) {
            console.log('🔄 Room selected:', roomType);
            
            const price = prices[roomType];
            const seats = seatsLeft[roomType];

            console.log('  → Price:', price, '| Seats:', seats);

            // Update hidden form fields
            const priceInput = document.getElementById('selected-price');
            const typeInput = document.getElementById('selected-type');
            
            if (priceInput && typeInput) {
                priceInput.value = price;
                typeInput.value = roomType;
                
                console.log('  ✅ Updated: selected-price =', priceInput.value);
                console.log('  ✅ Updated: selected-type =', typeInput.value);
            }

            // Show/hide button based on availability
            const bookBtn = document.getElementById('bookbtn');
            const fullMsg = document.getElementById('fullmsg');
            
            if (bookBtn && fullMsg) {
                if (seats > 0) {
                    bookBtn.style.display = 'block';
                    bookBtn.disabled = false;
                    fullMsg.style.display = 'none';
                    console.log('  ✅ Button: ENABLED');
                } else {
                    bookBtn.style.display = 'none';
                    fullMsg.style.display = 'block';
                    console.log('  ⚠️ Button: DISABLED (no seats)');
                }
            }
        }

        // Validate before submission (global function for form onsubmit)
        window.validateBookingSubmit = function(event) {
            const roomType = document.getElementById('selected-type').value;
            const seats = seatsLeft[roomType];
            
            console.log('📤 FORM SUBMIT - Room Type:', roomType, '| Seats:', seats);
            
            if (seats <= 0) {
                event.preventDefault();
                alert('This room type is fully booked. Please select another room type.');
                console.log('❌ BLOCKED - No seats');
                return false;
            }
            
            console.log('✅ SUBMIT ALLOWED');
            return true;
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📍 Setting up room selection listeners...');
            
            const radioNonAc = document.getElementById('radio-nonac');
            const radioAc = document.getElementById('radio-ac');
            
            if (radioNonAc) {
                radioNonAc.addEventListener('change', function() {
                    if (this.checked) {
                        console.log('🔘 Non-AC selected');
                        handleRoomSelection('Non AC');
                    }
                });
            }
            
            if (radioAc) {
                radioAc.addEventListener('change', function() {
                    if (this.checked) {
                        console.log('🔘 AC selected');
                        handleRoomSelection('AC');
                    }
                });
            }
            // Initialize with default
            handleRoomSelection('Non AC');
            console.log('✅ Initialization complete');
        });
    })();
</script>

<?php include 'footer.php'; ?>