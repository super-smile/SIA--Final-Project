<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" type="text/css" href="styleLetter.css">
    <title>Upload Event Letter</title>
</head>

<body>
    <div class="container">
        <form id="upload-letter">
            <p class="title">Upload Letter</p>
            <p class="subtitle">Please complete the event information.</p>

            <div class="input-event-name">
                <label for="event-name">Event Name:</label>
                <input type="text" name="event-name" id="event-name" required />
            </div>

            <div class="input-event-date">
                <label for="event-date">Event Date:</label>
                <input type="date" name="event-date" id="event-date" required />
            </div>

            <div class="subcontainer"></div>

            <div class="upload-icon">
                <i class='bx bx-cloud-upload'></i>
            </div>

            <a href="org.php">
                <div class="home-icon">

                    <i class='bx bx-home'></i>
            </a>


    </div>

    <p class="text1">Select a file from the computer or drag and drop here.</p>

    <p class="text2">For the best results, please submit a PDF file.</p>


    <form action="letter.php" method="post" enctype="multipart/form-data">
        <input type="button" value="Browse File" class="browse-file-button" onclick="uploadPDF() ">

        <input type="file" id="pdfFile" name="pdfFile" accept=".pdf" style="display: none;">
    </form>

    <script>
        function uploadPDF() {
            document.getElementById('pdfFile').click();
        }
    </script>

    <input type="submit" value="Cancel" class="cancel-button">


    <input type="submit" value="Submit" class="submit-button">

    </form>
    </div>

</body>

</html>