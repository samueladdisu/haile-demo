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



              <?php

              if (isset($_GET['id'])) {

                $id = $_GET['id'];
                $edit_res = array();
                $room_ids = array();
                $cart     = array();
                $rooms    = array();
                $res_query = "SELECT * FROM reservations WHERE res_id = $id LIMIT 1";
                $result    = mysqli_query($connection, $res_query);

                confirm($result);

                while ($row = mysqli_fetch_assoc($result)) {
                  $edit_res[] = $row;
                  $room_ids[] = json_decode($row['res_roomIDs']);
                }

                // foreach ($room_ids[0] as $value) {
                //   $room_query = "SELECT * FROM rooms WHERE room_id = '$value'";

                //   $room_result = mysqli_query($connection, $room_query);

                //   confirm($room_result);

                //   while ($row1 = mysqli_fetch_assoc($room_result)) {

                //     $rooms[] = $row1;
                //   }
                // }

                $encoded_res = json_encode($edit_res);
                $encoded_rooms = json_encode($rooms);
              }
              ?>


              <form action="" @submit.prevent="addReservation" method="POST" id="reservation" class="col-12 row" enctype="multipart/form-data">

                <h1 class="mb-4">Edit Reservation</h1>
                <div class="col-12">
                  <!------------------------- t-date picker  --------------------->
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Pick a Date & Filter </h6>
                    </div>
                    <div class="card-body">
                      <div class="row py-1">
                        <div class="col-12" v-if="editCheckin">
                          CheckIn: {{ editCheckin }} - Checkout: {{ editCheckout }}
                        </div>


                        <div class="form-group mt-2 col-3">
                          <input type="text" class="form-control" name="daterange" id="date" value="" readonly />
                        </div>

                        <?php

                        if ($_SESSION['user_role'] == 'SA' || ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'RA')) {

                        ?>
                          <div class="form-group mt-2 col-2">
                            <select name="room_location" class="custom-select" v-model="location" id="">
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
                        <?php } else { ?>
                          <input type="hidden" name="room_location" value="<?php echo $_SESSION['user_location']; ?>">


                        <?php  }


                        ?>

                        <div class="form-group mt-2 col-2">
                          <select name="room_location" class="custom-select" v-model="roomType" id="">
                            <option disabled value="">Room Type</option>
                            <?php

                            $query = "SELECT * FROM room_type";
                            $result = mysqli_query($connection, $query);
                            confirm($result);

                            while ($row = mysqli_fetch_assoc($result)) {
                              $type_name = $row['type_name'];
                              $type_location = $row['type_location'];

                              echo "<option value='$type_name'>{$type_name}</option>";
                            }
                            ?>
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
                              <select name="adults" v-model="res_adults" class="custom-select col-3">
                                <option value="" disabled>Adults*</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>

                              <select name="adults" @change="checkTeen" v-model="res_teen" class="custom-select col-3 offset-1" :disabled="teen">
                                <option value="" disabled>Teens(12-17)</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>

                              <select name="adults" @change="checkKid" v-model="res_kid" class="custom-select col-3 offset-1" :disabled="kid">
                                <option value="" disabled>kid(6-11)</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                              </select>


                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" @click="booked" data-dismiss="modal" class="btn btn-primary">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div>


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
                                <p class="mb-1">
                                  Adults:
                                  <select name="adults" v-model="item.adults" class="custom-select col-2">
                                    <option value="" disabled>Adults*</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                  </select>
                                </p>
                                <p class="mb-1">
                                  Teens:
                                  <select name="adults" @change="checkItemTeen" v-model="item.teens" class="custom-select col-2 offset-1">
                                    <option value="" disabled>Teens(12-17)</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                  </select>
                                </p>
                                <p class="mb-1"> Kids: {{ }}
                                  <select name="adults" @change="checkItemKid" v-model="item.kids" class="custom-select col-2 offset-1">
                                    <option value="" disabled>kid(6-11)</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                  </select>
                                </p>

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

                </div>

                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Fill in Guest Information</h6>
                  </div>
                  <div class="card-body d-flex justify-content-center">
                    <div class="col-6 row">
                      <div class="form-group col-6">
                        <input type="text" placeholder="First Name*" class="form-control" v-model="formData.res_firstname" name="res_firstname">
                      </div>
                      <div class="form-group col-6">
                        <input type="text" placeholder="Last Name*" class="form-control" v-model="formData.res_lastname" name="res_lastname">
                      </div>

                      <div class="form-group col-6">
                        <input type="text" placeholder="Phone No.*" class="form-control" v-model="formData.res_phone" name="res_phone">
                      </div>

                      <div class="form-group col-6">
                        <input type="text" placeholder="Email*" class="form-control" v-model="formData.res_email" name="res_email">
                      </div>





                      <div class="form-group col-6">
                        <select name="res_paymentMethod" v-model="formData.res_paymentMethod" class="custom-select" id="">
                          <option value="">Payment Method*</option>
                          <option value="bank_transfer">Bank Transfer</option>
                          <option value="cash">Cash</option>
                          <option value="GC1">Gift Card 1</option>
                          <option value="GC2">Gift Card 2</option>
                          <option value="GC3">Gift Card 3</option>
                        </select>

                      </div>


                      <div class="form-group col-6">
                        <select name="res_paymentStatus" v-model="formData.res_paymentStatus" class="custom-select" id="">
                          <option value="">Payment Status*</option>
                          <option value="paid">paid</option>
                          <option value="pending_payment">pending payment</option>
                        </select>
                      </div>



                      <div class="form-group col-6">
                        <input type="text" placeholder="Special Request*" class="form-control" v-model="formData.res_specialRequest" name="res_specialRequest" id="">
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
                        <textarea name="res_remark" v-model="formData.res_remark" placeholder="Remark*" id="" cols="30" rows="10" class="form-control"></textarea>
                      </div>


                      <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="add_res" value="Update Reservation">
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


  <!-- <script src="https://unpkg.com/axios/dist/axios.min.js"></script> -->
  <script src="https://unpkg.com/axios@0.27.2/dist/axios.min.js"></script>
  <!-- Core plugin JavaScript-->


  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <!-- data table plugin  -->



  <script>
    let rooms = <?= $encoded_rooms ?>

    let res_id = <?= $id ?>


    let editTemp = <?= $encoded_res ?>


    let cart = JSON.parse(editTemp[0].res_cart)


    console.log("cart ", cart);

    console.log(res_id);

    console.log("res checkin", editTemp[0].res_checkin)

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






    // console.log("edit", edit);


    const app = Vue.createApp({
      data() {
        return {
          editTemp: [],
          editCheckin: editTemp[0].res_checkin,
          editCheckout: editTemp[0].res_checkout,
          search_data: [],
          location: '',
          roomType: '',
          allData: '',
          bookedRooms: [],
          totalPrice: 0,
          cart: cart,
          selectAllRoom: false,
          selectBtn: false,
          stayedNights: 0,
          isPromoApplied: '',
          promoCode: '',
          oneClick: false,
          kid: false,
          teen: false,
          formData: {
            res_firstname: editTemp[0].res_firstname,
            res_lastname: editTemp[0].res_lastname,
            res_phone: editTemp[0].res_phone,
            res_email: editTemp[0].res_email,
            res_groupName: editTemp[0].res_groupName,
            res_paymentMethod: editTemp[0].res_paymentMethod,
            res_paymentStatus: editTemp[0].res_paymentStatus,
            res_promo: editTemp[0].res_promo,
            res_specialRequest: editTemp[0].res_specialRequest,
            res_remark: editTemp[0].res_remark,
            res_extraBed: false
          },
          tempRow: {},
          res_adults: '',
          res_teen: '',
          res_kid: '',

        }

      },
      methods: {
        table(row) {
          var selected = [];
          $('#addReserveTable').DataTable({
            destroy: true,
            dom: 'lBfrtip',
            buttons: [
              'colvis',
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
                data: 'room_acc'
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
                render: function(data) {
                  return `<input type="button" class="btn btn-primary" value="Select" data-row="${data}" id="selectRow">`
                }
              }
            ],
          });


          let vm = this
          $(document).on('click', '#selectRow', function() {
            // get room id from the table
            let ids = $(this).data("row")
            let temprow = {}

            // filter alldata which is equal to the room id
            vm.allData.forEach(item => {
              if (item.room_id == ids) {
                temprow = item
              }
            })

            // assign to temp row
            vm.tempRow = temprow
            console.log("temp row", vm.tempRow);
            $('#guest').modal('show')


          })

          $('#addReserveTable tbody').on('click', 'tr', function() {

            var id = this.id;
            var index = $.inArray(id, selected);

            if (index === -1) {
              selected.push(id);
            } else {
              selected.splice(index, 1);
            }

            $(this).toggleClass('selected');


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
        async deleteCart(item) {

          console.log(item.room_number);
          let cartIndex = this.cart.indexOf(item)
          this.cart.splice(cartIndex, 1)

          console.log(this.cart);

          await axios.post('load_modal.php', {
            action: 'freeRoom',
            roomId: item.room_id,
            res_id: res_id,
            cart: this.cart
          }).then(res => {
            console.log("cart room id", res.data);
          })


        },
        booked() {

          this.cart.forEach(item => {
            console.log(item.room_id);
          })
          let guests = {
            adults: this.res_adults,
            teens: this.res_teen,
            kids: this.res_kid,
            ...this.tempRow,
          }


          this.cart.push(guests);


          console.log("singleRoom", guests);
          console.log("cart", this.cart);
          guests = {}
          this.res_adults = ''
          this.res_teen = ''
          this.res_kid = ''
        },
        checkItemTeen() {
          if (this.cart.teens == 2 && this.cart.adults == 2) {
            console.log("more than two");
            this.cart.teens = 0

            alert("2 adult and 2 teens can't stay in 1 room")

            console.log(this.cart.kids);
          } else {
            this.cart.kids = false
            console.log(this.cart.kids);
          }
        },

        checkItemKid() {
          if (this.cart.kids == 2 && this.cart.adults == 2) {
            console.log("more than two");
            this.teen = true
            alert(`2 adult, 2 kids and ${this.cart.teens} teen can't stay in 1 room`)
            this.cart.teens = 0
            console.log(this.cart.teens);
          } else {
            this.cart.teens = false
            console.log(this.cart.teens);
          }
        },
        checkTeen() {
          if (this.res_teen == 2 && this.res_adults == 2) {
            console.log("more than two");
            this.res_teen = 0

            alert("2 adult and 2 teens can't stay in 1 room")

            console.log(this.res_kid);
          } else {
            this.kid = false
            console.log(this.res_kid);
          }
        },
        checkKid() {
          if (this.res_kid == 2 && this.res_adults == 2) {
            console.log("more than two");
            this.teen = true
            alert(`2 adult, 2 kids and ${this.res_teen} teen can't stay in 1 room`)
            this.res_kid = 0
            console.log(this.res_teen);
          } else {
            this.teen = false
            console.log(this.res_teen);
          }
        },

        async addReservation() {
          let arrival, departure

          if (start && end) {
            arrival = start
            departure = end
          } else {
            arrival = this.editCheckin
            departure = this.editCheckout
          }

          console.log("check in", arrival);
          console.log("check out", departure);

          await axios.post('load_modal.php', {
            action: 'editReservation',
            Form: this.formData,
            checkin: arrival,
            checkout: departure,
            res_id: res_id,
            rooms: this.cart,
            // price: this.totalPrice
          }).then(res => {
            window.location.href = 'view_all_reservations.php'
            console.log(res.data);
            this.totalPrice = res.data
          })

        },
        async filterRooms() {
          console.log(this.location);
          await axios.post('load_modal.php', {
            action: 'filter',
            location: this.location,
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
      }
    })

    app.mount('#app')
  </script>





  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>





  <!-- <script src="./js/room.js"></script> -->

</body>

</html>