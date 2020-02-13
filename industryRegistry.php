<?php

class industryRegistry
{

  public $Id;
  public $Name;
  public $System;
  public $Device;
  public $Description;
  public $Department;
  public $Date;
  public $User;

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

  public function getTile()    //zwraca kafelek jako drugi argument do printf trzeba podać nazwę
  {

    require_once('industrySystems.php');

    $systemName=new industrySystems();


    $html = '
        <link rel="stylesheet" href="card-style.css"> 
        <div class="card">
        <center>
        
            <div class="container">
                <h4><b>
                
               

                </b></h4>
        <p>' . $this->Name . '</p>
        <p>' .$systemName->getSystemName($this->System). '</p>
        <p>' . $this->Date . '</p>
        <p>' . $this->id . '</p>
        </div>
        </div>';    //Prosta wersja 


    //Wersja material design

    $html2 =
      '
        <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2">
            <div class="card">
    
            <div class="imageLow">
            <img src="' . plugins_url() . '/industryPlugin/public/images/forest.jpg" height="20%" width="100%">
          </div>
    
              <div class="text">
              
                <h4>' . $this->Name . '</h4>
                <p>' .$systemName->getSystemName($this->System).  '</p>
                <p>'.$this->Description.'</p>
                <p>' . $this->Date . '</p>
                <p>' . $this->Id . '</p>
              </div>
              
              
              <form method="POST" action="http://localhost/ProjectsSystem/wpis/"  
                style = "margin-bottom: 15px; margin-left: 30px; margin-top: 10px" ; >
                <input type="hidden" name="id" id="id" value="' .$this->Id. '" />
                <input type="submit" name="selectDepartment" id="selectDepartment" value="Przejdź do wpisu" /><br/>
                </form>
                

            </div>
          </div>
        </div>
      </div>
        ';


    return $html2; //tu zwracać wersje
  }

  public function getRegistryDataFromDb()
  {
    if ($this->Id!=NULL) {

      global $wpdb;
      $querryResult = $wpdb->get_results("SELECT * FROM wp_industryregistry WHERE id=" .$this->Id. ";");


      if ($querryResult == NULL) {
        $this->Id = NULL;
        $this->Name = "Temat wpisu";
        $this->System = 0;
        $this->Device = 0;
        $this->Department = 0;
        $this->Description = "Opis wpisu";
        $this->User = 0;
      }
      else {

        foreach ($querryResult as $row) {
         
          $this->Id = $row->id;
          $this->Name = $row->Name;
          $this->System = $row->System;
          $this->Device=$row->Device;
          $this->Department=$row->Department;
          $this->Description=$row->Description;
          $this->User=$row->User;
          $this->Date = $row->Date;
          
        }
      }
    } 
    
    else {
      $this->Id = NULL;
      $this->Name = "Temat wpisu";
      $this->System = 0;
      $this->Device = 0;
      $this->Department = 0;
      $this->Description = "Opis wpisu";
      $this->User = 0;
    }
  }
}
