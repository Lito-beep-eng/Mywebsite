<<<<<<< HEAD
<?php
 include "db.php";
 session_start();
    $username = $_POST['username'];
    $password = $_POST['password'] ?? NULL;
    $sql="SELECT username,password,role FROM users WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();
     if(isset($_POST['submit'])){
       if(password_verify($password,$user['password'])){
         $_SESSION['username'] = $username;
         $_SESSION['role'] = $user['role'];
         header("Location: LandingPage.php");
       }
       else{
         echo "Invalid username or password";
         }
}
=======
<?php
 include "db.php";
 session_start();
    $username = $_POST['username'];
    $password = $_POST['password'] ?? NULL;
    $sql="SELECT username,password,role FROM users WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();
     if(isset($_POST['submit'])){
       if(password_verify($password,$user['password'])){
         $_SESSION['username'] = $username;
         $_SESSION['role'] = $user['role'];
         header("Location: LandingPage.php");
       }
       else{
         echo "Invalid username or password";
         }
}
>>>>>>> 3780024 (update)
?>