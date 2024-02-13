$('document').ready(function (){
    $('#categoryAdd').click(function (event){
        event.preventDefault();
        // console.log(event);
        $.ajax({
            type: 'POST',
            url: 'add_selection.php',
            data: {category:$('#category').val()},
            success: function (response) {
                console.log(response);
            }
        })
        $('#category').val("");
    })

    $('#castAdd').click(function(event){
        event.preventDefault();
        // console.log(event);
        $.ajax({
            type: 'POST',
            url: 'add_selection.php',
            data: {cast:$('#cast').val()},
            success: function (response) {
                console.log(response);
            }
        })
        $('#cast').val("");
    })
})