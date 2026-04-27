<?php include("header.php");  ?>

<style>
body{
  overflow-x: hidden;
}
.form-card { border-radius: 14px; border: 1px solid #e9ecef; box-shadow: 0 4px 18px rgba(0,0,0,0.06); }
.form-card .card-head { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
.form-card .card-head h6 { margin: 0; font-weight: 700; font-size: .95rem; }
.form-label-custom { font-weight: 700; font-size: .83rem; color: #1a202c; margin-bottom: 5px; display: block; }
.input-custom { border: 1.5px solid #dee2e6; border-radius: 10px; padding: 9px 13px; font-size: .88rem; width: 100%; transition: border .2s; outline: none; font-family: inherit; }
.input-custom:focus { border-color: #00bcd4; box-shadow: 0 0 0 3px rgba(0,188,212,.12); }
textarea.input-custom { resize: vertical; min-height: 110px; }
.btn-save { background: #00bcd4; color: #fff; border: none; padding: 10px 26px; border-radius: 10px; font-weight: 700; font-size: .9rem; cursor: pointer; transition: all .2s; }
.btn-save:hover { background: #0097a7; box-shadow: 0 4px 14px rgba(0,188,212,.35); }
.upload-zone { border: 2px dashed #dee2e6; border-radius: 12px; padding: 36px 20px; text-align: center; cursor: pointer; transition: all .2s; background: #fafcff; }
.upload-zone:hover { border-color: #00bcd4; background: rgba(0,188,212,.05); }
.upload-zone i { font-size: 2.2rem; color: #00bcd4; margin-bottom: 10px; }
.img-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 10px; margin-top: 14px; }
.img-item {
  position: relative;
  border-radius: 10px;
  overflow: hidden;
  aspect-ratio: 1;
  background: #f1f3f5;
  border: 1px solid #e9ecef;
}
.img-item img { width: 100%; height: 100%; object-fit: cover; }
.img-item .rm { position: absolute; top: 5px; right: 5px; width: 22px; height: 22px; background: rgba(0,0,0,.55); border-radius: 50%; color: #fff; font-size: .65rem; display: flex; align-items: center; justify-content: center; cursor: pointer; opacity: 0; transition: .2s; border: none; }
.img-item:hover .rm { opacity: 1; }
.profile-preview { text-align: center; padding: 28px 20px; }
.hostel-icon { width: 90px; height: 90px; border-radius: 18px; background: rgba(0,188,212,.12); color: #00bcd4; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 14px; border: 2.5px solid #00bcd4; }
.meta-row { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
.meta-row:last-child { border-bottom: none; }
.meta-icon { width: 30px; height: 30px; border-radius: 8px; background: rgba(0,188,212,.1); color: #00bcd4; display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; }
.meta-label { font-size: .72rem; color: #888; font-weight: 700; }
.meta-val { font-size: .85rem; font-weight: 700; color: #1a202c; }
.check-amenity { accent-color: #00bcd4; }
.toast-msg { position: fixed; bottom: 24px; right: 24px; background: #00bcd4; color: #fff; padding: 12px 22px; border-radius: 10px; font-weight: 700; font-size: .88rem; box-shadow: 0 6px 20px rgba(0,188,212,.4); opacity: 0; transition: opacity .3s; z-index: 9999; 
}
.amenity-check {
  cursor: pointer;
  font-size: .85rem;
  font-weight: 600;
  color: #444;
  display: flex;
  align-items: center;
}

.amenity-check input[type="checkbox"] {
  margin-right: 8px;
  accent-color: #00bcd4;
}

.amenity-check input[type="checkbox"]:checked + span {
  color: #00bcd4;
  font-weight: 700;
}
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:#1a202c;">Hostel Profile</h2>
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0" style="font-size:.8rem;">
      <li class="breadcrumb-item"><a href="index.php" style="color:#00bcd4;">Dashboard</a></li>
      <li class="breadcrumb-item active">Hostel Profile</li>
    </ol></nav>
  </div>
</div>

<div class="row g-4">
  <!-- LEFT FORM -->
  <div class="col-lg-8">
    <div class="card form-card">
      <div class="card-head">
        <h6><i class="fas fa-edit me-2" style="color:#00bcd4;"></i>Edit Hostel Details</h6>
        <span style="font-size:.75rem;color:#888;background:#f8f9fa;padding:3px 10px;border-radius:20px;border:1px solid #e9ecef;">Last updated: <?php echo date('M d, Y'); ?></span>
      </div>
      <div class="p-4">
        <form method="POST" action="../../Backend/backend.php" enctype="multipart/form-data" >
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label-custom">Hostel Name <span style="color:#dc3545;">*</span></label>
             <?php echo  "<input type='text' class='input-custom' name='hostel_name' value='".htmlspecialchars($_SESSION['hostel_name'] ?? '')."' placeholder='Enter Hostel Name'/> ";?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">City <span style="color:#dc3545;">*</span></label>
             <?php echo "<input type='text' class='input-custom' name='city' value='" . htmlspecialchars($_SESSION['hostel_city'] ?? '') . "' placeholder='Enter city'/>";?>
            </div>
            <div class="col-12">
              <label class="form-label-custom">Full Address <span style="color:#dc3545;">*</span></label>
              <?php echo "<input type='text' class='input-custom' name='address' value='" . htmlspecialchars($_SESSION['hostel_address'] ?? '') . "' placeholder='Enter Full Address'/>";?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Contact Number</label>
              <?php echo "<input type='text' class='input-custom' name='contact' value='".htmlspecialchars($_SESSION['hostel_contact'] ?? '')."' placeholder='+92 XXX XXXXXXX'/>";?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Email</label>
              <?php echo "<input type='email' class='input-custom' name='email' value='" . htmlspecialchars($_SESSION['owner_email'] ?? 'usereamil@gmial.com') . "' placeholder='hostel@email.com'/>";?>
            </div>
            <div class="col-md-6">
              <label class="form-label-custom">Hostel Type</label>
              <select class="input-custom" name="hostel_type">
                <option value="boys">Boys Hostel</option>
                <option value="girls">Girls Hostel</option>
                <option value="both">Co-ed Hostel</option>
              </select>
            </div>
            <div class="col-md-6">
             <label class="form-label-custom">Total Capacity</label>
             <?php echo " <input type='number' class='input-custom' name='capacity' value='".htmlspecialchars($_SESSION['hostel_capacity'] ?? '0')."' placeholder='Number of students'/>";?>
            </div>
            <div class="col-12">
              <label class="form-label-custom">Description</label>
              <textarea class="input-custom" name="description" placeholder="Describe your hostel...">Abbas Hostel is a well-managed student accommodation in Karachi. We offer clean rooms, high-speed WiFi, meals, laundry, and 24/7 security.</textarea>
            </div>
            
            <div class="col-12">
            <label class="form-label-custom">Front Image</label>
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">

              <!-- Current image preview -->
              <?php if (!empty($_SESSION['hostel_img'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['hostel_img']); ?>"
                    id="frontImgPreview"
                    style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:2px solid #00bcd4;" />
              <?php else: ?>
                <div id="frontImgPreview"
                    style="width:100px;height:100px;border-radius:10px;border:2px dashed #dee2e6;
                            display:flex;align-items:center;justify-content:center;background:#fafcff;">
                  <i class="fas fa-image" style="font-size:1.8rem;color:#ccc;"></i>
                </div>
              <?php endif; ?>

              <!-- Upload button -->
              <div>
                <button type="button" class="btn-save"
                        style="background:#f8f9fa;color:#444;border:1.5px solid #dee2e6;font-weight:600;"
                        onclick="document.getElementById('frontImgInput').click()">
                  <i class="fas fa-upload me-2" style="color:#00bcd4;"></i>Choose Image
                </button>
                <input type="file" id="frontImgInput" name="hostel_img" accept="image/*"
                      style="display:none;" onchange="previewFront(event)" />
                <p class="mb-0 mt-1" style="font-size:.75rem;color:#888;">JPG, PNG — shown as hostel cover photo</p>
              </div>
            </div>
          </div>

          <!-- Amenities -->
          <div class="col-12">
            <label class="form-label-custom">Amenities</label>
            <div class="d-flex flex-wrap gap-3 mt-1">
              <?php
              $amenities = ['WiFi','Meals','Laundry','AC Rooms','Security','Generator','CCTV','Parking','Study Room','Gym'];
              $checked = isset($_SESSION['hostel_amenities']) ? explode(',', $_SESSION['hostel_amenities']) : [];
              foreach($amenities as $amenity):
              ?>
              <label class="amenity-check">
                <input type="checkbox" name="amenities[]" value="<?php echo $amenity; ?>" <?php echo in_array($amenity, $checked) ? 'checked' : ''; ?>/>
                <span><?php echo $amenity; ?></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
          </div>
          <div class="d-flex gap-3 mt-4">
            <button type="submit" name="update_owner_profile" class="btn-save"><i class="fas fa-save me-2"></i>Save Changes</button>     
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- RIGHT PREVIEW -->
<div class="col-lg-4">
    <div class="card form-card">
        <?php
          $hostel_name     = $_SESSION['hostel_name']     ?? 'N/A';
          $hostel_type     = $_SESSION['hostel_type']     ?? 'N/A';
          $hostel_address  = $_SESSION['hostel_address']  ?? 'N/A';
          $hostel_city     = $_SESSION['hostel_city']     ?? 'N/A';
          $hostel_contact  = $_SESSION['hostel_contact']  ?? 'N/A';
          $hostel_capacity = $_SESSION['hostel_capacity'] ?? 'N/A';
        ?>

        <div class='card-head'><h6><i class='fas fa-eye me-2' style='color:#00bcd4;'></i>Profile Preview</h6></div>
        <div class='profile-preview'>
            <div class='hostel-icon'><i class='fas fa-building'></i></div>
            <h5 class='fw-bold mb-0' style='color:#1a202c;'><?php echo htmlspecialchars($hostel_name); ?></h5>
            <p style='color:#00bcd4;font-weight:600;font-size:.85rem;margin:4px 0 6px;'><?php echo htmlspecialchars(strtoupper($hostel_type)); ?></p>
            <p style='font-size:.83rem;color:#888;'><?php echo htmlspecialchars($hostel_address); ?></p>
            <div class='text-start mt-3'>
                <div class='meta-row'><div class='meta-icon'><i class='fas fa-map-marker-alt'></i></div><div><div class='meta-label'><?php echo htmlspecialchars($hostel_city); ?></div><div class='meta-val'><?php echo htmlspecialchars($hostel_address); ?></div></div></div>
                <div class='meta-row'><div class='meta-icon'><i class='fas fa-phone'></i></div><div><div class='meta-label'>Contact</div><div class='meta-val'><?php echo htmlspecialchars($hostel_contact); ?></div></div></div>
                <div class='meta-row'><div class='meta-icon'><i class='fas fa-users'></i></div><div><div class='meta-label'>Capacity</div><div class='meta-val'><?php echo htmlspecialchars($hostel_capacity); ?></div></div></div>
                <div class='meta-row'><div class='meta-icon'><i class='fas fa-star'></i></div><div><div class='meta-label'>Status</div><div class='meta-val' style='color:#28a745;'>Active & Verified</div></div></div>
            </div>
        </div>
      </div>
      </div>
    </div>
    
<!-- Toast -->
<div class="toast-msg" id="toast"><i class="fas fa-check-circle me-2"></i>Profile saved successfully!</div>

<?php if(isset($_POST['save_profile'])): ?>
<script>
  const t = document.getElementById('toast');
  t.style.opacity = '1';
  setTimeout(() => t.style.opacity = '0', 3000);
</script>
<?php endif; ?>

<script>
function previewImages(e){
  const grid = document.getElementById('imgGrid');
  Array.from(e.target.files).forEach(file => {
    const reader = new FileReader();
    reader.onload = ev => {
      const div = document.createElement('div');
      div.className = 'img-item';
      div.innerHTML = `<img src="${ev.target.result}" alt=""/><button class="rm" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
      grid.appendChild(div);
    };
    reader.readAsDataURL(file);
  });
}
function previewFront(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = ev => {
    const preview = document.getElementById('frontImgPreview');
    // Replace placeholder div or existing img with an img tag
    if (preview.tagName === 'DIV') {
      const img = document.createElement('img');
      img.id = 'frontImgPreview';
      img.style.cssText = 'width:100px;height:100px;object-fit:cover;border-radius:10px;border:2px solid #00bcd4;';
      preview.replaceWith(img);
      img.src = ev.target.result;
    } else {
      preview.src = ev.target.result;
    }
  };
  reader.readAsDataURL(file);
}
</script>

<?php include("footer.php"); ?>