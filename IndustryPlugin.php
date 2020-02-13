<?php
/**
 * @package IndustryAutomation
 * @version 0.1
 */
/*
Plugin Name: Industry Automation Plugin
Plugin URI: localhost
Description: This is a plugin which helps in automation systems maintainence 
Author: Rafał Karnówka
Version: 0.1
Author URI: http://rafalkarnowka.pl/
*/



//Zdarzenie aktywacji wtyczki
//register_activation_hook( __FILE__, 'my_plugin_create_db' );


/// Utworzenie tabeli w bazie 
function my_plugin_create_db() {

$myInfo='Witaj!!';

printf('Status: %s',$myInfo);

//printf( '<script>alert("Tabela Twojej wtyczki została utworzona :)")</script>'); 


//Utworzenie tabeli   TODO:::: DOŁOŻYĆ  TABELE ŻEBY SIĘ INSTALOWAŁO DOBRZE
global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'my_testtable';

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		views smallint(5) NOT NULL,
		clicks smallint(5) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";


	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
}


// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'my_plugin_create_db' );


//Widok Oddziałów - Dodanie shordcode który zwraca widok na tabelę z Oddziałami
function industryGetDepartments() 
{
	global $wpdb;
	//$con=mysqli_connect("example.com","peter","abc123","my_db");
// Check connection

   $querryResult=$wpdb->get_results("SELECT * FROM wp_industrydepartments");
if ($querryResult==NULL)
{
printf('Wystąpił błąd bazy danych: %s',$wpdb->print_error());
}

else
{
//$result = mysqli_query($con,"SELECT * FROM Persons");

require_once('industryDepartment.php');

/*	PROSTA TABELKA

echo "<table border='1'>
<tr>
<th>Nazwa</th>
<th>Data utworzenia</th>
</tr>";
foreach($querryResult as $row)
{

echo "<tr>";
echo "<td>".$row->Name. "</td>";
echo "<td>" .$row->Date."</td>";
echo "</tr>";

}
echo "</table>";

*/

foreach($querryResult as $row)
{
	$tmpDepartment=new industryDepartment();
	$tmpDepartment->Id=$row->id;
	$tmpDepartment->Name=$row->Name;
	$tmpDepartment->Date=$row->Date;
	echo $tmpDepartment->getTile();
}

//Do testu
industrialUpdateStyles();

}

}
add_shortcode( 'Departments', 'industryGetDepartments' );	// Dodanie shordcode który zwraca widok na tabelę z Oddziałami




function industrialUpdateStyles()
{
	wp_enqueue_style('card',plugins_url().'/industryPlugin/public/css/card-style.css');
	wp_enqueue_style('container',plugins_url().'/industryPlugin/public/css/card-style.css');
}

add_action( 'wp_enqueue_scripts', 'industrialUpdateStyles');


function industryGetRegistry() {

	global $wpdb;

	if (isset($_POST['departmentId']))
    {
        echo "<p> ".$_POST['selectDepartment']." Id:  ".$_POST['departmentId']."</p>";
    
   $querryResult=$wpdb->get_results("SELECT id,Name,System,Date,Department,LEFT(Description,50) Description FROM wp_industryregistry WHERE Department=".$_POST['departmentId'].";");


if ($querryResult==NULL)
{
echo '<p> Brak wpisów w dzienniku, baza danych zwróciła NULL: </p>';
}

else
{

require_once('industryRegistry.php');


foreach($querryResult as $row)
{
	$tmpRegister=new industryRegistry();
	$tmpRegister->Id=$row->id;
	$tmpRegister->Name=$row->Name;
	$tmpRegister->System=$row->System;
	$tmpRegister->Department=$row->Department;
	$tmpRegister->Description=$row->Description;
	$tmpRegister->Date=$row->Date;
	echo $tmpRegister->getTile();		//Fabryka kafelka wpisu
}


//Do testu
industrialUpdateStyles();

}

}

}
add_shortcode( 'Registry', 'industryGetRegistry' );	// Dodanie shordcode który zwraca widok na tabelę z Oddziałami

require_once('industrySystems.php');
require_once('industryDepartments.php');
require_once('industryRegistry.php');

// Wpis-zawartość wpisu
function editRegistry() {
	
	//Pobranie wartości wpisu
	$tmpRegister=new industryRegistry();
	$tmpRegister->Id=$_POST['id'];
	$tmpRegister->getRegistryDataFromDb();	//Pobranie całości wpisu

	$editorContent=$_POST['industryDescription'];

if(isset($editorContent))
{
	//echo $editorContent;
}

	if($tmpRegister!=NULL)
	{

	//Tytuł
	echo '<table border="1"><h3>Temat: </h3>
	<input type="text" name="name" id="name" value="'

	.$tmpRegister->Name.'"></table>';

	//Id wpisu
	echo '<h4>ID: #'.$tmpRegister->Id.'</h4>';

	//Data

	echo '<table border="1"><h5>Data wpisu: '.$tmpRegister->Date.'</h5></table>';

	//Użytkownik
	$author=get_userdata($tmpRegister->User);

	
	echo '<table border="1"><img src="'.$get_author_gravatar = get_avatar_url($author->Id, array('size' => 100)).'">';
	echo "<h5>Autor:".$author->user_firstname." ".$author->user_lastname."</h5>";
	echo "<p>".$author->user_login."</p> </table>";

	//Wstawienie Listy Oddziałów
	$departmentsList=new industryDepartments();
	echo '<table border="1">'.$departmentsList->getSelectBox().'</table>';
	
	//Wstawienie select boxa systemu
	$systemList=new industrySystems();
	echo '<table border="1">'.$systemList->getSelectBox($tmpRegister->Id).'</table>';
	

	//Edytor
	echo '<h5>Treść wpisu:</h5> ';
	$editor_id='industryDescription';
	//$editor=wp_editor( $tmpRegister->Description, $editor_id ); 
	echo wp_editor( $_POST['industryDescription'], $editor_id ,array()); 

	$jsScript="
	<script>
function post() {
	const form = document.createElement('form');
	method='post';
	form.method = method;


	tinyMCE.triggerSave();

		const editBox = document.createElement('input');
		editBox.type = 'hidden';
		editBox.name = 'industryDescription';
		editBox.value = tinyMCE.get('industryDescription').getContent(); 
		form.appendChild(editBox);

		const Name = document.createElement('input');
		Name.type = 'hidden';
		Name.name = 'Name';
		Name.value = document.getElementById('name').value;
		form.appendChild(Name);

		const System = document.createElement('input');
		System.type = 'hidden';
		System.name = 'System';
		System.value = document.getElementById('systemSelect').value;
		form.appendChild(System);

		const Department = document.createElement('input');
		Department.type = 'hidden';
		Department.name = 'Department';
		Department.value = document.getElementById('departmentSelect').value;
		form.appendChild(Department);

		const Id = document.createElement('input');
		Id.type = 'hidden';
		Id.name = 'id';
		Id.value = ".$tmpRegister->Id.";
		form.appendChild(Id);
	  
		alert('Zapisano zmiany!');
		
  
	document.body.appendChild(form);
	form.submit();
  }

  </script>

";	


  	echo $jsScript;

	echo '
	<button type="button" onclick="post()"> Zapisz </button>
	';




	}
	else{
		echo 'Coś poszło nie tak... podczas ładowania wpisu..';
	}





}
add_shortcode( 'editRegistry', 'editRegistry' );



function getSystems() {

	

}
add_shortcode( 'Systems', 'getSystems' );


//dodawanie nowego Oddziału

function addDepartment()
{
	$html=
	'
		<form method="POST">
		<p><label>Nazwa Oddziału (wymagane)<br />
    		<input type="text" id="newDepartment" name="newDepartment"> </label></p>
			<p><input type="submit" name="selectDepartment" id="selectDepartment" value="Dodaj" /><br/></p>
		</form>

	';

	if(isset($_POST['newDepartment']))
	{
		
		require_once('industryDepartment.php');
		$newDepartment=new industryDepartment();
		$newDepartment->Name=$_POST['newDepartment'];
		$newDepartment->addNew($newDepartment->Name);

		$html=
		'
			<form method="POST">
			<p><label>Nazwa Oddziału (wymagane)<br />
				<input type="text" id="newDepartment" name="newDepartment"> </label></p>
				<p><input type="submit" name="selectDepartment" id="selectDepartment" value="Dodaj kolejny" /><br/></p>
			</form>
		';

		echo $html;

		unset($_POST['newDepartment']);
	}
	else
	{
		echo $html;
	}


}

add_shortcode( 'addDepartment', 'addDepartment');
