<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Outlet Info — Warung Mas Ndutt94</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- FONT POPPINS + INTER -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --color-primary: #dc2626;
      --color-accent: #facc15;
    }

    body {
      background-color: #E6DCD2;
      font-family: Inter, sans-serif;
    }

    .title-font {
      font-family: "Poppins", sans-serif;
    }

    /* Card dengan shadow elegan */
    .section-card {
      background: white;
      border-radius: 20px;
      padding: 22px 24px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Wave background lembut seperti halaman lain */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23d1b8a9' d='M0,224L60,202.7C120,181,240,139,360,138.7C480,139,600,181,720,197.3C840,213,960,203,1080,186.7C1200,171,1320,149,1380,138.7L1440,128V0H0Z'/%3E%3C/svg%3E");
      opacity: .65;
      z-index: -1;
      background-size: cover;
      background-repeat: no-repeat;
    }
  </style>

</head>

<body class="min-h-screen">

  <div class="max-w-3xl mx-auto py-10 px-6">

    <!-- BACK BUTTON -->
    <a href="{{ route('buyer.menu') }}" 
       class="text-red-600 font-semibold mb-6 inline-block hover:underline text-lg">
      ← Kembali
    </a>

    <!-- TITLE -->
    <h1 class="text-5xl title-font font-extrabold text-red-600 text-center mb-3">
      WARUNG MAS NDUTT94
    </h1>

    <p class="text-center text-gray-900 text-lg font-medium title-font">
      Komplek Pakujaya Permai A1 No. 29, Tangerang Selatan
    </p>

    <!-- DESKRIPSI -->
    <p class="text-center text-gray-700 mt-5 leading-relaxed max-w-2xl mx-auto text-[17px]">
      Warung Mas Ndutt94 adalah tempat makan sederhana dengan cita rasa rumahan.
      Menyajikan makanan enak, murah, dan selalu siap melayani kamu setiap hari.
    </p>

    <!-- MEDIA SOSIAL -->
    <div class="section-card mt-12 border-l-4 border-yellow-400">
      <h3 class="text-xl font-bold title-font mb-3">Media Sosial</h3>

      <p class="text-gray-700 leading-relaxed mb-3">
        Hubungi atau ikuti kami melalui tautan berikut:
      </p>

      <div class="space-y-2 text-[17px]">
        <p>
          <span class="font-semibold text-gray-900">WhatsApp:</span>
          <a href="https://wa.me/6283871524206" target="_blank"
             class="text-red-600 hover:underline">
            0838-7152-4206
          </a>
        </p>

        <p>
          <span class="font-semibold text-gray-900">Instagram:</span>
          <a href="https://instagram.com/warungmasndutt94" target="_blank" 
             class="text-red-600 hover:underline">
            @warungmasndutt94
          </a>
        </p>
      </div>
    </div>

    <!-- OPERATIONAL HOURS -->
    <div class="mt-12">
      <h3 class="text-2xl font-bold title-font mb-4">
        Operational Hours
      </h3>

      <div class="section-card p-0 overflow-hidden">

        @foreach(['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'] as $day)
          <div class="flex justify-between px-6 py-3 border-b last:border-none">
            <span class="font-semibold text-gray-900 title-font text-[15px] tracking-wide">
              {{ $day }}
            </span>

            <span class="text-gray-800 font-medium">
              06:00 - 19:00
            </span>
          </div>
        @endforeach

      </div>
    </div>

  </div>

</body>
</html>
