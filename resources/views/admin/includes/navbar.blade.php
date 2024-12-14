<nav class="app-header navbar navbar-expand bg-dark" data-bs-theme="dark">
    <div class="container-fluid" style="background-color: rgba(0, 0, 0, 0.05);">
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link text-white" data-lte-toggle="sidebar" href="{{ url('/') }}" role="button">Alert-bot | Панель администратора</a> </li>
        </ul>
        @if (auth()->check())
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-menu me-5"> <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown"> <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="user-footer">
                            <form action="{{ url('/logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link px-2 text-white d-flex">Выйти</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    </div>
</nav>
