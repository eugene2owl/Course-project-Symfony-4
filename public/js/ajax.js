$(document).ready(function () {

    var btn = document.querySelector('[type=submit]');
    $('.radioButtons').click(function (e) {
        btn.disabled = false;
    });

   $('#questionList').submit(function (e) {
        e.preventDefault();

        var data = $(this).serialize();
        console.log(data);
        $.ajax({
            url: this.action,
            type: "POST",
            data: data,
            dataType: 'json',
            cache: false,
            success:function (response) {

                if (response['correctness']) {
                        swal("Correct!", "", "success")
                } else {
                    swal("Not correct.", "", "warning");
                }

                if (response['toResultLink'] != "") {
                    setTimeout(function () {
                        document.location.href = response['toResultLink'];
                    }, 1000);
                } else {
                    console.log(response['lastQuestion']);

                    $('#questiontext')[0].innerHTML = response['number'] + ') ' + response['nextQuestion'];

                    $('#questionList')[0].action = response['nextQuestionLink'];

                    response['answers'].forEach(function (element, i) {
                        $('#label' + (i + 1).toString())[0].innerHTML = element;
                    });
                }
            },
            error:function () {
                alert('err');
            }
        })
   }
   )
});