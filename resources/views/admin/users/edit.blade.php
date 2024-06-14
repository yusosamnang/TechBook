<x-admin-layout>
    <div class="container mt-5" style="max-width: 400px;"> <!-- Decrease width by setting max-width -->
        <!-- <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mx-auto d-block"/>
        </div> -->

        <b class="text-center mb-4 h3">Edit User</b>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
            </div>

            <div class="form-group mt-4">
                <x-input-label for="email" :value="__('Email address')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $user->email)" required autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>

            @if ($user->userType !== 'admin')
                <div class="form-group mt-4">
                    <x-input-label for="role" :value="__('Role')" />
                    <x-text-input id="role" class="form-control" type="text" name="role" :value="old('userType', $user->userType)" required />
                    <x-input-error :messages="$errors->get('role')" class="mt-2 text-danger" />
                </div>
            @endif

            <div class="form-group mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="form-control" type="password" name="password" autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
            </div>

            <div class="form-group mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</x-admin-layout>
