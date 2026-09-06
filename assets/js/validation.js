/**
 * validateForm(form): call this at the top of any submit handler.
 * Returns true/false, and shows/hides inline .field-error spans
 * (each one needs data-for="fieldName" matching an input's name).
 *
 * Also auto-clears the error style as the user retypes a field
 * (wired once per form via markFormForLiveClear).
 */
function validateForm(form) {
  let isValid = true;

  form.querySelectorAll("[required]").forEach(function (field) {
    const errorEl = form.querySelector('.field-error[data-for="' + field.name + '"]');
    let fieldValid = field.value.trim() !== "";

    if (field.type === "email" && fieldValid) {
      fieldValid = field.value.includes("@") && field.value.includes(".");
    }
    if (field.type === "password" && fieldValid) {
      fieldValid = field.value.length >= 6;
    }

    if (!fieldValid) {
      isValid = false;
      field.classList.add("invalid");
      if (errorEl) errorEl.classList.add("visible");
    } else {
      field.classList.remove("invalid");
      if (errorEl) errorEl.classList.remove("visible");
    }
  });

  const pass = form.querySelector('[name="password"]');
  const confirm = form.querySelector('[name="confirm_password"]');
  if (pass && confirm) {
    const errorEl = form.querySelector('.field-error[data-for="confirm_password"]');
    if (pass.value !== confirm.value) {
      isValid = false;
      confirm.classList.add("invalid");
      if (errorEl) errorEl.classList.add("visible");
    }
  }

  markFormForLiveClear(form);
  return isValid;
}

function markFormForLiveClear(form) {
  if (form.dataset.liveClearBound) return;
  form.dataset.liveClearBound = "true";
  form.querySelectorAll("input").forEach(function (field) {
    field.addEventListener("input", function () {
      field.classList.remove("invalid");
      const errorEl = form.querySelector('.field-error[data-for="' + field.name + '"]');
      if (errorEl) errorEl.classList.remove("visible");
    });
  });
}
