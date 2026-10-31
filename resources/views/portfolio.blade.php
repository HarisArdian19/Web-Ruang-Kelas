<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title id="page-title">Portofolio Profesional - Haris Ardian</title>
  
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            neo: {
              bg: '#fafaf8',
              border: '#111827',
              blue: '#2563eb',
              yellow: '#fde047',
              red: '#ef4444'
            }
          }
        }
      }
    }
  </script>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Custom Styles via Laravel Asset Helper -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="text-slate-900 antialiased selection:bg-yellow-300 selection:text-black">

  <!-- ==================== TOP FLOATING NAVBAR ==================== -->
  <header class="sticky top-3 z-40 px-4 max-w-6xl mx-auto">
    <nav class="bg-white neo-card px-4 py-3 flex items-center justify-between shadow-[3px_3px_0px_#111827]">
      <!-- Brand Logo / Badge -->
      <a href="#hero" class="flex items-center gap-2 group">
        <span id="nav-initials" class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center neo-border text-sm shadow-[1.5px_1.5px_0px_#111827] group-hover:scale-105 transition-transform">
          HA
        </span>
        <span id="nav-name" class="font-bold tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors hidden sm:inline">
          Haris Ardian
        </span>
      </a>

      <!-- Desktop Nav Links -->
      <div class="hidden lg:flex items-center gap-6 text-sm font-semibold text-slate-700">
        <a href="#tentang" class="hover:text-blue-600 transition-colors">Tentang</a>
        <a href="#galeri" class="hover:text-blue-600 transition-colors">Galeri</a>
        <a href="#pengalaman" class="hover:text-blue-600 transition-colors">Pengalaman</a>
        <a href="#pendidikan" class="hover:text-blue-600 transition-colors">Pendidikan</a>
        <a href="#keahlian" class="hover:text-blue-600 transition-colors">Keahlian</a>
        <a href="#sertifikat" class="hover:text-blue-600 transition-colors">Sertifikat</a>
        <a href="#kontak" class="hover:text-blue-600 transition-colors">Kontak</a>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-2">
        <!-- Live Editor Toggle Button -->
        <button id="btn-open-editor" class="neo-btn bg-yellow-300 text-slate-900 text-xs px-3 py-1.5 rounded-lg hover:bg-yellow-400">
          <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
          <span>Mode Edit</span>
        </button>

        <!-- Dropdown Navigation Pill -->
        <div class="relative">
          <button id="btn-nav-dropdown" class="neo-btn bg-white text-slate-900 text-xs px-3 py-1.5 rounded-lg hover:bg-slate-50 flex items-center gap-1.5">
            <span>Navigasi Halaman</span>
            <span class="text-amber-500">★</span>
          </button>

          <!-- Dropdown Menu -->
          <div id="nav-dropdown-menu" class="hidden absolute right-0 mt-2 w-56 bg-white neo-card p-2 shadow-[4px_4px_0px_#111827] animate-fade-in z-50">
            <div class="text-[10px] uppercase font-bold text-slate-400 px-3 py-1">Pilih Bagian</div>
            <a href="#tentang" class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100">
              <i data-lucide="user" class="w-4 h-4 text-blue-600"></i> Tentang Saya
            </a>
            <a href="#galeri" class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100">
              <i data-lucide="image" class="w-4 h-4 text-purple-600"></i> Galeri Kegiatan
            </a>
            <a href="#pengalaman" class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100">
              <i data-lucide="briefcase" class="w-4 h-4 text-amber-600"></i> Pengalaman Kerja
            </a>
            <a href="#pendidikan" class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100">
              <i data-lucide="graduation-cap" class="w-4 h-4 text-emerald-600"></i> Pendidikan
            </a>
            <a href="#keahlian" class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100">
              <i data-lucide="cpu" class="w-4 h-4 text-rose-600"></i> Keahlian & Tools
            </a>
            <a href="#sertifikat" class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100">
              <i data-lucide="award" class="w-4 h-4 text-yellow-600"></i> Sertifikasi
            </a>
            <a href="#kontak" class="dropdown-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100">
              <i data-lucide="send" class="w-4 h-4 text-blue-600"></i> Kontak Kolaborasi
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- ==================== MAIN CONTENT CONTAINER ==================== -->
  <main class="max-w-6xl mx-auto px-4 py-8 space-y-16">

    <!-- 1. HERO SECTION -->
    <section id="hero" class="pt-6 sm:pt-12">
      <!-- Announcement Pill -->
      <div class="mb-5 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-yellow-200 neo-border neo-shadow-sm text-xs font-bold text-slate-900">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span id="hero-badge-status">TERBUKA UNTUK RISET, PROYEK & KOLABORASI DIGITAL</span>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        <!-- Hero Text -->
        <div class="lg:col-span-7 space-y-6">
          <div class="space-y-2">
            <p id="hero-greeting" class="text-lg font-semibold text-slate-700 flex items-center gap-2">
              <span>👋 Halo, Saya</span>
            </p>
            <h1 id="hero-name" class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-tight">
              Haris Ardian
            </h1>
            <p id="hero-role" class="text-xl sm:text-2xl font-bold text-blue-600">
              Digital Specialist & Technology Enthusiast
            </p>
          </div>

          <p id="hero-bio" class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
            Menghubungkan potensi inovasi dan strategi digital modern demi terciptanya solusi yang berdampak, berkelanjutan, dan berdaya saing tinggi.
          </p>

          <!-- CTAs -->
          <div class="flex flex-wrap items-center gap-3 pt-2">
            <a id="hero-wa-btn" href="#" target="_blank" class="neo-btn bg-blue-600 text-white px-5 py-3 rounded-xl hover:bg-blue-700 text-sm">
              <i data-lucide="message-circle" class="w-4 h-4"></i>
              <span>Hubungi WhatsApp</span>
            </a>
            <a href="#galeri" class="neo-btn bg-white text-slate-900 px-5 py-3 rounded-xl hover:bg-slate-50 text-sm">
              <span>Lihat Galeri ▾</span>
            </a>
          </div>

          <!-- Email Chip with Copy -->
          <div class="pt-2 flex items-center gap-3">
            <div id="hero-email-chip" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-100 neo-border text-xs font-medium text-slate-700 cursor-pointer hover:bg-slate-200 transition-colors" title="Klik untuk menyalin email">
              <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-500"></i>
              <span id="hero-email-text">haris.ardian@example.com</span>
              <i data-lucide="copy" class="w-3 h-3 text-slate-400"></i>
            </div>
            <span id="copy-toast" class="text-xs font-bold text-emerald-600 opacity-0 transition-opacity">Tersalin!</span>
          </div>
        </div>

        <!-- Hero Photo Card (Polaroid Style matching Video) -->
        <div class="lg:col-span-5 flex justify-center">
          <div class="relative w-full max-w-md">
            <!-- Background Accent Frame -->
            <div class="absolute inset-0 bg-blue-200 rounded-3xl neo-border transform translate-x-3 translate-y-3"></div>

            <!-- Main Photo Card -->
            <div class="relative bg-white neo-card p-4 sm:p-5 shadow-[5px_5px_0px_#111827]">
              <!-- Image Container -->
              <div class="relative overflow-hidden rounded-2xl neo-border bg-slate-100 aspect-[4/4]">
                <img id="hero-photo" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=80" alt="Foto Profil" class="w-full h-full object-cover">
              </div>

              <!-- Bottom Bar inside Card -->
              <div class="mt-4 flex items-center justify-between text-xs font-semibold text-slate-600">
                <div class="flex items-center gap-1.5">
                  <i data-lucide="map-pin" class="w-4 h-4 text-rose-500"></i>
                  <span id="hero-location">Yogyakarta, Indonesia</span>
                </div>
                <div class="flex items-center gap-1 text-slate-400">
                  <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                  <span>Official Profile</span>
                </div>
              </div>

              <!-- Retro Star Badge Sticker on corner -->
              <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-yellow-300 neo-border rounded-xl flex items-center justify-center shadow-[2px_2px_0px_#111827] transform -rotate-12 hover:rotate-0 transition-transform">
                <span class="text-xl">⭐</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. TENTANG SAYA & QUICK STATS -->
    <section id="tentang" class="space-y-6 pt-8">
      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-blue-100 neo-border text-xs font-bold uppercase tracking-wider text-blue-900">
        <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Profil & Ringkasan
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-7 space-y-4">
          <h2 id="about-headline" class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
            Pengembang Solusi Digital & Inovator Berbasis Data
          </h2>
          <div class="space-y-3 text-slate-600 text-base leading-relaxed">
            <p id="about-desc-1">
              Saya berfokus pada pengembangan sistem, analisis data, dan strategi inovasi digital yang mampu menyelesaikan permasalahan nyata di lapangan secara efisien dan terukur.
            </p>
            <p id="about-desc-2">
              Terbiasa bekerja secara kolaboratif lintas disiplin, mengelola proyek end-to-end, serta menyampaikan ide kompleks menjadi solusi yang mudah dipahami oleh berbagai pemangku kepentingan.
            </p>
          </div>
        </div>

        <!-- 4 Stats Cards (Grid 2x2) -->
        <div id="stats-container" class="lg:col-span-5 grid grid-cols-2 gap-4">
          <!-- Dynamically populated via app.js -->
        </div>
      </div>
    </section>

    <!-- 3. GALERI KEGIATAN & RISET -->
    <section id="galeri" class="space-y-6 pt-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-purple-100 neo-border text-xs font-bold uppercase tracking-wider text-purple-900">
            <i data-lucide="image" class="w-3.5 h-3.5"></i> DOKUMENTASI & AKTIVITAS
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Galeri Kegiatan & Riset Lapangan
          </h2>
          <p class="text-sm text-slate-600 mt-1">
            Dokumentasi kunjungan lapangan, riset, dan kegiatan kolaborasi
          </p>
        </div>
      </div>

      <!-- Gallery Grid -->
      <div id="gallery-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Dynamically populated via app.js -->
      </div>
    </section>

    <!-- 4. PENGALAMAN PROFESIONAL & ORGANISASI -->
    <section id="pengalaman" class="space-y-6 pt-8">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-amber-100 neo-border text-xs font-bold uppercase tracking-wider text-amber-900">
          <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> KARIER & PENGALAMAN
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
          Perjalanan Profesional & Riset
        </h2>
        <p class="text-sm text-slate-600 mt-1">
          Rekam jejak kontribusi, kepemimpinan proyek, dan pengalaman industri
        </p>
      </div>

      <!-- Experience Cards List -->
      <div id="experience-container" class="space-y-5">
        <!-- Dynamically populated via app.js -->
      </div>
    </section>

    <!-- 5. LATAR BELAKANG PENDIDIKAN -->
    <section id="pendidikan" class="space-y-6 pt-8">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-emerald-100 neo-border text-xs font-bold uppercase tracking-wider text-emerald-900">
          <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> PENDIDIKAN & STUDI
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
          Latar Belakang Akademis
        </h2>
      </div>

      <!-- Education Cards Grid -->
      <div id="education-container" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Dynamically populated via app.js -->
      </div>
    </section>

    <!-- 6. KEAHLIAN & PENGUASAAN TOOLS -->
    <section id="keahlian" class="space-y-6 pt-8">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-rose-100 neo-border text-xs font-bold uppercase tracking-wider text-rose-900">
          <i data-lucide="cpu" class="w-3.5 h-3.5"></i> KOMPETENSI & TOOLS
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
          Keahlian & Penguasaan Tools
        </h2>
      </div>

      <!-- Skills Categories Grid -->
      <div id="skills-container" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Dynamically populated via app.js -->
      </div>

      <!-- 7. Kemampuan Berbahasa -->
      <div class="pt-6">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-sky-100 neo-border text-xs font-bold uppercase tracking-wider text-sky-900 mb-3">
          <i data-lucide="globe" class="w-3.5 h-3.5"></i> KEMAMPUAN BERBAHASA
        </div>
        <div id="languages-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Dynamically populated via app.js -->
        </div>
      </div>
    </section>

    <!-- 8. SERTIFIKASI PROFESIONAL -->
    <section id="sertifikat" class="space-y-6 pt-8">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-yellow-200 neo-border text-xs font-bold uppercase tracking-wider text-yellow-900">
          <i data-lucide="award" class="w-3.5 h-3.5"></i> KREDENSIAL & LISENSI
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
          Sertifikasi Profesional
        </h2>
        <p class="text-sm text-slate-600 mt-1">
          Bukti kompetensi tervalidasi dari program pelatihan dan sertifikasi resmi
        </p>
      </div>

      <!-- Certificates Grid -->
      <div id="certifications-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Dynamically populated via app.js -->
      </div>
    </section>

    <!-- 9. MARI BERKOLABORASI BERSAMA (CONTACT SECTION) -->
    <section id="kontak" class="pt-8">
      <div class="relative bg-blue-600 rounded-3xl neo-border p-6 sm:p-10 text-white shadow-[6px_6px_0px_#111827] overflow-hidden">
        <!-- Background Decorative Stars -->
        <div class="absolute top-4 right-6 text-yellow-300 text-3xl opacity-80 select-none">✦</div>
        <div class="absolute bottom-6 left-6 text-yellow-300 text-2xl opacity-60 select-none">★</div>

        <div class="relative max-w-2xl mx-auto text-center space-y-4">
          <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-blue-800 text-blue-200 text-xs font-bold uppercase tracking-wide neo-border">
            <span>🤝 MARI TERHUBUNG</span>
          </div>

          <h2 id="contact-title" class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
            Mari Berkolaborasi Bersama.
          </h2>

          <p id="contact-subtitle" class="text-blue-100 text-sm sm:text-base max-w-lg mx-auto">
            Punya ide menarik atau tawaran riset & proyek digital? Jangan ragu untuk menyapa!
          </p>

          <!-- Direct Buttons -->
          <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <a id="contact-wa-link" href="#" target="_blank" class="neo-btn bg-yellow-300 text-slate-900 px-5 py-2.5 rounded-xl hover:bg-yellow-400 text-sm">
              <i data-lucide="message-circle" class="w-4 h-4 text-emerald-700"></i>
              <span>Hubungi via WhatsApp</span>
            </a>
            <a id="contact-email-link" href="#" class="neo-btn bg-white text-slate-900 px-5 py-2.5 rounded-xl hover:bg-slate-100 text-sm">
              <i data-lucide="mail" class="w-4 h-4 text-blue-600"></i>
              <span id="contact-email-text">Kirim Email</span>
            </a>
          </div>

          <!-- Quick Message Box to WhatsApp -->
          <div class="mt-8 bg-white text-slate-900 rounded-2xl neo-border p-5 text-left shadow-[4px_4px_0px_#111827]">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
              <i data-lucide="send" class="w-3.5 h-3.5 text-blue-600"></i> Kirim Pesan Cepat ke WhatsApp
            </h3>

            <form id="form-quick-wa" class="space-y-3">
              <div>
                <label for="input-sender-name" class="block text-xs font-bold text-slate-700 mb-1">Nama Anda</label>
                <input id="input-sender-name" type="text" required placeholder="Contoh: Budi Pratama / PT Inovasi" class="w-full px-3 py-2 rounded-xl border-2 border-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>

              <div>
                <label for="input-sender-message" class="block text-xs font-bold text-slate-700 mb-1">Pesan atau Tawaran Proyek</label>
                <textarea id="input-sender-message" rows="3" required placeholder="Ketikkan gambaran singkat proyek atau pesan Anda..." class="w-full px-3 py-2 rounded-xl border-2 border-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
              </div>

              <button type="submit" class="w-full neo-btn bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl text-sm font-bold shadow-[2px_2px_0px_#111827]">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Kirim Langsung ke WhatsApp</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- ==================== FOOTER ==================== -->
  <footer class="mt-20 border-t-2 border-slate-900 bg-white py-10 px-4">
    <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">
      <div class="flex items-center gap-3">
        <span id="footer-initials" class="w-8 h-8 rounded-lg bg-blue-600 text-white font-extrabold flex items-center justify-center neo-border text-xs">
          HA
        </span>
        <div>
          <div id="footer-name" class="font-bold text-slate-900 text-sm">Haris Ardian</div>
          <div id="footer-role" class="text-xs text-slate-500">Portfolio Digital</div>
        </div>
      </div>

      <!-- Social Links -->
      <div class="flex items-center gap-3">
        <a id="footer-social-linkedin" href="#" target="_blank" class="w-8 h-8 rounded-lg neo-card flex items-center justify-center text-slate-700 hover:text-blue-600 hover:bg-slate-50 transition-colors" title="LinkedIn">
          <i data-lucide="linkedin" class="w-4 h-4"></i>
        </a>
        <a id="footer-social-github" href="#" target="_blank" class="w-8 h-8 rounded-lg neo-card flex items-center justify-center text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-colors" title="GitHub">
          <i data-lucide="github" class="w-4 h-4"></i>
        </a>
        <a id="footer-social-instagram" href="#" target="_blank" class="w-8 h-8 rounded-lg neo-card flex items-center justify-center text-slate-700 hover:text-rose-600 hover:bg-slate-50 transition-colors" title="Instagram">
          <i data-lucide="instagram" class="w-4 h-4"></i>
        </a>
      </div>

      <div class="text-xs text-slate-500 text-center sm:text-right">
        <span>© 2026. Hak Cipta Dilindungi.</span>
      </div>
    </div>
  </footer>

  <!-- ==================== IMAGE LIGHTBOX MODAL ==================== -->
  <div id="lightbox-modal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="relative bg-white neo-card max-w-3xl w-full p-4 max-h-[90vh] overflow-y-auto animate-fade-in">
      <button id="btn-close-lightbox" class="absolute top-3 right-3 neo-btn bg-slate-100 hover:bg-slate-200 w-8 h-8 rounded-lg text-slate-900">
        ✕
      </button>
      <div class="mt-6 space-y-3">
        <img id="lightbox-image" src="" alt="Pratinjau" class="w-full max-h-[60vh] object-contain rounded-xl neo-border bg-slate-100">
        <h4 id="lightbox-title" class="font-bold text-lg text-slate-900"></h4>
        <p id="lightbox-desc" class="text-sm text-slate-600"></p>
      </div>
    </div>
  </div>

  <!-- ==================== LIVE VISUAL EDITOR (CMS DRAWER/MODAL) ==================== -->
  <div id="editor-modal" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 hidden flex justify-end">
    <div class="bg-white w-full max-w-2xl h-full shadow-2xl flex flex-col border-l-2 border-slate-900 animate-slide-up">
      <!-- Editor Header -->
      <div class="p-4 border-b-2 border-slate-900 flex items-center justify-between bg-yellow-100">
        <div class="flex items-center gap-2">
          <i data-lucide="edit-3" class="w-5 h-5 text-slate-900"></i>
          <h3 class="font-extrabold text-base text-slate-900">Mode Edit Portofolio (Visual Editor)</h3>
        </div>
        <button id="btn-close-editor" class="neo-btn bg-white hover:bg-slate-100 w-8 h-8 rounded-lg text-sm">
          ✕
        </button>
      </div>

      <!-- Editor Tabs -->
      <div class="flex border-b-2 border-slate-900 bg-slate-50 overflow-x-auto text-xs font-bold text-slate-700">
        <button class="tab-btn px-4 py-2.5 border-r-2 border-slate-900 bg-white text-blue-600" data-tab="tab-profile">Profil</button>
        <button class="tab-btn px-4 py-2.5 border-r-2 border-slate-900 hover:bg-white" data-tab="tab-about">Tentang</button>
        <button class="tab-btn px-4 py-2.5 border-r-2 border-slate-900 hover:bg-white" data-tab="tab-experience">Pengalaman</button>
        <button class="tab-btn px-4 py-2.5 border-r-2 border-slate-900 hover:bg-white" data-tab="tab-education">Pendidikan</button>
        <button class="tab-btn px-4 py-2.5 border-r-2 border-slate-900 hover:bg-white" data-tab="tab-certs">Sertifikat</button>
        <button class="tab-btn px-4 py-2.5 hover:bg-white" data-tab="tab-export">Ekspor / Simpan</button>
      </div>

      <!-- Editor Form Content Area -->
      <div class="flex-1 overflow-y-auto p-5 space-y-5 text-sm">
        
        <!-- Tab 1: Profil -->
        <div id="tab-profile" class="tab-content space-y-4">
          <h4 class="font-bold text-slate-900 text-sm border-b pb-1">Informasi Personal Utama</h4>
          <div>
            <label class="block text-xs font-bold mb-1">Nama Lengkap</label>
            <input id="edit-profile-name" type="text" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">Inisial (Maks 2 Karakter)</label>
            <input id="edit-profile-initials" type="text" maxlength="3" class="w-24 px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">Role / Profesi Singkat</label>
            <input id="edit-profile-role" type="text" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">Badge Status (Paling Atas)</label>
            <input id="edit-profile-badge" type="text" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">Bio Ringkas</label>
            <textarea id="edit-profile-bio" rows="3" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm"></textarea>
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">URL Foto Profil (Link Gambar)</label>
            <input id="edit-profile-photo" type="text" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">Lokasi</label>
            <input id="edit-profile-location" type="text" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold mb-1">Nomor WhatsApp (628...)</label>
              <input id="edit-profile-whatsapp" type="text" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
            </div>
            <div>
              <label class="block text-xs font-bold mb-1">Email</label>
              <input id="edit-profile-email" type="email" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
            </div>
          </div>
        </div>

        <!-- Tab 2: Tentang -->
        <div id="tab-about" class="tab-content hidden space-y-4">
          <h4 class="font-bold text-slate-900 text-sm border-b pb-1">Deskripsi Tentang Saya</h4>
          <div>
            <label class="block text-xs font-bold mb-1">Headline Utama</label>
            <input id="edit-about-headline" type="text" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm">
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">Paragraf 1</label>
            <textarea id="edit-about-desc1" rows="3" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm"></textarea>
          </div>
          <div>
            <label class="block text-xs font-bold mb-1">Paragraf 2</label>
            <textarea id="edit-about-desc2" rows="3" class="w-full px-3 py-2 rounded-lg border-2 border-slate-900 text-sm"></textarea>
          </div>
        </div>

        <!-- Tab 3: Pengalaman -->
        <div id="tab-experience" class="tab-content hidden space-y-4">
          <div class="flex items-center justify-between border-b pb-1">
            <h4 class="font-bold text-slate-900 text-sm">Daftar Pengalaman Kerja & Organisasi</h4>
            <button id="btn-add-experience" class="neo-btn bg-emerald-300 text-slate-900 text-xs px-2.5 py-1 rounded-md">
              + Tambah Pengalaman
            </button>
          </div>
          <div id="edit-experience-list" class="space-y-4">
            <!-- Dynamically populated -->
          </div>
        </div>

        <!-- Tab 4: Pendidikan -->
        <div id="tab-education" class="tab-content hidden space-y-4">
          <div class="flex items-center justify-between border-b pb-1">
            <h4 class="font-bold text-slate-900 text-sm">Riwayat Pendidikan</h4>
            <button id="btn-add-education" class="neo-btn bg-emerald-300 text-slate-900 text-xs px-2.5 py-1 rounded-md">
              + Tambah Pendidikan
            </button>
          </div>
          <div id="edit-education-list" class="space-y-4">
            <!-- Dynamically populated -->
          </div>
        </div>

        <!-- Tab 5: Sertifikat -->
        <div id="tab-certs" class="tab-content hidden space-y-4">
          <div class="flex items-center justify-between border-b pb-1">
            <h4 class="font-bold text-slate-900 text-sm">Daftar Sertifikasi</h4>
            <button id="btn-add-cert" class="neo-btn bg-emerald-300 text-slate-900 text-xs px-2.5 py-1 rounded-md">
              + Tambah Sertifikat
            </button>
          </div>
          <div id="edit-certs-list" class="space-y-4">
            <!-- Dynamically populated -->
          </div>
        </div>

        <!-- Tab 6: Ekspor / Simpan -->
        <div id="tab-export" class="tab-content hidden space-y-5">
          <div class="p-4 bg-blue-50 border-2 border-blue-600 rounded-xl space-y-2 text-blue-900">
            <div class="font-extrabold flex items-center gap-1.5">
              <i data-lucide="info" class="w-4 h-4"></i> Cara Menyimpan Perubahan
            </div>
            <p class="text-xs leading-relaxed">
              Perubahan Anda otomatis tersimpan di memori browser ini (localStorage). Namun agar perubahan Anda menjadi permanen saat dideploy ke internet, silakan klik tombol <b>Unduh File Data (portfolio-data.js)</b> di bawah, lalu ganti file <code>portfolio-data.js</code> lama di folder proyek Anda dengan file yang baru diunduh.
            </p>
          </div>

          <div class="space-y-3">
            <button id="btn-download-data" class="w-full neo-btn bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl text-sm font-bold shadow-[3px_3px_0px_#111827]">
              <i data-lucide="download" class="w-4 h-4"></i>
              <span>Unduh File Data Baru (portfolio-data.js)</span>
            </button>

            <button id="btn-reset-default" class="w-full neo-btn bg-white hover:bg-rose-50 text-rose-600 py-2.5 rounded-xl text-xs font-bold border-rose-600">
              <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
              <span>Reset Kembali ke Data Bawaan</span>
            </button>
          </div>
        </div>

      </div>

      <!-- Editor Footer Action Bar -->
      <div class="p-4 border-t-2 border-slate-900 bg-white flex items-center justify-between">
        <span id="editor-save-indicator" class="text-xs text-slate-500 font-medium">Perubahan langsung diterapkan</span>
        <button id="btn-apply-editor" class="neo-btn bg-emerald-400 hover:bg-emerald-500 text-slate-900 px-5 py-2 rounded-xl text-sm font-bold">
          <i data-lucide="check" class="w-4 h-4"></i>
          <span>Terapkan & Tutup</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Scripts via Laravel Asset Helper -->
  <script src="{{ asset('js/portfolio-data.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
