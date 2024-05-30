<?php include 'auth.php'; ?>

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
            background: url('https://images.wallpaperscraft.ru/image/single/zvezdnoe_nebo_lodka_otrazhenie_125803_1920x1080.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            
        }

        header {
            background-color: rgba(255,255,255,0.8);
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
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 5px;
        }

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        section {
            max-width: 800px;
            margin: 2em auto;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #FFFFFF;
            background-color: #333;
            padding: 1em;
            margin: 0;
        }
        
        h2 {
            color: #333;
            padding: 1em;
            margin: 0;
        }

        ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        li {
            margin-bottom: 20px;
            width: 45%; 
            background-color: #f5f5f5;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        li:hover {
            transform: scale(1.05);
        }

        .artist-info {
            padding: 15px;
        }

        .artist-image {
            width: 100%;
            height: 200px; 
            object-fit: cover;
        }

        .menu {
            color: #FFFFFF;
            background-color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 5px;
        }

        .menu:hover {
            background-color: #555;
        }

        li a:hover {
            text-decoration: underline;
        }
        .user-info {
    position: absolute;
    top: 25px;
    right: 25px;
    padding: 6px 10px;
    background-color: rgba(255, 255, 255, 0.8);
    color: #333;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

.user-info:hover {
    background-color: #eee;
}


        
    </style>
    <title>Music Vibes</title>
</head>
<body>
    <header>
    <div class="header-content">
        <h1>Music Vibes</h1>
        <div class="user-info">
            <?php
            
            if (isset($_SESSION['username'])) {
                echo '<div class="user-name">' . htmlspecialchars($_SESSION['username']) . '</div>';
            }
            ?>
        </div>
    </div>
        
        <nav>
            <ul>
                <li><a class="menu" href="index.php">Home</a></li>
                <li><a class="menu" href="regestration.php">Login</a></li>
                <?php
  
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {

                    echo '<li><a class="menu" href="artists.php">View Artists</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <section id="content">
        <h2>All Artists</h2>
        <ul>
            <?php
          
            $mysqli = new mysqli("localhost", "root", "Qq123456", "music");
            // echo htmlspecialchars($_SESSION['username']); 
            
            $result = $mysqli->query("SELECT * FROM artists");

           
            while ($row = $result->fetch_assoc()) {
                echo "<li>
                        
                        <div class='artist-info'>
                            <h3>{$row['pseudonym']}</h3>
                            <a href='artists/{$row['pseudonym']}.php' class='menu'>View Profile</a>
                        </div>
                      </li>";
            }
            ?>
        </ul>
    </section>
</body>
</html>
