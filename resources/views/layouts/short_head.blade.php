<div id="header-container" class="short" style="background-image:url('@yield('header-bg')')">
    <div class="black-filter"></div>
</div>
@if (View::hasSection('header-title'))
    <div id="header-title">
        <div class="container py-3 pl-1 display-4" style="font-size:1.5em;">
            <span class="ml-3">@yield('header-title')</span>
        </div>
    </div>
@endif


