<?php ob_start(); ?>
<?php include  './includes/db.php'; ?>
<?php include  './includes/functions.php'; ?>
<?php session_start(); ?>

<?php

if (!isset($_SESSION['user_role'])) {
  header("Location: ./index.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Kuriftu Resort - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.css" />


  <link rel="stylesheet" href="./css/t-datepicker.min.css">
  <link rel="stylesheet" href="./css/themes/t-datepicker-green.css">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

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
            <h1 class="h3 mb-0 text-gray-800">Rooms</h1>

          </div>
          <!-- Content Row -->
          <div class="row">

            <div id="viewRoom" class="mb-2 col-12">


              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Room Inventory</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="roomTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Occupancy</th>
                          <th>Accomodation</th>
                          <th>Image</th>
                          <th>Price</th>
                          <th>Number</th>
                          <th>Status</th>
                          <th>Location</th>
                          <th>Description</th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>


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
            </div>

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


          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->




      <script src="https://unpkg.com/vue@3.0.2"></script>


      <!-- <script src="https://unpkg.com/axios/dist/axios.min.js"></script> -->
      <script src="https://unpkg.com/axios@0.27.2/dist/axios.min.js"></script>
      <!-- Core plugin JavaScript-->


      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
      <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
      <script src="./js/t-datepicker.min.js"></script>
      <script>
        const roomApp = Vue.createApp({
          data() {
            return {
              location: '',
              roomType: '',
              allData: '',
              bookedRooms: [],
              rowId: [],
              posts: [''],
              tempRow: {},
              page: 1,
              perPage: 9,
              pages: [],
              tempDelete: {}
            }
          },
          methods: {
            table(row) {
              $('#roomTable').DataTable({
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
                    data: 'room_image',
                    render: function(data, type, row) {
                      if (row.room_location === 'entoto') {
                        return '<img width="100" src="./room_img/entoto/' + data + '">'
                      } else if (row.room_location === 'awash') {
                        return '<img width="100" src="./room_img/awash/' + data + '">'
                      } else if (row.room_location === 'Lake tana') {
                        return '<img width="100" src="./room_img/tana/' + data + '">'
                      } else {
                        return '<img width="100" src="./room_img/' + data + '">'
                      }

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
                    data: 'room_desc',
                    render: function(data, row) {
                      return data.substr(0, 10) + '…'

                    }
                  },
                  {
                    data: 'room_id',
                    render: function(data) {
                      return `<a href="rooms.php?source=edit_room&p_id=${data}">
							<i style="color: turquoise" class="far fa-edit"></i>
						</a>`
                    }
                  },
                  {
                    data: 'room_id',
                    render: function(data, type, row) {
                      return `<a data-toggle="modal" data-row="${data}"  id="deleteRow">
							<i style='color: red;' class='far fa-trash-alt'></i>
						</a>`
                    }
                  }
                ],
                searchPanes: {
                  "viewTotal": true
                },
              });

              let vm = this

              $(document).on('click', '#deleteRow', function() {
                let id = $(this).data("row")
                console.log(id);
                let temprow = {}

                vm.allData.forEach(item => {
                  if (item.room_id == id) {
                    temprow = item
                  }
                });

                vm.tempDelete = temprow
                console.log("temp row", vm.tempDelete);
                $('#deleteModal').modal('show')
              })

            },
            // setTemp(row) {
            //   this.tempDelete = row
            // },
            async deleteRes() {

              await axios.post('./includes/load_avialable_rooms.php', {
                action: 'delete',
                row: this.tempDelete
              }).then(res => {
                console.log(res.data);
                this.fetchAll()
              })
            },
            async filterRooms() {
              // console.log(this.location);

              if (user_location) {
                location_value = user_location.value
              } else {
                location_value = this.location
              }
              console.log(location_value);

              await axios.post('./includes/load_avialable_rooms.php', {
                action: 'filter',
                location: location_value,
                roomType: this.roomType,
                checkin: start,
                checkout: end
              }).then(res => {
                console.log(res.data);
                this.allData = res.data
              }).catch(err => console.log(err.message))

              console.log("filtered data", this.allData);
            },
            clearFilter() {
              this.fetchAll()
            },

            fetchAll() {
              axios.post('./includes/load_avialable_rooms.php', {
                action: 'fetchAllRoom'
              }).then(res => {
                this.allData = res.data
                this.table(res.data)

                console.log(this.allData);
              })
            },
          },
          created() {

            this.fetchAll()

          }
        })

        roomApp.mount('#viewRoom')
      </script>
      <!-- data table plugin  -->


      <script src="js/sb-admin-2.min.js"></script>

</body>

</html>