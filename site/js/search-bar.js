import asyncRequest from "./default-ajax.js";

const searchBar = document.getElementById('search');
const searchSpace = document.getElementById('searchSpace');

searchBar.addEventListener('keyup', () => {
    searchSpace.innerHTML = '';
    if (searchBar.value.length > 0) {
        asyncRequest('request-search.php', (response) => {
            searchSpace.innerHTML = '';
            response['user'].forEach(element => {
                searchSpace.appendChild(
                    Object.assign(document.createElement('a'),
                        {
                            href: `./profile.php?username=${encodeURIComponent(element['username'])}`,
                            innerText: element['username']
                        }));
                searchSpace.appendChild(document.createElement('br'));
            });
            response['communities'].forEach(element => {
                searchSpace.appendChild(
                    Object.assign(document.createElement('a'),
                    {
                        href: `./community.php?name=${encodeURIComponent(element['name'])}`,
                        innerText: element['name']
                    }));
                searchSpace.appendChild(document.createElement('br'));
            });
        }, { value: searchBar.value })
    }
});

