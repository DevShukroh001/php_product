<?php 
session_start();

include('includes/header.php');

?>

<?php
if (isset($_SESSION['message'])) {
  ?>
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Hey!</strong> <?= $_SESSION['message']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php
  unset($_SESSION['message']);
}
?>


<h2>Hello Devshuk</h2>
<button class="btn btn-primary">Testing</button>
<?php include('includes/footer.php'); ?>