// LOGIN MODAL
const showlog = document.getElementById("log");
const loginFormWrapper = document.getElementById("loginform");
const container = document.getElementById("Container");
const registrationContainer = document.getElementById("registrationcontainer");
const showreg = document.getElementById("registration");
const closeLoginBtn = document.getElementById("closeLogin");
const closeRegistrationBtn = document.getElementById("closeRegistration");
let currentStep = 0;
const steps = document.querySelectorAll(".step");

function openLoginModal() {
  container.style.display = "flex";
  loginFormWrapper.style.display = "block";
  registrationContainer.style.display = "none";
  document.body.style.overflow = "hidden";
}

function openRegistrationModal() {
  registrationContainer.style.display = "flex";
  container.style.display = "none";
  loginFormWrapper.style.display = "none";
  document.body.style.overflow = "hidden";
  currentStep = 0;
  showStep(currentStep);
}

function closeAllModals() {
  container.style.display = "none";
  loginFormWrapper.style.display = "none";
  registrationContainer.style.display = "none";
  document.body.style.overflow = "auto";
}

showlog.addEventListener("click", (event) => {
  event.preventDefault();
  openLoginModal();
});

showreg.addEventListener("click", (event) => {
  event.preventDefault();
  openRegistrationModal();
});

if (closeLoginBtn) {
  closeLoginBtn.addEventListener("click", closeAllModals);
}
if (closeRegistrationBtn) {
  closeRegistrationBtn.addEventListener("click", closeAllModals);
}

// Step navigation
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const submitBtn = document.getElementById("submitBtn");

function showStep(index) {
  steps.forEach((step, i) => {
    step.style.display = (i === index) ? "block" : "none";
  });

  if (prevBtn && nextBtn && submitBtn) {
    prevBtn.style.display = (index === 0) ? "none" : "inline-flex";
    nextBtn.style.display = (index === steps.length - 1) ? "none" : "inline-flex";
    submitBtn.style.display = (index === steps.length - 1) ? "inline-flex" : "none";
  }
}

function nextStep() {
  if (validateStep(currentStep)) {
    if (currentStep < steps.length - 1) {
      currentStep++;
      showStep(currentStep);
    }
  } else {
    alert("Please fill out all required fields before proceeding.");
  }
}

function prevStep() {
  if (currentStep > 0) {
    currentStep--;
    showStep(currentStep);
  }
}

// Validation
function validateStep(stepIndex) {
  const inputs = steps[stepIndex].querySelectorAll("input, select");
  let valid = true;

  inputs.forEach(input => {
    if (input.hasAttribute("required") && !input.value.trim()) {
      input.style.border = "2px solid red";
      valid = false;
    } else {
      input.style.border = "1px solid #ccc";
    }
  });

  return valid;
}

// Close modals when clicking outside
window.addEventListener("click", (event) => {
  if (event.target === container) {
    closeAllModals();
  }
  if (event.target === registrationContainer) {
    closeAllModals();
  }
});

// Escape key closes modals
window.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeAllModals();
  }
});

// Announcement scroll effect
const announcementLink = document.getElementById("announcement");
if (announcementLink) {
  announcementLink.addEventListener("click", (e) => {
    e.preventDefault();
    const section = document.getElementById("section2");
    if (section) {
      section.scrollIntoView({ behavior: "smooth", block: "start" });
      setTimeout(() => {
        section.classList.add("visible");
      }, 500);
    }
  });
}

// Profile dropdown
document.addEventListener("DOMContentLoaded", () => {
  const profilePic = document.querySelector(".profile-pic");
  const dropdown = document.querySelector(".dropdown");
  const registrationForm = document.querySelector("#registrationcontainer form");

  if (profilePic && dropdown) {
    profilePic.addEventListener("click", () => {
      dropdown.classList.toggle("show");
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", nextStep);
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", prevStep);
  }

  if (registrationForm) {
    registrationForm.addEventListener("submit", (e) => {
      const password = registrationForm.querySelector("input[name='password']");
      const confirmPassword = registrationForm.querySelector("input[name='confirm_password']");

      if (password && confirmPassword && password.value !== confirmPassword.value) {
        e.preventDefault();
        alert("Passwords do not match. Please check and try again.");
        password.style.border = "2px solid red";
        confirmPassword.style.border = "2px solid red";
        return;
      }

      if (!validateStep(currentStep)) {
        e.preventDefault();
        alert("Please complete all required fields.");
        return;
      }

      document.body.style.overflow = "auto";
      document.getElementById("registrationcontainer").style.display = "none";
    });
  }
});
