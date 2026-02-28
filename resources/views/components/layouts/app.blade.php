@props(['title' => 'Звіти по пальному'])

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @fluxAppearance
</head>
<body class="min-h-screen bg-zinc-800 antialiased">
    <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left" />
        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="home" href="/">Головна</flux:navbar.item>
            <flux:navbar.item href="/company-info">Дані компанії</flux:navbar.item>
            <flux:navbar.item href="#">ТЗ та спецтехніка</flux:navbar.item>
            <flux:navbar.item href="#">Внесення даних по пальному</flux:navbar.item>
            <flux:navbar.item href="#">Звіт по використанню палива</flux:navbar.item>
        </flux:navbar>
    </flux:header>
    <flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header class="justify-end">
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2"/>
        </flux:sidebar.header>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="/">Головна</flux:sidebar.item>
            <flux:sidebar.item href="#">Дані компанії</flux:sidebar.item>
            <flux:sidebar.item href="#">ТЗ та спецтехніка</flux:sidebar.item>
            <flux:sidebar.item href="#">Внесення даних по пальному</flux:sidebar.item>
            <flux:sidebar.item href="#">Звіт по використанню палива</flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>
    <flux:main container>
        {{ $slot }}
    </flux:main>

    @livewireScripts
    @fluxScripts
</body>
</html>
