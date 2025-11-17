<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset('theme/assets/css/styles.css')}}" rel="stylesheet"/>
    <link href="{{asset('theme/assets/vendors/keenicons/styles.bundle.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('theme/assets/vendors/flatpickr/flatpickr.min.css')}}">
    <link href="{{asset('theme/assets/vendors/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>
    <script src="{{asset('theme/assets/vendors/ktui/ktui.min.js')}}"></script>
    <script src="{{asset('theme/assets/js/core.bundle.js')}}"></script>
    <script src="{{asset('theme/assets/vendors/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('theme/assets/vendors/flatpickr/flatpickr.js')}}"></script>
    <script src="{{asset('theme/assets/vendors/flatpickr/id.js')}}"></script>
    <!--suppress JSConstantReassignment -->
    <script>
        window.API_BASE = "{{ $apiBaseUrl }}";
    </script>
    <script src="{{ asset('js/app-axios.js') }}"></script>
    <script src="{{ asset('js/page-helpers.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>

</head>
<body class="bg-[#F9FAFB] text-[#003D73]">

@include('layouts.partials.navbar')

<main class="p-6">
    @yield('content')
</main>


@stack('scripts')
</body>
</html>
