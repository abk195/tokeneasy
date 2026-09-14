<!-- Navbar -->

<nav
  class="layout-navbar container-fluid navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
  id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="icon-base bx bx-menu icon-md"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item navbar-search-wrapper mb-0">
        <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
          <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
        </a>
      </div>
    </div>

    <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-md-auto">

      <!-- Style Switcher -->
      {{-- <li class="nav-item dropdown me-2 me-xl-0">
        <a
          class="nav-link dropdown-toggle hide-arrow"
          id="nav-theme"
          href="javascript:void(0);"
          data-bs-toggle="dropdown">
          <i class="icon-base bx bx-sun icon-md theme-icon-active"></i>
          <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
          <li>
            <button
              type="button"
              class="dropdown-item align-items-center active"
              data-bs-theme-value="light"
              aria-pressed="false">
              <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>Light</span>
            </button>
          </li>
          <li>
            <button
              type="button"
              class="dropdown-item align-items-center"
              data-bs-theme-value="dark"
              aria-pressed="true">
              <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>Dark</span>
            </button>
          </li>
          <li>
            <button
              type="button"
              class="dropdown-item align-items-center"
              data-bs-theme-value="system"
              aria-pressed="false">
              <span><i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>System</span>
            </button>
          </li>
        </ul>
      </li> --}}
      <!-- / Style Switcher-->


      <!--/ Notification -->
      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a
          class="nav-link dropdown-toggle hide-arrow p-0"
          href="javascript:void(0);"
          data-bs-toggle="dropdown"
          id="userDropdown">
          <div class="avatar avatar-online">
            <img src="../../assets/img/avatars/1.png" alt class="rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li>
            <a class="dropdown-item" href="pages-account-settings-account.html">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{Auth::user()->name }}</h6>
                  <small class="text-body-secondary">{{Auth::user()->getRole()}}</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider my-1"></div>
          </li>
          <li>
            {{-- Issuer pages. /profile and /security are the investor versions, guarded by
                 the investor middleware, which sent issuers to issuer/token-demo. --}}
            <a class="dropdown-item" href="{{ url('/issuer/profile') }}">
              <i class="icon-base bx bx-user icon-md me-3"></i><span>Profile</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ url('/issuer/security') }}">
              <i class="icon-base bx bx-cog icon-md me-3"></i><span>Security</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('logout') }}" target="_blank"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
            >
              <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
            </a>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>

  </div>
</nav>

<!-- / Navbar -->

<script>
// Ensure Bootstrap dropdowns are properly initialized
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Specific fix for user dropdown - more robust approach
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        // Wait a bit to ensure all other scripts have loaded
        setTimeout(function() {
            // Remove any existing event listeners by cloning
            const newUserDropdown = userDropdown.cloneNode(true);
            userDropdown.parentNode.replaceChild(newUserDropdown, userDropdown);

            // Get the fresh reference
            const freshUserDropdown = document.getElementById('userDropdown');
            const dropdownMenu = document.querySelector('.dropdown-menu[aria-labelledby="userDropdown"]');

            // Ensure proper positioning CSS
            if (dropdownMenu) {
                dropdownMenu.style.position = 'absolute';
                dropdownMenu.style.top = '100%';
                dropdownMenu.style.right = '0';
                dropdownMenu.style.zIndex = '1050';
                dropdownMenu.style.minWidth = '200px';
                dropdownMenu.style.marginTop = '0.125rem';
                dropdownMenu.style.display = 'none';
            }

            // Create a completely custom dropdown implementation
            let isDropdownOpen = false;

            // Add click handler
            freshUserDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                if (isDropdownOpen) {
                    // Close dropdown
                    dropdownMenu.style.display = 'none';
                    freshUserDropdown.classList.remove('show');
                    isDropdownOpen = false;
                } else {
                    // Open dropdown
                    dropdownMenu.style.display = 'block';
                    freshUserDropdown.classList.add('show');
                    isDropdownOpen = true;
                }
            });

            // Prevent dropdown from closing when clicking inside the menu
            if (dropdownMenu) {
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (isDropdownOpen && !freshUserDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.style.display = 'none';
                    freshUserDropdown.classList.remove('show');
                    isDropdownOpen = false;
                }
            });

            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isDropdownOpen) {
                    dropdownMenu.style.display = 'none';
                    freshUserDropdown.classList.remove('show');
                    isDropdownOpen = false;
                }
            });

        }, 100); // Small delay to ensure other scripts have loaded
    }
});
</script>
