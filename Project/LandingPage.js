<<<<<<< HEAD

const showlog = document.getElementById("log"); 
const log = document.getElementById("loginform");      
const container = document.getElementById("Container");
const registrationContainer = document.getElementById("registrationcontainer");
const showreg = document.getElementById("registration");
let currentStep = 0;
const steps = document.querySelectorAll(".step");

// LOGIN MODAL

showlog.addEventListener("click", (event) => {
  event.preventDefault();

  container.style.display = "block";
  log.style.display = "block";
  registrationContainer.style.display = "none"; // hide registration
  document.body.style.overflow = "hidden";
});



// REGISTRATION MODAL

showreg.addEventListener("click", (event) => {
  event.preventDefault();

  registrationContainer.style.display = "block";
  container.style.display = "none"; // hide login
  log.style.display = "none";
  document.body.style.overflow = "hidden";

  currentStep = 0;
  showStep(currentStep);
});



// STEP FUNCTION

function showStep(index) {
  steps.forEach((step, i) => {
    step.style.display = (i === index) ? "block" : "none";
  });
}



// VALIDATION FUNCTION

function validateStep(stepIndex) {
  const inputs = steps[stepIndex].querySelectorAll("input, select");
  let valid = true;

  const currentYear = new Date().getFullYear();

  inputs.forEach(input => {

    // REQUIRED CHECK
    if (input.hasAttribute("required") && !input.value.trim()) {
      input.style.border = "2px solid red";
      valid = false;
      return;
    }

    // BIRTHDATE VALIDATION
    if (input.name === "birthdate" && input.value) {
      const selectedYear = new Date(input.value).getFullYear();

      if (selectedYear >= currentYear) {
        alert("Birth year cannot be the current year or future year.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }
    }

    // PASSWORD VALIDATION
    if (input.name === "password") {
      const confirmPassword = document.querySelector('input[name="confirm_password"]');

      // Minimum 8 characters
      if (input.value.length < 8) {
        alert("Password must be at least 8 characters long.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }

      // Must contain at least 1 letter and 1 number
      const strongPassword = /^(?=.*[A-Za-z])(?=.*\d).+$/;
      if (!strongPassword.test(input.value)) {
        alert("Password must contain at least one letter and one number.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }

      // Match check
      if (confirmPassword && input.value !== confirmPassword.value) {
        alert("Passwords do not match.");
        confirmPassword.style.border = "2px solid red";
        input.style.border = "2px solid red";
        valid = false;
        return;
      }
    }

    // CONFIRM PASSWORD VALIDATION
    if (input.name === "confirm_password") {
      const password = document.querySelector('input[name="password"]');

      if (password && input.value !== password.value) {
        alert("Passwords do not match.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }
    }

    input.style.border = "1px solid #ccc";
  });

  return valid;
}


// NEXT BUTTON

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



// PREVIOUS BUTTON

function prevStep() {
  if (currentStep > 0) {
    currentStep--;
    showStep(currentStep);
  }
}


// CLOSE LOGIN and regitration WHEN CLICK OUTSIDE

window.addEventListener("click", (event) => {
  if (event.target === container) {
    container.style.display = "none";
    log.style.display = "none";
    document.body.style.overflow = "auto";
  }

  if (event.target === registrationContainer) {
    registrationContainer.style.display = "none";
    document.body.style.overflow = "auto";
    inputs.forEach(input => input.style.border = "1px solid #ccc"); // reset input borders
  }
});
//scrool effect anouncement
document.getElementById("anouncement").addEventListener("click", (e) => {
    e.preventDefault();
    const section = document.getElementById("section2");

   
    section.scrollIntoView({
        behavior: "smooth",
        block: "start"
    });

   
    setTimeout(() => {
        section.classList.add("visible");
    }, 2); 
});
// PROFILE DROPDOWN
document.addEventListener("DOMContentLoaded", () => {
  const profilePic = document.querySelector(".profile-pic");
  const dropdown = document.querySelector(".dropdown");

  profilePic.addEventListener("click", () => {
    dropdown.classList.toggle("show");
  });
=======

const showlog = document.getElementById("log"); 
const log = document.getElementById("loginform");      
const container = document.getElementById("Container");
const registrationContainer = document.getElementById("registrationcontainer");
const showreg = document.getElementById("registration");
let currentStep = 0;
const steps = document.querySelectorAll(".step");

// LOGIN MODAL

showlog.addEventListener("click", (event) => {
  event.preventDefault();

  container.style.display = "block";
  log.style.display = "block";
  registrationContainer.style.display = "none"; // hide registration
  document.body.style.overflow = "hidden";
});



// REGISTRATION MODAL

showreg.addEventListener("click", (event) => {
  event.preventDefault();

  registrationContainer.style.display = "block";
  container.style.display = "none"; // hide login
  log.style.display = "none";
  document.body.style.overflow = "hidden";

  currentStep = 0;
  showStep(currentStep);
});



// STEP FUNCTION

function showStep(index) {
  steps.forEach((step, i) => {
    step.style.display = (i === index) ? "block" : "none";
  });
}



// VALIDATION FUNCTION

function validateStep(stepIndex) {
  const inputs = steps[stepIndex].querySelectorAll("input, select");
  let valid = true;

  const currentYear = new Date().getFullYear();

  inputs.forEach(input => {

    // REQUIRED CHECK
    if (input.hasAttribute("required") && !input.value.trim()) {
      input.style.border = "2px solid red";
      valid = false;
      return;
    }

    // BIRTHDATE VALIDATION
    if (input.name === "birthdate" && input.value) {
      const selectedYear = new Date(input.value).getFullYear();

      if (selectedYear >= currentYear) {
        alert("Birth year cannot be the current year or future year.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }
    }

    // PASSWORD VALIDATION
    if (input.name === "password") {
      const confirmPassword = document.querySelector('input[name="confirm_password"]');

      // Minimum 8 characters
      if (input.value.length < 8) {
        alert("Password must be at least 8 characters long.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }

      // Must contain at least 1 letter and 1 number
      const strongPassword = /^(?=.*[A-Za-z])(?=.*\d).+$/;
      if (!strongPassword.test(input.value)) {
        alert("Password must contain at least one letter and one number.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }

      // Match check
      if (confirmPassword && input.value !== confirmPassword.value) {
        alert("Passwords do not match.");
        confirmPassword.style.border = "2px solid red";
        input.style.border = "2px solid red";
        valid = false;
        return;
      }
    }

    // CONFIRM PASSWORD VALIDATION
    if (input.name === "confirm_password") {
      const password = document.querySelector('input[name="password"]');

      if (password && input.value !== password.value) {
        alert("Passwords do not match.");
        input.style.border = "2px solid red";
        valid = false;
        return;
      }
    }

    input.style.border = "1px solid #ccc";
  });

  return valid;
}


// NEXT BUTTON

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



// PREVIOUS BUTTON

function prevStep() {
  if (currentStep > 0) {
    currentStep--;
    showStep(currentStep);
  }
}


// CLOSE LOGIN and regitration WHEN CLICK OUTSIDE

window.addEventListener("click", (event) => {
  if (event.target === container) {
    container.style.display = "none";
    log.style.display = "none";
    document.body.style.overflow = "auto";
  }

  if (event.target === registrationContainer) {
    registrationContainer.style.display = "none";
    document.body.style.overflow = "auto";
    inputs.forEach(input => input.style.border = "1px solid #ccc"); // reset input borders
  }
});
//scrool effect anouncement
document.getElementById("anouncement").addEventListener("click", (e) => {
    e.preventDefault();
    const section = document.getElementById("section2");

   
    section.scrollIntoView({
        behavior: "smooth",
        block: "start"
    });

   
    setTimeout(() => {
        section.classList.add("visible");
    }, 2); 
});
// PROFILE DROPDOWN
document.addEventListener("DOMContentLoaded", () => {
  const profilePic = document.querySelector(".profile-pic");
  const dropdown = document.querySelector(".dropdown");

  profilePic.addEventListener("click", () => {
    dropdown.classList.toggle("show");
  });
>>>>>>> 3780024 (update)
});