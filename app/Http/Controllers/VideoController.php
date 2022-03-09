<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Video;
use App\Repositories\IVideoRepository;

class VideoController extends Controller
{
       /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $repository;

    public function __construct(IvideoRepository $repository)
    {
        parent::__construct();
       $this->repository = $repository;
       // $this->middleware('guest');
    }  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->getPublishedVideos();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


}
