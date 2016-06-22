<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('clean_date')) {

    function clean_date($mydate) {
        if (strlen($mydate) == 6) {
            return $mydate;
        } else {
            $mydate = str_replace("-", "/", $mydate);
            $mydate = str_replace(".", "/", $mydate);
            $mydate = str_replace(" ", "/", $mydate);
            $mydate = date("mdy", strtotime($mydate));
            return $mydate;
        }
    }

}

function valid_phone_number($value) {
    $value = trim($value);
    if ($value == '') {
        return FALSE;
    } else {
        if (preg_match('/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/', $value)) {
            //return preg_replace('/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', '($1) $2-$3', $value);
            return preg_replace('/\D/', $value);
        } else {
            return FALSE;
        }
    }
}

/*
  if ( ! function_exists('pass_req'))
  {
  function pass_req($pass)
  {
  $myreply = array();

  $digits = preg_replace('|[^0-9]|', '', $pass);
  $digits_count = strlen($digits);
  $alphas = preg_replace('|[^a-zA-Z]|', '', $pass);
  $alphas_count = strlen($alphas);

  if (strlen($pass) >= 6) {
  if ($digits_count == strlen($pass)) {
  $myreply["good"] = false;
  $myreply["message"] = "Password may not be all numeric.";
  } else if ($digits_count >= 2) {
  $CI =& get_instance();
  $query = $CI->db->query('SELECT passid from tbl_bad_pass where password='.$CI->db->escape($pass));
  if ($query->num_rows() >= 1) {
  $myreply["good"] = false;
  $myreply["message"] = "Please select a less generic password.";
  } else {
  $myreply["good"] = true;
  $myreply["message"] = "";
  }
  } else {
  $myreply["good"] = false;
  $myreply["message"] = "Password must contain at least TWO numbers.";
  }
  } else {
  $myreply["good"] = false;
  $myreply["message"] = "Password is too short.";
  }
  return $myreply;
  }
  }
 */

if (!function_exists('check_login')) {

    function check_login() {
        $CI = & get_instance();
        if (!$CI->session->userdata('logged_in')) {
            redirect('login');
        } else {
            return TRUE;
        }
    }

}

if (!function_exists('check_is_staff')) {

    function check_is_staff() {
        $CI = & get_instance();
        if (!$CI->session->userdata('is_staff')) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
?>
