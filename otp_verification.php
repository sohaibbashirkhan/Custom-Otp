
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<form method="post">
<div class="mb-3">
    <input type="text" class="form-control" name="otp" placeholder="Search Your Data " required>
    <br>
    </div>
    <button type="submit" name="verify">Verify </button>
</form>
</body>
</html>
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    if (isset($_SESSION['otps']) && !empty($_SESSION['otps'])) {
        $user_otp = sanitize_text_field($_POST['otp']);
        $otp_found = false;
        foreach ($_SESSION['otps'] as $key => $otp_data) {
            $stored_otp = $otp_data['otp'];
  if ($user_otp == $stored_otp) {
                $form_data = $otp_data['form_data'];
                // echo "<h2>Yours Data:</h2>";
echo '<table class="table">';
echo '<thead>';
echo '<tr>';
echo '<th scope="col">#</th>';
echo '<th scope="col">Key</th>';
echo '<th scope="col">Value</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$index = 1;
foreach ($form_data as $key => $value) {
    echo '<tr>';
    echo '<th scope="row">' . $index . '</th>';
    echo '<td>' . esc_html(strtoupper($key)) . '</td>'; // Convert key to uppercase
    echo '<td>' . esc_html($value) . '</td>';
    echo '</tr>';
    $index++;
}

echo '</tbody>';
echo '</table>';

                $otp_found = true;
                unset($_SESSION['otps'][$key]);
                break;
            }
        }
        if (!$otp_found) {
            echo "Invalid OTP. Please try again.";
        }
    } else {
        echo "No OTPs available. Please request a new OTP.";
    }
}
?>

