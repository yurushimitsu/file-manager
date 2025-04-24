<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    // public function showDocuments() {
    //     $files = Storage::files('public/documents');
    //     $directories = Storage::directories('public/documents');

    //     // Calculate folder sizes
    //     $folderSizes = [];
    //     foreach ($directories as $directory) {
    //         $folderSizes[$directory] = $this->getFolderSize($directory);
    //     }

    //     // dd($files);

    //     return view('dashboard.documents', compact('files', 'directories', 'folderSizes'));
    // }

    public function showDocuments($folder = null) {
        if (!File::exists(storage_path('app/public/documents'))) {
            File::makeDirectory(storage_path('app/public/documents'), 0755, true);
        }

        $basePath = 'public/documents';

        $docuFolderFileSize = $this->getFolderSize($basePath);
    
        if ($folder) {
            $path = $basePath . '/' . $folder;
        } else {
            $path = $basePath;
        }
        
        $folderExploded = $folder ? explode('/', $folder) : [];
        $currentFolder = end($folderExploded);
    
        $files = Storage::files($path);
        $directories = Storage::directories($path);
    
        $folderSizes = [];
        foreach ($directories as $directory) {
            $folderSizes[$directory] = $this->getFolderSize($directory);
        }
    
        return view('dashboard.documents', compact('files', 'directories', 'folderSizes', 'folderExploded', 'currentFolder', 'docuFolderFileSize'));
    }

    public function showTrash($folder = null) {
        if (!File::exists(storage_path('app/public/trash'))) {
            File::makeDirectory(storage_path('app/public/trash'), 0755, true);
        }

        $basePath = 'public/trash';

        $trashFolderFileSize = $this->getFolderSize($basePath);

    
        if ($folder) {
            $path = $basePath . '/' . $folder;
        } else {
            $path = $basePath;
        }
        
        $folderExploded = $folder ? explode('/', $folder) : [];
        $currentFolder = end($folderExploded);
    
        $files = Storage::files($path);
        $directories = Storage::directories($path);
    
        $folderSizes = [];
        foreach ($directories as $directory) {
            $folderSizes[$directory] = $this->getFolderSize($directory);
        }
    
        return view('dashboard.trash', compact('files', 'directories', 'folderSizes', 'folderExploded', 'currentFolder', 'trashFolderFileSize'));
    }


    public function getFolderSize($directory) {
        $totalSize = 0;
        $files = Storage::allFiles($directory);

        foreach ($files as $file) {
            $totalSize += Storage::size($file); 
        }

        return $totalSize;
    }

    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $relativePath = $request->input('relativePath'); // folder1/file.txt
        $storagePath = 'public' . $currentFolder . '/' . dirname($relativePath);
        $fileName = basename($relativePath);

        Storage::makeDirectory($storagePath);

        $storedPath = $request->file('file')->storeAs($storagePath, $fileName);

        return response()->json([
            'success' => true,
            'filename' => $fileName,
            'path' => $storedPath,
            'url' => Storage::url($storedPath),
        ]);
    }

    public function checkIfExists(Request $request)
    {
        $relativePath = $request->input('relativePath');

        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $fullPath = 'public' . $currentFolder . '/' . $relativePath;

        return response()->json([
            'exists' => Storage::exists($fullPath),
        ]);
    }

    public function createFolder (Request $request) {
        $request->validate([
            'folderName' => 'required|string|max:255'
        ]);

        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $folderName = $request->input('folderName');        
        if ($currentFolder == '/dashboard') {
            $path = storage_path('app/public/documents/' . $folderName);
        } else {
            $path = storage_path('app/public'. $currentFolder . '/' . $folderName);
        }

        // Check if the folder already exists
        if (File::exists($path)) {
            return response()->json(['message' => 'Folder already exists'], 400);
        }

        // Create the folder
        if (File::makeDirectory($path)) {
            return response()->json(['message' => 'Folder created successfully'], 200);
        }

        return response()->json(['message' => 'Failed to create folder'], 500);
    }

    public function moveToTrash(Request $request) {

        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $itemName = $request->input('item');  // File or Folder name
        $isFolder = $request->input('isFolder');  // Is it a folder?

        // Define paths
        $documentPath = 'public' . $currentFolder . '/' . $itemName;
        $trashPath = 'public/trash/' . $itemName;

        // For file handling
        if (!$isFolder) {
            if (Storage::exists($documentPath)) {
                try {
                    // Ensure the trash folder exists
                    if (!File::exists(storage_path('app/public/trash'))) {
                        File::makeDirectory(storage_path('app/public/trash'), 0755, true);
                    }

                    // Move the file to trash
                    if (Storage::move($documentPath, $trashPath)) {
                        return response()->json(['success' => true]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to move file.'], 500);
                    }
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
                }
            }
        }
        // For folder handling
        else {
            $folderPath = storage_path('app/public' . $currentFolder . '/' . $itemName);
            $folderTrashPath = storage_path('app/public/trash/' . $itemName);
            
            if (File::isDirectory($folderPath)) {
                try {
                    // Ensure the trash folder exists
                    if (!File::exists(storage_path('app/public/trash'))) {
                        File::makeDirectory(storage_path('app/public/trash'), 0755, true);
                    }

                    // Move the folder to trash
                    if (File::move($folderPath, $folderTrashPath)) {
                        return response()->json(['success' => true]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to move folder.'], 500);
                    }
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
                }
            }
        }

        return response()->json(['success' => false, 'message' => 'Item not found.' . $folderPath], 404);
    
    }

}
