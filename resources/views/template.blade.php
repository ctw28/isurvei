<!DOCTYPE html>
@if(!Auth::user())
<html lang="en" data-color="light-purple" data-footer="true" data-override='{"attributes": {"placement": "horizontal","layout": "fluid" ,"color":"light-purple"}, "showSettings":false, "storagePrefix": "starter-project"}'>
@else
@if(session('session_role')->role_aktif->role=="admin_organisasi")

<html lang="en" data-color="light-purple" data-footer="true" data-override='{"attributes": {"placement": "vertical","layout": "fluid" ,"color":"light-purple"}, "showSettings":false, "storagePrefix": "starter-project"}'>
@else
<html lang="en" data-color="light-purple" data-footer="true" data-override='{"attributes": {"placement": "horizontal","layout": "fluid" ,"color":"light-purple"}, "showSettings":false, "storagePrefix": "starter-project"}'>
@endif
@endif

<head>
    @include('parts.head')
</head>

<body>
    <!-- <div id="root"> -->
    <div>
        <div id="nav" class="nav-container d-flex">
            <div class="nav-content d-flex">
                <!-- Logo Start -->
                <div class="logo position-relative">
                    <a href="index.html">
                        <!-- Logo can be added directly -->
                        <img src="{{asset('/')}}logo.png" alt="logo" style="width:70px">
                        <!-- <br><span style="font-size:20px;font-weight: 600;color:white">SI LANNI</span> -->

                        <!-- Or added via css to provide different ones for different color themes -->
                        <!-- <div class="img"></div> -->
                    </a>
                </div>
                <!-- Logo End -->



                <!-- User Menu Start -->
                <div class="user-container d-flex">
                    <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="profile" alt="profile" src="{{asset('/')}}icon.jpeg" width="50px" />
                        <div class="name">
                            @if(!Auth::user())
                            Mitra
                            @else
                            @if(session('session_role')->role_aktif->role=="admin_organsasi")
                            Administrator
                            @elseif(session('session_role')->role_aktif->role=="mahasiswa")
                            {{Auth::user()->userMahasiswa->mahasiswa->dataDiri->nama_lengkap}}
                            @elseif(session('session_role')->role_aktif->role=="pegawai")
                            {{Auth::user()->userPegawai->pegawai->dataDiri->nama_lengkap}}
                            @endif
                            @endif
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end user-menu wide">
                        <div class="row ms-0 me-0">
                            <div class="col-12 ps-1 pe-1">
                                <label>Login Sebagai</label> <br>
                                @php
                                if(Auth::user()) {
                                @endphp

                                <select class="form-select my-3" onchange="loginAs(event)">
                                    <option value="default">{{Auth::user()->userRole->role->nama_role}} (Default)</option>
                                    @php
                                    $selected = '';
                                    if(!empty(session('session_role')->role_aktif->detail))
                                    @endphp
                                </select>

                                @php
                                }
                                @endphp

                                <p>Atau</p>
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#">
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary align-items-center w-100">Logout</button>
                                            </form>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- User Menu End -->

                <!-- Icons Menu Start -->
                <ul class="list-unstyled list-inline text-center menu-icons">

                    <li class="list-inline-item">
                        <a href="#" id="pinButton" class="pin-button">
                            <i data-cs-icon="lock-on" class="unpin" data-cs-size="18"></i>
                            <i data-cs-icon="lock-off" class="pin" data-cs-size="18"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" id="colorButton">
                            <i data-cs-icon="light-on" class="light" data-cs-size="18"></i>
                            <i data-cs-icon="light-off" class="dark" data-cs-size="18"></i>
                        </a>
                    </li>

                </ul>
                <!-- Icons Menu End -->

                <!-- Menu Start -->
                <div class="menu-container flex-grow-1">
                    <ul id="menu" class="menu">
                        @if(!Auth::user())

                        @include('parts/menu-mitra')
                        @else
                        @if(session('session_role')->role_aktif->role=="admin_organisasi")
                        @include('parts/menu-admin')
                        @elseif(session('session_role')->role_aktif->role =="mahasiswa")
                        @include('parts/menu-user')
                        @elseif(session('session_role')->role_aktif->role =="pembimbing")
                        @include('parts/menu-user')
                        @endif
                        @endif


                    </ul>
                </div>
                <!-- Menu End -->

                <!-- Mobile Buttons Start -->
                <div class="mobile-buttons-container">
                    <!-- Scrollspy Mobile Button Start -->
                    <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
                        <i data-cs-icon="menu-dropdown"></i>
                    </a>
                    <!-- Scrollspy Mobile Button End -->

                    <!-- Scrollspy Mobile Dropdown Start -->
                    <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
                    <!-- Scrollspy Mobile Dropdown End -->

                    <!-- Menu Button Start -->
                    <a href="#" id="mobileMenuButton" class="menu-button">
                        <i data-cs-icon="menu"></i>
                    </a>
                    <!-- Menu Button End -->
                </div>
                <!-- Mobile Buttons End -->
            </div>
            <div class="nav-shadow"></div>
        </div>

        <main>
            <div class="container">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7">
                            <h1 class="mb-0 pb-0 display-4" id="title">{{$title}}</h1>
                            <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                                <ul class="breadcrumb pt-0">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                </ul>
                            </nav>
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                @yield('content')
                <!-- Content End -->
            </div>
        </main>
        <!-- Layout Footer Start -->
        <footer>
            <div class="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <p class="mb-0 text-muted text-medium">Colored Strategies 2021</p>
                        </div>
                        <div class="col-sm-6 d-none d-sm-block">
                            <ul class="breadcrumb pt-0 pe-0 mb-0 float-end">
                                <li class="breadcrumb-item mb-0 text-medium">
                                    <a href="https://1.envato.market/BX5oGy" target="_blank" class="btn-link">Review</a>
                                </li>
                                <li class="breadcrumb-item mb-0 text-medium">
                                    <a href="https://1.envato.market/BX5oGy" target="_blank" class="btn-link">Purchase</a>
                                </li>
                                <li class="breadcrumb-item mb-0 text-medium">
                                    <a href="https://acorn-html-docs.coloredstrategies.com/" target="_blank" class="btn-link">Docs</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Layout Footer End -->
    </div>
    <!-- Theme Settings Modal Start -->
    <div class="modal fade modal-right scroll-out-negative" id="settings" data-bs-backdrop="true" tabindex="-1" role="dialog" aria-labelledby="settings" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable full" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Theme Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="scroll-track-visible">
                        <div class="mb-5" id="color">
                            <label class="mb-3 d-inline-block form-label">Color</label>
                            <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="light-blue" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="blue-light"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">LIGHT BLUE</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-blue" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="blue-dark"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">DARK BLUE</span>
                                    </div>
                                </a>
                            </div>

                            <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="light-red" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="red-light"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">LIGHT RED</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-red" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="red-dark"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">DARK RED</span>
                                    </div>
                                </a>
                            </div>

                            <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="light-green" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="green-light"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">LIGHT GREEN</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-green" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="green-dark"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">DARK GREEN</span>
                                    </div>
                                </a>
                            </div>

                            <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="light-purple" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="purple-light"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">LIGHT PURPLE</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-purple" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="purple-dark"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">DARK PURPLE</span>
                                    </div>
                                </a>
                            </div>

                            <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="light-pink" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="pink-light"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">LIGHT PINK</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-pink" data-parent="color">
                                    <div class="card rounded-md p-3 mb-1 no-shadow color">
                                        <div class="pink-dark"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">DARK PINK</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="mb-5" id="navcolor">
                            <label class="mb-3 d-inline-block form-label">Override Nav Palette</label>
                            <div class="row d-flex g-3 justify-content-between flex-wrap">
                                <a href="#" class="flex-grow-1 w-33 option col" data-value="default" data-parent="navcolor">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">DEFAULT</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-33 option col" data-value="light" data-parent="navcolor">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-secondary figure-light top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">LIGHT</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-33 option col" data-value="dark" data-parent="navcolor">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-muted figure-dark top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">DARK</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="mb-5" id="placement">
                            <label class="mb-3 d-inline-block form-label">Menu Placement</label>
                            <div class="row d-flex g-3 justify-content-between flex-wrap">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="horizontal" data-parent="placement">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">HORIZONTAL</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="vertical" data-parent="placement">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary left"></div>
                                        <div class="figure figure-secondary right"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">VERTICAL</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="mb-5" id="behaviour">
                            <label class="mb-3 d-inline-block form-label">Menu Behaviour</label>
                            <div class="row d-flex g-3 justify-content-between flex-wrap">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="pinned" data-parent="behaviour">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary left large"></div>
                                        <div class="figure figure-secondary right small"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">PINNED</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="unpinned" data-parent="behaviour">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary left"></div>
                                        <div class="figure figure-secondary right"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">UNPINNED</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="mb-5" id="layout">
                            <label class="mb-3 d-inline-block form-label">Layout</label>
                            <div class="row d-flex g-3 justify-content-between flex-wrap">
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="fluid" data-parent="layout">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">FLUID</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-50 option col" data-value="boxed" data-parent="layout">
                                    <div class="card rounded-md p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary top"></div>
                                        <div class="figure figure-secondary bottom small"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">BOXED</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="mb-5" id="radius">
                            <label class="mb-3 d-inline-block form-label">Radius</label>
                            <div class="row d-flex g-3 justify-content-between flex-wrap">
                                <a href="#" class="flex-grow-1 w-33 option col" data-value="rounded" data-parent="radius">
                                    <div class="card rounded-md radius-rounded p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">ROUNDED</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-33 option col" data-value="standard" data-parent="radius">
                                    <div class="card rounded-md radius-regular p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">STANDARD</span>
                                    </div>
                                </a>
                                <a href="#" class="flex-grow-1 w-33 option col" data-value="flat" data-parent="radius">
                                    <div class="card rounded-md radius-flat p-3 mb-1 no-shadow">
                                        <div class="figure figure-primary top"></div>
                                        <div class="figure figure-secondary bottom"></div>
                                    </div>
                                    <div class="text-muted text-part">
                                        <span class="text-extra-small align-middle">FLAT</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Theme Settings Modal End -->


    <!-- Theme Settings & Niches Buttons Start -->
    <div class="settings-buttons-container">
        <button type="button" class="btn settings-button btn-primary p-0" data-bs-toggle="modal" data-bs-target="#settings" id="settingsButton">
            <span class="d-inline-block no-delay" data-bs-delay="0" data-bs-offset="0,3" data-bs-toggle="tooltip" data-bs-placement="left" title="Settings">
                <i data-cs-icon="paint-roller" class="position-relative"></i>
            </span>
        </button>
    </div>
    <!-- Theme Settings & Niches Buttons End -->

    <!-- Search Modal Start -->
    <div class="modal fade modal-under-nav modal-search modal-close-out" id="searchPagesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 p-0">
                    <button type="button" class="btn-close btn btn-icon btn-icon-only btn-foreground" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ps-5 pe-5 pb-0 border-0">
                    <input id="searchPagesInput" class="form-control form-control-xl borderless ps-0 pe-0 mb-1 auto-complete" type="text" autocomplete="off" />
                </div>
                <div class="modal-footer border-top justify-content-start ps-5 pe-5 pb-3 pt-3 border-0">
                    <span class="text-alternate d-inline-block m-0 me-3">
                        <i data-cs-icon="arrow-bottom" data-cs-size="15" class="text-alternate align-middle me-1"></i>
                        <span class="align-middle text-medium">Navigate</span>
                    </span>
                    <span class="text-alternate d-inline-block m-0 me-3">
                        <i data-cs-icon="arrow-bottom-left" data-cs-size="15" class="text-alternate align-middle me-1"></i>
                        <span class="align-middle text-medium">Select</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Modal End -->
    <script>
        async function loginAs(e) {
            if (confirm(`Anda yakin login sebagai ${e.target.options[e.target.selectedIndex].text}`)) {
                let url = "{{route('login.as',':id')}}"
                url = url.replace(':id', `${e.target.value}`)

                // return alert(url)
                window.open(url, '_self')
            }
        }
    </script>

    @include('parts.js')
</body>

</html>