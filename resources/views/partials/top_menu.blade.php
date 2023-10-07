
<div class="max-md:flex-col flex items-center md:px-2 border-b-purple-800/10 border-b relative">
    <div class="flex w-full items-center justify-between text-gray-900 ">
        <div class="d-md-none items-center flex ">
            <button class="p-2 d-flex items-center sidebar-mobile-main-toggle text-purple-700" type="button" id="sidebar-toggle">
                <i class="fi fi-br-bars-staggered text-2xl"></i>
            </button>
        </div>
        <div class="mr-auto p-2">
            <a href="{{ route('dashboard') }}" class="m-0  text-decoration-none flex items-center ">
            <span class="md:pr-2 md:border-r"><img src="{{ Qs::getSetting('logo') }}" class="w-10 h-10" alt=""></span>
            <h4 class="text-lg font-bold m-0 mr-auto uppercase md:px-2 max-md:hidden text-purple-900">{{ Qs::getSystemName() }}</h4>
            </a>
        </div>
        <div class="flex items-center ml-auto mr-2 space-x-2">
            <button class=" focus:ring-4 focus:ring-orange-300 font-medium rounded-full p-2 bg-orange-50" type="button" >
                <i class="fi fi-rr-bell text-2xl flex text-orange-700"></i>
            </button>
            <button class=" focus:ring-4 focus:ring-blue-300 font-medium rounded-full p-2 bg-blue-50" type="button"data-drawer-target="drawer-right-example" data-drawer-show="drawer-right-example" data-drawer-placement="right" aria-controls="drawer-right-example">
                <i class="fi fi-rr-graduation-cap text-2xl flex text-blue-700"></i>
            </button>

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

         <!-- drawer component -->
         <div id="drawer-right-example" class="fixed top-0 right-0 z-40 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white w-80 dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-right-label">
             <h5 id="drawer-top-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>Top drawer
            </h5>
            <button type="button" data-drawer-hide="drawer-top-example" aria-controls="drawer-top-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 right-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
            <div class="content">
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
            $('#ajax-loader').show('slow');
            $.ajax({
                dataType:'json',
                url:`/setup/change-school/${id}`,
                success:function(){
                 $('#ajax-loader').hide('slow');
                 location.reload()
                },
                error: function(xhr, status, error) {
                    $('#ajax-loader').hide('slow');
                    console.error(error); // Log the error to the console
                }
            })
        }

        function changeAcademicYear(id){
            $('#ajax-loader').show('slow');
            $.ajax({
                dataType:'json',
                url:`/setup/change-academic-year/${id}`,
                success:function(){
                 location.reload()
                 $('#ajax-loader').hide('slow');
                },
                error: function(xhr, status, error) {
                    $('#ajax-loader').hide('slow');
                    console.error(error); // Log the error to the console
                }
            })
        }
    </script>
