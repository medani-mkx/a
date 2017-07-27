$(document).ready(function(){

    /* Datepicker */
    $(function() {
        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        $( "#date" ).datepicker({
            monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
            dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
            dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dateFormat: 'DD, dd. MM yy',
            altFormat: 'yy-mm-dd',
            altField: '#alternativeDate',
            minDate: today
        });
    });

    $(".collapse").on("show.bs.collapse hide.bs.collapse", function(){
        var taskId = $(this).attr('id').replace('collapse', '');
        mwdToggleTask(taskId);
    });

    function mwdToggleTask(taskId) {
        var editableElements = $('#mwd-task-' + taskId).find('[data-task]');

        // Icon
        var status = mwdToggleIcon(taskId);
        // Editable
        mwdToggleEditable(editableElements, status);
        // Style
        if(status === 'editable') {
            $('#mwd-task-' + taskId).css({"border-color": "#99d6ff",
                "border-width":"2px",
                "border-style":"solid"});
        }
        else if(status === 'saved') {
            $('#mwd-task-' + taskId).css({"border": "none"});
        }
        // Ajax
        if(status === 'saved') {
            // Collect data
            var data = {};
            data['id'] = taskId;
            editableElements.each( function() {
                var key = $(this).data('task');
                var index = $(this).html();
                data[key] = index;
            });
            // Save to DB
            mwdAjaxUpdate(taskId, data);
        }
    }

    function mwdAjaxUpdate(taskId, data) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: 'PUT',
            url: '/tasks/' + taskId,
            data: data,
            dataType: 'json',
            success: function (data) {
                if(data === 1) {
                    console.log('Ajax-Request erfolgreich');
                }
                else {
                    console.log(data);
                }
            },
            error: function (request, error, message) {
                console.log('Ajax-Fehler:', error, 'Beschreibung:', message);
            }
        });
    }

    function mwdToggleIcon(taskId) {
        var href = '#collapse' + taskId;
        var iconElement = $('span[href="' + href + '"]');
        var saveIcon = 'glyphicon-floppy-save';
        var editIcon = 'glyphicon-edit';

        if( iconElement.hasClass(saveIcon) ) {
            iconElement.removeClass(saveIcon).addClass(editIcon);
            return 'saved';
        }
        else {
            iconElement.removeClass(editIcon).addClass(saveIcon);
            return 'editable';
        }
    }

    function mwdToggleEditable(editableElements, status) {
        if(status === 'editable') {
            editableElements.bind('mouseenter',
                function(){
                    $(this).attr('contentEditable',true);
                }
            );
        }
        else if(status === 'saved') {
            editableElements.bind('mouseenter',
                function(){
                    $(this).attr('contentEditable',false);
                }
            );
        }
    }

    /****************************************** not in use ************************************************/
    /* Open modals */
    $('[data-create]').click(function() {
//        var model = $(this).data('create');

        $('.modal').modal('show');
    });

    /* Toggle offers depending on offer status selection */
    $('.mwd-offer-statuses li').click(function(e) {
        e.preventDefault();

        var statuses = $('.mwd-offer-statuses').children();

        [].forEach.call(statuses, function(el) {
            el.classList.remove('active');
        });

        $(this).addClass('active');

        var datas = $('.mwd-offer-data').children();

        [].forEach.call(datas, function(el) {
            el.classList.add('hidden')
        });

        var selectedStatus = $(this).data('status');

        $('#' + selectedStatus).removeClass('hidden');
    });

    // $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    // });
});

// $(document).ready(function() {
//     needToConfirm = false;
//     window.onbeforeunload = askConfirm;
// });
//
// function askConfirm() {
//     if (needToConfirm) {
//         // Put your custom message here
//         return "Your unsaved data will be lost.";
//     }
// }
//
// $("select,input,textarea").change(function() {
//     needToConfirm = true;
// });


// var el = document.getElementById('myCoolForm');
//
// el.addEventListener('submit', function(){
//     return confirm('Are you sure you want to submit this form?');
// }, false);