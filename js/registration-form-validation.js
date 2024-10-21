// reference to the original source
// source: https://www.tutorialspoint.com/javascript/javascript_form_validations.htm

function validate() {
  let isValid = true;

  document.getElementById("nameError").innerHTML = "";
  document.getElementById("emailError").innerHTML = "";
  document.getElementById("passwordError").innerHTML = "";
  document.getElementById("confirmPasswordError").innerHTML = "";
  document.getElementById("addressError").innerHTML = "";
  document.getElementById("phoneError").innerHTML = "";

  // Validate name
  if (document.myForm.name.value == "") {
    document.getElementById("nameError").innerHTML =
      "Please provide your name!";
    isValid = false;
  }

  // Validate email
  if (document.myForm.email.value == "") {
    document.getElementById("emailError").innerHTML =
      "Please provide your Email!";
    isValid = false;
  }

  // Validate password
  if (document.myForm.password.value == "") {
    document.getElementById("passwordError").innerHTML =
      "Please provide a password!";
    isValid = false;
  }

  // Validate confirm password
  if (document.myForm.confirm_password.value == "") {
    document.getElementById("confirmPasswordError").innerHTML =
      "Please confirm your password!";
    isValid = false;
  }

  // Check if passwords match
  if (
    document.myForm.password.value !== document.myForm.confirm_password.value
  ) {
    document.getElementById("confirmPasswordError").innerHTML =
      "Passwords do not match!";
    isValid = false;
  }

  // Validate address
  if (document.myForm.address.value == "") {
    document.getElementById("addressError").innerHTML =
      "Please provide your address!";
    isValid = false;
  }

  // Validate phone number
  if (
    document.myForm.phone_number.value == "" ||
    document.myForm.phone_number.value.length != 10
  ) {
    document.getElementById("phoneError").innerHTML =
      "Please provide a valid 10-digit phone number.";
    isValid = false;
  }

  return isValid;
}
