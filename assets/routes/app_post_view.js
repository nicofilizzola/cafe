import Swiper from "swiper";
import SwiperCore, { Pagination, Navigation } from "swiper/core";
SwiperCore.use([Pagination, Navigation]);
import "swiper/swiper-bundle.css";

const swiper = new Swiper(".swiper-container", {
  observer: true,
  observeParents: true,
  pagination: {
    el: ".swiper-pagination",
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});

import manageContainerHeightMobile from "../functions/manageContainerHeightMobile";
const manageContainerHeight = () => {
  const container = document.querySelector(".container-fluid.adapt-height");
  if (container != null) {
    container.style.height =
      "calc(100vh - " + document.querySelector("header").clientHeight + "px)";
  }
};
const manageNavigateButton = () => {
  const navigateButton = document.querySelector("#navigate-button");
  if (navigateButton != null) {
    const mediaContainer = document.querySelector("#media");
    let clientOnMedia;
    
    window.addEventListener("scroll", function () {
      if (window.scrollY >= mediaContainer.offsetTop - 10) {
        clientOnMedia = true;
      } else {
        clientOnMedia = false;
      }

      if (clientOnMedia) {
        navigateButton.href = "#text"
        navigateButton.children[0].textContent = "Revenir au texte";
      } else {
        navigateButton.href = "#media"
        navigateButton.children[0].textContent = "Sauter aux médias";
      }
    });

  }
};

manageContainerHeightMobile(".col-12.mobile:not(.no-resize)");
manageContainerHeight();
manageNavigateButton();
