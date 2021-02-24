(function () {
    const menuToggle = document.querySelector(".menu-toggle");
    menuToggle.onclick = function (e) {
        const body = document.querySelector("body");
        body.classList.toggle("hide-sidebar");
    };
})();

function activateClock() {
    const activeClock = document.querySelector("[active-clock]");
    if (!activeClock) return;

    function addOneSecond(hours, minutes, seconds) {
        const d = new Date();
        d.setHours(parseInt(hours));
        d.setMinutes(parseInt(minutes));
        d.setSeconds(parseInt(seconds) + 1);

        const h = `${d.getHours()}`.padStart(2, 0);
        const m = `${d.getMinutes()}`.padStart(2, 0);
        const s = `${d.getSeconds()}`.padStart(2, 0);

        return `${h}:${m}:${s}`;
    }

    setInterval(function () {
        // '07:27:19' => ['07', '27', '19']
        const parts = activeClock.innerHTML.split(":");
        activeClock.innerHTML = addOneSecond(...parts);
    }, 1000);
}

activateClock();

$(function () {
    $(".time").mask("00:00:00");
    $("form.dayRecord").submit(function (e) {
        e.preventDefault();
        swal({
            title: "Confirma apontamento1?",
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
        month = month < 10 ? "0" + month : month;
        var day = currentDate.getDate();
        day = day < 10 ? "0" + day : day;
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

function getWorkResume() {
    $.blockUI({
        message: "",
    });
    $.ajax({
        url: window.workResume,
        type: "GET",
        success: function (response) {
            $("#sidebar-widgets").html(response);
            activateClock();
            $.unblockUI();
        },
        error: function (error) {
            $.unblockUI();
        },
    });
}

function updateTableMonthlyReport(index, response) {
    $("tr[data-index='" + index + "'] td[data-time='1']").html(
        response["time1"]
    );
    $("tr[data-index='" + index + "'] td[data-time='2']").html(
        response["time2"]
    );
    $("tr[data-index='" + index + "'] td[data-time='3']").html(
        response["time3"]
    );
    $("tr[data-index='" + index + "'] td[data-time='4']").html(
        response["time4"]
    );
    $("tr[data-index='" + index + "'] td[data-time='5']").html(
        response["time5"]
    );
    $("tr[data-index='" + index + "'] td[data-time='6']").html(
        response["time6"]
    );
}

$(function () {
    $("form.recalculate").submit(function (event) {
        event.preventDefault();
        var _this = $(this);
        swal({
            title:
                _this.attr("data-action") === "only"
                    ? "Recalculate balance?"
                    : "Recalculate every day?",
            text: "",
            icon: "warning",
            buttons: ["Não", "Sim"],
        }).then((result) => {
            if (result) {
                $.blockUI({
                    message: "",
                });

                var send;
                if (_this.attr("data-action") === "only") {
                    var formId = _this.attr("data-id");
                    var user = $("select[name='user']").val();
                    var period = $("select[name='period']").val();
                    var action = $(
                        "form[data-id='" + formId + "'] input[name='action']"
                    ).val();
                    var id = $(
                        "form[data-id='" + formId + "'] input[name='id']"
                    ).val();
                    var index = $(
                        "form[data-id='" + formId + "'] input[name='index']"
                    ).val();
                    var token = $(
                        "form[data-id='" + formId + "'] input[name='_token']"
                    ).val();
                    send = {
                        user: user,
                        period: period,
                        action: action,
                        id: id,
                        _token: token,
                        index: index,
                    };
                } else if (_this.attr("data-action") === "all") {
                    var action = $(
                        "form[data-action='all'] input[name='action']"
                    ).val();
                    var token = $(
                        "form[data-action='all'] input[name='_token']"
                    ).val();
                    var user = $("select[name='user']").val();
                    var period = $("select[name='period']").val();
                    send = {
                        user: user,
                        period: period,
                        action: action,
                        _token: token,
                    };
                }

                console.log(
                    " user " +
                        user +
                        " period " +
                        period +
                        " action " +
                        action +
                        " id " +
                        id +
                        " index " +
                        index
                );
                $.ajax({
                    url: _this.attr("action"),
                    type: "PUT",
                    data: send,
                    success: function (response) {
                        if (response) {
                            if (Array.isArray(response) === true) {
                                console.log(response);
                                for (var i = 0; i < response.length; i++) {
                                    if (response[i].worked_time === 0) {
                                        continue;
                                    }
                                    updateTableMonthlyReport(i, response[i]);
                                }
                            } else {
                                updateTableMonthlyReport(formId, response);
                            }
                        }
                        $.unblockUI();
                        return;
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
