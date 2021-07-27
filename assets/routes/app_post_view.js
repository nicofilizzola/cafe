import Swiper from "swiper";
import SwiperCore, { Pagination, Navigation } from "swiper/core";
SwiperCore.use([Pagination, Navigation]);
import "swiper/swiper-bundle.css";

const swiper = new Swiper(".swiper-container", {
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  observer: true,
  observeParents: true,
  loop: true,
});
