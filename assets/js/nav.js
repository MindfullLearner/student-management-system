/**
 * Included on every protected page (dashboard, students, etc).
 * 1. Calls /api/auth/session.php - if not logged in, redirects to login.html
 * 2. Fills in the sidebar's user name/role/avatar
 * 3. Reveals any ".admin-only" elements if the user is an admin
 * 4. Wires up the logout button
 */
(async function () {
  try {
    const data = await apiFetch("/api/auth/session.php");
    window.currentUser = data.user;

    document.querySelectorAll(".js-user-name").forEach((el) => (el.textContent = data.user.full_name));
    document.querySelectorAll(".js-user-role").forEach((el) => (el.textContent = data.user.role));
    document.querySelectorAll(".js-user-avatar").forEach(
      (el) => (el.textContent = data.user.full_name.charAt(0).toUpperCase())
    );

    if (data.user.role === "admin") {
      document.querySelectorAll(".admin-only").forEach((el) => (el.style.display = ""));
    }

    // let the page-specific script know the user is ready (students.js waits for this)
    document.dispatchEvent(new CustomEvent("user-ready", { detail: data.user }));
  } catch (e) {
    window.location.href = "/login.html";
  }
})();

document.addEventListener("DOMContentLoaded", function () {
  const logoutBtn = document.getElementById("logoutBtn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", async function (e) {
      e.preventDefault();
      try {
        await apiFetch("/api/auth/logout.php", { method: "POST" });
      } finally {
        window.location.href = "/login.html";
      }
    });
  }
});
