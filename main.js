
$(document).ready(function () {



    fetchData();
    function hideSelected(value) {
        if (value && !value.selected) {
          return $('<span>' + value.text + '</span>');
        }
      }
    $('#castAndCrewInput').select2({
        placeholder: "Select a cast",
        templateResult: hideSelected
    });
    
    $('#ratingInput').select2({
        placeholder: "Select a rating",
    });


    $('#myForm').submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'insert.php',
            data: getFormData(),
            success: function (response) {
                let movieId = JSON.parse(response).movieId;
                // $('#tableBody').html("");
                fetchData();
                $('#reset').trigger("click")
            }
        })
    })



    $('#reset').click(function (event) {
        event.preventDefault();

        $("#movieNameInput").val("");
        $('.checkInput').prop("checked", false)
        $('#castAndCrewInput').val(null).trigger('change');
        $('#ratingInput').val(null).trigger('change');

        $('#submit').removeClass('d-none');
        $('#update').addClass('d-none');
    })



    function update(movieId) {
        $('#update').unbind('click');

        $.ajax({
            url: 'fetch_all.php',
            data: {
                id: $('#update').attr("data-id")
            },
            success: function (response) {
                let data = JSON.parse(response)[0];
                // console.log(data);

                $("#movieNameInput").val(data.movie_name);

                $('.checkInput').prop("checked", false)
                if (data.categories !== null) {
                    categoryArr = data.categories.split(",")
                    categoryArr.forEach((item) => {
                        // console.log(item);
                        $(`#check${item}`).prop("checked", true)
                    })
                }

                $('#castAndCrewInput').val(null).trigger('change');
                if (data.cast_crew !== null) {
                    let castArr = data.cast_crew.split(",")
                    $('#castAndCrewInput').val(castArr);
                    $('#castAndCrewInput').trigger('change');
                }

                $('#ratingInput').val(`${data.rating}`).trigger('change');

                $('#update').removeClass('d-none');
                $('#submit').addClass('d-none');
            }
        })

        $('#update').click(function (e) {
            // e.stopPropagation()
            console.log(e);
            // console.log(getFormData($('#update').attr("data-id")))
            $.ajax({
                type: 'POST',
                url: 'update.php',
                data: getFormData($('#update').attr("data-id")),
                success: function (response) {
                    console.log(response);
                    // $('#tableBody').html("");
                    fetchData();
                    $('#reset').trigger("click")
                }
            })

            $('#submit').removeClass('d-none');
            $('#update').addClass('d-none');
        })
    }

    function fetchData(movieId) {
        $.ajax({
            url: 'fetch_all.php',
            data: {
                id: movieId
            },
            success: function (response) {
                let data = JSON.parse(response);
                $('#tableBody').html("");
                // console.log(data)
                data.forEach((movie, i) => {
                    $('#tableBody').append(`
                        <tr>
                            <td>${i + 1}</td>
                            <td>${movie.movie_name}</td>
                            <td>${movie.categories}</td>
                            <td>${movie.cast_crew}</td>
                            <td>${movie.rating}</td>
                            <td><button class="btn btn-dark editBtn" data-id=${movie.id}><span class="material-symbols-outlined" >edit_square</span></button></td>
                            <td><button class="btn btn-dark deleteBtn" data-id=${movie.id}><span class="material-symbols-outlined" >delete</span></button></td>
                        </tr>`
                    )
                });

                $('.editBtn').click(function () {
                    const movieId = $(this).data('id')
                    $('#update').attr("data-id", movieId);
                    update();
                });

                $('.deleteBtn').click(function () {
                    deleteData($(this).data('id'));
                })
            }
        })


        $.ajax({
            url:'fetch_category.php',
            success : function(response){
                let data = JSON.parse(response);
                // console.log(data);
                $('#categoryInput').html("");
                data.forEach(function(category){
                    const id = `check${category.category}`;
                   
                    $('#categoryInput').append(
                        `<div class="form-check col-sm-6 col-md-2">
                            <input class="form-check-input checkInput" type="checkbox" name="category[]" value="${category.category}"
                                id="${id}">
                            <label class="form-check-label" for="${id}">${category.category}</label>
					    </div>`
                    )

                })
            }
        })
        $.ajax({
            url:'fetch_cast.php',
            success : function(response){
                let data = JSON.parse(response);
                // console.log(data);
                $('#castAndCrewInput').html("");
                // const options =[]
                data.forEach(function(cast,i){
                    $('#castAndCrewInput').append(`<option value="${cast.cast_name}">${cast.cast_name}</option>`)
                    // options.push({id:i,text:})
                })
              
            }
        })

    }
   

    function deleteData(movieId) {
        $.ajax({
            url: 'delete.php',
            data: {
                id: movieId
            },
            success: function (response) {
                console.log(response);
                $('#tableBody').html("");
                fetchData();
                $('#reset').trigger("click")
            }
        })
    }

    // $('#castAndCrewInput').change(function(event){
    //     console.log(event.target);
    // })

    $('#mySelect2').on('select2:select', function (e) {
        var data = e.params.data;
        console.log(data);
    });

    function getFormData(movieId) {

        const castArr = [];
        $('#castAndCrewInput').find(':selected').each(function () {
            castArr.push(this.value)
        })

        const categoryArr = [];
        $('#categoryInput input[type=checkbox]:checked').each(function () {
            categoryArr.push(this.value)
        })

        const data = {
            id: movieId,
            movieName: $("#movieNameInput").val().trim(),
            category: categoryArr,
            castAndCrew: castArr,
            rating: $('#ratingInput').find(':selected').val()
        }
        return data;
    }
});
