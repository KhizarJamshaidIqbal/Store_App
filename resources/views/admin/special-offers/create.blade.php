@extends('admin.layouts.app')

@push('styles')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Additional Styles (optional, for customization) -->
    <style>
        .flatpickr-calendar {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .flatpickr-day.selected {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        input.flatpickr-input {
            padding-right: 2.5rem !important;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold leading-tight text-gray-900">
                Create Special Offer
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Add a new special offer or promotion
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.special-offers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       value="{{ old('title') }}" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="editor mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Discount Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="discount_amount" class="block text-sm font-medium text-gray-700">Discount Amount ($)</label>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ old('discount_amount') }}">
                    @error('discount_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Discount Percentage (%)</label>
                    <input type="number" name="discount_percentage" id="discount_percentage"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ old('discount_percentage') }}">
                    @error('discount_percentage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <div class="relative">
                        <input type="text" name="start_date" id="start_date"
                               class="flatpickr-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                               placeholder="Select start date and time"
                               value="{{ old('start_date') }}" required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1 cursor-pointer"
                             onclick="document.getElementById('start_date').focus()">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <div class="relative">
                        <input type="text" name="end_date" id="end_date"
                               class="flatpickr-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                               placeholder="Select end date and time"
                               value="{{ old('end_date') }}" required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1 cursor-pointer"
                             onclick="document.getElementById('end_date').focus()">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mt-6">
                <label for="image" class="block text-sm font-medium text-gray-700">Offer Image</label>
                <input type="file" name="image" id="image"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       accept="image/*">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status and Featured -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_featured" id="is_featured"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                           {{ old('is_featured') ? 'checked' : '' }}>
                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                        Featured Offer
                    </label>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="mt-6">
                <label for="terms_conditions" class="block text-sm font-medium text-gray-700">Terms & Conditions</label>
                <textarea name="terms_conditions" id="terms_conditions" rows="4"
                          class="editor mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('terms_conditions') }}</textarea>
                @error('terms_conditions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.special-offers.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Offers
                </a>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Offer
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for date pickers
            flatpickr("#start_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                defaultHour: new Date().getHours(),
                defaultMinute: new Date().getMinutes(),
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0]) {
                        const endPicker = document.querySelector("#end_date")._flatpickr;
                        endPicker.set('minDate', selectedDates[0]);
                    }
                }
            });

            flatpickr("#end_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                defaultHour: new Date().getHours(),
                defaultMinute: new Date().getMinutes()
            });

            // Set initial dates
            const now = new Date();
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);

            document.querySelector("#start_date")._flatpickr.setDate(now);
            document.querySelector("#end_date")._flatpickr.setDate(tomorrow);

            // Initialize TinyMCE for rich text editors
            tinymce.init({
                selector: '.editor',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'table', 'wordcount', 'textcolor'
                ],
                toolbar: [
                    'fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify',
                    'bullist numlist outdent indent | table link | code'
                ],
                toolbar_mode: 'wrap',
                font_family_formats: 'Arial=arial,helvetica,sans-serif; Times New Roman=times new roman,times,serif',
                font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt',
                content_style: `
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 14px;
                        line-height: 1.6;
                        color: #333;
                        padding: 0.5rem;
                    }
                `,
                branding: false,
                statusbar: false,
                resize: false
            });
        });
    </script>
@endpush
