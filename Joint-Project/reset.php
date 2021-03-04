<?php

//Aaron Morrissey c00239014

//References
//https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
//https://www.youtube.com/watch?v=LC9GaXkdxF8
//https://www.youtube.com/watch?v=aIsu9SPcGbU
//https://www.youtube.com/watch?v=aIsu9SPcGbU



session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: login.php");
    exit;
}

require_once "Connect.php";

$npassword = $cpassword = "";
$npasswordchecker = $cpasswordchecker = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

    // Validate new password
    if(empty(($_POST["npassword"])))
    {
        $npasswordchecker = "Enter the new password.";
    }
    else if(strlen(($_POST["npassword"])) < 6)
    {
        $npasswordchecker = "Password must have atleast 6 characters.";
    }
    else
    {
        $npassword = ($_POST["npassword"]);
    }

    if(empty(($_POST["cpassword"])))
    {
        $cpasswordchecker = "Please confirm the password.";
    }
    else
    {
        $cpassword = ($_POST["cpassword"]);
        if(empty($npasswordchecker) && ($npassword != $cpassword))
        {
            $cpasswordchecker = "Password did not match.";
        }
    }

    if(empty($npasswordchecker) && empty($cpasswordchecker))
    {
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        if($stmt = mysqli_prepare($conn, $sql))
        {
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            $param_password = password_hash($npassword, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            if(mysqli_stmt_execute($stmt))
            {
                session_destroy();
                header("location: login.php");
                exit();
            }
            else
            {
                echo "Error has been detected";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>

<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <div >
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div <?php echo (!empty($npasswordchecker)) ? 'has-error' : ''; ?>>

                <label>New Password</label>
                <input type="password" name="npassword" value="<?php echo $npassword; ?>">
                <span ><?php echo $npasswordchecker; ?></span>

            </div>
            <br>
            <div <?php echo (!empty($cpasswordchecker)) ? 'has-error' : ''; ?>>
                <label>Confirm Password</label>
                <input type="password" name="cpassword">
                <span><?php echo $cpasswordchecker; ?></span>
            </div>
            <br>
            <div>
                <input type="submit" value="Submit">
                <a href="welcome.php">Return to signin</a>
            </div>
        </form>
    </div>
</body>
</html>
