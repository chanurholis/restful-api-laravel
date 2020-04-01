@include('partials.header')

<body class="bg-primary">
    <div class="col-8 m-auto">
        <div class="card text-center mt-5 rounded-0">
            <div class="card-header font-weight-bold">
                {{ $title }}
            </div>
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>
</body>

@include('partials.footer')