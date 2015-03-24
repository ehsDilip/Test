
window.fbAsyncInit = function() {
    FB.init({
        appId: '679880192067529', // replace your app id here
        channelUrl: '',
        status: true,
        cookie: true,
        xfbml: true
    });
};
(function(d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

function FBLogin() {
    FB.login(function(response) {
        if (response.authResponse) {
            window.location.href = "actions.php?action=fblogin";
        }
    }, {scope: 'email'});
}
        