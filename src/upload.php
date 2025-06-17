<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = '';
    $success = '';
    $filename = '';
    $filePath = '';
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'webp'];

    $target_dir = "uploads/";

    // Create directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $filename = basename($_FILES["file"]["name"]);
    $filePath = $target_dir . $filename;

    $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        $error = "File type not allowed.";
    } elseif (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
        include 'config.php';

        $stmt = $conn->prepare("INSERT INTO documents (filename, filepath) VALUES (?, ?)");
        $stmt->bind_param("ss", $filename, $filePath);

        if ($stmt->execute()) {
            $success = "✅ File uploaded and saved successfully!";
        } else {
            $error = "❌ Database error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        $error = "❌ Error uploading file.";
    }
} else {
    $error = "No file uploaded.";
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-wood">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Art Gallery</title>
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
   
    <style>
    .h {
        height: 100vh;
        align-items: center;
        display: flex;
        justify-content: center;
        padding: 20px;
    }
    .dm-serif-text-regular {
            font-family: "DM Serif Text", serif;
            font-weight: 400;
            font-style: normal;
    }
    </style>
</head>
<body class="bg-wood h">
    <div class="w-full max-w-md p-4 sm:p-6 mt-12 ml-auto mr-auto bg-gray-100 rounded-md shadow-md">
        <h1 class="text-2xl font-semibold text-center mb-6 dm-serif-text-regular">Upload Result</h1>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-md mb-4 font-semibold">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-green-100 text-center text-green-700 p-4 rounded-md mb-4 font-semibold">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <a 
            href='index.php' 
            class='bg-blue-500 hover:bg-blue-600 text-gray-50 font-semibold px-4 py-2 rounded-md block text-center mt-4'>
            ⬅ Back to Document Manager
        </a>
    </div>
</body>
</html>

