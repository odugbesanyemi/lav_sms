<div class=" p-0 px-md-4 px-2 py-3 d-flex items-center justify-between bg-gray-800">
    <div class="d-md-none items-center d-flex">
        <button class="p-2 d-flex items-center sidebar-mobile-main-toggle" type="button" id="sidebar-toggle">
        <iconify-icon icon="ci:menu-duo-md" class="text-white text-2xl"></iconify-icon>
        </button>
    </div>
    <div class="mr-auto">
        <a href="{{ route('dashboard') }}" class="m-0 d-inline-block text-decoration-none">
        <h4 class="text-bold text-white m-0 mr-auto uppercase">{{ Qs::getSystemName() }}</h4>
        </a>
    </div>


    <div class="" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item dropdown dropdown-user text-white">
                <p class="d-flex items-center justify-content center m-0" href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <img style="width: 25px; height:25px;" src="{{ Auth::user()->photo }}" class="rounded-full outline-2 outline outline-white mr-2" alt="photo">
                    <span class="pr-2 d-none d-md-block">{{ Auth::user()->name }}</span>
                    <i class="bi bi-caret-down-fill text-white" ></i>
                </p>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ Qs::userIsStudent() ? route('students.show', Qs::hash(Qs::findStudentRecord(Auth::user()->id)->id)) : route('users.show', Qs::hash(Auth::user()->id)) }}" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('my_account') }}" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
