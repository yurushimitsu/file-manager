@extends('layout.master')

@section('title', 'Dashboard')

@section('body')

<body>
    <style>
        /* HTML: <div class="inverted-radius"></div> */
        .inverted-radius {
        --r: 20px; /* the radius */
        --s: 25px; /* size of inner curve */
        --x: 10px; /* horizontal offset (no percentage) */
        --y: 10px; /* vertical offset (no percentage) */

        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.2))
                drop-shadow(0 25px 50px rgba(0, 0, 0, 0.15));

        
        width: 175px;
        aspect-ratio: 1;
        background: white;
        border-radius: var(--r);
        --_m:/calc(2*var(--r)) calc(2*var(--r)) radial-gradient(#000 70%,#0000 72%);
        --_g:conic-gradient(at var(--r) var(--r),#000 75%,#0000 0);
        --_d:(var(--s) + var(--r));
        mask:
            calc(var(--_d) + var(--x)) 0 var(--_m),
            0 calc(var(--_d) + var(--y)) var(--_m),
            radial-gradient(var(--s) at 0 0,#0000 99%,#000 calc(100% + 1px)) 
            calc(var(--r) + var(--x)) calc(var(--r) + var(--y)),
            var(--_g) calc(var(--_d) + var(--x)) 0,
            var(--_g) 0 calc(var(--_d) + var(--y));
        mask-repeat: no-repeat;
        }
    </style>

    @include('layout.navbar')

    @include('layout.sidebar')

    <div class="ml-15 lg:ml-50 mt-20 flex justify-center">
        <div class="container">
            <div class="p-1">
                <div id="drop-zone" class="relative m-5 p-5 bg-[#E8F8FF] rounded-xl">
                    <!-- Drop zone overlay -->
                    <div id="drag-overlay" class="absolute inset-0 rounded-xl ring-2 ring-blue-400 bg-blue-50 bg-opacity-75 pt-10 flex justify-center text-blue-700 font-semibold text-lg hidden z-10">
                        Drop a file to upload
                        <div id="closeUpload" class="absolute cursor-pointer hover:bg-blue-200 rounded-md p-1 right-10 top-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>                              
                        </div>
                    </div>
                    <div class="grid grid-flow-row lg:grid-rows-4 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div class="md:col-span-1 lg:col-span-2 lg:row-span-1 order-1 md:order-1 lg:order-1 place-items-center">
                            <div class="flex items-center justify-center bg-[#9CCBFF] rounded-xl shadow-lg w-full h-full">
                                <div class="flex flex-col lg:flex-row items-center justify-center text-gray-900 text-xs py-4">
                                    <div class="radial-progress md:me-5" style="--value:{{ $usedPercentage }}; --size:7rem; --thickness:7px;">
                                        <div class="flex flex-col text-center">
                                            <span class="font-bold text-lg">{{ $usedPercentage }}%</span>
                                            <span class="text-md">Space Used</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xl">Available Storage</span>
                                        <span class="text-md">{{ $usedStorage }} / {{ $totalStorage }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 lg:row-span-1 order-2 md:order-3 lg:order-2 place-items-center cursor-pointer select-none" ondblclick="window.location.href='{{ route('documents') }}'">
                            <div class="flex relative">
                                <div class="inverted-radius flex items-end justify-center pb-7">
                                    <div class="text-center mx-5 w-full">
                                        <div class="text-gray-900 text-sm font-medium border-b border-gray-300 w-full pb-2">
                                            Documents
                                        </div>
                                        <div class="text-gray-400 text-xs font-light pt-2">
                                            Last Update
                                        </div>
                                        <div class="text-gray-900 text-xs pt-2">
                                            {{ $docuFolderModified ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#FF9C9C] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                    </svg>    
                                </div>
                                <div class="absolute top-3 right-5 text-xs font-medium">
                                    @php
                                        $size = $docuFolderSize; // Total size in bytes
                                
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
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 lg:row-span-1 order-3 md:order-4 lg:order-3 place-items-center cursor-pointer select-none" ondblclick="window.location.href='{{ route('media') }}'">
                            <div class="flex relative">
                                <div class="inverted-radius flex items-end justify-center pb-7">
                                    <div class="text-center mx-5 w-full">
                                        <div class="text-gray-900 text-sm font-medium border-b border-gray-300 w-full pb-2">
                                            Media
                                        </div>
                                        <div class="text-gray-400 text-xs font-light pt-2">
                                            Last Update
                                        </div>
                                        <div class="text-gray-900 text-xs pt-2">
                                            {{ $mediaFolderModified ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#FFCC8A] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                                    </svg>  
                                </div>
                                <div class="absolute top-3 right-5 text-xs font-medium">
                                    @php
                                        $size = $mediaFolderSize; // Total size in bytes
                                
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
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 lg:row-span-1 order-4 md:order-5 lg:order-4 place-items-center cursor-pointer select-none" ondblclick="window.location.href='{{ route('archive') }}'">
                            <div class="flex relative">
                                <div class="inverted-radius flex items-end justify-center pb-7">
                                    <div class="text-center mx-5 w-full">
                                        <div class="text-gray-900 text-sm font-medium border-b border-gray-300 w-full pb-2">
                                            Archive
                                        </div>
                                        <div class="text-gray-400 text-xs font-light pt-2">
                                            Last Update
                                        </div>
                                        <div class="text-gray-900 text-xs pt-2">
                                            {{ $archiveFolderModified ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#DA8AFF] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                        <path fill-rule="evenodd" d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="absolute top-3 right-5 text-xs font-medium">
                                    @php
                                        $size = $archiveFolderSize; // Total size in bytes
                                
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
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 lg:col-span-2 lg:row-span-2 order-7 md:order-2 lg:order-5 place-items-center">
                            <div class="bg-white flex flex-col rounded-xl w-full h-full p-5">
                                <div class="font-medium text-md">Shared Files</div>
                                <div class="flex items-center justify-between border-1 rounded-lg border-blue-400 p-2 mt-2">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 me-2 text-orange-500">
                                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                        </svg>
                                        
                                        <span>adsf</span>
                                    </div>
                                    <div class="flex relative me-1">
                                        <div class="w-8 h-8 overflow-hidden absolute right-3 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                        <div class="w-8 h-8 overflow-hidden absolute right-7 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                        <div class="w-8 h-8 overflow-hidden absolute right-11 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-1 rounded-lg border-blue-400 p-2 mt-2">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 me-2 text-orange-500">
                                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                        </svg>
                                        
                                        <span>adsf</span>
                                    </div>
                                    <div class="flex relative me-1">
                                        <div class="w-8 h-8 overflow-hidden absolute right-3 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                        <div class="w-8 h-8 overflow-hidden absolute right-7 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                        <div class="w-8 h-8 overflow-hidden absolute right-11 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-1 rounded-lg border-blue-400 p-2 mt-2">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 me-2 text-orange-500">
                                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                        </svg>
                                        
                                        <span>adsf</span>
                                    </div>
                                    <div class="flex relative me-1">
                                        <div class="w-8 h-8 overflow-hidden absolute right-3 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                        <div class="w-8 h-8 overflow-hidden absolute right-7 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                        <div class="w-8 h-8 overflow-hidden absolute right-11 -top-4 bg-gray-100 rounded-full">
                                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-3 lg:col-span-2 lg:row-span-3 order-8 md:order-8 lg:order-6 place-items-center">
                            <div class="flex flex-col w-full h-full">
                                <div class="font-medium text-lg">Files</div>
                                <div class="flex flex-col bg-white rounded-xl w-full h-full p-3">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        @foreach($photos->chunk(1) as $column) <!-- Chunk images into sets of 4 -->
                                            <div class="grid gap-4">
                                                @foreach($column as $photo)
                                                    <div>
                                                        <!-- Display the image -->
                                                        <img class="h-auto max-w-full rounded-lg" 
                                                             src="{{ asset('storage/' . $photo) }}" alt="">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lg:col-span-1 lg:row-span-1 order-5 md:order-6 lg:order-7 place-items-center cursor-pointer select-none" ondblclick="window.location.href='{{ route('others') }}'">
                            <div class="flex relative">
                                <div class="inverted-radius flex items-end justify-center pb-7">
                                    <div class="text-center mx-5 w-full">
                                        <div class="text-gray-900 text-sm font-medium border-b border-gray-300 w-full pb-2">
                                            Others
                                        </div>
                                        <div class="text-gray-400 text-xs font-light pt-2">
                                            Last Update
                                        </div>
                                        <div class="text-gray-900 text-xs pt-2">
                                            {{ $otherFolderModified ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#5BD0F4] p-3">
                                    <svg class="shrink-0 w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                                    </svg>
                                </div>
                                <div class="absolute top-3 right-5 text-xs font-medium">
                                    @php
                                        $size = $otherFolderSize; // Total size in bytes
                                
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
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 lg:row-span-1 order-6 md:order-7 lg:order-8 place-items-center cursor-pointer select-none" ondblclick="window.location.href='{{ route('trash') }}'">
                            <div class="flex relative">
                                <div class="inverted-radius flex items-end justify-center pb-7">
                                    <div class="text-center mx-5 w-full">
                                        <div class="text-gray-900 text-sm font-medium border-b border-gray-300 w-full pb-2">
                                            Trash
                                        </div>
                                        <div class="text-gray-400 text-xs font-light pt-2">
                                            Last Update
                                        </div>
                                        <div class="text-gray-900 text-xs pt-2">
                                            {{ $trashFolderModified ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#0F52FF] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="absolute top-3 right-5 text-xs font-medium">
                                    @php
                                        $size = $trashFolderSize; // Total size in bytes
                                
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
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-2 lg:row-span-1 order-9 md:order-9 lg:order-9 place-items-center">
                            <div class="bg-[#C8EAFF] flex flex-col lg:flex-row w-full h-full items-center shadow-lg rounded-xl p-5">
                                <div class="w-3/4 font-medium mb-1 lg:mb-0">
                                    Get more storage for more storage.
                                </div>
                                <div class="w-1/4 flex flex-col items-center">
                                    <img src="{{ asset('img/upgrade-icon.png') }}" alt="upgrade-icon" class="w-10 mb-2">
                                    <button class="bg-[#9CCBFF] font-medium cursor-pointer hover:bg-blue-200 rounded-xl py-1 px-5">Upgrade</button>
                                </div>
                            </div>
                        </div>
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
            upload: '{{ route("upload") }}',
            uplodaCheck: '{{ route("upload.check") }}',
        }
    };
</script>
<script src="{{ asset('js/uploadFile.js') }}"></script>

@endsection