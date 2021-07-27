import Swiper from "swiper";
import SwiperCore, { Pagination, Navigation } from "swiper/core";
SwiperCore.use([Pagination, Navigation]);
import "swiper/swiper-bundle.css";

const swiper = new Swiper(".swiper-container", {
  observer: true,
  observeParents: true,
  loop: true,
});
