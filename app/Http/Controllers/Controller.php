<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jenssegers\Agent\Agent;
use View;
use App\Helper\webinarAcl;
use Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $userAgent;
    public $isDesktop;
    public $isMobile;
    public $webinarAcl;
    public $currentMethod;
    public $activeMenu;

    public function __construct()
    {
      
      $this->userAgent=new Agent();
      
      $isMobile=$this->userAgent->isMobile();
      
      $isDesktop=$this->userAgent->isDesktop();
      
      $this->isMobile=($isMobile)?1:0;
      $this->isDesktop=($isDesktop)?1:0;
      $this->currentMethod=Route::getCurrentRoute()->getActionMethod();
      $this->activeMenu=Route::getCurrentRoute()->getAction("active_menu"); 
      // $url = url('/');
      // $domain = config('site.keyhabits');
      // if($url == config('site.keyhabits')){
      //   $folder = "keyhabits";
      // }elseif($url == config('site.athena')){
      //   $folder = "athena";
      // }else{
      //   $folder = "keyhabits";
      // }
      $folder = env('DEFAULT_THEME','keyhabits');

      View::share ( 'userAgent', $this->userAgent );
      View::share ( 'isMobile', $this->isMobile );
      View::share ( 'isDesktop', $this->isDesktop );
      View::share ( 'currentMethod', $this->currentMethod );
      View::share ( 'activeMenu', $this->activeMenu );
      View::share ( 'theme', $folder );
    }
    
}
