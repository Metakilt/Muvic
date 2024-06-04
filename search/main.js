//**필요한 추가 작업
//1. 무한스크롤 --> 실패 (키워드 검색 최대개수가 20개로 한정되는 문제가 있습니다.)
//헤더푸터 합치기




// 검색 버튼 클릭 시
document
  .querySelector(".search-page-input-box .sp-search-button")
  .addEventListener("click", getMovieByKeyword);

// Enter 키 입력 시
document
  .querySelector(".search-page-input-box input")
  .addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      getMovieByKeyword();
    }
  });

//close 버튼 이벤트
document
  .querySelector("#search-page .search-page-input-box .sp-close-button")
  .addEventListener("click", function () {
    document.querySelector(".search-page-input-box input").value = "";
    document.querySelector(
      "#search-page .search-page-input-box .sp-close-button"
    ).style.opacity = "0";
  });

//input 함수의 값이 지워졌을때의 함수
document
  .querySelector(".search-page-input-box input")
  .addEventListener("input", function () {
    // 입력란의 값 가져오기
    let keyword = this.value;

    // 입력값이 비어 있는 경우
    if (keyword.length === 0) {
      document.querySelector(
        "#search-page .search-page-input-box p"
      ).innerText = "";
      document.querySelector(
        "#search-page .search-page-input-box .sp-close-button"
      ).style.opacity = "0";
      // 추가로 원하는 동작을 수행할 수 있습니다.
    } else if (keyword.length !== 0) {
      document.querySelector(
        "#search-page .search-page-input-box .sp-close-button"
      ).style.opacity = "100";
    }
  });

getPopularMovies();
getRunningMovies();