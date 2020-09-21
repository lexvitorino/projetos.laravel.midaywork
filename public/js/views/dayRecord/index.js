$(function () {
    $('.time').mask('00:00:00');
    $("form").submit(function (e) {
        event.preventDefault();
        swal({
            title: "Confirma apontamento?",
            text: "",
            icon: "warning",
            buttons: ["Não", "Sim"],
        }).then((result) => {
            if (result) {
                $.blockUI({
                    message: "",
                });
                $.ajax({
                    url: $(this).attr("action"),
                    type: $(this).attr("method"),
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response) {
                            if (response.msg) {
                                swal(response.msg, {
                                    icon: "success",
                                });
                            }
                            updateInfo(response);
                            getWorkResume();
                        }
                        $.unblockUI();
                    },
                    error: function (error) {
                        $.unblockUI();

                        if (error && error.responseJSON) {
                            var response = error.responseJSON;
                            if (response.msg) {
                                swal(response.msg);
                            }
                        }
                    },
                });
            } else {
                /* */
            }
        });
    });
});

function updateInfo(response) {
    if (response.workingHours) {
        var workDate = response.workingHours.work_date;
        var currentDate = new Date();
        var month = currentDate.getMonth() + 1;
        month = month < 10 ? '0' + month : month;
        var day = currentDate.getDate();
        day = day < 10 ? '0' + day : day;
        currentDate = currentDate.getFullYear() + "-" + month + "-" + day;
        if (currentDate === workDate) {
            var keys = Object.keys(response.workingHours);
            for (var i = 0; i < keys.length; i++) {
                if (response.workingHours[keys[i]] !== null) {
                    $("#" + keys[i]).text(response.workingHours[keys[i]]);
                }
            }
        }
    }
    if (response.today) {
        /* */
    }
}

function getWorkResume(){
    $.blockUI({
        message: "",
    });
    $.ajax({
        url: window.workResume,
        type: 'GET',
        success: function (response) {
            $('#sidebar-widgets').html(response);
            activateClock();
            $.unblockUI();
            console.log(response);
        },
        error: function (error) {
            $.unblockUI();
        },
    });
}
