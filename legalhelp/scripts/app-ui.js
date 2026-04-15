document.addEventListener("DOMContentLoaded", () => {
  const body = document.body;
  const sidebar = document.querySelector(".sidebar");
  const sidebarToggle = document.querySelector("[data-sidebar-toggle]");
  const sidebarClose = document.querySelector("[data-sidebar-close]");
  const sidebarBackdrop = document.querySelector(".sidebar-backdrop");
  const themeToggle = document.getElementById("toggle-mode");

  const setSidebarState = (isOpen) => {
    if (!sidebar || !sidebarToggle) {
      return;
    }

    sidebar.classList.toggle("is-open", isOpen);

    if (sidebarBackdrop) {
      sidebarBackdrop.classList.toggle("is-visible", isOpen);
    }

    sidebarToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    body.classList.toggle("sidebar-open", isOpen);
  };

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", () => {
      setSidebarState(!sidebar.classList.contains("is-open"));
    });
  }

  if (sidebarClose) {
    sidebarClose.addEventListener("click", () => setSidebarState(false));
  }

  if (sidebarBackdrop) {
    sidebarBackdrop.addEventListener("click", () => setSidebarState(false));
  }

  const applyTheme = (theme) => {
    const isDark = theme === "enabled";
    body.classList.toggle("dark-mode", isDark);

    if (themeToggle) {
      themeToggle.textContent = isDark ? "Mode clair" : "Mode sombre";
      themeToggle.setAttribute("aria-pressed", isDark ? "true" : "false");
    }
  };

  applyTheme(localStorage.getItem("darkMode"));

  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      const nextValue = body.classList.contains("dark-mode") ? "disabled" : "enabled";
      localStorage.setItem("darkMode", nextValue);
      applyTheme(nextValue);
    });
  }

  window.addEventListener("resize", () => {
    if (window.innerWidth > 900) {
      setSidebarState(false);
    }
  });
});
