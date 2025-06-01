let isOpen = false;
const NOMOR_WHATSAPP = "<?php echo $nomor_wa; ?>";

function toggleChat() {
  const panel = document.getElementById("chatPanel");
  isOpen = !isOpen;

  if (isOpen) {
    panel.classList.add("active");
    updateStatusUsaha();
  } else {
    panel.classList.remove("active");
  }
}

function showTab(tabName, event ) {
  document.querySelectorAll(".tab-content").forEach((tab) => {
    tab.classList.remove("active");
  });

  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.classList.remove("active");
  });

  document.getElementById(tabName).classList.add("active");
  event.target.classList.add("active");

  updateStatusUsaha();
}

function openWhatsApp(pesan) {
  const encodedMessage = encodeURIComponent(pesan);
  const whatsappUrl = `https://wa.me/${NOMOR_WHATSAPP}?text=${encodedMessage}`;
  window.open(whatsappUrl, "_blank");
}

function updateStatusUsaha() {
  const now = new Date();
  const hour = now.getHours();
  const minute = now.getMinutes();
  const currentTime = hour + minute / 60;

  let jamBuka = false;
  let statusText = "";

  if (currentTime >= 12 && currentTime < 22) {
    jamBuka = true;
    statusText = "Kami sedang online";
  } else {
    statusText = currentTime < 12
      ? "Sedang offline - Buka jam 12:00 WIB"
      : "Sedang offline - Buka besok jam 12:00 WIB";
  }

  const statusElements = ["statusText1", "statusText2"];
  const statusDots = ["statusDot1", "statusDot2"];

  statusElements.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.textContent = statusText;
  });

  statusDots.forEach(id => {
    const dot = document.getElementById(id);
    if (dot) {
      dot.style.background = jamBuka ? "#25D366" : "#ff6b6b";
      dot.classList.toggle("online", jamBuka);
    }
  });
}

setTimeout(() => {
  if (!isOpen) {
    const notification = document.querySelector(".notification-dot");
    if (notification) {
      notification.style.display = "block";
    }
  }
}, 5000);

document.addEventListener("click", function (event) {
  const widget = document.querySelector(".chat-widget");
  if (!widget.contains(event.target) && isOpen) {
    toggleChat();
  }
});

document.addEventListener("DOMContentLoaded", function () {
  updateStatusUsaha();

  setInterval(updateStatusUsaha, 60000);
});
