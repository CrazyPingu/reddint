function asyncCall(fileName, callback = () => { }, args = null, contentType = 'application/x-www-form-urlencoded') {
    let jsonArgs = null;
    if(args != null){
        let name = args.split('=')[0];
        let value = args.split('=')[1];
        jsonArgs = JSON.stringify({ [name] : value });
    }
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            callback(this.responseText);
        }
    };
    xhttp.open('POST', 'requests/' + fileName, true);
    xhttp.setRequestHeader('Content-type', contentType);
    xhttp.send('args=' + jsonArgs);
}

export default asyncCall;