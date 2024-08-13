<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} </title>
    <link rel="icon" href="{{ asset('/simsoft-n.png') }}">
    <meta name="description" content="{{ env('APP_DESCRIPTION', 'Laravel description') }}">
    @vite(['resources/css/app.css'])
</head>

<body>
    <header>
        <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span
                        class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Ticket System</span>
                </a>
                <div class="flex items-center lg:order-2">
                <a href="{{ route('filament.auth.login') }}"
                style="background-color: #f89c0c;" 
                class="text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                {{ __('Login') }}</a>
                </div>
            </div>
        </nav>
    </header>

    <section class="bg-white dark:bg-gray-900">
    <div class="flex items-center justify-center py-8 px-4 mx-auto max-w-screen-xl lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="lg:col-span-12 flex justify-center">
            <img src="{{ url('simsoft-n.png') }}" alt="Centered Image">
        </div>
    </div>
    </section>

    <section class="bg-gray-50 dark:bg-gray-900 dark:bg-gray-800">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 ">
            <div class="max-w-screen-lg text-gray-500 sm:text-lg dark:text-gray-400">
                <h2 class="mb-4 text-4xl font-bold text-gray-900 dark:text-white">{{ __('Pourquoi utiliser un SIMSOFT Ticket ?') }}</h2>
                <p class="mb-4 font-light">Les tickets offrent un support efficace, centralisent la communication, rationalisent les workflows, offrent des options en libre-service, surveillent les performances et améliorent l'expérience client.</p>
            </div>
        </div>
    </section>

    <footer class="p-4 bg-gray-50 sm:p-6 dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl">
            <!--<hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />-->
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© {{ date('Y') }} <a
                        href="https://ict.ummi.ac.id" class="hover:underline">{{ config('app.name') }}</a>. All Rights
                    Reserved.
                </span>
                <div class="flex mt-4 sm:justify-center sm:mt-0">
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
