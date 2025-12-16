/**
 * @file validation.js
 * @description Handles form validation for registration, login, search, and booking.
 * @author Shengyi Shi, Yuming Deng, Mingxuan Xu, Yanzhang Lu
 */
document.addEventListener("DOMContentLoaded", function () {
  // Create an error placeholder for a given input field.
  const ensureErrorPlaceholder = (input) => {
    const formControl = input.parentElement;
    let errorDisplay = formControl.querySelector(".error-message");
    if (!errorDisplay) {
      errorDisplay = document.createElement("div");
      errorDisplay.className = "error-message";
      errorDisplay.innerHTML = "&nbsp;";
      if (input.nextSibling) {
        input.parentNode.insertBefore(errorDisplay, input.nextSibling);
      } else {
        input.parentNode.appendChild(errorDisplay);
      }
    }
    return errorDisplay;
  };

  // Show an error message for an input.
  const showError = (input, message) => {
    const errorDisplay = ensureErrorPlaceholder(input);
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    errorDisplay.textContent = message;
    errorDisplay.style.visibility = "visible";
  };

  // Show a success state for an input.
  const showSuccess = (input) => {
    input.classList.add("is-valid");
    input.classList.remove("is-invalid");
    const errorDisplay = ensureErrorPlaceholder(input);
    errorDisplay.innerHTML = "&nbsp;";
    errorDisplay.style.visibility = "hidden";
  };

  // --- Universal Validation Logic ---

  // Check if a required field is empty.
  const checkRequired = (input) => {
    if (input.required && input.value.trim() === "") {
      let labelText = "This field";
      const label =
        input.previousElementSibling ||
        document.querySelector(`label[for="${input.id}"]`);
      if (label) {
        labelText = label.innerText;
      }
      showError(input, `${labelText} is required`);
      return false;
    }
    if (input.required && input.value.trim() !== "") {
      showSuccess(input);
    }
    return true;
  };

  // Check for a valid email format.
  const checkEmail = (input) => {
    if (!input.value.trim()) return true;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(input.value.trim())) {
      showError(input, "Please enter a valid email address");
      return false;
    }
    showSuccess(input);
    return true;
  };

  // Check for a valid mobile number format.
  const checkMobile = (input) => {
    if (!input.value.trim()) return true;
    const mobileRegex =
      /^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{3,5}[-\s\.]?[0-9]{3,5}$/;
    const cleanNumber = input.value.trim().replace(/[\s\-\.\(\)]/g, "");

    if (
      !mobileRegex.test(input.value.trim()) ||
      cleanNumber.length < 8 ||
      cleanNumber.length > 15
    ) {
      showError(input, "Please enter a valid mobile number (8-15 digits)");
      return false;
    }
    showSuccess(input);
    return true;
  };

  // Check for password strength.
  const checkPasswordStrength = (input) => {
    if (!input.value) return true;
    const passwordRegex =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+={}[\]|\\:;"'<>,.?/~`]).{6,12}$/;
    if (!passwordRegex.test(input.value)) {
      showError(
        input,
        "Password must be 6-12 characters and include uppercase, lowercase, number, and special character"
      );
      return false;
    }
    showSuccess(input);
    return true;
  };

  // Check if two passwords match.
  const checkPasswordsMatch = (pass1, pass2) => {
    if (!pass2.value) return true;
    if (pass1.value !== pass2.value) {
      showError(pass2, "Passwords do not match");
      return false;
    }
    showSuccess(pass2);
    return true;
  };

  // Validate credit card number using Luhn algorithm.
  const checkCreditCard = (input) => {
    if (!input.value.trim()) return true;
    const cardNumber = input.value.trim().replace(/[\s\-]/g, "");

    if (!/^\d+$/.test(cardNumber)) {
      showError(input, "Credit card number can only contain digits");
      return false;
    }

    if (cardNumber.length < 13 || cardNumber.length > 19) {
      showError(input, "Credit card number must be 13-19 digits");
      return false;
    }

    let sum = 0;
    let isEven = false;
    for (let i = cardNumber.length - 1; i >= 0; i--) {
      let digit = parseInt(cardNumber[i]);
      if (isEven) {
        digit *= 2;
        if (digit > 9) digit -= 9;
      }
      sum += digit;
      isEven = !isEven;
    }

    if (sum % 10 !== 0) {
      showError(input, "Please enter a valid credit card number");
      return false;
    }
    showSuccess(input);
    return true;
  };

  // Validate expiration date (MM/YY).
  const checkExpiration = (input) => {
    if (!input.value.trim()) return true;
    const expPattern = /^(0[1-9]|1[0-2])\/(\d{2})$/;
    if (!expPattern.test(input.value.trim())) {
      showError(input, "Please use a valid MM/YY format");
      return false;
    }
    showSuccess(input);
    return true;
  };

  // Validate CVV (3-4 digits).
  const checkCVV = (input) => {
    if (!input.value.trim()) return true;
    const cvvPattern = /^\d{3,4}$/;
    if (!cvvPattern.test(input.value.trim())) {
      showError(input, "CVV must be 3-4 digits");
      return false;
    }
    showSuccess(input);
    return true;
  };

  // --- Registration Form Logic ---
  const registrationForm = document.getElementById("registrationForm");
  if (registrationForm) {
    const userType = document.getElementById("userType");
    const firstName = document.getElementById("firstName");
    const lastName = document.getElementById("lastName");
    const email = document.getElementById("email");
    const mobile = document.getElementById("mobile");
    const postalAddress = document.getElementById("postalAddress");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const abn = document.getElementById("abn");

    // Initialize error placeholders
    [
      firstName,
      lastName,
      email,
      mobile,
      postalAddress,
      password,
      confirmPassword,
      abn,
    ].forEach((field) => field && ensureErrorPlaceholder(field));

    // Real-time validation on blur
    firstName.addEventListener("blur", () => checkRequired(firstName));
    lastName.addEventListener("blur", () => checkRequired(lastName));
    email.addEventListener(
      "blur",
      () => checkRequired(email) && checkEmail(email)
    );
    mobile.addEventListener(
      "blur",
      () => checkRequired(mobile) && checkMobile(mobile)
    );
    postalAddress.addEventListener("blur", () => checkRequired(postalAddress));
    password.addEventListener(
      "blur",
      () => checkRequired(password) && checkPasswordStrength(password)
    );
    confirmPassword.addEventListener(
      "blur",
      () =>
        checkRequired(confirmPassword) &&
        checkPasswordsMatch(password, confirmPassword)
    );
    abn.addEventListener(
      "blur",
      () => userType.value === "host" && checkRequired(abn)
    );

    // Real-time validation on input
    password.addEventListener(
      "input",
      () => password.value && checkPasswordStrength(password)
    );
    confirmPassword.addEventListener(
      "input",
      () =>
        confirmPassword.value && checkPasswordsMatch(password, confirmPassword)
    );

    registrationForm.addEventListener("submit", function (e) {
      let isValid =
        checkRequired(firstName) &&
        checkRequired(lastName) &&
        checkRequired(email) &&
        checkEmail(email) &&
        checkRequired(mobile) &&
        checkMobile(mobile) &&
        checkRequired(postalAddress) &&
        checkRequired(password) &&
        checkPasswordStrength(password) &&
        checkRequired(confirmPassword) &&
        checkPasswordsMatch(password, confirmPassword);

      if (userType.value === "host") {
        isValid = checkRequired(abn) && isValid;
      }

      if (!isValid) {
        e.preventDefault();
      }
    });

    userType.addEventListener("change", function () {
      const abnSection = document.getElementById("abnSection");
      if (this.value === "host") {
        abnSection.classList.remove("d-none");
        abn.required = true;
      } else {
        abnSection.classList.add("d-none");
        abn.required = false;
      }
    });
  }

  // --- Login Form Logic ---
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    const loginEmail = document.getElementById("loginEmail");
    const loginPassword = document.getElementById("loginPassword");

    [loginEmail, loginPassword].forEach(
      (field) => field && ensureErrorPlaceholder(field)
    );

    loginEmail.addEventListener(
      "blur",
      () => checkRequired(loginEmail) && checkEmail(loginEmail)
    );
    loginPassword.addEventListener("blur", () => checkRequired(loginPassword));

    loginForm.addEventListener("submit", function (e) {
      const isValid =
        checkRequired(loginEmail) &&
        checkEmail(loginEmail) &&
        checkRequired(loginPassword);

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // --- Search Form Logic ---
  const searchForm = document.getElementById("searchForm");
  if (searchForm) {
    const destination = document.getElementById("destination");
    const checkin = document.getElementById("checkin");
    const checkout = document.getElementById("checkout");
    const guests = document.getElementById("guests");

    [destination, checkin, checkout, guests].forEach(
      (field) => field && ensureErrorPlaceholder(field)
    );

    const checkDates = (checkinInput, checkoutInput) => {
      if (!checkinInput.value || !checkoutInput.value) return true;
      if (new Date(checkoutInput.value) <= new Date(checkinInput.value)) {
        showError(checkoutInput, "Checkout date must be after check-in date");
        return false;
      }
      showSuccess(checkoutInput);
      return true;
    };

    destination.addEventListener("blur", () => checkRequired(destination));
    checkin.addEventListener("blur", () => checkRequired(checkin));
    checkout.addEventListener(
      "blur",
      () => checkRequired(checkout) && checkDates(checkin, checkout)
    );
    checkout.addEventListener(
      "change",
      () => checkout.value && checkDates(checkin, checkout)
    );
    guests.addEventListener("blur", () => checkRequired(guests));

    searchForm.addEventListener("submit", function (e) {
      const isValid =
        checkRequired(destination) &&
        checkRequired(checkin) &&
        checkRequired(checkout) &&
        checkDates(checkin, checkout) &&
        checkRequired(guests);

      if (!isValid) {
        e.preventDefault();
      }
    });
  }

  // --- Booking Form Logic ---
  const bookingForm = document.getElementById("bookingForm");
  if (bookingForm) {
    const bookFirstName = document.getElementById("bookFirstName");
    const bookLastName = document.getElementById("bookLastName");
    const bookEmail = document.getElementById("bookEmail");
    const bookPhone = document.getElementById("bookPhone");
    const ccName = document.getElementById("cc-name");
    const ccNumber = document.getElementById("cc-number");
    const ccExpiration = document.getElementById("cc-expiration");
    const ccCvv = document.getElementById("cc-cvv");

    [
      bookFirstName,
      bookLastName,
      bookEmail,
      bookPhone,
      ccName,
      ccNumber,
      ccExpiration,
      ccCvv,
    ].forEach((field) => field && ensureErrorPlaceholder(field));

    bookFirstName.addEventListener("blur", () => checkRequired(bookFirstName));
    bookLastName.addEventListener("blur", () => checkRequired(bookLastName));
    bookEmail.addEventListener(
      "blur",
      () => checkRequired(bookEmail) && checkEmail(bookEmail)
    );
    bookPhone.addEventListener(
      "blur",
      () => checkRequired(bookPhone) && checkMobile(bookPhone)
    );
    ccName.addEventListener("blur", () => checkRequired(ccName));
    ccNumber.addEventListener(
      "blur",
      () => checkRequired(ccNumber) && checkCreditCard(ccNumber)
    );
    ccExpiration.addEventListener(
      "blur",
      () => checkRequired(ccExpiration) && checkExpiration(ccExpiration)
    );
    ccCvv.addEventListener(
      "blur",
      () => checkRequired(ccCvv) && checkCVV(ccCvv)
    );

    ccNumber.addEventListener(
      "input",
      () => ccNumber.value.length >= 13 && checkCreditCard(ccNumber)
    );
    ccExpiration.addEventListener(
      "input",
      () => ccExpiration.value.length === 5 && checkExpiration(ccExpiration)
    );
    ccCvv.addEventListener(
      "input",
      () => ccCvv.value.length >= 3 && checkCVV(ccCvv)
    );

    bookingForm.addEventListener("submit", function () {
      const isValid =
        checkRequired(bookFirstName) &&
        checkRequired(bookLastName) &&
        checkRequired(bookEmail) &&
        checkEmail(bookEmail) &&
        checkRequired(bookPhone) &&
        checkMobile(bookPhone) &&
        checkRequired(ccName) &&
        checkRequired(ccNumber) &&
        checkCreditCard(ccNumber) &&
        checkRequired(ccExpiration) &&
        checkExpiration(ccExpiration) &&
        checkRequired(ccCvv) &&
        checkCVV(ccCvv);

      if (isValid) {
        alert("Booking successful!");
        bookingForm.reset();
        document
          .querySelectorAll(".is-valid, .is-invalid")
          .forEach((el) => el.classList.remove("is-valid", "is-invalid"));
        // redirect to a confirmation page.
      }
    });
  }
});
