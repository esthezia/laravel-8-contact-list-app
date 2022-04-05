$(function () {
    $("#delete-confirmation").on("show.bs.modal", function(e) {
        $(this).find(".btn-success").attr("href", $(e.relatedTarget).attr("href"));
    });

    // just to be sure nothing is left behind
    $("#delete-confirmation").on("hide.bs.modal", function(e) {
        $(this).find(".btn-success").attr("href", "#");
    });

    // set contacts' visibility
    (function () {
        var container = $("#contacts-visibility"),
            field = container.find("input"),
            loading = container.find(".js-loading");

        if (!container.length) {
            return;
        }

        field.on("change", function () {
            loading.removeClass("invisible");
            field.prop("disabled", true);

            $.ajax({
                "url": container.data("url"),
                "method": "POST",
                "headers": {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                },
                "cache": false,
                "data": {
                    "isPublic": +field.prop("checked")
                },
                "complete": function () {
                    loading.addClass("invisible");
                    field.prop("disabled", false);
                },
                "error": function (jqXHR, textStatus, errorThrown) {
                    // return the checkbox to its previous state
                    field.prop("checked", !field.prop("checked"));

                    alert("There was an error:\n\n" + textStatus);
                },
                "success": function (data, textStatus, jqXHR) {
                    if (data !== "1") {
                        // return the checkbox to its previous state
                        field.prop("checked", !field.prop("checked"));

                        alert("There was an error! Please try again. If the error persists, please contact us.");
                    }
                }
            });
        });
    }());
});
