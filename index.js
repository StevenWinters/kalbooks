function displaySearch() {
  const searchContainer = document.querySelector(".search__container");
  const overlay = document.querySelector(".overlay");
  document.body.style.overflow = "hidden";

  searchContainer.classList.add("active");
  overlay.classList.add("active");
}

function closeSearch() {
  const searchContainer = document.querySelector(".search__container");
  const overlay = document.querySelector(".overlay");
  document.body.style.overflow = "auto";

  searchContainer.classList.remove("active");
  overlay.classList.remove("active");
}

function searchBook() {
  const searchValue = document.querySelector("#text").value;
  const filter = document.querySelector("#filter").value;
  const results = document.querySelector("#results");

  if (searchValue.length > 0) {
    results.classList.add("active");

    fetch("autoSearchResults.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `text=${encodeURIComponent(
        searchValue
      )}&filter=${encodeURIComponent(filter)}`,
    })
      .then((res) => res.text())
      .then((data) => (results.innerHTML = data))
      .catch((err) => console.error("Error:", err));
  } else {
    results.innerHTML = "";
  }
}

function displayAddBook(status) {
  if (status) {
    const input = document.querySelector("#book__id");
    input.type = "text";
  }
}

function validateLogin() {
  validateUser();
  validateSubmit();
}

function showAccount() {
  const accountDetails = document.querySelector(".account__details");
  accountDetails.classList.add("active");
}

function closeAccount() {
  const accountDetails = document.querySelector(".account__details");
  accountDetails.classList.remove("active");
}

function initSlider() {  
  const imageList = document.querySelector(".image__list");
  const slideButtons = document.querySelectorAll(".slide__btn");
  const sliderScrollbar = document.querySelector(".slider__scrollbar");
  
  const scrollbarThumb = sliderScrollbar.querySelector(".scrollbar__thumb");
  const maxScrollLeft = imageList.scrollWidth - imageList.clientWidth;

  scrollbarThumb.addEventListener("mousedown", (e) => {
    const startX = e.clientX;
    const thumbPosition = scrollbarThumb.offsetLeft;

    const handleMouseMove = (e) => {
      const deltaX = e.clientX - startX;
      const newThumbPosition = thumbPosition + deltaX;
      const maxThumbPosition =
        sliderScrollbar.getBoundingClientRect().width -
        scrollbarThumb.offsetWidth;

      const boundedPosition = Math.max(
        0,
        Math.min(maxThumbPosition, newThumbPosition)
      );
      const scrollPosition =
        (boundedPosition / maxThumbPosition) * maxScrollLeft;

      scrollbarThumb.style.left = `${boundedPosition}px`;
      imageList.scrollLeft = scrollPosition;
    };

    const handleMouseUp = () => {
      document.removeEventListener("mousemove", handleMouseMove);
      document.removeEventListener("mouseup", handleMouseUp);
    };

    document.addEventListener("mousemove", handleMouseMove);
    document.addEventListener("mouseup", handleMouseUp);
  });

  slideButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const direction = button.id === "prev__slide" ? -1 : 1;
      const scrollAmount = imageList.clientWidth * direction;
      imageList.scrollBy({ left: scrollAmount, behavior: "smooth" });
      
      
    });
  });

  const handleSlideButtons = () => {
    slideButtons[0].style.display =
      imageList.scrollLeft <= 0 ? "none" : "block";
    slideButtons[1].style.display =
      imageList.scrollLeft >= maxScrollLeft ? "none" : "block";
  };

  const updateScrollThumbPosition = () => {
    const scrollPosition = imageList.scrollLeft;
    const thumbPosition =
      (scrollPosition / maxScrollLeft) *
      (sliderScrollbar.clientWidth - scrollbarThumb.offsetWidth);
    scrollbarThumb.style.left = `${thumbPosition}px`;
  };

  imageList.addEventListener("scroll", () => {
    handleSlideButtons();
    updateScrollThumbPosition();
  });
}



if (window.location.pathname === "/index.php") {
  window.addEventListener("load", initSlider);
}

function openSidebar() {
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".overlay");

  sidebar.classList.add("active");
  overlay.classList.add("active");
  document.classList.add("sidebar--active");
}

function closeSidebar() {
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".overlay");

  sidebar.classList.remove("active");
  overlay.classList.remove("active");
  document.body.classList.remove("sidebar--active");
}

function showFAQDesc(fac) {
  const FAQDesc = fac.nextElementSibling;

  if (FAQDesc.classList.contains("active"))
    return FAQDesc.classList.remove("active");
  FAQDesc.classList.add("active");
}
