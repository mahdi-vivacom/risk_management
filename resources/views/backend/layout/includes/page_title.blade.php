@if (!empty($title))
    <div class="pagetitle row">
        <h1>{{ !empty($title) ? $title : '' }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">@lang('admin_menu.home')</a></li>
                <li class="breadcrumb-item active">{{ !empty($title) ? $title : '' }}</li>
            </ol>
        </nav>
    </div>
@endif
