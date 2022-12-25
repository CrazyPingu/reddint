function loadInteractions(fileName, contentType = 'application/x-www-form-urlencoded') {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            return this.responseText;
        }
    };
    xhttp.open('POST', 'requests/' + fileName, true);
    xhttp.setRequestHeader('Content-type', contentType);
    xhttp.send();
}

export default loadInteractions;