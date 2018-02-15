var form = document.getElementById('questionList');

form.addEventListener('change', function() {
    var btn = document.querySelector('[type=submit]');
    btn.disabled = false;
}, false);



