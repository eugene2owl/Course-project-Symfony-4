$(document).ready(function () {
   $('#questionList').submit(function (e) {
        //e.preventDefault();

        var data = $(this).serialize();
        console.log(data);
        $.ajax({
            url: this.action,
            type: "POST",
            data: data,
            dataType: 'html',
            cache: false,
            success:function (response) {
                showTruth(data);
                //alert(data);
            },
            error:function () {
                alert('err');
            }
        })
   }
   )
});

function showTruth(answerText) {
    var radioArray = document.getElementsByClassName('radioButtons');
    var currentButtonId = 0;
    var currentButtonValue = "";
    console.log(radioArray.length);
    for (var radioNumber = 0; radioNumber < radioArray.length; radioNumber++) {
        currentButtonId = radioArray[radioNumber].id;
        currentButtonValue = radioArray[radioNumber].value;
        console.log(currentButtonValue);
        if ($('#' + currentButtonId).data('is-true')) {
            console.log("true");
        } else {
            console.log("false");
        }
        answerText = convertSerializedToString(answerText);
        console.log("answerText =" + answerText);
        console.log("currentButtonValue =" + currentButtonValue);

        if (answerText == currentButtonValue) {
            if ($('#' + currentButtonId).data('is-true')) {
                alert("true");
            } else {
                alert("false");
            }
        }
    }

    function convertSerializedToString(answerText)
    {
        answerText = answerText.split('%20').join(' ');
        answerText = answerText.split('%40').join('@');
        answerText = answerText.split('%23').join('#');
        answerText = answerText.split('%24').join('$');
        answerText = answerText.split('%25').join('%');
        answerText = answerText.split('%5E').join('^');
        answerText = answerText.split('%26').join('&');
        answerText = answerText.split('%2B').join('+');
        answerText = answerText.split('%5C').join('\\');
        answerText = answerText.split('%7C').join('|');
        answerText = answerText.split('%2F').join('/');
        answerText = answerText.split('%2C').join(',');
        answerText = answerText.split('%3F').join('?');
        answerText = answerText.replace("select=", "");
        return answerText;
    }
}