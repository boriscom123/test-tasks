let cardsContainerEl = document.getElementsByClassName('cards')[0];

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
    render(response.items);
    cardsContainerEl.itemsList = response.items;
  });
}
document.addEventListener('DOMContentLoaded', getData);

// отрисовка карточек
function render(data){
  cardsContainerEl.innerHTML = '';
  let items = data;
  for (let i = 0; i < data.length; i++) {
    // console.log(data[i]);
    // создаем элементы
    let cardDiv = document.createElement('div');
    cardDiv.classList.add('card');
    cardDiv.dataInfo = data[i];
    let imageDiv = document.createElement('div');
    imageDiv.classList.add('image');
    let imageImg = document.createElement('img');
    // выбираем изображение в зависимости от направления
    switch (data[i].subject) {
      case 'Алгебра':  imageImg.src = 'image/card-alg.jpg'; break;
      case 'Английский язык':  imageImg.src = 'image/card-eng.jpg'; break;
      case 'Биология':  imageImg.src = 'image/card-bio.jpg'; break;
      case 'География':  imageImg.src = 'image/card-geo.jpg'; break;
      case 'Геометрия':  imageImg.src = 'image/card-geom.jpg'; break;
      case 'Демо-версия':  imageImg.src = 'image/card-demo.jpg'; break;
      case 'Информатика':  imageImg.src = 'image/card-it.jpg'; break;
      case 'История':  imageImg.src = 'image/card-history.jpg'; break;
      case 'Литература':  imageImg.src = 'image/card-lit.jpg'; break;
      case 'Математика':  imageImg.src = 'image/card-mat.jpg'; break;
      case 'Обществознание':  imageImg.src = 'image/card-obsch.jpg'; break;
      case 'Окружающий мир':  imageImg.src = 'image/card-mir.jpg'; break;
      case 'Робототехника':  imageImg.src = 'image/card-robo.jpg'; break;
      case 'Русский язык':  imageImg.src = 'image/card-rus.jpg'; break;
      case 'Физика':  imageImg.src = 'image/card-phy.jpg'; break;
      case 'Химия':  imageImg.src = 'image/card-ch.jpg'; break;
      default: imageImg.src = 'image/115.jpg';
    }
    // imageImg.src = 'image/115.jpg';
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
    // выбираем цену в зависимости от выбора
    if(priceViewEl.children[0].classList.contains('active')){
      // RUB
      price.innerHTML = data[i].price + ' руб.';
    } else {
      // BONUS
      price.innerHTML = data[i].priceBonus + ' бонусов';
    }
    price.classList.add('btn-blue');

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
}

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
document.addEventListener('scroll',  function(){scroll(this)});

// burger
function showMenu(el){
  if(checkboxEl.checked) {
      checkboxEl.checked = false;
  } else {
    checkboxEl.checked = true;
  }
}
let checkboxEl = document.getElementById('header-burger');
checkboxEl.nextElementSibling.addEventListener('click', function (){showMenu(this)});
// форма поиска
function formAction(el){
  if(event.key == 'Enter') {
    event.preventDefault();
    selectChange();
  }
}
function selectChange(el){
  let data = [...cardsContainerEl.itemsList];
  if(subjectEl.value != ''){
    data = data.filter( (obj) => { return obj.subject == subjectEl.value; });
  }
  if(genreEl.value != ''){
    data = data.filter( (obj) => { return obj.genre == genreEl.value; });
  }
  if(gradeEl.value != ''){
    regexpVal = '(^'+ gradeEl.value +'{1}$)|(^'+ gradeEl.value +'{1}\;)|(\;'+ gradeEl.value +'{1}\;)|(\;'+ gradeEl.value +'{1}$)';
    let regexp = new RegExp(regexpVal);
    data = data.filter( (obj) => { return regexp.exec(obj.grade); });
  }
  if(inputEl.value != ''){
    data = data.filter( (obj) => { return obj.title.includes(inputEl.value); });
  }
  render(data);
}
function changePriceView(el){
  if(!event.target.classList.contains('active')){
    el.children[0].classList.toggle('active');
    el.children[1].classList.toggle('active');
    selectChange();
  }
}
let formEl = document.getElementById('search-form');
let subjectEl = document.getElementById('subject');
let genreEl = document.getElementById('genre');
let gradeEl = document.getElementById('grade');
let inputEl = document.getElementById('search-input');
let buttonEl = document.getElementById('search-button');
let priceViewEl = document.getElementById('price-view');
subjectEl.addEventListener('change',  function(){selectChange(this)});
genreEl.addEventListener('change',  function(){selectChange(this)});
gradeEl.addEventListener('change',  function(){selectChange(this)});
inputEl.addEventListener('change',  function(){selectChange(this)});
buttonEl.addEventListener('click',  function(){selectChange(this)});
formEl.addEventListener('keydown',  function(){formAction(this)});
priceViewEl.addEventListener('click',  function(){changePriceView(this)});
