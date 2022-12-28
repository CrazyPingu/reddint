function asyncRequest(fileName, callback = () => { }, args = null, contentType = 'application/x-www-form-urlencoded') {
    let jsonArgs = args ? JSON.stringify(args) : null;
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            callback(this.responseText);
        }
    };
    xhttp.open('POST', 'requests/' + fileName, true);
    xhttp.setRequestHeader('Content-type', contentType);
    xhttp.send(jsonArgs ? 'args=' + jsonArgs : '');
}

export default asyncRequest;