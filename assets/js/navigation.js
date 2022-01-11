/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function () {
    const siteNavigation = document.getElementById("site-navigation");

    // Return early if the navigation don't exist.
    if (!siteNavigation) {
        return;
    }

    const button = siteNavigation.querySelector(".js-nav__toggle");

    // Return early if the button don't exist.
    if ("undefined" === typeof button) {
        return;
    }

    const menu = siteNavigation.getElementsByTagName("ul")[0];
    const menuBranding = document.querySelector(".js-header__branding");

    // Hide menu toggle button if menu is empty and return early.
    if ("undefined" === typeof menu) {
        button.style.display = "none";
        return;
    }

    if (!menu.classList.contains("nav-menu")) {
        menu.classList.add("nav-menu");
    }

    // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
    button.addEventListener("click", function () {
        menuBranding.classList.toggle("is-revert");

        if (siteNavigation.classList.contains("is-open")) {
            siteNavigation.classList.toggle("is-close");
            siteNavigation.classList.remove("is-open");
        } else {
            siteNavigation.classList.toggle("is-open");
            siteNavigation.classList.remove("is-close");
        }
        button.classList.toggle("on");

        if (button.getAttribute("aria-expanded") === "true") {
            button.setAttribute("aria-expanded", "false");
        } else {
            button.setAttribute("aria-expanded", "true");
        }
    });
})();
