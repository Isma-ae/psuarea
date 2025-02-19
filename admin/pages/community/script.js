$(function () {
    load_data(query = '');

    $('#search').keyup(function (e) {
        var query = $('#search').val();
        load_data(query);
    });

    function load_data(query, page_number = 1) {
        var form_data = new FormData();
        form_data.append('query', query);
        form_data.append('page', page_number);
        form_data.append('fn', 'load_community');

        $.ajax({
            url: 'pages/community/action.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                var data = JSON.parse(response);
                var html = '';

                if (data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        html += '<tr>';
                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td><img src="../files/community/' + post.community_img + '" width="150px;"></td>';
                        html += '<td>' + post.community_title + '</td>';
                        html += '<td>' + post.community_description + '</td>';
                        html += '<td><div class="btn-list">';
                        html += '<button type="button" class="btn btn-sm btn-warning"><i class="icon-note"></i></button>';
                        html += '<button type="button" class="btn btn-sm btn-danger"><i class="icon-trash"></i></button>';
                        html += '</div></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="4" class="text-center">No Data Found</td></tr>';
                }

                $('#post_data').html(html);
                $('#total_data').html(data.total_data);
                $('#pagination_link').html(data.pagination);
            }
        });
    }

    $('#add').click(function (e) {
        e.preventDefault();
        $('#fn').val('add_community');
        $('#community_id').val('');
        $('#img').attr('src', '../img/3673.jpg');
        $('#community_title').val('');
        $('#community_description').val('');

        $('#community-header-modalLabel').html('เพิ่มชุมชน');
        $('#header-modal').removeClass('bg-warning');
        $('#header-modal').addClass('bg-success');
        $('#add-community').show();
        $('#edit-community').hide();
        $('#community-modal').modal('show');
    });

    $('#add-community,#edit-community').click(function (e) {
        e.preventDefault();
        $('#form-community').submit();
    });

    $('#form-community').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "pages/community/action.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (res) {
                Swal.fire(res.title, res.message, res.icon).then((result) => {
                    load_data(query = '');
                });
            }
        });
    });
});