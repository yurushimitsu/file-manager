<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class FileController extends Controller
{
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

    public function downloadFolder(Request $request) {
        $folder = $request->query('folder'); // e.g., "some-folder"
        
        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));
        $pathSegments = explode('/', trim($currentFolder, '/'));
        $firstFolder = $pathSegments[0] ?? '';
        
        $folderPath = storage_path("app/public/{$firstFolder}/{$folder}");
        $zipDir = storage_path('app/temp');
        $folderName = basename($folder);
        $zipFileName = Str::slug($folderName) . '.zip';
        $zipPath = $zipDir . '/' . $zipFileName;
    
        // Make sure temp directory exists
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0755, true);
        }
    
        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            abort(404, 'Folder not found.');
        }
    
        // Check if folder contains files
        $hasFiles = false;
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folderPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
    
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $hasFiles = true;
                break;
            }
        }

        if (!$hasFiles) {
            return response()->json(['error' => 'The folder is empty.'], 400);
        }
    
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add files to the zip archive
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folderPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
    
            $zip->close();
        }
    
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
    

    public function updateFileName(Request $request) {
        $oldFileName = $request->input('old_file_name');
        $newFileName = $request->input('new_file_name');

        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        // Ensure the old and new file names are not empty
        if (!$oldFileName || !$newFileName) {
            return response()->json(['success' => false, 'message' => 'File names are required']);
        }

        // Get the full path of the old and new file names
        $oldFilePath = 'public' . $currentFolder . '/' . $oldFileName;
        $newFilePath = 'public' . $currentFolder . '/' . $newFileName;

        // Check if the file exists
        if (!Storage::exists($oldFilePath)) {
            return response()->json(['success' => false, 'message' => 'File does not exist: '. $oldFilePath]);
        }

        // Check if the new file already exists
        if (Storage::exists($newFilePath)) {
            return response()->json(['success' => false, 'message' => 'Filename already exists: '. $newFileName]);
        }

        // Rename the file
        try {
            // Rename the file on the storage disk (this will also move the file if a new folder is specified)
            Storage::move($oldFilePath, $newFilePath);

            // Return success response
            return response()->json(['success' => true, 'message' => $oldFileName. ' has been renamed to '. $newFileName]);
        } catch (\Exception $e) {
            // Return error response in case of failure
            return response()->json(['success' => false, 'message' => 'Failed to update the file']);
        }
    }

    public function showArchive($folder = null) {
        if (!File::exists(storage_path('app/public/archive'))) {
            File::makeDirectory(storage_path('app/public/archive'), 0755, true);
        }

        $basePath = 'public/archive';

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
    
        return view('dashboard.archive', compact('files', 'directories', 'folderSizes', 'folderExploded', 'currentFolder', 'docuFolderFileSize'));
    }

    public function showTrash($folder = null) {
        if (!File::exists(storage_path('app/public/trash'))) {
            File::makeDirectory(storage_path('app/public/trash'), 0755, true);
        }

        $basePath = 'public/trash';

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
    
        return view('dashboard.trash', compact('files', 'directories', 'folderSizes', 'folderExploded', 'currentFolder', 'docuFolderFileSize'));
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

    public function moveToArchive(Request $request) {

        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $itemName = $request->input('item');  // File or Folder name
        $isFolder = $request->input('isFolder');  // Is it a folder?

        // Define paths
        $documentPath = 'public' . $currentFolder . '/' . $itemName;
        $archivePath = 'public/archive/' . $itemName;

        // For file handling
        if (!$isFolder) {
            if (Storage::exists($documentPath)) {
                try {
                    // Ensure the archive folder exists
                    if (!File::exists(storage_path('app/public/archive'))) {
                        File::makeDirectory(storage_path('app/public/archive'), 0755, true);
                    }

                    // Move the file to archive
                    if (Storage::move($documentPath, $archivePath)) {
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
            $folderArchivePath = storage_path('app/public/archive/' . $itemName);
            
            if (File::isDirectory($folderPath)) {
                try {
                    // Ensure the trash archive exists
                    if (!File::exists(storage_path('app/public/archive'))) {
                        File::makeDirectory(storage_path('app/public/archive'), 0755, true);
                    }

                    // Move the folder to archive
                    if (File::move($folderPath, $folderArchivePath)) {
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

    public function restoreFiles(Request $request) {
        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $itemName = $request->input('item');  // File or Folder name
        $isFolder = $request->input('isFolder');  // Is it a folder?

        // Define paths
        $documentPath = 'public' . $currentFolder . '/' . $itemName;
        $trashPath = 'public/documents/' . $itemName;

        // For file handling
        if (!$isFolder) {
            if (Storage::exists($documentPath)) {
                try {
                    // Ensure the trash folder exists
                    if (!File::exists(storage_path('app/public/documents'))) {
                        File::makeDirectory(storage_path('app/public/documents'), 0755, true);
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
            $folderTrashPath = storage_path('app/public/documents/' . $itemName);
            
            if (File::isDirectory($folderPath)) {
                try {
                    // Ensure the trash folder exists
                    if (!File::exists(storage_path('app/public/documents'))) {
                        File::makeDirectory(storage_path('app/public/documents'), 0755, true);
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

    public function deleteFile(Request $request) {
        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $itemName = $request->input('item');     // File or Folder name
        $isFolder = $request->input('isFolder'); // true/false
        $isEmpty = $request->input('isEmpty'); // true/false

        if ($isEmpty) {
            $trashPath = storage_path('app/public/trash');
    
            try {
                if (File::exists($trashPath)) {
                    File::deleteDirectory($trashPath); // Delete the trash folder and its contents
                    File::makeDirectory($trashPath);   // Recreate empty trash folder if needed
                    return response()->json(['success' => true, 'message' => 'Trash emptied successfully.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Trash folder not found.'], 404);
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

        // Full relative path to the item
        $relativePath = 'public' . $currentFolder . '/' . $itemName;

        try {
            if ($isFolder) {
                $folderPath = storage_path('app/' . $relativePath);

                if (File::isDirectory($folderPath)) {
                    File::deleteDirectory($folderPath);
                    return response()->json(['success' => true, 'message' => 'Folder permanently deleted.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Folder not found.'], 404);
                }
            } else {
                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                    return response()->json(['success' => true, 'message' => 'File permanently deleted.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'File not found.'], 404);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
