<?php
namespace App\Helper;
use Request;
//use Auth;
use App\Model\Webinar;

class WebinarAcl
{

    /**
     * @var
     */
    protected $user;

    /** @var Webinar  */
    protected $webinar;

    /**
     * WebinarAcl constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->webinar = Webinar::getRecent();
    }

    /**
     * @return Webinar
     */
    public function getWebinar()
    {
        return $this->webinar;
    }

    /**
     * @return mixed
    */

    public function getUser()
    {
        return $this->user;
    }

    /**
     * Allowed groups
     *
     * @return array
     */
    public function getAllowedGroups()
    {
        return $this->webinar->getAllowedGroups(); // Key Habits US Habits / US / Personal Goal Management
    }

    /**
     * Check if current User can access webinars
     *
     * @return bool
     */
    public function canAccessWebinar()
    {
       
        $allowedGroups = $this->getAllowedGroups();
       
        $groups=json_decode($this->user->groups,true);

        if($groups && is_array($groups)){
            
            foreach($groups as $userGroup) {
                if (in_array($userGroup, $allowedGroups)) {
                    return true;
                }
            }
        }

        return false;
    }
}