<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Penjual — Warung Mas Ndutt94</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Inter & Fredoka One -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fredoka+One&display=swap" rel="stylesheet">

  <style>
    :root {
      --color-primary: #dc2626;
      --color-secondary: #facc15;
    }

    body { 
      font-family: 'Inter', sans-serif;
      background-color: #E6DCD2;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
      padding: 20px; /* biar ga mepet di HP */
    }

    /* Background Gelombang */
    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-image:
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' fill-opacity='1' d='M0,224L48,229.3C96,235,192,245,288,229.3C384,213,480,171,576,170.7C672,171,768,213,864,240C960,267,1056,277,1152,256C1248,235,1344,181,1392,154.7L1440,128L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-size: cover;
      opacity: 0.75;
      z-index: 0;
    }

    /* Card Login */
    .login-card {
      background: rgba(255, 255, 255, 0.92);
      border-radius: 20px;
      backdrop-filter: blur(10px);
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 420px; /* biar bagus di desktop */
    }

    .brand-title {
      font-family: 'Fredoka One', cursive;
    }
  </style>
</head>

<body class="flex items-center justify-center">

  <!-- CARD LOGIN -->
  <div class="login-card p-8 md:p-10 shadow-2xl border border-white/40">

    <div class="text-center mb-6">
      <h1 class="text-3xl md:text-4xl font-black brand-title text-[var(--color-primary)] tracking-wide">
        Login Penjual
      </h1>
      <p class="text-slate-600 text-sm mt-1">Kelola menu & transaksi warung Anda</p>
    </div>

    {{-- ERROR --}}
    @if($errors->any())
      <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-2 rounded-md text-sm">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- SUCCESS --}}
    @if(session('success'))
      <div class="mb-4 bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-md text-sm">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('seller.login.submit') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-red-300 focus:border-red-500 text-sm" 
          required autofocus>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password"
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-red-300 focus:border-red-500 text-sm"
          required>
      </div>

      <button type="submit"
        class="w-full bg-[var(--color-primary)] text-white font-semibold py-2.5 rounded-lg hover:bg-red-700 transition shadow-md">
        Masuk
      </button>
    </form>

    <div class="mt-5 text-center">
      <a href="{{ route('login.choice') }}"
        class="text-sm text-gray-600 hover:text-[var(--color-primary)] transition font-medium">
         ← Kembali ke Pilihan Role
      </a>
    </div>
  </div>

  <footer class="absolute bottom-5 text-center w-full text-xs text-gray-600 font-medium">
    © {{ date('Y') }} Warung Mas Ndutt94 — Sistem Pemesanan
  </footer>

</body>
</html>
