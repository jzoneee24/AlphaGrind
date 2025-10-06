function openForm() {
  document.getElementById("signupModal").style.display = "block";
}

function closeForm() {
  document.getElementById("signupModal").style.display = "none";
}

function openAbout() {
  document.getElementById("aboutModal").style.display = "block";
}

function closeAbout() {
  document.getElementById("aboutModal").style.display = "none";
}

function openLogin() {
  const loginModal = document.getElementById("loginModal");
  loginModal.style.display = "block";
}

function closeLogin() {
  const loginModal = document.getElementById("loginModal");
  loginModal.style.display = "none";
}

window.onclick = function (event) {
  const signupModal = document.getElementById("signupModal");
  const aboutModal = document.getElementById("aboutModal");
  const loginModal = document.getElementById("loginModal");

  if (event.target === signupModal) signupModal.style.display = "none";
  if (event.target === aboutModal) aboutModal.style.display = "none";
  if (event.target === loginModal) loginModal.style.display = "none";
};

document.addEventListener("DOMContentLoaded", function () {
  const paymentSelect = document.getElementById("paymentMethod");
  const eWalletOptions = document.getElementById("eWalletOptions");
  const eWalletChoice = document.getElementById("eWalletChoice");
  const form = document.querySelector("#signupModal form");
  const contactForm = document.getElementById("contactForm");

  // ✅ Toggle program descriptions
  const toggles = document.querySelectorAll(".program-toggle");

  toggles.forEach((button) => {
    button.addEventListener("click", () => {
      const card = button.closest(".program-card");
      const desc = card.querySelector(".program-description");

      document.querySelectorAll(".program-card").forEach((c) => {
        c.classList.remove("active");
        c.querySelector(".program-description").classList.remove("active");
      });

      const isActive = card.classList.contains("active");
      if (!isActive) {
        card.classList.add("active");
        desc.classList.add("active");
        card.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    });
  });

  // ✅ Show E-Wallet options dynamically
  paymentSelect.addEventListener("change", function () {
    eWalletOptions.style.display =
      this.value === "e-wallet" ? "block" : "none";
  });

  // ✅ Form submission logic
  form.addEventListener("submit", function (e) {
    const paymentMethod = paymentSelect.value;

    if (paymentMethod === "e-wallet") {
      const wallet = eWalletChoice?.value;
      if (!wallet) {
        e.preventDefault();
        alert("Please select a specific E-Wallet option.");
        return;
      }

      const confirmed = confirm(
        `You selected ${wallet.toUpperCase()} as your payment method.\nClick OK to continue.`
      );
      if (!confirmed) {
        e.preventDefault();
      }
    }
  });

  // ✅ Contact form handler (if present)
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      alert("Thanks for reaching out! We'll get back to you shortly.");
      contactForm.reset();
      closeAbout();
    });
  }

  // ✅ Inject login form into modal
  fetch('login.html')
    .then(res => res.text())
    .then(html => {
      const loginContent = document.getElementById('loginContent');
      if (loginContent) {
        loginContent.innerHTML += html;
      }
    });
});
