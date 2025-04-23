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
                <div class="m-5 p-5 bg-[#E8F8FF] rounded-xl">
                    <div class="grid grid-flow-row lg:grid-rows-4 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div class="md:col-span-1 lg:col-span-2 lg:row-span-1 order-1 md:order-1 lg:order-1 place-items-center">
                            <div class="flex items-center justify-center bg-[#9CCBFF] rounded-xl shadow-lg w-full h-full">
                                <div class="flex flex-col lg:flex-row items-center justify-center text-gray-900 text-xs py-4">
                                    <div class="radial-progress md:me-5" style="--value:37; --size:7rem; --thickness:7px;">
                                        <div class="flex flex-col text-center">
                                            <span class="font-bold text-lg">37%</span>
                                            <span class="text-md">Space Used</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xl">Available Storage</span>
                                        <span class="text-md">123/123</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 lg:row-span-1 order-2 md:order-3 lg:order-2 place-items-center">
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
                                            11:00 AM. 02 Dec
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#FF9C9C] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                    </svg>    
                                </div>
                                <div class="absolute top-3 right-9 text-xs font-medium">
                                    7GB
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 lg:row-span-1 order-3 md:order-4 lg:order-3 place-items-center">
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
                                            11:00 AM. 02 Dec
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#FFCC8A] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                                    </svg>  
                                </div>
                                <div class="absolute top-3 right-9 text-xs font-medium">
                                    15GB
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 lg:row-span-1 order-4 md:order-5 lg:order-4 place-items-center">
                            <div class="flex relative">
                                <div class="inverted-radius flex items-end justify-center pb-7">
                                    <div class="text-center mx-5 w-full">
                                        <div class="text-gray-900 text-sm font-medium border-b border-gray-300 w-full pb-2">
                                            Reviewers
                                        </div>
                                        <div class="text-gray-400 text-xs font-light pt-2">
                                            Last Update
                                        </div>
                                        <div class="text-gray-900 text-xs pt-2">
                                            11:00 AM. 02 Dec
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#DA8AFF] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path d="M7.5 3.375c0-1.036.84-1.875 1.875-1.875h.375a3.75 3.75 0 0 1 3.75 3.75v1.875C13.5 8.161 14.34 9 15.375 9h1.875A3.75 3.75 0 0 1 21 12.75v3.375C21 17.16 20.16 18 19.125 18h-9.75A1.875 1.875 0 0 1 7.5 16.125V3.375Z" />
                                        <path d="M15 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 17.25 7.5h-1.875A.375.375 0 0 1 15 7.125V5.25ZM4.875 6H6v10.125A3.375 3.375 0 0 0 9.375 19.5H16.5v1.125c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V7.875C3 6.839 3.84 6 4.875 6Z" />
                                    </svg> 
                                </div>
                                <div class="absolute top-3 right-9 text-xs font-medium">
                                    11GB
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
                            <div class=" flex flex-col w-full h-full">
                                <div class="font-medium text-lg">Files</div>
                                <div class="flex flex-col bg-white rounded-xl w-full h-full p-3">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="grid gap-4">
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image.jpg" alt="">
                                            </div>
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-1.jpg" alt="">
                                            </div>
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-2.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="grid gap-4">
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-3.jpg" alt="">
                                            </div>
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-4.jpg" alt="">
                                            </div>
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-5.jpg" alt="">
                                            </div>
                                        </div>
                                        <div class="grid gap-4">
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-6.jpg" alt="">
                                            </div>
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-7.jpg" alt="">
                                            </div>
                                            <div>
                                                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-8.jpg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lg:col-span-1 lg:row-span-1 order-5 md:order-6 lg:order-7 place-items-center">
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
                                            11:00 AM. 02 Dec
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#5BD0F4] p-3">
                                    <svg class="shrink-0 w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                                    </svg>
                                </div>
                                <div class="absolute top-3 right-9 text-xs font-medium">
                                    7GB
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 lg:row-span-1 order-6 md:order-7 lg:order-8 place-items-center">
                            <div class="flex relative">
                                <div class="inverted-radius flex items-end justify-center pb-7">
                                    <div class="text-center mx-5 w-full">
                                        <div class="text-gray-900 text-sm font-medium border-b border-gray-300 w-full pb-2">
                                            Folders
                                        </div>
                                        <div class="text-gray-400 text-xs font-light pt-2">
                                            Last Update
                                        </div>
                                        <div class="text-gray-900 text-xs pt-2">
                                            11:00 AM. 02 Dec
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-0 left-0 rounded-full bg-[#0F52FF] p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-white">
                                        <path d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 0 0-3-3h-3.879a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H6a3 3 0 0 0-3 3v3.162A3.756 3.756 0 0 1 4.094 9h15.812ZM4.094 10.5a2.25 2.25 0 0 0-2.227 2.568l.857 6A2.25 2.25 0 0 0 4.951 21H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-2.227-2.568H4.094Z" />
                                    </svg>
                                </div>
                                <div class="absolute top-3 right-9 text-xs font-medium">
                                    9GB
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

@endsection