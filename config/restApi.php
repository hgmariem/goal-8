<?php
return [

         'loginApi' => array(
            'url' => 'http://www4.sidelinesports.com/xpsweb?operation=lclogon&',
            'secret' => 'Land123Rover',
            'curl_timeout' => 30,
        ),
         'postloginApi' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonLoginRequestQuery2',
            'curl_timeout' => 30,
        ),
        'messageApi' => array(
            'url' => 'http://www4.sidelinesports.com/xpsweb/?',
            'prefix_function' => 'is.sideline.apps.xps.server.web.json.messages.',
            'curl_timeout' => 30,
        ),
        'userApi' => array(
            'url' => 'http://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonCommunicationBasicQuery&charset=UTF-8&_languageCode=en',
            'curl_timeout' => 30,
        ),
        'getAllChatApi' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonCommunicationQuery2',
            'curl_timeout' => 30,
        ),
        'userDetailsApi' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonProfileQuery&charset=UTF-8',
            'curl_timeout' => 30,
        ),
        'userCategoriesApi' => array(
            'url' => 'http://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonKeyHabitsGroupsQuery',
            'curl_timeout' => 30
        ),
        'updateProfileApi' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonProfileChangeRequest',
            'curl_timeout' => 30,
        ),
        'changePreferenceOnMail' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonPreferenceUpdateRequest',
            'curl_timeout' => 30,
        ),
        'changeImageApi' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonProfileChangeRequest',
            'curl_timeout' => 30,
        ),
        'credChangeApi' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonCredChangeRequest',
            'curl_timeout' => 30,
        ),
        'deleteMessage' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonDeleteChatRequest',
            'curl_timeout' => 30,
        ),
        'addMessage' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonSaveChatRequest',
            'curl_timeout' => 30,
        ),
        'programAvailabity' => array(
            'url' => 'https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonSharingQuery',
            'curl_timeout' => 30
        ),
    // application timezone
    'timeZone' => 'UTC'
	
]

       
?>