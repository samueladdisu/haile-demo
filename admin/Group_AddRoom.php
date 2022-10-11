<?php include './includes/admin_header.php'; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include './includes/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d_flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include './includes/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d_sm-flex align-items_center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Accomodation</h1>

                    </div>
                    <!-- Content Row -->
                    <div class="row">
                        <?php
                        global $type_location;
                        if ($_SESSION['user_role'] == 'SA' || $_SESSION['user_role'] == 'PA') {
                        ?>
                            <div class="col-12">



                                <form method="POST" class="row" enctype="multipart/form-data" action="./GroupReservation_function.php" onchange="ValidateInputes()">


                                    <div class="form-group col-4">
                                        <label for="title">Weekday Pricing</label>
                                        <input type="text" class="form-control" name="Weekday_Pricing" id="WeekdayRoom" onchange="ValidateInputes()">
                                    </div>

                                    <div class="form-group col-4">
                                        <label for="type_name">Breakfast</label>
                                        <input type="text" class="form-control" name="Breakfast" id="Breakfast" onchange="ValidateInputes()">
                                    </div>



                                    <div class="form-group col-4">
                                        <label for="user_role">Extra Bed</label>
                                        <input type="text" class="form-control" name="Extra_Bed" id="Extra_Bed" onchange="ValidateInputes()">
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="user_role"> Weekend Pricing </label>
                                        <input type="text" class="form-control" name="Weekend_Pricing" id="Weekend_Pricing" onchange="ValidateInputes()">
                                    </div>
                                    <div class="form-group col-4">

                                        <label for="user_role"> Tea Break </label>
                                        <input type="text" class="form-control" name="Tea_Break" id="Tea_Break" onchange="ValidateInputes()">

                                    </div>

                                    <div class="form-group col-4">

                                        <label for="user_role"> Lunch Price </label>
                                        <input type="text" class="form-control" name="Lunch" id="LDB">

                                    </div>


                                    <div class="form-group col-4">

                                        <label for="user_role"> Dinner Price</label>
                                        <input type="text" class="form-control" name="Dinner" id="LDB">

                                    </div>


                                    <div class="form-group col-4">

                                        <label for="user_role"> BBQ Price </label>
                                        <input type="text" class="form-control" name="BBQ" id="LDB">

                                    </div>

                                    <?php

                                    if ($_SESSION['user_role'] != 'PA') {
                                    ?>
                                        <div class="form-group col-4">
                                            <label for="user_role"> Hotel Location </label> <br>
                                            <select name="type_location" class="custom-select" v-model="location" id="">
                                                <option disabled value="">Resort Location</option>
                                                <?php

                                                $query = "SELECT * FROM locations";
                                                $result = mysqli_query($connection, $query);
                                                confirm($result);

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $location_id = $row['location_id'];
                                                    $location_name = $row['location_name'];

                                                    echo "<option value='$location_name'>{$location_name}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    <?php
                                    } else {
                                    ?>
                                        <input type="hidden" name="type_location" value="<?php echo $_SESSION['user_location']; ?>">

                                    <?php
                                    }

                                    ?>

                                    <div class="form-group col-4">
                                        <label for="user_role"> Room Number Range </label> <br>
                                        <select name="Range" class="custom-select" v-model="location" id="RangeRoom">
                                            <option name='$Range1' value='0-50'>0-50</option>
                                            <option name='$Range2' value="51-80">51-80</option>
                                            <option name='$Range3' value="80 above">80 above</option>

                                        </select>
                                    </div>

                                    <div class="form-group col-4">
                                        <label for="user_role"> Event Type </label> <br>
                                        <select name="reason" class="custom-select" id="">
                                            <option value="">Event Type*</option>
                                            <option value="con">Conference</option>
                                            <option value="wed">Wedding</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12">
                                        <input type="submit" class="btn btn-primary" name="submit" value="Add" id="SubmitBTN">
                                    </div>
                                </form>

                                <?php
                                if (isset($_GET['edit'])) {
                                    $cat_id = escape($_GET['edit']);
                                    include './includes/update_acc.php';
                                }


                                ?>

                            </div>

                        <?php } ?>

                        <div class="col-12">



                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Room Types</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table display table-bordered" id="accTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Location</th>
                                                    <th>Weekday Rate</th>
                                                    <th>Weekend Rate</th>
                                                    <th>Breakfast</th>
                                                    <th>Extrabed</th>
                                                    <th>Lunch/Dinner/BBQ</th>
                                                    <th>Tea Break </th>
                                                    <th>Number of Room Range</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <?php


                                            // Display categories from database
                                            $location = $_SESSION['user_location'];
                                            $role = $_SESSION['user_role'];
                                            if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {
                                                $query = "SELECT * FROM group_pricing ORDER BY group_pricing_id DESC";
                                            } else {
                                                $query = "SELECT * FROM group_pricing WHERE group_location = '$location' ORDER BY group_pricing_id DESC";
                                            }

                                            $result = mysqli_query($connection, $query);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $type_id = $row['group_pricing_id'];
                                                $Location = $row['group_location'];
                                                $Weekday_Rate = $row['group_weekday'];
                                                $Weekend_Rate = $row['group_weeends'];
                                                $Breakfast = $row['group_breakfast'];
                                                $Extrabed = $row['group_extrabed'];
                                                $Lunch = $row['group_lunch'];
                                                $Tea_Break = $row['group_tea'];
                                                $Range = $row['group_range'];


                                            ?>
                                                <tr>
                                                    <td><?php echo $type_id; ?></td>
                                                    <td><?php echo $Location; ?></td>
                                                    <td><?php echo $Weekday_Rate; ?></td>
                                                    <td><?php echo $Weekend_Rate; ?></td>
                                                    <td><?php echo $Breakfast; ?></td>
                                                    <td><?php echo $Extrabed; ?></td>
                                                    <td><?php echo $Lunch; ?></td>
                                                    <td><?php echo $Tea_Break; ?></td>
                                                    <td><?php echo $Range; ?></td>
                                                    <?php if ($role != 'RA') {
                                                    ?>
                                                        <td><?php echo "<a href='Group_AddRoom.php?edit={$type_id}'><i style='color: turquoise;' class='far fa-edit'></i> </a>"; ?></td>
                                                        <td><?php echo "<a href='Group_AddRoom.php?delete={$type_id}'><i style='color: red;' class='far fa-trash-alt'></i> </a>"; ?></td>
                                                </tr>
                                        <?php  }
                                                }
                                        ?>


                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>

            <?php
            // Delete Categories from data base
            if ($role != 'RA') {

                if (isset($_GET['delete'])) {
                    $the_type_id = escape($_GET['delete']);
                    $query = "DELETE FROM group_pricing WHERE group_pricing_id = {$the_type_id} ";
                    $delete = mysqli_query($connection, $query);

                    if (!$delete) {
                        die('Can not delete data' . mysqli_error($connection));
                    } else {
                        header("Location: ./Group_AddRoom.php");
                    }
                }
            }

            ?>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Kuriftu resorts 2021. Powered by <a href="https://versavvymedia.com">Versavvy Media</a> </span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="./includes/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://unpkg.com/vue@3.0.2"></script>


    <!-- <script src="https://unpkg.com/axios/dist/axios.min.js"></script> -->
    <script src="https://unpkg.com/axios@0.27.2/dist/axios.min.js"></script>
    <!-- Core plugin JavaScript-->


    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script>
        $(document).ready(function() {

            $("#accTable").DataTable();
            ValidateInputes()
        })
        // const SubmitBTN = document.getElementById(' SubmitBTN')
        const WekdaysRack = document.getElementById('WeekdayRoom').value
        const BF = document.getElementById('Breakfast').value
        const EB = document.getElementById('Extra_Bed').value
        const WeP = document.getElementById('Weekend_Pricing').value
        const TB = document.getElementById('Tea_Break').value
        const LDB = document.getElementById('LDB').value
        const RR = document.getElementById('RangeRoom').value

        function ValidateInputes() {
            console.log("here")
            if (WekdaysRack == '' || BF == '' || EB == '' || WeP == '' || TB == '' || LDB == '') {
                document.querySelector('#SubmitBTN').disabled = false;
            } else {
                document.querySelector('#SubmitBTN').disabled = false;
            }
        }
    </script>


    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>





    <!-- <script src="./js/room.js"></script> -->

</body>

</html>