{{-- <div class="sidebar position-fixed border-right col-md-3 col-lg-2 p-0 bg-body-tertiary" style="z-index: 9999;">
    <div class="offcanvas-md offcanvas-start bg-body-tertiary" tabindex="-1" id="sidebarMenu"
         aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">{{ config('devstarit.app_name') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                    aria-label="Close"></button>
        </div>

        <div class="offcanvas-body position-static sidebar-sticky d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto"
             style="background-color: #202C46 !important;">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ (request()->is('admin')) ? 'active' : '' }}" aria-current="page"
                       href="{{ route('admin.index') }}">
                        <span data-feather="home" class="align-text-bottom"></span>
                        Dashboard
                    </a>
                </li>
                @can('user_access')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/users*')) ? 'active' : '' }}"
                           href="{{ route('admin.users.index') }}">
                            <span data-feather="users" class="align-text-bottom"></span>
                            Users
                        </a>
                    </li>
                @endcan
                @can('user_access')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/school*')) ? 'active' : '' }}"
                           href="{{ route('admin.schools.index') }}">
                            <span data-feather="users" class="align-text-bottom"></span>
                            Schools
                        </a>
                    </li>
                @endcan
                @can('permission_access')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/permissions*')) ? 'active' : '' }}"
                           href="{{ route('admin.permissions.index') }}">
                            <span data-feather="shield" class="align-text-bottom"></span>
                            Permissions
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/roles*')) ? 'active' : '' }}"
                           href="{{ route('admin.roles.index') }}">
                            <span data-feather="disc" class="align-text-bottom"></span>
                            Roles
                        </a>
                    </li>
                @endcan
                @can('post_access')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/posts*')) ? 'active' : '' }}"
                           href="{{ route('admin.posts.index') }}">
                            <span data-feather="file" class="align-text-bottom"></span>
                            Posts
                        </a>
                    </li>
                @endcan
                @can('category_access')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/categories*')) ? 'active' : '' }}"
                           href="{{ route('admin.categories.index') }}">
                            <span data-feather="list" class="align-text-bottom"></span>
                            Categories
                        </a>
                    </li>
                @endcan
                @can('tag_access')
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('admin/tags*')) ? 'active' : '' }}"
                           href="{{ route('admin.tags.index') }}">
                            <span data-feather="tag" class="align-text-bottom"></span>
                            Tags
                        </a>
                    </li>
                @endcan
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                <span>Setting</span>
                <a class="link-secondary" href="#" aria-label="Add a new report">
                    <span data-feather="plus-circle" class="align-text-bottom"></span>
                </a>
            </h6>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span data-feather="settings" class="align-text-bottom"></span>
                        App Setting
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->is('admin/profile')) ? 'active' : '' }}"
                       href="{{ route('admin.profile.index') }}">
                        <span data-feather="user" class="align-text-bottom"></span>
                        Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ (request()->is('admin/change-password')) ? 'active' : '' }}"
                       href="{{ route('admin.password.index') }}">
                        <span data-feather="key" class="align-text-bottom"></span>
                        Change Password
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>  --}}



{{-- ------ --}}

<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('admin.index') }}" class="waves-effect">
                        {{-- <i class="bx bx-home-circle"></i>
                        <span class="badge rounded-pill bg-info float-end">04</span> --}}
                        <i class="bx bx-layout"></i>

                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                @can('user_access')
                <li>
                    <a href="{{ route('admin.users.index') }}" class="{{ (request()->is('admin/users*')) ? 'active' : '' }} waves-effect">
                        <i class="bx bx-layout"></i>
                        <span key="t-dashboards">Users</span>
                    </a>
                </li>
                @endcan

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-layouts">Schools</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{ route('admin.schools.index') }}" class="{{ (request()->is('admin/schools')) ? 'active' : '' }}" key="t-light-sidebar">All Schools</a></li>
                        <li><a href="{{ route('admin.semesters.index') }}" class="{{ (request()->is('admin/semesters')) ? 'active' : '' }}" key="t-compact-sidebar">School Semesters</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-grid-horizontal"></i>
                        <span key="t-layouts">Subscription</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{ route('admin.plans') }}" class="{{ (request()->is('admin/subscription/plans')) ? 'active' : '' }}" key="t-compact-sidebar">Plans</a></li>
                    </ul>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{ route('admin.subscription.invoices') }}" class="{{ (request()->is('admin/subscription/plans')) ? 'active' : '' }}" key="t-compact-sidebar">Invoices</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-layouts">Groups</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{ route('admin.groups.index') }}" class="{{ (request()->is('admin/groups')) ? 'active' : '' }}" key="t-light-sidebar">All Groups</a></li>
                        <li><a href="{{ route('admin.groups.create') }}" class="{{ (request()->is('admin/groups')) ? 'active' : '' }}" key="t-compact-sidebar">Groups Create</a></li>
                        <li><a href="{{ route('admin.trips.index') }}" class="{{ (request()->is('admin/trips')) ? 'active' : '' }}" key="t-compact-sidebar">Trips</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.drivers.index') }}" class="{{ (request()->is('admin/drivers*')) ? 'active' : '' }} waves-effect">
                        <i class="bx bx-car"></i>
                        <span key="t-driver">Drivers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.fathers.index') }}" class="{{ (request()->is('admin/fathers*')) ? 'active' : '' }} waves-effect">
                        <i class="bx bx-group"></i>
                        <span key="t-driver">Parents</span>
                    </a>
                </li>
                @can('permission_access')
                <li>
                    <a href="{{ route('admin.permissions.index') }}" class="{{ (request()->is('admin/permissions*')) ? 'active' : '' }} waves-effect">
                        <i class="bx bx-layout"></i>
                        <span key="t-dashboards">Permissions</span>
                    </a>
                </li>
                @endcan

                @can('role_access')
                <li>
                    <a href="{{ route('admin.roles.index') }}" class="{{ (request()->is('admin/roles*')) ? 'active' : '' }} waves-effect">
                        <i class="bx bx-layout"></i>
                        <span key="t-dashboards">Roles</span>
                    </a>
                </li>
                @endcan

                {{-- Official Holidays --}}
                <li>
                    <a href="{{ route('admin.official.holiday.index') }}" class="{{ (request()->is('admin/roles*')) ? 'active' : '' }} waves-effect">
                        <i class="bx bx-happy-beaming"></i>
                        <span key="t-dashboards">Official Holidays</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-layouts">Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        {{-- <li><a href="{{ route('admin.profile.index') }}" class="{{ (request()->is('admin/profile')) ? 'active' : '' }}" key="t-light-sidebar">Profile</a></li>
                        <li><a href="{{ route('admin.password.index') }}" class="{{ (request()->is('admin/profile')) ? 'active' : '' }}" key="t-compact-sidebar">Change Password</a></li> --}}
                        <li><a href="{{ route('admin.main.setting') }}" class="{{ (request()->is('admin/setting/main-settings')) ? 'active' : '' }}" key="t-compact-sidebar">Main Setting</a></li>
                    </ul>
                </li>

                {{-- <li class="menu-title" key="t-apps">Apps</li> --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
