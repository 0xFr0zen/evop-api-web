<?php
include_once __DIR__.'/utils/company.php';
include_once __DIR__.'/utils/mydb.php';
header("Content-Type: application/javascript");

$result = array();
if (isset($_REQUEST['resetlog'])) {
    $dbconn = new MyAdminDBConnector();
    $dbconn->resetLog();
    die(json_encode(array("result" => "log resetted")));
}
if (isset($_REQUEST['modrequests'])) {
    $dbconn = new MyAdminDBConnector();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['did']) && isset($_REQUEST['sid'])) {
        $did = $_REQUEST['did'];
        $sid = $_REQUEST['sid'];
        $lgd = $dbconn->addModRequest($did, $sid);
    }
    $mrqs = $dbconn->getModRequests();
    $result = array("result" => array("mod_requests" => $mrqs));
    die(json_encode($result));
}

if (isset($_REQUEST['loginstatus'])) {
    $dbconn = new MyAdminDBConnector();
    $mrqs = $dbconn->checklogin();
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['did']) && isset($_REQUEST['sid'])) {
        $did = $_REQUEST['did'];
        $sid = $_REQUEST['sid'];
        $mrqs = $dbconn->addModRequest($did, $sid);
    }
    $result = array("result" => array("requestparams" => $mrqs));
    die(json_encode($result));
}
if (!isset($_REQUEST['companies'])) {
    if (!isset($_REQUEST['company']) && !isset($_REQUEST['mode']) && !isset($_REQUEST['details'])) {

        if (!isset($_REQUEST['company'])) {
            die("company not set");
        }
        if (!isset($_REQUEST['mode'])) {
            die("mode not set");
        }
        if (!isset($_REQUEST['details'])) {
            die("details not set");
        }
    }
    $comp = $_REQUEST['company'];
    $mode = $_REQUEST['mode'];
    $details = $_REQUEST['details'];

    $company = new Company($comp);
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($company->exists()) {
                mode_switch:
                switch ($mode) {
                    case 'lookup':
                        $result = array("result" => true);
                        break;
                    case 'information':
                        switch ($details) {
                            case 'minimum':
                                $information = $company->information();
                                $result = array("result" => $information);
                                break;

                            default:
                                $information = $company->information();
                                $result = array("result" => $information);
                                break;
                        }
                        break;
                    case 'configuration':
                        switch ($details) {
                            case 'colors':
                                $configurationColor = $company->getConfiguration('colors');
                                $result = array("result" => $configurationColor);
                                break;
                            case 'textstyles':
                                $configurationTextstyle = $company->getConfiguration('textstyles');
                                $result = array("result" => $configurationTextstyle);
                                break;
                            case 'strings':
                                $configurationStrings = $company->getConfiguration('strings');
                                $result = array("result" => $configurationStrings);
                                break;
                            default:
                                $configuration = $company->getConfiguration();
                                $result = array("result" => $configuration);
                                break;
                        }
                        break;
                    case 'amount':
                        switch ($details) {
                            case 'users':
                                $users = $company->getUsersCount();
                                $result = array("result" => array("users" => array("amount" => $users['amount'])));
                                break;
                            case 'products':
                                $products = $company->getProductsCount();
                                $result = array("result" => array("products" => array("amount" => $products['amount'])));
                                break;
                            default:

                                $products = $company->getProductsCount();
                                $result = array("result" => array("products" => array("amount" => $products['amount'])));
                                break;
                        }
                        break;
                    default:
                        $result = array("error" => "you need to specify a query");
                        break;
                }

                break;
            } else {
                $result = array("error" => Company::$COMPANY_NONEXISTING);
            }
        case 'PUT':

            if ($company->exists()) {
                switch ($mode) {
                    case 'configuration':
                        if (!isset($_REQUEST['values'])) {
                            $result = array("error" => "you need to put values");
                        } else {
                            $values = explode(",", $_REQUEST['values']);
                            $vals = array();
                            foreach ($values as $value) {
                                $splitted = explode(":", $value);
                                $vals[$splitted[0]] = $splitted[1];
                            }
                            $val = json_decode(json_encode($vals, JSON_NUMERIC_CHECK), true);
                            $valName = $val['name'];
                            $valOldName = $val['oldname'];
                            unset($val['name']);
                            unset($val['oldname']);
                            $valObj = array("name" => $valName, "oldname" => $valOldName, "values" => $val);
                            $updated = $company->updateConfiguration($details, $valObj['oldname'], $valObj['name'], $valObj['values']);
                            $result = array("result" => array("updated" => $updated));
                        }
                        break;
                    case 'tables':
                        if ($details < 1) {
                            $result = array("error" => "you need to put a number >= 1");
                        } else {

                            $updated = $company->updateTables($details);
                            $result = array("result" => array("updated" => $updated));
                        }
                        break;

                    default:
                        $result = array("error" => "you need to specify a mode");
                        break;
                }
                break;
            } else {

            }
        case 'POST':
            switch ($mode) {
                case 'create':
                    if (!$company->exists()) {
                        $created = $company->create();
                        $result = array("result" => array("created" => $created));
                    } else {
                        $result = array("error" => Company::$COMPANY_ALREADY_EXISTS);
                    }
                    break;
                case 'configuration':

                    if ($company->exists()) {
                        if (!isset($_REQUEST['values'])) {
                            $result = array("error" => "you need to put values");
                        } else {
                            $values = explode(",", $_REQUEST['values']);
                            $vals = array();
                            foreach ($values as $value) {
                                $splitted = explode(":", $value);
                                $vals[$splitted[0]] = $splitted[1];
                            }
                            $val = json_decode(json_encode($vals, JSON_NUMERIC_CHECK), true);
                            $valName = $val['name'];
                            unset($val['name']);
                            $valObj = array("name" => $valName, "values" => $val);
                            $updated = $company->addConfiguration($details, $valObj['name'], $valObj['values']);
                            if ($updated) {

                                $result = array("result" => array("updated" => $updated));
                            } else {
                                $result = array("result" => array("updated" => $updated, "message" => Company::$CONFIGURATION_ALREADY_EXISTS));

                            }
                        }
                    } else {
                        $result = array("error" => Company::$COMPANY_NONEXISTING);
                    }
                    break;
            }
            break;
        case 'DELETE':
            if ($company->exists()) {
                switch ($mode) {
                    case 'configuration':
                        if (!isset($_REQUEST['values'])) {
                            $result = array("error" => "you need to put values");
                        } else {
                            $values = explode(",", $_REQUEST['values']);
                            $vals = array();
                            foreach ($values as $value) {
                                $splitted = explode(":", $value);
                                $vals[$splitted[0]] = $splitted[1];
                            }
                            $val = json_decode(json_encode($vals, JSON_NUMERIC_CHECK), true);
                            if (!isset($val['name'])) {
                                $result = array("error" => "you need to specify a resource-name");
                                break;
                            }
                            $configurationDeleted = $company->deleteConfiguration($details, $val['name']);
                            $result = array("result" => $configurationDeleted);
                        }

                        break;
                    default:
                        $removed = $company->remove();
                        $result = array("result" => array("removed" => $removed));
                        break;
                }
                break;
            } else {
                $result = array("error" => Company::$COMPANY_NONEXISTING);
            }
        default:
            # code...
            break;
    }

} else {

    $dbconn = new MyCompanyDBConnector();
    $sql = "SELECT `name`, `tables` FROM company";
    $resultCompanies = $dbconn->query($sql);
    $resulter = array();
    while ($r = $resultCompanies->fetch_assoc()) {
        array_push($resulter, $r);
    }
    $result = array("result" => $resulter);

}
$enc = json_encode($result);
print($enc);
