@extends('layouts.base')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/xps.css') }}">
  @section('page_head_css_scripts')
<style>

body {
    font-size: 0.85em;
    font-family:Helvetica, Arial, sans-serif;
}
.avatar {
    padding: 0;
}
#shared {
    /*//padding-top: 16px;*/
}
.shared-section{
	padding-top: 20px;
}

[dlmenu] .right-button, .root-button .right-button {
    height: 32px;
}

#shared .search input {
    height: 40px;
}

#shared .viewbar ul li.list-view,
#shared .viewbar ul li.thumbs-view {
    width: 48px;
}
#shared .viewbar ul li.list-view.selected,
#shared .viewbar ul li.thumbs-view.selected {
    height: 30px;
}

</style>
@endsection
 @section('content')
<div ng-app="xpsWeb"  id="shared" ng-class="{scrollbars: navigator.appVersion.indexOf('Windows') !== -1}">
    <?php //echo $this->renderPartial('//layouts/header', array('title' => 'Yii Rock!')); ?>

<script type="text/ng-template" id="doc"><img src="" />
    <div>
        <span ng-class="{unread: doc.na > 0}" class="unread-dot"></span>
        <p class="title"></p>
        <p class="desc"></p>
    </div>
</script>
  
    <section class="shared-section" ng-controller="SharedCtrl" ng-cloak>
	  
		<div class="container shared">
        	<!--Menu-->
			<div class="row">
                <div class="span12 navbar">
                    <div ng-repeat="node in shared.getRoots()">
                        <div dlmenu="" class="dl-menuwrapper" ng-click="clicked = true" ng-class="{selected: path[0]._guid === node._guid, noname: node._singleFolder, loading: !shared.allLoaded, clicked: clicked}" refresh-on="'sharedLoaded'">
                            <button class="left-button" ng-class="{'dl-trigger': !node._singleFolder}" ng-click="selectRoot(node)"><span><span> <i ng-show="clicked && !shared.allLoaded" class="dot-one">.</i> <i ng-show="clicked && !shared.allLoaded" class="dot-two">.</i> <i ng-show="clicked && !shared.allLoaded" class="dot-three">.</i></span></span></button>
                            <button class="dl-trigger dropdown right-button" ng-class="{single: !node._singleFolder}" ng-click="active = !active"></button>
                            <ul tree="" class="dl-menu">
                                <li ng-show="node._children.length === 0 && shared.allLoaded" class="cf">
                                    <a class="empty">No documents</a>
                                </li>
                                <li ng-show="node._isFolder" class="cf" ng-repeat="node in node._children" ng-class="{nofolders: !shared.hasFolders(node)}">
                                    <a class="next-folder"></a>
                                    <a class="folder" ng-click="selectFolder(node, null, $event)"></a>
                                    <ul tree-recurse="" class="dl-submenu"></ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="viewbar" ng-init="view = 'thumbs'">
					@section('content')
                        <ul class="cf">
                            <li ng-click="view = 'thumbs'" ng-class="{selected: view == 'thumbs'}" class="button thumbs-view pull-right">
                                <svg version="1.1" width="18px" height="12px" viewbox="0 0 18 12" enable-background="new 0 0 18 12" xml:space="preserve"><g><path fill-rule="evenodd" clip-rule="evenodd" fill="#417EDA" d="M0,4h4V0H0V4z M7,4h4V0H7V4z M14,0v4h4V0H14z M0,12h4V8H0V12z
                                  M7,12h4V8H7V12z M14,12h4V8h-4V12z"></g></svg>
                            </li>
                            <li ng-click="view = 'list'" ng-class="{selected: view == 'list'}" class="button list-view pull-right">
                                <svg version="1.1" width="16px" height="12px" viewbox="0 0 16 12" enable-background="new 0 0 16 12"><g><path fill-rule="evenodd" clip-rule="evenodd" fill="#417EDA" d="M0,2h2V0H0V2z M0,12h2v-2H0V12z M0,7h2V5H0V7z M16,0H4v2h12V0z
                                  M4,5v2h12V5H4z M4,12h12v-2H4V12z"></g></svg>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--Search -->
            <div class="row">
                <div class="span12 search-row">
                    <div class="search">
                        <input placeholder="Search…" type="text" ng-model="searchTerm" ng-keyup="search()" autocorrect="off" autocapitalize="off">
                    </div>
                </div>
            </div>
            <!--Inner page-->
            <div class="row">
                <div doc-list="" class="span12 doc-list" ng-class="view">
                    <div class="doc-list-wrapper">
                        <ul class="cf">
                            <li ng-show="searchTerm" class="search-info"></li>
                            <li ng-show="!searchTerm" class="breadcrumb" ng-repeat="f in path" ng-class="{first: $index === 0}" ng-click="selectFolder(f, $index)"></li>
                        </ul>
                        <!--<svg ng-show="!shared.unreadLoaded" loader=""></svg>-->
                        <ul class="cf unread">
                            <!--<li document="doc" class="doc" ng-repeat="doc in docs.unread"><ng-include src="'doc'" class="doc-inner"></li>-->
                            <li document="doc" class="doc" ng-repeat="doc in docs.read"><ng-include src="'doc'" class="doc-inner"></li>
                        </ul>
                        <!--<svg ng-show="docs.read.length < allDocs.read.length || (shared.unreadLoaded && !shared.allLoaded)" loader=""></svg>-->
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

<script src="{{ URL::asset('js/xps.js') }}"></script>
@endsection