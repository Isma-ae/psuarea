$(document).ready(function () {

    //------------------ ผู้เขียน -----------------//
    let limit_authur = 1;
    let offset_author = 0;

    $('#collapse').hide();

    load_authur();

    function load_authur() {
        $.ajax({
            type: "post",
            url: "pages/search/action.php",
            data: {
                fn: "load_authur",
                limit: limit_authur,
                offset: offset_author,
            },
            success: function (res) {
                var data = JSON.parse(res);
                if (data.data.length > 0) {
                    $.each(data.data, function (index, value) {
                        var html = '';
                        var i = Math.random() * 1000000;
                        html += '<p data-type="authur">';
                        html += '<input type="checkbox" id="f-option' + (i) + '" name="author[]" style="width:20%;" value="' + value.writer_prefix + '' + value.writer_fname + ' ' + value.writer_lname + '" class="my-check">';
                        html += '<label for="f-option' + (i) + '">' + value.writer_prefix + '' + value.writer_fname + ' ' + value.writer_lname + '</label>';
                        html += '</p>';
                        $("#authur-data").append(html);
                    });
                    offset_author += 1;
                } else {
                    $("#showMore").hide();
                }
            }
        });
    }


    $("#showMore").click(function () {
        load_authur();
        $('#collapse').show();
    });

    $("#collapse").click(function () {
        limit_authur = 1;
        offset_author = 0;
        $("#authur-data").empty();
        load_authur();
        $(this).hide();
        $("#showMore").show();
    });

    $("#search-author").autoComplete({
        minChars: 1,
        cache: false,
        source: function (term, suggest) {
            $.post("pages/search/action.php", {
                fn: "search_author",
                search: term,
            }, function (res) {
                suggest(res.data);
            }, 'json');
        },
        renderItem: function (item, search) {
            var $tmp = $('<div><div class="autocomplete-suggestion"></div></div>');
            $tmp.find(".autocomplete-suggestion").attr("data-item", JSON.stringify(item));
            $tmp.find(".autocomplete-suggestion").html(item.writer_fname + ' ' + item.writer_lname);
            return $tmp.html();
        },
        onSelect: function (e, term, item) {
            var value = item.data("item");
            var fullname = value.writer_prefix + '' + value.writer_fname + ' ' + value.writer_lname;
            var is_duplicate = false;
            $.each($("[name^=author]"), function (i, v) {
                var x = $(this).val();
                if (x == fullname) {
                    is_duplicate = true;
                    return;
                }
            });
            if (is_duplicate) {
                $("[name^='author'][value='" + fullname + "']").prop("checked", true);
                return;
            }
            var html = '';
            var i = Math.random() * 1000000;
            html += '<p data-type="authur">';
            html += '<input checked type="checkbox" id="f-option' + (i) + '" name="author[]" style="width:20%;" value="' + fullname + '" class="my-check">';
            html += '<label for="f-option' + (i) + '">' + fullname + '</label>';
            html += '</p>';
            $("#authur-data").prepend(html);
            load_data(searchQuery, 1);
        }
    }).keydown(function (e) {
        if (e.keyCode == 8) { // key delete
        } else if (e.keyCode == 13) { // key enter
            e.preventDefault();
        }
    }).click(function () {
        $(this).focus();
    });

    //------------------ จบผู้เขียน -----------------//
    //-------------------- เรื่อง -------------------//

    let limit_subject = 1;
    let offset_subject = 0;
    $('#collapse2').hide();

    load_subject();

    function load_subject() {
        $.ajax({
            type: "post",
            url: "pages/search/action.php",
            data: {
                fn: "load_subject",
                offset: offset_subject,
                limit: limit_subject
            },
            success: function (res) {
                var data = JSON.parse(res);
                if (data.data.length > 0) {
                    $.each(data.data, function (index, value) {
                        var html = '';
                        var i = Math.random() * 1000000;
                        html += '<p data-type="subject">';
                        html += '<input type="checkbox" id="subject_name' + i + '" name="subject_name[]" style="width:20%;" value="' + value.subject_name + '" class="my-check">';
                        html += '<label for="subject_name' + i + '">' + value.subject_name + '</label>';
                        html += '</p>';
                        $("#subject-data").append(html);
                    });
                    offset_subject += 1;
                } else {
                    $("#showMore2").hide();
                }
            }
        });
    }

    $("#showMore2").click(function () {
        load_subject();
        $('#collapse2').show();
    });

    $("#collapse2").click(function () {
        limit_subject = 1;
        offset_subject = 0;
        $("#subject-data").empty();
        load_subject();
        $(this).hide();
        $("#showMore2").show();
    });

    $("#search-subject").autoComplete({
        minChars: 1,
        cache: false,
        source: function (term, suggest) {
            $.post("pages/search/action.php", {
                fn: "search_subject",
                search: term,
            }, function (res) {
                suggest(res.data);
            }, 'json');
        },
        renderItem: function (item, search) {
            var $tmp = $('<div><div class="autocomplete-suggestion"></div></div>');
            $tmp.find(".autocomplete-suggestion").attr("data-item", JSON.stringify(item));
            $tmp.find(".autocomplete-suggestion").html(item.subject_name);
            return $tmp.html();
        },
        onSelect: function (e, term, item) {
            var value = item.data("item");
            var subject = value.subject_name;
            var is_duplicate = false;
            $.each($("[name^=author]"), function (i, v) {
                var x = $(this).val();
                if (x == subject) {
                    is_duplicate = true;
                    return;
                }
            });
            if (is_duplicate) {
                $("[name^='author'][value='" + subject + "']").prop("checked", true);
                return;
            }
            var html = '';
            var i = Math.random() * 1000000;
            html += '<p data-type="subject">';
            html += '<input checked type="checkbox" id="subject_name' + (i) + '" name="subject_name[]" style="width:20%;" value="' + subject + '" class="my-check">';
            html += '<label for="subject_name' + (i) + '">' + subject + '</label>';
            html += '</p>';
            $("#subject-data").prepend(html);
            load_data(searchQuery, 1);
        }
    }).keydown(function (e) {
        if (e.keyCode == 8) { // key delete
        } else if (e.keyCode == 13) { // key enter
            e.preventDefault();
        }
    }).click(function () {
        $(this).focus();
    });

    //-------------------- จบเรื่อง -------------------//
    //-------------------- วันที่ -------------------//
    var year_min, year_max;

    function load_date() {
        $.ajax({
            type: "post",
            url: "pages/search/action.php",
            data: {
                fn: "load_date"
            },
            dataType: "json",
            success: function (data) {
                year_max = data.data[0].max_year;
                year_min = data.data[0].min_year;
                if (year_min == year_max) {
                    var min = parseFloat(year_min - 1);
                } else {
                    var min = year_min;
                }
                $("#slider-range").slider({
                    range: true,
                    min: parseFloat(year_min - 2),
                    max: year_max,
                    values: [min, year_max],
                    slide: function (event, ui) {
                        $("#min-input").val(ui.values[0]);
                        $("#max-input").val(ui.values[1]);
                    }
                });

                $("#min-input").val($("#slider-range").slider("values", 0));
                $("#max-input").val($("#slider-range").slider("values", 1));
            }
        });
    }

    $('#min-input').keyup(function () {
        var minValue = parseInt($(this).val()) || parseFloat(year_min - 2);
        var maxValue = $("#max-input").val();
        if (minValue <= maxValue) {
            $("#slider-range").slider("values", 0, minValue);
            load_data(searchQuery, 1);
        }
    });

    $('#max-input').keyup(function () {
        var maxValue = parseInt($(this).val()) || year_max;
        var minValue = $("#min-input").val();
        if (minValue <= maxValue) {
            $("#slider-range").slider("values", 1, maxValue);
            load_data(searchQuery, 1);
        }
    });

    $('#slider-range').on('mouseup touchend', function (e) {
        load_data(searchQuery, 1);
    });

    load_date();

    //-------------------- จบวันที่ -------------------//
    $("body").on("change", ".my-check", function () {
        load_data(searchQuery, 1);
    });

    $('input[name="has_file"]').change(function (e) {
        e.preventDefault();
        load_data(searchQuery, 1);
    });

    $('#order_by').change(function (e) {
        e.preventDefault();
        load_data(searchQuery, 1);
    });

    $('#limit').change(function (e) {
        e.preventDefault();
        load_data(searchQuery, 1);
    });
    var searchQuery = $('#search_query').val();

    load_data(searchQuery, 1);

    function load_data(query, page_number) {
        var form_data = new FormData();
        var author = [];
        var subject = [];
        $.each($("[name^=author]:checked"), function () {
            author.push($(this).val());
        });
        $.each($("[name^=subject_name]:checked"), function () {
            subject.push($(this).val());
        });
        if ($('input[name="has_file"]').is(':checked')) {
            var has_file = 'y';
        } else {
            var has_file = 'n';
        }
        form_data.append('query', query);
        form_data.append('writer', author.length > 0 ? JSON.stringify(author) : '');
        form_data.append('subject', subject.length > 0 ? JSON.stringify(subject) : '');
        form_data.append('min_year', $('#min-input').val());
        form_data.append('max_year', $('#max-input').val());
        form_data.append('order_by', $('#order_by').val());
        form_data.append('limit', $('#limit').val());
        form_data.append('has_file', has_file);
        form_data.append('page', page_number);
        form_data.append('fn', 'load_item');

        $.ajax({
            url: 'pages/search/action.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                var data = JSON.parse(response);
                var html = '';
                if (data.data.length > 0) {
                    $.each(data.data, function (index, post) {
                        var cover = (post.cover_name && post.cover_name !== "null") ?
                            '<img src="files/item/' + post.item_id + '/' + post.cover_name + '" alt="Cover Image">' :
                            '<img src="img/930231.png" alt="No Cover">';
                        html += '<div class="blog-author row my-blog" style="margin-bottom:20px;">';
                        html += '<div class="col-2"><a href="?p=item&item_id=' + post.item_id_md5 + '">' + cover + '</a></div>';
                        html += '<div class="col-10">';
                        html += '<span class="badge badge-primary">รายการ</span>';
                        html += '<a href="?p=item&item_id=' + post.item_id_md5 + '"><h4>' + post.item_title + '</h4></a>';
                        html += '<ul class="blog-info-link"><li>(' + post.item_publisher + ', ' + post.item_issued_year + ') ' + post.writer_names + '</li></ul>';
                        html += '<p>' + post.item_abstract + '</p>';
                        html += '</div>';
                        html += '</div>';
                    });
                } else {
                    html += '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
                }
                $('#item-data').html(html);
                $('#pagination_link').html(data.pagination);
                $('#pagination_info').text(data.pagination_info);

                window.total_pages = data.total_pages;
            }
        });
    }

    $(document).on('click', '#pagination_link .page-link', function (e) {
        e.preventDefault();
        var page_number = $(this).data('page');
        var searchQuery = $('#search_query').val();
        load_data(searchQuery, page_number);
    });

    $(document).on('click', '#pagination_link .prev', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page > 1) {
            var searchQuery = $('#search_query').val();
            load_data(searchQuery, current_page - 1);
        }
    });

    $(document).on('click', '#pagination_link .next', function (e) {
        e.preventDefault();
        var current_page = parseInt($('#pagination_link .active .page-link').data('page')) || 1;
        if (current_page < window.total_pages) {
            var searchQuery = $('#search_query').val();
            load_data(searchQuery, current_page + 1);
        }
    });

    $('#search-button').on('click', function () {
        var searchQuery = $('#search_query').val();
        load_data(searchQuery, 1);
    });
});