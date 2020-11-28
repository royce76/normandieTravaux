// target articles cards of project on index.html.twig
let articles = document.getElementsByTagName("article");

for (let article of articles) {
  // target first div contains card
  let divArticle = article.firstElementChild;
  // target the tag h5 to have the name of the project
  let div = divArticle.firstElementChild;
  let nameArticle = div.firstElementChild;
  // create an icon to reduce
  let img = document.createElement("img");
  div.appendChild(img);
  img.src = "https://img.icons8.com/metro/26/000000/minus-2-math.png";
  //by a click, we reduce the article
  img.addEventListener("click", function(){
    divArticle.style.display = "none";
    let divReduceArticle = document.createElement("div");
    article.appendChild(divReduceArticle);
    // we get the project'sname
    let nameReduceArticle = document.createElement("div");
    nameReduceArticle.innerHTML = nameArticle.innerHTML;
    //create a button to open back the project
    let buttonGetArticle = document.createElement("img");
    buttonGetArticle.src = "https://img.icons8.com/material-sharp/24/000000/plus.png";

    divReduceArticle.appendChild(nameReduceArticle);
    divReduceArticle.appendChild(buttonGetArticle);
    divReduceArticle.style.height = "3em";

    divReduceArticle.classList.add("d-flex", "justify-content-between","align-items-center");
    //by a click we open the project back
    buttonGetArticle.addEventListener("click", function(){
      divArticle.style.display = "block";
      divReduceArticle.remove();
    });
  });
}

let status = document.getElementsByClassName("status");
for (var statu of status) {
  if (statu.innerText = "Terminé") {
    let ul = statu.parentElement;
    let divArticle = ul.parentElement;
    let article = divArticle.parentElement;
    article.style.border = "thick solid #0000FF";
  }
  else if (statu.innerText = "En cours") {
    let ul = statu.parentElement;
    let divArticle = ul.parentElement;
    let article = divArticle.parentElement;
    article.style.border = "thick solid #00FF00";
  }
}
