<?php
include 'auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$mysqli = new mysqli("localhost", "root", "Qq123456", "music");

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
        if ($_POST["action"] == "add_artist") {
            $pseudonym = $_POST["pseudonym"];
            $real_name = $_POST["real_name"];
            $age = $_POST["age"];
            $country = $_POST["country"];
            $famous_track = $_POST["famous_track"];
            $cover_link = $_POST["cover_link"];
            $song_link = $_POST["song_link"];
            $about = $_POST["about"];

            $stmt = $mysqli->prepare("INSERT INTO artists (pseudonym, real_name, age, country, famous_track, song_link, cover_link, about) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisssss", $pseudonym, $real_name, $age, $country, $famous_track, $song_link, $cover_link, $about);
            $stmt->execute();

            $artistId = $stmt->insert_id;
            $stmt->close();
            require_once('generate_artist_page.php');
            $generatedFile = generateArtistPage($mysqli->insert_id, $pseudonym, $real_name, $age, $country, $famous_track, $song_link, $cover_link, $about);


            $newArtist = [
                'id' => $artistId,
                'pseudonym' => $pseudonym,
                'real_name' => $real_name,
                'age' => $age,
                'country' => $country,
                'famous_track' => $famous_track,
                'song_link' => $song_link,
                'cover_link' => $cover_link,
                'about' => $about
            ];

            $response = ['status' => 'success', 'message' => 'Artist added successfully', 'artist' => ['id' => $artistId, 'pseudonym' => $pseudonym], 'changes' => ['action' => 'added', 'pseudonym' => $pseudonym, 'real_name' => $real_name, 'age' => $age, 'country' => $country, 'famous_track' => $famous_track, 'song_link' => $song_link, 'cover_link' => $cover_link, 'about' => $about]];
        } elseif ($_POST["action"] == "delete_artist") {
            $deleteId = $_POST['delete'];

            
            $getArtistStmt = $mysqli->prepare("SELECT pseudonym, real_name, age, country, famous_track, song_link, cover_link, about FROM artists WHERE artist_id = ?");
            $getArtistStmt->bind_param("i", $deleteId);
            $getArtistStmt->execute();
            $getArtistStmt->bind_result($pseudonym, $real_name, $age, $country, $famous_track, $song_link, $cover_link, $about);
            $getArtistStmt->fetch();
            $getArtistStmt->close();

            /*$insertDeletedArtistStmt = $mysqli->prepare("INSERT INTO deleted_artists (pseudonym, real_name, age, country, famous_track, cover_link, song_link) SELECT pseudonym, real_name, age, country, famous_track, cover_link, song_link FROM artists WHERE artist_id = ?");
            $insertDeletedArtistStmt->bind_param("i", $deleteId);
            $insertDeletedArtistStmt->execute();
            $insertDeletedArtistStmt->close();*/


            $deleteStmt = $mysqli->prepare("DELETE FROM artists WHERE artist_id = ?");
            $deleteStmt->bind_param("i", $deleteId);
            $deleteStmt->execute();
            $deleteStmt->close();

            
            $deletedArtist = [
                'pseudonym' => $pseudonym,
                'real_name' => $real_name,
                'age' => $age,
                'country' => $country,
                'famous_track' => $famous_track,
                'song_link' => $song_link,
                'cover_link' => $cover_link,
                'about' => $about
            ];


            $changes = [
                'action' => 'deleted',
                'pseudonym' => $pseudonym,
                'real_name' => $real_name,
                'age' => $age,
                'country' => $country,
                'famous_track' => $famous_track,
                'song_link' => $song_link,
                'cover_link' => $cover_link,
                'about' => $about
            ];

            $response = ['status' => 'success', 'message' => 'Artist deleted successfully', 'deleted_artist' => $deletedArtist, 'changes' => $changes];
        } elseif ($_POST["action"] == "restore_artist") {
            $restoreId = $_POST['restore'];
        
   
            $restoreStmt = $mysqli->prepare("SELECT pseudonym, real_name, age, country, famous_track, song_link, cover_link, about FROM deleted_artists WHERE artist_id = ?");
            $restoreStmt->bind_param("i", $restoreId);
            $restoreStmt->execute();
            $restoreStmt->bind_result($pseudonym, $real_name, $age, $country, $famous_track, $song_link, $cover_link, $about);
            $restoreStmt->fetch();
            $restoreStmt->close();
        

            $insertStmt = $mysqli->prepare("INSERT INTO artists (pseudonym, real_name, age, country, famous_track, song_link, cover_link, about) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("ssisssss", $pseudonym, $real_name, $age, $country, $famous_track, $song_link, $cover_link, $about);
            $insertStmt->execute();
            $insertStmt->close();
        
  
            $deleteStmt = $mysqli->prepare("DELETE FROM deleted_artists WHERE artist_id = ?");
            $deleteStmt->bind_param("i", $restoreId);
            $deleteStmt->execute();
            $deleteStmt->close();
        
            $restoredArtist = [
                'pseudonym' => $pseudonym,
                'real_name' => $real_name,
                'age' => $age,
                'country' => $country,
                'famous_track' => $famous_track,
                'song_link' => $song_link,
                'cover_link' => $cover_link,
                'about' => $about
            ];
        
            $response = ['status' => 'success', 'message' => 'Artist restored successfully', 'restored_artist' => $restoredArtist];
        }
        
    }
}

echo json_encode($response);
?>
