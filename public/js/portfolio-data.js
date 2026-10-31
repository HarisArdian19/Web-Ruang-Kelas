/**
 * =========================================================================
 * KONFIGURASI DATA PORTOFOLIO
 * =========================================================================
 * Anda dapat mengedit semua data teks, foto, kontak, pengalaman, dll. 
 * langsung di file ini ATAU melalui tombol "Mode Edit" di website!
 */

const defaultPortfolioData = {
  // --- PROFIL UTAMA ---
  profile: {
    name: "Haris Ardian",
    initials: "HA",
    badgeStatus: "TERBUKA UNTUK RISET, PROYEK & KOLABORASI DIGITAL",
    greeting: "Halo, Saya",
    role: "Digital Specialist & Technology Enthusiast",
    bio: "Menghubungkan potensi inovasi dan strategi digital modern demi terciptanya solusi yang berdampak, berkelanjutan, dan berdaya saing tinggi.",
    photo: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=80",
    location: "Yogyakarta, Indonesia",
    whatsappNumber: "6281234567890", // Ganti dengan nomor WhatsApp Anda (format internasional tanpa tanda +)
    whatsappText: "Halo Haris, saya melihat portofolio Anda dan tertarik untuk berdiskusi/berkolaborasi.",
    email: "haris.ardian@example.com",
    cvLink: "#",
    socials: {
      linkedin: "https://linkedin.com",
      github: "https://github.com",
      instagram: "https://instagram.com"
    }
  },

  // --- TENTANG SAYA & STATS ---
  about: {
    headline: "Pengembang Solusi Digital & Inovator Berbasis Data",
    description1: "Saya berfokus pada pengembangan sistem, analisis data, dan strategi inovasi digital yang mampu menyelesaikan permasalahan nyata di lapangan secara efisien dan terukur.",
    description2: "Terbiasa bekerja secara kolaboratif lintas disiplin, mengelola proyek end-to-end, serta menyampaikan ide kompleks menjadi solusi yang mudah dipahami oleh berbagai pemangku kepentingan.",
    stats: [
      { id: 1, value: "12+", label: "Proyek & Riset Selesai", color: "bg-amber-100" },
      { id: 2, value: "22 Thn", label: "Usia / Dinamis", color: "bg-rose-100" },
      { id: 3, value: "1.5K+", label: "Jaringan & Relasi", color: "bg-blue-100" },
      { id: 4, value: "Aktif", label: "Status Ketersediaan", color: "bg-emerald-100" }
    ]
  },

  // --- GALERI KEGIATAN & RISET ---
  gallery: [
    {
      id: 1,
      title: "Riset Lapangan & Observasi Pengguna",
      category: "Riset & Analisis",
      image: "https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80",
      description: "Dokumentasi wawancara dan observasi langsung bersama stakeholder untuk memetakan kebutuhan sistem."
    },
    {
      id: 2,
      title: "Presentasi Solusi & Diskusi Strategis",
      category: "Presentasi Bisnis",
      image: "https://images.unsplash.com/photo-1531497865144-0464ef8fb9a9?auto=format&fit=crop&w=800&q=80",
      description: "Penyampaian hasil analisis data dan rekomendasi implementasi di hadapan panel penguji dan mitra."
    },
    {
      id: 3,
      title: "Implementasi & Pengujian di Lapangan",
      category: "Implementasi Proyek",
      image: "https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80",
      description: "Uji coba langsung prototipe solusi bersama komunitas pengguna dengan evaluasi terukur."
    }
  ],

  // --- PENGALAMAN PROFESIONAL & ORGANISASI ---
  experiences: [
    {
      id: 1,
      role: "Digital & Market Research Intern",
      organization: "PT Mitra Inovasi Nusantara",
      period: "Feb 2024 - Agu 2024",
      location: "Yogyakarta, Indonesia",
      bullets: [
        "Melakukan analisis tren pasar dan sentimen konsumen digital pada 5+ sektor komoditas utama.",
        "Menyusun laporan rekomendasi berkala yang digunakan oleh pimpinan sebagai acuan strategi distribusi.",
        "Menginisiasi dashboard pemantauan performa proyek berbasis web yang meningkatkan efisiensi reporting hingga 35%."
      ],
      tags: ["Market Research", "Data Analytics", "Strategy", "Reporting"]
    },
    {
      id: 2,
      role: "Project Leader - Program Pemberdayaan",
      organization: "Program Pengabdian Komunitas",
      period: "Jul 2023 - Sep 2023",
      location: "Sleman, D.I. Yogyakarta",
      bullets: [
        "Memimpin tim beranggotakan 15 orang dalam digitalisasi pencatatan operasional UMKM lokal.",
        "Menyelenggarakan lokakarya literasi digital yang dihadiri oleh lebih dari 50 pelaku usaha setempat.",
        "Meningkatkan adopsi pencatatan keuangan digital peserta sebesar 65% dalam kurun waktu 2 bulan."
      ],
      tags: ["Leadership", "Community Development", "Digital Literacy", "Event Management"]
    },
    {
      id: 3,
      role: "Staff Departemen Komunikasi & Publikasi",
      organization: "Himpunan Mahasiswa",
      period: "Jan 2023 - Des 2023",
      location: "Universitas",
      bullets: [
        "Mengelola publikasi konten media sosial resmi dengan total engagement meningkat sebesar 40%.",
        "Bertanggung jawab atas desain visual materi promosi kegiatan nasional dan dokumentasi berkala.",
        "Mengkoordinasikan tim liputan dan live report selama rangkaian acara dies natalis tahunan."
      ],
      tags: ["Content Strategy", "Graphic Design", "Social Media", "Public Relations"]
    }
  ],

  // --- LATAR BELAKANG PENDIDIKAN ---
  education: [
    {
      id: 1,
      institution: "Universitas Gadjah Mada",
      major: "Sarjana (S1) - Bidang Studi Pilihan",
      period: "2021 - Sekarang",
      description: "Fokus pada integrasi teknologi informasi, analisis kuantitatif, dan metodologi riset terapan. Aktif dalam organisasi kemahasiswaan dan kompetisi riset."
    },
    {
      id: 2,
      institution: "SMAN 01 Yogyakarta",
      major: "Jurusan MIPA",
      period: "2018 - 2021",
      description: "Lulus dengan predikat memuaskan. Aktif dalam kegiatan ekstrakurikuler sains terapan dan kepemimpinan OSIS."
    }
  ],

  // --- KEAHLIAN & TOOLS ---
  skillCategories: [
    {
      id: 1,
      category: "Riset & Analisis Data",
      skills: ["Data Analysis", "Survei & Kuisioner", "Excel & Spreadsheet Lanjutan", "Visualisasi Data", "Analisis Tren"]
    },
    {
      id: 2,
      category: "Teknologi & Digital Tools",
      skills: ["Web Development (HTML/CSS/JS)", "Git & GitHub", "Workflow Automation", "API Integration", "Database Basics"]
    },
    {
      id: 3,
      category: "Desain Grafis & Presentasi",
      skills: ["Canva Pro", "Figma", "Business Pitch Deck", "Infografis", "Desain Identitas Visual"]
    },
    {
      id: 4,
      category: "Komunikasi & Manajemen Proyek",
      skills: ["Public Speaking", "Negosiasi", "Project Management", "Team Leadership", "Problem Solving"]
    }
  ],

  // --- KEMAMPUAN BAHASA ---
  languages: [
    {
      id: 1,
      language: "Bahasa Indonesia",
      level: "Penutur Asli (Native Speaker)",
      badge: "Lancar / Fasih"
    },
    {
      id: 2,
      language: "Bahasa Inggris",
      level: "Kemampuan Kerja Profesional (TOEFL / Professional Working)",
      badge: "Tingkat Lanjut"
    }
  ],

  // --- SERTIFIKASI PROFESIONAL ---
  certifications: [
    {
      id: 1,
      title: "Data Analytics & Research Methodology",
      issuer: "Lembaga Sertifikasi Nasional",
      year: "2024",
      image: "https://images.unsplash.com/photo-1589330694653-dad6d3240a91?auto=format&fit=crop&w=600&q=80",
      credentialUrl: "https://example.com/verify/12345"
    },
    {
      id: 2,
      title: "Project Management Foundations & Leadership",
      issuer: "Global Education Institute",
      year: "2023",
      image: "https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?auto=format&fit=crop&w=600&q=80",
      credentialUrl: "https://example.com/verify/67890"
    },
    {
      id: 3,
      title: "Digital Strategy & Creative Presentation",
      issuer: "Academy of Creative Professional",
      year: "2023",
      image: "https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=600&q=80",
      credentialUrl: "https://example.com/verify/54321"
    }
  ],

  // --- KONTAK & PESAN CEPAT ---
  contact: {
    title: "Mari Berkolaborasi Bersama.",
    subtitle: "Punya ide menarik, riset, atau tawaran proyek yang ingin dikerjakan bersama? Jangan ragu untuk menyapa!",
    quickMessageDefault: "Halo Haris, saya tertarik dengan portofolio Anda dan ingin mendiskusikan peluang kolaborasi."
  }
};
