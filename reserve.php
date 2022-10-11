<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Kuriftu - Reservation</title>
</head>

<body>
    <header class="reserve-header">
        <nav class="nav-center">

            <div class="logo">
                <img src="./img/black_logo.svg" alt="">
            </div>
        </nav>
    </header>



    <section class="Destinations-list">
        <div class="container" style="display: grid; place-items: center; height: 60vh">
            <h2>
                Page Under Construction
            </h2>

        </div>

    </section>
</body>

</html> -->

<?php include  'config.php'; ?>
<?php
$RoomType = '';
$Location = '';
if (isset($_GET['location'])) {
  $Location = $_GET['location'];

  if ($Location == 'Tana' || $Location == 'tana') {
    $Location = "Lake tana";
  }
} else {
  $Location = '';
}
if (isset($_GET['roomType'])) {
  $RoomType = $_GET['roomType'];
} else {
  $RoomType = '';
}

if (isset($_GET['checkin'])) {
  $checkin = $_GET['checkin'];
} else {
  $checkin = '';
}

if (isset($_GET['checkout'])) {
  $checkout = $_GET['checkout'];
} else {
  $checkout = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

  <!-- <link rel="stylesheet" href="css/t-datepicker.min.css">
  <link rel="stylesheet" href="css/themes/t-datepicker-green.css"> -->
  <link rel="stylesheet" href="./css/style.css">


  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



  <title>Reservation</title>
</head>

<body>


  <div id="app">
    <header class="header reserve-header">
      <div class="container">
        <nav class="nav-center">

          <div class="logo">
            <img src="./img/HaileLogo.png" alt="">
          </div>
          <div class="line">
            <div class="container1">
              <hr class="line1">
              <ul class="justify-list">

                <li>
                  <a class="link-text" href="./">Back to Resorts</a>
                </li>
                <li>
                  <a class="link-text" href="./signUp.php">Sign Up</a>
                </li>
                <li>
                  <a class="link-text" href="./signIn.php">Login</a>
                </li>
              </ul>


              <hr class="line2">
            </div>
          </div>


          <?php

          if (isset($_SESSION['m_username'])) {
            $user_name =  $_SESSION['m_username'];
          ?>
            <div class="profile">
              <div @click="showDropdown" class="profile-icon">
                <h1 class="profile-name">
                  SA
                </h1>

              </div>

              <div v-if="dropdown" class="drop-down">
                <ul>
                  <li><a href="./profile.php"> <i class="fa-solid fa-user"></i> Profile</a></li>
                  <li> <a href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i> Log out</a></li>
                </ul>
              </div>
            </div>

          <?php
          } else {
            $user_name = null;
          ?>



          <?php
          }

          ?>


        </nav>



      </div>
    </header>
    <section class="resort-wall">
      <?php
      if ($Location == "bishoftu") { ?>
        <img src="./img/2.webp" alt="">
      <?php } else if ($Location == "awash") { ?>
        <img src="./img/awash-cover.webp" alt="">
      <?php } else if ($Location == "entoto") { ?>
        <img src="./img/Glamping.webp" alt="">
      <?php } else if ($Location == "Lake tana") { ?>
        <img src="./img/Tana.webp" alt="">
      <?php } ?>
    </section>
    <div class="container-sm" v-if="haveData">
      <div class="form-wrapper">

        <div class="modal" tabindex="-1" role="dialog" id="TimesUP">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Kuriftu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>are you still there.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="clearOrder">Cancel</button>
                <button type="button" class="btn btn-red BAlert" data-dismiss="modal" @click="TimerExtend()">Yes</button>
              </div>
            </div>
          </div>
        </div>

        <form class="myform row" @submit.prevent="submitData">

          <div class="form-group col-lg-6">
            <label for="date" class="label-date" id="label">Select Date</label>
            <input type="text" class="form-control" name="daterange" id="date" value="" readonly />
          </div>
          <div class="form-group col-lg-6">
            <button type="submit" class="btn btn-black" id="check">
              Check Availability
            </button>
          </div>
        </form>
      </div>


      <div class="lg-xl-wide">


        <!-- available rooms -->

        <div class=" mt-3 single-room-detail">
          <div class="mycard mt-5 mt-lg-3" v-for="rows in roomName" :key="rows.room_id">

            <?php
            if ($Location == "bishoftu") { ?>
              <img :src="'./admin/room_img/' + rows.room_image" class="mycard-img-top">
            <?php } else if ($Location == "awash") { ?>
              <img :src="'./admin/room_img/awash/' + rows.room_image" class="mycard-img-top">
            <?php } else if ($Location == "entoto") { ?>
              <img :src="'./admin/room_img/entoto/' + rows.room_image" class="mycard-img-top">
            <?php } else if ($Location == "Lake tana") { ?>
              <img :src="'./admin/room_img/tana/' + rows.room_image" class="mycard-img-top">
            <?php } ?>
            <div class="mycard-body">
              <h5 class="mycard-title">
                {{ rows.room_acc }}
              </h5>
              <p class="mycard-price padding-top">
                <small class="text-muted">

                  {{ rows.room_location}}
                </small>


                <small class="text-red">
                  Only {{ rows.cnt }} left
                </small>
              </p>
              <p class="mycard-text">
              <ul>
                <!-- <li v-for="am in amenities(row.room_amenities)" :key="am">
                  {{ am }}
                </li> -->
              </ul>
              </p>
              <p class="mycard-text">
                {{ rows.room_desc.substring(0,147)+("...")}}
              </p>
              <!-- <a href="#popup1" class="pop-btn button ">Room Detail
                <hr class="btn-line ">
              </a> -->
              <hr class="divide-line" />
              <div class="flex-lg">
                <ul class="aminties-list">
                  <h4>Amenities</h4>
                  <li>Bathroom with a Shower</li>
                  <li>Wifi</li>
                  <li>COVID Kit</li>
                </ul>
                <div>

                  <div class="price-style">
                    <p class="price-txt">
                      ${{ rows.room_price }}

                    </p> Per Night
                  </div>

                  <div class="btn-container1">
                    <a @click.prevent="addRoom(rows)" class="btn btn-black">Select Room</a>

                  </div>
                </div>
              </div>
            </div>
            <!-- </div> -->
          </div>
        </div>

        <div class="">
          <div v-if="cart.length != 0" class="cart-container ">
            <div class="cart-image">
              <img src="./admin/room_img/kuriftu.jpg" alt="">
            </div>
            <div class="cart-wrapper mt-lg-5">
              <h2 class="cart-title">Your Stay At Kuriftu</h2>
              <div class="cart" v-for="items of cart" :key="items.id">
                <div class="upper">
                  <h3>{{ items.room_acc }} - {{ items.room_location}}</h3>

                </div>
                <div class="guest-info">
                  <div class="check-out">
                    <h3>Check Out</h3>
                    <p>{{items.checkout}}</p>
                  </div>
                  <div class="check-in">
                    <h3>Check in</h3>
                    <p>{{items.checkin}}</p>
                  </div>
                  <div class="guests">
                    <h3>Guests</h3>
                    <p>{{ items.adults }} Adults {{ items.teens }} Teens {{ items.kids }} Kids</p>
                  </div>
                </div>

                <div class="lower">
                  <p class="text-muted">
                  </p>

                  <p class="text-muted">
                    ${{ items.room_price }} Per Night
                  </p>
                </div>
                <div class="cl-icon close" @click.prevent="deleteRoom(items)">
                  <i class="bi bi-trash">Delete</i>
                  <!-- <i class="edit-icon"><img src="./img/edit.svg" alt=""> 
                    <p class="edit-p">edit</p>
                    </i>
                     -->
                  <!-- <i class="add-icon"><img src="./img/add2.svg" alt="">
                    <p class="add-p">Add Room</p>
                  </i> -->
                </div>



              </div>


              <hr>
              <div class="footer-btn promo mb-3">
              </div>
              <div class="cart-footer-lg" v-if="cart.length != 0">

                <div class="footer-btn">
                  <a @click.prevent="completeCart" class="btn btn-black"> BOOK NOW</a>
                </div>

                <div class="price">
                  Total: ${{ totalprice }} <br>
                  Rooms: {{ cart.length }}
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="bottom-cart" v-if="cart.length != 0">

        <div class="bottom-cart-modal" v-if="toggleModal">
          <div class="cart-header">
            <h3 class="summary-title">Booking Summary</h3>
            <div class="close" @click="openModal">
              <img src="./img/close2.svg" alt="">
              <!-- <i class="bi bi-x"></i> -->
            </div>

          </div>
          <hr>

          <div class="cart-content" v-for="items in cart" :key="items.id">
            <div class="upper">
              <h3>{{ items.room_acc }} - {{ items.room_location}}</h3>
              <div class="close" @click.prevent="deleteRoom(items)">
                <i class="bi bi-trash"></i>
              </div>
            </div>

            <div class="lower">
              <p class="text-muted">
              </p>

              <p class="text-muted">
                ${{ items.room_price }}
              </p>
            </div>

            <hr>


          </div>

        </div>

        <div class="cart-footer">
          <div class="price">
            <div>
              Total: ${{ totalprice }} <br>
              Rooms: {{ cart.length }}
            </div>

            <div @click="openModal">
              <i class="bi bi-chevron-up"></i>
            </div>
          </div>

          <div class="footer-btn">

            <a @click.prevent="completeCart" class="btn btn-primary1"> BOOK NOW</a>
          </div>
        </div>


      </div>
    </div>
    <div v-else>
      <div class="centerSAD">
        <div class="container-sm complete-cont  ">
          <div class="check-box-wrapper">
            <div class="check-icon">
              <img src="./img/sad2.svg" alt="">
              <p>No room available!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="guest">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Guests</h5>
            <button type="button btn" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="closeGuestModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <div class="row">
                <div class="col-3">
                  <label class="form-label">Adults</label>
                  <select name="adults" v-model="res_adults" @change="CheckGuest" class="form-select">
                    <option selected>Adults *</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>

                </div>
                <div class="col-3 offset-1">
                  <label class="form-label">Teens(12 - 17)</label>
                  <select name="adults" @change="CheckGuest" v-model="res_teen" class="form-select" :disabled="teen">
                    <option selected>Teens(12-17)</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                </div>
                <div class="col-3 offset-1">
                  <label class="form-label">Kid (6-11)</label>
                  <select name="adults" @change="CheckGuest" v-model="res_kid" class="form-select" :disabled="kid">
                    <option selected>Kid (6-11)</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                </div>


              </div>
              <?php
              if ($Location == "awash") {
              ?>
                <form>
                  <div class="row BBModal">
                    <div class="form-check col-3">
                      <input class="form-check-input costom" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="fullBoard" v-model="res_BB" required>
                      <label class="form-check-label" for="flexRadioDefault3">
                        full board
                      </label>
                    </div>
                    <div class="form-check col-3">
                      <input class="form-check-input costom" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="Half Board" v-model="res_BB" required>
                      <label class="form-check-label" for="flexRadioDefault2">
                        Half board
                      </label>
                    </div>
                    <div class="form-check col-3">
                      <input class="form-check-input costom" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="BedBreakfast" v-model="res_BB" required>
                      <label class="form-check-label" for="flexRadioDefault1">
                        Bed and Breakfast
                      </label>
                    </div>



                  </div>
                </form>
              <?php
              }
              ?>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" @click="nextBook" data-dismiss="modal" class="btn btn-black">Add Room</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="loftModal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Guests</h5>
            <button type="button btn" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="closeGuestModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="" class="text-dark">Guest Number</label>
              <div class="row">
                <select name="adults" v-model="res_adults" class="custom-select col-3">
                  <option value="" disabled selected>Guests</option>
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>

                <select name="adults" @change="CheckGuest" v-model="res_teen" class="custom-select col-3 offset-1" :disabled="loftTeen">
                  <option value="" disabled>Teens(12-17)</option>
                  <option value="0">0</option>
                  <option value="1">1</option>
                </select>

                <select name="adults" @change="CheckGuest" v-model="res_kid" class="custom-select col-3 offset-1" :disabled="loftKid">
                  <option value="" disabled>Kid (6-11)</option>
                  <option value="0">0</option>
                  <option value="1">1</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" @click="nextBook" data-dismiss="modal" class="btn btn-black">Add Room</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="popup1" class="overlay">
    <div class="popup" v-for="items in cart" :key="items.id">
      <a class="close" href="#">&times;</a>
      <div class="content">
        <h2>
          <!-- {{ rows.room_desc.substring(0,147)+("...")}} -->
          Deluxe Lake Front King Size Bed
        </h2>
        <div class="popup-content-wrapper">
          <img src="./admin/room_img/bed_king.jpg" class="mycard-img-top pop-img" alt="...">
          <div>
            <p class="mycard-price padding-top2">
              <small class="text-muted ">
                <!-- {{ rows.room_location}} --> Bishoftu
              </small>
              <small class="text-red">
                <!-- Only {{ rows.cnt }} left -->-
                Only 5 left
              </small>
            </p>
            <p class="mycard-text  padding-top2">
            <ul>
            </ul>
            </p>
            <p class="mycard-text">
              Lorem ipsum, dolor sit amet consectetur adipisicing elit. Asperiores ipsam vel quae temporibus maiores excepturi explicabo, sit neque blanditiis deleniti alias obcaecati repudiandae rem similique deserunt, provident esse reprehenderit? Tenetur.
            </p>
          </div>
        </div>

        <div class="mycard-body">


          <h4 class="amenities-txt">Room Amenities
          </h4>
          <hr class="divide-line" />
          <ul class="aminties-list1">


            <li>Wifi</li>
            <li> PBX Phone</li>
            <li>Hair-dryer</li>
            <li> Adapters</li>

            <li>COVID Kit</li>
            <li>32” HDTV</li>
            <li>Slippers</li>
            <li>Mini Fridge</li>
            <li> Luggage Rack</li>
            <li>Umbrella</li>
            <li> Ironing Board</li>
            <li>Bathrobe</li>
            <li>Tea and Coffee Maker</li>

            <li>Safety Deposit Box</li>
            <li> Private Balcony with Day Bed</li>
            <li>Private bathroom with a shower</li>

          </ul>

        </div>
      </div>
    </div>
  </div>

  </div>
  <footer>
    <div class="container">
      <img src="./img/black_logo.svg" alt="">
      <p>All Copyright &copy; 2022 Kuriftu Resort and Spa. Powered by <a href="https://versavvymedia.com/" target="_blank">Versavvy</a></p>
    </div>

  </footer>


  <?php include_once './includes/footer.php' ?>






  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

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

    // window.addEventListener('beforeunload', function(e) {
    //     e.preventDefault();
    //     // e.returnValue = '';
    //     $('#guest').modal('show')

    // });


    <?php
    if ($checkin !== '' && $checkout !== '') {
    ?>

      let checkin = '<?php echo $checkin ?>'
      let checkout = '<?php echo $checkout ?>'
      console.log("before");
      console.log(checkin);
      console.log(checkout);

      start = checkin
      end = checkout

      function formatDate(input) {
        var datePart = input.match(/\d+/g),
          year = datePart[0], // get only two digits
          month = datePart[1],
          day = datePart[2];

        return month + '/' + day + '/' + year;
      }

      checkin = formatDate(checkin)
      checkout = formatDate(checkout)

      console.log("after");
      console.log(checkin);
      console.log(checkout);



      $(document).ready(function() {

        console.log("initial start", start);
        console.log("initial end", end);
        $('#date').daterangepicker();
        $('#date').data('daterangepicker').setStartDate(checkin);
        $('#date').data('daterangepicker').setEndDate(checkout);

        $('#date').on('apply.daterangepicker', function(ev, picker) {
          // console.log(picker.startDate.format('YYYY-MM-DD'));
          // console.log(picker.endDate.format('YYYY-MM-DD'));

          start = picker.startDate.format('YYYY-MM-DD')
          end = picker.endDate.format('YYYY-MM-DD')
          console.log("updated start", start);
          console.log("updated end", end);
        });
      })

    <?php
    } else {
    ?>
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
    <?php
    }
    ?>


    const user = '<?php echo $user_name ?>'
    const app = Vue.createApp({
      mounted() {

      },
      data() {
        return {
          res_BB: '',
          haveData: true,
          guestModal: false,
          checkIn: '',
          checkOut: '',
          desti: '',
          allData: '',
          cart: [],
          totalprice: '',
          promoCode: '',
          toggleModal: false,
          oneClick: false,
          isPromoApplied: '',
          dropdown: false,
          guest: false,
          res_adults: '0',
          res_teen: '0',
          res_kid: '0',
          loftKid: false,
          loftTeen: false,
          res_guest: [],
          kid: false,
          teen: false,
          roww: '',
          totalpriceCart: 0.00,
          PriceArray: [],
          currSeconds: 0,
          timer: 0,
          roomName: []
        }
      },

      methods: {

        closeGuestModal() {
          this.guestModal = false
          $('#guest').modal('hide')
        },

        popOutSelected(row) {

          let temp = [];
          let newArray = []
          this.allData.forEach(item => {
            newArray = item.filter(data => {
              return !(data.room_acc == row.room_acc && data.room_id == row.room_id)

            })
            temp.push(newArray)
          })

          console.log("Original Data: ", this.allData);

          console.log("Filterd Data: ", temp);
          var Vm = this;

          this.allData = temp
          // Vm.$set(this.allData, temp)
          Vue.nextTick(() => {
            console.log("view updated")
          })
          console.log("Updated Original Data: ", this.allData);

          // Outerfunction(temp);
          this.takeOneEach(this.allData)

        },

        TimerExtend() {
          $("#TimesUP").modal("hide");
          this.resetTimer;
        },
        clearOrder() {

          this.cart.forEach(eachID => {
            axios
              .post("book.php", {
                action: "ClearHold",
                RoomId: eachID.room_id,
              })
              .then((response) => {
                sessionStorage.clear();
                window.location.href = "reserve.php?location=<?php echo $Location; ?>";
              });

          })
        },

        CheckGuest() {
          if (this.res_adults == "1") {

            if ((this.res_teen == "2" && this.res_kid == "2") || (this.res_teen == "2" && this.res_kid == "1") || (this.res_teen == "1" && this.res_kid == "2")) {
              this.adults = 0;
              this.teens = 0;
              this.kids = 0;
              alert("This combination of guest numbers is not possible.");
            } else {
              this.res_guest = [this.res_adults, this.res_teen, this.res_kid];

            }


          } else if (this.res_adults == "2") {

            if ((this.res_teen == "2" && this.res_kid == "2") || (this.res_teen == "2" && this.res_kid == "0") || (this.res_teen == "1" && this.res_kid == "2")) {
              this.res_teen = 0;
              this.res_kid = 0;
              alert("This combination of guest numbers is not possible.");

            } else {
              this.res_guest = [this.res_adults, this.res_teen, this.res_kid];
            }
          } else if (this.res_adults == "0") {
            alert("adult cant be 0");

            // this.teens = 0;
            // this.kids = 0;
          } else {
            if (this.res_teen == 1) {
              this.loftKid = true
              this.res_kid = 0
            } else {
              this.loftKid = false
            }

            if (this.res_kid == 1) {
              this.loftTeen = true
              this.res_teen = 0
            } else {
              this.loftTeen = false
            }
            this.res_guest = [this.res_adults, this.res_teen, this.res_kid];

          }

        },
        showDropdown() {
          this.dropdown = !this.dropdown
        },
        openModal() {
          this.toggleModal = !this.toggleModal

        },
        fetchPromo() {
          this.oneClick = true
          if (!sessionStorage.promo) {

            axios.post('book.php', {
              action: 'promoCode',
              data: this.promoCode,
              TotalPrice: JSON.parse(sessionStorage.total)
            }).then(res => {
              console.log(res.data)
              // let discount = this.totalprice - ((res.data / 100) * this.totalprice)
              this.totalprice = res.data.toFixed(2)
              sessionStorage.total = JSON.stringify(this.totalprice)
            })
            this.isPromoApplied = true
            sessionStorage.promo = this.isPromoApplied
          }
          this.isPromoApplied = JSON.parse(sessionStorage.promo || false)
        },
        completeCart() {

          if (start && end) {

            axios.post('book.php', {
              action: 'insert',
              checkIn: start,
              checkOut: end,
              location: this.desti,
              selectedLocation: '<?php echo $Location; ?>',
              data: this.cart,
              total: this.totalprice,
              totalroom: this.cart.length
            }).then((res) => {
              console.log(res.data);
              window.location.href = "register.php"

            })
          } else {
            alert("Please Select Check in and Check out date")
          }

        },
        addRoom(row) {
          if (start && end) {
            this.roww = row;

            console.log(row.room_id)
            if (row.room_acc === "Loft Family Room" || row.room_acc === "Presidential Family Room" || row.room_acc === "Presidential Suite Family Room") {
              $('#loftModal').modal('show')
            } else {

              $('#guest').modal('show')
            }
            this.guestModal = true

          } else {
            alert("Please Select Dates")
          }



        },
        nextBook() {
          let rooms = 0;
          var total = 0.00;
          row = this.roww
          console.log(this.res_BB)
          <?php if ($Location == "awash") { ?>
            if (this.res_adults === 'undefined' || this.res_adults == null || this.res_adults == "0") {
              alert("Adult can not be 0")
            } else if (this.res_BB == '') {
              alert("Please select from the given options")
            } else {
              axios.post('book.php', {
                action: 'hold',
                roomID: this.roww.room_id,
              }).then((res) => {

                if (user) {

                  if (row.cnt > 0) {

                    this.row.adults = this.res_guest[0],
                      this.row.teens = this.res_guest[1],
                      this.row.kids = this.res_guest[2],
                      this.cart.push(row)
                    this.cart.forEach(val => {
                      total += (parseInt(val.room_price) - (0.15 * parseInt(val.room_price))) * this.nights
                    })
                    this.totalprice = total
                    sessionStorage.total = JSON.stringify(this.totalprice)
                    sessionStorage.cart = JSON.stringify(this.cart)
                    sessionStorage.BB = JSON.stringify(this.res_BB)
                    localStorage.roomId = JSON.stringify(this.cart.room_id)

                    row.cnt--
                  }
                } else {

                  if (row.cnt > 0) {

                    let guests = {
                      checkin: start,
                      checkout: end,
                      adults: this.res_guest[0],
                      teens: this.res_guest[1],
                      kids: this.res_guest[2],
                      reservationBoard: this.res_BB
                    }
                    // console.log("row size:" + Object.keys(row).length);

                    let PutTogeter = Object.assign(row, guests)
                    this.cart.push(PutTogeter);
                    let temparray = [];

                    axios.post('book.php', {
                      action: 'calculatePrice',
                      data: this.cart,
                      dataBin: this.res_BB
                    }).then((res) => {
                      console.log(res.data);
                      this.PriceArray = res.data;

                    }).then((res) => {
                      this.PriceArray.forEach(val => {
                        total += val;
                      })
                      this.totalprice = total.toFixed(2);
                      console.log(this.cart.room_id);
                      let roomIDs = [];
                      this.cart.forEach(val => {
                        roomIDs.push(val.room_id);
                      })
                      localStorage.roomId = JSON.stringify(roomIDs)

                      sessionStorage.total = JSON.stringify()
                      sessionStorage.cart = JSON.stringify(this.cart)
                      sessionStorage.setItem("priceContainer", JSON.stringify(this.PriceArray));


                      row.cnt--

                      this.popOutSelected(row)
                    })

                    console.log("out");

                  }
                }

              }).then(res => {
                window.onmousemove = this.resetTimer;
                window.onmousedown = this.resetTimer;
                window.ontouchstart = this.resetTimer;
                window.onclick = this.resetTimer;
                window.onkeypress = this.resetTimer;
              })
              guests = {}
              this.res_adults = "0"
              this.res_teen = "0"
              this.res_kid = "0"
              // this.res_BB = ""
              $('#guest').modal('hide')
            }

          <?php } else { ?>
            if (this.res_adults === 'undefined' || this.res_adults == null || this.res_adults == "0") {
              alert("Adult can not be 0")
            } else {
              axios.post('book.php', {
                action: 'hold',
                roomID: this.roww.room_id,
              }).then((res) => {
                console.log(res.data);
                if (user) {

                  if (row.cnt > 0) {

                    this.row.adults = this.res_guest[0],
                      this.row.teens = this.res_guest[1],
                      this.row.kids = this.res_guest[2],
                      this.cart.push(row)
                    this.cart.forEach(val => {
                      total += (parseInt(val.room_price) - (0.15 * parseInt(val.room_price))) * this.nights
                    })
                    this.totalprice = total
                    sessionStorage.total = JSON.stringify(this.totalprice)
                    sessionStorage.cart = JSON.stringify(this.cart)
                    sessionStorage.BB = JSON.stringify(this.res_BB)

                    row.cnt--
                  }
                } else {

                  if (row.cnt > 0) {

                    let guests = {
                      checkin: start,
                      checkout: end,
                      adults: this.res_guest[0],
                      teens: this.res_guest[1],
                      kids: this.res_guest[2],
                      reservationBoard: ""
                    }
                    // console.log("row size:" + Object.keys(row).length);

                    let PutTogeter = Object.assign(row, guests)
                    this.cart.push(PutTogeter);
                    let temparray = [];

                    axios.post('book.php', {
                      action: 'calculatePrice',
                      data: this.cart,
                      dataBin: this.res_BB
                    }).then((res) => {
                      console.log(res.data);
                      this.PriceArray = res.data;
                    }).then((res) => {
                      this.PriceArray.forEach(val => {
                        total += val;
                      })
                      this.totalprice = total.toFixed(2);
                      let roomIDs = [];
                      this.cart.forEach(val => {
                        roomIDs.push(val.room_id);
                      })
                      localStorage.roomId = JSON.stringify(roomIDs)
                      sessionStorage.total = JSON.stringify(this.totalprice)
                      sessionStorage.cart = JSON.stringify(this.cart)
                      sessionStorage.setItem("priceContainer", JSON.stringify(this.PriceArray));
                      row.cnt--

                      this.popOutSelected(row)
                    })

                  }
                }

              }).then(res => {
                window.onmousemove = this.resetTimer;
                window.onmousedown = this.resetTimer;
                window.ontouchstart = this.resetTimer;
                window.onclick = this.resetTimer;
                window.onkeypress = this.resetTimer;
              })
              guests = {}
              this.res_adults = "0"
              this.res_teen = "0"
              this.res_kid = "0"
              this.res_BB = ""
              $('#guest').modal('hide')
              $('#loftModal').modal('hide')
            }
          <?php } ?>
        },

        booked() {


        },

        deleteRoom(row) {
          var tempBack = []
          var retrievedData = sessionStorage.getItem("priceContainer");
          var PriceCon = JSON.parse(retrievedData);
          let deleteTotal = 0;

          axios.post('book.php', {
            action: 'clearHold',
            roomID: row.room_id,
          }).then((res) => {
            console.log(res.data);
            if (user) {
              let cartIndex = this.cart.indexOf(row)
              this.cart.splice(cartIndex, 1)
              this.cart.forEach(val => {
                deleteTotal += (parseInt(val.room_price) - (0.15 * parseInt(val.room_price))) * this.nights

              })
              this.totalprice = deleteTotal
              sessionStorage.cart = JSON.stringify(this.cart)
              row.cnt++
            } else {
              let found = false;
              let cartIndex = this.cart.indexOf(row)
              this.cart.splice(cartIndex, 1)
              PriceCon.splice(cartIndex, 1)
              PriceCon.forEach(val => {
                deleteTotal += val;

              })

              this.allData.forEach(item => {
                for (let key2 in item) {

                  if (item[0].room_acc == row.room_acc) {
                    found = true;
                    console.log(item[0])
                    row.cnt--
                    item.push(row)
                    break;

                  }

                }

              })
              if (found == false) {
                let TempoArray = []
                row.cnt--
                TempoArray.push(row)
                this.allData[this.allData.length++] = TempoArray
              }
              this.takeOneEach(this.allData)
              console.log(" Delete new Array", this.allData)

              this.totalprice = deleteTotal.toFixed(2)
              sessionStorage.total = JSON.stringify(this.totalprice)
              sessionStorage.cart = JSON.stringify(this.cart)
              sessionStorage.setItem("priceContainer", JSON.stringify(PriceCon))
              // row.cnt++



            }
            var legth = JSON.parse(sessionStorage.cart)
            console.log(legth)
            if (legth == 0) {
              console.log("hey")
              sessionStorage.clear();
              clearInterval(this.timer);
              window.onload = '';
              window.onmousemove = '';
              window.onmousedown = '';
              window.ontouchstart = '';
              window.onclick = '';
              window.onkeypress = '';


            }
          })

        },
        async fetchAllData() {



          <?php
          if ($Location == '' && $RoomType == '' && $checkin == '' && $checkout == '') {
          ?>
            await axios.post('book.php', {
              action: 'fetchall',

            }).then(res => {
              this.allData = res.data
              console.log(res.data);
              this.takeOneEach(this.allData)


            })
          <?php } else if ($Location !== '' && $RoomType == '' && $checkin == '' && $checkout == '') { ?>

            await axios.post('book.php', {
              action: 'fetchallLocation',
              location: '<?php echo $Location; ?>'

            }).then(res => {
              this.allData = res.data
              this.takeOneEach(this.allData)
              console.log(res.data);
            })


          <?php } else if ($Location !== '' && $RoomType !== '' && $checkin == '' && $checkout == '') { ?>

            await axios.post('book.php', {
              action: 'fetchallLocRom',
              location: '<?php echo $Location; ?>',
              roomType: '<?php echo $RoomType; ?>'
            }).then(res => {
              this.allData = res.data
              console.log(res.data);
              this.takeOneEach(this.allData)
            })

          <?php } else if ($Location !== '' && $checkin !== '' && $checkout !== '') {
          ?>
            await axios.post('book.php', {
              action: 'filter',
              location: '<?php echo $Location; ?>',
              checkin: '<?php echo $checkin; ?>',
              roomType: '',
              checkout: '<?php echo $checkout; ?>'
            }).then(res => {
              this.allData = res.data
              console.log(res.data);
              this.takeOneEach(this.allData)
            })


          <?php  }
          ?>
        },

        takeOneEach(dataToShorten) {

          if (dataToShorten.length == 0) {
            this.haveData = false
          } else {
            this.roomName = []
            dataToShorten.forEach(data1 => {

              let i = 0;
              if (data1.length !== 0) {
                // break;
                data1[i]["cnt"] = data1.length;
                this.roomName.push(data1[0])
              }

            })


          }
        },
        startIdleTimer() {
          console.log("ideal")
          if (sessionStorage.cart !== 2) {



            if (this.sec >= 6) {
              this.sec--;
            } else if (this.sec == 0) {
              if (this.min > 0) {
                this.min--;
              } else if (this.min == 0) {
                this.clearOrder();
              }
            } else if (this.sec == 5) {
              this.sec--;
              $('#TimesUP').appendTo("body").modal('show');
            } else if (this.sec <= 4) {
              this.sec--;
            }
          }
        },
        resetTimer() {
          /* Clear the previous interval */
          clearInterval(this.timer);

          /* Reset the seconds of the timer */
          this.sec = '1800';
          this.min = '1';
          /* Set a new interval */
          this.timer = setInterval(this.startIdleTimer, 1000);
        },

        checkAvailablity() {
          axios.post('book.php', {
            action: 'fetchall'
          }).then(res => {
            this.allData = res.data

          })
        },


        async submitData() {

          if (start && end) {
            await axios.post('book.php', {
              action: 'filter',
              checkin: start,
              checkout: end,
              location: '<?php echo $Location; ?>',
            }).then(res => {
              this.allData = res.data
              console.log(this.allData);
              this.takeOneEach(this.allData)
            }).catch(err => {
              console.log(err);
            })
          } else {
            alert('Please fill all fields')
          }
        },
        checkLocalStorage() {


          if (sessionStorage.cart) {
            window.onload = this.resetTimer;
            window.onmousemove = this.resetTimer;
            window.onmousedown = this.resetTimer;
            window.ontouchstart = this.resetTimer;
            window.onclick = this.resetTimer;
            window.onkeypress = this.resetTimer;

          }

        }
        // amenities(am) {

        //   let amt = JSON.parse(JSON.parse(am))
        //   let arryAmt = new Array()
        //   arryAmt = amt

        //   // arryAmt.filter(ary => {
        //   //   return ary.replace("\"", '')
        //   // })
        //   return arryAmt
        // },

      },
      computed: {
        nights() {
          var checkin = new Date(start);
          var checkout = new Date(end);
          var Difference_In_Time = checkout.getTime() - checkin.getTime();
          var stayedNights = Difference_In_Time / (1000 * 3600 * 24);
          return stayedNights
        }
      },
      created() {
        this.fetchAllData()
        this.cart = JSON.parse(sessionStorage.cart || '[]')
        this.totalprice = JSON.parse(sessionStorage.total || '[]')
        this.checkLocalStorage()

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
            this.fetchAllData()
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
            this.fetchAllData()
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
            this.fetchAllData()
          }
        })

      }
    })
    app.mount('#app')
    $(".button").on("click", function() {
      var $button = $(this);
      var oldValue = $button.parent().find("input").val();
      $button.blur();
      if ($button.hasClass("inc")) {
        var newVal = parseFloat(oldValue) + 1;
      } else {
        if (oldValue > 0) {
          var newVal = parseFloat(oldValue) - 1;
        } else {
          newVal = 0;
        }
      }
      $button.parent().find("input").val(newVal);
    });
    $('#reveal-click').click(function() {
      $('#reveal-wrap #hidden-div').slideToggle({
        direction: "up"
      }, 300);
      $(this).toggleClass('clientsClose');
    }); // end click
    $(function() {
      $(".pop-btn").click(function() {
        $(".pop-up").fadeIn("300");
      })
      $(".pop-up,.close").click(function() {
        $(".pop-up").fadeOut("300");
      })
      $(".pop-wrapper").click(function(e) {
        e.stopPropagation();
      })
    })
  </script>


</body>

</html>