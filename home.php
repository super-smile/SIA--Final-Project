<?php
session_start();

include 'config.php';

if (isset($_SESSION['userName'])) {
    $userName = $_SESSION['userName'];
    echo "Welcome Back, $userName!";

    $query = "SELECT userName, userDept, userEmail FROM tbl_account WHERE userName = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dbUserName, $userDept, $userEmail);
    mysqli_stmt_fetch($stmt);
    $userType = $_SESSION['userType'];
    $userID = $_SESSION['userID'];
} else {
    header('location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="letter.php" id="uploadLetter">Upload a letter</a>

    <h1>Welcome to Event Tracking System</h1>
    <form id="form1" style="display: block;">
        <h1>Dashboard</h1>
    </form>
    
    <form id="form2" style="display: none;">
    <h1>Requests</h1>
</form>


    <form id="form3" style="display: none;">
        <h1>Archive</h1>
    </form>
    
    <form id="form4" style="display: none;">
      <h1>Account</h1>
      <p>Username: <span id="userNameDisplay"></span></p>
      <p>Department: <span id="userDeptDisplay"></span></p>
      <p>Email: <span id="userEmailDisplay"></span></p>
    </form>
    
      <button id="showForm1">Dashboard</button>
      <button id="showForm2">Request</button>
      <button id="showForm3">Archive</button>
      <button id="showForm4">Account</button>

<a href="login.php">Logout</a>
</body>
<script>
    function updateAccountInformation(userName, userDept, userEmail) {
    document.getElementById('userNameDisplay').textContent = userName;
    document.getElementById('userDeptDisplay').textContent = userDept;
    document.getElementById('userEmailDisplay').textContent = userEmail;
}
    var button1 = document.getElementById("showForm1");
    var button2 = document.getElementById("showForm2");
    var button3 = document.getElementById("showForm3");
    var button4 = document.getElementById("showForm4");
  
    var form1 = document.getElementById("form1");
    var form2 = document.getElementById("form2");
    var form3 = document.getElementById("form3");
    var form4 = document.getElementById("form4");
  
    button1.addEventListener("click", function() {
      form1.style.display = "block";
      form2.style.display = "none";
      form3.style.display = "none";
      form4.style.display = "none";
    });
  
    button2.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "block";
      form3.style.display = "none";
      form4.style.display = "none";
    });
  
    button3.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "none";
      form3.style.display = "block";
      form4.style.display = "none";
    });
  
    button4.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "none";
      form3.style.display = "none";
      form4.style.display = "block";

      updateAccountInformation("<?php echo $dbUserName; ?>", "<?php echo $userDept; ?>", "<?php echo $userEmail; ?>");
    });
</script>
</html>
