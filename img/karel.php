<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $images = $_FILES['images'];

    // Initialize an empty array to store the pixel data of all images
    $allPixels = [];

    // Loop through each uploaded image
    foreach ($images['tmp_name'] as $index => $fileTmp) {
        // Load image (PNG/JPG/GIF supported by GD)
        $src = @imagecreatefromstring(file_get_contents($fileTmp));
        if (!$src) {
            die("Failed to load image $index.");
        }

        $width = 50;
        $height = 50;

        // Create resized 50x50 image
        $dst = imagecreatetruecolor($width, $height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));

        // Extract pixels into 2D array
        $pixels = [];
        for ($y = 0; $y < $height; $y++) {
            $row = [];
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($dst, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $row[] = [$r, $g, $b];
            }
            $pixels[] = $row;
        }

        // Add the pixel data of the current image to the array of all images
        $allPixels[] = $pixels;
    }

    // Output JS array without flipping
    echo "<h2>Array:</h2>";
    echo "<p>var allPixels = [\n";
    foreach ($allPixels as $imageIndex => $imagePixels) {
        echo "  [\n";
        foreach ($imagePixels as $rowIndex => $row) {
            $rowStr = "[" . implode(", ", array_map(fn($rgb) => "[" . implode(",", $rgb) . "]", $row)) . "]";
            if ($rowIndex < count($imagePixels) - 1) {
                echo "    $rowStr,\n";
            } else {
                echo "    $rowStr\n"; // no trailing comma
            }
        }
        echo "  ]"; // close the image array
        if ($imageIndex < count($allPixels) - 1) {
            echo ",\n"; // add a comma after each image array except the last one
        }
    }
    echo "\n];</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>UltraKarel Image Converter</title>
</head>
<body>
  <form method="post" enctype="multipart/form-data">
      <input type="file" name="images[]" multiple required webkitdirectory mozallowdirectory allowdirectory directory>
      <button type="submit">Convert</button>
  </form>
</body>
</html>
