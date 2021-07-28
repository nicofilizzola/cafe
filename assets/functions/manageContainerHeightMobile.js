const manageContainerHeightMobile = (selector) => {
  if (document.querySelector(selector) != null) {
    document.querySelectorAll(selector).forEach((container) => {
      container.style.height = "calc(" + window.innerHeight + "px - " + "70px)";
    });
  }
};

export default manageContainerHeightMobile;
