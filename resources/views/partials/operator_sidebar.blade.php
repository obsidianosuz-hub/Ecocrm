<a href="{{ route('operator.dashboard') }}" class="nav-item {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-terminal"></i>
    <span>Netrunner Matrix</span>
</a>
<a href="{{ route('admin.clients.index') }}" class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
    <i class="fa-solid fa-address-book"></i>
    <span>Mijozlar Bazasi</span>
</a>
<a href="{{ route('chat.index') }}" class="nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
    <i class="fa-solid fa-comments"></i>
    <span>The Syndicate Hub</span>
</a>
