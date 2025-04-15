@extends('layout.master')

@section('body')

<body class="bg-[#C8EAFF]">
    <div class="flex h-screen items-center justify-center p-10">
        <div class="bg-white w-4xl rounded-4xl shadow-lg">
            <div class="flex flex-row">
                <div class="w-1/2 items-center p-10">
                    <div class="flex flex-col h-full items-center justify-center">                    
                        <img src="{{ asset('img/logo.png') }}" class="h-10 w-30 mb-3" alt="logo">
                        <div class="text-2xl font-medium mb-3">
                            Welcome
                        </div>
                        <a class="relative mb-5 w-full text-gray-900 cursor-pointer hover:bg-blue-300 bg-white shadow-lg font-medium rounded-lg text-sm py-3 text-center focus:ring-2 focus:outline-none focus:ring-primary-300">
                            <img src="{{ asset('img/google-logo.png') }}" class="h-6 w-6 absolute left-5 top-2.5" alt="google-logo">
                            Log in with Google
                        </a>
                        <div class="relative items-center justify-center w-full">
                            <hr class="h-px my-5 bg-gray-400 border-0">
                            <span class="absolute px-3 font-medium text-gray-400 -translate-x-1/2 -translate-y-1/2 bg-white left-1/2 top-1/2">or Log in with Email</span>
                        </div>
                        <div class="w-full">
                            <div class="relative z-0 w-full mb-5 group">
                                <input type="text" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-gray-400 bg-transparent border-0 border-b-2 border-blue-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="" required />
                                <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                            </div>
                            <div class="relative z-0 w-full mb-2 group">
                                <input type="text" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-gray-400 bg-transparent border-0 border-b-2 border-blue-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="" required />
                                <label for="password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                                <button type="button" tabindex="-1" class="absolute right-0 top-1/2 transform -translate-y-1/2 text-sm text-gray-500 px-2 focus:outline-none" onclick="togglePasswordVisibility('confirm-password', this)" >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#9CCBFF" class="size-5">
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                        <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                    <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-1 focus:ring-blue-300" required="">
                                    </div>
                                    <div class="ml-3 text-sm">
                                    <label for="remember" class="text-blue-400">Keep me logged in</label>
                                    </div>
                                </div>
                                <a href="#" class="text-sm text-red-400 hover:underline">Forgot password?</a>
                            </div>
                        </div>
                        <button type="submit" class="relative w-full text-gray-900 cursor-pointer bg-blue-300 hover:bg-blue-400 shadow-lg font-medium rounded-lg text-sm py-3 text-center focus:ring-2 focus:outline-none focus:ring-primary-300">
                            Log In 
                        </button>
                    </div>
                </div>
                <div class="w-1/2 justify-center">
                    <div class="flex flex-col h-full items-center justify-center">
                        <img src="{{ asset('img/login-pic.png') }}" class="" alt="side-pic">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection
