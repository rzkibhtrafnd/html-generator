<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit History Generate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-inter bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Edit History Generate</h1>
                <a href="{{ route('history') }}" 
                   class="text-gray-600 hover:text-blue-700 font-medium flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke History
                </a>
            </div>

            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
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

            <form action="{{ route('history.update', $upload->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2">File Excel (Biarkan kosong jika tidak ingin mengubah)</label>
                    <div class="relative">
                        <input type="file" name="data_file" id="data_file" accept=".xlsx"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition
                                   file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <p class="mt-2 text-sm text-gray-600">File saat ini: 
                        <span class="font-medium">{{ basename($upload->excel_file) }}</span>
                        <a href="{{ route('history.download.excel', $upload->id) }}" class="text-blue-600 hover:text-blue-800 ml-2">(Download)</a>
                    </p>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Template HTML</label>
                    <textarea name="template_text" id="template_text" rows="10" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition
                               font-mono text-sm">{{ old('template_text', $upload->template_file) }}</textarea>
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
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('history') }}" 
                       class="text-gray-600 hover:text-blue-700 font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>