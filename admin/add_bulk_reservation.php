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

                <h1 class="mb-4">Group Reservation</h1>

               


                <div class="col-12">
                  <!------------------------- t-date picker  --------------------->
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Pick a Date & Filter </h6>
                    </div>
                    <div class="card-body">
                      <div class="row py-1">
                        <div class="t-datepicker mt-2 col-3">
                          <div class="t-check-in">
                            <div class="t-dates t-date-check-in">
                              <label class="t-date-info-title">Check In</label>
                            </div>
                            <input type="hidden" class="t-input-check-in" name="start">
                            <div class="t-datepicker-day">
                              <table class="t-table-condensed">
                                <!-- Date theme calendar -->
                              </table>
                            </div>
                          </div>
                          <div class="t-check-out">
                            <div class="t-dates t-date-check-out">
                              <label class="t-date-info-title">Check Out</label>
                            </div>
                            <input type="hidden" class="t-input-check-out" name="end">
                          </div>
                        </div>

                        <!------------------------- t-date picker end  ------------------>

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
                          <option value="payed">Payed</option>
                          <option value="pending_payment">pending payment</option>
                        </select>
                      </div>



                      <div class="form-group col-6">
                        <input type="text" placeholder="Special Request*" class="form-control" v-model="formData.res_specialRequest" name="res_specialRequest" id="">
                        <!-- <textarea name="res_specialRequest" id="" cols="30" rows="10" placeholder="Special Request" class="form-control"></textarea> -->
                      </div>

                      <div class="form-group col-6">
                        <input type="text" placeholder="Promo Code" v-model="formData.res_promo" class="form-control">
                      </div>

                      <div class="form-group col-12">
                        <textarea name="res_remark" v-model="formData.res_remark" placeholder="Remark*" id="" cols="30" rows="10" class="form-control"></textarea>
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
  <script src="./js/t-datepicker.min.js"></script>

  <!-- data table plugin  -->



  <script>
    let start, end;

    $(document).ready(function() {
      const tdate = $('.t-datepicker')
      tdate.tDatePicker({
        show: true,
        iconDate: '<i class="fa fa-calendar"></i>'
      });
      tdate.tDatePicker('show')


      tdate.on('eventClickDay', function(e, dataDate) {

        var getDateInput = tdate.tDatePicker('getDateInputs')

        start = getDateInput[0];
        end = getDateInput[1];

        console.log("start", start);
        console.log("end", end);
      })
    });



    const app = Vue.createApp({
      data() {
        return {
          location: '',
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

            if (start && end) {
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
              
            } else {
              alert("Please Select Check In and Check Out Date");
            }
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
        // temp(row) {
        //   this.tempRow = row
        // },
        checkAdult() {
          if (this.res_adults == 2) {
            this.teen = true
            this.res_teen = 0
          } else {
            this.teen = false
          }
        },
        checkTeen() {
          if (this.res_teen == 2) {
            console.log("more than two");
            this.kid = true
            this.res_kid = 0

            console.log(this.res_kid);
          } else {
            this.kid = false
            console.log(this.res_kid);
          }
        },
        checkKid() {
          if (this.res_kid == 2) {
            console.log("more than two");
            this.teen = true
            this.res_teen = 0

            console.log(this.res_teen);
          } else {
            this.teen = false
            console.log(this.res_teen);
          }
        },

        fetchPromo() {
          this.oneClick = true

          // console.log("top promo", this.isPromoApplied);
          if (!localStorage.promoback) {
            console.log("excuted");
            axios.post('load_modal.php', {
              action: 'promoCode',
              data: this.formData.res_promo
            }).then(res => {
              let discount = this.totalPrice - ((res.data / 100) * this.totalPrice)

              this.totalPrice = discount
              // localStorage.totalBack = JSON.stringify(this.totalPrice)
              console.log(res.data);

            })
            this.isPromoApplied = true
            // localStorage.promoback = this.isPromoApplied
          }

          this.isPromoApplied = JSON.parse(localStorage.promoback || false)
        },
        async addReservation() {
          console.log("Selected room", this.cart);
          console.log("check in", start);
          console.log("check out", end);
          console.log("Form Data", this.formData);

          await axios.post('load_modal.php', {
            action: 'addReservation',
            Form: this.formData,
            checkin: start,
            checkout: end,
            rooms: this.cart,
            // price: this.totalPrice
          }).then(res => {
            // window.location.href = 'view_all_reservations.php'
            console.log(res.data);
            this.totalPrice = res.data
          })

        },
        selectAll() {

          var checkin = new Date(start)
          var checkout = new Date(end)

          console.log(checkin);
          console.log(checkout);
          // To calculate the time difference of two dates
          var Difference_In_Time = checkout.getTime() - checkin.getTime();

          // To calculate the no. of days between two dates
          var stayedNights = Difference_In_Time / (1000 * 3600 * 24);

          if (!this.selectAllRoom) {
            console.log("all");
            for (data in this.allData) {
              this.rowId.push(parseInt(this.allData[data].room_id))
              this.bookedRooms = this.allData

            }
            this.bookedRooms.forEach(row => {
              this.totalPrice += parseInt(row.room_price) * stayedNights
            })


            console.log("booked rooms", this.bookedRooms);
            console.log("Total Price", this.totalPrice);
            console.log("row ids", this.rowId);


          } else {

            this.rowId = []
            this.bookedRooms = []
            this.totalPrice = 0
            console.log("booked rooms", this.bookedRooms);
            console.log("Total Price", this.totalPrice);
            console.log(this.rowId);
          }

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


      },
      created() {
        this.fetchAll()
        // $('.t-datepicker').tDatePicker({});
      }
    })

    app.mount('#app')
  </script>





  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>





  <!-- <script src="./js/room.js"></script> -->

</body>

</html>