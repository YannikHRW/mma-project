
const quantitySelectsCart = document.getElementsByClassName('quantity-select-cart');
const prices = document.getElementsByClassName('product-price');
const form = document.getElementsByClassName('shopping-cart-form');

/**
 * Updates the quantity by every change in the shopping cart.
 */
for (let i = 0; i < prices.length; i++) {
    quantitySelectsCart[i].onchange = () => {
        form[i].submit();
    }
}


const ratingScales = document.getElementsByClassName('changeable-rating-scale');

/**
 * Makes the star scale interactive.
 */
for (const ratingScale of ratingScales) {

    let rating = 0;
    for (let i = 0; i < ratingScale.children.length; i++) {

        /**
         * Safes the rating and empties all stars.
         */
        ratingScale.onmouseenter = () => {
            for (let j = 0; j < 5; j++) {
                if (ratingScale.children[j].classList.contains("checked")) {
                    rating += 1;
                }
                ratingScale.children[j].classList.remove("checked");
            }
        }

        /**
         * Refills all stars according to the original rating.
         */
        ratingScale.onmouseleave = () => {
            for (let j = 0; j < rating; j++) {
                ratingScale.children[j].classList.add("checked");
            }
            rating = 0;
        }

        /**
         * Fills all stars to the left of the selected one.
         */
        ratingScale.children[i].onmouseenter = () => {
            for (let j = i; j >= 0; j--) {
                ratingScale.children[j].classList.add("checked");
            }
        }

        /**
         * Removes all stars to the left of the selected one.
         */
        ratingScale.children[i].onmouseleave = () => {
            for (let j = i; j >= 0; j--) {
                ratingScale.children[j].classList.remove("checked");
            }
        }

        /**
         * Safes the selected star-value in the form.
         */
        ratingScale.children[i].onclick = () => {
            rating = i + 1;
            ratingScale.previousElementSibling.attributes['value'].value = i + 1;
        }

    }
}


const articles = document.getElementsByClassName('article-box');

/**
 * Maps the HTML collection to a string array, since "articles" is also updated in HTML after deletion.
 *
 * @type {*[]}
 */
let articlesString = [];
for (const article of articles) {
    articlesString.push(article.innerHTML);
}

let articlesSearchableArray = buildArticlesSearchableArray(articles);

/**
 * Builds an Array of the searchable articles.
 *
 * @param articles as HTMLCollection
 * @returns {*[]}
 */
function buildArticlesSearchableArray(articles) {
    let articlesSearchableArray = [];
    for (const article of articles) {
        articlesSearchableArray.push(article);
    }
    return articlesSearchableArray;
}


/**
 * If the input changes, the current value in #search-article-input is written in value and changes the article rows.
 */
let searchArticleInputs = document.getElementsByClassName('search-article-input');
for (const searchArticleInput of searchArticleInputs) {
    searchArticleInput.addEventListener('input', () => {
        let value = searchArticleInput.value.toLowerCase();
        let filteredArticlesSearchableArray = buildFilteredArticlesSearchableArray(articlesSearchableArray, value);
        buildArticleRows(filteredArticlesSearchableArray, articlesSearchableArray);
    })
}


/**
 * Builds an Array of all articles that matches to the search input.
 *
 * @param articlesSearchableArray Array of product titles.
 * @param value search Value
 * @returns {*[]}
 */
function buildFilteredArticlesSearchableArray(articlesSearchableArray, value) {
    let filteredArticlesSearchableArray = [];
    for (const article of articlesSearchableArray) {
        for (const articleChild of article.children) {
            let content;
            if (articleChild.classList.contains("searchable")) {
                content = articleChild.innerHTML.toLowerCase();
            } else {
                content = "";
            }
            if (content.includes(value)) {
                filteredArticlesSearchableArray.push(article);
            }
        }
    }
    return filteredArticlesSearchableArray;
}

/**
 * Builds the new article row and insert it into the HTML-File.
 *
 * @param filteredArticlesSearchableArray
 * @param articlesSearchableArray
 */
function buildArticleRows(filteredArticlesSearchableArray, articlesSearchableArray) {

    let articleRows = document.getElementById("article-rows");
    let articleRowsTempString = "";
    let colCounter = 0;

    for (let i = 0; i < articlesSearchableArray.length; i++) {

        if (filteredArticlesSearchableArray.includes(articlesSearchableArray[i])){

            if (colCounter === 0) {
                articleRowsTempString += "<div class='row'>";
            }
            articleRowsTempString +=        "<div class='col-2'>";
            articleRowsTempString +=            "<article class='article-box'>";
            articleRowsTempString +=                articlesString[i];
            articleRowsTempString +=            "</article>";
            articleRowsTempString +=        "</div>";

            colCounter++;

        }
        if (colCounter === 3) {
            articleRowsTempString +=     "</div>";
            colCounter = 0;
        }
    }
    if (colCounter !== 0) {
        articleRowsTempString +=         "</div>";
    }
    articleRows.innerHTML = "";
    articleRows.innerHTML = articleRowsTempString;
}


/**
 * Sets the color of the selected nav-element.
 * @type {HTMLCollectionOf<Element>}
 */
/*function setNavEleActive(className) {
    let navElements = document.getElementsByClassName(className);
    for (const navElement of navElements) {
        navElement.classList.add("active");
    }
}*/

/**
 * makes the searchbar invisible.
 * @type {HTMLCollectionOf<Element>}
 */
/*
function setSearchBarInvis(className) {
    let searchInputs = document.getElementsByClassName(className);
    for (const searchInput of searchInputs) {
        searchInput.classList.add("invisible");
    }
}*/
