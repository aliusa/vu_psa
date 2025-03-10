class CmsDatepicker {
    static init($context = null){
        var inputSelector = "[type='date']";
        let $inputs = $context ? $context.find(inputSelector) : $(inputSelector);
        $inputs.each(function (index, element) {
            if($(element).data('datepicker_instance')) return;
            $(element).data('datepicker_instance', new CmsDatepicker($(element).data('options'), element))
        });

        var inputDateTimeSelector = "[type='datetime-local']";
        let $datetimeInputs = $context ? $context.find(inputDateTimeSelector) : $(inputDateTimeSelector);
        $datetimeInputs.each(function (index, element) {
            if($(element).data('datepicker_instance')) return;
            $(element).data('datepicker_instance', new CmsDatepicker({enableTime: true, time_24hr: true, dateFormat: 'Y-m-d H:i'}, element))
        });
    }
    constructor(config, context) {
        let lang = document.documentElement.getAttribute('lang');
        let $input = $(context);

        var c = {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'Y-m-d',
            locale: flatpickr.l10ns[lang] ? flatpickr.l10ns[lang] : flatpickr.l10ns.default,
            minDate: context.min,
            maxDate: context.max
        };
        $.extend(c, config);

        let instance = flatpickr(context, c);


        let dateRange = $input.data('date-range');

        if(dateRange){
            instance.config.onChange.push(function (selectedDates, dateStr, instance) {
                if(dateRange === 'from'){
                    let toInstance = $input.closest('.row').find('[data-date-range="to"]')[0]._flatpickr;
                    toInstance.config.minDate = dateStr;
                }else if(dateRange === 'to'){
                    let toInstance = $input.closest('.row').find('[data-date-range="from"]')[0]._flatpickr;
                    toInstance.config.maxDate = dateStr;
                }
                console.log(dateRange);
            }.bind(this))
        }
        if($input.next('button.clear-datetime').length) {
            $input.next('button.clear-datetime').on('click', function(){
                instance.clear();
            });
        }
    }
}



$(document).ready(function () {
    CmsDatepicker.init()


    $(".autosubmit :input").on('change', function (){
        $(this).closest('.content').find('button.action-saveAndContinue').click();
    })

    //$.datetimepicker.setLocale('en');

    // $('input[datetimepicker="datetimepicker"]').datetimepicker({
    //     format: 'Y-m-d H:i',
    //     lang: 'lt',
    // });
    // $('input[datetimepicker="datepicker"]').datetimepicker({
    //     format: 'Y-m-d',
    //     lang: 'lt',
    // });


    $('.form-tabs [data-bs-toggle="tab"]').on('shown.bs.tab', function (event){
        window.location.hash = event.currentTarget.attributes.href.value.substring(1)
    })

    var currentTab = window.location.hash ? window.location.hash.substring(1) : null;

    //console.log($('.form-tabs [data-bs-toggle="tab"][href="#'+currentTab+'"]'))
    //$('.form-tabs [data-bs-toggle="tab"][href="#'+currentTab+'"]').click()


    var triggerEl = document.querySelector('.form-tabs [data-bs-toggle="tab"][href="#'+currentTab+'"]')
    $(triggerEl).closest('.nav-tabs').find('.nav-link').removeClass('active');
    $(triggerEl).addClass('active');

    $(triggerEl).closest('.form-tabs').find('.tab-pane').removeClass('active');
    $(triggerEl).closest('.form-tabs').find('.tab-pane#' + currentTab).addClass('active');

});
