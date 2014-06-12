define([
  'jquery'
], 
function($)
{
    var fbLogin, goLogIn;

    $(function()
    {

        $('#signin a.fb').click(function()
        {
            fbLogin();
            return false;
        });

        goLogIn = function() 
        {
            window.location.href = '/fb/login_check';
        };

        fbLogin = function()
        {
            if (typeof(FB) != 'undefined' && FB != null)
            {
                
                FB.getLoginStatus(function(response) 
                {
                    if (response.status === 'connected'){
                        goLogIn();
                    } 
    //                else if (response.status === 'not_authorized') 
    //                {
    //                    // not_authorized
    //                }
                    else 
                    {
                        // not_logged_in
                        FB.login(function(response) 
                        {
                            console.log(response);

                            if (response.authResponse) 
                            {
                                setTimeout(goLogIn, 500); 
                            }
                            else 
                            {
                                // cancelled
                            }

                        }, {
                            scope:'email'
                        });
                    }

                });
            }
            else
            {
                console.log('FB NULL');
            }
        };


    });
    
});