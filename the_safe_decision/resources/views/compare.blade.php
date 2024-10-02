<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Comparison</title>
</head>
<body>
    <h1>Car Image Comparison</h1>
    <form action="/compare" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image1">Upload first image of the vehicle:</label>
        <input type="file" name="image1" id="image1" required><br><br>

        <label for="image2">Upload second image of the vehicle:</label>
        <input type="file" name="image2" id="image2" required><br><br>

        <button type="submit">Compare Images</button>
    </form>
</body>
</html>
