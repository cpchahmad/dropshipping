<!
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{Route('set_session')}}" method="post">
        @csrf
        <input type="text" name="name" placeholder="user">
        <br>
        <input type="password" name="password" placeholder="pass">
        <br>
        <button type="submit">Save</button>
    </form>
</body>
</html>
