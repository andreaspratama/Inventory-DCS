<!-- Sidebar -->
      <!--
          Sidebar Mini Mode - Display Helper classes

          Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
          Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
              If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

          Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
          Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
          Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
      -->
      <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header -->
        <div class="content-header">
          <!-- Logo -->
          <a class="fw-semibold text-dual" href="index.html">
            <span class="smini-visible">
              <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">DCS</span>
          </a>
          <!-- END Logo -->
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
          <!-- Side Navigation -->
          <div class="content-side">
            <ul class="nav-main">
              @if (auth()->user()->role === 'admin')
                  <li class="nav-main-item">
                    <a class="nav-main-link active" href="{{route('admin.dashboard')}}">
                      <i class="nav-main-link-icon si si-speedometer"></i>
                      <span class="nav-main-link-name">Dashboard</span>
                    </a>
                  </li>
              @else
                  <li class="nav-main-item">
                    <a class="nav-main-link active" href="{{route('sarpra.dashboard')}}">
                      <i class="nav-main-link-icon si si-speedometer"></i>
                      <span class="nav-main-link-name">Dashboard</span>
                    </a>
                  </li>
              @endif
              <li class="nav-main-heading">User Interface</li>
              {{-- HAK AKSES ADMIN --}}
              @if (auth()->user()->role === 'admin')
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-folder"></i>
                    <span class="nav-main-link-name">Unit</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('unit.index')}}">
                        <span class="nav-main-link-name">List</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('unit.create')}}">
                        <span class="nav-main-link-name">Add</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-folder"></i>
                    <span class="nav-main-link-name">Ruang</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('ruang.index')}}">
                        <span class="nav-main-link-name">List</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('ruang.create')}}">
                        <span class="nav-main-link-name">Add</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-folder"></i>
                    <span class="nav-main-link-name">Type</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('type.index')}}">
                        <span class="nav-main-link-name">List</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('type.create')}}">
                        <span class="nav-main-link-name">Add</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-folder"></i>
                    <span class="nav-main-link-name">Assets</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('asets.index')}}">
                        <span class="nav-main-link-name">List</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('asets.create')}}">
                        <span class="nav-main-link-name">Add</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-folder"></i>
                    <span class="nav-main-link-name">User</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('user.index')}}">
                        <span class="nav-main-link-name">List</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('user.create')}}">
                        <span class="nav-main-link-name">Add</span>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif
              {{-- HAK AKSES SARPRA --}}
              @if (in_array(auth()->user()->role, ['sarpra', 'ks']))
                <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-folder"></i>
                    <span class="nav-main-link-name">Assets</span>
                  </a>
                  <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('asets.index')}}">
                        <span class="nav-main-link-name">List</span>
                      </a>
                    </li>
                    @if (auth()->user()->role === 'admin')
                        <li class="nav-main-item">
                          <a class="nav-main-link" href="{{route('asets.create')}}">
                            <span class="nav-main-link-name">Add</span>
                          </a>
                        </li>
                    @endif
                  </ul>
                </li>
              @endif
            </ul>
          </div>
          <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
    </nav>
    <!-- END Sidebar -->