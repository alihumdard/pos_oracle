<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 px-4">
    <!-- Page Title -->
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ms-auto align-items-center">

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-2">
            <a class="nav-link position-relative" href="#" id="alertsDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill fs-5"></i>
                <!-- Counter - Alerts -->
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                      style="font-size: 0.65rem;">
                      3+
                </span>
            </a>
            <!-- Dropdown - Alerts -->
            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <li>
                    <h6 class="dropdown-header bg-primary text-white">Alerts Center</h6>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="me-3">
                            <div class="icon-circle bg-primary d-flex justify-content-center align-items-center"
                                 style="width:40px; height:40px; border-radius:50%;">
                                <i class="bi bi-file-earmark-text text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">September 07, 2025</div>
                            <span class="fw-bold">A new monthly report is ready to download!</span>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                </li>
            </ul>
        </li>

        <!-- Divider -->
        <div class="topbar-divider d-none d-sm-block mx-2 border-end"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-lg-inline text-gray-600 small">John Doe</span>
                <img class="img-profile rounded-circle"
                     src="https://placehold.co/60x60/E8E8E8/424242?text=J"
                     style="width: 40px; height: 40px; object-fit: cover;">
            </a>
            <!-- Dropdown - User Information -->
            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <li class="dropdown-header text-center">
                    <h6 class="text-primary fw-bold mb-0">John Doe</h6>
                    <span class="small text-muted">Administrator</span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-person-fill fa-sm fa-fw me-2 text-gray-400"></i>
                        Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-box-arrow-right fa-sm fa-fw me-2 text-gray-400"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
