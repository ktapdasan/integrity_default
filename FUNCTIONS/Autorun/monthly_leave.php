<?php
require_once('../connect.php');
require_once('../../CLASSES/LeaveTypes.php');
require_once('../../CLASSES/Employees.php');

$class = new LeaveTypes(
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            'false'
            			);

$leave_types_data = $class->fetch();

$leave_types = array();
foreach ($leave_types_data['result'] as $key => $value) {
    $details = (array)json_decode($value['details']);
    $leave_types[$value['pk']] = $details['leaves_per_month'];
}

$class = new Employees(
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL,
                            NULL
                        );

$employees_data = $class->fetch();
$employees = $employees_data['result'];

$sql = 'begin;';
foreach ($employees as $key => $value) {
    $leave_balances = (array)json_decode($value['leave_balances']);
    
    $new_leave_balances=$leave_types;
    foreach ($leave_balances as $k => $v) {
        $new_leave_balances[$k] = $v;
    }

    foreach ($leave_types as $k => $v) {
        foreach($leave_balances as $x => $y){
            if(intval($k) == intval($x)){
                $new_leave_balances[$k] = floatval($y) + floatval($v);
            }
        }
    }
    
    $lb=json_encode($new_leave_balances);
    $pk=$value['pk'];
    $sql .= <<<EOT
            update employees set leave_balances = '$lb' where pk = $pk;
EOT;

}
$sql .= 'commit;';
$employees_update = $class->auto_update_leave_balances($sql);

header("HTTP/1.0 200 OK");
header('Content-Type: application/json');
print(json_encode($employees_update));
?>