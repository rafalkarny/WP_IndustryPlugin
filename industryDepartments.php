<?php

class industryDepartments
{

  public function _construct()
  {
    //Warto by było zainicjalizować wartości domyślne
  }

  /*  public function _construct($name,$date)
    {   
        $this->Id=-1;
        $this->Name=$name;
        $this->Date=$date;
    }   */

  public function getSelectBox()
  {

    //Wersja material design
    global $wpdb;

    $querryResult = $wpdb->get_results("SELECT id,Name FROM wp_industryDepartments;");

    if ($querryResult != NULL) {


      $html =
        '
          <h5>Oddział:</h5>
          <select name="departments" id="departmentSelect" style = "margin-bottom: 15px; margin-left: 30px; margin-top: 10px;">
        ';
      foreach ($querryResult as $row) {

        $html = $html . "<option value=" . $row->id . ">" . $row->Name . "</option>";
      }

      $html = $html . '</select>';

      return $html; //

    } else {
      $html =
        '
          <h5>System:</h5>
          <select name="department" id="departmentSelect" 
          style = "margin-bottom: 15px; margin-left: 30px; margin-top: 2px;         
          ">
          <option value="">brak</option>
          </select>
          ';


      return $html; //Zwraca listę

    }
  }
}
