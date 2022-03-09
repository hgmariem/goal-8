@extends('layouts.base')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/css/xps.css')}}">
@section('page_head_css_scripts')
<style>

body {
    /*font-size: 0.85em;*/
    /*font-family:Helvetica, Arial, sans-serif;*/
}
.avatar {
    /*padding: 0;*/
}
#profile {
    /*padding-top: 16px;*/
}
.profile-section{
	padding-top: 40px;
}
input[type=text],
input[type=email],
input[type=password],
input[type=url] {
    height: 40px !important;
}

</style>
@endsection
@section('content')

<div id="profile" ng-app="xpsWeb">

    <section class="profile-section" ng-controller="ProfileCtrl">
		<div class="container profile">
			<div class="row">
                <div class="span2 profile-image-editor">
                    <div class="imagecontainer">
                        <img src="{{URL::asset('/img/anonymous@2x.png')}}">
            
                    </div>
            
                    <div class="file-input-button">
                        <input type="file" value="Change" ng-model-instant id="fileToUpload" single
                               onchange="angular.element(this).scope().setFiles(this)"/>
            
                        <input type="button" 
                               onclick="document.getElementById('fileToUpload').click()"/>
            
                        <input type="button" class="upload" ng-show="hasCrop" ng-click="uploadFile()"/>
            
                        <div ng-show="file" class="file-info">
                            <span></span>
                            (<span ng-switch="file.size > 1024* 1024">
                                <span ng-switch-when="true"> MB</span>
                                <span ng-switch-default> kB</span>
                            </span>)
            
                            <div ng-show="progressVisible" class="progress-bar"
                                 ng-style="{'width': ((progress / 100) * 162) + 'px'}"></div>
                        </div>
                   </div>
                </div>
            
                <div ng-show="sourceImage" class="span8 inputs">
                    <div class="personalinformation"></div>
                    <img id="source-image" src="" alt=""/>
                </div>
                <div ng-show="!sourceImage" class="span8 inputs">
                    <div class="profile-blue-top-panel">
                        
                        <div class="personalinformation"></div>
                    </div>
            
                    <div class="profile-half-input-container right-margin">
                        <div class="inputlabel profile-quarter-input-container right-margin">
                            
                            <input class="profile-quarter-input" type="text" ng-model="_json._firstName" required
                                   ng-blur="submitProfile(_json)">
                        </div>
            
                        <div class="inputlabel">
                           
                            <input class="profile-quarter-input" type="text" ng-model="_json._lastName" required
                                   ng-blur="submitProfile(_json)">
                        </div>
            
                        <div class="inputlabel">
                           
                            <select class="profile-select profile-half-input" ng-model="_json._country" ng-blur="submitProfile(_json)">
                                <option ng-repeat="c in _countries"></option>
                            </select>
                        </div>
            
                        <div class="inputlabel">
                            State (US)
                            <select class="profile-select profile-half-input" ng-disabled="_json._country!=='United States'"
                                    ng-model="_stateText" ng-blur="submitProfile(_json)">
                                <option ng-repeat="c in _states"></option>
                            </select>
                        </div>
            
                        <div class="inputlabel">
                          
                            <input class="profile-half-input" type="text" ng-model="_json._city" ng-blur="submitProfile(_json)">
                        </div>
                    </div>
            
                    <div class="profile-half-input-container">
                        <div class="inputlabel profile-quarter-input-container right-margin">
                           
                            <select class="profile-select profile-quarter-input" ng-model="_json._gender" required
                                    ng-blur="submitProfile(_json)">
                                <option ng-repeat="c in _genders"></option>
                            </select>
                        </div>
            
                        <div class="inputlabel profile-quarter-input-container">
                          
                            <input class="profile-quarter-input" type="text" ng-model="_json._yearOfBirth"
                                   ng-blur="submitProfile(_json)">
                        </div>
            
                        <div class="inputlabel profile-quarter-input-container right-margin">
                            
                            <input class="profile-quarter-input" type="text" ng-model="_json._currentWeight"
                                   ng-disabled="_json._isWeightRegistrationPossible !== true">
                        </div>
            
                        <div class="inputlabel profile-quarter-input-container" ng-show="_json._heightUnits.length===1">
                            
                        </div>
                        <div class="inputlabel profile-quarter-input-container" ng-show="_json._heightUnits.length===2">
                          
                        </div>
                        <div class="profile-quarter-input-container" ng-show="_json._heightUnits.length===1">
                            <input type="text" class="profile-quarter-input" ng-disabled="_json._isHeightRegistrationPossible !== true"
                                   ng-model="_json._currentHeight[0]" ng-blur="submitProfile(_json)">
                        </div>
                        <div lass="profile-quarter-input-container" ng-show="_json._heightUnits.length===2">
                            <input class="profile-feet-or-inch-input profile-feet-input" type="text"
                                   ng-disabled="_json._isHeightRegistrationPossible !== true" ng-model="_json._currentHeight[0]"
                                   ng-blur="submitProfile(_json)"> 
                            <input class="profile-feet-or-inch-input" type="text"
                                   ng-disabled="_json._isHeightRegistrationPossible !== true" ng-model="_json._currentHeight[1]"
                                   ng-blur="submitProfile(_json)"> 
                        </div>
                        <div style="clear: both"></div>
                        <div class="inputlabel">
                          
                            <input class="profile-half-input" type="email" ng-model="_json._emailAddress" required
                                   ng-blur="submitProfile(_json)">
                        </div>
                    </div>
            
                    <div class="profile-account-panel">
                        <div class="accountinformation"></div>
                        <div class="inputlabel profile-half-input-container right-margin">
                           
                            <input type="text" class="profile-account-username" ng-model="_json._userName" ng-disabled="true" required>
                        </div>
                        <div class="inputlabel profile-half-input-container">
                          
                            <input class="profile-account-username" type="password" value="********" ng-disabled="true" required>
                        </div>
                        <a href="" class="profile-change-credentials" ng-click="openCredentials()"></a>
                    </div>
                </div>
			</div>
		</div>
		
<!--   <div>
      Areas of training:
      <list ng-repeat="sport in _json._sports">
         <div ng-show="sport._isTeamSport">
            <select ng-model="sport._guid" ng-options="s._guid as s._name for s in _allSports._availableTeamSports">
            </select>
            (team sport)
         </div>
         <div ng-show="!sport._isTeamSport">
            <select ng-model="sport._guid" ng-options="s._guid as s._name for s in _allSports._availableIndividualSports">
            </select>
            (individual sport)
         </div>
      </list>
   </div>-->


    </section>
</div>

<script src="{{URL::asset('/js/xps.js')}}"></script>
@endsection