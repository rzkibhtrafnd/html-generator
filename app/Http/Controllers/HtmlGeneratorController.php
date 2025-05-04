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
    
            // Extract headers
            $headers = [];
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
            
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $header = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                $headers[$col] = trim($header);
            }
            
            // Get highest row
            $highestRow = $worksheet->getHighestRow();
            
            // Initialize rows array
            $rows = [];
            
            // Create mapping of images by coordinates
            $imageMapping = [];
            $drawings = $worksheet->getDrawingCollection();
            
            foreach ($drawings as $drawing) {
                $coordinates = $drawing->getCoordinates();
                list($col, $row) = Coordinate::coordinateFromString($coordinates);
                $colIndex = Coordinate::columnIndexFromString($col);
                
                // Get image content and convert to base64
                $imageContents = null;
                $imageExtension = 'png'; // Default extension
                
                if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\Drawing) {
                    $zipReader = fopen($drawing->getPath(), 'r');
                    if ($zipReader !== false) {
                        $imageContents = stream_get_contents($zipReader);
                        fclose($zipReader);
                        
                        // Get file extension
                        $extension = pathinfo($drawing->getPath(), PATHINFO_EXTENSION);
                        if (!empty($extension)) {
                            $imageExtension = strtolower($extension);
                        }
                    }
                } elseif ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
                    ob_start();
                    call_user_func(
                        $drawing->getRenderingFunction(),
                        $drawing->getImageResource()
                    );
                    $imageContents = ob_get_contents();
                    ob_end_clean();
                }
                
                if ($imageContents) {
                    $base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($imageContents);
                    $imageMapping[$row][$colIndex] = $base64;
                }
            }
            
            // Extract data rows with text and images
            for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
                $rowData = [];
                
                for ($colIndex = 1; $colIndex <= $highestColumnIndex; $colIndex++) {
                    $value = $worksheet->getCellByColumnAndRow($colIndex, $rowIndex)->getValue();
                    
                    // If there's an image at this cell, use the image instead of text
                    if (isset($imageMapping[$rowIndex][$colIndex])) {
                        $value = $imageMapping[$rowIndex][$colIndex];
                    }
                    
                    if (isset($headers[$colIndex])) {
                        $rowData[$headers[$colIndex]] = $value;
                    }
                }
                
                $rows[] = $rowData;
            }
    
            // ZIP generation
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

    // Tambahkan method ini untuk memudahkan pengguna melihat contoh template
    public function exampleTemplate()
    {
        return view('example-template');
    }
}