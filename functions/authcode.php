<?php
session_start();
include('../config/db.php');
include('../functions/myfunctions.php');


if (isset($_POST['register_btn'])) {

  $name = mysqli_real_escape_string($connect, $_POST['name']);
  $phone = mysqli_real_escape_string($connect, $_POST['phone']);
  $email = mysqli_real_escape_string($connect, $_POST['email']);
  $password = mysqli_real_escape_string($connect, $_POST['password']);
  $cpassword = mysqli_real_escape_string($connect, $_POST['cpassword']);

  if ($password === $cpassword) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_query = "INSERT INTO ecomusers (name, email, phone, password) 
                     VALUES ('$name', '$email', '$phone', '$hashed_password')";
    $insert_query_run = mysqli_query($connect, $insert_query);

    if ($insert_query_run) {
      $_SESSION['message'] = "Registered Successfully";
      header('Location: ../login.php');
      exit();
    } else {
      $_SESSION['message'] = "Registration Failed";
      header('Location: ../register.php');
      exit();
    }
  } else {
    $_SESSION['message'] = "Passwords do not match";
    header('Location: ../register.php');
    exit();
  }
} 
else if (isset($_POST['login_btn'])) {
  $email = mysqli_real_escape_string($connect, $_POST['email']);
  $password = mysqli_real_escape_string($connect, $_POST['password']);

  $login_query = "SELECT * FROM ecomusers WHERE email='$email'";
  $login_query_run = mysqli_query($connect, $login_query);

  if (mysqli_num_rows($login_query_run) > 0) {
    $userdata = mysqli_fetch_assoc($login_query_run);

    if (password_verify($password, $userdata['password'])) {
      $_SESSION['auth'] = true;

      $_SESSION['auth_user'] = [
        'name' => $userdata['name'],
        'email' => $userdata['email']
      ];

      $_SESSION['role_as'] = $userdata['role_as'];

      if ($userdata['role_as'] == 1) {
        redirect("../admin/index.php", "Welcome to Dashbord");
      } 
    } else {
      redirect("../index.php", "Logged In Successfully");
    }
  } else {
   redirect("../login.php", "Invalid Credential");
  }
}
?>
