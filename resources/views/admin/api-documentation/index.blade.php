@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">API Documentation</h1>
            <p class="mt-2 text-gray-600">Complete guide to using our REST API endpoints</p>
        </div>

        <!-- Base URL Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Base URL</h2>
            <div class="bg-gray-100 rounded-lg p-4">
                <code class="text-blue-600">{{ config('app.url') }}/api/v1</code>
            </div>
            <p class="mt-4 text-gray-600">
                All API requests must include the following headers:
            </p>
            <div class="mt-2 space-y-2">
                <div class="flex items-center gap-4">
                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">Accept: application/json</code>
                </div>
                <div class="flex items-center gap-4">
                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">Content-Type: application/json</code>
                </div>
                <div class="flex items-center gap-4">
                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">Authorization: Bearer {token}</code>
                    <span class="text-gray-500 text-sm">(For authenticated endpoints)</span>
                </div>
            </div>
        </div>

        <!-- API Endpoints -->
        @foreach($apiDocs as $category => $endpoints)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold mb-6">{{ $category }}</h2>

            <div class="space-y-8">
                @foreach($endpoints as $endpoint)
                <div class="border-b border-gray-200 pb-8 last:border-0 last:pb-0">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($endpoint['method'] === 'GET') bg-green-100 text-green-800
                            @elseif($endpoint['method'] === 'POST') bg-blue-100 text-blue-800
                            @elseif($endpoint['method'] === 'PUT') bg-yellow-100 text-yellow-800
                            @elseif($endpoint['method'] === 'DELETE') bg-red-100 text-red-800
                            @endif">
                            {{ $endpoint['method'] }}
                        </span>
                        <h3 class="text-lg font-medium">{{ $endpoint['name'] }}</h3>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Endpoint</h4>
                        <code class="block bg-gray-100 p-3 rounded-lg text-blue-600">{{ $endpoint['endpoint'] }}</code>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Description</h4>
                        <p class="text-gray-700">{{ $endpoint['description'] }}</p>
                    </div>

                    @if(!empty($endpoint['auth']))
                    <div class="mb-4">
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                            Authentication {{ $endpoint['auth'] }}
                        </span>
                    </div>
                    @endif

                    @if(!empty($endpoint['parameters']))
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Parameters</h4>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <pre class="text-sm text-gray-700">{{ json_encode($endpoint['parameters'], JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif

                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Example Response</h4>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <pre class="text-sm text-gray-700">{{ json_encode($endpoint['response'], JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>

                    <!-- Postman Testing Button -->
                    <div class="mt-4">
                        <button onclick="copyPostmanCommand('{{ $endpoint['method'] }}', '{{ config('app.url') }}{{ $endpoint['endpoint'] }}')"
                                class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200">
                            <img src="https://www.postman.com/favicon-32x32.png" alt="Postman" class="w-4 h-4 mr-2">
                            Copy Postman Command
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function copyPostmanCommand(method, url) {
    const command = `curl -X ${method} "${url}" \\
-H "Accept: application/json" \\
-H "Content-Type: application/json"`;

    navigator.clipboard.writeText(command).then(() => {
        alert('Copied to clipboard! You can now paste this in Postman.');
    });
}
</script>
@endpush
@endsection
