<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <!-- Safari/iOS Status Bar and Navigation Colors -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#1c1917" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#fafaf9" media="(prefers-color-scheme: light)">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Apply the saved theme before first paint to avoid a flash of the wrong theme -->
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('vueuse-color-scheme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var isDark = stored === 'dark' || ((!stored || stored === 'auto') && prefersDark);
                document.documentElement.classList.toggle('dark', isDark);
            } catch (e) {}
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=fraunces:400,500,600|instrument-sans:400,500,600,700&display=swap">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="bg-stone-50 font-sans text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100">
@inertia
</body>
</html>
