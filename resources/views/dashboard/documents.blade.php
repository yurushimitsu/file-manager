@extends('layout.master')

@section('title', 'Documents')

@section('body')

<body>
    @include('layout.navbar')

    @include('layout.sidebar')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

    <div class="ml-15 lg:ml-50 mt-20 flex justify-center">
        <div class="container">
            <div class="p-1">
                <div id="drop-zone" class="relative m-5 p-8 bg-[#E8F8FF] rounded-xl min-h-150">
                    <!-- Add this inside your drop zone -->
                    <div id="drag-overlay" class="absolute inset-0 rounded-xl ring-2 ring-blue-400 bg-blue-50 bg-opacity-75 pt-10 flex justify-center text-blue-700 font-semibold text-lg hidden z-10">
                        Drop a file to upload
                        <div id="closeUpload" class="absolute cursor-pointer hover:bg-blue-200 rounded-md p-1 right-10 top-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>                              
                        </div>
                    </div>
                    <a href="{{ route('documents') }}" class="font-bold text-3xl hover:underline mb-2">
                        Documents
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
                                $size = $docuFolderFileSize; // Total size in bytes
                        
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
                        <a onclick="sortByName()" class="flex flex-row items-center rounded-lg select-none p-2 cursor-pointer hover:bg-[#C8EAFF]">
                            Name
                            <svg id="sort-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ml-1 transition-transform duration-200">
                                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v16.19l2.47-2.47a.75.75 0 1 1 1.06 1.06l-3.75 3.75a.75.75 0 0 1-1.06 0l-3.75-3.75a.75.75 0 1 1 1.06-1.06l2.47 2.47V3a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a onclick="showListView()" class="rounded-lg p-2 cursor-pointer hover:bg-[#C8EAFF]" id="listViewButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </a>
                        <a onclick="showGridView()" class="rounded-lg p-2 cursor-pointer bg-[#C8EAFF] hover:bg-[#C8EAFF]" id="gridViewButton">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div id="sortable-files" class="flex flex-wrap justify-center md:justify-start gap-5">
                        @if (!empty($directories) || !empty($files))
                            @foreach ($directories as $directory)
                                <div class="folder-card grid-view grid grid-cols-1 content-between bg-white rounded-xl p-4 shadow-lg h-45 w-45 select-none cursor-pointer hover:bg-gray-50" data-id="{{ $directory }}" id="folder-{{ basename($directory) }}" ondblclick="window.location.href='{{ route('documents', ['folder' => str_replace('public/documents/', '', $directory)]) }}'" title="{{ basename($directory) }}">
                                    <div class="flex items-start justify-between">
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
                                                        <li>
                                                            {{-- <a href="{{ route('folders.download', ['folder' => str_replace('public/documents/', '', $directory)]) }}"
                                                               class="block px-4 py-2 hover:bg-gray-100"
                                                               onclick="event.stopPropagation();">
                                                               Download
                                                            </a> --}}
                                                            <button 
                                                                class="block px-4 py-2 hover:bg-gray-100 bg-transparent border-0 cursor-pointer"
                                                                onclick="downloadFolder('{{ route('folders.download', ['folder' => str_replace('public/documents/', '', $directory)]) }}');">
                                                                Download
                                                            </button>
                                                        </li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="editFolderName('{{ basename($directory) }}')">Edit</button></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full">Archive</button></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="moveToTrash('{{ basename($directory) }}', true)">Trash</button></li>                     
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
                                    <div class="flex flex-col justify-end">
                                        <div class="text-lg font-bold overflow-hidden overflow-ellipsis whitespace-normal line-clamp-2" id="folder-name-{{ basename($directory) }}">
                                            {{ basename($directory) }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- List View --}}
                                <div class="folder-card list-view bg-white rounded-xl shadow-lg h-15 w-full select-none cursor-pointer hover:bg-gray-50 hidden" data-id="{{ $directory }}" id="folderList-{{ basename($directory) }}" ondblclick="window.location.href='{{ route('documents', ['folder' => str_replace('public/documents/', '', $directory)]) }}'" title="{{ basename($directory) }}">
                                    <div class="flex items-center justify-between p-3 h-full w-full">
                                        <div class="flex flex-row items-center min-w-0">
                                            <div class="bg-[#C8EAFF] rounded-full p-3 me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-[#0F52FF]">
                                                    <path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 0 0-3-3h-3.879a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H6a3 3 0 0 0-3 3v3.162A3.756 3.756 0 0 1 4.094 9h15.812ZM4.094 10.5a2.25 2.25 0 0 0-2.227 2.568l.857 6A2.25 2.25 0 0 0 4.951 21H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-2.227-2.568H4.094Z" />
                                                </svg> 
                                            </div>
                                            <div class="flex flex-col justify-end min-w-0">
                                                <div class="text-lg font-bold truncate max-w-full" id="folder-name-list-{{ basename($directory) }}">
                                                    {{ basename($directory) }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center justify-end">
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
                                                <button id="docuButtonList-{{ $directory }}" type="button" data-dropdown-toggle="docuDropdownList-{{ $directory }}" class="cursor-pointer hover:bg-gray-200 rounded-lg" onclick="event.stopPropagation()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-gray-400">
                                                        <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <!-- Dropdown menu -->
                                                <div id="docuDropdownList-{{ $directory }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm">
                                                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="docuButtonList-{{ $directory }}">
                                                        <li><a href="{{ route('documents', ['folder' => str_replace('public/documents/', '', $directory)]) }}" class="block px-4 py-2 hover:bg-gray-100">Open</a></li>
                                                        <li>
                                                            <button 
                                                                class="block px-4 py-2 hover:bg-gray-100 bg-transparent border-0 cursor-pointer"
                                                                onclick="downloadFolder('{{ route('folders.download', ['folder' => str_replace('public/documents/', '', $directory)]) }}');">
                                                                Download
                                                            </button>
                                                        </li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="editFolderNameList('{{ basename($directory) }}')">Edit</button></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full">Archive</button></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="moveToTrash('{{ basename($directory) }}', true)">Trash</button></li>                     
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($files as $index => $file)
                                <div class="file-card grid-view grid grid-cols-1 bg-white rounded-xl p-4 shadow-lg h-45 w-45 select-none cursor-pointer hover:bg-gray-50" data-id="{{ $file }}" id="file-{{ basename($file) }}" ondblclick="window.open('{{ Storage::url($file) }}', '_blank')" title="{{ basename($file) }}">
                                    <div class="flex items-start justify-between">
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
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="editFileName('{{ basename($file) }}', {{ $index }})">Edit</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full">Archive</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="moveToTrash('{{ basename($file) }}', false)">Trash</button>
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
                                    <div class="flex flex-col justify-end">
                                        <div class="text-lg font-bold overflow-hidden overflow-ellipsis whitespace-normal line-clamp-2" id="file-name-{{ basename($file) }}">
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

                                {{-- List View --}}
                                <div class="file-card list-view bg-white rounded-xl shadow-lg h-15 w-full select-none cursor-pointer hover:bg-gray-50 hidden" data-id="{{ $file }}" id="fileList-{{ basename($file) }}" ondblclick="window.open('{{ Storage::url($file) }}', '_blank')" title="{{ basename($file) }}">
                                    <div class="flex items-center justify-between p-3 h-full w-full">
                                        <div class="flex flex-row items-center min-w-0">
                                            <div class="bg-[#C8EAFF] rounded-full p-3 me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-orange-500">
                                                    <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                                                    <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                                </svg>
                                            </div>
                                            <div class="flex flex-col justify-end min-w-0">
                                                <div class="text-lg font-bold truncate max-w-full" id="file-name-list-{{ basename($file) }}">
                                                    {{ basename($file) }}
                                                </div>
                                                <div class="text-xs text-gray-400 truncate max-w-full">
                                                    @php
                                                        $timestamp = Storage::lastModified($file);
                                                        $lastModifiedDate = \Carbon\Carbon::createFromTimestamp($timestamp)->format('h:i A, d M Y');
                                                    @endphp
                                                    {{ $lastModifiedDate }}
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-end">
                                                <button id="docuButtonList-{{ $index }}" type="button" data-dropdown-toggle="docuDropdownList-{{ $index }}" class="cursor-pointer hover:bg-gray-200 rounded-lg" onclick="event.stopPropagation()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-gray-400">
                                                        <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <div id="docuDropdownList-{{ $index }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm">
                                                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="docuButtonList-{{ $index }}">
                                                        <li>
                                                            <a href="{{ Storage::url($file) }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100">Download</a>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="editFileNameList('{{ basename($file) }}', {{ $index }})">Edit</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full">Archive</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="moveToTrash('{{ basename($file) }}', false)">Trash</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                @php
                                                    $size = Storage::size($file); 
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
    function downloadFolder(url) {
        // Make a request to the download route using fetch (or axios)
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (data.error) {
                            // Show SweetAlert if folder is empty
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: data.error,
                            });
                        }
                    });
                }

                // If the folder is not empty, the server will handle the file download.
                window.location.href = url; // Trigger the download directly using the URL
            })
            .catch(error => {
                console.error('Error downloading folder:', error);
            });
    }

    function editFolderName(directory) {
        // Find the file name display element
        const folderNameElement = document.getElementById('folder-name-' + directory);
        folderNameElement.classList.add('p-0.5');

        // Store the original file name to revert back if needed
        const originalFolderName = directory;

        // Create an input field to edit the base file name (without extension)
        const inputField = document.createElement('input');
        inputField.type = 'text';
        inputField.value = directory; // Set the base name as the default value
        inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-2', 'w-full');

        // Replace the text with the input field
        folderNameElement.innerHTML = '';
        folderNameElement.appendChild(inputField);

        // Focus on the input field
        inputField.focus();
        
        // Create a save button for the changes
        const saveButton = document.createElement('button');
        saveButton.textContent = 'Save';
        saveButton.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

        saveButton.onclick = function() {
            const newFolderName = inputField.value;
            if (newFolderName && newFolderName !== originalFolderName) {
                updateFileName(originalFolderName, newFolderName);
            } else {
                folderNameElement.innerHTML = originalFolderName;
            }
        };

        // Create a cancel button to revert changes
        const cancelButton = document.createElement('button');
        cancelButton.textContent = 'Cancel';
        cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

        cancelButton.onclick = function() {
            // Revert back to the original file name
            folderNameElement.innerHTML = originalFolderName;
        };

        // Append the save and cancel buttons
        folderNameElement.appendChild(saveButton);
        folderNameElement.appendChild(cancelButton);

        inputField.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                // Trigger the save button's onclick event when Enter is pressed
                saveButton.click();
            }
        });
    }

    function editFolderNameList(directory) {
        const folderNameElement = document.getElementById('folder-name-list-' + directory);
        folderNameElement.classList.add('p-0.5');
        const originalFolderName = folderNameElement.innerText.trim(); // Get the current file name

        // Create an input field for editing the file name (without extension)
        const inputField = document.createElement('input');
        inputField.type = 'text';
        inputField.value = directory; // Set the base name as the default value
        inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-1', 'w-full');

        // Clear the current file name and append the input field
        folderNameElement.innerHTML = '';  // Clear content
        folderNameElement.appendChild(inputField);  // Add input field
        inputField.focus(); // Focus on the input field

        // Get the parent file card element (for positioning the buttons inside the file-card)
        const folderCard = folderNameElement.closest('.folder-card.list-view');
        folderCard.style.position = 'relative'; // Make sure file card is positioned relative

        // Create the Save button
        const saveButtonList = document.createElement('button');
        saveButtonList.textContent = 'Save';
        saveButtonList.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
        saveButtonList.style.position = 'absolute';
        saveButtonList.style.top = '10px'; // Adjust as needed
        saveButtonList.style.right = '160px'; // Adjust as needed

        // Save button functionality
        saveButtonList.onclick = function() {
            const newFolderName = inputField.value;
            if (newFolderName && newFolderName !== directory) {
                updateFileName(originalFolderName, newFolderName);
            } else {
                folderNameElement.innerHTML = originalFolderName;
                saveButtonList.remove();
                cancelButton.remove();
            }
        };

        // Create the Cancel button
        const cancelButton = document.createElement('button');
        cancelButton.textContent = 'Cancel';
        cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
        cancelButton.style.position = 'absolute';
        cancelButton.style.top = '10px'; // Adjust as needed
        cancelButton.style.right = '85px'; // Adjust as needed

        // Cancel button functionality
        cancelButton.onclick = function() {
            // Revert to original file name if cancel is clicked
            folderNameElement.innerHTML = originalFolderName;
            // Hide the Save and Cancel buttons after canceling
            saveButtonList.remove();
            cancelButton.remove();
        };

        // Append the Save and Cancel buttons to the file card element
        folderCard.appendChild(saveButtonList);
        folderCard.appendChild(cancelButton);

        inputField.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                // Trigger the save button's onclick event when Enter is pressed
                saveButtonList.click();
            }
        });
    }

    function editFileName(fileName, index) {
        // Split the file name and extension
        const fileParts = fileName.split('.');
        const fileExtension = fileParts.length > 1 ? '.' + fileParts.pop() : ''; // Get the extension (if any)
        const baseFileName = fileParts.join('.'); // Get the base name without the extension

        // Find the file name display element
        const fileNameElement = document.getElementById('file-name-' + fileName);
        fileNameElement.classList.add('p-0.5');

        // Store the original file name to revert back if needed
        const originalFileName = fileName;

        // Create an input field to edit the base file name (without extension)
        const inputField = document.createElement('input');
        inputField.type = 'text';
        inputField.value = baseFileName; // Set the base name as the default value
        inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-2', 'w-full');

        // Replace the text with the input field
        fileNameElement.innerHTML = '';
        fileNameElement.appendChild(inputField);

        // Focus on the input field
        inputField.focus();
        
        // Create a save button for the changes
        const saveButton = document.createElement('button');
        saveButton.textContent = 'Save';
        saveButton.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

        saveButton.onclick = function() {
            const newBaseFileName = inputField.value.trim();
            if (newBaseFileName && newBaseFileName !== baseFileName) {
                const newFileName = newBaseFileName + fileExtension;

                // Call backend to update the file name
                updateFileName(originalFileName, newFileName);
            } else {
                fileNameElement.innerHTML = originalFileName;
            }
        };

        // Create a cancel button to revert changes
        const cancelButton = document.createElement('button');
        cancelButton.textContent = 'Cancel';
        cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

        cancelButton.onclick = function() {
            // Revert back to the original file name
            fileNameElement.innerHTML = originalFileName;
        };

        // Append the save and cancel buttons
        fileNameElement.appendChild(saveButton);
        fileNameElement.appendChild(cancelButton);

        inputField.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                // Trigger the save button's onclick event when Enter is pressed
                saveButton.click();
            }
        });
    }

    function editFileNameList(fileName, index) {
        // Split the file name and extension
        const fileParts = fileName.split('.');
        const fileExtension = fileParts.length > 1 ? '.' + fileParts.pop() : ''; // Get the extension (if any)
        const baseFileName = fileParts.join('.'); // Get the base name without the extension

        const fileNameElement = document.getElementById('file-name-list-' + fileName);
        fileNameElement.classList.add('p-0.5');
        const originalFileName = fileNameElement.innerText.trim(); // Get the current file name

        // Create an input field for editing the file name (without extension)
        const inputField = document.createElement('input');
        inputField.type = 'text';
        inputField.value = baseFileName; // Set the base name as the default value
        inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-1', 'w-full');

        // Clear the current file name and append the input field
        fileNameElement.innerHTML = '';  // Clear content
        fileNameElement.appendChild(inputField);  // Add input field
        inputField.focus(); // Focus on the input field

        // Get the parent file card element (for positioning the buttons inside the file-card)
        const fileCard = fileNameElement.closest('.file-card.list-view');
        fileCard.style.position = 'relative'; // Make sure file card is positioned relative

        // Create the Save button
        const saveButtonList = document.createElement('button');
        saveButtonList.textContent = 'Save';
        saveButtonList.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
        saveButtonList.style.position = 'absolute';
        saveButtonList.style.top = '10px'; // Adjust as needed
        saveButtonList.style.right = '160px'; // Adjust as needed

        // Save button functionality
        saveButtonList.onclick = function() {
            const newBaseFileName = inputField.value.trim();
            if (newBaseFileName && newBaseFileName !== baseFileName) {
                const newFileName = newBaseFileName + fileExtension;

                // Call backend to update the file name
                updateFileName(originalFileName, newFileName);
            } else {
                fileNameElement.innerHTML = originalFileName;
                saveButtonList.remove();
                cancelButton.remove();
            }
        };

        // Create the Cancel button
        const cancelButton = document.createElement('button');
        cancelButton.textContent = 'Cancel';
        cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
        cancelButton.style.position = 'absolute';
        cancelButton.style.top = '10px'; // Adjust as needed
        cancelButton.style.right = '85px'; // Adjust as needed

        // Cancel button functionality
        cancelButton.onclick = function() {
            // Revert to original file name if cancel is clicked
            fileNameElement.innerHTML = originalFileName;
            // Hide the Save and Cancel buttons after canceling
            saveButtonList.remove();
            cancelButton.remove();
        };

        // Append the Save and Cancel buttons to the file card element
        fileCard.appendChild(saveButtonList);
        fileCard.appendChild(cancelButton);

        inputField.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                // Trigger the save button's onclick event when Enter is pressed
                saveButtonList.click();
            }
        });
    }

    function updateFileName(oldFileName, newFileName) {
        const data = {
            old_file_name: oldFileName,
            new_file_name: newFileName,
        };
        
        // Make the AJAX request (using fetch)
        fetch('{{ route("update-file-name") }}', {
            method: 'POST', // or 'PUT' depending on your controller method
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(responseData => {
            if (responseData.success) {
                // alert('File name updated successfully');
                // Optionally, update the UI here with the new file name
                Swal.fire({
                    icon: 'success',
                    title: 'Edit Complete',
                    text: responseData.message,
                    timer: 3000,
                    showConfirmButton: false,
                    willClose: () => { location.reload(); }
                });
            } else {
                // alert('Failed to update the file name: ' + (responseData.message || 'Unknown error'));
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: responseData.message,
                    showConfirmButton: true
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the file name');
        });
    }


    let sortDirection = 'asc';

    function sortByName() {
        const container = document.getElementById('sortable-files');
        const cards = Array.from(container.children);

        // Toggle direction FIRST
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';

        const sorted = cards.sort((a, b) => {
            const nameA = a.getAttribute('title').toLowerCase();
            const nameB = b.getAttribute('title').toLowerCase();

            if (sortDirection === 'asc') {
                return nameA.localeCompare(nameB);
            } else {
                return nameB.localeCompare(nameA);
            }
        });

        // Update icon direction
        const icon = document.getElementById('sort-icon');
        icon.style.transform = sortDirection === 'asc' ? 'rotate(0deg)' : 'rotate(180deg)';

        // Re-append sorted nodes
        sorted.forEach(card => container.appendChild(card));
    }

    function showListView() {
        document.querySelectorAll('.grid-view').forEach(card => {
            card.classList.add('hidden');
        });

        document.querySelectorAll('.list-view').forEach(card => {
            card.classList.remove('hidden');
        });

        // Update active state on buttons
        document.getElementById('listViewButton').classList.add('bg-[#C8EAFF]');
        document.getElementById('gridViewButton').classList.remove('bg-[#C8EAFF]');
    }

    function showGridView() {
        document.querySelectorAll('.list-view').forEach(card => {
            card.classList.add('hidden');
        });

        document.querySelectorAll('.folder-card.grid-view, .file-card.grid-view').forEach(card => {
            card.classList.remove('hidden');
        });

        // Update active state on buttons
        document.getElementById('gridViewButton').classList.add('bg-[#C8EAFF]');
        document.getElementById('listViewButton').classList.remove('bg-[#C8EAFF]');
    }

    const dropZone = document.getElementById('drop-zone');
    const dragOverlay = document.getElementById('drag-overlay');
    const fileUploadButton = document.getElementById('fileUploadButton');
    const folderUploadButton = document.getElementById('folderUploadButton');
    const closeUpload = document.getElementById('closeUpload');

    let isUploading = false;  // Flag to prevent multiple SweetAlerts
    let filesToUpload = 0;     // Counter for the number of files to upload
    let filesUploaded = 0;     // Counter for the number of files successfully uploaded
    let existingFiles = [];
    let newFiles = [];

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dragOverlay.classList.remove('hidden');
    });

    dropZone.addEventListener('dragleave', (e) => {
        if (!dropZone.contains(e.relatedTarget)) {
            dragOverlay.classList.add('hidden');
        }
    });

    fileUploadButton.addEventListener('click', (e) => {
        e.preventDefault();
        dragOverlay.classList.remove('hidden');
    });

    folderUploadButton.addEventListener('click', (e) => {
        e.preventDefault();
        dragOverlay.classList.remove('hidden');
    });

    closeUpload.addEventListener('click', (e) => {
        e.preventDefault();
        dragOverlay.classList.add('hidden');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dragOverlay.classList.add('hidden');
        
        const items = e.dataTransfer.items;

        let folderNames = new Set(); // To track the folder names and avoid duplication

        // Count the number of files to upload
        filesToUpload = 0;
        for (let i = 0; i < items.length; i++) {
            const item = items[i].webkitGetAsEntry?.();
            if (item) {
                countFilesInItem(item);
            }
        }

        // Start the actual upload process after counting the files
        for (let i = 0; i < items.length; i++) {
            const item = items[i].webkitGetAsEntry?.();
            if (item) {
                traverseFileTree(item, folderNames);
            }
        }
    });

    function countFilesInItem(item, path = "") {
        if (item.isFile) {
            filesToUpload++;
        } else if (item.isDirectory) {
            const dirReader = item.createReader();
            dirReader.readEntries(entries => {
                for (const entry of entries) {
                    countFilesInItem(entry, path + item.name + "/");
                }
            });
        }
    }

    function traverseFileTree(item, folderNames, path = "") {
        if (item.isFile) {
            item.file(file => {
                file.relativePath = path + file.name;
                uploadFile(file, file.relativePath, folderNames);
            });
        } else if (item.isDirectory) {
            if (folderNames.has(item.name)) {
                console.log(`Folder "${item.name}" already exists, skipping upload.`);
                return; // Skip folder if it already exists
            }

            folderNames.add(item.name);  // Track the folder name

            const dirReader = item.createReader();
            dirReader.readEntries(entries => {
                for (const entry of entries) {
                    traverseFileTree(entry, folderNames, path + item.name + "/");
                }
            });
        }
    }

    function uploadFile(file, relativePath, folderNames) {
        Swal.fire({
            title: 'Checking...',
            text: 'Please wait while your files are being checked.',
            imageUrl: 'https://cdn.jsdelivr.net/npm/sweetalert2@11/src/swal2-spinner.gif',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("upload.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ relativePath: relativePath })
        })
        .then(response => response.json())
        .then(data => {
            file.relativePath = relativePath;

            if (data.exists) {
                existingFiles.push(file);
            } else {
                newFiles.push(file);
            }

            if (existingFiles.length + newFiles.length === filesToUpload) {
                handleUploadDecision();
            }
        });
    }

    function handleUploadDecision() {
        if (existingFiles.length > 0) {
            const fileList = existingFiles.map(f => `• ${f.relativePath}`).join('<br>');

            Swal.fire({
                title: `${existingFiles.length} file(s) already exist`,
                html: `<div class="text-start font-inherit">${fileList}</div><br><span class="font-medium">Do you want to overwrite them?<span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#0D4AE5",
                cancelButtonColor: "#d33",
                confirmButtonText: 'Yes, overwrite',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    [...existingFiles, ...newFiles].forEach(file => {
                        proceedUpload(file, file.relativePath);
                    });
                } else {
                    // Reset values
                    existingFiles = [];
                    newFiles = [];
                }
            });
        } else {
            newFiles.forEach(file => {
                proceedUpload(file, file.relativePath);
            });
        }
    }


    function proceedUpload(file, relativePath) {
        if (!isUploading) {
            // Show the "Uploading..." SweetAlert with spinner
            isUploading = true; // Set the flag to prevent showing multiple SweetAlerts
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while your files are being uploaded.',
                imageUrl: 'https://cdn.jsdelivr.net/npm/sweetalert2@11/src/swal2-spinner.gif',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('relativePath', relativePath);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("upload") }}', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                filesUploaded++;
                if (filesUploaded === filesToUpload) {
                    // All files uploaded, show the "Upload Complete" SweetAlert
                    // setTimeout(() => {
                        Swal.close(); // Close loading spinner
                        Swal.fire({
                            icon: 'success',
                            title: 'Upload Complete',
                            text: `${filesToUpload} file(s) uploaded successfully!`,
                            timer: 3000,
                            showConfirmButton: false,
                            willClose: () => { location.reload(); }
                        });

                        // setTimeout(() => location.reload(), 3000); // Reload after success message
                    // }, 3000); // Show spinner for 3 seconds before success alert
                }
            } else {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: data.error || 'Something went wrong.',
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: error.message || 'Something went wrong.',
            });
            console.error(error);
        });
    }

    function moveToTrash(itemName, isFolder) {
        Swal.fire({
            title: "Are you sure?",
            text: "Move this file to trash?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0D4AE5",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, move to trash"
        }).then((result) => {
            if (result.isConfirmed) {
                // Send the appropriate data to the backend
                fetch("{{ route('moveToTrash') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'  // CSRF token for security
                    },
                    body: JSON.stringify({
                        item: itemName,
                        isFolder: isFolder  // Send whether it's a folder or not
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Moved to Trash!",
                            text: "Your item has been moved to trash",
                            // text: data.message,
                            icon: "success"
                        }).then(() => {
                            // location.reload();  // Reload the page to reflect changes
                            if (isFolder) {
                                document.getElementById('folder-' + itemName).remove();
                                document.getElementById('folderList-' + itemName).remove();
                            } else {
                                document.getElementById('file-' + itemName).remove();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: "There was an issue moving the item to trash: " + data.message,
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

    // Initialize SortableJS for dragging and dropping
    const sortable = new Sortable(document.getElementById('sortable-files'), {
        group: 'files-and-folders',  // Define the group for drag-and-drop
        animation: 150,  // Animation duration when dragging
        // onEnd(evt) {
        //     const movedItem = evt.item;  // Get the moved item
        //     const originalIndex = evt.from.children; // Get the original index
        //     const newIndex = evt.to.children; // Get the new index
            
        //     // Optionally, you can trigger a POST request to save the change to the backend
        //     const id = movedItem.getAttribute('data-id');
        //     const newParentId = evt.to.getAttribute('id');
            
        //     // Perform backend action (e.g., move the file/folder) using AJAX/fetch
        //     moveItemToNewLocation(id, newParentId);
        // }
    });

    
</script>

@endsection