<?php
include_once __DIR__.'/mydb.php';
include_once __DIR__.'/../../essentials/queries.php';

class User
{

    private $uuid = "";
    private $type;
    private $osname;
    private $osversion;
    private $personalinformation = array();

    public static $USER_NONEXISTING = "User doesnt exists!";
    public static $UNEXPECTED_ERROR = "Sorry, something happend and we dont know quite yet why...";
    public static $STATUS_MISSING = "Please write a status name to check.";
    public function __construct(string $uuid, $type = null, $osname = null, $osversion = null, $personalinformation = array())
    {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->osname = $osname;
        $this->osversion = $osversion;
        $this->personalinformation = $personalinformation;
    }
    /**
     * Check if the company exists on the database
     * 
     * @return bool
     */
    public function exists():bool
    {
        $res = false;
        $dbconn = new MyUserDBConnector();
        $res = $dbconn->check(
            Queries::get("user","exists"),
            $this->uuid
        );
        return $res;
    }

    /**
     * Sets the OS-Values of the User
     * @param string $type
     * @param string $osversion
     * @param string $osname
     * 
     * @return bool
     */
    public function setOS(string $type, string $osversion, string $osname):bool
    {
        $this->type = $type;
        $this->osversion = $osversion;
        $this->osname = $osname;
        $dbconn = new MyUserDBConnector();

        return $dbconn->update(
            Queries::get('user','update-os'),
            $this->uuid,
            $this->type,
            $this->osversion,
            $this->osname,
        );
    }

    /**
     * Sets the personal informations.
     * @param string $address
     * @param string $city
     * @param string $plz
     * 
     * @return bool
     */
    public function setPersonalInfo(string $address, string $city, string $plz):bool
    {
        $this->personalinformation = array(
            "address" => $address,
            "city" => $city,
            "plz" => $plz,
        );
        $dbconn = new MyUserDBConnector();

        return $dbconn->update(
            Queries::get('user','update-personal-information'),
            $this->uuid,
            $this->personalinformation['address'],
            $this->personalinformation['city'],
            $this->personalinformation['plz'],
        );
    }

    /**
     * Creates the user on the database
     * 
     * The return value represents if the action is either done or has error.
     * 
     * @return array
     */
    public function create():array
    {
        $res = array();
        $dbconn = new MyUserDBConnector();
        $created = $dbconn->insert(
            Queries::get('user', 'create'),
            $this->uuid,
            
        );

        $res = array("status" => $created);
        if (!$created) {
            $res['message'] = Company::$COMPANY_ALREADY_EXISTS;
        }
        return $res;
    }


    /**
     * Checks if the company is activated
     * 
     * @return bool
     */
    public function isOnline():bool
    {
        $res = false;
        $dbconn = new MyUserDBConnector();
        $res = $dbconn->check(
            Queries::get('user','is-online'),
            $this->uuid
        );
        return $res;
    }

    /**
     * Activates the company
     * 
     * @return bool
     */
    public function setOnline():bool
    {
        $res = false;
        $dbconn = new MyUserDBConnector();
        $updated = $dbconn->update(
            Queries::get('user','set-status'),
            $this->uuid,
            true
        );

        $res = array("status" => $updated);
        if (!$updated) {
            $res['message'] = Company::$UPDATE_ERROR;
        }
        return $res;
    }

    /**
     * Activates the company
     * 
     * @return bool
     */
    public function setOffline():bool
    {
        $res = false;
        $dbconn = new MyUserDBConnector();
        $updated = $dbconn->update(
            Queries::get('user','set-status'),
            $this->uuid,
            false
        );

        $res = array("status" => $updated);
        if (!$updated) {
            $res['message'] = Company::$UPDATE_ERROR;
        }
        return $res;
    }

    /**
     * Reads out information from the database
     * 
     * @param bool $detailed
     * 
     * @return array
     */
    public function information($detailed = false):array
    {
        $res = array();
        $dbconn = new MyUserDBConnector();
        $res2 = null;
        if(!$detailed){
            $res2 = $dbconn->query(
                Queries::get('user','information-little'),
                $this->uuid
            );
            
        }else {
            $res2 = $dbconn->query(
                Queries::get('user','information-all'),
                $this->uuid
            );
        }
        if($res2 != null){
            $res = $res2->fetch_assoc();
        }else {
            $res = array("error" => User::$UNEXPECTED_ERROR);
        }
        
        return $res;
    }

    public function load()
    {
        $dbconn = new MyUserDBConnector();
        $res2 = $dbconn->query(
            Queries::get('user','information-all'),
            $this->uuid
        );
        $this->type = $res2['type'];
        $this->personalinformation;
        $this->osname = $res2['osname'];
        $this->osversion = $res2['osversion'];
    }

    public function status(string $of)
    {
        $result = array();
        if(empty($of)){
            $result = array("error"=> array("message" => User::$STATUS_MISSING));
        }else {
            switch ($of) {
                case 'registration':
                    $result = array($of => $this->exists());
                    break;
                case 'online':
                    $result = array($of => $this->isOnline());
                    break;
                // case 'loggedin':
                //     $result = array($of => $this->isLoggedin());
                //     break;
                default:
                    $result = array("online" => $this->isOnline());
                    break;
            }
        }
        return $result;
    }
    /**
     * Returns likewise-users
     * @param string $uuid
     * 
     * @return array
     */
    public static function find(string $uuid):array
    {
        $result = array();
        $dbconn = new MyUserDBConnector();
        $prepareduuid = "%".$uuid."%";
        $res = $dbconn->query(Queries::get('user', 'resolver'),
            $prepareduuid
        );
        while(($row = $res->fetch_assoc()) != null){
            array_push($result, $row);
        }
        return $result;

    }
}