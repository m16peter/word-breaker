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
        /* Font Awesome SVG Icons: https://fontawesome.com/license */
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
        .page .header .logout {
            display: flex;
            width: 40px;
            height: 40px;
            margin: auto 10px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 100ms ease-out;
        }
        .page .header .logout:hover {
            border: 2px solid #333;
        }
        .page .header .logout > div {
            margin: auto;
        }
        .page .header .logout > div > img {
            margin-top: 2px;
        }
        /* content */
        .page .content {
            display: flex;
            width: 100%;
            height: calc(100% - 51px);
        }
        .page .content > div {
            margin: 0 auto;
        }
         .page .content > div > div {
            display: flex;
            flex-direction: column;
            margin: auto;
        }
        .page .content > div .title {
            display: flex;
            height: 50px;
            width: 250px;
            margin: 50px auto;
            font-size: 24px;
        }
        .page .content > div .title > div {
            margin: auto 0;
        }
        /* left-side */
        .page .content .left-side {
            border-right: 1px dashed #333;
        }
        .page .content .left-side .title {
            margin: 60px auto 50px auto;
        }
        .page .content > div .new-document {
            display: flex;
            width: 250px;
            height: 250px;
            border: 1px solid #aaa;
            margin: 0 auto;
            border-radius: 5px;
            background: #fafafa;
            cursor: pointer;
            transition: all 100ms ease-in-out;
        }
        .page .content > div .new-document > div {
            display: flex;
            flex-direction: column;
            margin: auto;
        }
        .page .content > div .new-document:hover {
            background: #fff;
            box-shadow: 0px 0px 15px 1px rgba(0,0,0, .75);
        }
        /* right-side */
        .page .content .right-side {
            overflow-y: auto;
            opacity: 0;
            transition: all 300ms ease-in-out;
        }
        .page .content .right-side .documents {
            margin-bottom: 50px;
        }
        .page .content > div .document {
            position: relative;
            display: flex;
            width: 250px;
            height: 50px;
            border: 1px solid #aaa;
            margin: 10px auto;
            border-radius: 5px;
            background: #fafafa;
            cursor: pointer;
            transition: all 100ms ease-in-out;
        }
        .page .content > div .document:hover {
            background: #fff;
            box-shadow: 0px 0px 15px 1px rgba(0,0,0, .75);
        }
        .page .content > div .document-model {
            display: none;
        }
        .page .content > div .document > div {
            width: 100%;
            display: flex;
        }
        .page .content > div .document > div > span {
            margin: auto;
        }
        .page .content > div .document .delete {
            position: absolute;
            display: flex;
            width: 50px;
            height: 50px;
            background: transparent;
            top: 0;
            right: -50px;
            opacity: 0;
            transition: all 100ms ease-in-out;
        }
        .page .content > div .document .delete img {
            margin: auto;
        }
        .page .content > div .document:hover .delete {
            opacity: 0.25;
        }
        .page .content > div .document .delete:hover {
            opacity: 1;
        }
    </style>
</head>
<body>

    <main class="page">

        <div class="header">

            <div style="width: 64px;"></div>

            <div class="flex"></div>

            <a class="logo" href="<?php echo URL; ?>">
                <div>WORD BREAKER</div>
            </a>

            <div class="flex"></div>

            <div class="logout" onclick="logout()">
                <div>
                    <img src="icons/power-off.svg" alt="Logout">
                </div>
            </div>
            
        </div>

        <div class="content">

            <div class="left-side flex">

                <div class="title">
                    <div>New Document:</div>
                </div>

                <!-- Add new document -->
                <div class="new-document" onclick="createDocument()">
                    <div>
                        <img src="icons/plus.svg" alt="New Document">
                    </div>
                </div>

            </div>

            <div id="right-side" class="right-side flex">

                <div class="title">
                    <div>Select Document:</div>
                </div>

                <div class="documents">

                    <!-- Document model -->
                    <div id="document-model" class="document document-model">

                        <div onclick="selectDocument(event)" data-node="parent">
                            <span data-node="child"></span>
                        </div>

                        <div data-node="parent" class="delete" onclick="deleteDocument(event)">
                            <img data-node="child" src="icons/delete.svg" alt="Delete Document">
                        </div>

                    </div>

                    <!-- List of all documents -->
                    <div id="list"></div>

                </div>

            </div>

        </div>

    </main>

    <script>

        var new_document_name;
        var document_model;
        var right_side;
        var list;
        var cloned_item;
        var selectedId;
        var selectedElement;

        function init()
        {
            new_document_name = document.getElementById('new-document-name');
            document_model = document.getElementById('document-model');
            right_side = document.getElementById('right-side');
            list = document.getElementById('list');

            selectedId = 0;
            selectedElement = undefined;

            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    // console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    for (var i = 0; i < response.data.length; i++)
                    {
                        addDocument(
                            response.data[i].json_id,
                            response.data[i].json_name
                        );
                    }

                    right_side.style.opacity = (list.children.length === 0) ? 0 : 1;
                }
            };
            xhttp.open('POST', 'api/getDocuments');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send();
        }

        function addDocument(id, name)
        {
            cloned_item = document_model.cloneNode(true);
            cloned_item.children[0].children[0].innerHTML = name;
            cloned_item.classList.remove('document-model');
            cloned_item.setAttribute('id', id);
            list.prepend(cloned_item);
        }

        function createDocument()
        {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    // console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    if (response.data)
                    {
                        addDocument(
                            response.data.json_id,
                            response.data.json_name
                        );
                        right_side.style.opacity = (list.children.length === 0) ? 0 : 1;
                    }
                }
            };
            xhttp.open('POST', 'api/newDocument');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send();
        }

        function selectDocument($ev)
        {
            select($ev);
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    // console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    if (response.data)
                    {
                        window.location = 'document';
                    }
                }
            };
            xhttp.open('POST', 'api/selectDocument');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send(
                JSON.stringify(
                    {
                        'data': selectedElement.getAttribute('id')
                    }
                )
            );
        }

        function select($ev)
        {
            console.log($ev.target);

            if ($ev.target.getAttribute('data-node') === 'child')
            {
                selectedElement = $ev.target.parentNode.parentNode;
            }
            else
            {
                selectedElement = $ev.target.parentNode;
            }
        }

        function deleteDocument($ev)
        {
            select($ev);

            if (confirm('Do you really want to delete "' + selectedElement.children[0].children[0].innerText + '"? This action cannot be reversed!'))
            {
                var xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function()
                {
                    if (this.readyState == 4 && this.status == 200)
                    {
                        // console.log(xhttp.responseText);
                        const response = JSON.parse(xhttp.responseText);

                        if (response.data)
                        {
                            list.removeChild(selectedElement);
                            right_side.style.opacity = (list.children.length === 0) ? 0 : 1;
                        }
                    }
                };
                xhttp.open('POST', 'api/deleteDocument');
                xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
                xhttp.send(
                    JSON.stringify(
                        {
                            'data': selectedElement.getAttribute('id')
                        }
                    )
                );
            }
        }

        function logout()
        {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    // console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    if (response.data)
                    {
                        window.location = '';
                    }
                }
            };
            xhttp.open('POST', 'api/logout');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send();
        }

        init();

    </script>
</body>
</html>
