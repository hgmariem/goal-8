
@extends('layouts.base') 

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
                    @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif
        
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session()->get('error') }}
                </div>
                @endif
        
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session()->get('success') }}
                </div>
                @endif
                </div>
                <div class="yellow-head"><h1>A simple price structure for great Products</h1></div>
                
                <ul>
                    <div
                        class="subscription-row">
                        @foreach ($plans as $plan)
                        <form action="{{ url('payment') }}" method="post">
                            {{ csrf_field() }}
                            <div class="col-md-3" style="margin-top : 5px;">
                                <div class="box subscription-box">
                                    <div class="box-header text-center">
                                        <h3>{{ $plan-> title }}</h3>
                                    </div>
                                    <div class="box-body text-center">
                                        <ul>
                                          @foreach ($plan->items as $item)
                                          <li>{{ $item->title }}: <b>{{ $item->description }}</b></li>
                                          @endforeach
                                            
                                        </ul>
                                        <div class="plan-price">
                                            <h3 id="div_price{{ $plan->id }}">$ {{ $plan->normal_price }}</h3>
                                            <h4>Per month </h4>
                                            <select name="price_plan{{$plan->id}}" id="price_plan{{$plan->id}}" onchange="getPrice({{$plan->id}})">
                                                <option value="normal_price"  class="{{ $plan->normal_price }}" selected> 
                                                     {{ $plan->normal_period }} Month(s)
                                                    </option>
                                                <option  value="discount_price" class="{{ $plan->discount_price }}"> 
                                                    {{ $plan->discount_period }} Month(s)
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="box-footer text-center">
                                            <input type="hidden" name="plan_id" value="{{ $plan->id }}" />
                                            <button type="submit" class="btn btn-block btn-lg choose-plan-btn"
                                                title="Process to payment">Choose Plan</button>
                                    </div>
                                </div>
                            </div>
                        </form>     
                        @endforeach
                    </div>   
                           
            </section>
    </div>
</div>
@endsection
<script>
   function getPrice(id){
    console.log("price_plan"+id)
    var selectedName = "price_plan"+id
    console.log($('select[name="'+selectedName+'"] :selected').attr('class'))
    var price = $('select[name="'+selectedName+'"] :selected').attr('class')
    $("#div_price"+id).html("$ "+price)
   }
</script>