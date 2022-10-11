<?php include './includes/admin_header.php'; ?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include './includes/sidebar.php'; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="resApp" style="width: 100vw;" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include './includes/topbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Reservations</h1>

          </div>
          <!-- Content Row -->
          <div class="row">
            <div class="col-12 mb-2">

              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Reservations</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table display table-bordered" width="100%" id="viewResTable" cellspacing="0">


                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Phone</th>
                          <th>Arrival</th>
                          <th>Departure</th>
                          <th>Total Price</th>
                          <th></th>
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
                        <select name="room_location" class="custom-select" v-model="filterLocation" @change="checkLocation" id="">
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
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>

                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- View Full Reservation Details Modal -->
      <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">
                {{ tempRow.res_firstname }}
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-if="guestInfo.length > 0">
                <h1 class="my-2">Room Info</h1>

                <div class="card-group">

                  <div class="card" style="width: 18rem;" v-for="row in guestInfo" :key="row.info_id">
                    <div class="card-body">
                      <h5 class="card-title font-weight-bold"> {{ row.info_room_acc }} <span v-if="row.info_board"> - {{ row.info_board }} </span> </h5>

                    </div>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item"> <strong>Adults:</strong>{{ row.info_adults }}</li>
                      <li class="list-group-item"> <strong>Kids:</strong> {{ row.info_kids }}</li>
                      <li class="list-group-item"> <strong>Teens:</strong> {{ row.info_teens }}</li>
                      <li class="list-group-item"> <strong>Room Number:</strong> {{ row.info_room_number }}</li>
                      <li class="list-group-item"> <strong>Room Location:</strong> {{ row.info_room_location }}</li>
                    </ul>
                  </div>
                </div>

              </div>

              <h1 class="my-2">Detail Guest Info</h1>

              <div class="card-group mt-4">
                <div class="card" style="width: 18rem;">
                  <ul class="list-group">
                    <li class="list-group-item"> <strong>Full Name:</strong> {{ tempRow.res_firstname }} {{ tempRow.res_lastname }}</li>
                    <li class="list-group-item"> <strong>Phone:</strong> {{ tempRow.res_phone }}</li>
                    <li class="list-group-item"> <strong> Email: </strong>{{ tempRow.res_email }}</li>
                    <li class="list-group-item"> <strong>Check in:</strong> {{ tempRow.res_checkin }}</li>
                    <li class="list-group-item"> <strong>Check out:</strong> {{ tempRow.res_checkout }}</li>
                    <li class="list-group-item"> <strong>Agent:</strong> {{ tempRow.res_agent }}</li>
                  </ul>
                </div>

                <div class="card" style="width: 18rem;">

                  <ul class="list-group">
                    <li class="list-group-item"> <strong>Group Name:</strong> {{ tempRow.res_groupName }}</li>
                    <li class="list-group-item"> <strong>Price:</strong> {{ tempRow.res_price }}</li>
                    <li class="list-group-item"> <strong> Remark: </strong>{{ tempRow.res_remark }}</li>
                    <li class="list-group-item"> <strong>Promo:</strong> {{ tempRow.res_promo }}</li>
                    <li class="list-group-item"> <strong>Date of Birth:</strong> {{ tempRow.res_dob }}</li>
                    <li class="list-group-item"> <strong>Member User name:</strong> {{ tempRow.res_member }}</li>
                  </ul>
                </div>

              </div>

              <div class="card-group  mt-4">
                <div class="card" style="width: 18rem;">
                  <ul class="list-group">
                    <li class="list-group-item"> <strong>Location:</strong> {{ tempRow.res_location }}</li>
                    <li class="list-group-item"> <strong>Confirm ID:</strong> {{ tempRow.res_confirmID }}</li>
                    <li class="list-group-item"> <strong> Special Request: </strong>{{ tempRow.res_specialRequest }}</li>
                    <li class="list-group-item"> <strong>Payment Status:</strong> {{ tempRow.res_paymentStatus }}</li>
                    <li class="list-group-item"> <strong>Check out:</strong> {{ tempRow.res_checkout }}</li>
                    <li class="list-group-item"> <strong>Agent:</strong> {{ tempRow.res_agent }}</li>
                  </ul>
                </div>
                <div class="card" style="width: 18rem;">
                  <ul class="list-group">
                    <li class="list-group-item"> <strong>Contry:</strong> {{ tempRow.res_country }}</li>
                    <li class="list-group-item"> <strong>Address:</strong> {{ tempRow.res_address }}</li>
                    <li class="list-group-item"> <strong> City: </strong>{{ tempRow.res_city }}</li>
                    <li class="list-group-item"> <strong>Zip Code:</strong> {{ tempRow.res_zipcode }}</li>
                    <li class="list-group-item"> <strong>Payment Method:</strong> {{ tempRow.res_paymentMethod }}</li>
                  </ul>
                </div>



              </div>

            </div>

          </div>
        </div>
      </div>

      <!-- End of View Full Reservation Details -->




      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Kuriftu resorts 2022. Powered by <a href="https://versavvymedia.com">Versavvy Media</a> </span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

      <!-- Delete Modal  -->
      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Are you sure You want to Delete?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">Select "Delete" to confirm deletion.</div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <button class="btn btn-primary" @click="deleteRes" data-dismiss="modal">Delete</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Delete Modal  -->

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
  <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>


  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>
    const app = Vue.createApp({
      data() {
        return {
          posts: [''],
          tempRow: {},
          location: '',
          filterDate: "<?php echo date('Y-m-d'); ?>",
          location: '',
          modal: "",
          tempcheckin: '',
          tempcheckout: '',
          firstName: '',
          lastName: '',
          email: '',
          phone: '',
          dob: '',
          remark: '',
          tempDelete: {},
          guestInfo: []
        }
      },
      methods: {
        table(row) {
          $('#viewResTable').DataTable({
            destroy: true,
            dom: 'lBfrtip',
            buttons: [
              'excel',
              'print',
              'csv'
            ],
            order: [
              [0, 'desc']
            ],
            data: row,
            columns: [{
                data: 'res_id'
              },
              {
                data: 'res_firstname'
              },
              {
                data: 'res_lastname'
              },
              {
                data: 'res_phone'
              },
              {
                data: 'res_checkin'
              },
              {
                data: 'res_checkout'
              },
              {
                data: 'res_price'
              },
              {
                data: 'res_id',
                render: function(data) {
                  return `<div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                          <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-600"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                          <a data-toggle="modal" :data-target="modal" class="dropdown-item" href="#">
                            Add
                          </a>
                          <a class="dropdown-item" data-id="${data}" id="view" href="#">
                            View
                          </a>
                          <a class="dropdown-item" id="edit" href="edit_reservation.php?id=${data}" href="#">
                            Edit
                          </a>
                          <div class="dropdown-divider"></div>
                          <a data-id="${data}" id="delete" href="#" class="dropdown-item text-danger">
                            Delete
                          </a>

                        </div>
                      </div>`
                }
              }
            ],
          });


          let vm = this

          // $(document).on('click', '#edit', function(){
          //   let res_id = $(this).data("id")

          // })

          $(document).on('click', '#delete', function() {
            // get room id from the table
            let ids = $(this).data("id")
            let temprow = {}

            // filter alldata which is equal to the room id
            vm.posts.forEach(item => {
              if (item.res_id == ids) {
                temprow = item
              }
            })

            // assign to temp row
            vm.tempDelete = temprow
            console.log("temp row", vm.tempDelete);
            $('#deleteModal').modal('show')
          })

          $(document).on('click', '#view', function() {
            // get room id from the table
            let ids = $(this).data("id")
            let temprow = {}

            // filter alldata which is equal to the room id
            vm.posts.forEach(item => {
              if (item.res_id == ids) {
                temprow = item
              }
            })

            // assign to temp row
            vm.tempRow = temprow
            console.log("temp row", vm.tempRow);
            vm.fetchGuestinfo()
            $('#exampleModalLong').modal('show')
          })

        },
        async deleteRes() {
          await axios.post('./includes/backEndreservation.php', {
            action: 'delete',
            row: this.tempDelete
          }).then(res => {
            console.log(res.data);
            this.fetchData()
          })
        },
        fetchData() {
          axios.post('./includes/backEndreservation.php', {
            action: 'fetchRes'
          }).then(res => {
            // console.log("comes from api", res.data);
            this.posts = res.data
            this.table(res.data)
          })
        },
        async fetchGuestinfo() {
          await axios.post('./includes/backEndreservation.php', {
            action: 'guestInfo',
            id: this.tempRow.res_id
          }).then(res => {
            console.log(res.data);

            this.guestInfo = res.data
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
            // console.log(res.data[0].b_checkin);

            res.data.forEach(item => {
              item.status = ""

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

            console.log(res.data)
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
              }
            ],
          });

        },

      },
      created() {
        this.fetchData()

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
            this.fetchData()
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
            this.fetchData()
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
            this.fetchData()
          }
        })
      }
    }).mount("#resApp")
  </script>

  <!-- Custom scripts for all pages-->
  <script src="./js/sb-admin-2.min.js"></script>


</body>

</html>