<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class FileController extends Controller
{

    public function showDashboard() {
        $photos = collect(Storage::disk('public')->allFiles('user/'.auth()->user()->id.'/media/Photos'));

        $docuFolderSize = $this->getFolderSize('public/user/'.auth()->user()->id.'/documents');
        $mediaFolderSize = $this->getFolderSize('public/user/'.auth()->user()->id.'/media');
        $archiveFolderSize = $this->getFolderSize('public/user/'.auth()->user()->id.'/archive');
        $trashFolderSize = $this->getFolderSize('public/user/'.auth()->user()->id.'/trash');
        $otherFolderSize = $this->getFolderSize('public/user/'.auth()->user()->id.'/others');

        $totalStorage = auth()->user()->storage_gb * 1024 * 1024 * 1024; // First number is the allotted max size (GB)
        $usedStorage = $docuFolderSize + $mediaFolderSize + $archiveFolderSize + $trashFolderSize + $otherFolderSize;
        $usedPercentage = ($usedStorage / $totalStorage) * 100;

        $docuFolderModified = $this->getLastModifiedDate('user/'.auth()->user()->id.'/documents');
        $mediaFolderModified = $this->getLastModifiedDate('user/'.auth()->user()->id.'/media');
        $archiveFolderModified = $this->getLastModifiedDate('user/'.auth()->user()->id.'/archive');
        $trashFolderModified = $this->getLastModifiedDate('user/'.auth()->user()->id.'/trash');
        $otherFolderModified = $this->getLastModifiedDate('user/'.auth()->user()->id.'/others');

        return view('dashboard.main', [
            'usedStorage' => $this->formatBytes($usedStorage),
            'totalStorage' => $this->formatBytes($totalStorage),
            'usedPercentage' => round($usedPercentage),
            'photos' => $photos,
            'docuFolderSize' => $docuFolderSize,
            'mediaFolderSize' => $mediaFolderSize,
            'archiveFolderSize' => $archiveFolderSize,
            'trashFolderSize' => $trashFolderSize,
            'otherFolderSize' => $otherFolderSize,
            'docuFolderModified' => $docuFolderModified,
            'mediaFolderModified' => $mediaFolderModified,
            'archiveFolderModified' => $archiveFolderModified,
            'trashFolderModified' => $trashFolderModified,
            'otherFolderModified' => $otherFolderModified,
        ]);
    }

    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function getLastModifiedDate($folderPath) {
        $files = Storage::disk('public')->allFiles($folderPath);

        if (empty($files)) {
            return null;
        }

        $lastModifiedTimestamp = collect($files)
            ->map(fn($file) => Storage::disk('public')->lastModified($file))
            ->max();

        return Carbon::createFromTimestamp($lastModifiedTimestamp)->format('h:i A | d M');
    }

    public function showDocuments($folder = null) {
        if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/documents'))) {
            File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/documents'), 0755, true);
        }

        $basePath = 'public/user/'.auth()->user()->id.'/documents';

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
        $folder = $request->query('folder');
        
        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));
        $pathSegments = explode('/', trim($currentFolder, '/'));
        $firstFolder = $pathSegments[0] ?? '';
        
        $folderPath = storage_path("app/public/user/".auth()->user()->id."/{$firstFolder}/{$folder}");
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
        $oldFilePath = 'public/user/' . auth()->user()->id . '/' . $currentFolder . '/' . $oldFileName;
        $newFilePath = 'public/user/' . auth()->user()->id . '/' . $currentFolder . '/' . $newFileName;

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

    public function showMedia($folder = null) {
        if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/media'))) {
            File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/media'), 0755, true);
        }

        $basePath = 'public/user/'.auth()->user()->id.'/media';

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
    
        return view('dashboard.media', compact('files', 'directories', 'folderSizes', 'folderExploded', 'currentFolder', 'docuFolderFileSize'));
    }

    public function showArchive($folder = null) {
        if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/archive'))) {
            File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/archive'), 0755, true);
        }

        $basePath = 'public/user/'.auth()->user()->id.'/archive';

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

    public function showOthers($folder = null) {
        if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/others'))) {
            File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/others'), 0755, true);
        }

        $basePath = 'public/user/'.auth()->user()->id.'/others';

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
    
        return view('dashboard.others', compact('files', 'directories', 'folderSizes', 'folderExploded', 'currentFolder', 'docuFolderFileSize'));
    }

    

    public function showTrash($folder = null) {
        if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/trash'))) {
            File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/trash'), 0755, true);
        }

        $basePath = 'public/user/'.auth()->user()->id.'/trash';

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
            'file' => 'required|file',
        ]);

        $storageLimit = auth()->user()->storage_gb * 1024 * 1024 * 1024;

        $currentStorageUsage = Storage::disk('public')->allFiles('user/'.auth()->user()->id);
        $totalSize = 0;

        // Calculate the total size of all files in the public disk
        foreach ($currentStorageUsage as $file) {
            $totalSize += Storage::disk('public')->size($file);
        }

        // Check if the current storage usage exceeds the limit
        if ($totalSize >= $storageLimit) {
            return response()->json([
                'success' => false,
                'message' => 'Storage limit reached. Cannot upload more files.',
            ], 400);
        }
        
        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));
        
        $relativePath = $request->input('relativePath'); // folder1/file.txt
        $fileName = basename($relativePath);

        $documentMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ];
        
        $mediaTypesPhotos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mediaTypesVideos = ['video/mp4', 'video/avi', 'video/mkv', 'video/webm', 'video/x-matroska'];
        
        $allMediaTypes = array_merge($mediaTypesPhotos, $mediaTypesVideos);
        $fileMimeType = $request->file('file')->getMimeType();
        
        if ($currentFolder === '/dashboard') {
            if (in_array($fileMimeType, $documentMimeTypes)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/documents/' . dirname($relativePath);
            } elseif (in_array($fileMimeType, $mediaTypesPhotos)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/media/Photos/' . dirname($relativePath);
            } elseif (in_array($fileMimeType, $mediaTypesVideos)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/media/Videos/' . dirname($relativePath);
            } else {
                // If the file is not a photo, video, or document, store it in the "other" folder
                $storagePath = 'public/user/'.auth()->user()->id.'/others/' . dirname($relativePath);
            }
        } elseif ($currentFolder !== '/media') {
            if (in_array($fileMimeType, $mediaTypesPhotos)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/media/Photos/' . dirname($relativePath);
            } elseif (in_array($fileMimeType, $mediaTypesVideos)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/media/Videos/' . dirname($relativePath);
            } elseif (in_array($fileMimeType, $documentMimeTypes)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/documents/' . dirname($relativePath);
            } else {
                // If the file is not a photo, video, or document, store it in the "other" folder
                $storagePath = 'public/user/'.auth()->user()->id.'/others/' . dirname($relativePath);
            }
        } else {
            if (in_array($fileMimeType, $documentMimeTypes)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/documents/' . dirname($relativePath);
            } elseif (in_array($fileMimeType, $mediaTypesPhotos)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/media/Photos/' . dirname($relativePath);
            } elseif (in_array($fileMimeType, $mediaTypesVideos)) {
                $storagePath = 'public/user/'.auth()->user()->id.'/media/Videos/' . dirname($relativePath);
            } else {
                $storagePath = 'public/user/'.auth()->user()->id.'/others/' . dirname($relativePath);
            }
        }
        
        // Create the directory if it doesn't exist
        Storage::makeDirectory($storagePath);
        
        // Store the file in the determined storage path
        $storedPath = $request->file('file')->storeAs($storagePath, $fileName);
        
        return response()->json([
            'success' => true,
            'filename' => $fileName,
            'path' => $storedPath,
            'url' => Storage::url($storedPath),
        ]);
    }

    public function checkIfExists(Request $request) {
        $relativePath = $request->input('relativePath');

        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        // If user uploaded in the dashboard
        if ($currentFolder == '/dashboard') {
            $fullPath = 'public/user/'.auth()->user()->id.'/documents/' . $relativePath;
        } else {
            $fullPath = 'public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $relativePath;
        }

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
            $path = storage_path('app/public/user/'.auth()->user()->id.'/documents/' . $folderName);
        } else {
            $path = storage_path('app/public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $folderName);
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
        $documentPath = 'public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $itemName;
        $archivePath = 'public/user/'.auth()->user()->id.'/archive/' . $itemName;

        // For file handling
        if (!$isFolder) {
            if (Storage::exists($documentPath)) {
                try {
                    // Ensure the archive folder exists
                    if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/archive'))) {
                        File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/archive'), 0755, true);
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
            $folderPath = storage_path('app/public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $itemName);
            $folderArchivePath = storage_path('app/public/user/'.auth()->user()->id.'/archive/' . $itemName);
            
            if (File::isDirectory($folderPath)) {
                try {
                    // Ensure the trash archive exists
                    if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/archive'))) {
                        File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/archive'), 0755, true);
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
        $documentPath = 'public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $itemName;
        $trashPath = 'public/user/'.auth()->user()->id.'/trash/' . $itemName;

        // For file handling
        if (!$isFolder) {
            if (Storage::exists($documentPath)) {
                try {
                    // Ensure the trash folder exists
                    if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/trash'))) {
                        File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/trash'), 0755, true);
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
            $folderPath = storage_path('app/public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $itemName);
            $folderTrashPath = storage_path('app/public/user/'.auth()->user()->id.'/trash/' . $itemName);
            
            if (File::isDirectory($folderPath)) {
                try {
                    // Ensure the trash folder exists
                    if (!File::exists(storage_path('app/public/user/'.auth()->user()->id.'/trash'))) {
                        File::makeDirectory(storage_path('app/public/user/'.auth()->user()->id.'/trash'), 0755, true);
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
        $itemName = $request->input('item');        // File or Folder name
        $isFolder = $request->input('isFolder');    // Boolean: is it a folder?

        // Define supported MIME types
        $documentMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ];
        $mediaTypesPhotos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mediaTypesVideos = ['video/mp4', 'video/avi', 'video/mkv', 'video/webm', 'video/x-matroska'];

        $trashPath = 'public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $itemName;

        // === FILE RESTORE ===
        if (!$isFolder) {
            if (Storage::exists($trashPath)) {
                $absoluteTrashPath = storage_path('app/' . $trashPath);
                $mimeType = mime_content_type($absoluteTrashPath);

                // Determine restore path based on MIME type
                if (in_array($mimeType, $documentMimeTypes)) {
                    $restorePath = 'public/user/'.auth()->user()->id.'/documents/' . $itemName;
                } elseif (in_array($mimeType, $mediaTypesPhotos)) {
                    $restorePath = 'public/user/'.auth()->user()->id.'/media/Photos/' . $itemName;
                } elseif (in_array($mimeType, $mediaTypesVideos)) {
                    $restorePath = 'public/user/'.auth()->user()->id.'/media/Videos/' . $itemName;
                } else {
                    $restorePath = 'public/user/'.auth()->user()->id.'/others/' . $itemName;
                }

                // Ensure destination directory exists
                Storage::makeDirectory(dirname($restorePath));

                try {
                    if (Storage::move($trashPath, $restorePath)) {
                        return response()->json(['success' => true]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to move file.'], 500);
                    }
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
                }
            }
        }

        // === FOLDER RESTORE ===
        else {
            $folderPath = storage_path('app/public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $itemName);
            $files = File::allFiles($folderPath);

            if (count($files) > 0) {
                $firstFile = $files[0];
                $mimeType = File::mimeType($firstFile);

                // Determine restore path based on MIME type of first file
                if (in_array($mimeType, $documentMimeTypes)) {
                    $restorePath = 'public/user/'.auth()->user()->id.'/documents/' . $itemName;
                } elseif (in_array($mimeType, $mediaTypesPhotos)) {
                    $restorePath = 'public/user/'.auth()->user()->id.'/media/Photos/' . $itemName;
                } elseif (in_array($mimeType, $mediaTypesVideos)) {
                    $restorePath = 'public/user/'.auth()->user()->id.'/media/Videos/' . $itemName;
                } else {
                    $restorePath = 'public/user/'.auth()->user()->id.'/others/' . $itemName;
                }
            } else {
                // Folder is empty, send to "others"
                $restorePath = 'public/user/'.auth()->user()->id.'/others/' . $itemName;
            }

            try {
                if (File::isDirectory($folderPath)) {
                    $restoreAbsolutePath = storage_path('app/' . $restorePath);

                    // Ensure destination folder exists
                    File::makeDirectory(dirname($restoreAbsolutePath), 0755, true, true);

                    if (File::copyDirectory($folderPath, $restoreAbsolutePath)) {
                        File::deleteDirectory($folderPath); // Clean up original
                        return response()->json(['success' => true]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to restore folder.'], 500);
                    }
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
    }

    public function deleteFile(Request $request) {
        $referrer = $request->headers->get('referer');
        $currentFolder = urldecode(parse_url($referrer, PHP_URL_PATH));

        $itemName = $request->input('item');     // File or Folder name
        $isFolder = $request->input('isFolder'); // true/false
        $isEmpty = $request->input('isEmpty'); // true/false

        if ($isEmpty) {
            $trashPath = storage_path('app/public/user/'.auth()->user()->id.'/trash');
    
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
        $relativePath = 'public/user/'.auth()->user()->id. '/' . $currentFolder . '/' . $itemName;

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
