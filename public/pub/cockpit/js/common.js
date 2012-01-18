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
    
    // build datetime plugins.
    $(".datetime").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        yearRange: "c-100:C"
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
    
    $("#patientType").change(function() {
        if ("INPATIENT" == $(this).val()) {
            $("#fieldset-outpatient_information").hide();
            $("#fieldset-inpatient_information").show();
        } else {
            $("#fieldset-inpatient_information").hide();
            $("#fieldset-outpatient_information").show();
        }
    });
});

function fnGetDomain(url) {
   try {
       return url.match(/:\/\/(.[^/]+)/)[1];
   } catch (ex) {
       return url;
   }
}