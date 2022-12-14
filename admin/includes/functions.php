<?php
function confirm($result)
{

  global $connection;
  if (!$result) {
    die('QUERY FAILED ' . mysqli_error($connection));
  }
}

function getName($n)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';

  for ($i = 0; $i < $n; $i++) {
    $index = rand(0, strlen($characters) - 1);
    $randomString .= $characters[$index];
  }

  return $randomString;
}

function CheckAndCutPromo($price, $promo)
{
  global $connection;

  date_default_timezone_set("Africa/Addis_Ababa");

  $promo_query = "SELECT * FROM promo WHERE promo_code = '$promo' AND promo_active = 'yes' LIMIT 1";
  $promo_result = mysqli_query($connection, $promo_query);

  confirm($promo_result);

  $row = mysqli_fetch_assoc($promo_result);


  $PromoId = $row['promo_id'];
  $usage = $row['promo_usage'];


  if ($row['promo_time'] == '' && $row['promo_usage'] == '') {
    $Discount = $price * ($row['promo_amount'] / 100);
    return $price - $Discount;
  } else if ($row['promo_time'] == '' && $row['promo_usage'] !== '') {

    if ($row['promo_usage'] == 0) {
      return $price;
    } else {
      $updated_usage = intval($usage)  - 1;
      $update_promo = "UPDATE promo SET promo_usage = $updated_usage WHERE promo_id = '$PromoId'";
      $promo_result = mysqli_query($connection, $update_promo);
      confirm($promo_result);

      $Discount = $price * ($row['promo_amount'] / 100);
      return $price - $Discount;
    }
  } else if ($row['promo_time'] !== '' && $row['promo_usage'] == '') {

    $expireDate = strtotime($row['promo_time']);
    $today = strtotime(date('Y-m-d H:i:s'));

    if ($today >= $expireDate) {
      return $price;
    } else {
      $Discount = $price * ($row['promo_amount'] / 100);
      return $price - $Discount;
    }
  } else if ($row['promo_time'] !== '' && $row['promo_usage'] !== '') {
    $expireDate = strtotime($row['promo_time']);
    $today = strtotime(date('Y-m-d H:i:s'));
    $usage = $row['promo_usage'];


    if (($today < $expireDate) && ($usage !== 0)) {

      $updated_usage = intval($usage) - 1;
      if ($updated_usage == 0) {
        $update_promo = "UPDATE promo SET promo_usage = $updated_usage, promo_active = 'No' WHERE promo_id = '$PromoId'";
      } else {
        $update_promo = "UPDATE promo SET promo_usage = $updated_usage WHERE promo_id = '$PromoId'";
      }
      $promo_result = mysqli_query($connection, $update_promo);
      confirm($promo_result);

      $Discount = $price * ($row['promo_amount'] / 100);
      return $price - $Discount;
    } else {
      // The Promo code is expired
      return $price;
    }
  }
}


function escape($string)
{

  global $connection;
  return mysqli_real_escape_string($connection, trim($string));
}

function getToken($len)
{
  $rand_str = md5(uniqid(mt_rand(), true));
  $base64_encode = base64_encode($rand_str);
  $modified_base64_encode = str_replace(array('+', '='), array('', ''), $base64_encode);
  $token = substr($modified_base64_encode, 0, $len);

  return $token;
}

function calculatePrice($ad, $kid, $teen, $single, $double, $dMember, $sMemeber, $promo)
{

  $price = 0.00;

  if ($promo == "member") {
    echo json_encode("member");
    if ($ad == 1) {
      if ($kid == 0 && $teen == 0) {
        // Single occupancy
        $price = $sMemeber;
      } else if ($kid == 1 && $teen == 1) {
        $price = $dMember + 10;
      } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
        $price = $dMember;
      } else if ($kid == 2 && $teen == 0) {
        $price = $dMember + 10;
      } else if ($kid == 0 && $teen == 2) {
        $price = $dMember + 38;
      }
    } else if ($ad == 2) {
      if ($kid == 0 && $teen == 0) {
        $price = $dMember;
      } else if ($kid == 1 && $teen == 0) {
        $price = $dMember + 10;
      } else if ($kid == 1 && $teen == 1) {
        $price = $dMember + 48;
      } else if ($kid == 2 && $teen == 0) {
        $price = $dMember + 20;
      } else if ($kid == 0 && $teen == 1) {
        $price = $dMember + 38;
      }
    }
  } else if ($promo == "") {
    // echo json_encode("None");
    if ($ad == 1) {
      if ($kid == 0 && $teen == 0) {
        // Single occupancy
        $price = $single;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 10;
      } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
        $price = $double;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 0 && $teen == 2) {
        $price = $double + 38;
      }
    } else if ($ad == 2) {
      if ($kid == 0 && $teen == 0) {
        $price = $double;
      } else if ($kid == 1 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 48;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 20;
      } else if ($kid == 0 && $teen == 1) {
        $price = $double + 38;
      }
    }
  } else if ($promo !== "" && $promo !== "member") {

    if ($ad == 1) {
      if ($kid == 0 && $teen == 0) {
        // Single occupancy
        $price = $single;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 10;
      } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
        $price = $double;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 0 && $teen == 2) {
        $price = $double + 38;
      }
    } else if ($ad == 2) {
      if ($kid == 0 && $teen == 0) {
        $price = $double;
      } else if ($kid == 1 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 48;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 20;
      } else if ($kid == 0 && $teen == 1) {
        $price = $double + 38;
      }
    }
    $DiscountPrice = CheckAndCutPromo($price, $promo);
    if ($DiscountPrice == $price) {
      return $price;
    } else if ($DiscountPrice > $price) {
      return $price;
    } else {
      $price = $DiscountPrice;
      return $price;
    }
  }

  return $price;
}

function calculateLoft($kid, $teen, $dbRack, $dMember, $promo)
{
  $price = 0.00;

  if ($promo == "member") {
    if ($teen == 0 && $kid == 0) {
      $price = $dMember;
    } else if ($teen == 1 && $kid == 0) {
      $price = $dMember + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $dMember + 10;
    }
  } else if ($promo == "") {
    if ($teen == 0 && $kid == 0) {
      $price = $dbRack;
    } else if ($teen == 1 && $kid == 0) {
      $price = $dbRack + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $dbRack + 10;
    }
  } else if ($promo !== "" && $promo !== "member") {
    if ($teen == 0 && $kid == 0) {
      $price = $dbRack;
    } else if ($teen == 1 && $kid == 0) {
      $price = $dbRack + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $dbRack + 10;
    }

    $DiscountPrice = CheckAndCutPromo($price, $promo);
    if ($DiscountPrice == $price) {
      return $price;
    } else if ($DiscountPrice > $price) {
      return $price;
    } else {
      $price = $DiscountPrice;
      return $price;
    }
  }


  return $price;
}

function calculatePriceAwash($ad, $kid, $teen, $Bored, $days, $selectedDB)
{
  $price = 0.00;
  foreach ($days as $day) {
    switch ($day) {
      case 'Friday':
        if ($Bored == "fullBoard") {

          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_fb_we'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_fb_we'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_fb_we'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_fb_we'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_fb_we'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_fb_we'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_fb_we'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_fb_we'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_fb_we'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_fb_we'] + 38;
            }
          }
        } else if ($Bored == "Half Board") {

          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_hb_we'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_hb_we'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_hb_we'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_hb_we'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_hb_we'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_hb_we'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_hb_we'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_hb_we'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_hb_we'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_hb_we'] + 38;
            }
          }
        } else if ($Bored == "BedBreakfast") {
          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_bb_we'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_bb_we'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_bb_we'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_bb_we'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_bb_we'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_bb_we'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_bb_we'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_bb_we'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_bb_we'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_bb_we'] + 38;
            }
          }
        }
        break;
      case 'Saturday':
        if ($Bored == "fullBoard") {

          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_fb_we'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_fb_we'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_fb_we'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_fb_we'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_fb_we'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_fb_we'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_fb_we'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_fb_we'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_fb_we'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_fb_we'] + 38;
            }
          }
        } else if ($Bored == "Half Board") {

          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_hb_we'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_hb_we'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_hb_we'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_hb_we'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_hb_we'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_hb_we'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_hb_we'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_hb_we'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_hb_we'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_hb_we'] + 38;
            }
          }
        } else if ($Bored == "BedBreakfast") {
          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_bb_we'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_bb_we'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_bb_we'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_bb_we'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_bb_we'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_bb_we'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_bb_we'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_bb_we'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_bb_we'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_bb_we'] + 38;
            }
          }
        }
        break;
      default:
        if ($Bored == "fullBoard") {

          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_bb_wd'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_fb_wd'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_fb_wd'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_fb_wd'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_fb_wd'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_fb_wd'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_fb_wd'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_fb_wd'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_fb_wd'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_fb_wd'] + 38;
            }
          }
        } else if ($Bored == "Half Board") {

          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_hb_wd'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_hb_wd'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_hb_wd'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_hb_wd'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_hb_wd'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_hb_wd'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_hb_wd'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_hb_wd'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_hb_wd'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_hb_wd'] + 38;
            }
          }
        } else if ($Bored == "BedBreakfast") {
          if ($ad == 1) {
            if ($kid == 0 && $teen == 0) {
              // Single occupancy
              $price += $selectedDB['s_bb_wd'];
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_bb_wd'] + 10;
            } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
              $price += $selectedDB['d_bb_wd'];
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_bb_wd'] + 10;
            } else if ($kid == 0 && $teen == 2) {
              $price += $selectedDB['d_bb_wd'] + 38;
            }
          } else if ($ad == 2) {
            if ($kid == 0 && $teen == 0) {
              $price += $selectedDB['d_bb_wd'];
            } else if ($kid == 1 && $teen == 0) {
              $price += $selectedDB['d_bb_wd'] + 10;
            } else if ($kid == 1 && $teen == 1) {
              $price += $selectedDB['d_bb_wd'] + 48;
            } else if ($kid == 2 && $teen == 0) {
              $price += $selectedDB['d_bb_wd'] + 20;
            } else if ($kid == 0 && $teen == 1) {
              $price += $selectedDB['d_bb_wd'] + 38;
            }
          }
        }
        break;
    }
  }
  return $price;
}
function calculateEntoto($ad, $kid, $teen, $double, $single, $promo)
{
  $price = 0.00;
  if ($promo == "") {
    // echo json_encode("None");
    if ($ad == 1) {
      if ($kid == 0 && $teen == 0) {
        // Single occupancy
        $price = $single;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 10;
      } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
        $price = $double;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 0 && $teen == 2) {
        $price = $double + 38;
      }
    } else if ($ad == 2) {
      if ($kid == 0 && $teen == 0) {
        $price = $double;
      } else if ($kid == 1 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 48;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 20;
      } else if ($kid == 0 && $teen == 1) {
        $price = $double + 38;
      }
    }
  } else if ($promo !== "" && $promo !== "member") {

    if ($ad == 1) {
      if ($kid == 0 && $teen == 0) {
        // Single occupancy
        $price = $single;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 10;
      } else if (($kid == 1 && $teen == 0) || ($kid == 0 && $teen == 1)) {
        $price = $double;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 0 && $teen == 2) {
        $price = $double + 38;
      }
    } else if ($ad == 2) {
      if ($kid == 0 && $teen == 0) {
        $price = $double;
      } else if ($kid == 1 && $teen == 0) {
        $price = $double + 10;
      } else if ($kid == 1 && $teen == 1) {
        $price = $double + 48;
      } else if ($kid == 2 && $teen == 0) {
        $price = $double + 20;
      } else if ($kid == 0 && $teen == 1) {
        $price = $double + 38;
      }
    }
    $DiscountPrice = CheckAndCutPromo($price, $promo);
    if ($DiscountPrice == $price) {
      return $price;
    } else if ($DiscountPrice > $price) {
      return $price;
    } else {
      $price = $DiscountPrice;
      return $price;
    }
  }


  return $price;
}

function calculatePre($kid, $teen, $double, $dMember, $promo)
{

  $price = 0.00;

  if ($promo == "member") {
    if ($teen == 0 && $kid == 0) {
      $price = $dMember;
    } else if ($teen == 1 && $kid == 0) {
      $price = $dMember + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $dMember + 10;
    }
  } else if ($promo == "") {
    if ($teen == 0 && $kid == 0) {
      $price = $double;
    } else if ($teen == 1 && $kid == 0) {
      $price = $double + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $double + 10;
    }
  } else if ($promo !== "" && $promo !== "member") {
    if ($teen == 0 && $kid == 0) {
      $price = $double;
    } else if ($teen == 1 && $kid == 0) {
      $price = $double + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $double + 10;
    }

    $DiscountPrice = CheckAndCutPromo($price, $promo);
    if ($DiscountPrice == $price) {
      return $price;
    } else if ($DiscountPrice > $price) {
      return $price;
    } else {
      $price = $DiscountPrice;
      return $price;
    }
  }

  return $price;
}

function calculatePreEntoto($kid, $teen, $double, $promo)
{
  $price = 0.00;

  if ($promo == "") {
    if ($teen == 0 && $kid == 0) {
      $price = $double;
    } else if ($teen == 1 && $kid == 0) {
      $price = $double + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $double + 10;
    }
  } else if ($promo !== "" && $promo !== "member") {
    if ($teen == 0 && $kid == 0) {
      $price = $double;
    } else if ($teen == 1 && $kid == 0) {
      $price = $double + 38;
    } else if ($teen == 0 && $kid == 1) {
      $price = $double + 10;
    }

    $DiscountPrice = CheckAndCutPromo($price, $promo);
    if ($DiscountPrice == $price) {
      return $price;
    } else if ($DiscountPrice > $price) {
      return $price;
    } else {
      $price = $DiscountPrice;
      return $price;
    }
  }

  return $price;
}
