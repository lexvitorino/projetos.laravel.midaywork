(function() {
    const menuToggle = document.querySelector(".menu-toggle");
    menuToggle.onclick = function(e) {
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

    setInterval(function() {
        // '07:27:19' => ['07', '27', '19']
        const parts = activeClock.innerHTML.split(":");
        activeClock.innerHTML = addOneSecond(...parts);
    }, 1000);
}

activateClock();

$(function () {
    $('.time').mask('00:00:00');
    $("form.dayRecord").submit(function (e) {
        e.preventDefault();
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
        },
        error: function (error) {
            $.unblockUI();
        },
    });
}
