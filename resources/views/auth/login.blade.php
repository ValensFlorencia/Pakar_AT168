{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – Sistem Pakar Diagnosa Penyakit Ayam</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-yellow-100 via-amber-100 to-orange-100 flex items-center justify-center p-4">

    {{-- Kartu besar dua kolom --}}
    <div class="w-full max-w-5xl bg-white/80 backdrop-blur rounded-3xl shadow-2xl overflow-hidden">
        <div class="grid md:grid-cols-2">

            {{-- KIRI: welcome text --}}
            <div class="bg-white px-10 py-12 flex flex-col justify-center text-center">
                <div class="flex flex-col items-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-yellow-300/70 flex items-center justify-center shadow-md mb-3">
                        <span class="text-4xl">🐔</span>
                    </div>

                    <h1 class="text-3xl font-extrabold text-amber-600 tracking-wide">
                        SISTEM PAKAR
                    </h1>
                    <h2 class="text-xl font-semibold text-slate-700 mt-1">
                        DIAGNOSA PENYAKIT AYAM
                    </h2>

                    <p class="mt-4 text-sm text-slate-500 leading-relaxed max-w-sm">
                        Masuk untuk melakukan diagnosa dan melihat riwayat diagnosa sesuai hak akses pengguna.
                    </p>
                </div>
            </div>

            {{-- KANAN: area login --}}
            <div class="relative bg-gradient-to-br from-amber-300 via-yellow-200 to-orange-200 px-6 py-10 flex items-center justify-center">

                {{-- dekorasi bulat-bulat pastel --}}
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/40 rounded-full blur-2xl opacity-70"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-orange-200/40 rounded-full blur-3xl opacity-70"></div>

                {{-- CARD LOGIN --}}
                <div class="relative w-full max-w-sm bg-white/95 rounded-2xl shadow-xl px-7 py-6">
                    <h2 class="text-center text-lg font-semibold text-amber-700">
                        SIGN IN
                    </h2>
                    <p class="text-center text-xs text-slate-500 mb-4">
                        Masuk untuk mengakses portal sistem pakar.
                    </p>

                    {{-- ✅ ALERT ERROR LOGIN --}}
                    @if ($errors->any())
                        @php
                            $allErrors = $errors->all();

                            // Breeze biasanya: auth failed dilempar ke field 'email' dengan pesan auth.failed
                            $authFailedMsg = $errors->first('email');

                            // Anggap auth-failed jika:
                            // - hanya ada 1 error, dan error itu berada di field email
                            // - dan password tidak punya error "required"
                            $hasAuthFailed = count($allErrors) === 1
                                && !empty($authFailedMsg)
                                && !$errors->has('password');
                        @endphp

                        @if ($errors->has('email') && !$errors->has('password'))
                        <div class="mb-3 text-xs text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                            <strong>Email atau Password yang Anda masukkan salah.</strong>
                        </div>
                    @endif

                    @endif

                    {{-- ✅ novalidate + HAPUS required agar tidak muncul tooltip browser --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
                        @csrf

                        {{-- Email --}}
                        <div class="space-y-1">
                            <label for="email" class="block text-xs font-semibold text-slate-600">
                                Email
                            </label>
                            <div class="flex items-center bg-yellow-50 border border-amber-200 rounded-full px-4 py-2">
                                {{-- icon user --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 14a4 4 0 10-8 0v1a4 4 0 008 0v-1z" />
                                </svg>

                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       autofocus
                                       placeholder="masukkan email"
                                       class="ml-2 flex-1 bg-transparent border-none outline-none text-sm text-slate-700 placeholder:text-slate-400" />
                            </div>

                            {{-- error field email (opsional, tidak ganggu) --}}
                            @error('email')
                                <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-semibold text-slate-600">
                                Password
                            </label>
                            <div class="flex items-center bg-yellow-50 border border-amber-200 rounded-full px-4 py-2">
                                {{-- icon key --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 7a5 5 0 10-4 8.9V17l2 2 2-2-1-1 1-1-1-1a5 5 0 001-8z" />
                                </svg>

                                <input id="password"
                                       type="password"
                                       name="password"
                                       autocomplete="current-password"
                                       class="ml-2 flex-1 bg-transparent border-none outline-none text-sm text-slate-700 placeholder:text-slate-400" />
                            </div>

                            {{-- error field password (opsional, tidak ganggu) --}}
                            @error('password')
                                <div class="text-[11px] text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol login --}}
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-2.5 rounded-full bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-400
                                           text-white text-sm font-semibold shadow-md hover:from-amber-500 hover:to-orange-500
                                           transition-transform transform hover:-translate-y-[1px]">
                                Login
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</body>
</html>
