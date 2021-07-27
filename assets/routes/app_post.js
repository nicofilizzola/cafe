import Swiper from "swiper";
import SwiperCore, { Pagination } from "swiper/core";
SwiperCore.use([Pagination]);
import "swiper/swiper-bundle.css";

const swiper = new Swiper(".swiper-container", {
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  observer: true,
  observeParents: true,
});