import Swiper from "swiper";
const swiper = new Swiper(".swiper-container", {
    allowTouchMove: false
});

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
    swiper.slideTo(pressedButtonIndex - 1);
    switchClasses(
      document.querySelector("#js-media-form-" + pressedButtonIndex),
      document.querySelector("#js-media-form-" + otherButtonIndex),
      'active',
      'inactive'
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

const errorController = (error) => {
  switch (error) {
    case "ERROR721":
    case "ERROR722":
      document
        .querySelector("#image_media_imageFile_file")
        .classList.add("alert-danger");
      break;
    case "ERROR723":
      document.querySelector("#video_media_url").classList.add("alert-danger");

      switchClasses(
        document.querySelector("#js-media-type-2"),
        document.querySelector("#js-media-type-1"),
        "btn-primary",
        "btn-secondary"
      );
      swiper.slideTo(1);
      switchClasses(
        document.querySelector("#js-media-form-" + pressedButtonIndex),
        document.querySelector("#js-media-form-" + otherButtonIndex),
        'active',
        'inactive'
      );
      break;
    default:
      break;
  }
};

const ManageErrorDisplay = () => {
  if (document.querySelector("#flash")) {
    const flashSelector = "#flash div.alert-danger";
    errorController(error);
    const originalString = document
      .querySelector(flashSelector)
      .textContent.trim();
    const error = originalString.split(" ")[0];

    document.querySelector(flashSelector).textContent = originalString.substr(
      originalString.indexOf(" ") + 1
    );
  }
};

ManageMediaType();
ManageErrorDisplay();
