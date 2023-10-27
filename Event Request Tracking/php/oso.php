<h1>Office of Students Organization</h1>

<?php
session_start();

include 'config.php';
//Account Information
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


include 'config.php';

$userID = $_SESSION['userID'];

$query = "SELECT userName FROM tbl_account";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$queryReq = "SELECT * FROM tbl_reqhistory";
$stmtReq = mysqli_prepare($conn, $queryReq);
mysqli_stmt_execute($stmtReq);
$resultReq = mysqli_stmt_get_result($stmtReq);

$queryEvents = "SELECT * FROM tbl_reqhistory";
$stmtEvents = mysqli_prepare($conn, $queryEvents);
mysqli_stmt_execute($stmtEvents);
$resultEvents = mysqli_stmt_get_result($stmtEvents);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSO</title>
</head>
<body>


    <h2>Welcome to Event Tracking System</h2>

    <button id="showForm1">Dashboard</button>
    <button id="showForm2">Organizations</button>
    <button id="showForm3">Requests</button>
    <button id="showForm4">All Events</button>
    <button id="showForm5">Create Account</button>
    <button id="showForm6">Account</button>

    <p><a href="login.php">Logout</a></p>
    
    <form id="form1" style="display: block;">
        <h2>Dashboard</h2>
    </form>
    
    <form id="form2" style="display: none;">
        <h2>Organizations</h2>

    <style>
    table {
        text-align: center;
    }
    .bordered {
        padding: 5px;
        border-collapse: collapse;
        border: 1px solid #000;
    }

    .bordered th, .bordered td {
        padding: 5px;
        border: 1px solid #000;
    }
    </style>

        <table class="bordered">
            <thead>
                <tr>
                    <th>Organizations Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['userName']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>


    <form id="form3" style="display: none;">
        <h2>Requests</h2>
    <style>
    table {
        text-align: center;
    }
    .bordered {
        padding: 5px;
        border-collapse: collapse;
        border: 1px solid #000;
    }

    .bordered th, .bordered td {
        padding: 5px;
        border: 1px solid #000;
    }
    </style>

        <table class="bordered">
            <thead>
                <tr>
                    <th>histID</th>
                    <th>reqStatus</th>
                    <th>statusDate</th>
                    <th>reqDeadline</th>
                    <th>userID</th>
                    <th>reqID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php';
                while ($rowReq = mysqli_fetch_assoc($resultReq)) {
                    echo "<tr>";
                    echo "<td>{$rowReq['histID']}</td>";
                    echo "<td>{$rowReq['reqStatus']}</td>";
                    echo "<td>{$rowReq['statusDate']}</td>";
                    echo "<td>{$rowReq['reqDeadline']}</td>";
                    echo "<td>{$rowReq['userID']}</td>";
                    echo "<td>{$rowReq['reqID']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>
    
    <form id="form4" style="display: none">
    <h2>All Events</h2>

    <style>
    table {
        text-align: center;
    }
    .bordered {
        padding: 5px;
        border-collapse: collapse;
        border: 1px solid #000;
    }

    .bordered th, .bordered td {
        padding: 5px;
        border: 1px solid #000;
    }
    </style>

        <table class="bordered">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Approval Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php';
                while ($rowEvents = mysqli_fetch_assoc($resultEvents)) {
                    echo "<tr>";
                    echo "<td>{$rowEvents['reqID']}</td>";
                    echo "<td>{$rowEvents['statusDate']}</td>";
                    echo "<td>{$rowEvents['reqStatus']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>

    <form id="form5" style="display: none;">
    <h2>Create Account</h2>
    <p>Create New Account <a href="register.php">Here!</a></p>
    </form>

    <form id="form6" style="display: none;">
      <h2>Account</h2>
      <p>Username: <span id="userNameDisplay"></span></p>
      <p>Department: <span id="userDeptDisplay"></span></p>
      <p>Email: <span id="userEmailDisplay"></span></p>
    </form>
    
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
    var button5 = document.getElementById("showForm5");
    var button6 = document.getElementById("showForm6");
  
    var form1 = document.getElementById("form1");
    var form2 = document.getElementById("form2");
    var form3 = document.getElementById("form3");
    var form4 = document.getElementById("form4");
    var form5 = document.getElementById("form5");
    var form6 = document.getElementById("form6");
  
    button1.addEventListener("click", function() {
      form1.style.display = "block";
      form2.style.display = "none";
      form3.style.display = "none";
      form4.style.display = "none";
      form5.style.display = "none"
      form6.style.display = "none"
    });
  
    button2.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "block";
      form3.style.display = "none";
      form4.style.display = "none";
      form5.style.display = "none";
      form6.style.display = "none";
    });
  
    button3.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "none";
      form3.style.display = "block";
      form4.style.display = "none";
      form5.style.display = "none";
      form6.style.display = "none";
    });

    button4.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "none";
      form3.style.display = "none";
      form4.style.display = "block";
      form5.style.display = "none";
      form6.style.display = "none";
    });

    button5.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "none";
      form3.style.display = "none";
      form4.style.display = "none";
      form5.style.display = "block";
      form6.style.display = "none";
    });
  
    button6.addEventListener("click", function() {
      form1.style.display = "none";
      form2.style.display = "none";
      form3.style.display = "none";
      form4.style.display = "none";
      form5.style.display = "none";
      form6.style.display = "block";

      updateAccountInformation("<?php echo $dbUserName; ?>", "<?php echo $userDept; ?>", "<?php echo $userEmail; ?>");
    });
</script>
</html>
