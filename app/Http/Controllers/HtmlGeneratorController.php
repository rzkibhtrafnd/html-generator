<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
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
            'data_file' => 'required|file|mimes:xlsx',
            'template_text' => 'required|string',
        ]);
    
        try {
            $dataPath = $request->file('data_file')->store('uploads');
            $templateContent = $request->input('template_text');
    
            // Load spreadsheet
            $spreadsheet = IOFactory::load(Storage::path($dataPath));
            $worksheet = $spreadsheet->getActiveSheet();
    
            $rows = [];
            $headers = [];
    
            foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $rowData = [];
    
                foreach ($cellIterator as $cellIndex => $cell) {
                    $value = $cell->getValue();
                    if ($rowIndex === 1) {
                        $headers[] = trim($value);
                    } else {
                        $rowData[] = $value;
                    }
                }
    
                if ($rowIndex > 1) {
                    $rows[] = array_combine($headers, $rowData);
                }
            }
    
            // Handle images
            $drawings = $worksheet->getDrawingCollection();
            foreach ($drawings as $drawing) {
                $coordinates = $drawing->getCoordinates();
                [$col, $row] = Coordinate::coordinateFromString($coordinates);
                $imageData = null;
    
                // Convert image to base64
                ob_start();
                $drawing->getImageResource() && imagepng($drawing->getImageResource());
                $imageData = ob_get_contents();
                ob_end_clean();
    
                if ($imageData) {
                    $base64 = 'data:image/png;base64,' . base64_encode($imageData);
                    $colIndex = Coordinate::columnIndexFromString($col) - 1;
                    $rows[$row - 2][$headers[$colIndex]] = $base64; // row -2 karena header dan index 0
                }
            }
    
            // ZIP generation (same as your code)
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
            }
    
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
