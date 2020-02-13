<?php

class industryDepartment
{

    public $Id;
    public $Name;
    public $Date;

    public function _construct($id,$name,$date)
    {   
        $this->Id=$id;
        $this->Name=$name;
        $this->Date=$date;
    }

  /*  public function _construct($name,$date)
    {   
        $this->Id=-1;
        $this->Name=$name;
        $this->Date=$date;
    }   */

    public function getTile()    //zwraca kafelek jako drugi argument do printf trzeba podać nazwę
    {

        $html='
        <link rel="stylesheet" href="card-style.css"> 
        <div class="card">
        <img src="img_avatar.png" alt="Avatar" style="width:100%">
            <div class="container">
                <h4><b></b></h4>
        <p>'.$this->Name.'</p>
        </div>
        </div>';    //Prosta wersja 

    
        //Wersja material design

        $html2=
        '
        <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-offset-2">
            <div class="card">
    
              <div class="image">
                <img src="'.plugins_url().'/industryPlugin/public/images/power_plant.jpg" width="100%">
              </div>
    
              <div class="text">
                
              <form method="POST" action="http://localhost/ProjectsSystem/dziennik/">
              <input type="hidden" name="departmentId" id="departmentId" value="'.$this->Id.'" />
              <input type="submit" name="selectDepartment" id="selectDepartment" value="'.$this->Name.'" /><br/>
              </form>
    
                
                <p>Data powstania'.$this->Date.'</p>
    
              </div>
    
            </div>
          </div>
        </div>
      </div>
        ';


        return $html2; //tu zwracać wersje
    }


    public function addNew($newName)
    {
      if(isset($newName))
      {
        global $wpdb;
        $querryResult = $wpdb->get_results("INSERT INTO wp_industrydepartments (id,Name,Date) VALUES (NULL,'$newName', current_timestamp());");
       echo "Pomyślnie dodano nowy oddział.";
       
      }
    }


}

//<h3>'.$this->Name.'</h3>

?>
