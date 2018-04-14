<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Web Translation app, using Google Translate's API...">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <title>Word Breaker</title>

    <link rel="apple-touch-icon" sizes="180x180" href="icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icons/favicon-16x16.png">
    <link rel="manifest" href="icons/site.webmanifest">
    <link rel="shortcut icon" href="icons/favicon.ico">
    
    <style>
        * {
            outline: none;
            text-decoration: none;
            color: #333;
            margin: 0;
        }
        html, body {
            display: flex;
            width: 100%;
            height: 100%;
            background: #fafafa;
            font-family: consolas;
        }
        .page {
            width: 100%;
            height: 100%;
        }
        .page .header {
            display: flex;
            width: 100%;
            height: 50px;
            background: #fff;
            border-bottom: 1px solid #ccc;
        }
        .page .header .logo {
            display: flex;
            width: 250px;
            height: 50px;
            font-size: 32px;
        }
        .page .header .logo > div {
            margin: auto;
        }
        .page .content {
            display: flex;
            width: 100%;
            height: calc(100% - 101px);
        }
        .page .content .form {
            margin: auto;
        }
        .page .content .input {
            display: flex;
            margin: 0 20px;
        }
        .page .content .input > label {
            display: none;
        }
        .page .content .input > span {
            margin: auto;
        }
        .page .content .input > input {
            width: 250px;
            height: 40px;
            padding: 5px 10px;
            margin: 10px 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 0 30px white inset;
            font-size: 16px;
            letter-spacing: 2px;
        }
        .page .content .btn {
            display: flex;
            width: 250px;
            padding: 5px 10px;
            height: 40px;
            margin: 30px 25px 10px 25px;
            border: 1px solid #333;
            cursor: pointer;
            border-radius: 5px;
            letter-spacing: 2px;
            background: #fafafa;
            font-size: 18px;
        }
        .page .content .input > input:hover,
        .page .content .input > input:focus {
            border: 1px solid #333;
        }
        .page .content .btn:hover {
            box-shadow: 0px 0px 15px 1px rgba(0,0,0, .75);
            background: #fff;
        }
        .page .content .btn > img {
            margin: auto;
        }
        .page .content .form .error > input {
            border: 1px solid red;
        }
        .flex {
            flex: 1;
        }
    </style>
</head>
<body>

    <main class="page">

        <div class="header">

            <div class="flex"></div>

            <a class="logo" href="">
                <div>WORD BREAKER</div>
            </a>

            <div class="flex"></div>
            
        </div>

        <div class="content">

            <div class="form">

                <div class="input">
                    <input id="email" name="email" aria-label="Email" type="text" placeholder="Email" onkeydown="focusNext(event)">
                </div>

                <div class="input">
                    <input id="password" name="password" aria-label="Password" type="password" placeholder="Password" onkeydown="submit(event)">
                </div>

                <div class="btn" onclick="login()">
                    <img src="icons/login.svg" alt="Login / Register">
                </div>

            </div>

        </div>

    </main>

    <script>

        var email;
        var password;

        function init()
        {
            email = document.getElementById('email');
            password = document.getElementById('password');
        }

        function focusNext($ev)
        {
            if (((typeof $ev.which === 'number') ? $ev.which : $ev.keyCode) == 13)
            {
                password.focus();
            }
        }

        function submit($ev)
        {
            if (((typeof $ev.which === 'number') ? $ev.which : $ev.keyCode) == 13)
            {
                login();
            }
        }

        function login()
        {
            var xhttp = new XMLHttpRequest();

            email.parentNode.classList.remove('error');
            password.parentNode.classList.remove('error');

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    switch (response.data)
                    {
                        case 'ok':
                        {
                            window.location = "";
                            break;
                        }
                        case 'user_email':
                        {
                            email.parentNode.classList.add('error');
                            break;
                        }
                        case 'user_password':
                        {
                            password.parentNode.classList.add('error');
                            break;
                        }
                        default: break;
                    }
                }
            };
            xhttp.open('POST', 'api/login');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send(
                JSON.stringify(
                {
                    'data':
                    {
                        'email': email.value,
                        'password': password.value
                    }
                })
            );
        }

        init();

    </script>
</body>
</html>
