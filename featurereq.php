<?php session_start(); include_once("_h.php"); 

if (!isset($_SESSION['id'])) {
    echo "
    <div class='alert alert-warning'>You need to be logged in to perform this action.</div>
    ";
    exit;
}

?>

<h2>Feature Requests</h2>

<p>Submit a feature request below. <b>You can only submit one request, but you can include multiple features</b>.</p>

<div class="form-floating">
  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
  <label for="floatingTextarea2">Start typing here...</label>
</div>

<?php include_once("_f.php"); ?>