// Scripts

// Initialize AOS, naja, and Fancybox
AOS.init();
naja.initialize();

Fancybox.bind('[data-fancybox="gallery"]', {
  Thumbs: {
    autoStart: true,
    type: "classic",
  },
  Image: {
    thumb: function (instance, slide) {
      return slide.$thumb ? slide.$thumb.attr("src") : null;
    },
    preload: false,
  },
  src: function (instance, slide) {
    return slide.$thumb ? slide.$thumb.attr("data-src") : slide.src;
  },
});

// Navbar
window.addEventListener('DOMContentLoaded', event => {
  var navbarScroll = function () {
    const navbar = document.body.querySelector('#navbar');
    const navbarIcon = navbar.querySelector('.navbar-icon');
    const navIcon = navbarIcon.querySelector('img');
    if (!navbar || !navbarIcon || !navIcon) {
      return;
    }

    if (window.scrollY === 0) {
      navbar.classList.remove('nav-scroll');
      navIcon.style.opacity = '0';
      navIcon.style.transition = 'none';
    } else {
      navbar.classList.add('nav-scroll');
      navIcon.style.transition = 'opacity 0.5s ease';
      navIcon.style.opacity = '1';
    }
  };

  navbarScroll();

  document.addEventListener('scroll', navbarScroll);

  const mainNav = document.body.querySelector('#navbar');
  if (mainNav) {
    new bootstrap.ScrollSpy(document.body, {
      target: '#navbar',
      rootMargin: '0px 0px -40%',
    });
  }

  const navbarToggler = document.body.querySelector('.navbar-toggler');
  const responsiveNavItems = [].slice.call(
    document.querySelectorAll('#navbarResponsive .nav-link')
  );
  responsiveNavItems.map(function (responsiveNavItem) {
    responsiveNavItem.addEventListener('click', () => {
      if (window.getComputedStyle(navbarToggler).display !== 'none') {
        navbarToggler.click();
      }
    });
  });
});

// Preloader
document.addEventListener("DOMContentLoaded", function () {
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      setTimeout(() => {
        preloader.classList.add('loaded');
      }, 2000);
    });
  }
});

// Language dropdown
function toggleDropdown() {
  document.getElementById('language-dropdown').classList.toggle('show');
}

// Swiper
document.addEventListener('DOMContentLoaded', function () {
  const swiper = new Swiper('.swiper-container', {
    direction: 'horizontal',
    loop: true,
    slidesPerView: 'auto',
    centeredSlides: true,
    spaceBetween: 0,
    breakpoints: {
      // Window width >= 576px
      576: {
        slidesPerView: 1,
        spaceBetween: 3
      },
      // Window width >= 768px
      768: {
        slidesPerView: 2,
        spaceBetween: 5
      },
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    effect: 'coverflow',
    coverflowEffect: {
      rotate: 30,
      stretch: 50,
      depth: 200,
      modifier: 0.5,
      slideShadows: false,
      scale: 0.9
    },
  });
});

// Typewriter
const text = document.getElementById('animatedText').getAttribute('data-text');
const animatedText = document.getElementById('animatedText');

let i = 0;
function typeWriter() {
  if (i < text.length) {
    animatedText.innerHTML += text.charAt(i);
    i++;
    setTimeout(typeWriter, 60); // Adjust typing speed here
  }
}

window.onload = typeWriter;

// Jump to gallery images
document.getElementById('animatedText').addEventListener('click', function(event) {
  event.preventDefault();
  
  const target = document.getElementById('gallery-items');
  const offset = 170;
  const bodyRect = document.body.getBoundingClientRect().top;
  const elementRect = target.getBoundingClientRect().top;
  const elementPosition = elementRect - bodyRect;
  const offsetPosition = elementPosition - offset;

  window.scrollTo({
    top: offsetPosition,
    behavior: 'smooth'
  });
});