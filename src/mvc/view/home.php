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
        .page .content {
            display: flex;
            width: 100%;
            height: calc(100% - 51px);
        }
        .page .content .left-side {
            border-right: 1px dashed #333;
        }
        .page .content .right-side {
            overflow-y: auto;
            opacity: 0;
            transition: all 300ms ease-in-out;
        }
        .page .content .right-side .documents {
            margin-bottom: 50px;
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
        .page .content > div > div {
            margin: 0 auto;
        }
        .page .content > div .new-document {
            display: flex;
            width: 250px;
            height: 250px;
            border: 1px solid #aaa;
            margin: auto;
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
        .page .content > div {
            margin: 0 auto;
        }
        .page .content > div > div {
            display: flex;
            flex-direction: column;
            margin: auto;
        }
        .page .content > div .document {
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
        .page .content > div .document > div {
            margin: auto;
        }
        .page .content > div .create-new-document {
            display: none;
        }
        .page .content > div .document-model {
            display: none;
        }
        .flex {
            flex: 1;
        }
    </style>
</head>
<body>

    <div class="page">

        <div class="header">

            <div style="width: 64px;"></div>

            <div class="flex"></div>

            <a class="logo" href=''>
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
                    <div id="document-model" class="document document-model" onclick="deleteDocument(event)" data-node="parent">
                        <div data-node="child"></div>
                    </div>

                    <!-- List of all documents -->
                    <div id="list"></div>

                </div>

            </div>

        </div>

    </div>

    <script>

        var new_document_name;
        var document_model;
        var right_side;
        var list;
        var cloned_item;

        function init()
        {
            new_document_name = document.getElementById('new-document-name');
            document_model = document.getElementById('document-model');
            right_side = document.getElementById('right-side');
            list = document.getElementById('list');

            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    // console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    for (var i = 0; i < response['data'].length; i++)
                    {
                        addDocument(
                            response['data'][i]['json_id'],
                            response['data'][i]['json_name'],
                            response['data'][i]['source'],
                            response['data'][i]['target']
                        );
                    }

                    right_side.style.opacity = (list.children.length === 0) ? 0 : 1;
                }
            };
            xhttp.open('POST', 'api/getDocuments');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send();
        }

        function addDocument(id, name, source, target)
        {
            cloned_item = document_model.cloneNode(true);
            cloned_item.childNodes[1].innerHTML = name;
            cloned_item.classList.remove('document-model');
            cloned_item.removeAttribute('id');
            cloned_item.setAttribute('data-id', id);
            cloned_item.setAttribute('data-source', source);
            cloned_item.setAttribute('data-target', target);
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
                            response['data']['json_id'],
                            response['data']['json_name'],
                            response['data']['source'],
                            response['data']['target']
                        );
                        right_side.style.opacity = (list.children.length === 0) ? 0 : 1;
                    }
                }
            };
            xhttp.open('POST', 'api/newDocument');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send();
        }

        function deleteDocument($ev)
        {
            var element = $ev.target;
            var xhttp = new XMLHttpRequest();

            if (element.getAttribute('data-node') === 'child')
            {
                element = element.parentNode;
            }

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    // console.log(xhttp.responseText);
                    const response = JSON.parse(xhttp.responseText);

                    if (response.data)
                    {
                        element.parentNode.removeChild(element);
                        right_side.style.opacity = (list.children.length === 0) ? 0 : 1;
                    }
                }
            };
            xhttp.open('POST', 'api/deleteDocument');
            xhttp.setRequestHeader('Content-type', 'application/json;charset=utf-8');
            xhttp.send(JSON.stringify({"data": element.getAttribute('data-id')}));
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
