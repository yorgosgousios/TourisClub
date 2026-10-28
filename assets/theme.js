document.addEventListener("DOMContentLoaded", () => {
  /* ===========================
     HEADER: hide on scroll down, show on scroll up
  =========================== */
  const header = document.getElementById("site-header");
  if (header) {
    let lastScrollY = 0;
    const scrollThreshold = 80; // don't hide until past hero area a bit

    const onScroll = () => {
      const currentY = window.scrollY;

      // Add/remove background
      header.classList.toggle("scrolled", currentY > 60);

      // Hide/show based on direction
      if (currentY > scrollThreshold) {
        if (currentY > lastScrollY) {
          // Scrolling DOWN → hide
          header.classList.add("site-header--hidden");
        } else {
          // Scrolling UP → show
          header.classList.remove("site-header--hidden");
        }
      } else {
        // At the top → always visible
        header.classList.remove("site-header--hidden");
      }

      lastScrollY = currentY;
    };

    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
  }

  /* ===========================
     MOBILE SIDEBAR
  =========================== */
  const menuToggle = document.getElementById("menu-toggle");
  const sidebar = document.getElementById("mobile-sidebar");
  const sidebarClose = document.getElementById("sidebar-close");
  const sidebarOverlay = document.getElementById("sidebar-overlay");

  const openSidebar = () => {
    if (sidebar) sidebar.classList.add("open");
    if (sidebarOverlay) sidebarOverlay.classList.add("active");
    document.body.style.overflow = "hidden";
  };

  const closeSidebar = () => {
    if (sidebar) sidebar.classList.remove("open");
    if (sidebarOverlay) sidebarOverlay.classList.remove("active");
    document.body.style.overflow = "";
  };

  if (menuToggle) menuToggle.addEventListener("click", openSidebar);
  if (sidebarClose) sidebarClose.addEventListener("click", closeSidebar);
  if (sidebarOverlay) sidebarOverlay.addEventListener("click", closeSidebar);

  // Toggle sub-menus
  document.querySelectorAll(".msb-item__toggle").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const item = btn.closest(".msb-item");
      const sub = item.querySelector(".msb-item__sub");
      const isOpen = sub.classList.contains("open");

      // Close all others
      document.querySelectorAll(".msb-item__sub.open").forEach((s) => {
        s.classList.remove("open");
        s.previousElementSibling.classList.remove("active");
      });

      if (!isOpen) {
        sub.classList.add("open");
        btn.classList.add("active");
      }
    });
  });

  // Parent links with children: toggle submenu instead of navigating
  document
    .querySelectorAll(".msb-item--has-children > .msb-item__link")
    .forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const btn = link.nextElementSibling;
        if (btn) btn.click();
      });
    });

  // Sub-links and standalone links close the sidebar
  document.querySelectorAll(".msb-item__sublink").forEach((link) => {
    link.addEventListener("click", closeSidebar);
  });
  document
    .querySelectorAll(
      ".msb-item:not(.msb-item--has-children) > .msb-item__link",
    )
    .forEach((link) => {
      link.addEventListener("click", closeSidebar);
    });

  /* ===========================
     HERO CAROUSEL (Glide.js)
  =========================== */
  const heroEl = document.getElementById("hero-glide");
  if (heroEl && typeof Glide !== "undefined") {
    const heroGlide = new Glide("#hero-glide", {
      type: "carousel",
      autoplay: 5000,
      hoverpause: false,
      animationDuration: 800,
      gap: 0,
    });

    const manageVideos = () => {
      // Glide carousel mode adds clones; skip them so i matches heroGlide.index
      const slides = heroEl.querySelectorAll(
        ".glide__slide:not(.glide__slide--clone)",
      );
      slides.forEach((slide, i) => {
        const video = slide.querySelector(".hero-carousel__video");
        if (!video) return;

        if (i === heroGlide.index) {
          video.currentTime = 0;
          video.play().catch(() => {});
        } else {
          video.pause();
        }
      });
    };

    heroGlide.on("run", () => {
      // Update bullets
      const bullets = heroEl.querySelectorAll(".glide__bullet");
      bullets.forEach((b, i) => {
        b.classList.toggle("glide__bullet--active", i === heroGlide.index);
      });

      // Handle videos
      manageVideos();
    });

    heroGlide.mount();

    // Activate first bullet & play first video
    const firstBullet = heroEl.querySelector(".glide__bullet");
    if (firstBullet) firstBullet.classList.add("glide__bullet--active");
    manageVideos();
  }

  /* ===========================
     FEATURES CAROUSEL (Glide.js)
  =========================== */
  const featuresEl = document.getElementById("features-glide");
  if (featuresEl && typeof Glide !== "undefined") {
    const featuresGlide = new Glide("#features-glide", {
      type: "carousel",
      perView: 4,
      gap: 16,
      autoplay: false,
      animationDuration: 600,
      breakpoints: {
        1200: { perView: 3 },
        900: { perView: 2 },
        600: { perView: 1.2 },
      },
    });

    featuresGlide.mount();

    // External nav buttons
    const featuresPrev = document.getElementById("features-prev");
    const featuresNext = document.getElementById("features-next");
    if (featuresPrev)
      featuresPrev.addEventListener("click", () => featuresGlide.go("<"));
    if (featuresNext)
      featuresNext.addEventListener("click", () => featuresGlide.go(">"));
  }

  /* ===========================
     GALLERY CAROUSEL (Glide.js)
  =========================== */
  const galleryEl = document.getElementById("gallery-glide");
  if (galleryEl && typeof Glide !== "undefined") {
    const galleryGlide = new Glide("#gallery-glide", {
      type: "carousel",
      perView: 3,
      gap: 16,
      autoplay: 4000,
      hoverpause: true,
      animationDuration: 600,
      breakpoints: {
        1024: { perView: 2 },
        768: { perView: 1.2 },
      },
    });

    galleryGlide.mount();

    // External nav buttons
    const prevBtn = document.getElementById("gallery-prev");
    const nextBtn = document.getElementById("gallery-next");
    if (prevBtn) prevBtn.addEventListener("click", () => galleryGlide.go("<"));
    if (nextBtn) nextBtn.addEventListener("click", () => galleryGlide.go(">"));
  }

  /* ===========================
     SINGLE PRODUCT: Gallery Thumbs
  =========================== */
  const mainImg = document.getElementById("product-main-img");
  if (mainImg) {
    document.querySelectorAll(".single-product__thumb").forEach((thumb) => {
      thumb.addEventListener("click", () => {
        mainImg.src = thumb.dataset.src;
        document
          .querySelectorAll(".single-product__thumb")
          .forEach((t) => t.classList.remove("active"));
        thumb.classList.add("active");
      });
    });
  }

  /* ===========================
     INTEREST MODAL
  =========================== */
  const modal = document.getElementById("interest-modal");
  const openBtn = document.getElementById("open-interest-modal");
  const closeBtn = document.getElementById("close-interest-modal");
  const modalOverlay = document.getElementById("interest-modal-overlay");
  const interestForm = document.getElementById("interest-form");
  const successMsg = document.getElementById("interest-success");

  const openModal = () => {
    if (modal) {
      modal.classList.add("open");
      document.body.style.overflow = "hidden";
    }
  };

  const closeModal = () => {
    if (modal) {
      modal.classList.remove("open");
      document.body.style.overflow = "";
    }
  };

  if (openBtn) openBtn.addEventListener("click", openModal);
  if (closeBtn) closeBtn.addEventListener("click", closeModal);
  if (modalOverlay) modalOverlay.addEventListener("click", closeModal);

  // Close on Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal && modal.classList.contains("open")) {
      closeModal();
    }
  });

  // Form submission
  if (interestForm) {
    interestForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const errorMsg = document.getElementById("interest-error");
      const submitBtn = interestForm.querySelector('[type="submit"]');
      const fail = (msg) => {
        if (errorMsg) {
          errorMsg.textContent =
            msg || "Sorry, something went wrong. Please try again.";
          errorMsg.style.display = "block";
        }
      };

      submitBtn.disabled = true;
      if (errorMsg) errorMsg.style.display = "none";

      // getAttribute: form.action is shadowed by the <input name="action">
      fetch(interestForm.getAttribute("action"), {
        method: "POST",
        body: new FormData(interestForm),
      })
        .then((res) => res.json())
        .then((res) => (res.success ? showSuccess() : fail(res.data)))
        .catch(() => fail())
        .finally(() => (submitBtn.disabled = false));
    });

    const showSuccess = () => {
      interestForm.style.display = "none";
      if (successMsg) successMsg.style.display = "block";

      // Auto-close after 3 seconds
      setTimeout(() => {
        closeModal();
        interestForm.style.display = "";
        interestForm.reset();
        if (successMsg) successMsg.style.display = "none";
      }, 3000);
    };
  }
});
