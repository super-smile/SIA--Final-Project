<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" type="text/css" href="styleLetter.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <title>Upload Event Letter</title>
</head>

<body>
    <div class="container">

        <form id="upload-letter" method="POST" action="upload.php" enctype="multipart/form-data">


            <p class="title">Upload Letter</p>
            <p class="subtitle">Please complete the event information.</p>

            <div class="input-event-name">
                <label for="eventName">Event Name:</label>
                <input type="text" name="eventName" id="eventName" required />
            </div>

            <div class="input-event-date">
                <label for="eventDate">Event Date:</label>
                <input type="date" name="eventDate" id="eventDate" required />
            </div>

            <div class="subcontainer">
                <p class="attachment-file">
                    <a id="file-link" href="#" target="_blank"></a>
                </p>

            </div>

            <div class="upload-icon">
                <i class='bx bx-cloud-upload'></i>
            </div>

            <a href="org.php">
                <div class="home-icon">
                    <i class='bx bx-home'></i>
                </div>
            </a>

            <p class="text1">Selsect a file from the computer or drag and drop here.</p>
            <p class="text2">For the best results, please submit a PDF file.</p>

            <input type="file" id="pdfFile" name="pdfFile" accept=".pdf" style="display: none; visibility: visible;">
            <input type="button" value="Browse File" class="browse-file-button" onclick="uploadPDF()">
            <script>
                function uploadPDF() {
                    document.getElementById("pdfFile").click();
                }

                document.getElementById("pdfFile").addEventListener("change", function() {
                    var fileInput = this;
                    var fileName = fileInput.files[0] ? fileInput.files[0].name : "No file selected" + fileName;
                    var fileURL = window.URL.createObjectURL(fileInput.files[0]);

                    // Update the file link with the file name and URL
                    document.querySelector(".attachment-file a").textContent = fileName;
                    document.querySelector(".attachment-file a").href = fileURL;
                });
            </script>

            <input type="submit" value="Cancel" class="cancel-button">

            <input type="submit" value="Submit" class="submit-button">


            <script>
                document.querySelector(".submit-button").addEventListener("click", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Submission Confirm',
                        html: "Are you certain you want to submit this? The information you've entered <strong>cannot be altered or reversed once</strong> it's been submitted.",
                        showCancelButton: true,
                        confirmButtonText: 'Confirm Submission',
                        cancelButtonText: 'Cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("upload-letter").submit();
                            Swal.fire({
                                title: 'Submission Successful',
                                text: 'Thank you for submitting your form!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            </script>


        </form>
    </div>

    <script src="script.js">
    </script>
</body>

</html>