<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 8:09 AM
 */
require_once("../assets/php/var.php");

if (userLoggedIn())
    header("Location: ../");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Fitness - Register</title>
        <link rel="stylesheet" href="../assets/css/fitness.css" />
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
        <script src="../assets/js/fitness.js"></script>
    </head>
    <body class="main">
        <a class="metro-button" id="login-button" href="javascript:void(0);">Log in</a>
        <section id="form" class="main">
            <div id="logo-container">
                <img src="../assets/img/main_logo.svg" alt="Fitness Logo" class="logo" id="register-logo" />
            </div>
            <div id="form-container">
                <div id="registration-form">
                    <form onsubmit="javascript:void(0);">
                        <div id="form-1" class="form-part">
                            <h1>Hello</h1>
                            <input placeholder="your name" id="name" type="text" autocomplete="off" autofocus onkeyup="if(event.keyCode == 13) next(); ">
                        </div>
                        <div id="form-2" class="form-part">
                            <h1>Height and Weight</h1>
                            <input type="radio" name="units" value="customary" id="customary" onchange="switchCustomary();" checked>
                            <label for="customary"><span><span></span></span>customary</label>
                            <input type="radio" name="units" value="metric" id="metric" onchange="switchMetric();">
                            <label for="metric"><span><span></span></span>metric</label>
                            <br />
                            <br />
                            <div id="customarybox">
                                <div class="input-group" id="feetcont">
                                    <input type="text" autocomplete="off" id="ft" onkeyup="if(event.keyCode == 13) next(); ">
                                    <span>ft.</span>
                                </div>
                                <div class="input-group" id="inchescont">
                                    <input type="text" autocomplete="off" id="in" onkeyup="if(event.keyCode == 13) next(); ">
                                    <span>in.</span>
                                </div>
                                <div class="input-group">
                                    <input type="text" autocomplete="off"  id="lbs" onkeyup="if(event.keyCode == 13) next(); ">
                                    <span>lbs.</span>
                                </div>
                            </div>
                            <div id="metricbox">
                                <div class="input-group" id="cmcont">
                                    <input type="text" autocomplete="off" id="cm" onkeyup="if(event.keyCode == 13) next(); ">
                                    <span>cm.</span>
                                </div>
                                <div class="input-group"id="kgcont">
                                    <input type="text" autocomplete="off" id="kg" onkeyup="if(event.keyCode == 13) next(); ">
                                    <span>kg.</span>
                                </div>
                            </div>
                        </div>
                        <div id="form-3" class="form-part">
                            <h1>Date of Birth</h1>
                            <select id="d">
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                            <select id="m">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <select id="y">
                                <?php
                                for ($i = 1900; $i <= date("Y"); $i++) {
                                    echo "<option value='$i'>$i</option>'";
                                }
                                ?>
                            </select>
                            <a id="birthday-next" href="javascript:next();">&#8595;</a>
                        </div>
                        <div id="form-4" class="form-part">
                            <h1>Login info</h1>
                            <input type="text" id="username" placeholder="username" onkeyup="if(event.keyCode == 13) next(); " autocomplete="off">
                            <br />
                            <br />
                            <input type="password" id="password" placeholder="password" onkeyup="if(event.keyCode == 13) next(); " autocomplete="off">
                        </div>
                        <div id="form-5" class="form-part">
                            <h1 id="action_header">Creating your account...</h1>
                            <span class="error" id="action_error"></span>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </body>
</html>
