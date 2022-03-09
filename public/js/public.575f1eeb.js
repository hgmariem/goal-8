(function () {
  var appEl = document.getElementById('sharedInfoApp');
  var browserNotSupported =
    (bowser.msie) ||
    (bowser.opera && bowser.version < 38) ||
    (bowser.safari && bowser.version < 10) ||
    (bowser.chrome && bowser.version < 51) ||
    (bowser.edge && bowser.version < 40) ||
    (bowser.firefox && bowser.version < 57);
  if (browserNotSupported) {
    appEl.innerHTML = '<p style="font-size:20px;text-align:center;margin:40px 0;line-height:2"><strong>This browser version is currently unsupported</strong><br>Please use the most recent version of ' +
      (bowser.mac ? '<a href="https://support.apple.com/en-is/HT204416">Safari</a> ' : '') +
      (bowser.windows ? '<a href="https://www.microsoft.com/en-us/windows/microsoft-edge">Edge</a> ' : '') +
      ' or <a href="https://www.google.com/chrome/browser/desktop/">Google Chrome</a>';
    return;
  }

  //var loadingEl = document.getElementById('loading');

  //loadingEl.style.display = 'relative';

  var publicId = "khdoc";

  if (!publicId) {
    appEl.innerHTML = '<p>Invalid public collection</p>';
    return;
  }

  fetch('https://www4.sidelinesports.com/xpsweb/?json=is.sideline.apps.xps.server.web.json.messages.JsonPublicShareQuery&charset=UTF-8&gzip=true&languageCode=en', {
    method: 'POST',
    body: JSON.stringify({
      _publicId: publicId
    }),
    headers: {
      'Content-Type': 'application/json'
    }
  })
    .then(res => res.json())
    .then(json => {
      if (json._failureTextForUser) {
        //loadingEl.style.display = 'none';
        appEl.innerHTML = json._failureTextForUser;
        return;
      }
      window.USER_SESSION = {
        sessionId: json._sessionId,
        isTrainer: true,
        userGuid: json._collectionOwnerId,
        selectedCollectionType: json._type,
      };
      window.CUSTOM_COLLECTION = true;
      window.XPS_COLLECTION_SHOW_NAME_INPUT = true;
      window.XPS_COLLECTION_DISABLE_COLLECTION_TYPE_SELECTOR = true;
      window.XPS_COLLECTION_SHOW_SHARING = !json._disableSharing;
      //if (json._disableSharing) {
        window.XPS_COLLECTION_DISABLE_ADD_TO_XPS = true;
        window.XPS_COLLECTION_DISABLE_SHARE_BY_EMAIL = true;
      //}
      EmbedXPSCollectionBrowser(appEl, null, null, () => {
        //loadingEl.style.display = 'none';
      });
    });

  window.onhashchange = () => location.reload();
})();
