<style>
/* Hide horizontal scrollbar */
.sidebar-content::-webkit-scrollbar {
    width: 0;
}

/* Hide vertical scrollbar */
.sidebar-content::-webkit-scrollbar-thumb {
    background-color: transparent;
}
    .sidebar-content a{
        text-decoration: none;
        color: #2c3e50 !important;
        display: flex !important;
        align-items: center !important;
        padding: 15px 15px 15px 25px !important;
        transition: all .5s ease;

    }
    .sidebar-content a:hover{
        background: linear-gradient(to right,#2c3e5010,#2c3e5000) ;
        color: black !important;
        position: relative;
    }
    .sidebar-content a:hover iconify-icon{
        color: black !important;
        transform: scale(110%);
        transform-origin: center;
    }
    .sidebar-content iconify-icon{
        text-decoration: none;
        color: #460f79 !important;
        font-size: 25px !important;
        margin-right: 15px;
        transition: all .5s ease;
    }
    .sidebar-content .active{
        border-left: 4px solid #531092;
        color: #531092 !important;
    }
    .sidebar-content .active iconify-icon{
        color: #531092 !important;
        transform: scale(110%);
        transform-origin: center;
    }
    .sidebar-content .nav-item-expanded{
        background-color: white;
        border-right: 3px solid #7317ca9a;
        overflow: hidden;
        box-shadow: 0 5px 10px rgb(108 43 217 10);
        padding: 5px 0;
    }
    .sidebar-content .nav-item ul{
        /* box-shadow: 0 -5px 10px #2c3e5010 inset; */
    }
    a.nav-link{
        padding: 15px !important;
        margin-bottom:5px; 
    }
</style>
<div class="" id="sidebar-collapsible">
    <!-- Sidebar content -->
    <div class="sidebar-content overflow-y-auto text-light position-relative" style="height:calc(100vh - 60px);width:250px;">
        <!-- User menu -->
        <!-- <div class="sidebar-user m-0 py-1 bg-body text-dark rounded-2 p-2">
            <div class="border rounded-2">
                <div class="p-2 d-flex align-items-center gx-3">
                    <div class="position-relative w-25 mr-2">
                        <img src="{{Auth::user()->photo}}" alt="" class="rounded img-thumbnail">
                    </div>
                    <div class="media-body ">
                        <div class="media-title"><p class="m-0 fs-6">{{ Auth::user()->name }}</p></div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i> &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type)) }}
                        </div>
                    </div>

                    <div class=" align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- /user menu -->
        <!-- Main navigation -->
        <div class="m-0">
            <ul class="nav nav-sidebar m-0 p-0" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                        <iconify-icon icon="carbon:home"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{--Academics--}}
                @if(Qs::userIsAcademic())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><iconify-icon icon="mdi:education-outline"></iconify-icon> <span> Academics</span></a>

                        <ul class="nav-group-sub" data-submenu-title="Manage Academics">

                        {{--Timetables--}}
                            <li class="nav-item"><a href="{{ route('tt.index') }}" class="d-block {{ in_array(Route::currentRouteName(), ['tt.index']) ? 'active' : '' }}">Timetables</a></li>
                        </ul>
                    </li>
                    @endif

                {{--Administrative--}}
                @if(Qs::userIsAdministrative())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show','setup.marking-period','setup.calendar','setup.class-periods','setup.grade-levels','setup.classrooms','setup.schools','setup.schools.create','setup.schools.preferences']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><iconify-icon icon="carbon:network-admin-control"></iconify-icon> <span> Administrative</span></a>

                        <ul class="nav-group-sub" data-submenu-title="Administrative">

                            {{--Payments--}}
                            @if(Qs::userIsTeamAccount())
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice','setup.marking-period','setup.calendar','setup.class-periods','setup.grade-levels','setup.classrooms','setup.schools','setup.schools.create','setup.schools.preferences']) ? 'nav-item-expanded' : '' }}">

                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'active' : '' }}">Payments</a>

                                <ul class="nav-group-sub">
                                    <li class="nav-item"><a href="{{ route('payments.create') }}" class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">Create Payment</a></li>
                                    <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'active' : '' }}">Manage Payments</a></li>
                                    <li class="nav-item"><a href="{{ route('payments.manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'active' : '' }}">Student Payments</a></li>

                                </ul>

                            </li>
                            @endif
                            <!-- schools setup -->
                            @if (Qs::userIsTeamSA())
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['setup.schools','setup.schools.create','setup.schools.preferences','setup.marking-period','setup.calendar','setup.class-periods','setup.grade-levels','setup.classrooms','setup.schools','setup.schools.create','setup.schools.preferences','']) ? 'nav-item-expanded' : '' }}">

                                    <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.schools','setup.schools.create','setup.schools.preferences','setup.marking-period']) ? 'active' : '' }}">School Setup</a>

                                    <ul class="nav-group-sub">
                                        <li class="nav-item"><a href="/setup/marking-period" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.marking-period']) ? 'active' : '' }}">Marking Periods</a></li>
                                        <li class="nav-item"><a href="/setup/calendar" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.calendar']) ? 'active' : '' }}">Manage Calendar</a></li>
                                        <li class="nav-item"><a href="/setup/periods" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.class-periods']) ? 'active' : '' }}">Class Periods</a></li>
                                        <li class="nav-item"><a href="/setup/grade-levels" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.grade-levels']) ? 'active' : '' }}">Grade Levels</a></li>
                                        <li class="nav-item"><a href="/setup/classrooms" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.classrooms']) ? 'active' : '' }}">Manage classrooms</a></li>
                                        <li class="nav-item nav-item-submenu {{in_array(Route::currentRouteName(),['setup.schools','setup.schools.create','setup.schools.preferences']) ? 'nav-item-expanded':''}} ">
                                            <a href="{{ route('payments.manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.schools','setup.schools.create','setup.schools.preferences']) ? 'active' : '' }}">School</a>
                                            <ul class="nav-group-sub">
                                                <li class="nav-item"><a href="/setup/schools/" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.schools']) ? 'active' : '' }}">School Information</a></li>
                                                <li class="nav-item"><a href="/setup/schools/create" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.schools.create']) ? 'active' : '' }}">Manage Schools</a></li>
                                                <li class="nav-item"><a href="/setup/schools/preferences" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.schools.preferences']) ? 'active' : '' }}">System Preferences</a></li>

                                            </ul>
                                        </li>

                                    </ul>

                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{--Manage Students--}}
                @if(Qs::userIsTeamSAT())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.create', 'students.list', 'students.edit', 'students.show','students.import', 'students.promotion', 'students.promotion_manage', 'students.graduated']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><iconify-icon icon="carbon:user"></iconify-icon> <span> Students</span></a>

                        <ul class="nav-group-sub" data-submenu-title="Manage Students">
                            {{--Admit Student--}}
                            @if(Qs::userIsTeamSA())
                                <li class="nav-item">
                                    <a href="{{ route('students.create') }}"
                                       class="nav-link {{ (Route::is('students.create')) ? 'active' : '' }}">Admit Student</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('students.import') }}"
                                       class="nav-link {{ (Route::is('students.import')) ? 'active' : '' }}">Import</a>
                                </li>
                            @endif

                            {{--Student Information--}}
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'nav-item-expanded' : '' }}">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'active' : '' }}">Student Information</a>
                                <ul class="nav-group-sub">
                                    @foreach(Qs::getSchoolGradeLevels() as $c)
                                        <li class="nav-item"><a href="{{ route('students.list', $c->id) }}" class="nav-link ">{{ $c->title }}</a></li>
                                    @endforeach

                                </ul>
                            </li>

                            @if(Qs::userIsTeamSA())

                            {{--Student Promotion--}}
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage']) ? 'nav-item-expanded' : '' }}"><a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage' ]) ? 'active' : '' }}">Student Promotion</a>
                            <ul class="nav-group-sub">
                                <li class="nav-item"><a href="{{ route('students.promotion') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion']) ? 'active' : '' }}">Promote Students</a></li>
                                <li class="nav-item"><a href="{{ route('students.promotion_manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion_manage']) ? 'active' : '' }}">Manage Promotions</a></li>
                            </ul>

                            </li>

                            {{--Student Graduated--}}
                            <li class="nav-item"><a href="{{ route('students.graduated') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.graduated' ]) ? 'active' : '' }}">Students Graduated</a></li>
                                @endif

                        </ul>
                    </li>
                @endif

                @if(Qs::userIsTeamSA())
                    {{--Manage Users--}}
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><iconify-icon icon="carbon:user-avatar"></iconify-icon> <span> Users</span></a>
                    </li>

                    {{--Manage Classes--}}
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}"><iconify-icon icon="carbon:group-presentation"></iconify-icon> <span> Classes</span></a>
                    </li>

                    {{--Manage Dorms--}}
                    <li class="nav-item">
                        <a href="{{ route('dorms.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}"><iconify-icon icon="clarity:block-solid"></iconify-icon> <span> Dormitories</span></a>
                    </li>

                    {{--Manage Sections--}}
                    <li class="nav-item">
                        <a href="{{ route('sections.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit',]) ? 'active' : '' }}"><iconify-icon icon="carbon:tag-group"></iconify-icon> <span>Sections</span></a>
                    </li>

                    {{--Manage Subjects--}}
                    <li class="nav-item">
                        <a href="{{ route('subjects.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit',]) ? 'active' : '' }}"><iconify-icon icon="carbon:bookmark-add"></iconify-icon> <span>Subjects</span></a>
                    </li>
                @endif

                {{--Exam--}}
                @if(Qs::userIsTeamSAT())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['exams.index', 'exams.edit', 'grades.index', 'grades.edit', 'marks.index', 'marks.manage', 'marks.bulk', 'marks.tabulation', 'marks.show', 'marks.batch_fix',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><iconify-icon icon="carbon:notebook"></iconify-icon> <span> Exams</span></a>

                    <ul class="nav-group-sub" data-submenu-title="Manage Exams">
                        @if(Qs::userIsTeamSA())

                        {{--Exam list--}}
                            <li class="nav-item">
                                <a href="{{ route('exams.index') }}"
                                   class="nav-link {{ (Route::is('exams.index')) ? 'active' : '' }}">Exam List</a>
                            </li>

                            {{--Grades list--}}
                            <li class="nav-item">
                                    <a href="{{ route('grades.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['grades.index', 'grades.edit']) ? 'active' : '' }}">Grades</a>
                            </li>

                            {{--Tabulation Sheet--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.tabulation') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.tabulation']) ? 'active' : '' }}">Tabulation Sheet</a>
                            </li>

                            {{--Marks Batch Fix--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.batch_fix') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.batch_fix']) ? 'active' : '' }}">Batch Fix</a>
                            </li>
                        @endif

                        @if(Qs::userIsTeamSAT())
                            {{--Marks Manage--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['marks.index']) ? 'active' : '' }}">Marks</a>
                            </li>

                            {{--Marksheet--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.bulk') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.bulk', 'marks.show']) ? 'active' : '' }}">Marksheet</a>
                            </li>

                        @endif
                        @if(Qs::userIsTeamSA())
                            <li class="nav-item nav-item-submenu">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['setup.schools','setup.schools.create','setup.schools.preferences','marks.setup.manage-skills-type','marks.setup.manage-remarks']) ? 'active' : '' }}">Setup</a>
                                <ul class="nav-group-sub">
                                    <li class="nav-item"><a href="/marks/setup/manage-skills-type" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.setup.manage-skills-type']) ? 'active' : '' }}">Manage Skill Types</a></li>
                                    <li class="nav-item"><a href="/marks/setup/manage-skills" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.setup.manage-skills']) ? 'active' : '' }}">Manage Skills</a></li>
                                    <li class="nav-item"><a href="/marks/setup/remarks" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.setup.manage-remarks']) ? 'active' : '' }}">Manage Remarks</a></li>
                                    <li class="nav-item"><a href="/marks/setup/preferences" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.setup.preferences']) ? 'active' : '' }}">Exam System Preferences</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif

                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['resources.view',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><iconify-icon icon="grommet-icons:resources"></iconify-icon> <span>Resources</span></a>

                    <ul class="nav-group-sub" data-submenu-title="Uploads">

                        {{--Exam list--}}
                            <li class="nav-item">
                                <a href="/resource"
                                   class="nav-link {{ (Route::is('resource.index')) ? 'active' : '' }}">Resource page</a>
                            </li>

                            {{--Grades list--}}
                            <!-- <li class="nav-item">
                                    <a href=""
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['grades.index', 'grades.edit']) ? 'active' : '' }}">Manage Resource</a>
                            </li> -->


                    </ul>

                </li>
                {{--End Exam--}}
                {{-- resources --}}


                @include('pages.'.Qs::getUserType().'.menu')

                {{--Manage Account--}}
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><iconify-icon icon="carbon:user-avatar-filled"></iconify-icon> <span>My Account</span></a>
                </li>

                </ul>
            </div>
        </div>

</div>
