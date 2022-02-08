<h1 class="ortho_admin_header">&nbsp;</h1>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">
						<h2 class="hndle"><span><?php echo $err ?></span>
						</h2>
                        <div align="center">
                        <form method ="post" action="">
                                <input class="button-primary" type="submit" name="article_form_test" value="Test System." style="margin: 20px;" />
                                <input class="button-primary" type="submit" name="article_form_clear_logs" value="Clear logs" style="margin: 20px;"  />
                        </form>
                        </div>
                        <p>
                        <?php
                            $myfile = fopen(GARY_PLUGIN_URI . "logs/mail.log", "r") or die("Unable to open file!");
                            $contents = fread($myfile,filesize(GARY_PLUGIN_URI . "logs/mail.log"));
                            $lines = explode("\n", $contents); // this is your array of words
                            //var_dump($lines);
                            foreach($lines as $word) {
                                echo "<p>" . $word . "</p>";
                            }
                            fclose($myfile);
                        ?>
                        </p>						
					</div>
			</div>
        </div>
    </div>
</div>