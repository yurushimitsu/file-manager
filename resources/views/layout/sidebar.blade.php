<div class="fixed top-20 ms-3 lg:hidden bg-white">
    <ul class="space-y-2 pt-3 font-medium">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="z-20 inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-[#5BD0F4] hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-200">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
        </button>
        <li>
           <a href="{{ Route('dashboard') }}" class="flex items-center justify-center p-2 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('dashboard')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
              <svg class="w-5 h-5 text-gray-500 group-hover:text-white {{ (Route::is('dashboard')) ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                 <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                 <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
              </svg>
           </a>
        </li>
        <li>
            <a href="{{ Route('documents') }}" class="flex items-center justify-center p-2 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('documents')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('documents')) ? 'text-white' : '' }}">
                    <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                    <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                </svg>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center justify-center p-2 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('media')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('media')) ? 'text-white' : '' }}">
                    <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                </svg>
            </a>
        </li>
        {{-- <li>
            <a href="#" class="flex items-center justify-center p-2 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white">
                    <path d="M7.5 3.375c0-1.036.84-1.875 1.875-1.875h.375a3.75 3.75 0 0 1 3.75 3.75v1.875C13.5 8.161 14.34 9 15.375 9h1.875A3.75 3.75 0 0 1 21 12.75v3.375C21 17.16 20.16 18 19.125 18h-9.75A1.875 1.875 0 0 1 7.5 16.125V3.375Z" />
                    <path d="M15 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 17.25 7.5h-1.875A.375.375 0 0 1 15 7.125V5.25ZM4.875 6H6v10.125A3.375 3.375 0 0 0 9.375 19.5H16.5v1.125c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V7.875C3 6.839 3.84 6 4.875 6Z" />
                </svg>
            </a>
        </li> --}}
        <li>
            <a href="{{ route('archive') }}" class="flex items-center justify-center p-2 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('archive')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('archive')) ? 'text-white' : '' }}">
                    <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                    <path fill-rule="evenodd" d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                </svg>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center justify-center p-2 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                <svg class="shrink-0 w-5 h-5 text-gray-500 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="{{ route('trash') }}" class="flex items-center justify-center p-2 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('trash')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('trash')) ? 'text-white' : '' }}">
                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                </svg>
            </a>
        </li>
    </ul>
</div>
 
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 md:z-0 w-50 h-screen transition-transform -translate-x-full lg:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-5 pt-20 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium pt-5">
            <li>
                <a href="{{ Route('dashboard') }}" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('dashboard')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-white {{ (Route::is('dashboard')) ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ Route('documents') }}" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('documents')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('documents')) ? 'text-white' : '' }}">
                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                    </svg>                          
                   <span class="ms-3">Documents</span>
                </a>
            </li>
            <li>
                <a href="{{ Route('media') }}" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('media')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('media')) ? 'text-white' : '' }}">
                        <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                    </svg>
                   <span class="ms-3">Media</span>
                </a>
            </li>
            {{-- <li>
                <a href="#" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white">
                        <path d="M7.5 3.375c0-1.036.84-1.875 1.875-1.875h.375a3.75 3.75 0 0 1 3.75 3.75v1.875C13.5 8.161 14.34 9 15.375 9h1.875A3.75 3.75 0 0 1 21 12.75v3.375C21 17.16 20.16 18 19.125 18h-9.75A1.875 1.875 0 0 1 7.5 16.125V3.375Z" />
                        <path d="M15 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 17.25 7.5h-1.875A.375.375 0 0 1 15 7.125V5.25ZM4.875 6H6v10.125A3.375 3.375 0 0 0 9.375 19.5H16.5v1.125c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V7.875C3 6.839 3.84 6 4.875 6Z" />
                    </svg>
                   <span class="ms-3">Reviewers</span>
                </a>
            </li> --}}
            <li>
                <a href="{{ route('archive') }}" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('archive')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('archive')) ? 'text-white' : '' }}">
                        <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                        <path fill-rule="evenodd" d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                    </svg>
                    <span class="ms-3">Archive</span>
                </a>
            </li>
            <li>
                <a href="{{ route('others') }}" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('others')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                    <svg class="shrink-0 w-5 h-5 text-gray-500 group-hover:text-white {{ (Route::is('others')) ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                    </svg>
                   <span class="ms-3">Others</span>
                </a>
            </li>
            <li>
                <a href="{{ route('trash') }}" class="flex items-center p-3 text-gray-900 rounded-xl hover:bg-[#5BD0F4] hover:text-white {{ (Route::is('trash')) ? 'bg-[#5BD0F4] text-white' : '' }} focus:outline-none focus:ring-2 focus:ring-gray-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-gray-500 group-hover:text-white {{ (Route::is('trash')) ? 'text-white' : '' }}">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                    </svg>
                    <span class="ms-3">Trash</span>
                </a>
            </li>
        </ul>
    </div>
</aside>