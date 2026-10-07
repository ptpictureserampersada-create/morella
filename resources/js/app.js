import Alpine from 'alpinejs';

function readSession(key, fallback) {
  try {
    return sessionStorage.getItem(key) || fallback;
  } catch (e) {
    return fallback;
  }
}

function writeSession(key, value) {
  try {
    sessionStorage.setItem(key, value);
  } catch (e) {
    /* ignore */
  }
}

// Port dari TourismContext (SPA): state bahasa & role global.
// Di SPA state ini in-memory; di Blade kita simpan di sessionStorage
// agar tidak reset saat pindah halaman.
Alpine.store('ui', {
  lang: readSession('morela_lang', 'id'),
  role: readSession('morela_role', 'pengunjung'),
  setLang(lang) {
    this.lang = lang;
    writeSession('morela_lang', lang);
    const root = document.documentElement;
    root.classList.toggle('lang-en', lang === 'en');
    root.classList.toggle('lang-id', lang === 'id');
    root.setAttribute('lang', lang);
  },
  setRole(role) {
    this.role = role;
    writeSession('morela_role', role);
  },
});

// Port dari state modal di TourismContext (SPA): dipakai bersama oleh
// seluruh halaman karena semua overlay modal dirender global di layout.
Alpine.store('modals', {
  destination: null,
  culture: null,
  product: null,
  article: null,
  qr: null,
  lightboxIndex: null,
  ticketForPrint: null,
  activeBookingDestination: null,
  openDestination(item) {
    this.destination = item;
  },
  closeDestination() {
    this.destination = null;
  },
  openCulture(item) {
    this.culture = item;
  },
  closeCulture() {
    this.culture = null;
  },
  openProduct(item) {
    this.product = item;
  },
  closeProduct() {
    this.product = null;
  },
  openArticle(item) {
    this.article = item;
  },
  closeArticle() {
    this.article = null;
  },
  openQr(item) {
    this.qr = item;
  },
  closeQr() {
    this.qr = null;
  },
  openLightbox(index) {
    this.lightboxIndex = index;
  },
  closeLightbox() {
    this.lightboxIndex = null;
  },
  openTicket(item) {
    this.ticketForPrint = item;
  },
  closeTicket() {
    this.ticketForPrint = null;
  },
  setActiveBookingDestination(item) {
    this.activeBookingDestination = item;
  },
});

// Port dari handler SPA (DestinationModal/ProductModal/TicketModal).
window.morelaWhatsApp = function (phone, text) {
  var clean = String(phone || '').replace(/[^0-9]/g, '');
  window.open('https://wa.me/' + clean + '?text=' + encodeURIComponent(text), '_blank');
};

window.morelaShare = function (title, text) {
  if (navigator.share) {
    navigator.share({ title: title, text: text, url: window.location.href }).catch(function () {});
  } else {
    navigator.clipboard.writeText(window.location.href);
    var store = window.Alpine && window.Alpine.store('ui');
    alert(store && store.lang === 'en' ? 'Link copied!' : 'Tautan berhasil disalin!');
  }
};

// DestinationModal: set activeBookingDestination lalu pindah ke halaman tiket.
window.morelaBookFromModal = function () {
  var modals = window.Alpine.store('modals');
  var dest = modals.destination;
  if (!dest) return;
  modals.closeDestination();
  window.location.href = (window.MORELA_URLS.tickets || '/tiket') + '?destinasi=' + encodeURIComponent(dest.id);
};

window.morelaPrintTicket = function () {
  window.print();
};

// Port dari src/utils/imageUtils.ts (dipakai admin untuk upload gambar).
window.compressImage = function (file, maxWidth = 1200, quality = 0.7) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = (event) => {
      const img = new Image();
      img.src = event.target?.result;
      img.onload = () => {
        const canvas = document.createElement('canvas');
        let width = img.width;
        let height = img.height;

        if (width > maxWidth) {
          height = Math.round((height * maxWidth) / width);
          width = maxWidth;
        }

        canvas.width = width;
        canvas.height = height;

        const ctx = canvas.getContext('2d');
        if (ctx) {
          ctx.drawImage(img, 0, 0, width, height);
          resolve(canvas.toDataURL('image/jpeg', quality));
        } else {
          reject(new Error('Canvas context not available'));
        }
      };
      img.onerror = (error) => reject(error);
    };
    reader.onerror = (error) => reject(error);
  });
};

window.Alpine = Alpine;
Alpine.start();
