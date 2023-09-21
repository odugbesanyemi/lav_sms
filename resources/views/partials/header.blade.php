<style>
    .transition{
        transition: all .4s ease;
        z-index: 100;
    }
</style>
<div id="page-header" class="bg-body-secondary text-dark py-2 position-sticky top-0">
    <div class="page-header-content header-elements-md-inline">
        <div class="m-0 py-2 d-flex border-md-0">
            <h5 class="m-0"><i class="icon-plus-circle2 mr-2"></i> <span class="font-weight-semibold">@yield('page_title')</span></h5>
            <a href="#headerCollapse" id="menu-toggle" role="button" aria-expanded="false" aria-controls="collapseExample" data-toggle="collapse" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="d-md-block d-none" id="menu-collapse">
            <div  class="d-flex align-items-center flex-column flex-md-row py-1">
                <form action="" class="w-100 pr-0 pr-md-2 mb-1 mb-md-0">
                    <select onchange="changeSchool(this.value)" name="" id="" class="d-flex  form-control " style="width: 100% !important;">
                        @foreach (Qs::getSchools() as $sl )
                        @if (Qs::getSchools()->count() == 0)
                            <option>No Registered School</option>
                        @endif
                        <option {{ $sl->active == 1 ? 'selected=true': ""}}  value="{{ $sl->id }}">{{$sl->name}}</option>
                        @endforeach
                    </select>
                </form>
                <select  onchange="changeAcademicYear(this.value)" name="" class="d-flex  form-control " id="">
                    @foreach (Qs::getAllAcademicYear() as $ayear )
                        <option {{ $ayear->default==1?'selected=true':"" }} value="{{ $ayear->id }}">{{$ayear->title}}</option>
                    @endforeach
                </select>
                <!-- <a href="{{ Qs::userIsSuperAdmin() ? route('settings') : '' }}" class="btn btn-link text-default d-flex w-100 border rounded"> <span class="font-weight-semibold">Session: {{ Qs::getSetting('current_session') }}</span></a> -->
            </div>
        </div>
    </div>

    {{--Breadcrumbs--}}
    {{--<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                <a href="form_select2.html" class="breadcrumb-item">Forms</a>
                <span class="breadcrumb-item active">Select2 selects</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none">
            <div class="breadcrumb justify-content-center">
                <a href="#" class="breadcrumb-elements-item">
                    <i class="icon-comment-discussion mr-2"></i>
                    Support
                </a>

                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-gear mr-2"></i>
                        Settings
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
                        <a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
                        <a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}

    <script>
        $(document).ready()
        {
            var sidebar = $('#sidebar-collapsible')

            var sidebarToggler = $('#sidebar-toggle')
            var toggler = $('#menu-toggle')
            var collapsible = $('#menu-collapse')
            toggler.click(()=>{
                collapsible.toggleClass('d-none transition-all')
            })
            sidebarToggler.click(()=>{
                sidebar.toggleClass('d-none transition-all')
            })
        }
        function changeSchool(id)
        {

            $.ajax({
                dataType:'json',
                url:`/setup/change-school/${id}`,
                success:function(){
                 location.href=(`/`)
                }
            })
        }

        function changeAcademicYear(id){
            $.ajax({
                dataType:'json',
                url:`/setup/change-academic-year/${id}`,
                success:function(){
                 location.href=(`/`)
                }
            })
        }
    </script>
</div>
