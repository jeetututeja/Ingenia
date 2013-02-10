var beforeDay = ["Feliz","Buen","Sí, es","Whooa,","Yajúa,","Bon","Yay,","Es","Yuju, es"];
var coeficient = beforeDay.length - 1;
var randomBeforeDay = Math.round(Math.random()*(coeficient));
var dayNames = ["domingo","lunes","martes","miércoles","jueves","viernes","sábado"];
var now = new Date();
document.write(beforeDay[randomBeforeDay] + " " + dayNames[now.getDay()] + ",");