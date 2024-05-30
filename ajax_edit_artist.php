<?php
include 'auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$mysqli = new mysqli("localhost", "root", "Qq123456", "music");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $artistId = $_POST["id"];
    $pseudonym = $_POST["pseudonym"];
    $real_name = $_POST["real_name"];
    $age = $_POST["age"];
    $country = $_POST["country"];
    $famous_track = $_POST["famous_track"];
    $cover_link = $_POST["cover_link"];
    $song_link = $_POST["song_link"];
    $about = $_POST["about"];

    $updateStmt = $mysqli->prepare("UPDATE artists SET pseudonym=?, real_name=?, age=?, country=?, famous_track=?, song_link=?, cover_link=?, about=? WHERE artist_id=?");
    $updateStmt->bind_param("ssisssssi", $pseudonym, $real_name, $age, $country, $famous_track, $song_link, $cover_link, $about, $artistId);
    
    if ($updateStmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Artist updated successfully',
            'data' => [
                'id' => $artistId,
                'pseudonym' => $pseudonym,
                'real_name' => $real_name,
                'age' => $age,
                'country' => $country,
                'famous_track' => $famous_track,
                'song_link' => $song_link,
                'cover_link' => $cover_link,
                'about' => $about
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update artist']);
    }
    
    $updateStmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
