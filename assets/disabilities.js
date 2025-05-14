export default class Disabilities {
    constructor() {
        this.init()
    }

    init() {
        var jDisWrap = $('#disabilites_version');

        if (jDisWrap.length) {
            var updateBodyPadding = function () {
                var paddingTop = jDisWrap.height();
                $('body').css('paddingTop', paddingTop + 'px');
            };

            $(window).resize(function () {
                updateBodyPadding();
            });

            updateBodyPadding();

            jDisWrap.on('click', 'a.dis_actions', function () {
                var value_key = $(this).data("key");
                var value = $(this).data("value");
                var imgShowUpdated = false;
                var jBody = $('body');

                if (value_key == "size") {
                    jBody.removeClass('size-1 size-2 size-3');
                    $('#disabilites_version a.size').removeClass("active");
                } else if (value_key == "bg") {
                    jBody.removeClass('bg-white bg-black');
                    $('#disabilites_version a.bg').removeClass("active");
                } else if (value_key == "img") {
                    jBody.removeClass('img-show img-hide');
                    $('#disabilites_version a.img').removeClass("active");
                    imgShowUpdated = true;
                }
                $(this).addClass("active");
                jBody.addClass(value_key + '-' + value);
                $.get('/general/disabilities/action.' + value_key + '-' + value + '/');
            });
        }
    }
}
