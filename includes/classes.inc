<?php
class EmailObject {
  function set_from($from) {
    $this->from = $from;
  }

  function set_to($to) {
    $this->to = $to;
  }

  function set_cc($cc) {
    $this->cc = $cc;
  }

  function set_bcc($bcc) {
    $this->bcc = $bcc;
  }

  function set_subject($subject) {
    $this->subject = $subject;
  }

  function set_message($message) {
    $this->message = $message;
  }

  function set_format($format) {
    if ($format == "H") {
      $this->format = "text/html";
    }
    else {
      $this->format = "text/plain";
    }
  }

  function add_attachment($file) {
    $this->attachment[] = $file;
  }

  function send() {
    $headers  = "From: $this->from\r\n";
    if ($this->cc) {
      $headers .= "cc: $this->cc\r\n";
    }
    if ($this->bcc) {
      $headers .= "bcc: $this->bcc\r\n";
    }
    if (sizeof($this->attachment) > 0) {
      $this->mime_boundary = "<<<--==+X[" . md5(time()) . "]";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: multipart/mixed;\r\n";
      $headers .= " boundary=\"$this->mime_boundary\"\r\n";

      $headers .= "--" . $this->mime_boundary . "\r\n";
      $headers .= "Content-type: $this->format\r\n";
      $headers .= "Content-Transfer-Encoding: 8bit\r\n\n";
      $headers .= "$this->message\n";

      foreach($this->attachment as $n => $a) {
        if (preg_match("/\/([^\/]+)$/", $a, $match)) {
          $filename = $match[1];
        }
        else {
          $filename = $a;
        }
        $fp = fopen($a, "r");
        $content = "";
        while (!feof($fp)) {
          $content .= fread($fp, 1024);
        }
        fclose($fp);
        $this->content = $content;

        $this->encoded_content = chunk_split(base64_encode($this->content));

        $headers .= "\r\n";
        $headers .= "--" . $this->mime_boundary . "\r\n";
        $headers .= "Content-Type: application/octet-stream; ";
        $headers .= " name=\"$filename\"\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n";
        $headers .= "Content-Disposition: attachment; ";
        $headers .= "filename=\$filename\"\r\n\n";
        $headers .= "$this->encoded_content\r\n";
      }
      $headers .= "--" . $this->mime_boundary . "--";
    }
    else {
      $headers .= "Content-type: $this->format\r\n";
    }
    $this->headers = $headers;

    $result = mail($this->to, $this->subject, $this->message, $this->headers); 
    return $result;
  }
}


class Page {
  function Page($tag) 
  {
    global $connect;
  
    $sql = "SELECT * FROM site_general ";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->metatitle = $row->g_metatitle;
      $this->metakeywords = $row->g_metakeywords;
      $this->metadescription = $row->g_metadescription;
    }

    $sql = "SELECT * FROM site_content " .
           "WHERE cont_tag='$tag' ";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      if ($row->cont_metatitle) {
        $this->metatitle = $row->cont_metatitle;
      }
      if ($row->cont_metakeywords) {
        $this->metakeywords = $row->cont_metakeywords;
      }
      if ($row->cont_metadescription) {
        $this->metadescription = $row->cont_metadescription;
      }
      if ($row->cont_text) {
        $cont_text = $row->cont_text;
        $para = explode("\r\n\r\n", $cont_text);
        $text = "";
        while (list($n, $p) = each($para)) {
          $p = "      <p>" . preg_replace("/\r\n/", "<br />", $p) . "</p>";
          $text .= $p . "\n";
        }
        $this->pagecontent = fix_html($text);
      }
      if ($row->cont_layout) {
        $this->layout = $row->cont_layout;
      }
    }
  }
}


class Site {
  function Site() 
  {
    global $connect;
    $sql = "SELECT * FROM site_admin_misc ";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->name = $row->am_name;
      $this->address = preg_replace("/\r\n/", "<br>", $row->am_address);
      $this->email = $row->am_email;
      $this->base = $row->am_base;
      $this->adminbase = $row->am_adminbase;
    }
  }
}


class Login {
  function Login($id) 
  {
    global $connect;
    $this->id = $id;
  
    $sql = "SELECT * FROM site_users " .
           "WHERE u_id='$this->id'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->first = $row->u_first;
      $this->name = format_name($row->u_first, $row->u_last);
      $this->email = $row->u_email;
    }
  }
}


class Nav {
  function Nav()
  {
    global $PATH;
    global $connect;
    $homenavimg = array();
    $navimg = array();
    $homenavitem = array();
    $navitem = array();
    $homebottomnavitem = array();
    $bottomnavitem = array();
    $sql = "SELECT * FROM site_nav " .
           "ORDER BY nav_seq";
    $result = mysql_query($sql, $connect);
    while ($row = mysql_fetch_object($result)) {
      $navimg[] = '"' . $row->nav_tag . '"';
      $command = preg_match("/^https?:\/\//", $row->nav_command) ? $row->nav_command : "$PATH/$row->nav_command";
      $navitem[] = "<a href=\"$command\">$row->nav_label</a>";
      $bottomnavitem[] = "<a href=\"$command\">$row->nav_label</a>";
      if ($row->nav_tag != "home") {
        $homenavimg[] = '"' . $row->nav_tag . '"';
        $homenavitem[] = "<a href=\"$command\">$row->nav_label</a>";
        $homebottomnavitem[] = "<a href=\"$command\">$row->nav_label</a>";
      }
    }
    $this->homenavimglist = implode(",", $homenavimg);
    $this->homenavbar = implode(" | ", $homenavitem);
    $this->homebottomnavbar = implode(" | ", $homebottomnavitem);
    $this->navimglist = implode(",", $navimg);
    $this->navbar = implode(" | ", $navitem);
    $this->bottomnavbar = implode(" | ", $bottomnavitem);
  }
}


class Members {
  function Members($id) 
  {
    $connect = $_REQUEST["connect"];

    $sql = "SELECT * FROM site_members " .
           "WHERE m_id='$id' ";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->id = $row->m_id;
      $this->last = $row->m_last;
      $this->first = $row->m_first;
      $this->email = $row->m_email;
      $this->emailformat = $row->m_emailformat;
      $this->about = $row->m_about;
      $this->image = $row->m_image;
    }
  }
}


class Offices {
  function Offices($id)
  {
    $connect = $_REQUEST["connect"];

    $sql = "SELECT * FROM site_offices " .
           "WHERE o_id='$id'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->id = $row->o_id;
      $this->office = $row->o_office;
      $this->display = $row->o_display;
      $this->seq = $row->o_seq;
    }
    $sql = "SELECT MAX(o_seq) AS follow FROM site_offices " .
           "WHERE o_seq < '$row->o_seq'";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->follow = $row->follow;
    }
  }
}


class Gallery {
  function Gallery($data) {
    $GALLERY_PATH =& $_REQUEST["GALLERY_PATH"];
    $THUMB_WIDTH =& $_REQUEST["THUMB_WIDTH"];
    $this->id = $data->p_id;
    $this->galleryid = $data->p_galleryid;
    $this->width = $data->p_width;
    $this->height = $data->p_height;
    $this->thumbwidth = $THUMB_WIDTH;
    $this->thumbheight = $this->width > 0 ? $this->height * $this->thumbwidth / $this->width : $THUMB_WIDTH;
    $this->photo = $data->p_photo;
    $this->picture = "$GALLERY_PATH/$this->galleryid/$data->p_photo";
    $this->caption = $data->p_caption;
  }
}

// Facts and Figures

class Facts {
  function Facts()
  {
    $connect = $_REQUEST['connect'];
    $FACTS_PATH = $_REQUEST['FACTS_PATH'];

    $sql = "SELECT * FROM site_facts_figures ";
    $result = mysql_query($sql, $connect);
    if ($row = mysql_fetch_object($result)) {
      $this->id = $row->ff_id;
      $this->phone = $row->ff_phone;
      $this->fmtphone = format_phone($row->ff_phone);
      $this->address = format_text($row->ff_address);
      $this->membership = $row->ff_membership;
      $this->avgattendance = $row->ff_avgattendance;
      $this->churchimage = ($row->ff_image1 && file_exists("$FACTS_PATH/$row->ff_image1")) ? $row->ff_image1 : "";
      $this->servicesimage = ($row->ff_image2 && file_exists("$FACTS_PATH/$row->ff_image2")) ? $row->ff_image2 : "";
      $this->groupsimage = ($row->ff_image3 && file_exists("$FACTS_PATH/$row->ff_image3")) ? $row->ff_image3 : "";
    }
  }
}


class ServiceData {
  function ServiceData($data)
  {
    $this->id = $data->s_id;
    $this->day = $data->s_day;
    $this->weekday = $_REQUEST['WEEKDAYS'][$this->day - 1];
    $this->begin = $data->s_begin;
    $this->end = $data->s_end;
    $this->fmttime = format_time($this->begin, $this->end); 
    $this->description = $data->s_description;
  }
}


class Services {
  function Services()
  {
    $connect = $_REQUEST['connect'];
    $this->data = array();
    $sql = "SELECT * FROM site_services " .
           "ORDER BY s_day, s_begin, s_end";
    $result = mysql_query($sql, $connect);
    while ($row = mysql_fetch_object($result)) {
      $this->data[] = new ServiceData($row);
    }
  }
}


class SmallGroupData {
  function SmallGroupData($data)
  {
    $this->id = $data->sg_id;
    $this->name = $data->sg_name;
    $this->contact = $data->sg_contact;
    $this->email = $data->sg_email;
    $this->phone = $data->sg_phone;
    $this->fmtphone = format_phone($this->phone);
    $this->time = $data->sg_time;
    $this->location = $data->sg_location;
    $this->description = $data->sg_description;
  }
}


class SmallGroups {
  function SmallGroups()
  {
    $connect = $_REQUEST['connect'];
    $this->data = array();
    $sql = "SELECT * FROM site_smallgroups " .
           "ORDER BY sg_seq";
    $result = mysql_query($sql, $connect);
    while ($row = mysql_fetch_object($result)) {
      $this->data[] = new SmallGroupData($row);
    }
  }
}
