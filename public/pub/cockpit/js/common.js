$(document).ready(function() {
    // -------------------------------------------------------------------------
    // Admin Template Related JavaScript section.
    // -------------------------------------------------------------------------
    
    // initialize the sortable tables.
    $(".tablesorter").tablesorter();
    
    //When page loads...
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content

    //On Click Event
    $("ul.tabs li").click(function() {
        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content

        var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
        $(activeTab).fadeIn(); //Fade in the active ID content
        return false;
    });
    
    //$('.column').equalHeight();
    $('.column').height($(document).height());
    
    // -------------------------------------------------------------------------
    // News Controller Related JavaScript section.
    // -------------------------------------------------------------------------
    $('#photo').fileupload({
        dataType: 'json',
        url: getUrl("/gorsel-yukle?format=json"),
        done: function (e, o) {
            $("#uploadFileInputWrapper").hide();
            $("#uploadFileInputWrapper").after(
                $("<p>").attr("id", "uploadedPhotoWrapper").append(
                    $("<img />").attr("id", "orgPhoto").attr("src", getUrl("/../pub/img/news/" + o.result.data[0])),
                    $("<input>").attr("type", "hidden").attr("name", "photoURL").attr("value", o.result.data[0]),
                    $("<input>").attr("type", "hidden").attr("id", "x1").attr("name", "x1").attr("value", 0),
                    $("<input>").attr("type", "hidden").attr("id", "y1").attr("name", "y1").attr("value", 0),
                    $("<input>").attr("type", "hidden").attr("id", "w").attr("name", "w").attr("value", $("img#orgPhoto").width()),
                    $("<input>").attr("type", "hidden").attr("id", "h").attr("name", "h").attr("value", $("img#orgPhoto").height()),
                    $("<a>").attr("class", "do").text("Tamam").click(function() {
                        $.post(getUrl("/gorsel-duzenle?format=json"), {
                            'photoURL': o.result.data[0],
                            'x1': $("input#x1").val(),
                            'y1': $("input#y1").val(),
                            'w': $("input#w").val(),
                            'h': $("input#h").val()
                        }, function(o2) {
                            $("#uploadedPhotoWrapper").remove();
                            $("#uploadFileInputWrapper").after(
                                $("<p>").attr("id", "uploadedPhotoWrapper").append(
                                    $("<img />").attr("id", "croppedPhoto").attr("src", getUrl("/../pub/img/news/" + o2.data)),
                                    $("<input />").attr("type", "hidden").attr("name", "photoURL").val(o.result.data[0])
                                )
                            );
                        });
                        
                        return false;
                    }),
                    $("<a>").attr("class", "undo").text("Cancel").click(function() {
                        $("#uploadedPhotoWrapper").remove();
                        $("#uploadFileInputWrapper").show();
                        return false;
                    })
                )
            );

            // first settings
            $("#x1").val(0);
            $("#y1").val(0);
            $("#w").val(640);
            $("#h").val(480);
            $("img#orgPhoto").Jcrop({
                aspectRatio: 1,
                minSize: [130, 130],
                maxSize: [$("img#orgPhoto").width(), $("img#orgPhoto").height()],
                setSelect: [0, 0, 130, 130],
                onSelect: function (data) {
                    $("#x1").val(data.x);
                    $("#y1").val(data.y);
                    $("#w").val(data.w);
                    $("#h").val(data.h);
                }
            });
        }
    });
    
    // build datetime plugins.
    $(".datetime").datetimepicker({
        ampm: false,
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss",
        monthNames: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
        monthNamesShort: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'],
        monthNamesMin: ['Oc', 'Şu', 'Mr', 'Ns', 'My', 'Hz', 'Tm', 'Ağ', 'Ey', 'Ek', 'Ka', 'Ar'],
        dayNames: ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'],
        dayNamesShort: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
        dayNamesMin: ['Pt', 'Sa', 'Çr', 'Pr', 'Cm', 'Ct', 'Pz'],
        timeOnlyTitle: "Zaman seç",
        timeText: "Zaman",
        hourText: "Saat",
        minuteText: "Dakika",
        secondText: "Saniye",
        currentText: "Şu An",
        closeText: "Tamam"
    });
    
    // update the radio buttons.
    $(".radio input[type=radio]").dblclick(function() {
        if ($(this).is(":checked")) {
            $(this).removeAttr("checked");
        }
    });
    $(".radio label").dblclick(function() {
        if ($(this).find("input[type=radio]").is(":checked")) {
            $(this).find("input[type=radio]").removeAttr("checked");
        }
    });
    
    $("#main form").submit(function(e) {
        $("#facebookPostPreview img.pagePhotoURL").attr("src", "https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash2/188068_205573406147090_3851704_q.jpg");
        $("#facebookPostPreview .postBrief").text($("textarea[name=brief]").val());
        $("#facebookPostPreview .postPhotoURL").attr("src", $("#croppedPhoto").attr("src"));
        $("#facebookPostPreview .postURL").attr("href", $("input[name=url]").val()).text($("input[name=title]").val());
        $("#facebookPostPreview .postURLDomain").text(fnGetDomain($("input[name=url]").val()));
        
        return handleSections(e);
    });
    
    // delete the corresponding news records.
    $("a.delete").click(function() {
        var $this = $(this);
        $.post(getUrl("/haberi-sil?format=json"), {
            "news_id": $this.attr("href").replace(/#sil-/, '')
        }, function (result) {
            if (! result || ! result.message) {
                alert("Haber silinirken bir hata oluştu!");
            } else {
                alert(result.message);

                if (0 == result.code) {
                    // @todo
                    window.location.reload();
                }
            }
        }, "json");
        
        return false;
    });
    
    // publish the corresponding news records.
    $("a.publish").click(function() {
        var $this = $(this);
        $.post(getUrl("/haberi-yayinla?format=json"), {
            "news_id": $this.attr("href").replace(/#yayinla-/, '')
        }, function (result) {
            if (! result || ! result.message) {
                alert("Haber yaına alınırken bir hata oluştu!");
            } else {
                alert(result.message);

                if (0 == result.code) {
                    // @todo
                    window.location.reload();
                }
            }
        }, "json");
        
        return false;
    });
    
    // unpublish the corresponding news records.
    $("a.unpublish").click(function() {
        var $this = $(this);
        $.post(getUrl("/haberi-yayindan-kaldir?format=json"), {
            "news_id": $this.attr("href").replace(/#yayindan-kaldir-/, '')
        }, function (result) {
            if (! result || ! result.message) {
                alert("Haber yayından kaldırılırken bir hata oluştu!");
            } else {
                alert(result.message);

                if (0 == result.code) {
                    // @todo
                    window.location.reload();
                }
            }
        }, "json");
        
        return false;
    });
});

function handleSections(e) {
    if (! window.newsSectioned) {
        $("#fieldset-first_section").hide();
        $("#main input[type=submit]").before(
            $("<button />").attr("class", "submit").text("« Düzenle").click(function() {
                $("#fieldset-second_section").hide();
                $("#main button").remove();
                $("#main input[type=submit]").val("Önizleme »");
                $("#fieldset-first_section").show();
                window.newsSectioned = false;
                
                return false;
            })
        ).val("Yayınla");
        $("#fieldset-second_section").show();
        
        window.newsSectioned = true;

        return false;
    }
    
    // if the news will be posted on Facebook;
    if ($("#main input[name=isToBePostedOnFacebook]").is(":checked")) {
        // make sure that he/she is connected to Facebook.
        FB.getLoginStatus(function(response) {
            // if the user is not logged in;
            if (! response.authResponse) {
                // force the user to login.
                FB.login(function(response) {
                    // if the user has logged in successfully;
                    if (response.authResponse) {
                        // submit the form.
                        $("#main form").submit();
                    }
                }, {scope: 'email'});
             
                // do not submit the form.
                e.preventDefault();
            }
        });
    }
    
    // change disabled fields.
    $("#main input[disabled]").each(function() {
        $(this).removeAttr("disabled");
    });
    $("#main select[disabled]").each(function() {
        $(this).removeAttr("disabled");
    });
    
    // submit the form (works only if not event.preventDefault is called)
    return true;
}

function fnGetDomain(url) {
   try {
       return url.match(/:\/\/(.[^/]+)/)[1];
   } catch (ex) {
       return url;
   }
}