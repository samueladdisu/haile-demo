<?php include  './includes/db.php'; ?>
<?php include  './includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./css/reserve.css">
  <script src="https://unpkg.com/vue@3.0.2"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <title>Reservation</title>
</head>

<body>

  <header class="header">
    <div class="container">
      <nav class="nav-center">
        <div class="menu">
          <div class="line1"></div>
          <div class="line2"></div>
        </div>
        <div class="logo">
          <img src="./img/Kuriftu_logo.svg" alt="">
        </div>
      </nav>

      <div class="side-socials">
        <img src="./img/facebook.svg" alt="">
        <img src="./img/instagram.svg" alt="">
        <img src="./img/youtube.svg" alt="">
      </div>

    </div>
  </header>

  <div class="container" id="app">
    <form class="myform" @submit.prevent="submitData">


      <div class="mb-3">
        <select class="form-select mySelect" v-model="desti">
          <option disabled value="">Choose Destination</option>
          <?php

          $query = "SELECT * FROM locations";
          $result = mysqli_query($connection, $query);

          confirm($result);

          while ($row = mysqli_fetch_assoc($result)) {
            $location_id = $row['location_id'];
            $location_name = $row['location_name'];
          ?>
            <option value='<?php echo $location_name ?>'><?php echo $location_name ?></option>
          <?php  }

          ?>
        </select>

      </div>


      <div class="mb-3">
        <input type="text" class="form-control" v-model="checkIn" placeholder="Check in" onfocus="this.type='date'" onblur="(this.type='text')" id="check-in">
      </div>
      <div class="mb-3">
        <input type="text" class="form-control" v-model="checkOut" placeholder="Check in" onfocus="this.type='date'" id="check-out">
      </div>


      <button type="submit" class="btn btn-primary1">Check Availability</button>
    </form>
    <!-- 
    <p>{{ checkIn }}</p>
    <p>{{ checkOut }}</p> -->

    <div class="row">


      <!-- available rooms -->

      <div class="col-lg-8 mt-5">
        <div class="mycard mt-5" v-for="row in allData" :key="row.room_id">


          <img :src="'./room_img/' + row.room_image" class="mycard-img-top" alt="...">
          <div class="mycard-body">
            <h5 class="mycard-title">
              {{ row.room_acc }}
            </h5>
            <p class="mycard-price">
              <small class="text-muted">
                ${{ row.room_price }} Per Night
              </small>

              <small class="text-red">
                only {{ row.cnt }} left
              </small>
            </p>
            <p class="mycard-text">
              {{ row.room_desc.substring(0,238)+".."}}
            </p>
            <div class="btn-container1">
              <a href="" @click.prevent="addRoom(row)" class="btn btn-primary1">Select Room</a>

            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 mt-5">
        <div class="cart-container mt-5">
          <h2 class="cart-title">Your Stay At Kuriftu</h2>

          <div class="cart-date">
            <div class="u-checkin">
              <h3 class="t-chkin">Check-in</h3>
              <h3>{{ checkIn }}</h3>
            </div>

            <div class="u-checkin">
              <h3 class="t-chkin">Check-out</h3>
              <h3>{{ checkOut }}</h3>
            </div>

          </div>

          <div class="cart" v-for="items of cart" :key="items.id">
            <div class="upper">
              <h3>{{ items.room_acc }} - {{ items.room_location}}</h3>
              <div class="close" @click.prevent="deleteRoom(items)">
                <i class="bi bi-trash"></i>
              </div>
            </div>

            <div class="lower">
              <p class="text-muted">
                {{ guests }}
              </p>

              <p class="text-muted">
                ${{ items.room_price }}
              </p>
            </div>

          </div>

          <hr>
          <div class="cart-footer-lg" v-if="cart.length != 0">

            <div class="footer-btn">
              <a @click.prevent="completeCart" class="btn btn-primary1"> BOOK NOW</a>
            </div>

            <div class="price">
              Total: ${{ totalprice }} <br>
              Rooms: {{ cart.length }}
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
            <i class="bi bi-x"></i>
          </div>

        </div>
        <hr>
        <div class="date">
          <div class="check-in">
            <h3>Check-in</h3>
            <h3>{{ checkIn }}</h3>
          </div>

          <div class="check-in">
            <h3>Check-out</h3>
            <h3>{{ checkOut }}</h3>
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
              {{ guests }}
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


  <footer class="footer">
    <div class="container">
      <section class="footer-wrapper">
      <div class="footer-link-container">
        <div class="upper">
          <div class="desti">
            <h3 class="desti-title">
              Destination
            </h3>
            <ul class="desti-list">
              <div>
                <li class="footer-link"><a href="#">Bishoftu</a></li>
                <li class="footer-link"><a href="#">Entoto</a></li>
                <li class="footer-link"><a href="#">Awash</a></li>
              </div>

              <div>
                <li class="footer-link"><a href="#">Water Park</a></li>
                <li class="footer-link"><a href="#">Lake Tana</a></li>
                <li class="footer-link"><a href="#"></a></li>
              </div>
            </ul>
          </div>

          <div class="desti">
            <h3 class="desti-title">
              Wellness
            </h3>
            <ul class="desti-list">
              <div>
                <li class="footer-link"><a href="#">Spa</a></li>
                <li class="footer-link"><a href="#">Pool</a></li>
                <li class="footer-link"><a href="#">Massage</a></li>
              </div>

              <div>
                <li class="footer-link"><a href="#">Manicure </a></li>
                <li class="footer-link"><a href="#">Pedicure</a></li>
                <li class="footer-link"><a href="#"></a></li>
              </div>
            </ul>
          </div>
        </div>

        <div class="upper">
          <div class="desti">
            <h3 class="desti-title">
              Experience
            </h3>
            <ul class="desti-list">
              <div>
                <li class="footer-link"><a href="#">Kayaking</a></li>
                <li class="footer-link"><a href="#">Archery</a></li>
                <li class="footer-link"><a href="#">Cycling</a></li>
              </div>

              <div>
                <li class="footer-link"><a href="#">Paintball </a></li>
                <li class="footer-link"><a href="#">Horse riding</a></li>
                <li class="footer-link"><a href="#"></a></li>
              </div>
            </ul>
          </div>

          <div class="desti">
            <h3 class="desti-title">
              Quick Links
            </h3>
            <ul class="desti-list">
              <div>
                <li class="footer-link"><a href="#">Home</a></li>
                <li class="footer-link"><a href="#">Entoto</a></li>
                <li class="footer-link"><a href="#">Our Story</a></li>
              </div>

              <div>
                <li class="footer-link"><a href="#">Lake Tana </a></li>
                <li class="footer-link"><a href="#">Awash</a></li>
                <li class="footer-link"><a href="#"></a></li>
              </div>
            </ul>
          </div>

        </div>
      </div>



      <div class="social">
        <h3 class="desti-title">follow us on</h3>

        <div class="icon-container">
          <img src="./img/facebook.svg" alt="">
          <img src="./img/instagram.svg" alt="">
          <img src="./img/youtube.svg" alt="">
        </div>
      </div>
      </section>
      
      <hr>
      <div class="lower">
        
        <img src="./img/Kuriftu_logo.svg" alt="">
        <p>&copy; 2021. All Rights Reserved. Web Design & Development by <a href="https://versavvymedia.com/">Versavvy Media PLC</a> </p>
      </div>


    </div>
  </footer>
  <script>
    const app = Vue.createApp({

      data() {
        return {
          checkIn: '',
          checkOut: '',
          desti: '',
          allData: '',
          cart: [],
          totalprice: '',
          toggleModal: false
        }
      },

      methods: {
        openModal() {
          this.toggleModal = !this.toggleModal

        },

        completeCart() {
          console.log(this.cart);

          if (this.checkOut != '' && this.checkin != '') {

            axios.post('book.php', {
              action: 'insert',
              checkIn: this.checkIn,
              checkOut: this.checkOut,
              location: this.desti,
              data: this.cart
            }).then(res => {
              window.location.href = "register.php"
            })
          } else {
            alert("Please Select Check in and Check out date")
          }

        },
        addRoom(row) {
          let total = 0;
          let rooms = 0;
          if (row.cnt > 0) {

            this.cart.push(row)
            this.cart.forEach(val => {
              total += parseInt(val.room_price)

            })
            this.totalprice = total
            // localStorage.setItem('cart',JSON.stringify(row))  
            console.log(this.cart);
            row.cnt--
          }
          // console.log(this.cart);
        },
        deleteRoom(row) {
          this.cart.pop(row)
          console.log(this.cart);

          row.cnt++
        },
        fetchAllData() {
          axios.post('book.php', {
            action: 'fetchall'
          }).then(res => {
            this.allData = res.data

            // console.log(this.cart);
          })
        },
        async submitData() {
          if (this.checkIn != '' && this.checkOut != '' && this.desti != '') {
            await axios.post('book.php', {
              action: 'getData',
              checkIn: this.checkIn,
              checkOut: this.checkOut,
              desti: this.desti
            }).then(res => {
              this.allData = res.data
              console.log(res.data);
              console.log(this.allData);
            })
          } else {
            alert('Please fill all fields')
          }
        }


      },
      created() {
        this.fetchAllData()
        // this.cart = JSON.parse(localStorage.getItem('cart') || '[]')
        // console.log(this.cart);
      }

    })

    app.mount('#app')
  </script>
</body>

</html>