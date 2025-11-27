@php
    $notifications = getNotification(Auth::user()->roles->pluck('name')->first());
    $notificationCount = count($notifications);
@endphp
<header class='d-flex align-items-center justify-content-between flex-grow-1 header px-4 px-xl-5 py-3 shadow-sm bg-white sticky-top'>
    
    {{-- 📱 Hamburger Button for Mobile Aside Menu --}}
    <button type="button" class="btn p-0 me-3 d-block d-xl-none sidebar-btn" title="Toggle Sidebar">
        <i class="fa-solid fa-bars fs-3 text-primary"></i>
    </button>

    {{-- 🏠 Dashboard Title or Main Navigation (Menu) --}}
    @if(url()->current() == url('dashboard'))
        <h3 class="navbar-brand-name text-dark text-decoration-none logo fw-bold fs-4 m-0 me-auto">
            {{ getAppName() }}
        </h3>
    @else
        <nav class="navbar navbar-expand-xl navbar-light top-navbar d-xl-flex d-block p-0 me-auto" id="nav-header">
            <div class="container-fluid p-0">
                <div class="navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @include('layouts.sub_menu')
                    </ul>
                </div>
            </div>
        </nav>
    @endif
    
    <ul class="nav align-items-center flex-nowrap ms-auto">
        
        {{-- 🌙 Dark/Light Mode Switcher --}}
        <li class="nav-item me-4">
            <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-trigger="hover"
                 title=""
                 data-bs-original-title="{{ getLoggedInUser()->thememode ? 'Switch to Light Mode' : 'Switch to Dark Mode' }}">
                <a data-turbo="false" href="{{ route('user.mode') }}" class="text-decoration-none p-2 rounded-circle hover-bg-light">
                    <i class="fas user-check-icon {{ getLoggedInUser()->thememode ? 'fa-sun text-warning' : 'fa-moon text-dark' }} fs-4 transition-3s"></i>
                </a>
            </div>
        </li>
        
        {{-- 🔔 Notifications Dropdown --}}
        <li class="nav-item me-4">
            <div class="dropdown custom-dropdown d-flex align-items-center">
                <button class="btn hide-arrow p-0 position-relative border-0" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-bell text-primary fs-4"></i>
                    @if($notificationCount != 0)
                        <span
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light p-1 badge-circle"
                              id="counter">{{ ($notificationCount > 9) ? '9+' : $notificationCount }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg py-0 mt-2" aria-labelledby="dropdownMenuButton1" style="min-width: 320px;">
                    
                    {{-- Dropdown Header --}}
                    <div class="text-start border-bottom py-3 px-4 bg-light rounded-top">
                        <h5 class="text-gray-900 mb-0 fw-bold">{{__('messages.notification.notifications')}} ({{ $notificationCount }})</h5>
                    </div>
                    
                    {{-- Notifications List --}}
                    <div class="px-3 mt-3 inner-scroll" style="max-height: 300px; overflow-y: auto;">
                        @if($notificationCount > 0)
                            @foreach($notifications as $notification)
                                <a href="javascript:void(0)" data-id="{{ $notification->id }}"
                                   class="notification d-flex align-items-center mb-3 p-3 rounded-3 text-decoration-none text-hover-primary transition-3s bg-hover-light-primary"
                                   id="notification">
                                    <span class="me-3 text-primary fs-5 icon-label flex-shrink-0">
                                        <i class="{{ getNotificationIcon($notification->type) }}"></i>
                                    </span>
                                    <div class="flex-grow-1">
                                        <h6 class="text-gray-800 fw-semibold mb-1 notification-title">{{ $notification->title }}</h6>
                                        <p class="text-gray-600 fs-small m-0 text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans(null, true)}} ago</p>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="py-5 text-center">
                                <i class="fa-solid fa-bell-slash text-muted fs-1 mb-3"></i>
                                <h6 class="text-muted fw-normal m-0">{{ __('messages.notification.you_don`t_have_any_new_notification') }}</h6>
                            </div>
                        @endif
                        <div class="py-2">
                            <h6 class="text-center text-muted fw-normal m-0 empty-state empty-notification d-none">{{ __('messages.notification.you_don`t_have_any_new_notification') }}</h6>
                        </div>
                    </div>
                    
                    {{-- Dropdown Footer --}}
                    @if($notificationCount > 0)
                        <div class="text-center border-top p-3 mark-read rounded-bottom">
                            <a href="#" class="text-primary fw-bold fs-6 read-all-notification text-decoration-none hover-text-dark"
                               id="readAllNotification">{{ __('messages.notification.mark_all_as_read') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </li>
        
        {{-- 👤 User Profile Dropdown --}}
        <li class="nav-item">
            <div class="dropdown d-flex align-items-center">
                <div class="image image-circle image-mini me-2 flex-shrink-0">
                    <img src="{{ Auth::user()->image_url??'' }}"
                         class="img-fluid rounded-circle object-fit-cover" alt="profile image" style="width: 38px; height: 38px;">
                </div>
                <button class="btn ps-0 pe-0 text-gray-800 dropdown-toggle-split fw-semibold border-0 user-dropdown-btn" type="button" id="dropdownMenuButton2"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    {{ (Auth::user()->full_name)??'' }}
                </button>
                <div class="dropdown-menu dropdown-menu-end py-0 shadow-lg my-2" aria-labelledby="dropdownMenuButton2" style="min-width: 250px;">
                    
                    {{-- User Info Header --}}
                    <div class="text-center border-bottom p-5 bg-light rounded-top">
                        <div class="image image-circle image-tiny mb-3 mx-auto">
                            <img alt="User Avatar" src="{{ Auth::user()->image_url??'' }}" class="img-fluid rounded-circle object-fit-cover border border-primary border-3"
                                 alt="profile image" id="loginUserImage" style="width: 70px; height: 70px;">
                        </div>
                        <h5 class="text-gray-900 fw-bold mb-1">{{ (Auth::user()->full_name)??'' }}</h5>
                        <p class="mb-0 fw-normal fs-6 text-decoration-none text-muted">{{ (Auth::user()->email)??'' }}</p>
                    </div>
                    
                    {{-- Profile Links --}}
                    <ul class="list-unstyled pt-2 pb-2">
                        <li>
                            <a class="dropdown-item text-gray-700 editProfile py-2 px-4 transition-3s hover-bg-light" href="javascript:void(0)"
                               data-bs-toggle="modal" data-bs-target="#editProfileModal"
                               data-id="{{ getLoggedInUserId() }}">
                            <span class="dropdown-icon me-3 text-primary opacity-75">
                                <i class="fa-solid fa-user fa-fw"></i>
                            </span>
                                {{ __('messages.user.edit_profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-gray-700 py-2 px-4 transition-3s hover-bg-light" href="javascript:void(0)"
                               data-id="{{ getLoggedInUserId() }}"
                               data-bs-toggle="modal"
                               data-bs-target="#changePasswordModal">
                                 <span class="dropdown-icon me-3 text-primary opacity-75">
                                    <i class="fa-solid fa-lock fa-fw"></i>
                                </span>
                                {{ __('messages.user.change_password') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-gray-700 py-2 px-4 transition-3s hover-bg-light" href="javascript:void(0)"
                               data-id="{{ getLoggedInUserId() }}"
                               data-bs-toggle="modal"
                               data-bs-target="#changeLanguageModal">
                               <span class="dropdown-icon me-3 text-primary opacity-75">
                                    <i class="fa-solid fa-globe fa-fw"></i>
                               </span>
                                {{__('messages.profile.change_language')}}
                            </a>
                        </li>
                        <li class="border-top mt-2 pt-2">
                            <a class="dropdown-item text-danger py-2 px-4 transition-3s hover-bg-light" href="{{ route('logout.user') }}"
                               onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                                 <span class="dropdown-icon me-3 text-danger opacity-75">
                                    <i class="fa-solid fa-right-from-bracket fa-fw"></i>
                                </span>
                                {{ __('messages.user.logout') }}
                                <form id="logout-form" action="{{ route('logout.user') }}" method="POST" class="d-none">
                                    {{ csrf_field() }}
                                </form>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
    
    {{-- ☰ Another Hamburger button for responsive menu --}}
    <button type="button" class="btn p-0 ms-3 d-block d-xl-none header-btn" title="Toggle Nav Menu">
        <i class="fa-solid fa-bars fs-3 text-primary"></i>
    </button>
</header>
<div class="bg-overlay" id="nav-overly"></div>

<style>
/* Define a primary color variable for theme consistency */
:root {
    --primary-color: #0d6efd; /* Bootstrap blue */
    --light-bg: #f8f9fa; /* Very light gray for background/hover */
}

/* Custom Styles for a beautiful header */
.header {
    z-index: 1030;
    transition: all 0.3s ease;
}

/* General hover effect for icon buttons */
.hover-bg-light:hover {
    background-color: var(--light-bg) !important;
}

/* Custom Notification Badge Styling */
.badge-circle {
    min-width: 1.25rem;
    height: 1.25rem;
    padding: 0; /* Removed padding to rely fully on min-width/height */
    font-size: 0.75rem;
    font-weight: 700;
    line-height: 1;
    border-radius: 50% !important;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: translate(25%, -25%); /* Adjusted translate for cleaner positioning */
}

/* Style for the notification list scroll container */
.inner-scroll {
    overflow-x: hidden;
    padding-right: 1.5rem; /* Increased padding to visually contain items */
    padding-left: 1.5rem;
}

/* Notification item alignment and hover */
.bg-hover-light-primary:hover {
    background-color: rgba(13, 110, 253, 0.07) !important; /* Light primary color background on hover */
}

/* Make notification title look slightly better */
.notification-title {
    line-height: 1.2; /* Better readability */
}

/* Customizing the dropdown menu for shadow and border */
.dropdown-menu {
    border: 1px solid rgba(0, 0, 0, 0.05); /* Added subtle border */
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1) !important; /* Refined shadow */
}

/* User dropdown button styling */
.user-dropdown-btn {
    /* Use Bootstrap's built-in dropdown-toggle for the arrow if possible, 
       but keeping the manual font-awesome arrow in the original structure */
}

/* Icon transition for mode switcher and links */
.transition-3s {
    transition: all 0.3s ease-in-out;
}
</style>