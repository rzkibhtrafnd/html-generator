<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar File Hasil Generate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-inter bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto p-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="border-b border-gray-200 px-6 py-5 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Daftar File Hasil Generate</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="font-medium">Total File:</span> {{ $upload->generated_count }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ asset('storage/' . $upload->zip_path) }}" 
                       class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Download ZIP
                    </a>
                    <a href="{{ route('history') }}" 
                       class="flex items-center gap-2 px-4 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- File List -->
            <div class="divide-y divide-gray-200">
                @forelse ($fileList as $file)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-800">{{ $file['filename'] }}</span>
                        </div>
                        <a href="{{ route('preview.file', ['id' => $upload->id, 'filename' => $file['filename']]) }}" 
                           class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors flex items-center gap-2">
                            Preview
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada file</h3>
                        <p class="mt-1 text-gray-500">Tidak ada file yang tersedia untuk preview</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>