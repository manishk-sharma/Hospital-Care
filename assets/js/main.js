let lastScrollY = 0;
let isOnMobile = window.innerWidth <= 768;

window.addEventListener("scroll", () => {
  if (!isOnMobile) return;

  let currentScrollY = window.scrollY;
  
  if (currentScrollY < 24 || currentScrollY < lastScrollY) {
    document.body.classList.remove("nav-hidden");
  } else {
    document.body.classList.add("nav-hidden");
  }
  
  lastScrollY = currentScrollY;
});

window.addEventListener("resize", () => {
  isOnMobile = window.innerWidth <= 768;
});

document.querySelector(".nav-reveal")?.addEventListener("click", () => {
  document.body.classList.remove("nav-hidden");
});
