    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu_lateral menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header" >
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="/">
                        <span class="brand-logo">
                             <img src="{{ asset('img/Logoblack.png', env('SECURE_PATH',  null)) }}" alt="" class="img-fluid">
                        </span>
                        <!-- <h2 class="brand-text">Gestão</h2> -->
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="{{ $elementActive == 'Incidentes' ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="/" onclick="link('/')">
                    <i data-feather='home'></i><span class="menu-title text-truncate" data-i18n="deshboard">Incidentes</span></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->
