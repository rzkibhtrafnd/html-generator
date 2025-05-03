<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class HtmlGeneratorController extends Controller
{
    public function index()
    {
        return view('form');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'data_file' => 'required|file|mimes:xlsx,csv',
            'template_text' => 'required|string',
        ]);

        try {
            // Simpan file Excel
            $dataPath = $request->file('data_file')->store('uploads');
            $templateContent = $request->input('template_text');

            // Baca data Excel
            $data = Excel::toArray([], Storage::path($dataPath))[0];
            
            if (count($data) < 1) {
                throw new \Exception("File Excel kosong atau format tidak valid");
            }

            // Ambil header dan data
            $headers = array_map('trim', array_shift($data));
            $rows = [];
            
            foreach ($data as $row) {
                if (count($row) !== count($headers)) {
                    \Log::error("Jumlah kolom tidak sesuai dengan header", ['row' => $row]);
                    continue;
                }
                $rows[] = array_combine($headers, $row);
            }

            if (empty($rows)) {
                throw new \Exception("Tidak ada data yang valid untuk diproses");
            }

            // Siapkan direktori
            $uploadId = uniqid();
            $previewDir = "previews/{$uploadId}";
            $zipName = "hasil_{$uploadId}.zip";
            
            Storage::disk('public')->makeDirectory('results');
            $zipPath = Storage::disk('public')->path("results/{$zipName}");

            Storage::makeDirectory($previewDir);

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                foreach ($rows as $i => $row) {
                    $html = $templateContent;
                    
                    foreach ($row as $key => $val) {
                        $pattern = '/\{\{\s*' . preg_quote(trim($key), '/') . '\s*\}\}/';
                        $html = preg_replace($pattern, $val, $html);
                    }

                    $filename = "html_{$i}.html";
                    Storage::put("{$previewDir}/{$filename}", $html);
                    $zip->addFromString($filename, $html);
                }
                
                $zip->close();
            } else {
                throw new \Exception("Gagal membuat file ZIP");
            }

            // Simpan ke database
            $upload = Upload::create([
                'excel_file' => $dataPath,
                'template_file' => $templateContent,
                'generated_count' => count($rows),
                'zip_path' => "results/{$zipName}",
                'preview_path' => $previewDir,
            ]);

            return redirect()->route('preview', $upload->id)->with('success', 'File berhasil digenerate!');
            
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function preview($id)
    {
        $upload = Upload::findOrFail($id);
        $previewFiles = Storage::files($upload->preview_path);
        
        $fileList = [];
        foreach ($previewFiles as $file) {
            $fileList[] = [
                'filename' => basename($file),
                'path' => $file,
            ];
        }
    
        return view('preview-list', compact('upload', 'fileList'));
    }
    
    public function previewFile($id, $filename)
    {
        $upload = Upload::findOrFail($id);
        $filePath = $upload->preview_path . '/' . $filename;
        
        if (!Storage::exists($filePath)) {
            abort(404);
        }
    
        $content = Storage::get($filePath);
    
        return view('preview-content', [
            'upload' => $upload,
            'filename' => $filename,
            'content' => $content
        ]);
    }

    public function history()
    {
        $uploads = Upload::latest()->paginate(10);
        return view('history', compact('uploads'));
    }
}
