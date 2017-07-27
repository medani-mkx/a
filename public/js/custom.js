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
        // Icon
        var status = mwdToggleIcon(taskId);
        // Editable
        mwdToggleEditable(taskId, status);
        // Style
        if(status === 'editable') {
            $('#mwd-task-' + taskId).css({"border-color": "#99d6ff",
                "border-width":"2px",
                "border-style":"solid"});
        }
        else if(status === 'saved') {
            $('#mwd-task-' + taskId).css({"border": "none"});
        }
        // Save to DB
        if(status === 'saved') {
            mwdAjaxUpdate(taskId);
        }
    }

    function mwdAjaxUpdate(taskId) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: 'POST',
            url: '/benutzer/' + userId + '/einsatzstelle/' + branchId,
            data: '[]',
            dataType: 'json',
            success: function (data) {
                var branch = data;
                var html =
                    '<div class="input-group mwd-user-branches mwd-single-user-branch" data-branch-id="' + branchId + '" data-branch=\'' + JSON.stringify(branch) + '\'>' +
                    '<div class="input-group-btn">' +
                    '<button type="button" class="btn btn-danger mwd-delete-user-branch">' +
                    '<span class="glyphicon glyphicon-minus"></span>' +
                    '</button>' +
                    '<button style="white-space: normal;" type="button" class="btn btn-default btn-detail open-modal">' +
                    branch.address_line_1 + ', ' + branch.zip_code + ' ' + branch.city + ' &#149; ' + branch.name + (branch.name_2 != '' ? '&#149; ' + branch.name_2 : '') +
                    '</button>' +
                    '</div>' +
                    '</div>';
                $('.row[data-user-id="' + userId + '"]').find('.mwd-user-branches-list').append(html);
                $('.row[data-user-id="' + userId + '"]').find('.text-muted').remove();
                initDeleteUserBranch();
            },
            error: function (req, status, err) {
                console.log('Something went wrong', status, err);
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

    function mwdToggleEditable(taskId, status) {
        var editableElements = $('#mwd-task-' + taskId + ' .mwd-editable');
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
});