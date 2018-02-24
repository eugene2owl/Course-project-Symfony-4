$(document).ready(function () {

    $('.radioButtons').click(function () {
        let btn = document.querySelector('[type=submit]');
        btn.disabled = false;
    });

   $('#questionList').submit(function (e) {
        e.preventDefault();

        let data = $(this).serialize();
        $.ajax({
            url: this.action,
            type: "POST",
            data: data,
            dataType: 'json',
            cache: false,
            success:function (response) {
                showCorrectness(response['correctness']);
                if (response['toResultLink'] !== "") {
                    setTimeout(function () {
                        document.location.href = response['toResultLink'];
                    }, 1000);
                } else {
                    blockButton();
                    $('#questionText')[0].innerHTML = response['number'] + ')' + response['nextQuestion'];
                    $('#questionList')[0].action = response['nextQuestionLink'];
                    removeOldAnswers();
                    putNewAnswers(response['answers']);
                    fillInNewAnswers(response['answers']);

                    $('.radioButtons').click(function () {
                        let btn = document.querySelector('[type=submit]');
                        btn.disabled = false;
                    });
                }
            },
        })
   })
});

function showCorrectness(isCorrect) {
    if (isCorrect) {
        swal("Correct!", "", "success")
    } else {
        swal("Not correct.", "", "warning");
    }
}

function blockButton() {
    let btn = document.querySelector('[type=submit]');
    btn.disabled = true;
}

function removeOldAnswers() {
    let oldAnswer = document.getElementsByTagName('li')[0];
    while (typeof(oldAnswer) !== 'undefined') {
        oldAnswer.parentNode.removeChild(oldAnswer);
        oldAnswer = document.getElementsByTagName('li')[0];
    }
}

function putNewAnswers(answers) {
    answers.forEach(function (text, index) {
        let platform = document.getElementsByTagName('ul');
        $(platform).append($('<li>\n' +
            '<input id="' + (index + 1) + '" type="radio" name="select" class="radioButtons" value="' + (index + 1) + '">\n' +
            '<label for="' + (index + 1) + '" id="label' + (index + 1) + '">' + text + '</label>\n' +
            '<div class="check"></div>\n' +
            '</li>'));
    });
}

function fillInNewAnswers(answers) {
    answers.forEach(function (element, i) {
        $('#label' + (i + 1).toString())[0].innerHTML = element;
    });
}
