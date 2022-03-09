@extends('layouts.base')
@section('page_head_css_scripts')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/xps.css') }}">
<style>
    body {
        font-size: 0.85em;
        font-family:Helvetica, Arial, sans-serif;
    }
    .avatar {
        padding: 0;
    }
    #message {
        padding-top: 16px;
    }
    .message-section-box{
        margin-top: 45px !important;
    }
</style>
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
@verbatim
	
	<div id="message" ng-app="xpsWeb" ng-cloak>

    <?php //echo $this->renderPartial('//layouts/header', array('title' => 'Yii Rock!')); ?>

    <section class="message-section" ng-controller="MessagesCtrl">

        <div class="container messages">
            <div id="video-player-container"></div>

            <div class="row message-section-box" style="width: 1010px;" ng-class="{'chat-open': currentGroup}">
                <div class="span4 left-col">
                    <div class="antiscroll-wrap">
                        <div class="antiscroll-inner">
                            <h3>{{ str.trainers}}</h3>
                            <ul ng-show="groups._usersToChatTo.length > 0" class="trainers">
                                <li ng-repeat="withUser in groups._usersToChatTo" ng-class="{unread: withUser._unreadByMeInGroup > 0, active: currentGroup._guidS == withUser._guidS}" ng-click="loadMessages(withUser, 'bottom')">
                                    <div class="unread-point"></div>
                                    <span class="avatar">
                                        <img ng-show="withUser._avatarURL" ng-src="{{ withUser._avatarURL}}" alt=""  />
                                    </span>
                                    <p class="time" ng-show="withUser._lastChatTime">{{withUser._lastChatTime| fromNow}}</p>
                                    <p class="name">
                                        {{withUser._name}}
                                        <span ng-show="withUser.isLoading"><span class="dot-one">.</span><span class="dot-two">.</span><span class="dot-three">.</span></span>
                                    </p>
                                    <p class="last-msg">{{withUser._lastMessage|truncate:35}}</p>
                                </li>
                            </ul>
                            <h3 ng-show="groups._groupsToChatIn.length > 0">{{ str.discussion}}</h3>
                            <ul class="groups trainers">
                                <li ng-repeat="withUser in groups._groupsToChatIn" ng-class="{unread: withUser._unreadByMeInGroup > 0, active: currentGroup._guidS == withUser._guidS}" ng-click="loadMessages(withUser, 'top')">
                                    <p class="num-msg">{{withUser._totalMessages}}</p>
                                    <p class="name">
                                        {{withUser._name}}
                                        <span ng-show="withUser.isLoading"><span class="dot-one">.</span><span class="dot-two">.</span><span class="dot-three">.</span></span>
                                    </p>
                                    <p class="last-msg" ng-show="withUser._lastChatTime">{{ str.latestPost}} {{withUser._lastChatTime| fromNow}}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="span5 right-col" ng-show="!currentGroup && !hasTrainer">
                    <div class="alert alert-info">{{ str.noTrainersConnected}}</div>
                </div>
                <div class="span5 right-col" ng-show="currentGroup">
                    <h2 ng-click="backClicked()"><span class="back"></span>{{currentGroup._name}}</h2>
                    <div class="antiscroll-wrap">
                        <div class="antiscroll-inner conversation" ng-if="currentGroup._type != 'Group'">
                            <ul>
                                <li ng-show="!currentMarker && messages" class="separator desktop-only">
                                    <div class="line"></div>
                                    <div class="text"><span>{{ str.conversationStarted}} {{ messages[0].sent | date:"dd MMM H:mm" }}</span></div>
                                </li>
                                <div ng-repeat="message in messages" class="message-wrapper">
                                    <li class="separator mobile-only cf" ng-show="(message.sent | date: 'dd-MMM-yyyy') !== (messages[$index - 1].sent | date: 'dd-MMM-yyyy')">
                                        <div class="text" ng-switch on="(date.now() | date: 'dd-MMM-yyyy') == (message.sent | date: 'dd-MMM-yyyy')">
                                            <span ng-switch-when="true">TODAY {{message.sent| date: 'H:mm'}}</span>
                                            <span ng-switch-default>{{message.sent| date: 'dd MMM H:mm'}}</span>
                                        </div>
                                    </li>
                                    <li ng-class="{clearfix: true, sent: message.fromGuidS == user.userGuid}">
                                        <span class="avatar">
                                            <img ng-show="message.fromImgUrl" ng-src="{{ message.fromImgUrl}}" alt=""/>
                                        </span>
                                        <div class="body">
                                            <a class="delete" href="javascript:void(0)" ng-if="message.fromGuidS==user.userGuid" ng-click="deleteMessage(message)" confirmation-needed><i></i></a>
                                            <span class="message-text" ng-bind-html="message.messageBody | clickableLinks"></span>
                                            <div class="attached-media">
                                                <div class="image-thumb" ng-show="message.imageThumbUrl">
                                                    <a video-link ng-href="{{ message.imageUrl}}">
                                                        <img ng-src="{{ message.imageThumbUrl}}" alt="attached image thumbnail" />
                                                    </a>
                                                </div>
                                                <div ng-show="message.videoThumbUrl">
                                                    <div ng-if="!IS_IOS && !IS_ANDROID" class="video-thumb">
                                                        <a video-link ng-href="{{ message.videoUrl}}">
                                                            <i class="play"></i>
                                                            <img ng-src="{{ message.videoThumbUrl}}" alt="attached video thumbnail" />
                                                        </a>
                                                    </div>
                                                    <video ng-if="IS_IOS || IS_ANDROID" controls
                                                           ng-poster="message.videoThumbUrl"
                                                           onclick="this.webkitRequestFullscreen()"
                                                           src="{{ message.videoUrl | unsafeUrl }}"></video>
                                                </div>
                                                <div class="links" ng-show="message.links.length > 0">
                                                    <a doc-link ng-repeat="link in message.links" href="{{ link.url}}">{{ link.itemName}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="time">
                                            {{message.sent| date:"MMM dd"}}<br/>{{message.sent| localeDate:"time"}}
                                        </div>
                                    </li>
                                </div>
                            </ul>
                        </div>
                        <div class="antiscroll-inner conversation group" ng-if="currentGroup._type == 'Group'">
                            <ul>
                                <li ng-repeat="message in messages" class="clearfix">
                                    <span class="avatar">
                                        <img ng-show="message.fromImgUrl" ng-src="{{ message.fromImgUrl}}" alt=""/>
                                    </span>
                                    <div class="body">
                                        <a class="delete" href="javascript:void(0)" ng-if="message.fromGuidS==user.userGuid" ng-click="deleteMessage(message)" confirmation-needed><i></i></a>
                                        <b>{{message.fromName}}</b>
                                        <span class="message-text" ng-bind-html="message.messageBody | clickableLinks"></span>
                                        <div class="attached-media">
                                            <div class="image-thumb" ng-show="message.imageThumbUrl">
                                                <a video-link ng-href="{{ message.imageUrl}}">
                                                    <img ng-src="{{ message.imageThumbUrl}}" alt="attached image thumbnail" />
                                                </a>
                                            </div>
                                            <div ng-show="message.videoThumbUrl">
                                                <div ng-if="!IS_IOS" class="video-thumb">
                                                    <a video-link ng-href="{{ message.videoUrl}}">
                                                        <i class="play"></i>
                                                        <img ng-src="{{ message.videoThumbUrl}}" alt="attached video thumbnail" />
                                                    </a>
                                                </div>
                                                <video ng-if="IS_IOS" controls
                                                       onclick="this.webkitRequestFullscreen()"
                                                       src="{{ message.videoUrl | unsafeUrl }}"></video>
                                            </div>
                                            <div class="links" ng-show="message.links.length > 0">
                                                <a doc-link ng-repeat="link in message.links" href="{{ link.url}}">{{ link.itemName}}</a>
                                            </div>
                                        </div>
                                        <div class="time">{{message.sent| date:"MMM dd H:mm"}}</div>
                                    </div>
                                    <div class="time">
                                        {{message.sent| date:"MMM dd"}}<br/>{{message.sent| date:"H:mm"}}
                                    </div>
                                    <ul class="replies">
                                        <li ng-repeat="reply in replies[message.guidS]">
                                            <div class="body" id="msg-{{reply.guidS}}">
                                                <a class="delete" href="javascript:void(0)" ng-if="reply.fromGuidS==user.userGuid" ng-click="deleteMessage(message)" confirmation-needed><i></i></a>
                                                <span class="avatar">
                                                    <img ng-show="reply.fromImgUrl" ng-src="{{ reply.fromImgUrl}}" alt=""/>
                                                </span>
                                                <div class="text">
                                                    <b>{{reply.fromName}}</b>
                                                    <span class="message-text" ng-bind-html="reply.messageBody | clickableLinks"></span>
                                                    <div class="attached-media">
                                                        <div class="image-thumb" ng-show="reply.imageThumbUrl">
                                                            <a video-link ng-href="{{ reply.imageUrl}}">
                                                                <img ng-src="{{ reply.imageThumbUrl}}" alt="attached image thumbnail" />
                                                            </a>
                                                        </div>
                                                        <div ng-show="reply.videoThumbUrl">
                                                            <div ng-if="!IS_IOS" class="video-thumb">
                                                                <a video-link ng-href="{{ reply.videoUrl}}">
                                                                    <i class="play"></i>
                                                                    <img ng-src="{{ reply.videoThumbUrl}}" alt="attached video thumbnail" />
                                                                </a>
                                                            </div>
                                                            <video ng-if="IS_IOS" controls
                                                                   onclick="this.webkitRequestFullscreen()"
                                                                   src="{{ reply.videoUrl | unsafeUrl }}"></video>
                                                        </div>
                                                        <div class="links" ng-show="reply.links.length > 0">
                                                            <a doc-link ng-repeat="link in reply.links" href="{{ link.url}}" >{{ link.itemName}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="time mobile-only">{{reply.sent| date:"MMM dd H:mm"}}</div>
                                                </div>
                                            </div>
                                            <div class="time">
                                                {{reply.sent| date:"MMM dd"}}<br/>{{reply.sent| date:"H:mm"}}
                                            </div>
                                        </li>
                                    </ul>
                                    <div ng-form="replyForm" ng-submit="submitReply(message)" class="newReplyForm">
                                        <textarea rows="1" placeholder="{{ str.writeReply}}…" ng-model="message.replyText"></textarea>
                                        <div class="controls">
                                            <div class="switch-label switch-anonymous" ng-class="{on: isAnonymousReply}" ng-click="toggleAnonymousReply()">
                                                <div class="switch">
                                                    <div class="switch-button"></div>
                                                </div>
                                                <span>{{ str.replyAsAnonymous}}</span>
                                            </div>
                                            <button ng-click="submitReply(message)" ng-show="!enterToSubmit" type="submit">{{ str.reply}}</button>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="newPostForm clearfix">
                        <div class="switch-label switch-anonymous mobile-only" ng-class="{on: isAnonymousPost}" ng-click="toggleAnonymousPost()" ng-show="currentGroup._type == 'Group'">
                            <div class="switch">
                                <div class="switch-button"></div>
                            </div>
                            <span>{{ str.postAsAnonymous}}</span>
                        </div>
                        <div ng-form="reply" ng-submit="submitReply()">
                            <textarea ng-model="replyText" ng-attr-rows="{{ IS_MOBILE && '1' || '3' }}" placeholder="{{verb(true)}}…"></textarea>
                            <div class="switch-label switch-anonymous" ng-class="{on: isAnonymousPost}" ng-click="toggleAnonymousPost()" ng-show="currentGroup._type == 'Group'">
                                <div class="switch">
                                    <div class="switch-button"></div>
                                </div>
                                <span>{{ str.postAsAnonymous}}</span>
                            </div>
                            <div class="switch-label switch-enter" ng-class="{on: enterToSubmit}" ng-click="toggleEnterToSubmit()">
                                <div class="switch">
                                    <div class="switch-button"></div>
                                </div>
                                <span>{{ str.pressEnterTo}} {{verb(false)}}</span>
                            </div>
                            <button ng-show="!enterToSubmit" type="submit" ng-click="submitReply()">{{verb(false)}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
</div>

@endverbatim          
</div>
</div>


<script src="{{ URL::asset('js/xps_26_oct.js') }}"></script>
@endsection