<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Web Translation app, using Google Translate's API...">

    <title>Login - Word Breaker</title>

    <link rel="icon" type="image/x-icon" href="favicon.ico">
    
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
            font-size: 24px;
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
        .page .content .input > input {
            width: 250px;
            height: 40px;
            padding: 5px 10px;
            margin: 10px 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            -webkit-box-shadow: 0 0 0 30px white inset;
            font-size: 16px;
            letter-spacing: 2px;
        }
        .page .content .btn {
            display: flex;
            width: 250px;
            padding: 5px 10px;
            height: 40px;
            margin: 10px 25px;
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
        .page .content .btn > div {
            margin: auto;
        }
        .flex {
            flex: 1;
        }
    </style>
</head>
<body>

    <div class="page">

        <div class="header">

            <a class="logo" href="">
                <div>WORD BREAKER</div>
            </a>
            
        </div>

        <div class="content">

            <div class="form">

                <div class="input">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="text" placeholder="Email" onkeydown="focusNext(event)">
                </div>

                <div class="input">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Password" onkeydown="submit(event)">
                </div>

                <div class="btn" onclick="login()">
                    <div>LOGIN</div>
                </div>

            </div>

        </div>

    </div>

    <script>

        function focusNext($ev)
        {
            (((typeof $ev.which === "number") ? $ev.which : $ev.keyCode) == 13) ? run(document.getElementById('password').focus()) : run();
        }

        function submit($ev)
        {
            (((typeof $ev.which === "number") ? $ev.which : $ev.keyCode) == 13) ? run(login) : run();
        }

        function login()
        {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    // console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    if (response.data)
                    {
                        window.location = "";
                    }
                }
            };
            xhttp.open('POST', 'api/login');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send(JSON.stringify({'data':{'email':email,'password':password}}));
        }

        function run(callback = function(){})
        {
            callback();
        }

    </script>
</body>
</html>