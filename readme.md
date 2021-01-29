# Laravel Nova: Export database views
> Export manually created database views

## Installation
1) Override the Nova layout through creating `resources/views/vendor/nova/layout.blade.php` in your project route.
2) Add `@include('database-view-export::exports-dropdown')` to your desired location
    -  Example:
```html
<!-- Content -->
<div class="content">
    <div class="flex items-center relative shadow h-header bg-white z-20 px-view">
        <a v-if="@json(\Laravel\Nova\Nova::name() !== null)" href="{{ \Illuminate\Support\Facades\Config::get('nova.url') }}" class="no-underline dim font-bold text-90 mr-6">
            {{ \Laravel\Nova\Nova::name() }}
        </a>

        @if (count(\Laravel\Nova\Nova::globallySearchableResources(request())) > 0)
            <global-search dusk="global-search-component"></global-search>
        @endif

        {{-- Add dropdown menu for exports package --}}
        <div class="ml-auto flex items-center dropdown-right">
            @include('database-view-export::exports-dropdown')
        </div>

        <dropdown class="ml-8 h-9 flex items-center dropdown-right">
            @include('nova::partials.user')
        </dropdown>
    </div>

    <div data-testid="content" class="px-view py-view mx-auto">
        @yield('content')

        @include('nova::partials.footer')
    </div>
</div>
```
3) Add the displayed key & the specific name of the view database for it to render in the dropdown.
