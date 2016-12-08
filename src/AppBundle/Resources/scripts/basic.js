function pad (val) { return val > 9 ? val : "0" + val; }


function countdown(seconds, minutes, hours, timestamp) {
    // + 2000 because on timer starts 2 seconds early
    var dif = new Date(timestamp * 1000 + 2000) - new Date().getTime();
    var sec = Math.abs(dif / 1000);

    // first iteration
    seconds.html(pad(parseInt(--sec % 60)));
    minutes.html(pad(parseInt(sec/60,10)));
    hours.html(pad(parseInt(sec/3600,10)));

    setInterval( function(){
        if(sec > 0)
        {
            seconds.html(pad(parseInt(--sec % 60)));
            minutes.html(pad(parseInt(sec/60,10)));
            hours.html(pad(parseInt(sec/3600,10)));
        }
    }, 1000);
}