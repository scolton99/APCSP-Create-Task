<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Fitness - Login</title>
        <link rel="stylesheet" href="../assets/css/fitness.css" />
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
        <script src="../assets/js/fitness.js"></script>
    </head>
    <body class="main">
    <a class="metro-button" id="login-button" href="../register">Register</a>
    <section id="form" class="main">
            <div id="logo-container"></div>
            <div id="form-container">
                <form onsubmit="login();">
                    <div id="form-1">
                        <h1>Welcome</h1>
                        <input placeholder="username" id="username" type="text" autofocus>
                        <input placeholder="password" id="password" type="password">
                    </div>
                </form>
            </div>
        </section>
    </body>
</html>