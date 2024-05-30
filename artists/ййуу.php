
    <?php include '../auth.php'; ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <style>
            body {
                font-family: 'Arial', sans-serif;
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

            a {
                text-decoration: none;
                color: #333;
            }
            .menu {
                color: #FFFFFF;
            }
            a:hover {
                text-decoration: underline;
            }
            .imgStyle{
                height: 350px;   
            }
        </style>
        <title>ййуу</title>
    </head>
    <body>
        <header>
            <h1>ййуу</h1>
            <nav>
                <ul>
                    <li><a class = "menu" href="../index.php">Home</a></li>
                </ul>
            </nav>
        </header>

        <section>
        <h2>Artist Information</h2>
        <ul>
            <?php
            
            $mysqli = new mysqli("localhost", "root", "Qq123456", "music");

           
            $result = $mysqli->query("SELECT * FROM artists WHERE pseudonym = 'ййуу'");
            $pseudonym = basename(__FILE__,'.php');
            
            if ($result && $row = $result->fetch_assoc()) {
                echo "<li><strong>Real Name:</strong> " . htmlspecialchars($row["real_name"]) . "</li>";
                echo "<li><strong>Age:</strong> " . htmlspecialchars($row["age"]) . "</li>";
                echo "<li><strong>Country:</strong> " . htmlspecialchars($row["country"]) . "</li>";
                echo "<li><strong>Famous Track:</strong> " . htmlspecialchars($row["famous_track"]) . "</li>";
                echo "<img class='imgStyle' src='" . htmlspecialchars($row["cover_link"]) . "'><br>";
                echo "<audio controls id='audioPlayer' src='" . htmlspecialchars($row["song_link"]) . "'></audio>";
            } else {
                echo "<li>Исполнитель не найден</li>";
            }
            ?>
        </ul>
        <label for="volume">Громкость:</label>
        <input type="range" id="volume" name="volume" min="0" max="1" step="0.01" value="1" oninput="updateVolume()">
        
        <script>
            // JavaScript-функция для обновления громкости аудиоэлемента
            function updateVolume() {
                var volumeControl = document.getElementById("volume");
                var audioPlayer = document.getElementById("audioPlayer");
                audioPlayer.volume = volumeControl.value;
            }
        </script>
            
    
        </section>


        <section>
            <h2>About</h2>
                <input type="hidden" name="pseudonym" value="<?php echo 'dd';?>">
                <textarea name="about_text" rows="10" cols="100"><?php 
            $defaultAboutText = ""; 
               $resultAbout = $mysqli->query("SELECT about FROM artists WHERE pseudonym ='ййуу'");
               if ($resultAbout && $rowAbout = $resultAbout->fetch_assoc()) 
               {
                  $defaultAboutText = htmlspecialchars($rowAbout["about"]);
                  echo $defaultAboutText;
               }?></textarea>

        </section>
    </body>
    </html>
    