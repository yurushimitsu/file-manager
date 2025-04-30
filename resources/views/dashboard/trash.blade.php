@extends('layout.master')

@section('title', 'Trash')

@section('body')

<body>
    @include('layout.navbar')

    @include('layout.sidebar')

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
                    <div class="flex justify-between">
                        <div>
                            <a href="{{ route('trash') }}" class="font-bold text-3xl hover:underline mb-2">
                                Trash
                            </a>
                            @if (!empty($folderExploded))
                                @foreach ($folderExploded as $index => $folderSegment)
                                    <!-- Create a link to each folder in the path -->
                                    /
                                    <a href="{{ route('trash', ['folder' => implode('/', array_slice($folderExploded, 0, $index + 1))]) }}"
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
                        </div>
                        <div>
                            <button id="emptyTrash" onclick="deleteFile(null, null, true)" type="button" class="text-white font-bold bg-blue-400 w-25 hover:bg-blue-500 cursor-pointer inline-flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full text-sm py-2 me-5 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 me-1">
                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                </svg>
                                Empty
                            </button>
                        </div>
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
                                <div class="folder-card grid-view grid grid-cols-1 content-between bg-white rounded-xl p-4 shadow-lg h-45 w-45 select-none cursor-pointer hover:bg-gray-50" data-id="{{ $directory }}" id="folder-{{ basename($directory) }}" ondblclick="window.location.href='{{ route('trash', ['folder' => str_replace('public/trash/', '', $directory)]) }}'" title="{{ basename($directory) }}">
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
                                                        <li><a href="{{ route('trash', ['folder' => str_replace('public/trash/', '', $directory)]) }}" class="block px-4 py-2 hover:bg-gray-100">Open</a></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="restoreFiles('{{ basename($directory) }}', true)">Restore</button></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="deleteFile('{{ basename($directory) }}', true, false)">Delete</button></li>                     
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
                                <div class="folder-card list-view bg-white rounded-xl shadow-lg h-15 w-full select-none cursor-pointer hover:bg-gray-50 hidden" data-id="{{ $directory }}" id="folderList-{{ basename($directory) }}" ondblclick="window.location.href='{{ route('trash', ['folder' => str_replace('public/trash/', '', $directory)]) }}'" title="{{ basename($directory) }}">
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
                                                        <li><a href="{{ route('trash', ['folder' => str_replace('public/trash/', '', $directory)]) }}" class="block px-4 py-2 hover:bg-gray-100">Open</a></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="restoreFiles('{{ basename($directory) }}', true)">Restore</button></li>
                                                        <li><button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="deleteFile('{{ basename($directory) }}', true, false)">Delete</button></li>                     
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
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="restoreFiles('{{ basename($file) }}', false)">Restore</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="deleteFile('{{ basename($file) }}', false, false)">Delete</button>
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
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="restoreFiles('{{ basename($file) }}', false)">Restore</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="block px-4 py-2 cursor-pointer hover:bg-gray-100 text-start w-full" onclick="deleteFile('{{ basename($file) }}', false, false)">Delete</button>
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
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}',
        routes: {
            deleteFile: '{{ route("deleteFile") }}',
            restoreFiles: '{{ route("restoreFiles") }}'
        }
    };
</script>
<script src="/js/sort.js"></script>
<script src="/js/move.js"></script>

@endsection