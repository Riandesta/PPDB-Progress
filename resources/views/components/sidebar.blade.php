<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            Navigation
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">

                <ul class="nav nav-main">
                    <li>
                        <a href="/Dashboard">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-parent">
                        <a href="#">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Pendaftaran</span>
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a href="{{ route('calonSiswa.show') }}">
                                    Calon Siswa
                                </a>
                            </li>


                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a href="#">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>Panitia</span>
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a href="{{ route('panitia.index') }}">
                                    Data Panitia
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-parent">
                        <a href="#">
                            <i class="fa fa-building" aria-hidden="true"></i>
                            <span>Manajemen Kelas</span>
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a href="{{ route('jurusan.index') }}">
                                    <span>Jurusan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kelas.index') }}">
                                    <span>Kelas</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav">
                        <a href="{{ route('post.index') }}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Post</span>
                        </a>
                    </li>


                </ul>
            </nav>

            <hr class="separator" />



            <hr class="separator" />


        </div>

        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>


    </div>

</aside>
