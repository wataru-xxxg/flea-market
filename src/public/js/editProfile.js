const fileSelect = document.getElementById("image-select-button");
const fileElem = document.getElementById("hidden-input");

fileSelect.addEventListener(
    "click",
    (e) => {
        if (fileElem) {
            fileElem.click();
        }
    },
    false
);

function preview(obj, previewId) {
    let fileReader = new FileReader();
    fileReader.onload = function () {
        document.getElementById(previewId).src = fileReader.result;
    };
    fileReader.readAsDataURL(obj.files[0]);
}
