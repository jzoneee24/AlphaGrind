function handleLogin(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  fetch("login.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.text())
  .then(data => {
    console.log("Response from PHP:", data);

    const response = data.trim();

    if (response === "member" || response === "success") {
      window.location.href = "user_dashboard.php"; 

    } else if (response === "admin") {
      window.location.href = "admin_dashboard.php"; 

    } else if (response === "trainer") {
      window.location.href = "trainer_dashboard.php"; 
      
    } else {
      alert("Invalid email or password!");
    }
  })
  .catch(err => {
    console.error("Login error:", err);
    alert("Something went wrong. Please try again.");
  });
}

function handleForgotPassword() {
  alert("Password recovery is not yet implemented.");
}
