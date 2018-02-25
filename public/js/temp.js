// значение по умолчанию
var defaults = { color:'green' };
var options = {};
// наши публичные методы
var methods = {
    // инициализация плагина
    init:function(params) {
        // актуальные настройки, будут индивидуальными при каждом запуске
        options = $.extend({}, defaults, params);

        return this.click(function(){
            $(this).css('color', options.color);
        });
    },
    // изменение цвета
    color:function(color) {
        $(this).css('color', color);
    },
    // сброс цвета
    reset:function() {
        $(this).css('color', 'black');
    }
};

$.fn.mySimplePlugin = function(method){

    // немного магии
    if ( methods[method] ) {
        // если запрашиваемый метод существует, мы его вызываем
        // все параметры, кроме имени метода прийдут в метод
        // this так же перекочует в метод
        return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
        // если первым параметром идет объект, либо совсем пусто
        // выполняем метод init
        return methods.init.apply( this, arguments );
    }
};

// вызов без параметров - будет вызван init
$('p').mySimplePlugin();
// вызов метода color и передача цвета в качестве параметров
$('p').mySimplePlugin('color', '#FFFF00');
// вызов метода reset
//$('p').mySimplePlugin('reset');

