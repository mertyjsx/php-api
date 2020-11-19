<?php
include_once './config/database.php';


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$email = '';
$password = '';

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();



$data = json_decode(file_get_contents("php://input"));

$email = $data->email;
$password = $data->password;

$table_name = 'users';

$query = "SELECT id, password FROM " . $table_name . " WHERE email = ? LIMIT 0,1";

$stmt = $conn->prepare( $query );
$stmt->bindParam(1, $email);
$stmt->execute();
$num = $stmt->rowCount();

if($num > 0){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id = $row['id'];
   
    $password2 = $row['password'];
    
    if(password_verify($password, $password2))
    {
       
       
 
        http_response_code(200);
 
     
        echo json_encode(
            array(
                "message" => "Successful login.",
               
            ));
    }
    else{
        
        http_response_code(401);
        echo json_encode(array("message" => "Login failed.", "password" => $password, "password2" => $password2));
    }
}else{
    http_response_code(400);
 
     
        echo json_encode(
            array(
                "message" => "email or password uncorrect",
               
            ));

}
?>


