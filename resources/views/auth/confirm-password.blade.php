<x-guest-layout>
  
<div class="max-w-md mx-auto p-6 bg-white rounded shadow mt-8">
    <h2 class="text-2xl font-bold mb-4">Confirm Password</h2>
    <p class="mb-6 text-gray-700">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="block font-medium text-gray-700 mb-2">Password</label>
            <input id="password" name="password" type="password" required autofocus
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Confirm Password
            </button>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
        </div>
    </form>
</div>

</x-guest-layout>
