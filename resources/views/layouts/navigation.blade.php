<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>

    <!-- Include jQuery first -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>var jQuery = jQuery.noConflict();</script>

    <!-- Include Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.5/cdn.min.js" integrity="sha512-y22y4rJ9d7NGoRLII5LVwUv0BfQKf6MpATy5ebVu/fbHdtJCxBpZRHZMHJv8VYl8scWjuwZcMPzwYk4sEoyL4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        img {
            width: 60px;
            height: 50px;
            margin-right: 30px;
        }
        .bg-blue {
            background-color: #0500FF;
        }
        .logo {
            margin-right: auto;
        }
        .user-img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .bg-light-green {
            background-color: #00c04b;
        }
        .navbar {
            display: flex;
            align-items: center;
            background-color: #007BFF;
            color: white;
            padding: 8px 20px;
        }
        .search-bar {
            position: relative;
            width: 550px;
            height: 20px;
            flex-grow: 1;
            margin: 32px 10px 50px;
        }
        .search-input {
            padding: 5px 10px;
            width: 100%;
            border: 2px solid white;
            border-radius: 20px;
            outline: none;
            color: #007BFF;
            background-color: white;
        }
        .search-icon {
            position: absolute;
            right: 10px;
            top: 100%;
            transform: translateY(-50%);
            color: #007BFF;
            cursor: pointer;
        }
        .icon {
            margin-left: 25px;
            cursor: pointer;
            position: relative;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            color: black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            min-width: 300px; /* Set a minimum width */
            max-width: 100%;  /* Allow it to expand as needed */
            font-size: 14px;
            z-index: 1000;
        }
        .dropdown-menu a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: black;
            white-space: nowrap;
        }
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        .badge-danger {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 5px 10px;
            border-radius: 50%;
            background-color: red;
            color: white;
        }
        .unread {
            font-weight: bold;
        }
    </style>
</head>
<body>
<nav x-data="{ open: false }" class="border-b border-gray-100" style="background-color: #33C2FF;">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-2">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex items-center justify-center logo">
                        <a href="{{ route('admin.users.index') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo">
                        </a>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Search Bar -->
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <form action="{{ route('home') }}" method="GET">
                            <input type="text" id="search" name="search" placeholder="Search by Title or Author..." class="search-input" value="{{ request()->query('search') }}">
                            <button type="submit"><i class="fas fa-search search-icon"></i></button>
                        </form>
                    </div>
                    @auth
                    <!-- Notification Icon -->
                    <div class="icon" id="notificationWrapper">
                        <i class="fas fa-bell" id="notificationIcon">
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge badge-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </i>
                        <!-- Blade Template -->
                        <div class="dropdown-menu" id="notificationsDropdown">
                            @foreach (auth()->user()->notifications as $notification)
                                <a href="#" class="{{ is_null($notification->read_at) ? 'unread' : '' }}" onclick="openNotificationModal('{{ $notification->id }}', '{{ $notification->data['book_title'] ?? 'Book Request' }}', '{{ ucfirst($notification->data['status']) }}')">
                                    <strong>{{ $notification->data['book_title'] ?? 'Book Request' }}</strong>: {{ ucfirst($notification->data['status']) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Menu Icon -->
                    <div class="icon" id="menuWrapper">
                        <i class="fas fa-bars" id="menuIcon"></i>
                        <div class="dropdown-menu" id="menuDropdown">
                            <a href="/favorites">Saved</a>
                            <a href="/cart">Cart</a>
                            <a href="/book_requests">Request New Book</a>
                            <a href="/user/books">Request Book for Sell</a>
                        </div>
                    </div>
                    
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="icon inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <x-avatar :user="Auth::user()"/>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @endauth

                    @guest
                        <div class="icon">
                            <button class="bg-blue hover:bg-blue text-white font-bold py-2 px-4 rounded">
                                <a href="{{ route('login') }}" class="text-white"><b>Login</b></a>
                            </button>
                        </div>
                        <div class="icon">
                            <button class="bg-gray-500 hover:bg-blue text-white font-bold py-2 px-4 rounded">
                                <a href="{{ route('register') }}" class="text-white"><b>Register</b></a>
                            </button>
                        </div>
                    @endguest
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                @auth
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            window.openNotificationModal = function(notificationId, title, status) {
                // Display alert with the book title
                alert(title);

                // Update notification details in the modal or wherever needed
                $('#notificationDetails').text(`Title: ${title}, Status: ${status}`);

                // Ajax call to mark notification as read
                $.ajax({
                    url: '{{ route('notifications.read') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        notificationId: notificationId
                    },
                    success: function() {
                        // Reload the page or update UI as needed
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error marking notification as read:", error);
                        // Handle the error appropriately
                    }
                });
            };

            $('#notificationIcon').on('click', function(event) {
                event.stopPropagation();
                $('#notificationsDropdown').toggle();
            });

            $('#menuIcon').on('click', function(event) {
                event.stopPropagation();
                $('#menuDropdown').toggle();
            });

            $(document).on('click', function(event) {
                if (!$(event.target).closest('#notificationWrapper').length) {
                    $('#notificationsDropdown').hide();
                }
                if (!$(event.target).closest('#menuWrapper').length) {
                    $('#menuDropdown').hide();
                }
            });
        });
    </script>
</body>
</html>
