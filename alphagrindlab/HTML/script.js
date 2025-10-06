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

window.onclick = function (event) {
  const signupModal = document.getElementById("signupModal");
  const aboutModal = document.getElementById("aboutModal");

  if (event.target === signupModal) {
    signupModal.style.display = "none";
  }

  if (event.target === aboutModal) {
    aboutModal.style.display = "none";
  }
};

document.addEventListener("DOMContentLoaded", function () {
  const paymentSelect = document.getElementById("paymentMethod");
  const eWalletOptions = document.getElementById("eWalletOptions");
  const eWalletChoice = document.getElementById("eWalletChoice");
  const form = document.querySelector("#signupModal form");
  const contactForm = document.getElementById("contactForm");

  const toggles = document.querySelectorAll(".program-toggle");
  let activeCard = null;

  const imageMap = {
    "Beginner's Strength Training": "beginner.jpg",
    "Advanced Strength & Power Program": "advanced.jpg",
    "Fat Loss & Conditioning Program": "fatloss.jpg",
    "Personal Coaching": "personal.jpg",
    "Core & Mobility Program": "core.jpg",
    "Sports Performance & Athletic Conditioning": "sports.jpg",
    "Post-Rehabilitation & Injury Recovery Program": "recovery.jpg",
  };

  // Toggle program descriptions
  toggles.forEach((button) => {
    button.addEventListener("click", () => {
      const card = button.closest(".program-card");
      const desc = button.nextElementSibling;
      const isActive = activeCard === card;

      document.querySelectorAll(".program-card").forEach((c) => {
        c.style.display = "block";
        c.classList.remove("active");
        c.querySelector(".program-description").style.display = "none";

        const oldImg = c.querySelector(
          ".program-description img.program-preview"
        );
        if (oldImg) oldImg.remove();
      });

      if (isActive) {
        activeCard = null;
      } else {
        card.classList.add("active");
        desc.style.display = "block";
        activeCard = card;

        const programTitle = button.textContent.trim();
        const imageSrc = imageMap[programTitle];
        if (imageSrc) {
          const img = document.createElement("img");
          img.src = imageSrc;
          img.alt = programTitle + " Exercise";
          img.className = "program-preview";
          desc.appendChild(img);
        }

        card.scrollIntoView({
          behavior: "smooth",
          block: "center",
        });
      }
    });
  });

  // Show E-Wallet options dynamically
  paymentSelect.addEventListener("change", function () {
    eWalletOptions.style.display =
      this.value === "e-wallet" ? "block" : "none";
  });

  // ✅ Fix: Allow form submission to signup.php
  form.addEventListener("submit", function (e) {
    const paymentMethod = paymentSelect.value;

    // If user selected e-wallet, validate wallet choice
    if (paymentMethod === "e-wallet") {
      const wallet = eWalletChoice?.value;
      if (!wallet) {
        e.preventDefault(); // stop submission only if invalid
        alert("Please select a specific E-Wallet option.");
        return;
      }

      // Optional confirmation before submitting to PHP
      const confirmed = confirm(
        `You selected ${wallet.toUpperCase()} as your payment method.\nClick OK to continue.`
      );
      if (!confirmed) {
        e.preventDefault(); // cancel submission
      }
    }
    // 👉 If offline payment or valid e-wallet, form will now submit to signup.php
  });

  // Contact form handler
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      alert("Thanks for reaching out! We'll get back to you shortly.");
      contactForm.reset();
      closeAbout();
    });
  }
});
