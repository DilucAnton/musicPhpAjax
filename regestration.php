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

        .form-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .form-box {
            width: 100%;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            position: relative;
        }

        .form-box h3 {
            margin-top: 0;
        }

        .form-box form {
            display: flex;
            flex-direction: column;
        }

        .form-box form input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-box form button {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-box form button:hover {
            background-color: #555;
        }

        .form-box .register-btn {
            background-color: transparent;
            border: none;
            color: #007BFF;
            cursor: pointer;
            padding: 0;
            margin-top: 10px;
        }

        .form-box .register-btn:hover {
            text-decoration: underline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: rgba(255, 255, 255, 0.9);
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .error-message {
            color: red;
            margin: 10px 0;
        }

        .success-message {
            color: green;
            margin: 10px 0;
        }
    </style>
    <title>User Authentication</title>
</head>
<body>
    <header>
        <h1>Music Vibes</h1>
        
    </header>

    <section id="content">
        <h2>Login</h2>
        <?php if (isset($_GET['login_error'])): ?>
            <div class="error-message"><?php echo $_GET['login_error']; ?></div>
        <?php endif; ?>
        <div class="form-container">
            <div class="form-box">
                <h3>Login</h3>
                <form action="login.php" method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
                <button class="register-btn" id="openRegisterModal">Create Account</button>
            </div>
        </div>
    </section>

    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeRegisterModal">&times;</span>
            <h2>Register</h2>
            <?php if (isset($_GET['register_error'])): ?>
                <div class="error-message"><?php echo $_GET['register_error']; ?></div>
            <?php elseif (isset($_GET['register_success'])): ?>
                <div class="success-message"><?php echo $_GET['register_success']; ?></div>
            <?php endif; ?>
            <form action="register.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>

    <script>
        const registerModal = document.getElementById('registerModal');
        const openRegisterModal = document.getElementById('openRegisterModal');
        const closeRegisterModal = document.getElementById('closeRegisterModal');

        openRegisterModal.onclick = function() {
            registerModal.style.display = 'block';
        }

        closeRegisterModal.onclick = function() {
            registerModal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == registerModal) {
                registerModal.style.display = 'none';
            }
        }

        
        if (window.location.search.includes('register_error') || window.location.search.includes('register_success')) {
            registerModal.style.display = 'block';
        }
    </script>
</body>
</html>
