var form = document.getElementById('questionList');

form.addEventListener('change', function() {
    var btn = document.querySelector('[type=submit]');
    //btn.setAttribute("disabled", "false");
    btn.disabled = false;
    alert();
}, false);

$('.radioButtons').onclick(function (e) {
        alert('d');
})