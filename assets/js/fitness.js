/**
 * Created by scolton17 on 1/10/17.
 */
var cur = 1;

function next() {
    var nxt = cur + 1;

    var c = document.getElementById("form-" + cur);
    var n = document.getElementById("form-" + nxt);

    window.cur += 1;

    c.style.transition = "initial";

    c.style.transition = "bottom 0.75s, opacity 0.5s";
    c.style.position = "absolute";
    c.style.width = "65vw";
    c.style.maxWidth = "800px";

    var bottom = document.getElementById("form").getBoundingClientRect().bottom;

    var newBottom = bottom - c.getBoundingClientRect().bottom;

    console.log(newBottom);

    c.style.bottom = newBottom + "px";

    window.setTimeout(function() {
        c.style.bottom = "500px";
        c.style.opacity = 0.0;

        window.setTimeout(function () {
            c.style.display = "none";

            n.style.opacity = 0.0;
            n.style.display = "block";

            n.style.position = "static";

            var pos = n.getBoundingClientRect().top - document.getElementById("form").getBoundingClientRect().top;

            n.style.position = "absolute";
            n.style.top = "500px";

            n.style.transition = "top 0.75s, opacity 0.5s";

            window.setTimeout(function () {
                n.style.top = "calc(" + pos + "px - 38.4px)";
                n.style.opacity = 1.0;

                window.setTimeout(function() {
                    n.style.top = "auto";
                    n.style.bottom = "auto";
                    n.style.position = "static";
                }, 750);

            }, 100);

        }, 300);
    }, 100);
}

function switchMetric() {
    var cust = document.getElementById("customarybox");
    var metr = document.getElementById("metricbox");

    metr.style.display = "none";

    cust.style.opacity = 0.0;
    metr.style.opacity = 0.0;

    window.setTimeout(function() {
        cust.style.display = "none";
        metr.style.display = "block";

        window.setTimeout(function() {
            metr.style.opacity = 1.0;
        }, 100);
    }, 500);
}

function switchCustomary() {
    var cust = document.getElementById("metricbox");
    var metr = document.getElementById("customarybox");

    metr.style.display = "none";

    cust.style.opacity = 0.0;
    metr.style.opacity = 0.0;

    window.setTimeout(function() {
        cust.style.display = "none";
        metr.style.display = "block";

        window.setTimeout(function() {
            metr.style.opacity = 1.0;
        }, 100);
    }, 500);
}