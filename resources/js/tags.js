let spanPreview = document.getElementById('showResult');
let inputNameTag = document.getElementById('name');
let inputbackgroundColor = document.getElementById('background_color');
let inputTextColor = document.getElementById('text_color');

window.addEventListener('DOMContentLoaded', (event) => {
    spanPreview.textContent = inputNameTag.value;
    spanPreview.style.background = inputbackgroundColor.value;
    spanPreview.style.color = inputTextColor.value;
    spanPreview.style.borderRadius = "5em";
    spanPreview.style.padding = "0.5em";
// method
});

// event listener invoke when something change in input
inputNameTag.addEventListener('input', updateValue);
inputbackgroundColor.addEventListener('input', updateValue);
inputTextColor.addEventListener('input', updateValue);
function updateValue(e) {
    spanPreview.textContent = inputNameTag.value;
    spanPreview.style.background = inputbackgroundColor.value;
    spanPreview.style.color = inputTextColor.value;
    spanPreview.style.borderRadius = "5em";
    spanPreview.style.padding = "0.5em";
}
