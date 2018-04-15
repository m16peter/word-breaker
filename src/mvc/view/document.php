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
        /* css */
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
        .layout-row {
            display: flex;
        }
        .layout-column {
            display: flex;
            flex-direction: column;
        }
        .flex {
            flex: 1;
        }
        .page {
            width: 100%;
            height: 100%;
        }
        /* header */
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
        /* content */
        .page .content {
            display: flex;
            width: 100%;
            height: calc(100% - 51px);
            overflow-y: auto;
        }
        
    </style>
</head>
<body>

    <main class="page">

        <div class="header">

            <div class="flex"></div>

            <a class="logo" href="<?php echo URL; ?>">
                <div>WORD BREAKER</div>
            </a>

            <div class="flex"></div>
            
        </div>

        <div class="content"></div>

    </main>

    <script>

        function init()
        {

        }

        init();

    </script>
</body>
</html>
