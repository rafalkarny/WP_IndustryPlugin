<?php

class industrySystems
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

  public function getSelectBox($DepartmentId)    //zwraca kafelek jako drugi argument 
  {

    //Wersja material design
    global $wpdb;

    if (isset($DepartmentId)) {
      // echo "<p> ".$DepartmentId."</p>";

      $querryResult = $wpdb->get_results("SELECT id,Name FROM wp_industrySystem WHERE Department=" .$DepartmentId. ";");

      if ($querryResult != NULL) {

        require_once('industrySystem.php');

        $html =
          '
          <h5>System:</h5>
          <select name="systems" id="systemSelect" style = "margin-bottom: 40px; margin-left: 30px; margin-top: 10px;">
        ';
        foreach ($querryResult as $row) 
        {          
         // $row->id;
         // $row->Name;
          $tmpSystem=new industrySystem();
          $tmpSystem->Id=$row->id;
          $tmpSystem->Name=$row->Name;
          
          $html=$html."<option value=".$tmpSystem->Id.">".$tmpSystem->Name."</option>";
        }

        $html=$html.'</select>';

        return $html; //

      } else {
        $html =
          '
          <h5>System:</h5>
          <select name="systems" id="systemSelect" 
          style = "margin-bottom: 40px; margin-left: 30px; margin-top: 2px;         
          ">
          <option value="">brak dodanych systemów powiązanych z Oddziałem</option>
          </select>
          ';


        return $html; //Zwraca listę

      }
    }
  }

  public function getSystemName($systemId)
  {

    global $wpdb;

    if (isset($systemId))
     {
      // echo "<p> ".$DepartmentId."</p>";

      $querryResult = $wpdb->get_results("SELECT id,Name FROM wp_industrySystem WHERE id=" .$systemId. ";");

      if($querryResult!=NULL)
      {
        foreach ($querryResult as $row) 
        {          
          return $row->Name;      
        }
      }

      else{
        return $systemId;
      }


    }

  }
}
