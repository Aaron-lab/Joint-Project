<?php

//Aaron Morrissey c00239014

//References
//https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
//https://www.youtube.com/watch?v=LC9GaXkdxF8
//https://www.youtube.com/watch?v=aIsu9SPcGbU
//https://www.youtube.com/watch?v=aIsu9SPcGbU


session_start();
$host = 'localhost';
$user = 'root';
$pass = 'beep';
$db = 'projecttest';

$conn = mysqli_connect($host, $user, $pass, $db);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <div >

      <h1>Hello <?php echo htmlspecialchars($_SESSION["username"]); ?></h1><h2>Below you will find your personal details.</h2>
      <p>
          <a href="reset.php"><button>Reset Your Password</button></a>
          <a href="logout.php"><button>Sign Out of Your Account</button></a>
      </p>

      <?php
      $username = htmlspecialchars($_SESSION["username"]);
      $cipher = 'AES-128-CBC';
      $key = 'thebestsecretkey';

      //$sql = "SELECT id, iv, firstname, secondname, dob, Gender, DOC, MCS, Email, Q1, Q2, Q3 FROM users WHERE username = '$username'";
      $sql = "SELECT id, iv, firstname, secondname, FROM users WHERE username = '$username'";
      $form = $conn->query($sql);

      if($form->num_rows > 0)
      {
        echo '<table><tr><th>ID</th><th>Firstname</th><th>Secondname</th><th>Dateofbirth</th><th>Gender</th><th>Doctor</th><th>Medicalcardstatus</th><th>Email</th><th>Username</th><th>Password</th><th>Question1</th><th>Question2</th><th>Question3</th></tr></table>';
        while($row = $form->fetch_assoc())
        {
          $id = ($_SESSION["id"]);
          $iv = hex2bin($row['iv']);
          $firstname = hex2bin($row['firstname']);
          $secondname = hex2bin($row['secondname']);
          // $dob = hex2bin($row['dob']);
          //$Gen = hex2bin($row['Gen']);
          //$doC = hex2bin($row['DOC']);
          //$MCS = hex2bin($row['MCS']);
          //$EM = hex2bin($row['EM']);
          //$Q1 = hex2bin($row['Q1']);
          //$Q2 = hex2bin($row['Q2']);
          //$Q3 = hex2bin($row['Q3']);

          $unencrypted_FN = openssl_decrypt($firstname, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

          $unencrypted_SN = openssl_decrypt($secondname, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

        /*  $unencrypted_dob = openssl_decrypt($dob, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

          $unencrypted_Gen = openssl_decrypt($Gen, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

          $unencrypted_Dc = openssl_decrypt($DC, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

          $unencrypted_MCS = openssl_decrypt($MCS, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

          $unencrypted_EM = openssl_decrypt($EM, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);


          $unencrypted_QO = openssl_decrypt($Q1, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

          $unencrypted_QT = openssl_decrypt($Q2, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);

          $unencrypted_QTR = openssl_decrypt($Q3, $cipher, $key,
          OPENSSL_RAW_DATA, $iv);*/

          //echo "<table><tr><td>$id</td><td>$unencrypted_FN</td><td>$unencrypted_SN</td><td>$unencrypted_dob</td><td>$unencrypted_Gen</td><td>$unencrypted_Dc</td><td>$unencrypted_MCS</td><td>$unencrypted_EM</td><td>$unencrypted_QO</td><td>$unencrypted_QT</td><td>$unencrypted_QTR</td></table>";
          echo "<table><tr><td>$id</td><td>$unencrypted_FN</td><td>$unencrypted_SN</td></tr></table>";

        }
      }
       ?>
    </div>
    <br>


</body>
</html>
