// 무한스크롤
let currentPage = 1;
let isLoading = false;
// Typing effect
const typingWords = "movies and music at the same time";
const typingText = document.querySelector(".typing-text");

const typeWord = async (word, delay = 100) => {
  const letters = word.split("");
  let i = 0;
  while (i < letters.length) {
    await waitForMs(delay);
    typingText.innerHTML += letters[i];
    i++;
  }
  return;
};

const deleteSentence = async (eleRef) => {
  const sentence = typingText.innerHTML;
  const letters = sentence.split("");
  let i = 0;
  while (letters.length > 0) {
    await waitForMs(100);
    letters.pop();
    typingText.innerHTML = letters.join("");
  }
};

const waitForMs = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

const typingEffect = async () => {

  while (true) {
    await typeWord(typingWords);
    await waitForMs(1500);
    await deleteSentence();
    await waitForMs(500);

  }
};

typingEffect();

// 스크롤 이벤트 리스너 추가
window.addEventListener("scroll", () => {
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
    if (scrollTop + clientHeight >= scrollHeight - 5 && !isLoading) {
      fetchMoreMovies();
    }
  });

// hamburger toggle btn
const hamburgerBtn = document.querySelector(".hamburger-btn");
const hamburgerBtnSticks = hamburgerBtn.querySelectorAll("span"); // 수정된 부분

hamburgerBtn.addEventListener("click", () => {
  hamburgerBtnSticks.forEach((stick) => {
    stick.classList.toggle("change");
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const slideNextBtn = document.querySelectorAll(".slide-next-button");
  const slidePrevBtn = document.querySelectorAll(".slide-pre-button");

  slideNextBtn.forEach((button) =>
    button.addEventListener("click", (event) => {
      const slides =
        event.currentTarget.parentNode.querySelector("ul.mv-slide");
      const itemWidth = slides.querySelector("li").offsetWidth + 20;
      const scrollAmount = slides.scrollLeft + itemWidth * 5;
      slides.scrollTo({
        left: scrollAmount,
        behavior: "smooth",
      });
    })
  );

  slidePrevBtn.forEach((button) =>
    button.addEventListener("click", (event) => {
      const slides =
        event.currentTarget.parentNode.querySelector("ul.mv-slide");
      const itemWidth = slides.querySelector("li").offsetWidth + 20;
      const scrollAmount = slides.scrollLeft - itemWidth * 5;
      slides.scrollTo({
        left: scrollAmount,
        behavior: "smooth",
      });
    })
  );
});