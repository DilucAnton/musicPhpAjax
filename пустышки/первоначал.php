<?php
include 'auth.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit; 
}

?>

<?php
$mysqli = new mysqli("localhost", "root", "Qq123456", "music");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_artist"])) {
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
    $generatedFile = generateArtistPage($artistId, $pseudonym, $real_name, $age, $country, $famous_track, $song_link, $cover_link, $about);
}



$result = $mysqli->query("SELECT * FROM artists");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $deleteId = $_POST['delete'];

    $getPseudonymStmt = $mysqli->prepare("SELECT pseudonym FROM artists WHERE artist_id = ?");
    $getPseudonymStmt->bind_param("i", $deleteId);
    $getPseudonymStmt->execute();
    $getPseudonymStmt->bind_result($pseudonym);
    $getPseudonymStmt->fetch();
    $getPseudonymStmt->close();

    $artistFilePath = "$pseudonym.php";
    if (file_exists($artistFilePath)) {
        unlink($artistFilePath);
    }

    $deleteStmt = $mysqli->prepare("DELETE FROM artists WHERE artist_id = ?");
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();
    
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
            background: url('https://img.freepik.com/free-photo/vibrant-night-sky-with-stars-and-nebula-and-galaxy_146671-19225.jpg?w=1800&t=st=1702251759~exp=1702252359~hmac=bc2d41ca35b31a9d5cedc4133ceec6b3bf7beb380e2367c0c5ca82eb4c80075a') no-repeat center center fixed;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em;
            text-align: center;
        }

        nav {
            margin-top: 1em;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 10px;
        }

        section {
            max-width: 800px;
            margin: 2em auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #FFFFFF;
        }
        
        h2 {
            color: #333;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 8px;
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

        form {
            margin-top: 20px;
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
        nav ul li a {
        color: #FFFFFF; 
        text-decoration: none;
    }

        nav ul li a:hover {
        text-decoration: underline;
    }
    </style>
    <title>Artists</title>
</head>
<body>
    <header>
        <h1>Artists</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>All Artists</h2>
        <ul>
        <?php
        $artistsData = [];
        while ($row = $result->fetch_assoc()) {
            $artistsData[] = $row;
        }

      
        foreach ($artistsData as $row): ?>
            <li>
                <?= htmlspecialchars($row["pseudonym"]) ?>
                <form method="post" action="edit_artist.php?id=<?= $row['artist_id'] ?>" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['artist_id'] ?>">
                    <button type="submit">Edit</button>
                </form>
                <form method="post" action="artists.php?id=<?= $row['artist_id'] ?>" style="display:inline;">
                    <input type="hidden" name="delete" value="<?= $row['artist_id'] ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete <?= $row["pseudonym"] ?>?')">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

        <button onclick="toggleAddArtistForm()">Add Artist</button>

        <div id="addArtistForm" style="display:none;">
            <h2>Add Artist</h2>
            <form method="post" action="artists.php">
                <label for="pseudonym">Pseudonym:</label>
                <input type="text" name="pseudonym" required>

                <label for="real_name">Real Name:</label>
                <input type="text" name="real_name" required>

                <label for="age">Age:</label>
                <input type="number" name="age">

                <label for="country">Country:</label>
                <input type="text" name="country">

                <label for="famous_track">Famous Track:</label>
                <input type="text" name="famous_track">

                <label for="song_link">Song Link:</label>
                <input type="text" name="song_link">

                <label for="cover_link">Cover Link:</label>
                <input type="text" name="cover_link">
                <label for="about">About:</label>
                <input type="text" name="about">

                <button type="submit" name="add_artist">Add</button>
            </form>
        </div>
    </section>

    <script>
        function toggleAddArtistForm() {
            var addArtistForm = document.getElementById("addArtistForm");
            addArtistForm.style.display = addArtistForm.style.display === "block" ? "none" : "block";
        }
    </script>
</body>
</html>
