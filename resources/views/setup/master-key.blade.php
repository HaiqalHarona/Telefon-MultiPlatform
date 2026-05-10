<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Key Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#09090b] min-h-screen flex items-center justify-center p-4 font-sans text-white">

    <div class="max-w-lg w-full bg-[#18181b] rounded-3xl p-8 text-center border border-white/10 shadow-2xl">
        
        <h1 class="text-2xl font-bold mb-2">Backend Test Run</h1>
        <p class="text-[#a1a1aa] text-sm mb-8">
            If your Controller successfully flashed the session data, the 24 words will appear below.
        </p>

        {{-- The actual test: Printing the session variable --}}
        <div class="bg-[#1e1e21] border border-white/5 rounded-2xl p-6 mb-8 text-left">
            @if(session('recovery_phrase'))
                <p class="font-mono text-pink-500 text-lg leading-relaxed break-words">
                    {{ session('recovery_phrase') }}
                </p>
            @else
                <p class="font-mono text-red-500 text-sm tracking-widest text-center">
                    ERROR: NO KEY FOUND IN SESSION
                </p>
            @endif
        </div>

        {{-- Continue button to verify your routing --}}
        <a href="{{ route('messenger') }}" 
           class="block w-full py-3 rounded-xl bg-pink-500 hover:bg-pink-600 text-white font-bold transition-colors">
            Continue to Messenger
        </a>
        
    </div>