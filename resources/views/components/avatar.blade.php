@props(['user'])

<div class="inline-flex items-center justify-center w-6 h-6 overflow-hidden rounded-full {{ $user->avatar ? 'bg-transparent' : 'bg-light-green' }}">
  @if($user->avatar)
    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Avatar" class="w-10 h-10 object-fit: cover">
  @else
    <span class="text-base font-semibold text-white flex items-center justify-center">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
  @endif
</div>
