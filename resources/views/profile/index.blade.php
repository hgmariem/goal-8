@php
$user = auth()->user();

@endphp
@extends('layouts.base') 
@section('head_css')
<style type="text/css">
    body{
margin-top:20px;
background:#f8f8f8
}
</style>
	<link href="{{ asset('css/bootstrap.min-profile.css') }}" rel="stylesheet">
@endsection
@section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
        
            <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
                <section class="lobby-goals-rows col-lg-12 no-pad">
                    <?php 
                        if($isMobile){?>
                            @include('mobile_header')
                    <?php } ?>
                </section>
            </div>
            
            <section class="trophy-room col-lg-12 no-pad" id="trophy-holder">
                <div class="col-md-4 pull-right">
                   
                  @if($reminder)
                  <div class="alert alert-danger alert-dismissable fade in">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  {{ $reminder }}
                  </div>
                  @endif
                
                </div>
                <div class="yellow-head"><h1>Edit My Profile</h1></div>
         
                <div class="container" style="
                margin-top: 20px; ">
                <div class="row flex-lg-nowrap">
                  
                
                  <div class="col">
                    <div class="row">
                      <div class="col mb-3">
                        <div class="card">
                          <div class="card-body">
                            <div class="e-profile">
                              <div class="row">
                                <div class="col-12 col-sm-auto mb-3">
                                  <div class="mx-auto" style="width: 140px;">
                                  @if($user->gender=="Male")
                                  <img  src="{{ asset('/images/avatar/male.png') }}">
                                  @else 
                                  <img  src="{{ asset('/images/avatar/female.png') }}">
                                  @endif
                                   
                                  </div>
                                </div>
                                <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                  <div class="text-center text-sm-left mb-2 mb-sm-0">
                                    <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">{{$user->fullname}}</h4>
                                    <div class="mt-2">
                                      <button class="btn btn-dark" type="button">
                                        <i class="fa fa-fw fa-camera"></i>
                                        <span>Change Photo</span>
                                      </button>
                                    </div>
                                  </div>
                                  <div class="text-center text-sm-right">
                                    <div class="text-muted"><small>Joined {{ $user->created_at }}</small></div>
                                  </div>
                                </div>
                              </div>
                              <div class="tab-content pt-3">
                                <div class="tab-pane active">
                                @if(Session::has('message'))
                                <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    {{ Session::get('message') }}
                                </div>
@endif
                                  <form class="form" action="{{ url('update_profile') }}" method="POST" autocomplete="off">
                                  {{ csrf_field() }}
                                  <input Type="text" name="id" value="{{$user->id}}" hidden>
                                    <div class="row">
                                      <div class="col">
                                        <div class="row">
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Full Name</label>
                                              <input class="form-control" type="text" name="fullname" placeholder="John Smith" value="{{$user->fullname}}" required>
                                            </div>
                                          </div>
                                         
                                        </div>
                                        <div class="row">
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Email</label>
                                              <input class="form-control" type="text" placeholder="" name="email" value="{{$user->email}}" required>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Gender</label>
                                              <select name="gender" class="form-control">
                                              @if($user->gender=="Male")
                                                <option value="Male" selected="selected">Male</option>
                                                 <option value="Female">Female</option>
                                              
                                              @else 
                                                <option value="Male" >Male</option>
                                                 <option value="Female" selected="selected">Female</option>
                                              @endif
                                             </select>
                                            </div>
                                          </div>
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Phone number</label>
                                              <input class="form-control" type="text" placeholder="" name="telephone" value="{{$user->telephone}}">
                                            </div>
                                          </div>
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Country</label>
                                              <input class="form-control" type="text" name="country" placeholder="John Smith" value="{{$user->country}}">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col">
                                            <div class="form-group">
                                              <label>City</label>
                                              <input class="form-control" type="text" name="city" placeholder="" value="{{$user->city}}" required>
                                            </div>
                                          </div>
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Street</label>
                                              <input class="form-control" type="text" name="street" placeholder="" value="{{$user->street}}" required>
                                            </div>
                                          </div>
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Post code</label>
                                              <input class="form-control" type="text" name="post_code" placeholder="" value="{{$user->post_code}}" required>
                                            </div>
                                          </div>
                                        </div>
                                        
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col d-flex justify-content-end">
                                        <button class="btn btn-danger" type="submit">Save Changes</button>
                                      </div>
                                    </div>
                                  </form>
                                    <div class="row">
                                      <div class="col-12 col-sm-6 mb-3">
 <form  class="form" action="{{ url('Change_password') }}" method="POST" autocomplete="off"  onsubmit ="return verifyPassword()">
                                     {{ csrf_field() }}
                                     <input Type="text" name="id" value="{{$user->id}}" hidden>
                                        <div class="mb-2"><b>Change Password</b></div>
                                        <div class="row">
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Current Password</label>
                                              <input class="form-control" type="password"  name="current_pwd" id="current_pwd" placeholder="old passowrd">
                                              <span id = "message_current" style="color:red"> </span>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col">
                                            <div class="form-group">
                                              <label>New Password</label>
                                              <input class="form-control" type="password" name="new_pwd" id="new_pwd"  placeholder="new password">
                                              <span id = "message_new" style="color:red"> </span>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col">
                                            <div class="form-group">
                                              <label>Confirm <span class="d-none d-xl-inline">Password</span></label>
                                              <input class="form-control" type="password" name="confirm_pwd" id="confirm_pwd" placeholder="confirm password"></div>
                                              <span id = "message_confirm" style="color:red"> </span>
                                              <span id = "message_length" style="color:red"> </span>
                                              <span id = "message_wrong" style="color:red"> </span>
                                              <span id = "message_check" style="color:green"> </span>

                                          </div>
                                        </div>
                                        <div class="row">
                                      <div class="col d-flex justify-content-end">
                                        <button class="btn btn-danger" type="submit">Change password</button>
                                      </div>
                                    </div>

                                      <form>

                                      </div>
                                      

                                    </div>
                                    
                
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                
                      <div class="col-12 col-md-3 mb-3">
                        <div class="card mb-3">
                          <div class="card-body">
                            <h6 class="card-title font-weight-bold">Subscription infos</h6>
                    
                              @if($user->lastsubscription_id)
                              @php 
                              $subscription = \App\Model\User_Subscription::where('id',$user->lastsubscription_id)
                                                                            ->where('payment_status','ok')
                                                                            ->get()->first();
                              @endphp
                              @if($subscription)
                              <p class="card-text">Start date : <b>{{ $subscription->subscription_from_date }}</b></p>
                              <p class="card-text">Due date : <b>{{ $subscription->subscription_till_date}}</b></p>
                              @endif
                              @php 
                              if ($user->activated ==0){
                                $disabled = 'disabled';
                              } else{
                                $disabled = '';
                              }
                               @endphp
                              <button class="btn btn-block btn-danger" id="btn-confirm"  {{ $disabled }} ></i>Unsubscribe</button>
                              <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
                                <div class="modal-dialog modal-sm">       
                                <input Type="text" name="id" value="{{$user->id}}" hidden>
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                    </div>
                                    <div class="modal-body">
                                    <p> You want to cancel your subscription , Are you sure ?</p>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-danger" id="Unsubscribe_button" >unsubscribe</button>
                                      <button type="button" class="btn btn-primary" id="modal-btn-no" data-dismiss="modal">cancel</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              

<div class="alert" role="alert" id="result"></div>
                            @else
                            Please subscribe to activate your account!
                            <a href="/subscription" class="btn btn-block btn-danger" id="btn-confirm">Subscribe</a>

                              @endif
                          </div>
                        </div>
                   
                        <div class="card">
                            <div class="card-body">
                              <h6 class="card-title font-weight-bold">Support</h6>
                              <p class="card-text">Get fast, free help from our friendly assistants.</p>
                              <button type="button" class="btn btn-primary">Contact Us</button>
                            </div>
                          </div>
                      </div>
                    </div>
                
                  </div>
                </div>
                </div>
                <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
                <script src="http://netdna.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
                <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

            </section>
    </div>
</div>
<script>

  $("#Unsubscribe_button").click(function(event){
      event.preventDefault();

      let id = $("input[name=id]").val();

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
      $.ajax({
        url: "{{ url('unsubscribe_user') }}",
        type:"POST",
        data:{
         id:id
        },
        success:function(response){
          console.log(response);
          if(response) {
           
            $("#mi-modal").modal('hide');
            alert("Your subscription has been successfully cancelled :( )");
            location.reload();

          }
        },
       });
  });
</script>
<script>

function verifyPassword()
{
  var confirm_pwd = document.getElementById("confirm_pwd").value;
  var new_pwd = document.getElementById("new_pwd").value;
  var current_pwd = document.getElementById("current_pwd").value;
  if(current_pwd == "") {
     document.getElementById("message_current").innerHTML = "**Fill the password please!";
     return false;
  }
  if(new_pwd == "") {
     document.getElementById("message_new").innerHTML = "**Fill the password please!";
     return false;
  }
  if(confirm_pwd == "") {
     document.getElementById("message_confirm").innerHTML = "**Fill the password please!";
     return false;
  }
  if(new_pwd.length <8)
  {
    document.getElementById("message_length").innerHTML = "**the password must be at least 8 charachters!";
     return false;
  }
  if(new_pwd!=confirm_pwd)
  {
    document.getElementById("message_wrong").innerHTML = "";
    document.getElementById("message_wrong").innerHTML = "** New password confirmation doesn't match!";
    return false;

  }
  else{

    document.getElementById("message_wrong").innerHTML = "";
    document.getElementById("message_length").innerHTML = "";
    document.getElementById("message_check").innerHTML = "**Chek you password !";
    return true;
  }
}
$("#btn-confirm").on("click", function(){
    $("#mi-modal").modal('show');
  });

</script>
@endsection
