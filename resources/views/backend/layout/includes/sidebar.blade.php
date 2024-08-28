<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @foreach ( $menuData as $sidemenu )
            @if ( !empty ( $sidemenu->route ) )
                <li class="nav-item">
                    <a class="nav-link {{ Route::current ()->getName () == $sidemenu->route ? 'active' : '' }}"
                        href="{{ route ( $sidemenu->route ) }}">
                        <i class="{{ !empty ( $sidemenu->icon ) ? $sidemenu->icon : 'bi bi-circle' }}"></i>
                        <span>{{ !empty ( $sidemenu->label ) ? $sidemenu->label : '' }}</span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link {{ Route::current ()->getName () == $sidemenu->route ? '' : 'collapsed' }}"
                        data-bs-target="#side{{ $sidemenu->id }}-nav" data-bs-toggle="collapse" href="#">
                        <i class="{{ !empty ( $sidemenu->icon ) ? $sidemenu->icon : 'ri-admin-line' }}"></i><span>
                            {{ $sidemenu->label }} </span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    @php
                        $routesArray = [];
                        foreach ( $sidemenu->SubMenu as $menu ) {
                            $routesArray[] = $menu->route;
                        }
                    @endphp
                    <ul id="side{{ $sidemenu->id }}-nav"
                        class="nav-content collapse {{ in_array ( Route::current ()->getName (), $routesArray ) ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        @if( $sidemenu->SubMenu->isNotEmpty () )
                            @foreach( $sidemenu->SubMenu as $submenu )
                                @if( in_array ( $submenu->permission_id, $userPermissions ) )
                                    @if ( !empty ( $submenu->route ) )
                                        <li>
                                            <a class="nav-link {{ Route::current ()->getName () == $submenu->route ? 'active' : '' }}"
                                                href="{{ route ( $submenu->route ) }}">
                                                <i
                                                    class="{{ !empty ( $submenu->icon ) ? $submenu->icon : 'bi bi-circle' }}"></i><span>{{ !empty ( $submenu->label ) ? $submenu->label : '' }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
</aside>