const switchClasses = (selector, oldClass, newClass) => {
    const element = document.querySelector(selector);
    if (element.classList.contains(oldClass)){
        element.classList.replace(oldClass, newClass);
    }
}

export default switchClasses;