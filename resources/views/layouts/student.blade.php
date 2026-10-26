<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Learning Workspace — Nirmaan')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-gray-800 min-h-full flex flex-col font-sans pb-20 md:pb-0 selection:bg-blue-100 selection:text-blue-900">

    <!-- Unified Top Header (Consistent across public and student portal) -->
    @include('partials.header')

    <!-- Flash notifications -->
    @include('partials.flash')

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Unified Footer -->
    @include('partials.footer')

    <!-- Unified Dynamic Mobile Bottom Navigation Bar -->
    @include('partials.bottom-nav')

    @stack('scripts')
</body>
</html>
