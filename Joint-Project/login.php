
<?php

//Aaron Morrissey c00239014

//References
//https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
//https://www.youtube.com/watch?v=LC9GaXkdxF8
//https://www.youtube.com/watch?v=aIsu9SPcGbU
//https://www.youtube.com/watch?v=aIsu9SPcGbU


session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

require_once "Connect.php";

$username = $password = "";
$usernamechecker = $passwordchecker = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

    if(empty(($_POST["username"])))
    {
        $usernamechecker = "Please enter username.";
    }
    else
    {
        $username = ($_POST["username"]);
    }

    if(empty(($_POST["password"])))
    {
        $passwordchecker = "Please enter your password.";
    }
    else
    {
        $password = ($_POST["password"]);
    }

    if(empty($usernamechecker) && empty($passwordchecker))
    {

        $sql = "SELECT id, iv, firstname, secondname, dob, Gender, DOC, MedCardStat, Email, Q1, Q2, Q3, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($conn, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    mysqli_stmt_bind_result($stmt, $id, $iv, $firstname, $secondname, $dob, $Gen, $DC, $MCS, $EM, $Q1, $Q2, $Q3, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password))
                        {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["iv"] = $iv;
                            $_SESSION["firstname"] = $firstname;
                            $_SESSION["secondname"] = $secondname;
                            $_SESSION["dob"] = $dob;
                            $_SESSION["Gen"] = $Gen;
                            $_SESSION["DC"] = $DC;
                            $_SESSION["MCS"] = $MCS;
                            $_SESSION["EM"] = $EM;
                            $_SESSION["Q1"] = $Q1;
                            $_SESSION["Q2"] = $Q2;
                            $_SESSION["Q3"] = $Q3;
                            $_SESSION["username"] = $username;

                            header("location: welcome.php");
                        }
                         else
                         {
                            $passwordchecker = "The password you entered was not valid.";
                        }
                    }
                }
                else
                {
                    $usernamechecker = "No account found with that username.";
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Login</title>
</head>
<body>
    <div >
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div  <?php echo (!empty($usernamechecker)) ? 'has-error' : ''; ?>>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $usernamechecker; ?></span>
            </div>

            <br>
            <div <?php echo (!empty($passwordchecker)) ? 'has-error' : ''; ?>>
                <label>Password</label>
                <input type="password" name="password">
                <span><?php echo $passwordchecker; ?></span>
            </div>

            <br>
            <div>
                <input type="submit" value="Login">
            </div>

            <p>If you do not have an account <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>
