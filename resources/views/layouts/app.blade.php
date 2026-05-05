<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webplattform</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <header class="p-4 bg-white shadow mb-6">
        <!-- Hier kommt später eure Navigation hin -->
        <h1 class="text-xl font-bold">Dashboard</h1>
    </header>

    <main class="max-w-7xl mx-auto px-4">
        <!-- Hier laden die Dashboards (Admin, Student, Teacher) rein -->
        // button to test the layout   -> fuer die Seiten, die ihr erstellt, z.B. die Test-Seite, damit ihr seht, dass das Layout funktioniert
        @yield('content')
    </main>

</body>
</html>