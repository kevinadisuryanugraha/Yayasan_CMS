/**
 * Event Registration Form Handler
 * Professional submission with file upload support
 * Updated for revised flow: ALL registrations → pending
 */

// ============================================
// Payment Section Helper Functions
// (Must be global for onclick handlers in AJAX-loaded content)
// ============================================

// Copy to clipboard
function copyToClipboard(text) {
  if (!text) {
    alert("Tidak ada nomor untuk disalin");
    return;
  }

  navigator.clipboard
    .writeText(text)
    .then(function () {
      alert("Nomor rekening berhasil disalin: " + text);
    })
    .catch(function () {
      // Fallback for older browsers
      var temp = document.createElement("input");
      temp.value = text;
      document.body.appendChild(temp);
      temp.select();
      document.execCommand("copy");
      document.body.removeChild(temp);
      alert("Nomor rekening berhasil disalin: " + text);
    });
}

// Show QR Code modal
function showQRCode(imgSrc, title) {
  var modal = document.getElementById("qrModal");
  var modalImg = document.getElementById("qrModalImage");
  var modalTitle = document.getElementById("qrModalTitle");

  if (modal && modalImg && modalTitle) {
    modalImg.src = imgSrc;
    modalTitle.textContent = title || "QRIS";
    modal.classList.add("active");
  } else {
    // Fallback: open image in new tab
    window.open(imgSrc, "_blank");
  }
}

// Close QR modal
function closeQRModal() {
  var modal = document.getElementById("qrModal");
  if (modal) {
    modal.classList.remove("active");
  }
}

// Preview payment proof
function previewPaymentProof(input) {
  if (input.files && input.files[0]) {
    var file = input.files[0];

    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
      alert("Ukuran file terlalu besar. Maksimal 5MB.");
      input.value = "";
      return;
    }

    // Validate file type
    if (!file.type.match("image.*")) {
      alert("Format file tidak valid. Pilih file gambar (JPG, PNG, dll).");
      input.value = "";
      return;
    }

    var reader = new FileReader();
    reader.onload = function (e) {
      var previewImg = document.getElementById("previewImage");
      var placeholder = document.getElementById("uploadPlaceholder");
      var preview = document.getElementById("uploadPreview");

      if (previewImg) previewImg.src = e.target.result;
      if (placeholder) placeholder.style.display = "none";
      if (preview) preview.style.display = "block";
    };
    reader.readAsDataURL(file);

    // Set payment date
    var now = new Date();
    var dateStr = now.toISOString().slice(0, 19).replace("T", " ");
    var dateInput = document.getElementById("paymentDateInput");
    if (dateInput) dateInput.value = dateStr;
  }
}

// Remove payment proof preview
function removePaymentProof() {
  var fileInput = document.getElementById("paymentProofInput");
  var previewImg = document.getElementById("previewImage");
  var placeholder = document.getElementById("uploadPlaceholder");
  var preview = document.getElementById("uploadPreview");
  var dateInput = document.getElementById("paymentDateInput");

  if (fileInput) fileInput.value = "";
  if (previewImg) previewImg.src = "";
  if (placeholder) placeholder.style.display = "block";
  if (preview) preview.style.display = "none";
  if (dateInput) dateInput.value = "";
}

// ============================================
// Registration Form Submit Handler
// ============================================

function submitRegistrationForm() {
  var form = document.getElementById("rfForm");
  var btn = document.getElementById("rfBtn");

  if (!form || !btn) {
    alert("Form tidak ditemukan. Silakan refresh halaman.");
    return false;
  }

  // Get form values
  var name = form.querySelector('input[name="full_name"]');
  var email = form.querySelector('input[name="email"]');
  var phone = form.querySelector('input[name="phone"]');
  var wa = form.querySelector('input[name="whatsapp"]');
  var paymentProof = form.querySelector('input[name="payment_proof"]');

  // Validate required fields
  if (!name || !name.value.trim()) {
    alert("Nama lengkap harus diisi!");
    if (name) name.focus();
    return false;
  }
  if (!email || !email.value.trim()) {
    alert("Email harus diisi!");
    if (email) email.focus();
    return false;
  }
  if (!phone || !phone.value.trim()) {
    alert("No. HP harus diisi!");
    if (phone) phone.focus();
    return false;
  }
  // Check WhatsApp for paid events
  if (wa && wa.hasAttribute("required") && !wa.value.trim()) {
    alert("WhatsApp harus diisi untuk event berbayar!");
    wa.focus();
    return false;
  }

  // Check payment proof for paid events
  if (paymentProof && paymentProof.hasAttribute("required")) {
    if (!paymentProof.files || !paymentProof.files.length) {
      alert("Bukti pembayaran wajib diupload untuk event berbayar!");
      return false;
    }
    // Validate file size (max 5MB)
    if (paymentProof.files[0].size > 5 * 1024 * 1024) {
      alert("Ukuran file bukti pembayaran terlalu besar. Maksimal 5MB.");
      return false;
    }
  }

  // Disable button and show loading
  var origText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML =
    '<i class="icofont-spinner-alt-1 icofont-spin"></i> <span>Memproses...</span>';

  // Create FormData (automatically includes files)
  var formData = new FormData(form);

  // AJAX Submit using XMLHttpRequest
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/submit_registration.php", true);

  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        var result = JSON.parse(xhr.responseText);
        if (result.success) {
          showRegistrationSuccess(result.data);
        } else {
          alert("Gagal: " + result.message);
          btn.disabled = false;
          btn.innerHTML = origText;
        }
      } catch (e) {
        console.error("Parse error:", e, xhr.responseText);
        alert("Terjadi kesalahan saat memproses data.");
        btn.disabled = false;
        btn.innerHTML = origText;
      }
    } else {
      alert("Server error: " + xhr.status);
      btn.disabled = false;
      btn.innerHTML = origText;
    }
  };

  xhr.onerror = function () {
    alert("Koneksi ke server gagal.");
    btn.disabled = false;
    btn.innerHTML = origText;
  };

  xhr.send(formData);
  return false;
}

function showRegistrationSuccess(data) {
  var content = document.getElementById("registrationModalContent");
  if (!content) {
    alert("Pendaftaran berhasil! Kode: " + data.registration_code);
    return;
  }

  // Updated messages for revised flow (ALL registrations are pending)
  var paymentHtml = "";
  var statusText = "";
  var waitingMessage = "";

  if (data.payment_required) {
    // Paid event - waiting for payment verification
    statusText =
      '<span class="status-pending"><i class="icofont-clock-time"></i> Menunggu Verifikasi Pembayaran</span>';
    waitingMessage = "Bukti pembayaran Anda sedang diverifikasi oleh admin.";

    paymentHtml =
      '<div class="success-payment-alert">' +
      '<div class="success-payment-icon"><i class="icofont-check"></i></div>' +
      '<div class="success-payment-text">' +
      "<strong>✅ Bukti Pembayaran Terkirim</strong>" +
      "<p>Total: <strong>Rp " +
      Number(data.payment_amount).toLocaleString("id-ID") +
      "</strong><br>Admin akan memverifikasi pembayaran Anda. Anda akan dihubungi via WhatsApp.</p>" +
      "</div>" +
      "</div>";
  } else {
    // Free event - waiting for admin confirmation
    statusText =
      '<span class="status-pending"><i class="icofont-clock-time"></i> Menunggu Konfirmasi Admin</span>';
    waitingMessage = "Pendaftaran Anda akan dikonfirmasi oleh admin.";
  }

  content.innerHTML =
    "<style>" +
    '.success-container{text-align:center;padding:30px 20px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}' +
    ".success-icon{width:80px;height:80px;background:linear-gradient(135deg,#00997d,#1a4d5c);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;animation:successPop 0.5s ease}" +
    ".success-icon i{font-size:40px;color:#fff}" +
    "@keyframes successPop{0%{transform:scale(0)}50%{transform:scale(1.2)}100%{transform:scale(1)}}" +
    ".success-title{font-size:22px;color:#1a4d5c;margin:0 0 8px;font-weight:700}" +
    ".success-subtitle{font-size:14px;color:#666;margin:0 0 25px}" +
    ".success-details-box{background:linear-gradient(135deg,#f8f9fa,#fff);border:1px solid #e0e0e0;border-radius:16px;padding:0;overflow:hidden;text-align:left;margin-bottom:20px}" +
    ".success-detail-row{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eee;font-size:14px}" +
    ".success-detail-row:last-child{border-bottom:none}" +
    ".success-detail-row strong{color:#666;font-weight:500}" +
    ".success-detail-row span{color:#333;font-weight:600;text-align:right}" +
    ".success-code{background:linear-gradient(135deg,#00997d,#1a4d5c);color:#fff;padding:6px 14px;border-radius:8px;font-family:monospace;font-size:14px;font-weight:700;letter-spacing:1px}" +
    ".status-pending{background:#fff3e0;color:#e65100;padding:5px 12px;border-radius:20px;font-size:12px;display:inline-flex;align-items:center;gap:5px}" +
    ".status-confirmed{background:#e8f5e9;color:#2e7d32;padding:5px 12px;border-radius:20px;font-size:12px;display:inline-flex;align-items:center;gap:5px}" +
    ".success-payment-alert{display:flex;gap:12px;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);border:2px solid #4caf50;border-radius:12px;padding:15px;margin-top:15px;text-align:left}" +
    ".success-payment-icon{width:44px;height:44px;background:linear-gradient(135deg,#4caf50,#388e3c);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}" +
    ".success-payment-icon i{font-size:20px;color:#fff}" +
    ".success-payment-text strong{font-size:13px;color:#2e7d32;display:block;margin-bottom:5px}" +
    ".success-payment-text p{font-size:12px;color:#2e7d32;margin:0;line-height:1.5}" +
    ".success-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#00997d,#1a4d5c);color:#fff;border:none;padding:14px 35px;border-radius:50px;font-size:15px;font-weight:600;cursor:pointer;transition:all 0.3s;box-shadow:0 4px 15px rgba(0,153,125,0.3)}" +
    ".success-btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,153,125,0.4)}" +
    "@media(max-width:480px){.success-container{padding:25px 15px}.success-icon{width:70px;height:70px}.success-icon i{font-size:35px}.success-title{font-size:20px}.success-detail-row{flex-direction:column;align-items:flex-start;gap:5px}.success-detail-row span{text-align:left}.success-payment-alert{flex-direction:column;text-align:center}.success-payment-icon{margin:0 auto}}" +
    "</style>" +
    '<div class="success-container">' +
    '<div class="success-icon">' +
    '<i class="icofont-check"></i>' +
    "</div>" +
    '<h3 class="success-title">Pendaftaran Berhasil!</h3>' +
    '<p class="success-subtitle">' +
    waitingMessage +
    "</p>" +
    '<div class="success-details-box">' +
    '<div class="success-detail-row">' +
    "<strong>Kode Registrasi</strong>" +
    '<span class="success-code">' +
    data.registration_code +
    "</span>" +
    "</div>" +
    '<div class="success-detail-row">' +
    "<strong>Event</strong>" +
    "<span>" +
    data.event_title +
    "</span>" +
    "</div>" +
    '<div class="success-detail-row">' +
    "<strong>Tanggal</strong>" +
    "<span>" +
    data.event_date +
    "</span>" +
    "</div>" +
    '<div class="success-detail-row">' +
    "<strong>Status</strong>" +
    statusText +
    "</div>" +
    paymentHtml +
    "</div>" +
    '<button onclick="closeRegistrationModal()" class="success-btn">' +
    '<i class="icofont-check-circled"></i> Selesai' +
    "</button>" +
    "</div>";
}
