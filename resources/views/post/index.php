<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Post</title>
</head>
<body>
<div style="margin: 50px">
    <h2>This is index page</h2>
    <div>
       <h4> Store</h4>
        <form action="/posts" method="post">
            <input type="text" placeholder="value" name="title">
            <input type="submit">
        </form>
    </div>
    <div>
        <h4>This is title</h4>
        <div>
            <?php
            if (isset($_SESSION['message'])){
                echo $_SESSION['message'];
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>