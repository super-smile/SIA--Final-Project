<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" type="text/css" href="letterStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <title>Upload Event Letter</title>
</head>

<body>

    <div class="container">

        <form id="upload-letter" method="POST" action="upload.php" enctype="multipart/form-data">

            <div class="input-event-name">
                <label for="eventName">Event Name:</label>
                <input type="text" name="eventName" id="eventName" required />
            </div>

            <div class="input-event-date">
                <label for="eventDate">Event Date:</label>
                <div style="font-size: 0.6em; margin-top: -6.5em; color: red;">Note: Schedule your event date one week from today for timely administrator approval.</div>
                <?php
                    $minDate = date('Y-m-d', strtotime('+1 week')); // Allow dates starting from tomorrow
                ?>
                <input type="date" name="eventDate" id="eventDate" required min="<?php echo $minDate; ?>"/>
            </div>

            <p class="title">Upload Letter</p>
            <p class="subtitle">Please complete the event information.</p>


            <div class="upload-icon">
                <i class='bx bx-cloud-upload'></i>
            </div>

            <a href="org.php">
                <div class="home-icon">
                    <i class='bx bx-home'></i>
                </div>
            </a>

            <div class="subcontainer">

            </div>

            <p class="text1">Select a file from the computer or drag and drop here.</p>
            <p class="text2">For the best results, please submit a PDF file.</p>

            <input type="file" id="pdfFile" name="pdfFile" accept=".pdf" style="display: none;">
            <input type="button" value="Browse File" class="browse-file-button" onclick="document.getElementById('pdfFile').click();">

            <script>
                function openFileInput() {
                    document.getElementById('pdfFile').click();
                }


                document.getElementById('pdfFile').addEventListener('change', function() {
                    var fileInput = this;
                    var fileName = fileInput.files[0] ? fileInput.files[0].name : 'No file selected';

                    if (fileInput.files[0]) {
                        var fileURL = window.URL.createObjectURL(fileInput.files[0]);
                        document.querySelector('.attachment-file a').textContent = fileName;
                        document.querySelector('.attachment-file a').href = fileURL;
                    } else {
                        document.querySelector('.attachment-file a').textContent = 'No file selected';
                        document.querySelector('.attachment-file a').removeAttribute('href');
                    }
                });
            </script>

            <input type="button" value="Cancel" class="cancel-button" onclick="window.location.href='org.php'">
            <input type="submit" value="Submit" class="submit-button">

            <script>
                document.querySelector(".submit-button").addEventListener("click", function(e) {
                    e.preventDefault();

                    const eventName = document.getElementById("eventName").value;
                    const eventDate = document.getElementById("eventDate").value;
                    const pdfFile = document.getElementById("pdfFile").value;

                    if (!eventName || !eventDate || !pdfFile) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please fill in all fields!',
                        });
                    } else {
                        Swal.fire({
                            title: 'Submission Confirm',
                            html: "Are you certain you want to submit this? The information you've entered <strong>cannot be altered or reversed once</strong> it's been submitted.",
                            showCancelButton: true,
                            confirmButtonText: 'Confirm Submission',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById("upload-letter").submit();
                            }
                        });
                    }
                });
            </script>

        </form>
    </div>
    
</body>

</html>