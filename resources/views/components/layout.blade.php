<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ config('app.name') }}</title>

    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="page">
        <!-- Sidebar -->
        <x-side-bar></x-side-bar>
        
        <div class="page-wrapper">
            <!-- Page header -->
            <x-header></x-header>
            
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    {{ $slot }}
                </div>
            </div>
            
            <x-footer></x-footer>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    
    <!-- Libs JS -->
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/nouislider/dist/nouislider.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/litepicker/dist/litepicker.js') }}" defer></script>
    <script src="{{ asset('dist/libs/tom-select/dist/js/tom-select.base.min.js') }}" defer></script>

    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
