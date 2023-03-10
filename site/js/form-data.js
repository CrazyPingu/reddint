// Obtain the data from the form and returns it as an object
function obtainData(formData) {
    let args = {};
    for (let [key, value] of formData.entries()) {
        args[key] = value;
    }
    return args;
}

export default obtainData;