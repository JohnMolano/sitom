var _gaq = _gaq || [];

_gaq.push(['_setAccount', 'UA-48204277-1']);
_gaq.push(['_setDomainName', 'bpmn.io']);
_gaq.push(['_trackPageview']);

(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();


function track(action, type, payload) {
  if (window._gaq) {
    window._gaq.push(['_trackEvent', action, type, payload]);
  }
}

(function($) {

  $('[data-track]').each(function() {
    var e = $(this);

    var data = e.attr('data-track').split(':');

    e.on('click', function() {
      track(data[0], data[1], data[2]);
    });
  });

})(require('jquery'));