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
            <h1 class="h3 mb-0 text-gray-800">Group Reservations</h1>

          </div>
          <!-- Content Row -->
          <div class="row">
            <div class="col-12 mb-2">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Group Reservations</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table display table-bordered" width="100%" id="viewBulkTable" cellspacing="0">


                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Group Name</th>
                          <th>Guest Number</th>
                          <th>Rooms</th>
                          <th>Ava. Rooms</th>
                          <th>Check in</th>
                          <th>Check out</th>
                          <th>Reason</th>
                          <th>Remark</th>
                          <th>Location</th>
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
                {{ tempRow.group_name }}
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                  <th>
                    Name
                  </th>
                  <th>
                    Value
                  </th>
                </thead>
                <tbody>
                  <tr v-for="(value, key) in tempRow">
                    <td>
                      {{ key }}
                    </td>
                    <td>
                      {{ value }}
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>

          </div>
        </div>
      </div>

      <!-- End of View Full Reservation Details -->


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
              <button class="btn btn-primary" @click="deleteGroupRes" data-dismiss="modal">Delete</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Delete Modal  -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Kuriftu resorts 2022. Powered by <a href="https://versavvymedia.com">Versavvy Media</a> </span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

      <!-- Add Guest At Check in Modal -->


      <div class="modal fade" style="z-index: 99999;" id="addGuest" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Guest</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="row">

                <div class="form-group col-6">
                  <label for="recipient-name" class="col-form-label">First Name:</label>
                  <input type="text" v-model="formData.firstName" class="form-control" id="recipient-name">
                </div>
                <div class="form-group col-6">
                  <label for="recipient-name" class="col-form-label">Last Name:</label>
                  <input type="text" v-model="formData.lastName" class="form-control" id="recipient-name">
                </div>
                <div class="form-group col-12">
                  <label for="recipient-name" class="col-form-label">Email:</label>
                  <input type="text" v-model="formData.email" class="form-control" id="recipient-name">
                </div>
                <div class="form-group col-6">
                  <label for="recipient-name" class="col-form-label">Phone:</label>
                  <input type="text" v-model="formData.phone" class="form-control" id="recipient-name">
                </div>

                <div class="form-group col-6">
                  <label for="recipient-name" class="col-form-label">Date of Birth:</label>
                  <input type="date" v-model="formData.dob" class="form-control" id="recipient-name">
                </div>

                <div class="form-group col-6">
                  <label for="recipient-name" class="col-form-label">Check in:</label>
                  <input type="date" class="form-control" :value="tempcheckin" readonly>
                </div>

                <div class="form-group col-6">
                  <label for="recipient-name" class="col-form-label">Check Out:</label>
                  <input type="date" class="form-control" :value="tempcheckout" readonly>
                </div>

                <div class="form-group col-12">
                  <label for="recipient-name" class="col-form-label">Remark</label>
                  <textarea name="" v-model="formData.remark" class="form-control" cols="30" rows="7"></textarea>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <button type="submit" @click.prevent="addGuest" class="btn btn-secondary">Add</button>
            </div>
          </div>
        </div>
      </div>

      <!-- End of Add Guest At Check in Modal -->

      <!-- Select Room for Single guest res  -->

      <div class="modal fade" id="selectRoom" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Select Room

              </h5>
              <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container-fluid">




                <div class="table-responsive">

                  <table class="table display table-bordered table-hover" id="addSingleTable" width="100%" cellspacing="0">

                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Room Id</th>
                        <th>Room Type</th>
                        <th>Room Number</th>
                        <th>Hotel Location</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
            </form>
          </div>
        </div>
      </div>

      <!-- End of Select Room for Single guest res  -->
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
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>
    // Enable pusher logging - don't include this in production




    const app = Vue.createApp({
      data() {
        return {
          posts: [''],
          tempRow: {},
          tempRow: {},
          location: '',
          date: '',
          modal: "",
          tempcheckin: '',
          tempcheckout: '',
          formData: {
            firstName: '',
            lastName: '',
            email: '',
            phone: '',
            dob: '',
            remark: '',
          },
          tempDelete: {},
          g_res_id: '',
          group_name: '',
          rooms: {}
        }
      },
      methods: {
        table(row) {
          $('#viewBulkTable').DataTable({
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
                data: 'group_id'
              },
              {
                data: 'group_name'
              },
              {
                data: 'group_guest'
              },
              {
                data: 'group_roomQuantity'
              },
              {
                data: 'group_remainingRooms'
              },
              {
                data: 'group_checkin'
              },
              {
                data: 'group_checkout'
              },
              {
                data: 'group_reason',
                render: function(data, display){
                  if(data == 'con'){
                    return `Conference`
                  }else if(data == 'wed'){
                    return `Wedding`
                  }

                }
              },
              {
                data: 'group_remark'
              },
              {
                data: 'group_location'
              },
              {
                data: 'group_price'
              },
              {
                data: 'group_id',
                render: function(data, row) {
                  return `<div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                          <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-600"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                          <a class="dropdown-item" data-id="${data}" href="#" id="add">
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
          $(document).on('click', '#add', function() {
            // get room id from the table
            let ids = $(this).data("id")
            let temprow = {}
            vm.g_res_id = ids
            // filter alldata which is equal to the room id
            vm.posts.forEach(item => {
              if (item.group_id == ids) {
                temprow = item
              }
            })

            vm.tempcheckin = temprow.group_checkin
            vm.tempcheckout = temprow.group_checkout
            vm.group_name = temprow.group_name
            vm.getRooms(ids)
            $('#selectRoom').modal('show')
          })

          $(document).on('click', '#delete', function() {
            // get room id from the table
            let ids = $(this).data("id")
            let temprow = {}

            // filter alldata which is equal to the room id
            vm.posts.forEach(item => {
              if (item.group_id == ids) {
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
              if (item.group_id == ids) {
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
        async fetchGuestinfo() {
          await axios.post('group_res.php', {
            action: 'guestInfo',
            id: this.tempRow.group_id
          }).then(res => {
            console.log(res.data);

            this.guestInfo = res.data
          })
        },
        async getRooms(id) {
          console.log(id);
          await axios.post('group_res.php', {
            action: 'getRooms',
            id: id
          }).then(res => {
            console.log(res.data);
            this.rooms = res.data
            this.singleTable(res.data)
          })
        },
        singleTable(row) {
          $('#addSingleTable').DataTable({
            destroy: true,
            dom: 'lBfrtip',
            buttons: [
              'colvis',
              'excel',
              'print',
              'csv'
            ],
            data: row,
            columns: [{
                data: 'g_id'
              },
              {
                data: 'g_room_id'
              },
              {
                data: 'g_room_acc'
              },
              {
                data: 'g_room_number'
              },
              {
                data: 'g_room_location'
              },
              {
                data: 'g_id',
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

            // filter alldata which is equal to the group id
            vm.rooms.forEach(item => {
              if (item.g_id == ids) {
                temprow = item
              }
            })

            // assign to temp row
            vm.tempDelete = temprow
            console.log("temp row", vm.tempDelete);
            $('#addGuest').modal('show')
          })
        },
        async addGuest() {

          console.log("room", this.tempDelete);
          console.log("checkin", this.tempcheckin);
          console.log("checkout", this.tempcheckout);
          console.log("Form data", this.formData);
          console.log("------------------------");
          console.log("group id", this.g_res_id);
          await axios.post('group_res.php', {
            action: 'addSingleRes',
            room: this.tempDelete,
            checkin: this.tempcheckin,
            checkout: this.tempcheckout,
            formData: this.formData,
            group_name: this.group_name,
            group_id: this.g_res_id
          }).then(res => {
            // window.location.href = 'view_bulk_reservations.php'
            console.log(res.data);
          })
        },
        async deleteGroupRes() {
          await axios.post('group_res.php', {
            action: 'delete',
            row: this.tempDelete
          }).then(res => {
            console.log(res.data);
            this.fetchData()
          })
        },
        fetchData() {
          axios.post('group_res.php', {
            action: 'fetchRes'
          }).then(res => {
            console.log("comes from api", res.data);
            this.posts = res.data
            this.table(res.data)
          })
        }
      },
      created() {
        this.fetchData()
        Pusher.logToConsole = true;

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