$(function () {

    load_community();

    function load_community() {
        $.post("pages/community-list/action.php", {
                fn: "load_community"
            },
            function (response) {
                let html = "";
                $.each(response, function (index, row) {
                    html += `
                    <div class="list-group-item">
                        <a href="#item-${index + 1}" data-toggle="collapse" data-com="${row.community_md5_id}"><i class="ti-icon ti-angle-right"></i></a>
                        <a href="?p=communities&community=${row.community_md5_id}"> 
                            ${row.community_title} 
                            <span class="badge badge-primary">${row.count_community}</span>
                        </a>
                        <p>${row.community_description}</p>
                        <div class="list-group collapse" id="item-${index + 1}"></div>
                    </div>`;
                });
                $("#community-list").html(html);
            },
            "json"
        );
    }

    $(document).on('click', ".list-group-item a[data-toggle='collapse']", function () {
        $('.ti-icon', this).toggleClass('ti-angle-right').toggleClass('ti-angle-down');
        let communityId = $(this).attr("data-com");
        let target = $(this).attr("href");
        $.post("pages/community-list/action.php", {
                fn: "load_collection",
                community_id: communityId
            },
            function (response) {
                let html = "";
                $.each(response, function (index, value) {
                    html += `<a href="?p=collections&collection=${value.collection_id}&community=${communityId}" class="list-group-item">
                        ${value.collection_name} <span class="badge badge-primary">${value.count_collection}</span>
                    </a>`;
                });
                $(target).html(html);
            },
            "json"
        );
    });

    load_collection();

    function load_collection() {
        $.post("pages/community-list/action.php", {
                fn: "load_collections"
            },
            function (response) {
                let html = "";
                $.each(response, function (index, row) {
                    html += `
                    <a href="?p=collections&collection=${row.collection_id}" class="list-group-item">
                        ${row.collection_name} <span class="badge badge-primary">${row.count_collection}</span>
                    </a>`;
                });
                $("#collection-list").html(html);
            },
            "json"
        );
    }

});