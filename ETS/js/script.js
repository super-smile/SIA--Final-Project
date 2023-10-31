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