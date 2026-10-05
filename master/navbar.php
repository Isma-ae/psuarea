<script>
    $(document).ready(function () {
        $('#se_search-box').keypress(function (e) {
            var key = e.which;
            if (key == 13) // the enter key code
            {
                var search_term = $('#se_search-box').val();
                window.location.href = '?p=search&search_term=' + search_term;
                return false;
            }
        });

        $("#se_search-btn.se_search-coll").click(function () {
            $(".se_search-coll").removeClass("se_search-coll");
            $("#se_search-box").focus();
            window.setTimeout(submittize, 1000);

            var action = $(this).attr('type');
            if (action == 'submit') {
                var search_term = $('#se_search-box').val();
                window.location.href = '?p=search&search_term=' + search_term;
            }
        });

        $("#se_search-box").blur(function () {
            $("#se_search-box, #se_search-btn").addClass("se_search-coll");
            window.setTimeout(buttonize, 1000);
        })

        function buttonize() {
            $("#se_search-btn").attr("type", "button");
        }

        function submittize() {
            $("#se_search-btn").attr("type", "submit");
        }
    });

    function changeLanguage(lang) {
        $.ajax({
            url: "languages/setLanguage.php", // Save language in session
            type: "POST",
            data: { lang: lang },
            success: function() {
                location.reload();
            }
        });
    }
</script>
<header class="main_menu home_menu">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="./"> <div class="d-none d-sm-none d-md-block"><img src="img/Agricultural.png" alt="logo" style="height:40px !important;"></div></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="menu_icon"><i class="fas fa-bars"></i></span>
                    </button>

                    <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link comm" href="?p=community-list">ขอบเขตเนื้อหาและคอลเล็กชัน</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle choice" href="blog.html" id="navbarDropdown_1"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ตัวเลือกการค้นหา
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown_1">
                                    <!--<a class="dropdown-item" href="?p=dateissued">ตามวันที่ออก</a>-->
                                    <a class="dropdown-item titleLan" href="?p=title">ชื่อเรื่อง</a>
                                    <a class="dropdown-item authorLan" href="?p=author">ผู้แต่ง</a>
                                    <a class="dropdown-item subLan" href="?p=subject">คำสำคัญ</a>
                                    <!--<a class="dropdown-item" href="?p=srsc">ตามหมวดหัวเรื่อง</a>-->
                                </div>
                            </li>
                            <!--<li class="nav-item">
                                <a class="nav-link" href="contact.html">สถิติ</a>
                            </li>-->
                        </ul>
                    </div>
                    <div class="hearer_icon d-flex align-items-center">
                        <!--<a id="search_1" href="javascript:void(0)"><i class="ti-search"></i></a>-->
                        <input id="se_search-box" name="search" size="20" placeholder="ค้นหาจากชื่อเรื่อง ชื่อผู้แต่ง และคำสำคัญ" class="se_search-coll searchPlace" type="text" />
                        <button id="se_search-btn" class="se_search-coll" type="button">
                            <i class="ti-search"></i>
                        </button>
                        <div class="nav-item dropdown">
                            <a href="cart.html" data-toggle="dropdown" id="languageDropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti-world"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="languageDropdown" style="top: 55px;left: -115px;">
                                <a class="dropdown-item" href="#" onclick="changeLanguage('th')">ภาษาไทย</a>
                                <a class="dropdown-item" href="#" onclick="changeLanguage('en')">English</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>