<!doctype html>
<html class="h-full bg-gray-50">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body class="h-full">
<div class="min-h-full flex items-center justify-center sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="hidden sm:block sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-12 w-auto" src="/imgs/logo.svg" alt="{{ config('app.name') }}" />
    </div>

    <div class="sm:mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="sm:hidden mb-10">
            <img class="mx-auto h-12 w-auto" src="/imgs/logo.svg" alt="{{ config('app.name') }}" />
        </div>
        <div class="bg-white py-4 sm:py-8 px-8 shadow sm:rounded-lg sm:px-10">
            <h2 class="sm:pb-6 text-center text-xl sm:text-3xl tracking-tight font-bold text-gray-900">{{__('Sign in to your account') }}</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700"> {{__('Email address') }} </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required class="@error('email') border-red-700 @else border-gray-300 @enderror appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email')
                        <span class="text-xs text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700"> {{__('Password') }} </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="@error('password') border-red-700 @else border-gray-300 @enderror appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ">
                        @error('password')
                        <span class="text-xs text-red-700">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col space-y-4 sm:flex-row items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 block text-sm text-gray-900"> {{__('Remember me') }} </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500"> {{__('Forgot your password?') }} </a>
                    </div>
                </div>
                <input type="hidden" name="{{ uniqid('security_check-') }}" value="">
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{__('Sign in') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>