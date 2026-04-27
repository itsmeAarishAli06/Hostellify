<?php 
    require_once "../config.php";
    // $conn = new mysqli("localhost","root","","hostel");
    include 'header.php';     
    if (!(isset($_SESSION['islogin']) && $_SESSION['islogin'])) {
        header("location:login.php");
    }
    // Find the hostel ID 
    $user_id = $_SESSION['user_id'];
    // Find the hostel ID
    $stmt0 = $conn->prepare("SELECT hostel_id from booking where student_id = ?");
    $stmt0->bind_param("i", $user_id);
    $stmt0->execute();
    $row = $stmt0->get_result();
    $data = $row->fetch_assoc(); 
    $hostel_id = $data['hostel_id'] ?? null;

    $stmt1 = $conn->prepare("SELECT * FROM complaints WHERE student_id = ? AND hostel_id = ? ");
    $stmt1->bind_param("ii",$user_id,$hostel_id);
    $stmt1->execute();     
    $row = $stmt1->get_result();
    

?>
<style>
select[name="priority"] {
    padding: 10px 15px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
}

select[name="priority"]:focus {
    border-color: #0052CC;
    outline: none;
}
</style>

        <!-- ====================================================
             COMPLAINTS
        ==================================================== -->
        <section class="section active" id="complaints">

        <form action = "../Backend/backend.php"  method = "post">
            <div class="section-header">
                <div class="section-label">REPORT &amp; TRACK</div>
                <h2 class="section-title">Complaints</h2>
                <p class="section-description">Submit and track your complaints</p>
            </div>

            <div class="form-section">
                <h3 style="color: var(--text-dark); margin-bottom: 25px; font-weight: 700;">Submit New Complaint</h3>
                <div class="form-group">
                    <label class="form-label">Complaint Title</label>
                    <input type="text" class="form-input" name = "title" placeholder="Enter complaint title...">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" name = "message" placeholder="Describe your issue..."></textarea>
                    <select name="priority" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <button class="form-button" name = "conplaint_sub_btn" <?php if (!(isset($_SESSION['success']) && $_SESSION['success'])) {
                  echo "onclick='alert('✅ Complaint submitted successfully!')'";
                } ?>>Submit Complaint</button>
            </div>
        </form>    
            <div class="form-section">
                <h3 style="color: var(--text-dark); margin-bottom: 25px; font-weight: 700;">Complaint History</h3>
                <div class="table-wrapper">
                 
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th> 
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        
                        if($row->num_rows === 0){
                            echo "<h3 style='text-align: center;
                            padding: 40px 20px;
                            background: #f8f9fa;
                            border-radius: 8px;';>
                            First you have to Book a hostel <h3>";
                        }

                        while($data = $row->fetch_assoc()){
                    
                        $c_created_at = $data['created_at'] ?? null;
                        $c_created_at = (new DateTime($c_created_at))->format('d-m-y');    
                        echo"    
                            <tr>
                                <td>".$data['subject']."</td>
                                <td>".$data['message']."</td>
                                <td>".$c_created_at."</td>
                                <td><span class='badge warning'>".$data['status']."</span></td>
                                <td>".strtoupper($data['level']).   "</td>
                            </tr>
                        "; }
                        ?>
                        </tbody>
                        </table>
                </div>
            </div>
        </section>

<?php include 'footer.php'; ?>
