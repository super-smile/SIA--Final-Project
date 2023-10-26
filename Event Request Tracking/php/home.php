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

if ($userType == 'Organization') {
    include 'config.php';

    $userID = $_SESSION['userID'];
    $query = "SELECT * FROM tbl_reqhistory WHERE reqStatus = 'Pending'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $queryArch = "SELECT * FROM tbl_reqhistory WHERE userID = ? and reqStatus = 'Approved'";
    $stmtArch = mysqli_prepare($conn, $queryArch);
    mysqli_stmt_bind_param($stmtArch, "s", $userID);
    mysqli_stmt_execute($stmtArch);
    $resultArch = mysqli_stmt_get_result($stmtArch);

}elseif ($userType == "Office"){
    include 'config.php';
    $userID = $_SESSION['userID'];

    $query = "SELECT * FROM tbl_reqhistory WHERE reqStatus = 'Pending'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $queryArch = "SELECT * FROM tbl_reqhistory WHERE reqStatus = 'Approved'";
    $stmtArch = mysqli_prepare($conn, $queryArch);
    mysqli_stmt_execute($stmtArch);
    $resultArch = mysqli_stmt_get_result($stmtArch);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/script.js"></script>
    <title>Document</title>
</head>
<body>
    <?php
    if ($userType == 'Organization') {
        echo '<a href="letter.php" id="uploadLetter">Upload a letter</a>';
    }
    ?>

    <h1>Welcome to Event Tracking System</h1>

    <button id="showForm1">Dashboard</button>
    <button id="showForm2">Request</button>
    <button id="showForm3">Archive</button>
    <button id="showForm4">Account</button>

    <p><a href="login.php">Logout</a></p>
    
    <form id="form1" style="display: block;">
        <h2>Dashboard</h2>
    </form>
    
    <form id="form2" style="display: none;">
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
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['histID']}</td>";
                    echo "<td>{$row['reqStatus']}</td>";
                    echo "<td>{$row['statusDate']}</td>";
                    echo "<td>{$row['reqDeadline']}</td>";
                    echo "<td>{$row['userID']}</td>";
                    echo "<td>{$row['reqID']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>


    <form id="form3" style="display: none;">
        <h2>Archive</h2>
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
                while ($rowArch = mysqli_fetch_assoc($resultArch)) {
                    echo "<tr>";
                    echo "<td>{$rowArch['histID']}</td>";
                    echo "<td>{$rowArch['reqStatus']}</td>";
                    echo "<td>{$rowArch['statusDate']}</td>";
                    echo "<td>{$rowArch['reqDeadline']}</td>";
                    echo "<td>{$rowArch['userID']}</td>";
                    echo "<td>{$rowArch['reqID']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>
    
    <form id="form4" style="display: none;">
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
