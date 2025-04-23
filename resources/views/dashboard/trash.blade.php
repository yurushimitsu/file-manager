@extends('layout.master')

@section('title', 'Trash')

@section('body')

<body>
    @include('layout.navbar')

    @include('layout.sidebar')

    <div class="ml-15 lg:ml-50 mt-20 flex justify-center">
        <div class="container">
            <div class="p-1">
                <div class="m-5 p-8 bg-[#E8F8FF] rounded-xl min-h-150">
                    <a href="{{ route('trash') }}" class="font-bold text-3xl hover:underline mb-2">
                        Trash
                    </a>
                    @if (!empty($folderExploded))
                            @foreach ($folderExploded as $index => $folderSegment)
                                <!-- Create a link to each folder in the path -->
                                /
                                <a href="{{ route('documents', ['folder' => implode('/', array_slice($folderExploded, 0, $index + 1))]) }}"
                                    class="cursor-pointer hover:underline 
                                           {{ (isset($currentFolder) && $currentFolder == $folderSegment) ? 'underline font-semibold' : '' }}">
                                     {{ ucfirst($folderSegment) }}
                                </a>
                            @endforeach
                        @endif
                    <div class="text-sm">
                        Total: 
                        <span class="font-medium"> 
                            @php
                                $size = $trashFolderFileSize; // Total size in bytes
                        
                                // Convert bytes to human-readable format
                                if ($size >= 1073741824) {
                                    $size = number_format($size / 1073741824, 2) . ' GB';
                                } elseif ($size >= 1048576) {
                                    $size = number_format($size / 1048576, 2) . ' MB';
                                } elseif ($size >= 1024) {
                                    $size = number_format($size / 1024, 2) . ' KB';
                                } else {
                                    $size = $size . ' bytes';
                                }
                            @endphp
                            {{ $size }}
                        </span>
                    </div>
                    <div class="flex flex-row mb-2 justify-end gap-1">
                        <a class="flex flex-row items-center rounded-lg p-2 hover:bg-[#C8EAFF]">
                            Name
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75 12 3m0 0 3.75 3.75M12 3v18" />
                            </svg>
                        </a>
                        <a class="rounded-lg p-2 hover:bg-[#C8EAFF]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </a>
                        <a class="rounded-lg p-2 bg-[#C8EAFF] hover:bg-[#C8EAFF]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div class="flex flex-wrap justify-center md:justify-start gap-5">
                        @if (!empty($directories) || !empty($files))
                            @foreach ($directories as $directory)
                                <div class="folder-card grid content-between bg-white rounded-xl p-4 shadow-lg h-45 w-45 select-none cursor-pointer hover:bg-gray-50" ondblclick="window.location.href='{{ route('trash', ['folder' => str_replace('public/trash/', '', $directory)]) }}'">
                                    <div class="flex justify-between">
                                        <div class="bg-[#C8EAFF] rounded-full p-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-[#0F52FF]">
                                                <path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 0 0-3-3h-3.879a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H6a3 3 0 0 0-3 3v3.162A3.756 3.756 0 0 1 4.094 9h15.812ZM4.094 10.5a2.25 2.25 0 0 0-2.227 2.568l.857 6A2.25 2.25 0 0 0 4.951 21H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-2.227-2.568H4.094Z" />
                                            </svg> 
                                        </div>
                                        <div>
                                            <div class="flex justify-end">
                                                <button id="docuButton-{{ $directory }}" type="button" data-dropdown-toggle="docuDropdown-{{ $directory }}" class="cursor-pointer hover:bg-gray-200 rounded-lg" onclick="event.stopPropagation()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-gray-400">
                                                        <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <!-- Dropdown menu -->
                                                <div id="docuDropdown-{{ $directory }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm">
                                                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="docuButton-{{ $directory }}">
                                                        <li><a href="{{ route('documents', ['folder' => str_replace('public/documents/', '', $directory)]) }}" class="block px-4 py-2 hover:bg-gray-100">Open</a></li>
                                                        <li><button type="button" class="block px-4 py-2 hover:bg-gray-100 text-start w-full">Edit</button></li>
                                                        <li><button type="button" class="block px-4 py-2 hover:bg-gray-100 text-start w-full">Archive</button></li>
                                                        <li><button type="button" class="block px-4 py-2 hover:bg-gray-100 text-start w-full" onclick="moveToTrash('{{ basename($directory) }}')">Restore</button></li>
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                @php
                                                    $size = $folderSizes[$directory]; // Get the size of the folder
                                    
                                                    if ($size >= 1073741824) {
                                                        $size = number_format($size / 1073741824, 2) . ' GB';
                                                    } elseif ($size >= 1048576) {
                                                        $size = number_format($size / 1048576, 2) . ' MB';
                                                    } elseif ($size >= 1024) {
                                                        $size = number_format($size / 1024, 2) . ' KB';
                                                    } else {
                                                        $size = $size . ' bytes';
                                                    }
                                                @endphp
                                                {{ $size }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold">
                                            {{ basename($directory) }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($files as $index => $file)
                                <div class="file-card grid content-between bg-white rounded-xl p-4 shadow-lg h-45 w-45 select-none cursor-pointer hover:bg-gray-50" ondblclick="window.location.href='{{ Storage::url($file) }}'">
                                    <div class="flex justify-between">
                                        <div class="bg-[#C8EAFF] rounded-full p-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-orange-500">
                                                <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                                                <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex justify-end">
                                                <button id="docuButton-{{ $index }}" type="button" data-dropdown-toggle="docuDropdown-{{ $index }}" class="cursor-pointer hover:bg-gray-200 rounded-lg" onclick="event.stopPropagation()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-gray-400">
                                                        <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <!-- Dropdown menu -->
                                                <div id="docuDropdown-{{ $index }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm">
                                                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="docuButton-{{ $index }}">
                                                        <li>
                                                            <a href="{{ Storage::url($file) }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100">Download</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Archive</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Restore</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                @php
                                                    $size = Storage::size($file); // Size in bytes
                                                    if ($size >= 1073741824) {
                                                        $size = number_format($size / 1073741824, 2) . ' GB';
                                                    } elseif ($size >= 1048576) {
                                                        $size = number_format($size / 1048576, 2) . ' MB';
                                                    } elseif ($size >= 1024) {
                                                        $size = number_format($size / 1024, 2) . ' KB';
                                                    } else {
                                                        $size = $size . ' bytes';
                                                    }
                                                @endphp
                                                {{ $size }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold">
                                            {{ basename($file) }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            @php
                                                // Get the last modified timestamp of the file
                                                $timestamp = Storage::lastModified($file);
                            
                                                // Format the timestamp using Carbon
                                                $lastModifiedDate = \Carbon\Carbon::createFromTimestamp($timestamp)->format('h:i A, d M Y');
                                            @endphp
                                            {{ $lastModifiedDate }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-xl font-medium text-center w-full mt-3">
                                No file
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     // Get all file cards
    //     const fileCards = document.querySelectorAll('.file-card');
        
    //     // Loop through each file card and add a click event listener
    //     fileCards.forEach(function(card) {
    //         card.addEventListener('click', function() {
    //             // Get the URL stored in the data-url attribute
    //             const url = card.getAttribute('data-url');
                
    //             // Create a new anchor element
    //             const link = document.createElement('a');
    //             link.href = url;         // Set the URL
    //             link.target = '_blank';  // Open in a new tab
            
    //             link.click();
    //         });
    //     });
    // });

    function moveToTrash(directory) {
        console.log(directory);
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, move it to trash!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request to move the folder to trash
                fetch("{{ route('moveToTrash') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'  // CSRF token for Laravel protection
                    },
                    body: JSON.stringify({ directory: directory })  // Pass the directory name
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Moved to Trash!",
                            text: "Your folder has been moved to the trash.",
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: "There was an issue moving the folder to trash: " + data.message,
                            icon: "error"
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: "Error!",
                        text: "There was an error with the request.",
                        icon: "error"
                    });
                });
            }
        });
    }
</script>

@endsection