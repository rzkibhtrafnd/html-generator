<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview: {{ $filename }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-inter bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto p-6"> <!-- max-w-6xl untuk lebar lebih besar -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="border-b border-gray-200 px-6 py-5 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Preview: {{ $filename }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('preview', $upload->id) }}" 
                       class="flex items-center gap-2 px-4 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- File Preview -->
            <div class="px-6 py-4">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="card-header">
                        <strong class="text-xl">File:</strong> {{ $filename }}
                    </div>
                    <div class="card-body preview-container">
                        {!! $content !!}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 flex gap-4 justify-between">
                <a href="{{ asset('storage/' . $upload->zip_path) }}" 
                   class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all">
                   Download Semua (ZIP)
                </a>
                
                <a href="{{ route('preview', $upload->id) }}" 
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all">
                   Lihat Semua File
                </a>
            </div>
        </div>
    </div>
</body>
</html>
