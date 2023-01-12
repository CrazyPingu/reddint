import asyncRequest from "./default-ajax.js";

const searchBar = document.getElementById('search');
const searchSpace = document.getElementById('searchSpace');

searchBar.addEventListener('keyup', () => {
    searchSpace.innerHTML = '';
    if (searchBar.value.length > 0) {
        asyncRequest('request-search.php', (response) => {
            searchSpace.innerHTML = '';

            if (response.length == 0) {
                const errorDiv = Object.assign(document.createElement('div'), {className: 'search-result'});
                const message = Object.assign(document.createElement('p'), {innerText: 'No results found'});
                errorDiv.appendChild(message);
                searchSpace.appendChild(errorDiv);
                return;
            }

            response.forEach(element => {
                const type = element['type'];
                const resultDiv = Object.assign(document.createElement("div"), {className: 'search-result'});
                const resultImg = Object.assign(document.createElement("img"), {className: 'svg', src: './res/svg/'+type+'.svg', alt: type});
                const resultName = Object.assign(document.createElement("a"), {
                    href: `./${type}.php?${type=='profile'?'username':'community'}=${encodeURIComponent(element['name'])}`,
                    innerText: element['name']
                });

                resultDiv.appendChild(resultImg);
                resultDiv.appendChild(resultName);
                searchSpace.appendChild(resultDiv);
            });
        }, { value: searchBar.value })
    }
});

