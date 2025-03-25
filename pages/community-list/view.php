<link rel="stylesheet" href="pages/community-list/style.css">
<script src="pages/community-list/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data</li>
            </ol>
        </nav>
    </div>
</div>
<section class="cart_area">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="just-padding">
                    <h2 class="contact-title">รายชื่อชุมชน</h2>
                    <div class="list-group list-group-root well">
                        <?php
                            $sql = "SELECT 
                                        tb_community.community_id,
                                        MD5(tb_community.community_id) AS community_md5_id,
                                        community_title,
                                        community_description,
                                        COUNT(item_id) AS count_community
                                    FROM tb_item
                                    RIGHT JOIN tb_community ON tb_community.community_id = tb_item.community_id
                                    GROUP BY tb_community.community_id";
                            $obj = $DATABASE->QueryObj($sql);
                            foreach ($obj as $key => $row) {
                        ?>
                        <div class="list-group-item">
                            <a href="#item-<?= ($key + 1)?>" data-toggle="collapse"><i class="ti-icon ti-angle-right"></i></a>
                            <a href="?p=communities&community=<?= $row["community_md5_id"];?>"> <?= $row["community_title"];?> <span class="badge badge-primary"><?= $row["count_community"];?></span></a>
                            <p><?= $row["community_description"];?></p>
                        </div>

                        <div class="list-group collapse" id="item-<?= ($key + 1)?>">
                            <?php
                                $sql2 = "SELECT MD5(tb_collection.collection_id) AS collection_id, tb_collection.collection_name, COUNT(tb_item.item_id) AS count_collection 
                                        FROM tb_collection
                                        LEFT JOIN tb_item ON tb_item.collection_id = tb_collection.collection_id 
                                                        AND tb_item.community_id = '".$row["community_id"]."'
                                        GROUP BY tb_collection.collection_id, tb_collection.collection_name";
                                $obj2 = $DATABASE->QueryObj($sql2);
                                foreach ($obj2 as $value) {
                            ?>
                            <a href="?p=collections&collection=<?= $value["collection_id"];?>&community=<?= $row["community_md5_id"];?>" class="list-group-item">
                                <?= $value["collection_name"];?> <span class="badge badge-primary"><?= $value["count_collection"];?></span>
                            </a>
                            <?php }?>

                        </div>

                        <?php }?>


                        <!--<a href="#item-3" class="list-group-item" data-toggle="collapse">
                            <i class="ti-icon ti-angle-right"></i>Item 3
                        </a>
                        <div class="list-group collapse" id="item-3">

                            <a href="#item-3-1" class="list-group-item" data-toggle="collapse">
                                <i class="ti-icon ti-angle-right"></i>Item 3.1
                            </a>
                            <div class="list-group collapse" id="item-3-1">
                                <a href="#" class="list-group-item">Item 3.1.1</a>
                                <a href="#" class="list-group-item">Item 3.1.2</a>
                                <a href="#" class="list-group-item">Item 3.1.3</a>
                            </div>

                            <a href="#item-3-2" class="list-group-item" data-toggle="collapse">
                                <i class="ti-icon ti-angle-right"></i>Item 3.2
                            </a>
                            <div class="list-group collapse" id="item-3-2">
                                <a href="#" class="list-group-item">Item 3.2.1</a>
                                <a href="#" class="list-group-item">Item 3.2.2</a>
                                <a href="#" class="list-group-item">Item 3.2.3</a>
                            </div>

                            <a href="#item-3-3" class="list-group-item" data-toggle="collapse">
                                <i class="ti-icon ti-angle-right"></i>Item 3.3
                            </a>
                            <div class="list-group collapse" id="item-3-3">
                                <a href="#" class="list-group-item">Item 3.3.1</a>
                                <a href="#" class="list-group-item">Item 3.3.2</a>
                                <a href="#" class="list-group-item">Item 3.3.3</a>
                            </div>

                        </div>-->

                    </div>

                </div>
            </div>
            <div class="col-md-6">
            <div class="just-padding">
                    <h2 class="contact-title">รายชื่อคอลเล็กชัน</h2>
                    <div class="list-group list-group-root well">
                        <?php
                            $sql2 = "SELECT MD5(tb_collection.collection_id) AS collection_id, tb_collection.collection_name, COUNT(tb_item.item_id) AS count_collection 
                                    FROM tb_collection
                                    LEFT JOIN tb_item ON tb_item.collection_id = tb_collection.collection_id 
                                    GROUP BY tb_collection.collection_id, tb_collection.collection_name";
                            $obj2 = $DATABASE->QueryObj($sql2);
                            foreach ($obj2 as $value) {
                        ?>
                        <a href="?p=collections&collection=<?= $value["collection_id"];?>" class="list-group-item">
                            <?= $value["collection_name"];?> <span class="badge badge-primary"><?= $value["count_collection"];?></span>
                        </a>
                        <?php }?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>