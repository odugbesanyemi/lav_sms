<div class="max-md:flex-col flex items-center px-2 py-2">
    <div class=" p-0 px-md-4 px-2 d-flex flex-1 w-full items-center justify-between text-gray-900">
        <div class="d-md-none items-center d-flex">
            <button class="p-2 d-flex items-center sidebar-mobile-main-toggle" type="button" id="sidebar-toggle">
                <x-bytesize-menu class="w-5 h-5 "/>
            </button>
        </div>
        <div class="mr-auto">
            <a href="{{ route('dashboard') }}" class="m-0 d-inline-block text-decoration-none text-gray-700">
            <h4 class="text-lg font-bold m-0 mr-auto uppercase">{{ Qs::getSystemName() }}</h4>
            </a>
        </div>
        <div class="" id="navbar-mobile">
            <ul class="navbar-nav">
                <li class="nav-item dropdown dropdown-user ">
                    <p class="d-flex items-center justify-content center m-0" href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <img style="width: 25px; height:25px;" src="{{ Auth::user()->photo }}" class="rounded-full outline-2 outline outline-gray-400 mr-2" alt="photo">
                        <span class="pr-2 d-none d-md-block">{{ Auth::user()->name }}</span>
                        <i class="bi bi-caret-down-fill" ></i>
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
        <div class="page-header-content header-elements-md-inline d-md-none">
            <div class="m-0 py-2 d-flex border-md-0">
                <a href="#headerCollapse" id="menu-toggle" role="button" aria-expanded="false" aria-controls="collapseExample" data-toggle="collapse" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>

    <div class="d-md-block d-none max-md:p-2 max-md:w-full" id="menu-collapse">
        <div  class="d-flex align-items-center w-full flex-column max-md:flex-1 flex-md-row py-1">
            <form action="" class="w-100 pr-0 pr-md-2 mb-1 mb-md-0">
                <select onchange="changeSchool(this.value)" name="" id="" class="d-flex select-search form-control " style="width: 100% !important;">
                    @foreach (Qs::getSchools() as $sl )
                    <option {{ Qs::findActiveSchool()[0]->id==$sl->id ? 'selected': ""}}  value="{{ $sl->id }}">{{$sl->name}}</option>
                    @endforeach
                </select>
            </form>
            <select  onchange="changeAcademicYear(this.value)" name="" class="d-flex select-search form-control " id="">
                @foreach (Qs::getAllAcademicYear() as $ayear )
                    <option {{ Qs::getActiveAcademicYear()[0]->id == $ayear->id ?'selected':"" }} value="{{ $ayear->id }}">{{$ayear->title}}</option>
                @endforeach
            </select>
            <!-- <a href="{{ Qs::userIsSuperAdmin() ? route('settings') : '' }}" class="btn btn-link text-default d-flex w-100 border rounded"> <span class="font-weight-semibold">Session: {{ Qs::getSetting('current_session') }}</span></a> -->
        </div>
    </div>
</div>


<script>
        $(document).ready()
        {

            var toggler = $('#menu-toggle')
            var collapsible = $('#menu-collapse')
            toggler.click(()=>{
                collapsible.toggleClass('d-none transition-all')
            })

        }
        function changeSchool(id)
        {

            $.ajax({
                dataType:'json',
                url:`/setup/change-school/${id}`,
                success:function(){
                 location.reload()
                }
            })
        }

        function changeAcademicYear(id){
            $.ajax({
                dataType:'json',
                url:`/setup/change-academic-year/${id}`,
                success:function(){
                 location.reload()
                }
            })
        }
    </script>
