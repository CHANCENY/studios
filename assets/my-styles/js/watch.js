const titleTag = document.getElementById('titlepage');
const contentTitle = titleTag.textContent;

const toWatchTitle = document.getElementById('watch');
titleTag.textContent = contentTitle+" "+toWatchTitle.getAttribute('data');