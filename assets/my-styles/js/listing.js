const titleTag = document.getElementById('titlepage');
const contentTitle = titleTag.textContent;

const toWatchTitle = document.getElementById('title-show');
titleTag.textContent = contentTitle+" "+toWatchTitle.getAttribute('data');