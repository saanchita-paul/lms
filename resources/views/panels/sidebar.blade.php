<aside
  class="{{$configData['sidenavMain']}} @if(!empty($configData['activeMenuType'])) {{$configData['activeMenuType']}} @else {{$configData['activeMenuTypeClass']}}@endif @if(($configData['isMenuDark']) === true) {{'sidenav-dark'}} @elseif(($configData['isMenuDark']) === false){{'sidenav-light'}}  @else {{$configData['sidenavMainColor']}}@endif">
  <div class="brand-sidebar">
    <h1 class="logo-wrapper">
      <a class="brand-logo darken-1" href="{{asset('/')}}">
        @if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
          @if($configData['mainLayoutType']=== 'vertical-modern-menu')
          <img class="hide-on-med-and-down" src="{{asset($configData['largeScreenLogo'])}}" alt="logo" />
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['smallScreenLogo'])}}"
            alt="logo" />

          @elseif($configData['mainLayoutType']=== 'vertical-menu-nav-dark')
          <img src="{{asset($configData['smallScreenLogo'])}}" alt="logo1" />

          @elseif($configData['mainLayoutType']=== 'vertical-gradient-menu')
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}"
            alt="logo" />
          <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="logo2" />

          @elseif($configData['mainLayoutType']=== 'vertical-dark-menu')
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}"
            alt="logo" />
          <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="logo3" />
          @endif
        @endif
        
      </a>
      <a class="navbar-toggler" href="javascript:void(0)"><i class="material-icons">radio_button_checked</i></a></h1>
  </div>
  

  <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
    data-menu="menu-navigation" data-collapsible="menu-accordion">
        
        <li class="bold {{(request()->is('admin')) ? 'active' : '' }}">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link {{(request()->is('admin')) ? 'active' : '' }}">
                <i class="material-icons">
                  dashboard
                </i>
                <span class="menu-title">{{ trans('global.dashboard') }}</span>
            </a>
        </li>
        @can('user_management_access')
            <li class="bold {{ request()->is("admin/permissions*") ? "active" : "" }}{{ request()->is("admin/roles*") ? "active" : "" }}{{ request()->is("admin/users*") ? "active" : "" }}{{ request()->is("admin/audit-logs*") ? "active" : "" }}">
                <a class="collapsible-header waves-effect waves-cyan" >
                    <i class="material-icons">
                        people
                    </i>
                    <span class="menu-title">{{ trans('cruds.userManagement.title') }}</span>
                </a>
                <div class="collapsible-body">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                    @can('permission_access')
                        <li class='{{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.permissions.index") }}" class="{{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.permission.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class='{{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.roles.index") }}" class="{{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.role.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class='{{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.users.index") }}" class="{{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.user.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class='{{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.audit-logs.index") }}" class="{{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.auditLog.title') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
                </div>
            </li>
        @endcan
        @can('task_management_access')
            <li class="hide bold {{ request()->is("admin/task-statuses*") ? "active" : "" }}{{ request()->is("admin/task-tags*") ? "active" : "" }}{{ request()->is("admin/tasks*") ? "active" : "" }}{{ request()->is("admin/tasks-calendars*") ? "active" : "" }}">
                <a class="collapsible-header waves-effect waves-cyan" >
                    <i class="material-icons">
                        content_paste
                    </i>
                    <span class="menu-title">{{ trans('cruds.taskManagement.title') }}</span>
                </a>
                <div class="collapsible-body">  
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                    @can('task_status_access')
                        <li class='{{ request()->is("admin/task-statuses") || request()->is("admin/task-statuses/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.task-statuses.index") }}" class="{{ request()->is("admin/task-statuses") || request()->is("admin/task-statuses/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.taskStatus.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('task_tag_access')
                        <li class='{{ request()->is("admin/task-tags") || request()->is("admin/task-tags/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.task-tags.index") }}" class="{{ request()->is("admin/task-tags") || request()->is("admin/task-tags/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.taskTag.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('task_access')
                        <li class='{{ request()->is("admin/tasks") || request()->is("admin/tasks/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.tasks.index") }}" class="{{ request()->is("admin/tasks") || request()->is("admin/tasks/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.task.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('tasks_calendar_access')
                        <li class='{{ request()->is("admin/tasks-calendars") || request()->is("admin/tasks-calendars/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.tasks-calendars.index") }}" class="{{ request()->is("admin/tasks-calendars") || request()->is("admin/tasks-calendars/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.tasksCalendar.title') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
                </div>  
            </li>
        @endcan
        @can('basic_c_r_m_access')
            <li class="bold {{ request()->is("admin/crm-customers*") ? "active" : "" }}{{ request()->is("admin/crm-notes*") ? "active" : "" }}{{ request()->is("admin/crm-documents*") ? "active" : "" }}">
                <a class="collapsible-header waves-effect waves-cyan" >
                    <i class="material-icons">
                      contacts
                    </i>
                    <span class="menu-title">{{ trans('cruds.basicCRM.title') }}</span>
                </a>
                <div class="collapsible-body">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                    @can('crm_customer_access')
                        <?php 
                        if(!isset($crmCustomer->convert_to_deal)){
                            $crmCustomer = new \stdClass();
                            $crmCustomer->convert_to_deal = 0;
                        }

                        ?>
                        <li class='{{ (request()->is("admin/crm-customers") || request()->is("admin/crm-customers/*")) && request()->input('view')!="consults" && ($crmCustomer->convert_to_deal != 1) ? "active" : "" }}'>
                            <a href="{{ route("admin.crm-customers.index") }}" class="{{ (request()->is("admin/crm-customers") || request()->is("admin/crm-customers/*")) && request()->input('view')!="consults" && ($crmCustomer->convert_to_deal != 1) ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.crmCustomer.title') }}</span>
                            </a>
                        </li>
                        <li class='{{ (request()->input('view')=="consults")  || ($crmCustomer->convert_to_deal == 1) ? "active" : "" }}'>
                            <a href="{{ route("admin.crm-customers.index") }}?view=consults" class="{{ (request()->input('view')=="consults")  || ($crmCustomer->convert_to_deal == 1) ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.crmCustomer.cosults') }} </span>
                            </a>
                        </li>
                    @endcan
                    @can('crm_note_access')
                        <li class='hide {{ request()->is("admin/crm-notes") || request()->is("admin/crm-notes/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.crm-notes.index") }}" class="{{ request()->is("admin/crm-notes") || request()->is("admin/crm-notes/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.crmNote.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('crm_document_access')
                        <li class='hide {{ request()->is("admin/crm-documents") || request()->is("admin/crm-documents/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.crm-documents.index") }}" class="{{ request()->is("admin/crm-documents") || request()->is("admin/crm-documents/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.crmDocument.title') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
                </div>
            </li>
        @endcan
        @can('inbox_management_access')
            <li class='bold {{ request()->is("admin/crm-chats*") ? "active" : "" }}'>
                <a href="{{ route("admin.crm-chats.index") }}" class="{{ request()->is("admin/crm-chats") || request()->is("admin/crm-chats/*") ? "active" : "" }}">
                    <i class="material-icons">
                        chat
                    </i>
                    <span class="menu-title">{{ trans('cruds.inbox.title') }}</span>
                </a>
            </li>
        @endcan
        @can('clinic_management_access')
            <?php 
                $userid = Auth()->user()->id;
                $user = Auth()->user()->roles;
                $userrole = $user[0]['title'];
                $clinics = Auth()->user()->managerClinics;
                
                if($userrole == 'Onboarding'){

            ?>
            <li class='bold {{ request()->is("admin/clinics*") ? "active" : "" }}'>
                <a href="{{ route('admin.clinics.edit', $clinics[0]->id) }}" class="c-sidebar-nav-link {{(request()->is("admin/clinics/*")) ? 'active' : '' }}">
                    <i class="material-icons">
                        account_balance
                    </i>
                    <span class="menu-title">{{ trans('cruds.clinic.title') }}</span>
                </a>
            </li>
            <?php } else{ ?>
            <li class='bold {{ request()->is("admin/clinics*") ? "active" : "" }}'>
                
                <a class="collapsible-header waves-effect waves-cyan" href="#">
                    <i class="material-icons">
                        account_balance
                    </i>
                    <span class="menu-title">{{ trans('cruds.clinicManagement.title') }}</span>
                </a>
                <div class="collapsible-body">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                    @can('clinic_access')
                        <li class='{{ request()->is("admin/clinics") || request()->is("admin/clinics/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.clinics.index") }}" class="{{ request()->is("admin/clinics") || request()->is("admin/clinics/*") ? "active" : "" }}">
                                <i class="material-icons">
                                    radio_button_unchecked
                                </i>
                                {{ trans('cruds.clinic.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
                </div>
            </li>
        <?php } ?>
        @endcan
        @can('reports_management_access')
            <li class='bold {{ request()->is("admin/reports*") ? "active" : "" }}'>
                <a href="{{ route("admin.reports.index") }}" class="{{ request()->is("admin/reports") || request()->is("admin/reports/*") ? "active" : "" }}">
                    <i class="material-icons">
                        insert_chart
                    </i>
                    <span class="menu-title">{{ trans('cruds.reports.title') }}</span>
                </a>
            </li>
        @endcan
        @can('dataupload_management_access')
            <li class='bold {{ request()->is("admin/dataupload*") ? "active" : "" }}'>
                <a href="{{ route("admin.dataupload.index") }}" class="{{ request()->is("admin/dataupload") || request()->is("admin/dataupload/*") ? "active" : "" }}">
                    <i class="material-icons">
                        file_upload
                    </i>
                    <span class="menu-title">{{ trans('cruds.dataupload.title') }}</span>
                </a>
            </li>
        @endcan
        @can('userreports_management_access')
            <li class='bold {{ request()->is("admin/userreports*") ? "active" : "" }}'>
                <a href="{{ route("admin.userreports.index") }}" class="{{ request()->is("admin/userreports") || request()->is("admin/userreports/*") ? "active" : "" }}">
                    <i class="material-icons">
                        insert_chart
                    </i>
                    <span class="menu-title">{{ trans('cruds.userreports.title') }}</span>
                </a>
            </li>
        @endcan
        @can('setting_access')
            <li class='bold {{ request()->is("admin/sources*") || request()->is("admin/crm-statuses") || request()->is("admin/crm-statuses/*") ? "active" : "" }} '>
                <a class="collapsible-header waves-effect waves-cyan" href="#">
                    <i class="material-icons">
                      settings
                    </i>
                    <span class="menu-title">{{ trans('cruds.setting.title') }}</span>
                </a>
                <div class="collapsible-body">
                <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                    @can('source_access')
                        <li class='{{ request()->is("admin/sources") || request()->is("admin/sources/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.sources.index") }}" class="{{ request()->is("admin/sources") || request()->is("admin/sources/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.source.title') }}</span>
                            </a>
                        </li>
                    @endcan
                    @can('crm_status_access')
                        <li class='{{ request()->is("admin/crm-statuses") || request()->is("admin/crm-statuses/*") ? "active" : "" }}'>
                            <a href="{{ route("admin.crm-statuses.index") }}" class="{{ request()->is("admin/crm-statuses") || request()->is("admin/crm-statuses/*") ? "active" : "" }}">
                                <i class="material-icons">
                                  radio_button_unchecked
                                </i>
                                <span>{{ trans('cruds.crmStatus.title') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
                </div>
            </li>
        @endcan
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="bold">
                    <a class="{{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="material-icons">
                          assignment_ind
                        </i>
                        <span class="menu-title">{{ trans('global.my_profile') }}</span>
                    </a>
                </li>
            @endcan
        @endif
        <li class="bold">
            <a href="#" class="" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="material-icons">
                  power_settings_new
                </i>
                <span class="menu-title">{{ trans('global.logout') }}</span>
            </a>
        </li>
    </ul>



  <div class="navigation-background"></div>
  <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
    href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>