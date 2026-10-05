$(document).ready(function () {
    $.ajax({
        url: "languages/getLanguage.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
            if (!response.error) {
                $(".comm").html(response.comm);
                $(".choice").html(response.choice);
                $(".titleLan").html(response.title);
                $(".authorLan").html(response.author);
                $(".subLan").html(response.sub);
                $(".searchPlace").attr('placeholder', response.searchPlace);
                $(".chooseComm").html(response.chooseComm);
                $(".item").html(response.item);
                $(".lastItem").html(response.lastItem);
                $(".viewAll").html(response.viewAll);
                $(".home").html(response.home);
                $(".show").html(response.show);
                $(".collection").html(response.collection);
                $(".year").html(response.year);
                $(".since").html(response.since);
                $(".to").html(response.to);
                $(".showButton").html('<i class="ti-book"></i> ' + response.show);
                $('.selectYear').html(' -- ' + response.selectYear + ' -- ');
                $(".back").html(response.back);
                $(".showByAuthor").html(response.showByAuthor);
                $(".authorPlace").attr('placeholder', response.authorPlace);
                $(".showByTitle").html(response.showByTitle);
                $(".titlePlace").attr('placeholder', response.titlePlace);
                $(".showBySub").html(response.showBySub);
                $(".subPlace").attr('placeholder', response.subPlace);
                $(".community").html(response.community);
                $(".file").html(response.file);
                $(".publisher").html(response.publisher);
                $(".abstract").html(response.abstract);
                $(".search").html(response.search);
                $(".filter").html(response.filter);
                $(".filterAuthor").html(response.author + ' <i class="right fas fa-caret-down"></i> ');
                $(".filterSubject").html(response.sub + ' <i class="right fas fa-caret-down"></i> ');
                $(".filterYear").html(response.year + ' <i class="right fas fa-caret-down"></i> ');
                $(".hasFile").html(response.hasFile + ' <i class="right fas fa-caret-down"></i> ');
                $(".yes").html(response.yes);
                $(".resetFilter").html('<i class="ti-reload"></i> ' + response.resetFilter);
                $(".setting").html(response.setting);
                $(".orderBy").html(response.orderBy);
                $(".resultPerPage").html(response.resultPerPage);
                $(".latestItems").html(response.latestItems);
                $(".firstItem").html(response.firstItem);
                $(".namesFromLeast").html(response.namesFromLeast);
                $(".namesFromMost").html(response.namesFromMost);
                $(".yearsFromLeast").html(response.yearsFromLeast);
                $(".yearsFromMost").html(response.yearsFromMost);
                $(".searchResults").html(response.searchResults);
                $('#order_by').niceSelect('update');
            }
        }
    });
});