{{--My Children--}}
<li class="nav-item">
    <a href="{{ route('my_children') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_children']) ? 'active' : '' }}"><iconify-icon icon="carbon:pedestrian-child"></iconify-icon> My Children</a>
</li>