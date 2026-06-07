<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzer anlegen</title>

    <style>
        body{
            font-family: Arial;
            background: #f5f5f5;
            padding: 40px;
        }

.container{
    width: 100%;
    max-width: 500px;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-sizing: border-box;
}

        input{
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        button{
            padding: 12px 20px;
            background: blue;
            color: white;
            border: none;
            margin-top: 20px;
        }

        h2{
            margin-bottom: 30px;
        }
        @media (max-width: 768px){

    body{
        padding: 15px;
    }

    .container{
        padding: 20px;
    }

    h2{
        font-size: 24px;
    }

    button{
        width: 100%;
    }
}
    </style>
</head>

<body>
@if(session('success'))
    <div style="
        background: #d4edda;
        color: #155724;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    ">
        {{ session('success') }}
    </div>
@endif
<div class="container">

    <h2>Benutzerverwaltung / Neuen Benutzer anlegen</h2>

    <form method="POST" action="/admin/benutzer-speichern">

        @csrf

        <div>
            <label>Vorname</label>
            <input type="text" name="vorname">
        </div>

        <br>

        <div>
            <label>Nachname</label>
            <input type="text" name="nachname">
        </div>

        <br>

        <div>
            <label>E-Mail-Adresse</label>
            <input type="email" name="email">
        </div>

        <button type="submit">
            Benutzer erstellen
        </button>

    </form>

</div>

</body>
</html>