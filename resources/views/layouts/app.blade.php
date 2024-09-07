<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <!-- Bootstrap CSS -->
    <!-- Include Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    @stack('styles')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
        </div>
    </nav>

    <!-- Main content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center py-4">
        &copy; {{ date('Y') }} Customer Management
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
