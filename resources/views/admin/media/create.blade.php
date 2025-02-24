@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Upload Files</h1>
            <p class="mt-2 text-gray-600">Add images, documents, and other files to your media library</p>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.media.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-6"
                  x-data="{
                      files: null,
                      filePreviews: [],
                      handleFiles(e) {
                          this.files = e.target.files;
                          this.filePreviews = [];

                          for (let i = 0; i < this.files.length; i++) {
                              const file = this.files[i];
                              if (file.type.startsWith('image/')) {
                                  const reader = new FileReader();
                                  reader.onload = (e) => {
                                      this.filePreviews.push({
                                          name: file.name,
                                          size: (file.size / 1024).toFixed(2),
                                          preview: e.target.result,
                                          type: 'image'
                                      });
                                  };
                                  reader.readAsDataURL(file);
                              } else {
                                  this.filePreviews.push({
                                      name: file.name,
                                      size: (file.size / 1024).toFixed(2),
                                      type: 'file'
                                  });
                              }
                          }
                      }
                  }">
                @csrf

                <!-- Drop Zone -->
                <div class="mb-6">
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 transition-colors"
                         x-on:dragover.prevent="$el.classList.add('border-blue-500')"
                         x-on:dragleave.prevent="$el.classList.remove('border-blue-500')"
                         x-on:drop.prevent="handleFiles($event)">

                        <input type="file"
                               name="files[]"
                               multiple
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               x-on:change="handleFiles($event)">

                        <div class="space-y-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div class="text-gray-600">
                                <span class="font-medium text-blue-600">Click to upload</span> or drag and drop files here
                            </div>
                            <p class="text-sm text-gray-500">
                                Maximum file size: 10MB
                            </p>
                        </div>
                    </div>
                </div>

                <!-- File Previews -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6" x-show="filePreviews.length > 0">
                    <template x-for="(file, index) in filePreviews" :key="index">
                        <div class="relative bg-gray-50 rounded-lg p-2 group">
                            <!-- Image Preview -->
                            <template x-if="file.type === 'image'">
                                <img :src="file.preview" class="w-full h-32 object-cover rounded-lg">
                            </template>

                            <!-- File Icon -->
                            <template x-if="file.type === 'file'">
                                <div class="w-full h-32 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                                </div>
                            </template>

                            <!-- File Info -->
                            <div class="mt-2">
                                <p class="text-sm truncate" x-text="file.name"></p>
                                <p class="text-xs text-gray-500" x-text="file.size + ' KB'"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Error Messages -->
                @error('files.*')
                    <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                @enderror

                <!-- Submit Button -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.media.index') }}"
                       class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Upload Files
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
