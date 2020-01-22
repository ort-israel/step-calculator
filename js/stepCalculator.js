var debug;
jQuery( document ).ready(function( $ ) {

    var categoryDropDown = $("#category");
    var activityDropDown = $("#activity");
    var timeDropDown = $("#timeDropDown");
    var activityTable = $("#activityTable");
    var stepsLabel = $("#stepsLabel");
    var errorLabel = $("#error");
    var buttonAdd = $("#buttonAdd");
    var totalSteps = 0;
    var totalDuration =0;
    var dataUrl=sc_object.data_url;
    var data = JSON.parse($.getJSON({'url': dataUrl, 'async': false}).responseText);

        $(".select").select2({
            minimumResultsForSearch: -1,
            dropdownPosition: 'below',
            language: {
                noResults: function(){
                    return sc_object.result_not_found;
                }
            }
        });
        $('select').on("select2:close", function () { $(this).focus(); });


        $('b[role="presentation"]').hide();
        $('.select2-selection__arrow').append('<i class="fa fa-angle-down"></i>');

        populateCategoryDropDown();

    //select category
    categoryDropDown.on('change', function () {
        errorLabel.text("");
        activityDropDown.empty();
        activityDropDown.append('<option value="" disabled selected>'+sc_object.choose_activity_from_list+'</option>');
        var currentCategory = data[categoryDropDown.val()].activities;
        $.each(currentCategory,function (key, entry) {
            activityDropDown.append($('<option></option>').attr('value', entry.steps).text(entry.name));
        });
    });

    //select activity
    activityDropDown.on('change', function () {
        errorLabel.text("");
    });


    //add activity to table
    buttonAdd.click(function() {
        if(categoryDropDown.val()!==null)
        {
            if(activityDropDown.val()!==null)
            {
                if(timeDropDown.val()>0)
                {
                    var duration = parseInt(timeDropDown.val());
                    var currentActivitySteps = duration * activityDropDown.val();
                    totalSteps+=currentActivitySteps;
                    totalDuration+=duration;
                    activityTable.append('<tr dur='+duration+' val='+currentActivitySteps+'><td>'+$("#activity option:selected").text()+'</td><td>'+duration+' '+sc_object.minutes+'</td><td class="Steps">'+currentActivitySteps+' '+sc_object.steps+'</td><td><span class="removeLineSpan"><button type="button">'+sc_object.remove_line+'</button></span></td></tr>');
                    renderTotalSteps();
                }
                else
                {
                    errorLabel.text(sc_object.error_choose_duration);
                }
            }
            else
            {
                errorLabel.text(sc_object.error_choose_specific_activity);
            }
        }
        else
        {
            errorLabel.text(sc_object.error_choose_activity);
        }
    });

    //delete all activity
     $("#buttonDeleteAll").on('click', function(event) {
        activityTable.find("tr:not(:first-child)").remove();
        totalSteps=0;
        totalDuration=0;
        renderTotalSteps();
        event.stopPropagation();
     });

    //delete activity
    activityTable.on('click', 'span', function() {
        var currentRow = $(this).parent().parent();
            totalSteps -=  parseInt(currentRow.attr("val"));
            totalDuration-=parseInt(currentRow.attr("dur"));
            currentRow.fadeOut(400,function(){
                this.remove();
                renderTotalSteps();
            });

     });

    function populateCategoryDropDown() {
        var i=0;
        $.each(data,function (key, entry) {
            categoryDropDown.append($('<option></option>').attr('value', i++).text(entry.name));
        });
    };


    function renderTotalSteps() {
        $('#stepsLabel').text(totalSteps+' '+sc_object.steps+'');
        $('#durationLabel').text(totalDuration+' '+sc_object.minutes+'');

    };

});