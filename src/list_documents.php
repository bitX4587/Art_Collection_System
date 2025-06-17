<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Art Gallery</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./output.css">
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

        .h {
            height: 100vh;
            align-items: center;
            display: flex;
            justify-content: center;
        }
        @media (max-width: 639px) {
            .flex.justify-evenly {
                gap: 5px;
            }
            .flex.justify-evenly a {
                padding: 0.5rem 0.75rem;
                font-size: 0.5rem;
            }
        }
    </style>

</head>
<body>

<?php
include 'config.php';

$stmt = $conn->prepare("SELECT * FROM documents ORDER BY uploaded_at DESC");

$stmt->execute();

$result = $stmt->get_result();
?>

<?php if ($result->num_rows > 0): ?>
    <div class="m-6 columns-1 sm:columns-2 md:columns-3 gap-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
                $id = $row['id']; 
                $filename = htmlspecialchars($row['filename']); 
                $filepath = htmlspecialchars($row['filepath']); 
                $fileExt = strtolower(pathinfo($filepath, PATHINFO_EXTENSION)); 
                $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']); 
            ?>
            <li class="bg-wood p-4 ml-4 mr-4 mb-4 rounded shadow-md break-inside-avoid">
                <?php if ($isImage): ?>
                    <img src="<?= $filepath ?>" alt="<?= $filename ?>" class="w-full h-auto object-cover rounded-md mb-4 border-3">
                <?php else: ?>
                    <div class='flex items-center justify-center w-full h-48 bg-gray-300 rounded-md mb-4'>
                        <span class='text-gray-500 font-semibold dm-serif-text-regular'>No Preview</span>
                    </div>
                <?php endif; ?>
                                
                <h5 class="text-2xl text-center font-semibold mb-4 rounded p-2 bg-wood-text">
                    <span class="dm-serif-text-regular"><?= $filename ?></span>
                </h5>

                <div class="flex justify-evenly flex-wrap gap-2 mt-4">
                    <a 
                        href="<?= $filepath ?>" 
                        class="bg-blue-500 hover:bg-blue-600 text-gray-50 font-semibold px-4 py-2 rounded-md montserrat-regular"
                        target="_blank">
                        View
                    </a>

                     <a 
                        href='<?= $filepath ?>' 
                        download
                        class='bg-green-500 hover:bg-green-600 text-gray-50 font-semibold px-4 py-2 rounded-md montserrat-regular'>
                        Download
                    </a>

                    <a 
                        href='update.php?id=<?= $id ?>' 
                        class='bg-yellow-500 hover:bg-yellow-600 text-gray-50 font-semibold px-4 py-2 rounded-md montserrat-regular'>
                        Edit
                    </a>

                    <a 
                        href='delete.php?id=<?= $id ?>'
                        class='bg-red-500 hover:bg-red-600 text-gray-50 font-semibold px-4 py-2 rounded-md montserrat-regular'
                        onclick='return confirm("Are you sure you want to delete this photo?");'>
                        Delete
                    </a>
                </div>
            </li>
        <?php endwhile; ?>
                </div>
<?php else: ?>
    <div class='bg-yellow-100 text-5xl text-center text-yellow-700 p-4 m-4 rounded-md font-semibold h'>
        No documents found.
    </div>
<?php endif; ?>

<?php
$stmt->close();
$conn->close();
?>

</body>
</html>
