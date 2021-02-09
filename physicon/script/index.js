console.log('blank');
// Получение данных
function getData(){
  let url = 'https://krapipl.imumk.ru:8443/api/mobilev1/update';
  let formData = new FormData();
  formData.set('data', '');
  fetch(url, {
    method: 'POST',
    // headers: { 'Content-Type': 'text/plain;charset=utf-8' },
    body: formData
  }).then(response => response.json()).then((response)=>{
    // обрабатываем полученные данные
    // console.log(response);
    // формируем карточки товаров
    let data = response.items;
    // console.log(data);
    // console.log(data.length);
    let cardsContainerEl = document.getElementsByClassName('cards')[0];
    cardsContainerEl.innerHTML = '';
    for (let i = 0; i < data.length; i++) {
      console.log(data[i]);
      // создаем элементы
      let cardDiv = document.createElement('div');
      cardDiv.classList.add('card');
      cardDiv.dataInfo = data[i];
      let imageDiv = document.createElement('div');
      imageDiv.classList.add('image');
      let imageImg = document.createElement('img');
      imageImg.src = 'image/115.jpg';
      imageImg.alt = 'demo';
      let infoDiv = document.createElement('div');
      infoDiv.classList.add('info');
      let title = document.createElement('p');
      title.classList.add('title');
      title.innerHTML = data[i].title.substring(0, 15);
      title.title = data[i].title;
      let grade = document.createElement('p');
      grade.classList.add('grade');
      grade.innerHTML = 'Класс: ' + data[i].grade;
      let genre = document.createElement('p');
      genre.classList.add('genre');
      genre.innerHTML = data[i].genre;
      let meta = document.createElement('p');
      meta.classList.add('meta');
      meta.innerHTML = '<a href="'+ data[i].shopUrl +'">Подробнее</a></p>';
      let price = document.createElement('button');
      price.classList.add('btn-blue');
      price.innerHTML = data[i].price + ' руб.';
      // компануем элементы в карточку
      infoDiv.append(title);
      infoDiv.append(grade);
      infoDiv.append(genre);
      infoDiv.append(meta);
      infoDiv.append(price);
      imageDiv.append(imageImg);
      cardDiv.append(imageDiv);
      cardDiv.append(infoDiv);
      // добавляем карточку к контейнеру
      // console.log(cardDiv);
      cardsContainerEl.append(cardDiv);
    }
  });
}
document.addEventListener("DOMContentLoaded", getData);

// прокрутка
function scroll(){
  let scrollHeight = Math.max(
  document.body.scrollHeight, document.documentElement.scrollHeight,
  document.body.offsetHeight, document.documentElement.offsetHeight,
  document.body.clientHeight, document.documentElement.clientHeight
  );
  let totalSrcoll = scrollHeight - window.innerHeight;
  let scrollStep = totalSrcoll / 100;
  scrollBarEl[0].children[0].style.width = (window.pageYOffset / scrollStep) +'%';
}
let scrollBarEl = document.getElementsByClassName('header-scrollbar');
document.addEventListener("scroll",  function(){scroll(this)}, false);

// форма поиска
function formAction(el){
  console.log('что то сделали в форме посика');
  console.log('Элемент нажатия: ', el);
  console.log('Эвент: ', event);
  console.log('Эвент таргет: ', event.target);
}
let formEl = document.getElementById('search-form');
let subjectEl = document.getElementById('subject');
let genreEl = document.getElementById('genre');
let gradeEl = document.getElementById('grade');
let inputEl = document.getElementById('search-input');
let buttonEl = document.getElementById('search-button');
formEl.addEventListener("click",  function(){formAction(this)});
