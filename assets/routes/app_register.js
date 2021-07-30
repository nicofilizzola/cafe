import switchClasses from "../functions/switchClasses";
import Modal from "../../node_modules/bootstrap/js/src/modal";

const manageFormSubmissionModal = () => {
  document
    .querySelector("#registration-form-button")
    .addEventListener("click", function (event) {
      event.preventDefault();
      var modal = new Modal(document.getElementById("modal"), options);
    });

  document
    .querySelector("#popup-button")
    .addEventListener("click", function (event) {
      event.preventDefault();
      document.querySelector("#registration-form").submit();
    });
};

manageFormSubmissionModal();
