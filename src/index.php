<!DOCTYPE html>
<html lang="en" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Art Gallery</title>
    <link rel="stylesheet" href="./output.css">
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Savate:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <style>
   .savate-regular {
        font-family: "Savate", sans-serif;
        font-optical-sizing: auto;
        font-weight: 400;
        font-style: normal;
        }
    .montserrat-regular {
        font-family: "Montserrat", sans-serif;
        font-optical-sizing: auto;
        font-weight: 400;
        font-style: normal;
        }
    .dm-serif-text-regular {
        font-family: "DM Serif Text", serif;
        font-weight: 400;
        font-style: normal;
        }
    </style>
</head>
<body class="bg-museum">
    <div class="max-w-3xl px-4 pt-12 mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold text-purple-100 text-shadow savate-regular">Art Gallery System 🖼️</h2>
        </div>

        <!-- Upload Form -->
        <div class="bg-wood rounded-lg shadow-md p-6 border-1">
            <h3 class="text-2xl font-semibold mb-4 dm-serif-text-regular">Upload Photo 😎</h3>
            <form action="upload.php" method="post" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4">
                <input 
                    type="file" 
                    name="file" 
                    class="flex-grow p-2 border rounded-md cursor-pointer bg-white" 
                    required>
                <button 
                    type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-gray-50 font-semibold px-4 py-2 text-center rounded-md montserrat-regular cursor-pointer">
                    Upload
                </button>
                <a 
                    href="index.php" 
                    class="bg-gray-500 hover:bg-gray-600 text-gray-50 font-semibold px-4 py-2 text-center rounded-md montserrat-regular cursor-pointer">
                    Cancel
                </a>
            </form>
        </div>
    </div>
    
    <!-- Document List -->
    <div class="max-w-full mx-auto p-6 md:p-12">
        <h4 class="text-3xl text-center font-semibold text-purple-100 savate-regular">📁 Documents</h4>
        <?php include 'list_documents.php'; ?>
    </div>
</body>
</html>
