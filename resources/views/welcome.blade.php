<!DOCTYPE html>
<html>
<head>
    <title>CueMaster Reserve</title>
</head>
<body>

    <h1>CueMaster Reserve</h1>

    <p>
        Platform reservasi meja billiard berbasis web.
    </p>

    <a href="/login">Login</a>
    
    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

</body>
</html>
