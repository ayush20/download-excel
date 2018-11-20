<?php
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");

require_once 'spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

// Open connection
try {

	$hostname = "localhost";
	$dbname = "YOUR_DB_NAME_HERE";
	$username = "YOUR_USERNAME_HERE";
	$password = "YOUR_PASSSWORD_HERE";

	$pdo = new PDO('mysql:host='.$hostname.';dbname='.$dbname, $username, $password);
}catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit();
}

// Run Query
$sql 	= 'SELECT * FROM sample';
$stmt 	= $pdo->prepare($sql); // Prevent MySQl injection.
$stmt->execute();
$total = $stmt->rowCount();

$pathToFile = './excel_'.uniqid().'.xlsx';

// Create new Spreadsheet object
$writer = WriterFactory::create(Type::XLSX); // for XLSX files
$writer->openToFile($pathToFile); // write data to a file or to a PHP stream
$writer->addRow(['ID','first_name','last_name','age','string','value']);
$i=0;
while ($row = $stmt->fetch()){

	$excelData = array($row['id'], $row['first_name'], $row['last_name'], $row['age'], $row['string'], $row['value']);

	// Save data to excel here
	$writer->addRow($excelData);

	$percent = floor(($i+1)*100/$total);
	$data = array('count'=>$i+1, 'percent'=>$percent,'done'=>0);
	echo "data: " .json_encode($data)."\n\n";
	flush();
	$i++;
}

$writer->close();

$data = array('done'=>1,'path'=>$pathToFile);
echo "data: " .json_encode($data)."\n\n";
flush();

// Close connection
$pdo = null;

?>