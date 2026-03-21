let isOnMobile = window.innerWidth <= 768;

// Hide navbar on page load for mobile
if (isOnMobile) {
  document.body.classList.add("nav-hidden");
}

// Close navbar on scroll for mobile
window.addEventListener("scroll", () => {
  if (isOnMobile) {
    document.body.classList.add("nav-hidden");
  }
});

window.addEventListener("resize", () => {
  isOnMobile = window.innerWidth <= 768;
  // Show navbar when resizing back to desktop
  if (!isOnMobile) {
    document.body.classList.remove("nav-hidden");
  }
});

// Toggle navbar visibility when menu button is clicked
document.querySelector(".nav-reveal")?.addEventListener("click", (e) => {
  e.stopPropagation();
  document.body.classList.toggle("nav-hidden");
});
