/**
 * Created by scolton17 on 1/10/17.
 */

// var cur = 1;

/* function next() {
    if (cur >= 5)
        return;

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

            var h1 = n.getElementsByTagName("H1")[0];
            var mar = window.getComputedStyle(h1).marginTop;

            console.log(mar);

            n.style.position = "absolute";
            n.style.top = "500px";

            n.style.transition = "top 0.75s, opacity 0.5s";

            window.setTimeout(function () {

                n.style.top = "calc(" + pos + "px - " + mar + ")";
                n.style.opacity = 1.0;

                window.setTimeout(function() {
                    n.style.top = "auto";
                    n.style.bottom = "auto";
                    n.style.position = "static";

                    if (cur == 5) {
                        register();
                    }
                }, 750);

            }, 100);

        }, 300);
    }, 100);
} */

function switchMetric() {
    var cust = document.getElementById("customarybox");
    var metr = document.getElementById("metricbox");

    metr.style.display = "none";

    cust.style.opacity = 0.0;
    metr.style.opacity = 0.0;

    cust.style.display = "none";
    metr.style.display = "block";
    metr.style.opacity = 1.0;
}

function switchCustomary() {
    var cust = document.getElementById("metricbox");
    var metr = document.getElementById("customarybox");

    metr.style.display = "none";

    cust.style.opacity = 0.0;
    metr.style.opacity = 0.0;

    cust.style.display = "none";
    metr.style.display = "block";
    metr.style.opacity = 1.0;
}

function register() {
    var name = document.getElementById("name").value;
    var units = document.getElementById("customary").checked ? 2 : 1;
    var height, weight;

    if (units == 2) {
        var inches = 12.0 * parseFloat(document.getElementById("ft").value) + parseFloat(document.getElementById("in").value);
        height = inches * 2.4;

        weight = parseFloat(document.getElementById("lbs").value) * 0.453592;
    } else if (units == 1) {
        height = document.getElementById("cm").value;
        weight = document.getElementById("kg").value;
    }

    var birthday = "" + document.getElementById("y").value + "-" + document.getElementById("m").value + "-" + document.getElementById("d").value;
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    console.log(password);

    var data = new FormData();
    data.append("name", name);
    data.append("units", units);
    data.append("height", height);
    data.append("weight", weight);
    data.append("birthday", birthday);
    data.append("username", username);
    data.append("password", password);

    var x = new XMLHttpRequest();
    x.open("POST", "../assets/php/scripts/ajax/user_create.php", true);
    x.onreadystatechange = function() {
        if (x.status == 200 && x.readyState == 4) {
            console.log(x.responseText);
            var result = JSON.parse(x.responseText);
            if (result.result == "success") {
                window.location = "../";
            } else {
				$('#error-modal').modal('toggle');
                document.getElementById("action_error").innerHTML = result.message + "<br />Try refreshing the page.";
                document.body.style.transition = "background-color 0.5s";
                window.setTimeout(function() {
                    document.body.style.backgroundColor = "rgb(255, 79, 79)";
                }, 100);
            }
        }
    };
    x.send(data);
}

function login() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    var data = new FormData();
    data.append("username", username);
    data.append("password", password);

    var x = new XMLHttpRequest();
    x.open("POST", "../assets/php/scripts/ajax/user_login.php", true);
    x.onreadystatechange = function () {
        if (x.readyState == 4 && x.status == 200) {
            var res = JSON.parse(x.responseText);
            if (res.result == "success") {
                window.location = "../";
            } else {

            }
        }
    };
    x.send(data);
}

function logout() {
    var x = new XMLHttpRequest();
    x.open("GET", root + "/assets/php/scripts/ajax/user_logout.php");
    x.onreadystatechange = function() {
        if (x.readyState == 4 && x.status == 200) {
            location.reload();
        }
    };
    x.send();
}



function init() {
	$(function () {
		$('[data-toggle="popover"]').popover()
	});

	/*var els = document.getElementsByClassName("goal-form");

	for (var el in els) {
	    if (!els.hasOwnProperty(el))
	        continue;

	    el.addEventListener("submit", updateGoal);
    }*/

    $(".goal-form").submit(updateGoal);
    window.root = "/apcsp";
    document.getElementById("logout").addEventListener("click", logout);
}

function updateGoal() {
    var id = this.dataset["goalId"];
    var add = $(this).find("input.goal-input")[0].value;

    var data = new FormData();
    data.append("goal", id);
    data.append("amount", add);

    var x = new XMLHttpRequest();
    x.open("POST", "./assets/php/scripts/ajax/goal_add_progress.php");
    x.onreadystatechange = function() {
        if (x.readyState == 4 && x.status == 200) {
            var res = JSON.parse(x.responseText);

            if (res.result === "success") {
                location.reload();
            } else {
            }
        }
    };
    x.send(data);
}

function addGoal() {
    var id = document.getElementById("goal-types").value;
    var amt = document.getElementById("amount").value;

    var data = new FormData();
    data.append("type", id);
    data.append("amount", amt);

    var x = new XMLHttpRequest();
    x.open("POST", "../../assets/php/scripts/ajax/goal_add.php");
    x.onreadystatechange = function() {
        if (x.readyState == 4 && x.status == 200) {
            var res = JSON.parse(x.responseText);

            if (res.result === "success") {
                window.location = root;
            } else {
            }
        }
    };
    x.send(data);
}

window.addEventListener("load", init);