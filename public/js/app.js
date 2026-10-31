// State portofolio (load dari localStorage jika ada, atau gunakan default)
const STORAGE_KEY = 'user_portfolio_data_v1';
let currentData = loadPortfolioData();

function loadPortfolioData() {
  try {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved) {
      return JSON.parse(saved);
    }
  } catch (e) {
    console.error('Gagal membaca data dari localStorage', e);
  }
  return JSON.parse(JSON.stringify(defaultPortfolioData));
}

function savePortfolioData(data) {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
  } catch (e) {
    console.error('Gagal menyimpan data ke localStorage', e);
  }
}

// ==========================================
// RENDER SELURUH HALAMAN PORTOFOLIO
// ==========================================
function renderPortfolio(data) {
  // Page Title & Nav
  document.getElementById('page-title').textContent = `Portofolio Profesional - ${data.profile.name}`;
  document.getElementById('nav-name').textContent = data.profile.name;
  document.getElementById('nav-initials').textContent = data.profile.initials || data.profile.name.substring(0, 2).toUpperCase();

  // Hero Section
  document.getElementById('hero-badge-status').textContent = data.profile.badgeStatus;
  document.getElementById('hero-greeting').innerHTML = `<span>👋 ${data.profile.greeting}</span>`;
  document.getElementById('hero-name').textContent = data.profile.name;
  document.getElementById('hero-role').textContent = data.profile.role;
  document.getElementById('hero-bio').textContent = data.profile.bio;
  document.getElementById('hero-location').textContent = data.profile.location;
  document.getElementById('hero-email-text').textContent = data.profile.email;

  const heroPhotoEl = document.getElementById('hero-photo');
  if (heroPhotoEl && data.profile.photo) {
    heroPhotoEl.src = data.profile.photo;
  }

  // Hero Buttons
  const cleanPhone = (data.profile.whatsappNumber || '').replace(/[^0-9]/g, '');
  const waUrl = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(data.profile.whatsappText || 'Halo, saya tertarik dengan portofolio Anda.')}`;
  document.getElementById('hero-wa-btn').href = waUrl;

  // Tentang Saya
  document.getElementById('about-headline').textContent = data.about.headline;
  document.getElementById('about-desc-1').textContent = data.about.description1;
  document.getElementById('about-desc-2').textContent = data.about.description2;

  // Stats Cards
  const statsContainer = document.getElementById('stats-container');
  if (statsContainer && data.about.stats) {
    statsContainer.innerHTML = data.about.stats.map(stat => `
      <div class="neo-card p-4 flex flex-col justify-between ${stat.color || 'bg-white'}">
        <div class="text-2xl sm:text-3xl font-black text-slate-900">${stat.value}</div>
        <div class="text-xs font-bold text-slate-600 mt-2 leading-tight">${stat.label}</div>
      </div>
    `).join('');
  }

  // Galeri Kegiatan
  const galleryContainer = document.getElementById('gallery-container');
  if (galleryContainer && data.gallery) {
    galleryContainer.innerHTML = data.gallery.map(item => `
      <div class="neo-card neo-card-hover overflow-hidden flex flex-col group cursor-pointer" onclick="openLightbox('${item.image}', '${escapeHtml(item.title)}', '${escapeHtml(item.description)}')">
        <div class="relative overflow-hidden aspect-[16/11] bg-slate-100 border-b-2 border-slate-900">
          <img src="${item.image}" alt="${item.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
          <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-white/95 neo-border text-[11px] font-bold text-slate-800">
            ${item.category}
          </span>
        </div>
        <div class="p-4 flex-1 flex flex-col justify-between">
          <div>
            <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-blue-600 transition-colors">${item.title}</h3>
            <p class="text-xs text-slate-600 mt-2 line-clamp-2">${item.description}</p>
          </div>
          <div class="mt-3 pt-2 flex items-center justify-between text-xs font-bold text-blue-600">
            <span>Perbesar Foto</span>
            <span>↗</span>
          </div>
        </div>
      </div>
    `).join('');
  }

  // Pengalaman Kerja & Organisasi
  const expContainer = document.getElementById('experience-container');
  if (expContainer && data.experiences) {
    expContainer.innerHTML = data.experiences.map(exp => `
      <div class="neo-card p-5 sm:p-6 neo-card-hover">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b-2 border-slate-100 pb-3">
          <div>
            <h3 class="font-black text-lg text-slate-900">${exp.role}</h3>
            <p class="text-sm font-bold text-blue-600">${exp.organization}</p>
          </div>
          <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
            <span class="px-2.5 py-1 rounded-lg bg-yellow-100 neo-border text-slate-800">${exp.period}</span>
            <span class="px-2.5 py-1 rounded-lg bg-slate-100 neo-border text-slate-600">📍 ${exp.location}</span>
          </div>
        </div>
        <ul class="mt-4 space-y-2 text-sm text-slate-600">
          ${exp.bullets.map(b => `
            <li class="flex items-start gap-2.5">
              <span class="text-blue-600 font-bold">✔</span>
              <span>${b}</span>
            </li>
          `).join('')}
        </ul>
        <div class="mt-4 pt-3 flex flex-wrap gap-1.5 border-t border-slate-100">
          ${(exp.tags || []).map(tag => `
            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[11px] font-semibold text-slate-700">#${tag}</span>
          `).join('')}
        </div>
      </div>
    `).join('');
  }

  // Pendidikan
  const eduContainer = document.getElementById('education-container');
  if (eduContainer && data.education) {
    eduContainer.innerHTML = data.education.map(edu => `
      <div class="neo-card p-5 neo-card-hover flex flex-col justify-between">
        <div>
          <div class="inline-block px-2.5 py-0.5 rounded-md bg-emerald-100 neo-border text-[11px] font-bold text-emerald-900 mb-2">
            ${edu.period}
          </div>
          <h3 class="font-black text-base text-slate-900 leading-snug">${edu.institution}</h3>
          <p class="text-sm font-bold text-blue-600 mt-0.5">${edu.major}</p>
          <p class="text-xs text-slate-600 mt-2 leading-relaxed">${edu.description}</p>
        </div>
      </div>
    `).join('');
  }

  // Keahlian
  const skillsContainer = document.getElementById('skills-container');
  if (skillsContainer && data.skillCategories) {
    skillsContainer.innerHTML = data.skillCategories.map(cat => `
      <div class="neo-card p-5">
        <h3 class="font-extrabold text-sm text-slate-900 mb-3 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
          <span>${cat.category}</span>
        </h3>
        <div class="flex flex-wrap gap-2">
          ${cat.skills.map(s => `
            <span class="px-3 py-1 rounded-xl bg-white neo-border text-xs font-bold text-slate-800 shadow-[1.5px_1.5px_0px_#111827]">
              ${s}
            </span>
          `).join('')}
        </div>
      </div>
    `).join('');
  }

  // Kemampuan Bahasa
  const langContainer = document.getElementById('languages-container');
  if (langContainer && data.languages) {
    langContainer.innerHTML = data.languages.map(lang => `
      <div class="neo-card p-4 flex items-center justify-between">
        <div>
          <h4 class="font-extrabold text-sm text-slate-900">${lang.language}</h4>
          <p class="text-xs text-slate-500">${lang.level}</p>
        </div>
        <span class="px-2.5 py-1 rounded-lg bg-sky-100 neo-border text-xs font-bold text-sky-900">
          ${lang.badge}
        </span>
      </div>
    `).join('');
  }

  // Sertifikasi
  const certContainer = document.getElementById('certifications-container');
  if (certContainer && data.certifications) {
    certContainer.innerHTML = data.certifications.map(cert => `
      <div class="neo-card neo-card-hover overflow-hidden flex flex-col">
        <div class="relative aspect-[16/10] bg-slate-100 border-b-2 border-slate-900 overflow-hidden cursor-pointer" onclick="openLightbox('${cert.image}', '${escapeHtml(cert.title)}', 'Penerbit: ${escapeHtml(cert.issuer)} (${cert.year})')">
          <img src="${cert.image}" alt="${cert.title}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
          <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-md bg-yellow-300 neo-border text-[10px] font-extrabold text-slate-900">
            ${cert.year}
          </span>
        </div>
        <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
          <div>
            <h3 class="font-bold text-sm text-slate-900 leading-snug">${cert.title}</h3>
            <p class="text-xs text-slate-500 mt-1">${cert.issuer}</p>
          </div>
          <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
            <button onclick="openLightbox('${cert.image}', '${escapeHtml(cert.title)}', 'Penerbit: ${escapeHtml(cert.issuer)}')" class="flex-1 neo-btn bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs py-1.5 rounded-lg font-bold">
              Lihat Sertifikat
            </button>
            ${cert.credentialUrl && cert.credentialUrl !== '#' ? `
              <a href="${cert.credentialUrl}" target="_blank" class="neo-btn bg-blue-600 hover:bg-blue-700 text-white text-xs px-2.5 py-1.5 rounded-lg" title="Verifikasi Kredensial">
                ↗
              </a>
            ` : ''}
          </div>
        </div>
      </div>
    `).join('');
  }

  // Kontak
  document.getElementById('contact-title').textContent = data.contact.title;
  document.getElementById('contact-subtitle').textContent = data.contact.subtitle;
  document.getElementById('contact-wa-link').href = waUrl;
  document.getElementById('contact-email-link').href = `mailto:${data.profile.email}`;
  document.getElementById('contact-email-text').textContent = `Email (${data.profile.email})`;

  // Footer
  document.getElementById('footer-name').textContent = data.profile.name;
  document.getElementById('footer-initials').textContent = data.profile.initials || 'HA';
  document.getElementById('footer-role').textContent = data.profile.role;

  if (data.profile.socials) {
    if (document.getElementById('footer-social-linkedin')) {
      document.getElementById('footer-social-linkedin').href = data.profile.socials.linkedin || '#';
    }
    if (document.getElementById('footer-social-github')) {
      document.getElementById('footer-social-github').href = data.profile.socials.github || '#';
    }
    if (document.getElementById('footer-social-instagram')) {
      document.getElementById('footer-social-instagram').href = data.profile.socials.instagram || '#';
    }
  }

  // Inisialisasi ikon Lucide
  if (window.lucide) {
    lucide.createIcons();
  }
}

// ==========================================
// UTILITY FUNCTIONS
// ==========================================
function escapeHtml(text) {
  if (!text) return '';
  return text.replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

// Copy email ke clipboard
const emailChip = document.getElementById('hero-email-chip');
if (emailChip) {
  emailChip.addEventListener('click', () => {
    const email = currentData.profile.email;
    navigator.clipboard.writeText(email).then(() => {
      const toast = document.getElementById('copy-toast');
      toast.classList.remove('opacity-0');
      setTimeout(() => {
        toast.classList.add('opacity-0');
      }, 2000);
    });
  });
}

// Quick message to WhatsApp
const quickWaForm = document.getElementById('form-quick-wa');
if (quickWaForm) {
  quickWaForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const senderName = document.getElementById('input-sender-name').value.trim();
    const senderMessage = document.getElementById('input-sender-message').value.trim();

    const cleanPhone = (currentData.profile.whatsappNumber || '').replace(/[^0-9]/g, '');
    const fullText = `Halo ${currentData.profile.name},\nSaya: *${senderName}*\n\nPesan / Tawaran Proyek:\n${senderMessage}`;
    const targetUrl = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(fullText)}`;
    
    window.open(targetUrl, '_blank');
  });
}

// Navigasi Dropdown Menu Toggle
const btnNavDropdown = document.getElementById('btn-nav-dropdown');
const navDropdownMenu = document.getElementById('nav-dropdown-menu');

if (btnNavDropdown && navDropdownMenu) {
  btnNavDropdown.addEventListener('click', (e) => {
    e.stopPropagation();
    navDropdownMenu.classList.toggle('hidden');
  });

  document.addEventListener('click', () => {
    navDropdownMenu.classList.add('hidden');
  });

  navDropdownMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      navDropdownMenu.classList.add('hidden');
    });
  });
}

// Lightbox Modal
const lightboxModal = document.getElementById('lightbox-modal');
const lightboxImage = document.getElementById('lightbox-image');
const lightboxTitle = document.getElementById('lightbox-title');
const lightboxDesc = document.getElementById('lightbox-desc');
const btnCloseLightbox = document.getElementById('btn-close-lightbox');

function openLightbox(src, title, desc) {
  if (!lightboxModal) return;
  lightboxImage.src = src;
  lightboxTitle.textContent = title;
  lightboxDesc.textContent = desc;
  lightboxModal.classList.remove('hidden');
}

if (btnCloseLightbox) {
  btnCloseLightbox.addEventListener('click', () => {
    lightboxModal.classList.add('hidden');
  });
}

if (lightboxModal) {
  lightboxModal.addEventListener('click', (e) => {
    if (e.target === lightboxModal) {
      lightboxModal.classList.add('hidden');
    }
  });
}

// ==========================================
// LIVE VISUAL EDITOR (CMS LOGIC)
// ==========================================
const editorModal = document.getElementById('editor-modal');
const btnOpenEditor = document.getElementById('btn-open-editor');
const btnCloseEditor = document.getElementById('btn-close-editor');
const btnApplyEditor = document.getElementById('btn-apply-editor');
const btnDownloadData = document.getElementById('btn-download-data');
const btnResetDefault = document.getElementById('btn-reset-default');

// Tab Switching di Editor
const tabButtons = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    const targetId = btn.getAttribute('data-tab');
    
    tabButtons.forEach(b => {
      b.classList.remove('bg-white', 'text-blue-600');
      b.classList.add('hover:bg-white');
    });
    btn.classList.add('bg-white', 'text-blue-600');
    btn.classList.remove('hover:bg-white');

    tabContents.forEach(content => {
      content.classList.add('hidden');
    });
    const targetContent = document.getElementById(targetId);
    if (targetContent) {
      targetContent.classList.remove('hidden');
    }
  });
});

// Buka Editor
if (btnOpenEditor) {
  btnOpenEditor.addEventListener('click', () => {
    populateEditorForms();
    editorModal.classList.remove('hidden');
  });
}

// Tutup Editor
if (btnCloseEditor) {
  btnCloseEditor.addEventListener('click', () => {
    editorModal.classList.add('hidden');
  });
}

if (btnApplyEditor) {
  btnApplyEditor.addEventListener('click', () => {
    collectEditorData();
    savePortfolioData(currentData);
    renderPortfolio(currentData);
    editorModal.classList.add('hidden');
  });
}

// Isi form editor dengan data terkini
function populateEditorForms() {
  // Profil
  document.getElementById('edit-profile-name').value = currentData.profile.name || '';
  document.getElementById('edit-profile-initials').value = currentData.profile.initials || '';
  document.getElementById('edit-profile-role').value = currentData.profile.role || '';
  document.getElementById('edit-profile-badge').value = currentData.profile.badgeStatus || '';
  document.getElementById('edit-profile-bio').value = currentData.profile.bio || '';
  document.getElementById('edit-profile-photo').value = currentData.profile.photo || '';
  document.getElementById('edit-profile-location').value = currentData.profile.location || '';
  document.getElementById('edit-profile-whatsapp').value = currentData.profile.whatsappNumber || '';
  document.getElementById('edit-profile-email').value = currentData.profile.email || '';

  // Tentang
  document.getElementById('edit-about-headline').value = currentData.about.headline || '';
  document.getElementById('edit-about-desc1').value = currentData.about.description1 || '';
  document.getElementById('edit-about-desc2').value = currentData.about.description2 || '';

  // Pengalaman
  renderEditExperienceList();

  // Pendidikan
  renderEditEducationList();

  // Sertifikat
  renderEditCertList();
}

// Render daftar pengalaman di editor
function renderEditExperienceList() {
  const container = document.getElementById('edit-experience-list');
  container.innerHTML = currentData.experiences.map((exp, index) => `
    <div class="p-3 bg-slate-50 border-2 border-slate-300 rounded-xl space-y-2 relative">
      <button onclick="removeExperience(${index})" class="absolute top-2 right-2 text-rose-600 hover:text-rose-800 font-bold text-xs">
        ✕ Hapus
      </button>
      <div class="font-bold text-xs text-slate-500">Pengalaman #${index + 1}</div>
      <input type="text" placeholder="Jabatan / Role" value="${escapeHtml(exp.role)}" onchange="updateExpField(${index}, 'role', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      <input type="text" placeholder="Perusahaan / Organisasi" value="${escapeHtml(exp.organization)}" onchange="updateExpField(${index}, 'organization', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      <div class="grid grid-cols-2 gap-2">
        <input type="text" placeholder="Periode (2023 - 2024)" value="${escapeHtml(exp.period)}" onchange="updateExpField(${index}, 'period', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
        <input type="text" placeholder="Lokasi" value="${escapeHtml(exp.location)}" onchange="updateExpField(${index}, 'location', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      </div>
      <textarea placeholder="Poin tanggung jawab/pencapaian (pisahkan dengan baris baru)" rows="2" onchange="updateExpBullets(${index}, this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">${(exp.bullets || []).join('\n')}</textarea>
    </div>
  `).join('');
}

function updateExpField(index, field, value) {
  currentData.experiences[index][field] = value;
  savePortfolioData(currentData);
  renderPortfolio(currentData);
}

function updateExpBullets(index, value) {
  currentData.experiences[index].bullets = value.split('\n').filter(b => b.trim().length > 0);
  savePortfolioData(currentData);
  renderPortfolio(currentData);
}

function removeExperience(index) {
  currentData.experiences.splice(index, 1);
  savePortfolioData(currentData);
  renderPortfolio(currentData);
  renderEditExperienceList();
}

document.getElementById('btn-add-experience').addEventListener('click', () => {
  currentData.experiences.unshift({
    id: Date.now(),
    role: "Posisi / Pekerjaan Baru",
    organization: "Nama Perusahaan / Organisasi",
    period: "2024 - Sekarang",
    location: "Indonesia",
    bullets: ["Deskripsi tanggung jawab dan pencapaian Anda."],
    tags: ["Keahlian", "Proyek"]
  });
  savePortfolioData(currentData);
  renderPortfolio(currentData);
  renderEditExperienceList();
});

// Render pendidikan di editor
function renderEditEducationList() {
  const container = document.getElementById('edit-education-list');
  container.innerHTML = currentData.education.map((edu, index) => `
    <div class="p-3 bg-slate-50 border-2 border-slate-300 rounded-xl space-y-2 relative">
      <button onclick="removeEducation(${index})" class="absolute top-2 right-2 text-rose-600 hover:text-rose-800 font-bold text-xs">
        ✕ Hapus
      </button>
      <div class="font-bold text-xs text-slate-500">Pendidikan #${index + 1}</div>
      <input type="text" placeholder="Institusi" value="${escapeHtml(edu.institution)}" onchange="updateEduField(${index}, 'institution', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      <input type="text" placeholder="Jurusan / Jenjang" value="${escapeHtml(edu.major)}" onchange="updateEduField(${index}, 'major', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      <input type="text" placeholder="Periode (2020 - 2024)" value="${escapeHtml(edu.period)}" onchange="updateEduField(${index}, 'period', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      <textarea placeholder="Deskripsi singkat" rows="2" onchange="updateEduField(${index}, 'description', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">${escapeHtml(edu.description)}</textarea>
    </div>
  `).join('');
}

function updateEduField(index, field, value) {
  currentData.education[index][field] = value;
  savePortfolioData(currentData);
  renderPortfolio(currentData);
}

function removeEducation(index) {
  currentData.education.splice(index, 1);
  savePortfolioData(currentData);
  renderPortfolio(currentData);
  renderEditEducationList();
}

document.getElementById('btn-add-education').addEventListener('click', () => {
  currentData.education.unshift({
    id: Date.now(),
    institution: "Nama Universitas / Sekolah",
    major: "Program Studi / Jurusan",
    period: "2024",
    description: "Keterangan prestasi atau fokus studi."
  });
  savePortfolioData(currentData);
  renderPortfolio(currentData);
  renderEditEducationList();
});

// Render Sertifikat di editor
function renderEditCertList() {
  const container = document.getElementById('edit-certs-list');
  container.innerHTML = currentData.certifications.map((cert, index) => `
    <div class="p-3 bg-slate-50 border-2 border-slate-300 rounded-xl space-y-2 relative">
      <button onclick="removeCert(${index})" class="absolute top-2 right-2 text-rose-600 hover:text-rose-800 font-bold text-xs">
        ✕ Hapus
      </button>
      <div class="font-bold text-xs text-slate-500">Sertifikat #${index + 1}</div>
      <input type="text" placeholder="Nama Sertifikat" value="${escapeHtml(cert.title)}" onchange="updateCertField(${index}, 'title', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      <input type="text" placeholder="Penerbit" value="${escapeHtml(cert.issuer)}" onchange="updateCertField(${index}, 'issuer', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      <div class="grid grid-cols-2 gap-2">
        <input type="text" placeholder="Tahun" value="${escapeHtml(cert.year)}" onchange="updateCertField(${index}, 'year', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
        <input type="text" placeholder="Link Verifikasi (opsional)" value="${escapeHtml(cert.credentialUrl || '')}" onchange="updateCertField(${index}, 'credentialUrl', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
      </div>
      <input type="text" placeholder="URL Gambar Sertifikat" value="${escapeHtml(cert.image)}" onchange="updateCertField(${index}, 'image', this.value)" class="w-full px-2.5 py-1.5 border rounded text-xs">
    </div>
  `).join('');
}

function updateCertField(index, field, value) {
  currentData.certifications[index][field] = value;
  savePortfolioData(currentData);
  renderPortfolio(currentData);
}

function removeCert(index) {
  currentData.certifications.splice(index, 1);
  savePortfolioData(currentData);
  renderPortfolio(currentData);
  renderEditCertList();
}

document.getElementById('btn-add-cert').addEventListener('click', () => {
  currentData.certifications.unshift({
    id: Date.now(),
    title: "Sertifikat Baru",
    issuer: "Lembaga Penerbit",
    year: "2024",
    image: "https://images.unsplash.com/photo-1589330694653-dad6d3240a91?auto=format&fit=crop&w=600&q=80",
    credentialUrl: "#"
  });
  savePortfolioData(currentData);
  renderPortfolio(currentData);
  renderEditCertList();
});

// Kumpulkan data teks dari input utama
function collectEditorData() {
  currentData.profile.name = document.getElementById('edit-profile-name').value.trim();
  currentData.profile.initials = document.getElementById('edit-profile-initials').value.trim();
  currentData.profile.role = document.getElementById('edit-profile-role').value.trim();
  currentData.profile.badgeStatus = document.getElementById('edit-profile-badge').value.trim();
  currentData.profile.bio = document.getElementById('edit-profile-bio').value.trim();
  currentData.profile.photo = document.getElementById('edit-profile-photo').value.trim();
  currentData.profile.location = document.getElementById('edit-profile-location').value.trim();
  currentData.profile.whatsappNumber = document.getElementById('edit-profile-whatsapp').value.trim();
  currentData.profile.email = document.getElementById('edit-profile-email').value.trim();

  currentData.about.headline = document.getElementById('edit-about-headline').value.trim();
  currentData.about.description1 = document.getElementById('edit-about-desc1').value.trim();
  currentData.about.description2 = document.getElementById('edit-about-desc2').value.trim();
}

// Download file portfolio-data.js yang sudah terupdate
if (btnDownloadData) {
  btnDownloadData.addEventListener('click', () => {
    collectEditorData();
    savePortfolioData(currentData);

    const fileContent = `/**
 * =========================================================================
 * KONFIGURASI DATA PORTOFOLIO (HASIL GENERASI VISUAL EDITOR)
 * =========================================================================
 */

const defaultPortfolioData = ${JSON.stringify(currentData, null, 2)};
`;

    const blob = new Blob([fileContent], { type: 'text/javascript' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'portfolio-data.js';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  });
}

// Reset data ke default bawaan
if (btnResetDefault) {
  btnResetDefault.addEventListener('click', () => {
    if (confirm('Yakin ingin mereset seluruh data kembali ke bawaan awal?')) {
      localStorage.removeItem(STORAGE_KEY);
      currentData = JSON.parse(JSON.stringify(defaultPortfolioData));
      renderPortfolio(currentData);
      populateEditorForms();
      alert('Data telah berhasil direset ke pengaturan awal.');
    }
  });
}

// ==========================================
// INISIALISASI AWAL SAAT HALAMAN DIMUAT
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
  renderPortfolio(currentData);
});
