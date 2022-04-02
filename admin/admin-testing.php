<script>
var fancy = document.getElementById("fancy");
fancy.scrollIntoView(false);
</script>
<style>
.box_holder {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-content: stretch;
    justify-content: space-evenly;
    align-items: baseline;
}

/* Add a black background color to the top navigation */
.topnav {
    background-color: #dcdcde;
    overflow: hidden;
}

/* Style the links inside the navigation bar */
.topnav input[type=submit] {
    float: left;
    color: rgba(61, 67, 79, 0.75);
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
    border: 0.5px solid #dcdcde;
    margin: 0px !important;
}

/* Change the color of links on hover */
.topnav input:hover {
    background-color: #ddd;
    color: black;
}

/* Add a color to the active/current link */
.topnav input.active {
    background-color: #04AA6D;
    color: white;
}
</style>
<h1 class="ortho_admin_header">&nbsp;</h1>
<div class="topnav">
    <form method="post" action="">
        <input class="button_logs" type="submit" name="article_form_test" value="Test System." style="margin: 20px;" />
        <input class="button_logs" type="submit" name="article_form_clear_logs" value="Clear logs"
            style="margin: 20px;" />
    </form>
</div>
<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">

                    <div class="box_holder">
                        <h2 class="hndle"><span><?php echo $err ?></span>
                        </h2>
                        <div class="fancy-logs" id="fancy">
                            <code>
                            <?php
                            $myfile = fopen(GARY_PLUGIN_URI . "logs/mail.log", "r") or die("Unable to open file!");
                            $contents = fread($myfile,filesize(GARY_PLUGIN_URI . "logs/mail.log"));
                            $lines = explode("\n", $contents);
                            foreach($lines as $word) {
                                if ($word != "") {
                                    echo "<p>---</p>";
                                    echo $word;
                                    echo "<p>---</p>";
                                }
                            }
                            fclose($myfile);
                        ?>
                        </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>