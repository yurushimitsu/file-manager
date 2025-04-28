<nav class="p-5 fixed items-center inset-x-0 top-0 z-50 bg-white">
    <div class="flex flex-wrap items-center justify-between mx-auto">
        <div href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('img/Logo.png') }}" class="h-10" alt="Logo" />
        </div>
        <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-[#1556FF] rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <div class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-300 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                <div class="md:me-5 mb-5 md:mb-0">
                    <form class="w-xs md:w-2xs mx-auto">   
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="search" id="default-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-blue-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
                        </div>
                    </form>
                </div>
                <div class="flex justify-center md:me-5">
                    <button id="uploadButton" type="button" data-dropdown-toggle="uploadDropdown" class="text-white font-bold bg-[#5BD0F4] w-30 hover:bg-blue-400 cursor-pointer inline-flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full text-sm py-2 me-5 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 me-1">
                            <path fill-rule="evenodd" d="M10.5 3.75a6 6 0 0 0-5.98 6.496A5.25 5.25 0 0 0 6.75 20.25H18a4.5 4.5 0 0 0 2.206-8.423 3.75 3.75 0 0 0-4.133-4.303A6.001 6.001 0 0 0 10.5 3.75Zm2.03 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v4.94a.75.75 0 0 0 1.5 0v-4.94l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd" />
                        </svg>
                        Upload
                    </button>
                    <div id="uploadDropdown" class="z-20 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44">
                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="profiletButton">
                            <li>
                                <button id="newFolderButton" class="block w-full text-start cursor-pointer px-4 py-2 hover:bg-gray-100">New Folder</button>
                            </li>
                            <hr class="mx-3 text-gray-300">
                            <li>
                                <button id="fileUploadButton" class="block w-full text-start cursor-pointer px-4 py-2 hover:bg-gray-100">File Upload</button>
                            </li>
                            <hr class="mx-3 text-gray-300">
                            <li>
                                <button id="folderUploadButton" class="block w-full text-start cursor-pointer px-4 py-2 hover:bg-gray-100">Folder Upload</button>
                            </li>
                        </ul>
                    </div>
                    <button id="profiletButton"  type="button" data-dropdown-toggle="profileDropdown" class="bg-transparent px-2 hover:text-blue-400 cursor-pointer text-center inline-flex items-center">
                        <div class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full me-3">
                            <img src="{{ asset('img/login-pic.png') }}" alt="profile-pic">
                        </div>
                        Name
                    </button>
                    <!-- Dropdown menu -->
                    <div id="profileDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44">
                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="profiletButton">
                            <li>
                                <a href="" class="block px-4 py-2 hover:bg-gray-100">Account</a>
                            </li>
                            <hr class="mx-3 text-gray-300">
                            <li>
                                <a href="" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('newFolderButton').addEventListener('click', function() {
        Swal.fire({
            title: 'New Folder',
            input: 'text',
            inputPlaceholder: 'Enter folder name',
            inputValue: 'Untitled Folder',
            showCancelButton: true,
            confirmButtonColor: "#0D4AE5",
            cancelButtonColor: "#d33",
            confirmButtonText: 'Create',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) {
                    return 'Please enter a folder name';
                }
            },
            didOpen: () => {
                // Select all text in the input field when the SweetAlert is opened
                const input = Swal.getInput();
                input.select();
            }

        }).then((result) => {
            if (result.isConfirmed) {
                const folderName = result.value;  

                Swal.close();
                fetch("{{ route('createFolder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',  // Make sure it's JSON
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF Token
                    },
                    body: JSON.stringify({ folderName: folderName })  // Send the folder name
                })
                .then(response => response.json())  // Parse the JSON response
                .then(data => {
                    if (data.message === 'Folder created successfully') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Folder Created',
                            text: `${folderName} created`,
                            timer: 2000,
                            showConfirmButton: false,
                            willClose: () => { location.reload(); }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong',
                    timer: 2000,
                    showConfirmButton: false
                    });
                });
            }
        });
    });
</script>