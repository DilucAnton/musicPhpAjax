<?php
include 'auth.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit; 
}

$mysqli = new mysqli("localhost", "root", "Qq123456", "music");

$result = $mysqli->query("SELECT * FROM artists");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Artists</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <ul id="artistsList">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li id="artist-<?= $row['artist_id'] ?>">
                    <?= htmlspecialchars($row["pseudonym"]) ?>
                    <form method="post" action="edit_artist.php?id=<?= $row['artist_id'] ?>" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['artist_id'] ?>">
                        <button type="submit">Edit</button>
                    </form>
                    <form method="post" class="delete-artist-form" data-id="<?= $row['artist_id'] ?>" style="display:inline;">
                        <input type="hidden" name="delete" value="<?= $row['artist_id'] ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete <?= $row["pseudonym"] ?>?')">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>

        <button onclick="toggleAddArtistForm()">Add Artist</button>

        <div id="addArtistForm" style="display:none;">
            <h2>Add Artist</h2>
            <form id="addArtistFormElement" method="post">
                <input type="hidden" name="action" value="add_artist">
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

                <button type="submit">Add</button>
            </form>
        </div>
    </section>

    <script>
        function toggleAddArtistForm() {
            var addArtistForm = document.getElementById("addArtistForm");
            addArtistForm.style.display = addArtistForm.style.display === "block" ? "none" : "block";
        }

        $(document).ready(function() {
            $("#addArtistFormElement").submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.post("ajax_handler.php", formData, function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        $("#artistsList").append('<li id="artist-' + res.artist.id + '">' +
                            res.artist.pseudonym + 
                            '<form method="post" action="edit_artist.php?id=' + res.artist.id + '" style="display:inline;">' +
                            '<input type="hidden" name="id" value="' + res.artist.id + '">' +
                            '<button type="submit">Edit</button></form>' +
                            '<form method="post" class="delete-artist-form" data-id="' + res.artist.id + '" style="display:inline;">' +
                            '<input type="hidden" name="delete" value="' + res.artist.id + '">' +
                            '<button type="submit" onclick="return confirm(\'Are you sure you want to delete ' + res.artist.pseudonym + '?\')">Delete</button>' +
                            '</form></li>');
                        toggleAddArtistForm();
                    } else {
                        alert(res.message);
                    }
                });
            });

            $(document).on("submit", ".delete-artist-form", function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                var artistId = $(this).data("id");

                $.post("ajax_handler.php", formData + "&action=delete_artist", function(response) {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        $("#artist-" + artistId).remove();
                    } else {
                        alert(res.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
