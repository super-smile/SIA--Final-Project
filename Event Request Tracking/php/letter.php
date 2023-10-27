<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
<h1>Upload Request Letter</h1>
<p> <a href="home.php">Home</a></p>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <p>Fill the event information</p>
        Event Name:
        <input type="text" name="eventName" placeholder="Event Name" required><br><br>
        Event Date:
        <input type="date" name="eventDate" required><br><br>
        <p>Upload Request Letter</p>
        <input type="file" name="reqLetter" accept=".pdf" required>
        <input type="submit" name="upload" value="Upload and Submit">
    </form>
</body>
</html>