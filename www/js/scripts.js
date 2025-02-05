/*!
* Start Bootstrap - Grayscale v7.0.6 (https://startbootstrap.com/theme/grayscale)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-grayscale/blob/master/LICENSE)
*/
//


// Scripts
window.addEventListener('DOMContentLoaded', event => {

  // Navbar shrink function with delayed image appearance
  var navbarShrink = function () {
      const navbarCollapsible = document.body.querySelector('#mainNav');
      const navbarBrand = navbarCollapsible.querySelector('.navbar-brand');
      const navImage = navbarBrand.querySelector('img'); // Get the image inside navbar-brand
      if (!navbarCollapsible || !navbarBrand || !navImage) {
          return;
      }

      // If the page is at the top, hide the image
      if (window.scrollY === 0) {
          navbarCollapsible.classList.remove('navbar-shrink');
          navImage.style.opacity = '0';  // Immediately hide image on top
          navImage.style.transition = 'none'; // Disable transition for instant change
      } else {
          // When scrolling down, show the image
          navbarCollapsible.classList.add('navbar-shrink');
          navImage.style.transition = 'opacity 0.5s ease'; // Re-enable transition for smooth fade-in
          navImage.style.opacity = '1';  // Show image on scroll
      }
  };

  // Shrink the navbar initially on page load
  navbarShrink();

  // Shrink the navbar when the page is scrolled
  document.addEventListener('scroll', navbarShrink);

  // Activate Bootstrap scrollspy on the main nav element
  const mainNav = document.body.querySelector('#mainNav');
  if (mainNav) {
      new bootstrap.ScrollSpy(document.body, {
          target: '#mainNav',
          rootMargin: '0px 0px -40%',
      });
  }

  // Collapse responsive navbar when toggler is clicked
  const navbarToggler = document.body.querySelector('.navbar-toggler');
  const responsiveNavItems = [].slice.call(
      document.querySelectorAll('#navbarResponsive .nav-link')
  );
  responsiveNavItems.map(function (responsiveNavItem) {
      responsiveNavItem.addEventListener('click', () => {
          if (window.getComputedStyle(navbarToggler).display !== 'none') {
              navbarToggler.click();  // Close the navbar after clicking a link
          }
      });
  });

});




 /**
   * Preloader
   */
 const preloader = document.querySelector('#preloader');
 if (preloader) {
   window.addEventListener('load', () => {
     setTimeout(() => {
       preloader.classList.add('loaded');
     }, 500);
     setTimeout(() => {
       preloader.remove();
     }, 1500);
   });
 }


 function toggleDropdown() {
    document.getElementById('language-dropdown').classList.toggle('show');
  }


