<?php

if ($_SESSION['user_role'] == 'RA') {
  header("Location: ./rooms.php");
}
?>

<?php
  $incoming = json_decode(file_get_contents("php://input"));
if (isset($_POST['add_room'])) {


 

  $room_occupancy   =  escape($_POST['room_occupancy']);
  $room_acc         =  escape($_POST['room_acc']);
  $room_number      =  escape($_POST['room_number']);
  $room_status      =  escape($_POST['room_status']);
  $room_location    =  escape($_POST['room_location']);
  $room_price       =  escape($_POST['room_price']);
  $room_amenities   =  $_SESSION['amt'];
  $room_desc        =  escape($_POST['room_desc']);
  $room_image       = $_FILES['room_image']['name'];
  $room_image_temp  = $_FILES['room_image']['tmp_name'];

  move_uploaded_file($room_image_temp, "./room_img/$room_image");

  $room_amenities = json_encode($room_amenities);


  $query = "INSERT INTO `rooms` (`room_occupancy`, `room_acc`, `room_price`, `room_image`, `room_number`, `room_status`, `room_location`, `room_amenities`, `room_desc`) VALUES ('$room_occupancy', '$room_acc', '$room_price', '$room_image', '$room_number', '$room_status', '$room_location', '$room_amenities', '$room_desc');";


  $result = mysqli_query($connection, $query);

  confirm($result);
  header("Location: ./rooms.php");
}
?>


<form action="" id="addRoom" method="POST" class="col-6" enctype="multipart/form-data">

  <div class="form-group">
    <label for="title">Room Occupancy</label>
    <input type="text" class="form-control" name="room_occupancy">
  </div>

  <div class="form-group">
    <label for="room_acc"> Room Type</label>

    <select name="room_acc" class="custom-select" id="">
      <option value="">Select option</option>
      <?php
      $location = $_SESSION['user_location'];
      if ($location == 'Boston') {
        $query = "SELECT * FROM room_type";
      } else {
        $query = "SELECT * FROM room_type WHERE type_location = '$location'";
      }
      $result = mysqli_query($connection, $query);

      confirm($result);
      while ($row = mysqli_fetch_assoc($result)) {
        $type_id = $row['type_id'];
        $type_name = $row['type_name'];
        $type_room_price = $row['room_price'];
        $temp_type = "";
        if (strcmp($type_name, $temp_type) == 0) {
          continue;
        } else {
      ?>

          <option value='<?php echo $type_name ?>'><?php echo $type_name ?></option>
      <?php
        }
      }

      ?>
    </select>
  </div>
  <div class="form-group">
    <input type="hidden" name="room_price" value='<?php echo $type_room_price ?>'>
  </div>

  <div class="form-group">
    <label for="post_image"> Room Image</label>
    <input type="file" name="room_image">
  </div>

  <div class="form-group">
    <label for="post_tags"> Room Number </label>
    <input type="text" class="form-control" name="room_number">
  </div>
  <div class="form-group">
    <label for="room_status"> Room Status</label>
    <select name="room_status" class="custom-select" id="">
      <option value="Not_booked">Select Options</option>
      <option value="booked">Booked</option>
      <option value="Not_booked">Not Booked</option>
    </select>
  </div>
  <?php

  if ($_SESSION['user_location'] == 'Boston' && $_SESSION['user_role'] == 'SA') {

  ?>
    <div class="form-group">
      <label for="location">Resort Location</label>
      <select name="room_location" class="custom-select" id="">
        <option value="">Select Option</option>
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
  <?php  } ?>

  <div class="form-group">
    <label for="amt">Room Amenities</label>
    <input type="text" name="room_amenities" @keyup.alt="addAmt" v-model="tempAmt" id="amt" class="form-control">
  </div>
  <div class="my-1">
    <span v-for="am in amt" :key="am" class="badge-pill px-3 mx-1 py-1 badge-dark">

      <span @click="deleteAmt(am)"> {{ am }}<i class="fal fa-times pl-2"></i></span>
    </span>
  </div>

  <div class="form-group">
    <label for="post_content"> Room Description</label>
    <textarea name="room_desc" id="" cols="30" rows="10" class="form-control"></textarea>
  </div>


  <div class="form-group">
    <input type="submit" class="btn btn-primary" name="add_room" value="Add Room">
  </div>

</form>