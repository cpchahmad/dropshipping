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
    <h1>Profile</h1>
{{--    <p>@if(session()->has('data')) {{session('data')['name']}} @endif</p>--}}
<a href="{{route('logout')}}" type="button">Logout</a>
</body>
</html>
