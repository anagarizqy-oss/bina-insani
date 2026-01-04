function toggleDropdown(id) {
  const dropdown = document.getElementById(id);

  // Tutup semua dropdown lain yang sedang terbuka
  document.querySelectorAll(".dropdown-content").forEach((el) => {
    if (el.id !== id) {
      el.classList.remove("show");
    }
  });

  // Toggle class 'show' pada dropdown yang diklik
  dropdown.classList.toggle("show");
}

// Tutup dropdown saat klik di luar area menu
window.onclick = function (event) {
  if (!event.target.matches(".dropbtn")) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains("show")) {
        openDropdown.classList.remove("show");
      }
    }
  }
};
const navbar = document.querySelector(".navbar-new");

window.addEventListener("scroll", () => {
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});
const hamburger = document.getElementById("hamburger-menu");
const navMenu = document.getElementById("nav-menu");

if (hamburger && navMenu) {
  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
  });

  // Close menu when clicking outside
  document.addEventListener("click", (e) => {
    if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
      hamburger.classList.remove("active");
      navMenu.classList.remove("active");
    }
  });
}
