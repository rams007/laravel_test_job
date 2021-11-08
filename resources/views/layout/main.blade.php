<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Test admin panel</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>
<body>
<header>
    <nav class="bg-dark fixed-top navbar navbar-dark navbar-expand-md">
        <div class="container-fluid"><a class="navbar-brand" href="#">Test job on laravel</a>
            <button class="border-0 btn btn-link navbar-toggler" type="button" id="sidebar"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        </div>
    </nav>
</header>
<div class="d-flex wrapper wrapper-navbar-fixed wrapper-navbar-used">
    <nav role="navigation" class="sidebar sidebar-bg-light" id="navigation">
        <div class="sidebar-menu">
            <div class="sidebar-menu-fixed">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a class="{{ (Request::routeIs('dashboard') ? 'nav-link link-dark active' : 'nav-link link-dark ') }}"
                           href="/">
                            Dashboard
                        </a>
                    </li>
                    @if( Auth::user()->role == 'manager')
                        <li>
                            <a class="{{ (Request::routeIs('employee') ? 'nav-link link-dark active' : 'nav-link link-dark ') }}"
                               href="/employee">
                                Employee
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="{{ (Request::routeIs('employee_records') ? 'nav-link link-dark active' : 'nav-link link-dark ') }}"
                           href="/employee/records">
                            Employee records
                        </a>
                    </li>
                    <li>
                        <a class="nav-link link-dark "
                           href="/logout">
                            Logout
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <main role="main">

            @yield('content')

        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="/js/sidebar.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


@yield('scripts')

</body>
</html>


