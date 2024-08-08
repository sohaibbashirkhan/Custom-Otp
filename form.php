<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'volunteer_form_data';

    // Sanitize and fetch form inputs
    $FullName = sanitize_text_field($_POST['full_name']);
    $Gender = sanitize_text_field($_POST['gender']);
    $DateofBirth = sanitize_text_field($_POST['dob']);
    $Address = sanitize_text_field($_POST['address']);
    $City = sanitize_text_field($_POST['city']);
    $State = sanitize_text_field($_POST['state']);
    $Zip = sanitize_text_field($_POST['zip']);
    $Country = sanitize_text_field($_POST['country']);
    $Phone = sanitize_text_field($_POST['phone']);
    $Email = sanitize_email($_POST['email']);
    $DaysAvailable = implode(", ", array_map('sanitize_text_field', $_POST['days_available']));
    $TimesAvailable = sanitize_text_field($_POST['times_available']);
    $SkillsExperience = sanitize_textarea_field($_POST['skills_experience']);
    $VolunteerInterests = implode(", ", array_map('sanitize_text_field', $_POST['volunteer_interests']));
    $EmergencyContactName = sanitize_text_field($_POST['emergency_contact_name']);
    $Relationship = sanitize_text_field($_POST['relationship']);
    $EmergencyPhone = sanitize_text_field($_POST['emergency_phone']);
    $EmergencyEmail = sanitize_email($_POST['emergency_email']);
    $Reference1Name = sanitize_text_field($_POST['reference1_name']);
    $Reference1Relationship = sanitize_text_field($_POST['reference1_relationship']);
    $Reference1Phone = sanitize_text_field($_POST['reference1_phone']);
    $Reference1Email = sanitize_email($_POST['reference1_email']);
    $Reference2Name = sanitize_text_field($_POST['reference2_name']);
    $Reference2Relationship = sanitize_text_field($_POST['reference2_relationship']);
    $Reference2Phone = sanitize_text_field($_POST['reference2_phone']);
    $Reference2Email = sanitize_email($_POST['reference2_email']);
    $AdditionalInfo = sanitize_textarea_field($_POST['additional_info']);
    // $Signature = sanitize_text_field($_POST['signature']);
    // $Date = sanitize_text_field($_POST['date']);

    $insert_result = $wpdb->insert($table_name, array(
        'full_name' => $FullName,
        'gender' => $Gender,
        'dob' => $DateofBirth,
        'address' => $Address,
        'city' => $City,
        'state' => $State,
        'zip' => $Zip,
        'country' => $Country,
        'phone' => $Phone,
        'email' => $Email,
        'days_available' => $DaysAvailable,
        'times_available' => $TimesAvailable,
        'skills_experience' => $SkillsExperience,
        'volunteer_interests' => $VolunteerInterests,
        'emergency_contact_name' => $EmergencyContactName,
        'relationship' => $Relationship,
        'emergency_phone' => $EmergencyPhone,
        'emergency_email' => $EmergencyEmail,
        'reference1_name' => $Reference1Name,
        'reference1_relationship' => $Reference1Relationship,
        'reference1_phone' => $Reference1Phone,
        'reference1_email' => $Reference1Email,
        'reference2_name' => $Reference2Name,
        'reference2_relationship' => $Reference2Relationship,
        'reference2_phone' => $Reference2Phone,
        'reference2_email' => $Reference2Email,
        'additional_info' => $AdditionalInfo,
        // 'signature' => $Signature,
        // 'date' => $Date
    ));

    // Check if the insertion was successful
    if ($insert_result !== false) {
        // Data inserted successfully, now try to send OTP
        $otp = mt_rand(100000, 999999);

        // Store OTP and form data in session
        if (!isset($_SESSION['otps'])) {
            $_SESSION['otps'] = array();
        }
        $_SESSION['otps'][] = array(
            'otp' => $otp,
            'form_data' => $_POST
        );

        // Send OTP via email
        $to = $Email;
        $subject = "OTP for Form Submission";
        $message = "Your OTP is: " . $otp;
        $headers = "From: admin@yourdomain.com";

        if (wp_mail($to, $subject, $message, $headers)) {
            // Redirect to OTP verification page
            wp_redirect(site_url('/otp-verification'));
            exit();
        } else {
            echo "Failed to send OTP. Please try again.";
        }
    } else {
        echo "Failed to insert data into the database. Error: " . $wpdb->last_error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Application Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <!-- Form fields here -->
    <h2>Volunteer Application Form - Lady Bug Ministry</h2>
    <h3>Personal Information:</h3>
    <div class="mb-3">
    <label for="full_name" >Full Name:</label>
    <input type="text" name="full_name" class="form-control" id="full_name" placeholder="Full Name" required><br>
    </div>
    <div class="mb-3">
   <label for="gender">Gender:</label>
<select name="gender" id="gender" class="form-control">
    <option value="">Select Gender</option>
    <option value="male">Male</option>
    <option value="female">Female</option>
</select><br>
</div>
<div class="mb-3">
    <label for="dob">Date of Birth:</label>
    <input type="date" name="dob" id="dob" class="form-control" required ><br>
    </div>
    <div class="mb-3">
    <label for="address">Address:</label>
    <input type="text" name="address" id="address" placeholder="Address" class="form-control" required><br>
    </div>
    <div class="mb-3">
    <label for="city">City:</label>
    <input type="text" name="city" id="city" placeholder="City" class="form-control" required><br>
</div>
<div class="mb-3">
    <label for="state">State/Province:</label>
    <input type="text" name="state" id="state" placeholder="State/Province" class="form-control" required><br>
    </div>
    <div class="mb-3">
    <label for="zip">Zip/Postal Code:</label>
    <input type="text" name="zip" id="zip" placeholder="Zip/Postal Code" class="form-control" required><br>
    </div>
    <div class="mb-3">
    <label for="country">Country:</label>
    <input type="text" name="country" id="country" placeholder="Country" class="form-control" required><br>
    </div>
    <div class="mb-3">
    <label for="phone">Phone Number:</label>
    <input type="tel" name="phone" id="phone" placeholder="Phone Number" class="form-control" required><br>
    </div>
    <div class="mb-3">
    <label for="email">Email Address:</label>
    <input type="email" name="email" id="email" placeholder="Email Address" class="form-control" required><br>
    </div>

    <h3>Availability:</h3>
    <div class="form-check">
    <label for="days_available">Days Available:</label><br>
    <input type="checkbox" name="days_available[]" value="Monday" class="form-check-input"> Monday<br>
    <input type="checkbox" name="days_available[]" value="Tuesday" class="form-check-input" > Tuesday<br>
    <input type="checkbox" name="days_available[]" value="Wednesday" class="form-check-input"> Wednesday<br>
    <input type="checkbox" name="days_available[]" value="Thursday" class="form-check-input"> Thursday<br>
    <input type="checkbox" name="days_available[]" value="Friday" class="form-check-input"> Friday<br>
    <input type="checkbox" name="days_available[]" value="Saturday" class="form-check-input"> Saturday<br>
    <input type="checkbox" name="days_available[]" value="Sunday" class="form-check-input"> Sunday<br>
    </div>
    <div class="mb-3">
    <label for="times_available">Times Available:</label><br>
    <input name="times_available" type="time" id="times_available" placeholder="Times Available" class="form-control"><br>
    </div>
    <div class="mb-3">
    <h3>Skills and Experience:</h3>
    <label for="skills_experience">Please describe any relevant skills or experience you have that would be beneficial to Lady Bug Ministry:</label><br>
    <textarea name="skills_experience" id="skills_experience" placeholder="Skills and Experience" class="form-control"></textarea><br>
    </div>
    <h3>Volunteer Interests:</h3>
    <div class="form-check">
    <label for="volunteer_interests">What areas of volunteering are you most interested in?</label><br>
    <input type="checkbox" name="volunteer_interests[]" value="fundraising" class="form-check-input"> Fundraising<br>
    <input type="checkbox" name="volunteer_interests[]" value="event_planning" class="form-check-input"> Event Planning<br>
    <input type="checkbox" name="volunteer_interests[]" value="mentoring" class="form-check-input"> Mentoring<br>
    <input type="checkbox" name="volunteer_interests[]" value="administrative_support" class="form-check-input"> Administrative Support<br>
    <input type="checkbox" name="volunteer_interests[]" value="donor_relations" class="form-check-input"> Donor Relations<br>
    <input type="checkbox" name="volunteer_interests[]" value="grant_writing" class="form-check-input"> Grant Writing<br>
    <input type="checkbox" name="volunteer_interests[]" value="individual_giving_campaigns" class="form-check-input"> Individual Giving Campaigns<br>
    <input type="checkbox" name="volunteer_interests[]" value="corporate_partnerships" class="form-check-input"> Corporate Partnerships<br>
    <input type="checkbox" name="volunteer_interests[]" value="online_fundraising" class="form-check-input"> Online Fundraising<br>
    <input type="checkbox" name="volunteer_interests[]" value="fundraising_events_coordination" class="form-check-input"> Fundraising Events Coordination<br>
    <input type="checkbox" name="volunteer_interests[]" value="community_engagement" class="form-check-input"> Community Engagement<br>
    <input type="checkbox" name="volunteer_interests[]" value="training_capacity_building" class="form-check-input"> Training and Capacity Building<br>
    </div>
    <h3>Emergency Contact:</h3>
    <div class="mb-3">
    <label for="emergency_contact_name">Full Name:</label>
    <input type="text" class="form-control" name="emergency_contact_name" id="emergency_contact_name" placeholder="Full Name" required><br>
</div>
<div class="mb-3">
    <label for="relationship">Relationship:</label>
    <input type="text" class="form-control" name="relationship" id="relationship" placeholder="Relationship" required><br>
    </div>
    <div class="mb-3">
    <label for="emergency_phone">Phone Number:</label>
    <input type="tel" class="form-control" name="emergency_phone" id="emergency_phone" placeholder="Phone Number" required><br>
    </div>
    <div class="mb-3">
    <label for="emergency_email">Email Address:</label>
    <input type="email" class="form-control" name="emergency_email" id="emergency_email" placeholder="Email Address" required><br>
    </div>
    <h3>References:</h3>
    <div class="mb-3">
    <label for="reference1_name">Name:</label>
    <input type="text" class="form-control" name="reference1_name" id="reference1_name" placeholder="Name" required><br>
    </div>
    <div class="mb-3">
    <label for="reference1_relationship">Relationship:</label>
    <input type="text" class="form-control" name="reference1_relationship" id="reference1_relationship" placeholder="Relationship" required><br>
    </div>
    <div class="mb-3">
    <label for="reference1_phone">Phone Number:</label>
    <input type="tel" class="form-control" name="reference1_phone" id="reference1_phone" placeholder="Phone Number" required><br>
    </div>
    <div class="mb-3">
    <label for="reference1_email">Email Address:</label>
    <input type="email" class="form-control" name="reference1_email" id="reference1_email" placeholder="Email Address" required><br>
    </div>
    <div class="mb-3">
    <label for="reference2_name">Name:</label>
    <input type="text" class="form-control" name="reference2_name" id="reference2_name" placeholder="Name" required><br>
    </div>
    <div class="mb-3">
    <label for="reference2_relationship">Relationship:</label>
    <input type="text" class="form-control" name="reference2_relationship" id="reference2_relationship" placeholder="Relationship" required><br>
    </div>
    <div class="mb-3">
    <label for="reference2_phone">Phone Number:</label>
    <input type="tel" class="form-control" name="reference2_phone" id="reference2_phone" placeholder="Phone Number" required><br>
    </div>
    <div class="mb-3">
    <label for="reference2_email">Email Address:</label>
    <input type="email" class="form-control" name="reference2_email" id="reference2_email" placeholder="Email Address" required><br>
    
    <h3>Additional Information:</h3>
    <div class="mb-3">
    <label for="additional_info">Is there anything else you would like us to know?</label><br>
    <textarea name="additional_info" class="form-control" id="additional_info" placeholder="Additional Information"></textarea><br>
    </div>
    <h5>I accept to submit this form to Lady Bug Ministry as a volunteer, agreeing to the terms & conditions.</h5>
    <button type="submit" name="submit">Submit</button>
    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
