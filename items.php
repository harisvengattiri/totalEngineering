<?php require_once "includes/menu.php"; ?>
<?php require_once "database.php"; ?>

<div class="app-body">
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <span style="float: left;">
                    <h2>Products</h2>
                </span>
                <span style="float: right;"><a href="<?php echo BASEURL; ?>/add/item"><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
                    <?php
                    if (isset($_GET['view'])) {
                    ?>
                        <a href="<?php echo BASEURL; ?>/items"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
                    <?php
                    } else {
                    ?>
                        <a href="<?php echo BASEURL; ?>/items?view=all"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
                    <?php
                    } ?>
                </span>
            </div><br />
            <div class="box-body">
                <span style="float: left;"></span>
                <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r" /></span>
            </div>
            <div>
                <?php
                if (isset($_GET['view'])) {
                    $list_count = 100;
                } else {
                    $list_count = 10;
                }
                ?>
                <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count; ?>">
                    <thead>
                        <tr>
                            <th data-toggle="true">
                                Product Code
                            </th>
                            <th>
                                Item
                            </th>
                            <th>
                                Price
                            </th>
                            <th>
                                Weight
                            </th>
                            <th data-hide="all">
                                Dimension
                            </th>
                            <th data-hide="all">
                                Description
                            </th>
                            <th>
                                Unit
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $itemsList = getItemsList();
                            foreach ($itemsList as $row) {
                        ?>
                                <tr>
                                    <td>PRD <?php echo sprintf("%04d", $row["id"]); ?></td>
                                    <td><?php echo $row["items"]; ?></td>
                                    <td><?php echo $row["price"]; ?></td>
                                    <td><?php echo $row["weight"]; ?></td>
                                    <td><?php echo $row["dimension"]; ?></td>
                                    <td><?php echo $row["description"]; ?></td>
                                    <td><?php echo $row["unit"]; ?></td>
                                    <td>
                                        <a href="<?php echo BASEURL; ?>/edit/items?id=<?php echo $row["id"]; ?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
                                        <?php if ($_SESSION['role'] == 'admin') { ?>
                                            <a href="<?php echo BASEURL; ?>/delete/items?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                    <tfoot class="hide-if-no-paging">
                        <tr>
                            <td colspan="5" class="text-center">
                                <ul class="pagination"></ul>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<?php include "includes/footer.php"; ?>
