<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <!-- Logo/Brand -->
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('dashboard') }}">
                <img src="./static/logo.svg" width="110" height="32" alt="Logo" class="navbar-brand-image">
            </a>
        </h1>

        <!-- Toggle Button untuk Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Sidebar -->
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">

                <nav class="sidebar">

                    <ul class="nav flex-column">
                        {{-- Dashboard --}}
                        <li class="nav-item {{ Request::is('dashboard*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>

                        {{-- Pendaftaran --}}
                        <li class="nav-item {{ Request::is('pendaftaran*') ? 'active' : '' }}">
                            <a href="#pendaftaranSubmenu" data-toggle="collapse" class="nav-link dropdown-toggle">
                                <i class="fas fa-user-plus"></i> Pendaftaran
                            </a>
                            <ul class="collapse {{ Request::is('pendaftaran*') ? 'show' : '' }}"
                                id="pendaftaranSubmenu">
                                <li><a href="{{ route('pendaftaran.index') }}">Data Pendaftar</a></li>
                                <li><a href="{{ route('pendaftaran.create') }}">Tambah Pendaftar</a></li>
                            </ul>
                        </li>

                        {{-- Jurusan --}}
                        <li class="nav-item {{ Request::is('jurusan*') ? 'active' : '' }}">
                            <a href="{{ route('jurusan.index') }}" class="nav-link">
                                <i class="fas fa-graduation-cap"></i> Jurusan
                            </a>
                        </li>

                        {{-- Kelas --}}
                        <li class="nav-item {{ Request::is('kelas*') ? 'active' : '' }}"    >
                            <a href="{{ route('kelas.index') }}" class="nav-link">
                                <i class="fas fa-chalkboard"></i> Kelas
                            </a>
                        </li>

                        {{-- Pembayaran --}}
                        <li class="nav-item {{ Request::is('administrasi*') ? 'active' : '' }}">
                            <a href="#pembayaranSubmenu" data-toggle="collapse" class="nav-link dropdown-toggle">
                                <i class="fas fa-money-bill"></i> Pembayaran
                            </a>
                            <ul class="collapse {{ Request::is('administrasi*') ? 'show' : '' }}"
                                id="pembayaranSubmenu">
                                <li><a href="{{ route('administrasi.pembayaran') }}">Data Pembayaran</a></li>
                                <li><a href="{{ route('administrasi.laporan') }}">Laporan Keuangan</a></li>
                            </ul>
                        </li>

                        @if (auth()->user()->hasRole('admin'))
                            <li class="nav-item">
                                <a href="{{ route('tahun-ajaran.index') }}" class="nav-link">
                                    <i class="fas fa-calendar"></i> Tahun Ajaran
                                </a>
                            </li>
                        @endif
                    </ul>
        </div>
    </div>
</aside>
