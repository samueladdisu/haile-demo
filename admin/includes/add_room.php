<?php

if ($_SESSION['user_role'] == 'RA') {
  header("Location: ./rooms.php");
}
?>
<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href)
  }
</script>
<?php
$incoming = json_decode(file_get_contents("php://input"));
if (isset($_POST['bishoftu_room'])) {
  $room_acc         =  escape($_POST['b_room_acc']);
  $room_number      =  escape($_POST['b_room_number']);

  $query_dup = "SELECT * FROM rooms WHERE room_acc = '$room_acc' AND room_number = '$room_number' AND room_location = 'Bishoftu'";

  $result_dup = mysqli_query($connection, $query_dup);
  confirm($result_dup);
  $row = mysqli_num_rows($result_dup);

  if ($row > 0) {
    echo "<script> alert('This room already exits!') </script>";
    // $row2 = mysqli_fetch_assoc($result_dup);

    // var_dump($row2);
  } else {
    // echo "can";
    $acc_query  = "SELECT * FROM room_type WHERE type_name = '$room_acc'";
    $acc_result = mysqli_query($connection, $acc_query);

    confirm($acc_result);

    while ($row = mysqli_fetch_assoc($acc_result)) {
      $occ = $row['occupancy'];
      $price = $row['d_rack_rate'];
      $img = $row['room_image'];
      $loc = $row['type_location'];
      $room_desc = escape($row['room_desc']);
    }


    $query = "INSERT INTO `rooms` (`room_occupancy`, `room_acc`, `room_price`, `room_image`, `room_number`, `room_status`, `room_location`, `room_desc`) VALUES ('$occ', '$room_acc', '$price', '$img', '$room_number', 'Not_booked', '$loc', '$room_desc');";


    $result = mysqli_query($connection, $query);

    confirm($result);
  }
}

if (isset($_POST['awash_room'])) {
  $room_acc         =  escape($_POST['a_room_acc']);
  $room_number      =  escape($_POST['a_room_number']);

  $acc_query  = "SELECT * FROM awash_price WHERE name = '$room_acc'";
  $acc_result = mysqli_query($connection, $acc_query);

  confirm($acc_result);

  while ($row = mysqli_fetch_assoc($acc_result)) {
    $occ = escape($row['occupancy']);
    $price = escape($row['double_occ']);
    $img = escape($row['room_img']);
    $room_desc = escape($row['room_desc']);
  }


  $query = "INSERT INTO `rooms` (`room_occupancy`, `room_acc`, `room_price`, `room_image`, `room_number`, `room_status`, `room_location`, `room_desc`) VALUES ('$occ', '$room_acc', '$price', '$img', '$room_number', 'Not_booked', 'awash', '$room_desc');";


  $result = mysqli_query($connection, $query);

  confirm($result);
}

if (isset($_POST['entoto_room'])) {
  $room_acc         =  escape($_POST['e_room_acc']);

  $room_number      =  escape($_POST['e_room_number']);

  $acc_query  = "SELECT * FROM entoto_price WHERE name = '$room_acc'";
  $acc_result = mysqli_query($connection, $acc_query);

  confirm($acc_result);

  while ($row = mysqli_fetch_assoc($acc_result)) {
    $occ = escape($row['occupancy']);
    $price = escape($row['double_occ']);
    $img = escape($row['room_img']);
    $room_desc = escape($row['room_desc']);
  }


  $query = "INSERT INTO rooms (room_occupancy, room_acc, room_price, room_image, room_number, room_status, room_location, room_desc) VALUES ('$occ', '$room_acc', '$price', '$img', '$room_number', 'Not_booked', 'entoto', '{$room_desc}');";


  $result = mysqli_query($connection, $query);

  confirm($result);
}

if (isset($_POST['tana_room'])) {
  $room_acc         =  escape($_POST['t_room_acc']);
  $room_number      =  escape($_POST['t_room_number']);

  $acc_query  = "SELECT * FROM tana_price WHERE name = '$room_acc'";
  $acc_result = mysqli_query($connection, $acc_query);

  confirm($acc_result);

  while ($row = mysqli_fetch_assoc($acc_result)) {
    $occ = escape($row['occupancy']);
    $price = escape($row['double_occ']);
    $img = escape($row['room_img']);
    $room_desc = escape($row['room_desc']);
  }


  $query = "INSERT INTO `rooms` (`room_occupancy`, `room_acc`, `room_price`, `room_image`, `room_number`, `room_status`, `room_location`, `room_desc`) VALUES ('$occ', '$room_acc', '$price', '$img', '$room_number', 'Not_booked', 'Lake tana', '$room_desc');";


  $result = mysqli_query($connection, $query);

  confirm($result);
}
?>





<form action="" id="bishoftuRoom" method="POST" class="col-6 mt-4" enctype="multipart/form-data">

  <div class="form-group">
    <label for="room_acc"> Room Location</label>

    <select name="b_room_acc" class="custom-select" required>
      <option value="">Select option</option>
      <?php
      $location_query = "SELECT * FROM locations";
      $result = mysqli_query($connection, $location_query);

      confirm($result);
      while ($row = mysqli_fetch_assoc($result)) {
        $type_name         = $row['location_name'];
      ?>

        <option value='<?php echo $type_name ?>'><?php echo $type_name ?></option>
      <?php
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="room_acc"> Room Type</label>

    <select name="b_room_acc" class="custom-select" required>
      <option value="">Select option</option>
      <?php
      $bishoftu_query = "SELECT * FROM room_type";
      $result = mysqli_query($connection, $bishoftu_query);

      confirm($result);
      while ($row = mysqli_fetch_assoc($result)) {
        $type_name         = $row['type_name'];
      ?>

        <option value='<?php echo $type_name ?>'><?php echo $type_name ?></option>
      <?php
      }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="post_tags"> Room Number </label>
    <input type="text" class="form-control" name="b_room_number" required>
  </div>


  <div class="form-group">
    <input type="submit" class="btn btn-primary" name="bishoftu_room" value="Add Room">
  </div>

</form>