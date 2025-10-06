function openLogin() {
  document.getElementById("loginModal").style.display = "block";
}

function closeLogin() {
  document.getElementById("loginModal").style.display = "none";
}

function handleForgotPassword() {
  alert("Password recovery is not yet implemented. Please contact support.");
}

// Close modal when clicking outside
window.onclick = function (event) {
  const modal = document.getElementById("loginModal");
  if (event.target === modal) {
    closeLogin();
  }
};

// ✅ Handle login form submission
document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(loginForm);

      fetch("login.php", {
        method: "POST",
        body: formData
      })
        .then((res) => res.text())
        .then((data) => {
          console.log("Server raw response:", data);
          if (data.trim() === "success") {
            // ✅ Redirect to secure PHP dashboard
            window.location.href = "user_dashboard.php";
          } else {
            alert("Invalid email or password. Please try again.");
          }
        })
        .catch((err) => console.error("Login error:", err));
    });
  }
});
