<?php
include 'config.php';

if (!isset($_GET['id'])) {
    echo "No document selected.";
    exit;
}

$id = intval($_GET['id']);

// Define fallback variables
$error = '';
$success = '';
$oldFilename = '';
$oldFilepath = '';

// Fetch existing data first
$stmt = $conn->prepare("SELECT filename, filepath FROM documents WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($oldFilename, $oldFilepath);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newFilename = $_POST['filename'];
    $newFilepath = $oldFilepath;

    // Check if a new file is uploaded
    if (isset($_FILES["newfile"]) && $_FILES["newfile"]["error"] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];
        $newFilenameRaw = basename($_FILES["newfile"]["name"]);
        $newFilepath = "uploads/" . $newFilenameRaw;

        $ext = strtolower(pathinfo($newFilepath, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Invalid file format.";
        } elseif (move_uploaded_file($_FILES["newfile"]["tmp_name"], $newFilepath)) {
            // Delete the old file
            if ($oldFilepath && file_exists($oldFilepath)) {
                unlink($oldFilepath);
            }
        } else {
            $error = "Failed to upload new file.";
        }
    }

    if (empty($error)) {
        // Update database
        $stmt = $conn->prepare("UPDATE documents SET filename = ?, filepath = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newFilename, $newFilepath, $id);

        if ($stmt->execute()) {
            $success = "Document updated successfully.";
            $oldFilename = $newFilename;
            $oldFilepath = $newFilepath;
        } else {
            $error = "Failed to update record.";
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Art Gallery</title>
  <link rel="stylesheet" href="./output.css">
  <link rel="stylesheet" href="./assets/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
  <style>
    .dm-serif-text-regular {
        font-family: "DM Serif Text", serif;
        font-weight: 400;
        font-style: normal;
        }
    .montserrat-regular {
        font-family: "Montserrat", sans-serif;
        font-optical-sizing: auto;
        font-weight: 400;
        font-style: normal;
        }
    .card {
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgb(0 0 0 / 0.25);
      background: white;
      max-width: 1000px;
      margin: 50px auto;
    }
    .card-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
    }
    .h {
        height: 100vh;
        align-items: center;
        display: flex;
        justify-content: center;
    }
    @media (min-width: 1024px) {
      .card-grid {
        grid-template-columns: 1fr 1fr;
      }
    }
  </style>
</head>
<body class="bg-wood h ">
  <div class="absolute top-0 left-0 w-full h-full filter blur-md -z-10" style='background-image: url("./assets/img1.png"); background-size: cover; background-position: center; background-repeat: no-repeat;'></div>

  <div class="flex justify-center items-center min-h-screen z-20 px-4">
    <div class="card">
      <h3 class="text-2xl font-semibold mb-2 dm-serif-text-regular">Edit Photo 🖼️</h3>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded-md mb-4 font-semibold">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded-md mb-4 font-semibold">
          <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>

      <div class="card-grid">
        <!-- Left side: Image preview -->
        <div class="flex items-center justify-center p-0">
          <img id="imagePreview" src="<?= htmlspecialchars($oldFilepath) ?>" alt="current document image" class="rounded-md max-w-full max-h-96">
        </div>

        <!-- Right side: Form -->
        <div class="flex items-center justify-center p-0">
          <div class="w-full p-4 text-black">
            <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
              <div>
                <label for="filename" class="block font-semibold mb-2">Filename</label>
                <input id="filename" type="text" name="filename" value="<?= htmlspecialchars($oldFilename) ?>" class="p-2 border border-gray-300 rounded-md w-full" required>
              </div>

              <div>
                <label for="newfile" class="block font-semibold mb-2">Replace File (optional)</label>
                <input id="newfile" type="file" name="newfile" onchange="previewImage(this)" class="p-2 border border-gray-300 rounded-md w-full">
                <p class="text-gray-500 mt-1">Choose a new file to replace the current one.</p>
              </div>

              <button type="submit" class="bg-green-500 hover:bg-green-600 text-gray-50 font-semibold px-4 py-2 rounded-md montserrat-regular cursor-pointer">
                Save Changes
              </button>

              <a 
                href='index.php' 
                class='bg-gray-500 hover:bg-gray-600 text-gray-50 font-semibold px-4 py-2 rounded-md text-center mt-2 block montserrat-regular cursor-pointer'>
                Cancel
              </a>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script>
    // preview the new image
    function previewImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          document.getElementById('imagePreview').src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>
</html>
