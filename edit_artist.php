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

        .menu {
            color: #FFFFFF;
        }

        .error {
            color: red;
        }

        .notification {
            display: none;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        
        <div id="notification" class="notification"></div>

        <form id="editArtistForm">
            <input type="hidden" name="id" id="artistId" value="<?= $artistId ?>">

            <label for="pseudonym">Pseudonym:</label>
            <input type="text" name="pseudonym" id="pseudonym" value="<?= $artistData['pseudonym'] ?>" required>

            <label for="real_name">Real Name:</label>
            <input type="text" name="real_name" id="real_name" value="<?= $artistData['real_name'] ?>" required>

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" value="<?= $artistData['age'] ?>">

            <label for="country">Country:</label>
            <input type="text" name="country" id="country" value="<?= $artistData['country'] ?>">

            <label for="famous_track">Famous Track:</label>
            <input type="text" name="famous_track" id="famous_track" value="<?= $artistData['famous_track'] ?>">

            <label for="song_link">Song Link:</label>
            <input type="text" name="song_link" id="song_link" value="<?= $artistData['song_link'] ?>">

            <label for="cover_link">Cover Link:</label>
            <input type="text" name="cover_link" id="cover_link" value="<?= $artistData['cover_link'] ?>">

            <label for="about">About:</label>
            <input type="text" name="about" id="about" value="<?= $artistData['about'] ?>">

            <button type="submit">Save Changes</button>
        </form>
        <div id="changes"></div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var previousData = <?= json_encode($artistData) ?>;
            $("#editArtistForm").submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "ajax_edit_artist.php",
                    data: formData,
                    success: function(response) {
                        var res = JSON.parse(response);
                        var notification = $("#notification");
                        if (res.status === 'success') {
                            notification.removeClass("error").addClass("success").text('Artist updated successfully').show();
                            setTimeout(function() {
                                notification.fadeOut();
                            }, 3000);
                            displayChanges(previousData, res.data);
                        } else {
                            notification.removeClass("success").addClass("error").text(res.message).show();
                            setTimeout(function() {
                                notification.fadeOut();
                            }, 3000);
                        }
                    },
                    error: function() {
                        var notification = $("#notification");
                        notification.removeClass("success").addClass("error").text('Error communicating with the server.').show();
                        setTimeout(function() {
                            notification.fadeOut();
                        }, 3000);
                    }
                });
            });
            function displayChanges(previousData, newData) {
            var changesDiv = $("#changes");
            changesDiv.empty();
            changesDiv.append("<h3>Changes:</h3>");
         for (var key in newData) {
                if (newData.hasOwnProperty(key) && newData[key] !== previousData[key] && key !== 'id' && key !== 'age') {
                 changesDiv.append("<p><strong>" + key.replace(/_/g, " ") + ":</strong> " + previousData[key] + " -> " + newData[key] + "</p>");
             }
         }
}
  




        });
    </script>
</body>
</html>
</body>
</html>
