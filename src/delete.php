<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch and delete the actual file
    $query = "SELECT filepath FROM documents WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filepath);
    if ($stmt->fetch() && file_exists($filepath)) {
        unlink($filepath); // delete the file from server
    }
    $stmt->close();

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting record.";
    }
    $stmt->close();
}

$conn->close();
?>
