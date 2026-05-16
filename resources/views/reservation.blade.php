<!DOCTYPE html>
<html>
<head>
    <title>Reservasi Meja</title>

    <style>
        body{
            font-family: Arial;
            margin: 20px;
            background-color: #f5f5f5;
        }

        nav{
            background-color: #222;
            padding: 15px;
        }

        nav a{
            color: white;
            margin-right: 15px;
            text-decoration: none;
        }

        .container{
            background-color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
        }

        .table-card{
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        button{
            padding: 8px 15px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<nav>
    <a href="/">Home</a>
    <a href="/customer">Customer</a>
    <a href="/operator">Operator</a>
    <a href="/owner">Owner</a>
    <a href="/reservation">Reservasi</a>
</nav>

<div class="container">

    <h1>Reservasi Meja Billiard</h1>

    <div class="table-card">
        <h3>Meja VIP 1</h3>
        <p>Status: Tersedia</p>
        <button>Reservasi</button>
    </div>

    <div class="table-card">
        <h3>Meja VIP 2</h3>
        <p>Status: Dipakai</p>
        <button>Reservasi</button>
    </div>

    <div class="table-card">
        <h3>Meja Reguler 1</h3>
        <p>Status: Tersedia</p>
        <button>Reservasi</button>
    </div>

</div>

</body>
</html>
