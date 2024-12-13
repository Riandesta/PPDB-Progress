<aside id="sidebar" class="sidebar">
    <div class="sidebar-content">
        <nav class="menu">
            <ul class="sidebar-nav">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Pendaftaran -->
                <li class="nav-item {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-plus"></i>
                        <span>Pendaftaran</span>
                        <i class="fas fa-angle-left right"></i>
                    </a>
                    <ul class="nav nav-treeview sub-menu">
                        <li>
                            <a href="{{ route('pendaftaran.create') }}" class="nav-link">
                                <i class="far fa-circle"></i>
                                <span>Form Pendaftaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pendaftaran.index') }}" class="nav-link">
                                <i class="far fa-circle"></i>
                                <span>Data Pendaftar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Administrasi -->
                <li class="nav-item {{ request()->routeIs('administrasi.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-money-bill"></i>
                        <span>Administrasi</span>
                        <i class="fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview sub-menu">
                        <li>
                            <a href="{{ route('administrasi.index') }}" class="nav-link">
                                <i class="fas fa-cash-register"></i>
                                <span>Data Pembayaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('administrasi.laporan') }}" class="nav-link">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Laporan Keuangan</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Manajemen Kelas -->
                <li class="nav-item {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-door-open"></i>
                        <span>Pengelolaan Kelas</span>
                        <i class="fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview sub-menu">
                        <li>
                            <a href="{{ route('kelas.index') }}" class="nav-link">
                                <i class="far fa-circle"></i>
                                <span>Data Kelas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelas.distribusi') }}" class="nav-link">
                                <i class="fas fa-random"></i>
                                <span>Distribusi Kelas</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Master Data -->
                @role('admin')
                <li class="nav-item {{ request()->routeIs('master.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-database"></i>
                        <span>Master Data</span>
                        <i class="fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview sub-menu">
                        <li>
                            <a href="{{ route('jurusan.index') }}" class="nav-link">
                                <i class="far fa-circle"></i>
                                <span>Data Jurusan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tahun-ajaran.index') }}" class="nav-link">
                                <i class="far fa-circle"></i>
                                <span>Tahun Ajaran</span>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="{{ route('kuota.index') }}" class="nav-link"> --}}
                                <i class="fas fa-users"></i>
                                <span>Kuota PPDB</span>
                            </a>
                        </li>
                    </ul>   
                </li>
                @endrole

                <!-- Laporan -->
                <li class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                        <i class="fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview sub-menu">
                        <li>
                            <a href="{{ route('laporan.pendaftaran') }}" class="nav-link">
                                <i class="fas fa-file-signature"></i>
                                <span>Laporan Pendaftaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('laporan.keuangan') }}" class="nav-link">
                                <i class="fas fa-file-invoice"></i>
                                <span>Laporan Keuangan</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
