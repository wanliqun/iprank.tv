// Define YT_ready function.
var YT_ready = (function() {
    var onReady_funcs = [], api_isReady = false;
    /* @param func function     Function to execute on ready
     * @param func Boolean      If true, all qeued functions are executed
     * @param b_before Boolean  If true, the func will added to the first
                                 position in the queue*/
    return function(func, b_before) {
        if (func === true) {
            api_isReady = true;
            while (onReady_funcs.length) {
                // Removes the first func from the array, and execute func
                onReady_funcs.shift()();
            }
        } else if (typeof func == "function") {
            if (api_isReady) func();
            else onReady_funcs[b_before?"unshift":"push"](func); 
        }
    }
})();

// This function will be called when the API is fully loaded
function onYouTubePlayerAPIReady() { YT_ready(true); }

// Load YouTube Frame API
(function() { // Closure, to not leak to the scope
	var s = document.createElement("script");
  	s.src = (location.protocol == 'https:' ? 'https' : 'http') + "://www.youtube.com/player_api";
  	var before = document.getElementsByTagName("script")[0];
  	before.parentNode.insertBefore(s, before);
})();