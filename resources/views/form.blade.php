<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>HTML Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-inter bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Generate HTML dari Excel</h1>
            
            <form method="POST" action="{{ route('generate') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">File Excel</label>
                    <div class="relative">
                        <input type="file" name="data_file" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition
                                   file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    @error('data_file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Template HTML</label>
                    <textarea name="template_text" rows="10" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition
                               font-mono text-sm">{{ old('template_text') }}</textarea>
                    @error('template_text')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-2 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded p-4">
                        <p class="font-semibold mb-1">Format Variabel:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Gunakan <code>{{ '{{ NamaKolomExcel' }}}}</code> untuk menampilkan data dari kolom.</li>
                            <li>Contoh: <code>{{ '{{ Nama' }}}}</code>, <code>{{ '{{ Email' }}}}</code>, <code>{{ '{{ Foto' }}}}</code></li>
                            <li>Untuk gambar: <code>&lt;img src="{{ '{{ Foto' }}}}" alt="Foto"&gt;</code></li>
                        </ul>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition-transform hover:scale-[1.02]">
                        Generate
                    </button>
                    <a href="{{ route('history') }}" 
                       class="text-gray-600 hover:text-blue-700 font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        Riwayat Generate
                    </a>
                </div>
            </form>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>