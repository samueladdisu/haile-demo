<?php $currentPage = "Add Reservation"; ?>
<?php include './includes/admin_header.php'; ?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include './includes/sidebar.php'; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include './includes/topbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <!-- <h1 class="h3 mb-0 text-gray-800">Reservations</h1> -->

          </div>
          <!-- Content Row -->
          <div class="row">



            <div id="app">


              <form action="" @submit.prevent="addReservation" method="POST" id="reservation" class="col-12 row" enctype="multipart/form-data">

                <h1 class="mb-4">Make Reservation</h1>




                <div class="col-12">
                  <!------------------------- t-date picker  --------------------->
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Pick a Date & Filter </h6>
                    </div>
                    <div class="card-body">
                      <div class="row py-1">

                        <div class="form-group mt-2 col-3">
                          <input type="text" class="form-control" name="daterange" id="date" value="" readonly />
                        </div>

                        <!------------------------- t-date picker end  ------------------>

                        <?php

                        if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {

                        ?>
                          <div class="form-group mt-2 col-2">
                            <select name="room_location" class="custom-select" v-model="location" @change="checkLocation" id="">
                              <option disabled value="">Resort Location</option>
                              <?php

                              $query = "SELECT * FROM locations";
                              $result = mysqli_query($connection, $query);
                              confirm($result);

                              while ($row = mysqli_fetch_assoc($result)) {
                                $location_id = $row['location_id'];
                                $location_name = $row['location_name'];

                                if ($location_name != 'Boston') {

                                  echo "<option value='$location_name'>{$location_name}</option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        <?php } else { ?>
                          <input type="hidden" id="hiddenlocation" name="room_location" value="<?php echo $_SESSION['user_location']; ?>">


                        <?php  }


                        ?>

                        <div class="form-group mt-2 col-2">

                          <select class="custom-select" v-model="roomType" id="">
                            <option disabled value="">Room Type</option>

                            <option :value="type.name" v-if="location !== 'Bishoftu'" v-for="type in types">
                              {{ type.name }}
                            </option>

                            <option :value="type.type_name" v-if="location === 'Bishoftu'" v-for="type in types">
                              {{ type.type_name }}
                            </option>


                          </select>
                        </div>



                        <div id="bulkContainer" class="col-3 mt-2">
                          <button name="booked" @click.prevent="filterRooms" class="btn btn-success">Filter</button>

                          <button name="booked" value="location" id="location" @click.prevent="clearFilter" class="btn btn-danger mx-2">Clear Filters</button>

                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cart">
                            Cart
                          </button>

                        </div>

                        <!-- Button trigger modal -->



                      </div>
                    </div>
                  </div>

                  <!-- Success Modal -->
                  <div id="success_tic" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <a class="close" href="#" data-dismiss="modal">&times;</a>
                        <div class="page-body">
                          <div class="text-center" v-if="spinner">
                            <div class="spinner-border" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>
                          </div>
                          <div v-if="success">
                            <div class="head">
                              <h3 style="margin-top:5px;">Operation Successful!</h3>
                              <!-- <h4>Lorem ipsum dolor sit amet</h4> -->
                            </div>

                            <h1 style="text-align:center;">
                              <div class="checkmark-circle">
                                <div class="background"></div>
                                <div class="checkmark draw"></div>
                              </div>
                            </h1>
                            <div style="text-align:center; margin-top: 2rem;">
                              <a href="view_all_reservations.php">
                                View reservation
                              </a>
                            </div>
                          </div>

                        </div>

                      </div>



                    </div>

                  </div>
                  <!-- End of Success Modal -->

                  <!-- Spinner Modal  -->
                  <div class="modal fade" id="spinnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Please wait...</h5>
                          <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button> -->
                        </div>
                        <div class="modal-body">
                          <div class="text-center">
                            <div class="spinner-border" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- End of Spinner Modal  -->

                  <!-- Modal -->
                  <div class="modal fade" id="guest">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Add Guests</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label for="" class="text-dark">Room 1:</label>
                            <div class="row">
                              <select v-model="res_adults" @change="CheckGuest" class="custom-select col-3">
                                <option value="" disabled>Adults*</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>

                              <select @change="CheckGuest" v-model="res_teen" class="custom-select col-3 offset-1" :disabled="teen">
                                <option value="" disabled>Teens(12-17)</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>

                              <select @change="CheckGuest" v-model="res_kid" class="custom-select col-3 offset-1" :disabled="kid">
                                <option value="" disabled>kid(6-11)</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>


                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" @click="booked" data-dismiss="modal" :disabled="guest" class="btn btn-primary">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Loft Modal  -->
                  <!-- Modal -->
                  <div class="modal fade" id="loftModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Add Guests</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label for="" class="text-dark">Room 1:</label>
                            <div class="row">
                              <select name="adults" @change="checkLoft" v-model="res_adults" class="custom-select col-3">
                                <option value="" disabled>Guests</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                              </select>

                              <select name="teen" @change="checkLoft" v-model="res_teen" class="custom-select col-3 offset-1" :disabled="loftTeen">
                                <option value="" disabled>Teens(12-17)</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                              </select>

                              <select name="kid" @change="checkLoft" v-model="res_kid" class="custom-select col-3 offset-1" :disabled="loftKid">
                                <option value="" disabled>kid(6-11)</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                              </select>


                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" @click="booked" data-dismiss="modal" :disabled="loftBtn" class="btn btn-primary">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Awash Modal -->
                  <div class="modal fade" id="awashModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Add Guests</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label for="" class="text-dark">Room 1:</label>
                            <div class="row">
                              <select v-model="res_adults" @change="CheckGuest" class="custom-select col-3">
                                <option value="" disabled>Adults*</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>

                              <select @change="CheckGuest" v-model="res_teen" class="custom-select col-3 offset-1" :disabled="teen">
                                <option value="" disabled>Teens(12-17)</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>

                              <select @change="CheckGuest" v-model="res_kid" class="custom-select col-3 offset-1" :disabled="kid">
                                <option value="" disabled>kid(6-11)</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>


                            </div>
                            <div class="row mt-3 d-flex justify-content-center BBModal">
                              <div class="form-check col-3">
                                <input class="form-check-input costom" type="radio" id="flexRadioDefault1" value="BedBreakfast" v-model="res_BB" required>
                                <label class="form-check-label" for="flexRadioDefault1">
                                  B&B
                                </label>
                              </div>
                              <div class="form-check col-3">
                                <input class="form-check-input costom" type="radio" id="flexRadioDefault2" value="Half Board" v-model="res_BB" required>
                                <label class="form-check-label" for="flexRadioDefault2">
                                  HB
                                </label>
                              </div>

                              <div class="form-check col-3">
                                <input class="form-check-input costom" type="radio" id="flexRadioDefault3" value="fullBoard" v-model="res_BB" required>
                                <label class="form-check-label" for="flexRadioDefault3">
                                  FB
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" @click="booked" data-dismiss="modal" :disabled="awash" class="btn btn-primary">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- End of Awash Modal  -->

                  <div class="modal fade" id="cart">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Selected Rooms</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">

                          <!-- cart items  -->
                          <div class="list-group">

                            <div v-if="cart.length">
                              <a href="#" v-for="item in cart" :key="item.room_id" class="list-group-item list-group-item-action flex-column align-items-start ">
                                <div class="d-flex w-100 justify-content-between">
                                  <h5 class="mb-1">{{ item.room_acc }} - {{ item.room_number }}</h5>
                                  <small> ${{ item.room_price }} / night</small>
                                </div>
                                <p class="mb-1"> Adults: {{ item.adults }} </p>
                                <p class="mb-1"> Teens: {{ item.teens }} </p>
                                <p class="mb-1"> Kids: {{ item.kids }} </p>

                                <div class="d-flex w-100 justify-content-between">
                                  <small>{{ item.room_location }} </small>

                                  <div class="d-flex">

                                    <p class="ml-2">
                                      <i style='color: red;' @click="deleteCart(item)" class='far fa-trash-alt'></i>
                                    </p>
                                  </div>
                                </div>
                              </a>
                            </div>

                            <div v-else>
                              <h2> No Room Selected </h2>
                            </div>


                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Available Rooms </h6>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">

                        <table class="table display table-bordered table-hover" id="addReserveTable" width="100%" cellspacing="0">

                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Occupancy</th>
                              <th>Accomodation</th>
                              <th>Price</th>
                              <th>Room Number</th>
                              <th>Room Status</th>
                              <th>Hotel Location</th>
                              <th>Select Room</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Room Status </h6>
                    </div>
                    <div class="card-body">
                      <div class="row py-1">

                        <div class="form-group mt-2 col-3">
                          <input type="date" v-model="filterDate" class="form-control" />
                        </div>

                        <!------------------------- t-date picker end  ------------------>

                        <?php

                        if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {

                        ?>
                          <div class="form-group mt-2 col-2">
                            <select name="room_location" class="custom-select" v-model="filterLocation" id="">
                              <option disabled value="">Resort Location</option>
                              <?php

                              $query = "SELECT * FROM locations";
                              $result = mysqli_query($connection, $query);
                              confirm($result);

                              while ($row = mysqli_fetch_assoc($result)) {
                                $location_id = $row['location_id'];
                                $location_name = $row['location_name'];

                                if ($location_name != 'Boston') {

                                  echo "<option value='$location_name'>{$location_name}</option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        <?php } else { ?>
                          <input type="hidden" name="room_location" value="<?php echo $_SESSION['user_location']; ?>">


                        <?php  }


                        ?>



                        <div id="bulkContainer" class="col-3 mt-2">

                          <?php

                          if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {

                          ?>

                            <button name="booked" @click.prevent="fetchRoomStatus(filterLocation)" class="btn btn-success">Filter</button>

                            <button @click.prevent="fetchRoomStatus('all')" class="btn btn-danger mx-2">Clear Filters</button>

                          <?php } else { ?>
                            <input type="hidden" name="room_location" value="<?php echo $_SESSION['user_location']; ?>">

                            <button name="booked" @click.prevent="fetchRoomStatus('<?php echo $_SESSION['user_location']; ?>')" class="btn btn-success">Filter</button>

                            <button @click.prevent="fetchRoomStatus('<?php echo $_SESSION['user_location']; ?>')" class="btn btn-danger mx-2">Clear Filters</button>
                          <?php  } ?>





                        </div>




                      </div>

                      <div class="table-responsive">
                        <table class="table display table-bordered" width="100%" id="roomStatus" cellspacing="0">


                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Type</th>
                              <th>Room no.</th>
                              <th>Guest Name</th>
                              <th>Adults</th>
                              <th>Kids</th>
                              <th>teens</th>
                              <th>Arrival</th>
                              <th>Departure</th>
                              <th>Status</th>
                              <th>Location</th>
                              <th>Select Room</th> 
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>

                        </table>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Fill in Guest Information</h6>
                  </div>
                  <div class="card-body d-flex justify-content-center">
                    <div class="col-6 row">
                      <div class="form-group col-6">
                        <input type="text" placeholder="First Name*" class="form-control" v-model="formData.res_firstname" name="res_firstname" required>
                      </div>
                      <div class="form-group col-6">
                        <input type="text" placeholder="Last Name*" class="form-control" v-model="formData.res_lastname" name="res_lastname" required>
                      </div>

                      <div class="form-group col-6">
                        <input type="text" placeholder="Phone No.*" class="form-control" v-model="formData.res_phone" name="res_phone" required>
                      </div>

                      <div class="form-group col-6">
                        <input type="text" placeholder="Email*" class="form-control" v-model="formData.res_email" name="res_email" required>
                      </div>





                      <div class="form-group col-6">
                        <select name="res_paymentMethod" v-model="formData.res_paymentMethod" class="custom-select" required>
                          <option value="">Payment Method*</option>
                          <option value="bank_transfer">Bank Transfer</option>
                          <option value="cash">Cash</option>
                          <option value="GC1">Gift Card 1</option>
                          <option value="GC2">Gift Card 2</option>
                          <option value="GC3">Gift Card 3</option>
                        </select>

                      </div>


                      <div class="form-group col-6">
                        <select name="res_paymentStatus" v-model="formData.res_paymentStatus" class="custom-select" required>
                          <option value="">Payment Status*</option>
                          <option value="paid">Paid</option>
                          <option value="pending_payment">pending payment</option>
                        </select>
                      </div>



                      <div class="form-group col-6">
                        <input type="text" placeholder="Special Request" class="form-control" v-model="formData.res_specialRequest" name="res_specialRequest" id="">
                        <!-- <textarea name="res_specialRequest" id="" cols="30" rows="10" placeholder="Special Request" class="form-control"></textarea> -->
                      </div>

                      <div class="form-group col-6">
                        <input type="text" placeholder="Promo Code" v-model="formData.res_promo" @keyup="getData()" class="form-control">

                        <div class="panel-footer" v-if="search_data.length">
                          <ul class="list-group">
                            <p class="list-group-item" @click="getName(data1.promo_code)" v-for="data1 in search_data">
                              {{ data1.promo_code }} {{ data1.promo_active }}
                            </p>
                          </ul>
                        </div>


                      </div>

                      <div class="form-group col-12">
                        <textarea name="res_remark" v-model="formData.res_remark" placeholder="Remark" id="" cols="30" rows="10" class="form-control"></textarea>
                      </div>


                      <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="add_res" value="Add Reservation">
                      </div>
                    </div>
                  </div>
                </div>




              </form>



            </div>

          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Kuriftu resorts 2022. Powered by <a href="https://versavvymedia.com">Versavvy Media</a> </span>
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


  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <!-- Core plugin JavaScript-->


  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

  <!-- data table plugin  -->



  <script>
    var start, end
    var today = new Date();
    const dd = String(today.getDate()).padStart(2, '0');
    const mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    const yyyy = today.getFullYear();

    let tomorrow = new Date(today)
    tomorrow.setDate(tomorrow.getDate() + 1)

    tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000)
    const td = String(tomorrow.getDate()).padStart(2, '0');
    const tm = String(tomorrow.getMonth() + 1).padStart(2, '0'); //January is 0!
    const ty = tomorrow.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;
    tomorrow = tm + '/' + td + '/' + ty;

    start = yyyy + '-' + mm + '-' + dd;
    end = ty + '-' + tm + '-' + td;

    console.log("inital start", start);
    console.log("inital end", end);

    $(document).ready(function() {

      console.log("initial start", start);
      console.log("initial end", end);
      $('#date').daterangepicker();
      $('#date').data('daterangepicker').setStartDate(today);
      $('#date').data('daterangepicker').setEndDate(tomorrow);

      $('#date').on('apply.daterangepicker', function(ev, picker) {
        // console.log(picker.startDate.format('YYYY-MM-DD'));
        // console.log(picker.endDate.format('YYYY-MM-DD'));

        start = picker.startDate.format('YYYY-MM-DD')
        end = picker.endDate.format('YYYY-MM-DD')
        console.log("updated start", start);
        console.log("updated end", end);
      });
    })



    const app = Vue.createApp({
      data() {
        return {
          res_BB: '',
          awash: true,
          loftBtn: true,
          guest: true,
          filterDate: "<?php echo date('Y-m-d'); ?>",
          spinner: false,
          success: false,
          search_data: [],
          location: '',
          types: [],
          roomType: '',
          allData: '',
          bookedRooms: [],
          totalPrice: 0,
          cart: [],
          selectAllRoom: false,
          selectBtn: false,
          stayedNights: 0,
          isPromoApplied: '',
          promoCode: '',
          oneClick: false,
          kid: false,
          teen: false,
          loftKid: false,
          loftTeen: false,
          formData: {
            res_firstname: '',
            res_lastname: '',
            res_phone: '',
            res_email: '',
            res_groupName: '',
            res_paymentMethod: '',
            res_paymentStatus: '',
            res_promo: '',
            res_specialRequest: '',
            res_remark: '',
            res_extraBed: false
          },
          tempRow: {},
          res_adults: '',
          res_teen: '',
          res_kid: '',
        }

      },
      watch: {
        res_BB(value) {
          if (value != '') {
            console.log(value);
            this.awash = false
          }
        },
        res_adults(value) {
          if (value != '' || value == 0) {
            this.guest = false
          } else {
            this.guest = true
          }
        },
        roomType(value) {
          console.log("Room Type", value);
        }
      },
      methods: {
        async checkLocationLoaded() {
          if (document.getElementById("hiddenlocation").value) {
            let location = document.getElementById("hiddenlocation").value

            console.log(location)
            await axios.post('load_modal.php', {
              action: 'fetchTypes',
              location: location
            }).then(res => {
              console.log("respose", res.data);
              this.types = res.data
            })
          }
        },
        async checkLocation() {
          console.log("location", this.location);

          await axios.post('load_modal.php', {
            action: 'fetchTypes',
            location: this.location
          }).then(res => {
            console.log("respose", res.data);
            this.types = res.data
          })

        },
        async fetchRoomStatus(flocation = "all") {

          // console.log(flocation);
          // console.log(this.filterDate)
          await axios.post('./includes/backEndreservation.php', {
            action: 'roomStatus',
            location: flocation,
            date: this.filterDate
          }).then(res => {
            console.log(res.data)

            res.data.forEach(item => {
              item.status = ""

              if ("2022-10-09" === item.b_checkin) {
                
                console.log("Start", start);
                console.log("bckin", item.b_checkin);
              }

              if (item.b_checkin === null) {
                item.status = "Free"
              } else if (item.b_checkin === this.filterDate) {
                item.status = "Arrival"
              } else if (this.filterDate > item.b_checkin && this.filterDate < item.b_checkout) {
                item.status = "Stay over"
              } else if (item.b_checkout === this.filterDate) {
                item.status = "Departure"
              }


            });


            this.roomStatusTable(res.data)
          })
        },
        roomStatusTable(row) {
          $('#roomStatus').DataTable({
            destroy: true,
            dom: 'lBfrtip',
            buttons: [
              'excel',
              'print',
              'csv'
            ],
            order: [
              [0, 'asc']
            ],
            data: row,
            columns: [{
                data: 'room_id'
              },
              {
                data: 'room_acc'
              },
              {
                data: 'room_number'
              },
              {
                data: 'res_firstname'
              },
              {
                data: 'info_adults'
              },
              {
                data: 'info_kids'
              },
              {
                data: 'info_teens'
              },
              {
                data: 'b_checkin'
              },
              {
                data: 'b_checkout'
              },
              {
                data: 'status',
              },
              {
                data: 'room_location'
              },
              {
                data: 'room_id',
                render: function(data, type, row) {
                  return `<input type="button" class="btn btn-primary" value="Select" data-id="${data}" data-row="${row}" id="selectRow">`
                }
              }
            ],
          });

        },
        table(row) {
          var selected = [];
          $('#addReserveTable').DataTable({
            destroy: true,
            dom: 'lBfrtip',
            buttons: [
              // 'colvis',
              'excel',
              'print',
              'csv'
            ],
            data: row,
            text: "edit",
            rowCallback: function(row, data) {
              if ($.inArray(data.DT_RowId, selected) !== -1) {
                $(row).addClass('selected');
              }
            },
            columns: [{
                data: 'room_id',
              },
              {
                data: 'room_occupancy'
              },
              {
                data: 'room_acc',
                render: function(data) {
                  return `<span data-acc="${data}" id="selectAcc"> ${data} </span>`
                }
              },
              {
                data: 'room_price'
              },
              {
                data: 'room_number'
              },
              {
                data: 'room_status'
              },
              {
                data: 'room_location'
              },
              {
                data: 'room_id',
                render: function(data, type, row) {
                  return `<input type="button" class="btn btn-primary" value="Select" data-id="${data}" data-row="${row}" id="selectRow">`
                }
              }
            ],
          });


          let vm = this
          $(document).on('click', '#selectRow', function() {


            if (start && end) {
              let acc = $('#selectAcc').data("acc")
              // get room id from the table
              let ids = $(this).data("id")
              let temprow = {}
              // filter alldata which is equal to the room id
              vm.allData.forEach(item => {
                if (item.room_id == ids) {
                  temprow = item
                }
              })

              // assign to temp row
              vm.tempRow = temprow
              console.log("temp row", vm.tempRow.room_acc);
              console.log("acc", acc)

              if (vm.tempRow.room_location === "awash") {
                $('#awashModal').modal('show')
              } else {
                if (vm.tempRow.room_acc === "Loft Family Room" || vm.tempRow.room_acc === "Presidential Family Room" || vm.tempRow.room_acc === "Presidential Suite Family Room") {
                  console.log("loft")
                  $('#loftModal').modal('show')
                } else {
                  $('#guest').modal('show')
                }

              }





            } else {
              alert("Please Select Check In and Check Out Date");
            }

            vm.guest = true


            console.log(vm.res_adults)
          })

          $('#addReserveTable tbody').on('click', 'tr', function() {
            if (start && end) {
              var id = this.id;
              var index = $.inArray(id, selected);

              if (index === -1) {
                selected.push(id);
              } else {
                selected.splice(index, 1);
              }

              $(this).toggleClass('selected');
            }

          });




        },
        async fetchAll() {

          await axios.post('load_modal.php', {
            action: 'fetchAll'
          }).then(res => {
            this.allData = res.data
            this.table(res.data)
            // console.log(this.allData);
          }).catch(err => console.log(err.message))
        },
        deleteCart(item) {
          let cartIndex = this.cart.indexOf(item)
          this.cart.splice(cartIndex, 1)

          console.log(this.cart);
        },
        booked() {

          this.cart.forEach(item => {
            console.log(item.room_id);
          })

          let loc = this.tempRow.room_location
          let guests
          if (loc == "awash") {

            if (this.res_BB == '') {
              alert('please select bored')
            } else {
              guests = {
                adults: this.res_adults,
                teens: this.res_teen,
                kids: this.res_kid,
                ...this.tempRow,
                board: this.res_BB
              }

            }
          } else {

            guests = {
              adults: this.res_adults,
              teens: this.res_teen,
              kids: this.res_kid,
              ...this.tempRow,
            }
          }


          this.cart.push(guests);


          console.log("singleRoom", guests);
          console.log("cart", this.cart);
          guests = {}
          this.res_adults = ''
          this.res_teen = ''
          this.res_kid = ''
          this.res_BB = ''
        },
        checkLoft() {
          if (this.res_adults === 0 || this.res_adults === "") {
            this.loftBtn = true
          } else {
            this.loftBtn = false
          }

          if (this.res_teen === 1 || this.res_teen === '1') {
            this.loftKid = true
            this.res_kid = 0
          } else {
            this.loftKid = false
          }

          if (this.res_kid === 1 || this.res_kid === '1') {
            this.loftTeen = true
            this.res_teen = 0
          } else {
            this.loftTeen = false
          }

        },
        CheckGuest() {
          if (this.res_adults === "1") {

            if ((this.res_teen === "2" && this.res_kid === "2") || (this.res_teen === "2" && this.res_kid === "1") || (this.res_teen === "1" && this.res_kid === "2")) {
              alert("This combination of guest numbers is not possible.");

              this.res_teen = 0;
              this.res_kid = 0;
            }


          } else if (this.res_adults === "2") {
            if ((this.res_teen === "2" && this.res_kid === "2") ||
              (this.res_teen === "2" && this.res_kid === "1") ||
              (this.res_teen === "2" && this.res_kid === 0) ||
              (this.res_teen === "2" && this.res_kid === "") ||
              (this.res_teen === "1" && this.res_kid === "2")) {
              alert("This combination of guest numbers is not possible.");

              this.res_teen = 0;
              this.res_kid = 0;
            } else {
              // alert("possible")
            }
          } else if (this.res_adults === "0" || this.res_adults === 0) {
            alert("adult cant be 0");
            this.res_teen = 0;
            this.res_kid = 0;
            this.guest = true
          }


        },

        async addReservation() {
          // console.log("Selected room", this.cart);
          // console.log("check in", start);
          // console.log("check out", end);
          // console.log("Form Data", this.formData);


          if (start && end) {


            if (this.cart == '') {
              alert('Cart is empty please select room(s)')
            } else {
              $('#success_tic').modal('show')
              this.spinner = true
              await axios.post('load_modal.php', {
                action: 'addReservation',
                Form: this.formData,
                checkin: start,
                checkout: end,
                rooms: this.cart,
              }).then(res => {
                // window.location.href = 'view_all_reservations.php'
                console.log(res.data);
                if (res.data == true) {
                  this.spinner = false
                  this.success = true
                  this.formData = {}
                  this.cart = {}
                  console.log("Selected room", this.cart);
                  console.log("check in", start);
                  console.log("check out", end);
                  console.log("Form Data", this.formData);

                  setTimeout(() => {
                    window.location.href = "./view_all_reservations.php"
                  }, 2000)
                } else {

                }


              })
            }
          }


        },
        async filterRooms() {
          let filterLocation


          <?php

          if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {

          ?>

            filterLocation = this.location

          <?php } else { ?>
            filterLocation = document.getElementById("hiddenlocation").value

          <?php  } ?>

          console.log(this.location);
          await axios.post('load_modal.php', {
            action: 'filter',
            location: filterLocation,
            roomType: this.roomType,
            checkin: start,
            checkout: end
          }).then(res => {
            console.log(res.data);
            this.allData = res.data
            this.table(res.data)
          }).catch(err => console.log(err.message))

          console.log("filtered data", this.allData);
        },
        clearFilter() {
          this.fetchAll()
          this.roomType = ''
          this.location = ''
        },

        getData() {
          this.search_data = []
          axios.post('load_modal.php', {
            query: this.formData.res_promo,
            action: 'fetchPromo'
          }).then(res => {
            console.log(res.data);
            this.search_data = res.data
          });
        },
        getName(name) {
          this.formData.res_promo = name
          this.search_data = []
        }
      },
      created() {
        this.fetchAll()

        <?php

        if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {
        ?>
          this.fetchRoomStatus()

        <?php } else { ?>

          this.fetchRoomStatus('<?php echo $_SESSION['user_location']; ?>')

        <?php  } ?>
        // Pusher.logToConsole = true;

        let fKey = '<?php echo $_ENV['FRONT_KEY'] ?>'
        let bKey = '<?php echo $_ENV['BACK_SINGLE_KEY'] ?>'
        let gKey = '<?php echo $_ENV['BACK_GROUP_KEY'] ?>'

        // Front end reservation notification channel from pusher


        const pusher = new Pusher(fKey, {
          cluster: 'mt1',
          encrypted: true
        });

        const channel = pusher.subscribe('front_notifications');
        channel.bind('front_reservation', (data) => {
          if (data) {
            this.fetchAll()
          }
        })

        // Back end reservation notification channel from pusher

        const back_pusher = new Pusher(bKey, {
          cluster: 'mt1',
          encrypted: true
        });

        const back_channel = back_pusher.subscribe('back_notifications');

        back_channel.bind('backend_reservation', (data) => {
          if (data) {
            this.fetchAll()
          }
        })

        // Group reservation notification channel from pusher

        const group_pusher = new Pusher(gKey, {
          cluster: 'mt1',
          encrypted: true
        });


        const group_channel = group_pusher.subscribe('group_notifications')

        group_channel.bind('group_reservation', data => {
          if (data) {
            this.fetchAll()
          }
        })
      },
      mounted() {
        <?php

        if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {

        ?>
          console.log("Super Admin")
        <?php } else { ?>


          this.checkLocationLoaded()

        <?php  } ?>
      }
    })

    app.mount('#app')
  </script>





  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>





  <!-- <script src="./js/room.js"></script> -->

</body>

</html>