@extends('layouts.base') 
@section('content')

<style type="text/css">
	form ul > li > label {
        display: inline-block;
        position: relative;
        top: 3px;
    }

    form ul > li {
        float: left;
        width: 25%;
        list-style: none;
    }
    .field {
        overflow: hidden;
    }
    .buttons {
        margin-top: 20px;
    }
    .row-field {
        width: 50%;
        float: left;
    }
    .clear {
        overflow: hidden;
        clear: both;
    }
    .groups {
        margin-top: 15px;
    }
    input[type=text],
    input[type=url] {
        width: 80%;
    }
    input[type=checkbox]:not(old), input[type=radio ]:not(old){
        opacity: 1;

    }
    input[type=checkbox]:not(old) + label, input[type=radio ]:not(old) + label {
        display: inline-block;
        margin-left: 10px;
        line-height: 34px;
    }
    .field.row-field{    margin-top: 10px;}
    .field.row-field label{
        width: 95px;
        font-weight: bold;
    }
    .field.row-field input{}
</style>
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
	    <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
	    	<?php 
		        if($isMobile){?>
		            @include('mobile_header')
		    <?php } ?>

		    <div class="container">
				<h1>Create Webinar</h1>

		    <div class="goal-create-details"> 
			    <div class="form">
			    	<p class="note">Fields with <span class="required">*</span> are required.</p>
			    		  {!! Form::open(array('action' => 'WebinarController@add')) !!}
			    		  	<div class="field row-field">
					            <label for="Webinar_name">Name</label>            
					            <input size="60" maxlength="255" name="Webinar[name]" id="Webinar_name" type="text" value="">        
				        	</div>

				        	<div class="field row-field">
					            <label for="Webinar_url">Url</label>            
					            <input size="60" maxlength="255" name="Webinar[url]" id="Webinar_url" type="text" value="">        
        					</div>

        					<div class="field row-field">
					            <label for="Webinar_date">Date</label>            
					            <input id="Webinar_date" name="Webinar[date]" type="text" class="hasDatepicker">        
					        </div>

					        <div class="field row-field">
								<label for="Webinar_register_url">Register Url</label>
								<input size="60" maxlength="255" name="Webinar[register_url]" id="Webinar_register_url" type="text">        
							</div>

							<div class="clear"></div>

							<div class="field groups">
                				<label for="Webinar_groups_allowed">Groups Allowed</label>                
			            		<ul>
				                    <li>
					                    <input type="checkbox" value="1" name="Webinar[groups_allowed][7241924199142776832]" id="Webinar_groups_allowed_7241924199142776832">    
					                    <label for="">Not Active</label>                    
			                		</li>
			            		</ul>
        					</div>

			    		  	<div class="field buttons">
			      				<input type="hidden" name="webinar_id" id="webinar_id" value="<?php echo isset($webinar->id)?$webinar->id:0?>">
				                <input name="save" id="btnSubmit" class="submit" type="submit" value="SAVE" />      
				            </div>

			    		  {!! Form::close() !!}
			    </div>
			</div>
	    </div>
	    </div>
	</div>
</div>

@endsection 