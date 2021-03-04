<?php

//Aaron Morrissey c00239014

//References
//https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
//https://www.youtube.com/watch?v=LC9GaXkdxF8
//https://www.youtube.com/watch?v=aIsu9SPcGbU
//https://www.youtube.com/watch?v=aIsu9SPcGbU



require_once "Connect.php";
$cipher = 'AES-128-CBC';
$key = 'thebestsecretkey';

$firstname = $secondname = $dob = $Gen = $DC = $MCS = $EM = $Q1 = $Q2 = $Q3 = $username = $password = $confirm_password = "";
$usernamechecker = $passwordchecker = $confirmpasswordchecker = "";


if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $firstname = ($_POST["firstname"]);
    $secondname = ($_POST["secondname"]);
    $dob = ($_POST["dob"]);
    $Gen = ($_POST["Gen"]);
    $DC = ($_POST["DC"]);
    $MCS = ($_POST["MCS"]);
    $EM = ($_POST["EM"]);
    $Q1 = ($_POST["Q1"]);
    $Q2 = ($_POST["Q2"]);
    $Q3 = ($_POST["Q3"]);

    $iv = random_bytes(16);

    $escaped_FN = $conn -> real_escape_string($_POST['firstname']);
    $encrypted_FN = openssl_encrypt($escaped_FN, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_SN = $conn -> real_escape_string($_POST['secondname']);
    $encrypted_SN = openssl_encrypt($escaped_SN, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_dob = $conn -> real_escape_string($_POST['dob']);
    $encrypted_dob = openssl_encrypt($escaped_dob, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_Gen = $conn -> real_escape_string($_POST['Gen']);
    $encrypted_Gen = openssl_encrypt($escaped_Gen, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_DC = $conn -> real_escape_string($_POST['DC']);
    $encrypted_DC = openssl_encrypt($escaped_DC, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_MCS = $conn -> real_escape_string($_POST['MCS']);
    $encrypted_MCS = openssl_encrypt($escaped_MCS, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_EM = $conn -> real_escape_string($_POST['EM']);
    $encrypted_EM = openssl_encrypt($escaped_EM, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_Q1 = $conn -> real_escape_string($_POST['Q1']);
    $encrypted_Q1 = openssl_encrypt($escaped_Q1, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_Q2 = $conn -> real_escape_string($_POST['Q1']);
    $encrypted_Q2 = openssl_encrypt($escaped_Q2, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);

    $escaped_Q3 = $conn -> real_escape_string($_POST['Q3']);
    $encrypted_Q3 = openssl_encrypt($escaped_Q3, $cipher, $key,
    OPENSSL_RAW_DATA, $iv);


    $iv_hex = bin2hex($iv);
    $FN_hex = bin2hex($encrypted_FN);
    $SN_hex = bin2hex($encrypted_SN);
    $dob_hex = bin2hex($encrypted_dob);
    $Gen_hex = bin2hex($encrypted_Gen);
    $DC_hex = bin2hex($encrypted_DC);
    $MCS_hex = bin2hex($encrypted_MCS);
    $EM_hex = bin2hex($encrypted_EM);
    $Q1_hex = bin2hex($encrypted_Q1);
    $Q2_hex = bin2hex($encrypted_Q2);
    $Q3_hex = bin2hex($encrypted_Q3);


    if(empty(($_POST["username"])))
    {
        $usernamechecker = "Please enter a username.";
    }

    else
    {
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($conn, $sql)){

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = ($_POST["username"]);

            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $usernamechecker = "This username is already taken.";
                }
                else
                {
                    $username = ($_POST["username"]);
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    if(empty(($_POST["password"]))){
        $passwordchecker = "Please enter a password.";
    }
    else if(strlen(($_POST["password"])) < 6)
    {
        $passwordchecker = "Password must have atleast 6 characters.";
    } else{
        $password = ($_POST["password"]);
    }

    if(empty(($_POST["confirm_password"])))
    {
        $confirmpasswordchecker = "Please confirm password.";
    }
    else
    {
        $confirm_password = ($_POST["confirm_password"]);
        if(empty($passwordchecker) && ($password != $confirm_password))
        {
            $confirmpasswordchecker = "Password did not match.";
        }
    }

    if(empty($usernamechecker) && empty($passwordchecker) && empty($confirmpasswordchecker))
    {

        $sql = "INSERT INTO users (iv, firstname, secondname, dob, Gender, DOC, MedCardStat, Email, Q1, Q2, Q3, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($conn, $sql))
        {
            mysqli_stmt_bind_param($stmt, "sssssssssssss", $param_iv, $param_firstname, $param_secondname, $param_dob, $param_Gen, $param_DC, $param_MCS, $param_EM, $param_Q1, $param_Q2, $param_Q3, $param_username, $param_password);

            $param_iv = $iv_hex;
            $param_firstname = $FN_hex;
            $param_secondname = $SN_hex;
            $param_dob = $dob_hex;
            $param_Gen = $Gen_hex;
            $param_DC = $DC_hex;
            $param_MCS = $MCS_hex;
            $param_EM = $EM_hex;
            $param_Q1 = $Q1_hex;
            $param_Q2 = $Q2_hex;
            $param_Q3 = $Q3_hex;
            $param_password = password_hash($password, PASSWORD_DEFAULT);


            if(mysqli_stmt_execute($stmt))
            {
                header("location: login.php");
            }
            else
            {
                echo "Error has emerged";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>

<html>
<head>

    <title>Registration Form</title>

</head>
<body>
    <div>
        <h2>Registration Form</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


          <div>
              <label>Firstname:</label>
              <input type="text" name="firstname"  value="<?php echo $firstname; ?>"  required>
          </div>
          <br>
          <div>
              <label>Secondname:</label>
              <input type="text" name="secondname"  value="<?php echo $secondname; ?>"  required>
          </div>
          <br>
          <div >
              <label>Date of Birth:</label>
              <input type="date" name="dob"  value="<?php echo $dob; ?>"  required>
          </div>
          <br>
          <div >
              <label>Gender</label>
              <input type="radio" name="Gen"  value="M<?php echo $Gen; ?>"  required>Male
              <input type="radio" name="Gen"  value="F<?php echo $Gen; ?>"  required>Female
          </div>
          <br>
          <div>
              <label>Name of personal Physician:</label>
                <input type="text" name="DC" placeholder="Dr.example"  value="<?php echo $DC; ?>" required>
          </div>
          <br>
          <div>
              <label>Medicalcardstatus:</label>
              <input type="radio" name="MCS"   value="Y<?php echo $MCS; ?>" required>Active
              <input type="radio" name="MCS"  value="N<?php echo $MCS; ?>" required>Inactive
          </div>
          <br>
          <div>
              <label>Email:</label>
                <input type="email" name="EM" value="<?php echo $EM; ?>" required>
          </div>
          <br>
          <div>
              <label>Question 1:</label>
              <br>
              <p>
                 Have you visited a country outside of Ireland recently?
              </p>
              <input type="radio" name="Q1"  value="Y<?php echo $Q1; ?>" required>Yes
              <input type="radio" name="Q1"  value="N<?php echo $Q1; ?>" required>No
          </div>
          <br>
          <div>
              <label>Question 2:</label>
              <br>
              <p>
                Are you suffering from any flu like symptoms?
              </p>
              <input type="radio" name="Q2"  value="Y<?php echo $Q2; ?>" required>Yes
              <input type="radio" name="Q2"  value="N<?php echo $Q2; ?>" required>No
          </div>
          <br>
          <div >
              <label>Question 3:</label>
              <br>
              <p>
                   Are you experiencing any fever/temperature symptoms:
              </p>
              <input type="radio" name="Q3"  value="Y<?php echo $Q3; ?>" required>Yes
              <input type="radio" name="Q3"  value="N<?php echo $Q3; ?>" required>No
          </div>
          <br>
            <div <?php echo (!empty($usernamechecker)) ? 'has-error' : ''; ?>>
                <label>Username</label>
                <input type="text" name="username"  value="<?php echo $username; ?>">
                <span><?php echo $usernamechecker; ?></span>
            </div>
            <br>

            <div  <?php echo (!empty($passwordchecker)) ? 'has-error' : ''; ?>>
                <label>Password</label>
                <input type="password" name="password"  value="<?php echo $password; ?>" required>
                <span><?php echo $passwordchecker; ?></span>
            </div>
            <br>

            <div <?php echo (!empty($confirmpasswordchecker)) ? 'has-error' : ''; ?>>
                <label>Confirm Password</label>
                <input type="password" name="confirm_password"  value="<?php echo $confirm_password; ?>" required>
                <span><?php echo $confirmpasswordchecker; ?></span>
            </div>

            <br>
            <div >
                <input type="submit" value="Submit">
            </div>


            <p>If you already have an existing account then <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
