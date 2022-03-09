@php
$user = auth()->user();
$sharedUrl = Config::get('constants.sharedUrl');
@endphp
@extends('layouts.base')
@section('head_css')
    <style type="text/css">
        body {
            margin-top: 20px;
            background: #f8f8f8
        }

    </style>
    <link href="{{ asset('css/bootstrap.min-profile.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div id="page-wrapper" class="no-pad">
        <div class="graphs">

            <div class="<?php echo $isMobile ? 'hide-on-desktop' : 'hide-on-mobile'; ?>">
                <section class="lobby-goals-rows col-lg-12 no-pad">
                    <?php if ($isMobile) { ?>
                    @include('mobile_header')
                    <?php } ?>
                </section>
            </div>

            <section class="trophy-room col-lg-12 no-pad" id="trophy-holder">
                <div class="col-md-4 pull-right">



                </div>
                <div class="yellow-head">
                    <h1>Video(s) of the week</h1>
                </div>

                <div class="container" style="
                    margin-top: 20px; ">
                    <div class="row flex-lg-nowrap">


                        <div class="col">
                            <div class="row">
                                <div class="col mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="e-profile">
                                        @forelse ($sharedVideos as $sharedVideo)
                                            @php
                                                $video = \App\Model\Video::where('id',$sharedVideo->video_id)->get()->first();
                                                
                                            @endphp
                                            @if($video)
                                            <div class="card mt-3">
                                                <h2>{{ $video->title }}</h2>
                                                <p>{{ $video->description }}</p>
                                              </div>
                                            <div class="tab-content pt-3">
                                              <div class="tab-pane active">
                                                  <div class="row">
                                                      <video id="my-player{{ $video->id }}" class="video-js" controls=""
                                                          controlsList="nodownload" preload="auto"
                                                          @if($video->video_cover)
                                                          poster="{{ $video->video_cover }}" 
                                                          @else
                                                          poster="https://athenabasketball.com/wp-content/uploads/2020/05/BRYNJAR.jpg" 
                                                          @endif
                                                          data-setup="{}"
                                                          style="margin-left: auto;margin-right: auto;display: block;">
                                                          <source 
                                                          @if($video->video_type =='external' )
                                                          src="{{$video->external_url}}"
                                                          @else
                                                          src = "{{$sharedUrl.$video->video_url}}"
                                                          @endif
                                                              type="video/mp4">
                                                         
                                                          <p class="vjs-no-js">
                                                              To view this video please enable JavaScript, and
                                                              consider upgrading to a
                                                              web browser that
                                                              <a href="https://videojs.com/html5-video-support/"
                                                                  target="_blank">
                                                                  supports HTML5 video
                                                              </a>
                                                          </p>
                                                      </video>
                                                  </div>
                                              </div>
                                          </div>
                                            @endif
                                       
                                            @empty
                                                  <p>Ooups!! There is no video to share today :(</p>  
                                                @endforelse
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                          
                            @endsection
                            <script>
                              // disable right click
                              document.addEventListener('contextmenu', event => event.preventDefault());
                            
                              document.onkeydown = function (e) {
                            
                                  // disable F12 key
                                  if(e.keyCode == 123) {
                                      return false;
                                  }
                            
                                  // disable I key
                                  if(e.ctrlKey && e.shiftKey && e.keyCode == 73){
                                      return false;
                                  }
                            
                                  // disable J key
                                  if(e.ctrlKey && e.shiftKey && e.keyCode == 74) {
                                      return false;
                                  }
                            
                                  // disable U key
                                  if(e.ctrlKey && e.keyCode == 85) {
                                      return false;
                                  }
                              }
                            
                            </script>