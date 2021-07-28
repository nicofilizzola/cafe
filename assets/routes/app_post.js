import Swiper from "swiper";
import SwiperCore, { Navigation } from "swiper/core";
SwiperCore.use([Navigation]);
import "swiper/swiper-bundle.css";

const swiper = new Swiper(".swiper-container", {
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  observer: true,
  observeParents: true,
});

const manageContainerHeightMobile = () => {
  document.querySelectorAll(".container-fluid").forEach((container) => {
    container.style.height = "calc(" + window.innerHeight + "px - " + "70px)";
  });
};
const manageSeeMoreButtonsHeightMobile = () => {
  document.querySelectorAll(".see-more").forEach((seeMoreDiv) => {
    seeMoreDiv.style.height = document.querySelector(".card").clientHeight + "px";
  });
};

manageContainerHeightMobile();
manageSeeMoreButtonsHeightMobile();
