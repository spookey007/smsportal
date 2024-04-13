<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('admin.dashboard') }}" class="sidebar__main-logo"><img
                     src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('admin.dashboard') }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="menu-icon las la-tachometer-alt"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.contact*', 3) }}">
                        <i class="menu-icon las la-id-card"></i>
                        <span class="menu-title">@lang('Manage Contact')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.contact*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.contact.all') }}">
                                <a href="{{ route('admin.contact.all') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Contact')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.contact.email') }}">
                                <a href="{{ route('admin.contact.email') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Contact')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.contact.sms') }}">
                                <a href="{{ route('admin.contact.sms') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SMS Contact')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['admin.email.group*','admin.sms.group*','admin.group.banned','admin.group.contact.view'], 3) }}">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Manage Group')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive(['admin.email.group*','admin.sms.group*'], 2) }}">
                        <ul>
                            <li
                                class="sidebar-menu-item {{ menuActive(['admin.email.group.index']) }}">
                                <a href="{{ route('admin.email.group.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Group')</span>
                                </a>
                            </li>
                            <li
                                class="sidebar-menu-item {{ menuActive(['admin.sms.group.index']) }}">
                                <a href="{{ route('admin.sms.group.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SMS Group')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['admin.email.history', 'admin.email.send', 'admin.email.view','admin.email.view'], 3) }}">
                        <i class="menu-icon las la-mail-bulk"></i>
                        <span class="menu-title">@lang('Manage Email')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive(['admin.email.history', 'admin.email.send', 'admin.email.view'], 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.email.send') }}">
                                <a href=" {{ route('admin.email.send') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Send Email')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.email.history') }}">
                                <a href=" {{ route('admin.email.history') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email History')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['admin.sms.history', 'admin.sms.send','admin.sms.view'], 3) }}">
                        <i class="menu-icon las la-comments"></i>
                        <span class="menu-title">@lang('Manage SMS')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive(['admin.sms.history', 'admin.sms.send'], 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.sms.send') }}">
                                <a href=" {{ route('admin.sms.send') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Send SMS')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.sms.history') }}">
                                <a href=" {{ route('admin.sms.history') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SMS History')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.batch*', 3) }}">
                        <i class="menu-icon lab la-battle-net"></i>
                        <span class="menu-title">@lang('Manage Batch')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.batch*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.batch.email') }}">
                                <a href="{{ route('admin.batch.email') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Batch')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.batch.sms') }}">
                                <a href="{{ route('admin.batch.sms') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Sms Batch')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar__menu-header">@lang('Email & SMS Setting')</li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['admin.setting.notification.email','admin.setting.notification.global.template.email'], 3) }}">
                        <i class="menu-icon las la-envelope-square"></i>
                        <span class="menu-title">@lang('Email Setting')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive(['admin.setting.notification.email', 'admin.smtp.index','admin.setting.notification.global.template.email'], 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive(['admin.setting.notification.global.template.email','admin.smtp.index']) }}">
                                <a href="{{ route('admin.setting.notification.global.template.email') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Global Template')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.setting.notification.email') }}">
                                <a href="{{ route('admin.setting.notification.email') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Sender')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.smtp.index') }}">
                                <a href="{{ route('admin.smtp.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Manage SMTP')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['admin.setting.sms*','admin.setting.notification.sms.global.template','admin.setting.notification.sms'], 3) }}">
                        <i class="menu-icon las la-comments-dollar"></i>
                        <span class="menu-title">@lang('SMS Setting')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive(['admin.setting.sms*','admin.setting.notification.sms.global.template','admin.setting.notification.sms'], 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.setting.notification.sms.global.template') }}">
                                <a href="{{ route('admin.setting.notification.sms.global.template') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Global Template')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.setting.notification.sms') }}">
                                <a href="{{ route('admin.setting.notification.sms') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Gateway Setting')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebar__menu-header">@lang('General Setting')</li>
                <li class="sidebar-menu-item {{ menuActive('admin.setting.index') }}">
                    <a href="{{ route('admin.setting.index') }}" class="nav-link">
                        <i class="menu-icon las la-life-ring"></i>
                        <span class="menu-title">@lang('General Setting')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('admin.setting.logo.icon') }}">
                    <a href="{{ route('admin.setting.logo.icon') }}" class="nav-link">
                        <i class="menu-icon las la-images"></i>
                        <span class="menu-title">@lang('Logo & Favicon')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('admin.extensions.index') }}">
                    <a href="{{ route('admin.extensions.index') }}" class="nav-link">
                        <i class="menu-icon las la-cogs"></i>
                        <span class="menu-title">@lang('Extensions')</span>
                    </a>
                </li>

                <li class="sidebar__menu-header">@lang('Extra')</li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.system*', 3) }}">
                        <i class="menu-icon la la-server"></i>
                        <span class="menu-title">@lang('System')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.system*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.system.info') }}">
                                <a href="{{ route('admin.system.info') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Application')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.system.server.info') }}">
                                <a href="{{ route('admin.system.server.info') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Server')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.system.optimize') }}">
                                <a href="{{ route('admin.system.optimize') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Cache')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{ menuActive('admin.request.report') }}">
                    <a href="{{ route('admin.request.report') }}" class="nav-link"
                       data-default-url="{{ route('admin.request.report') }}">
                        <i class="menu-icon las la-bug"></i>
                        <span class="menu-title">@lang('Report & Request') </span>
                    </a>
                </li>
            </ul>
            <div class="text-uppercase mb-3 text-center">
                <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
                <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
