@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl shadow-lg">
                <i class="fas fa-envelope"></i>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Verify Your Email Address
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10 border border-gray-100">
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-100">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p>A new verification link has been sent to the email address you provided during registration.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Resend Verification Email
                        </button>
                    </form>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or</span>
                    </div>
                </div>

                <div class="text-center">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-600 hover:text-gray-900 hover:underline transition-all duration-200">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
