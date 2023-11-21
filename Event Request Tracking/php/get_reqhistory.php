<?php
session_start();
include('config.php'); // Include your database connection file

if (isset($_POST['reqID'])) {
    $reqID = $_POST['reqID'];

    // Fetch data from tbl_reqhistory and join with tbl_office
    $queryHis = "SELECT rh.officeID, rh.statusDate, o.designation
                 FROM tbl_reqhistory rh
                 JOIN tbl_office o ON rh.officeID = o.officeAccID
                 WHERE rh.reqID = ?";
    $stmtHis = mysqli_prepare($conn, $queryHis);
    mysqli_stmt_bind_param($stmtHis, "s", $reqID);
    mysqli_stmt_execute($stmtHis);
    $resultHis = mysqli_stmt_get_result($stmtHis);
    ?>

    <table id="Req2" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Office ID</th>
                <th>Designation</th>
                <th>Date Approved</th>
            </tr>
        </thead>
        <?php
        // Display the data in the modal body
        while ($rowReq = mysqli_fetch_assoc($resultHis)) {
            echo "<tr>";
            echo "<td>{$rowReq['officeID']}</td>";
            echo "<td>{$rowReq['designation']}</td>";
            echo "<td>{$rowReq['statusDate']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
<?php
}
?>
