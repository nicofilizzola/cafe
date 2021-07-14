import Swiper from "swiper";
import SwiperCore, { Pagination } from "swiper/core";
SwiperCore.use([Pagination]);
import "swiper/swiper-bundle.css";

function initSwipers() {
  const formSwiper = new Swiper("#js-swiper-form-media-type", {
    allowTouchMove: false,
  });
  if (document.querySelector("#js-swiper-post-media")) {
    const mediaSwiper = new Swiper("#js-swiper-post-media", {
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      observer: true,
      observeParents: true,
      loop: true,
    });

    return { form: formSwiper, media: mediaSwiper };
  } else {
    return { form: formSwiper };
  }
}

const switchClasses = (newActive, newInactive, activeClass, inactiveClass) => {
  if (!newActive.classList.contains(activeClass)) {
    newActive.classList.replace(inactiveClass, activeClass);
    newInactive.classList.replace(activeClass, inactiveClass);
  }
};

const ManageMediaType = () => {
  const buttonClickHandler = (pressedButton) => {
    const pressedButtonId = pressedButton.id.split("-");
    const pressedButtonIndex = pressedButtonId[pressedButtonId.length - 1];
    const otherButtonIndex = pressedButtonIndex == 1 ? 2 : 1;
    const otherButton = document.querySelector(
      "#js-media-type-" + otherButtonIndex
    );

    switchClasses(pressedButton, otherButton, "btn-primary", "btn-secondary");
    swiper.form.slideTo(pressedButtonIndex - 1);
    switchClasses(
      document.querySelector("#js-media-form-" + pressedButtonIndex),
      document.querySelector("#js-media-form-" + otherButtonIndex),
      "active",
      "inactive"
    );
  };

  const mediaTypeButtons = [
    document.querySelector("#js-media-type-1"),
    document.querySelector("#js-media-type-2"),
  ];
  mediaTypeButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      buttonClickHandler(this);
    });
  });
};

const ManageErrorDisplay = () => {
  const getError = () => {
    const flashSelector = "#flash div.alert-danger";
    const originalString = document
      .querySelector(flashSelector)
      .textContent.trim();
    const error = originalString.split(" ")[0];

    return {
      flashSelector: flashSelector,
      originalString: originalString,
      code: error,
    };
  };

  const errorController = (error) => {
    switch (error) {
      case "ERROR721":
      case "ERROR722":
        document
          .querySelector("#image_media_imageFile_file")
          .classList.add("alert-danger");
        break;
      case "ERROR723":
        document
          .querySelector("#video_media_url")
          .classList.add("alert-danger");

        switchClasses(
          document.querySelector("#js-media-type-2"),
          document.querySelector("#js-media-type-1"),
          "btn-primary",
          "btn-secondary"
        );
        swiper.form.slideTo(1);
        switchClasses(
          document.querySelector("#js-media-form-2"),
          document.querySelector("#js-media-form-1"),
          "active",
          "inactive"
        );
        break;
      default:
        break;
    }
  };

  if (document.querySelector(".alert-danger")) {
    const error = getError();
    errorController(error.code);
    document.querySelector(error.flashSelector).textContent =
      error.originalString.substr(error.originalString.indexOf(" ") + 1);
  }
};

const swiper = initSwipers();
ManageMediaType();
ManageErrorDisplay();
