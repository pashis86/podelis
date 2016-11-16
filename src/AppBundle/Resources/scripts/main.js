function timedCounter(finalValue, seconds, callback){

    var startTime = (new Date).getTime();
    var milliseconds = seconds*1000;

    (function update(){

        var currentTime = (new Date).getTime();
        var value = finalValue*(currentTime - startTime)/milliseconds;

        if(value >= finalValue)
            value = finalValue;
        else
            setTimeout(update, 350);

        callback && callback(value);

    })();

}

function timedCounterCall(n) {

    timedCounter(n, 7, function(value){
        value = Math.floor(value);
        $('.user-count').html(value);
    });
};