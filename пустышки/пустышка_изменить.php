<?php
include 'auth.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit; 
}
?>

<?php

$mysqli = new mysqli("localhost", "root", "Qq123456", "music");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_artist"])) {
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
    $updateStmt->execute();
    $updateStmt->close();


    header("Location: artists.php");
    exit;
}

if (isset($_GET['id'])) {
    $artistId = $_GET['id'];


    $getArtistStmt = $mysqli->prepare("SELECT * FROM artists WHERE artist_id = ?");
    $getArtistStmt->bind_param("i", $artistId);
    $getArtistStmt->execute();
    $result = $getArtistStmt->get_result();

    if ($result->num_rows > 0) {
        $artistData = $result->fetch_assoc();
    } else {
        header("Location: artists.php");
        exit;
    }

    $getArtistStmt->close();
} else {

    header("Location: artists.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> 
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em;
            text-align: center;
        }

        section {
            max-width: 800px;
            margin: 2em auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }
        .menu{
            color: #FFFFFF;
        }
        .error {
            color: red;
        }
    </style>
    <title>Edit Artist</title>
</head>
<body>
    <header>
        <h1>Edit Artist</h1>
        <nav>
            <ul>
                <li><a class="menu" href="artists.php">Back to Artists</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Edit Artist Information</h2>
        
        <form method="post" action="edit_artist.php">
            <input type="hidden" name="id" value="<?= $artistId ?>">

            <label for="pseudonym">Pseudonym:</label>
            <input type="text" name="pseudonym" value="<?= $artistData['pseudonym'] ?>" required>

            <label for="real_name">Real Name:</label>
            <input type="text" name="real_name" value="<?= $artistData['real_name'] ?>" required>

            <label for="age">Age:</label>
            <input type="number" name="age" value="<?= $artistData['age'] ?>">

            <label for="country">Country:</label>
            <input type="text" name="country" value="<?= $artistData['country'] ?>">

            <label for="famous_track">Famous Track:</label>
            <input type="text" name="famous_track" value="<?= $artistData['famous_track'] ?>">

            <label for="song_link">Song Link:</label>
            <input type="text" name="song_link" value="<?= $artistData['song_link'] ?>">

            <label for="cover_link">Cover Link:</label>
            <input type="text" name="cover_link" value="<?= $artistData['cover_link'] ?>">

            <label for="about">About:</label>
            <input type="text" name="about" value="<?= $artistData['about'] ?>">

            <button type="submit" name="edit_artist">Save Changes</button>
        </form>
    </section>

</body>
</html>
