(function () {

    'use strict';

    var haveGSAP = false,
        GSAPVersion = null;

    if (typeof window.TweenLite == 'function') {
        haveGSAP = true;
        GSAPVersion = String(window.TweenLite.version);
    }

    var eventDetail = {
        "haveGSAP": haveGSAP,
        "GSAPVersion": GSAPVersion
    };

    var $body = document.body;
    $body.setAttribute('data-gsap', JSON.stringify(eventDetail));

    var gsapEvent = new CustomEvent('gsapcheck', {
        'detail': eventDetail
    });

    document.dispatchEvent(gsapEvent);

})();
