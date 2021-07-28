import Swiper from "swiper";
import SwiperCore, { Pagination, Navigation } from "swiper/core";
SwiperCore.use([Pagination, Navigation]);
import "swiper/swiper-bundle.css";

const swiper = new Swiper(".swiper-container", {
  observer: true,
  observeParents: true,
  loop: true,
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
  if (container) {
    container.style.height =
      "calc(100vh - " + document.querySelector("header").clientHeight + "px)";
  }
};

manageContainerHeightMobile('.col-12.mobile:not(.no-resize)');
manageContainerHeight();
