<style>
.pass-notify {
    font-size: 0.4rem;
    color: black;
    font-family: Helvetica, Arial, sans-serif;
}
</style>
<h1 class="ortho_admin_header">&nbsp;</h1>
<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">

                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <!-- Toggle -->
                        <h2 class="hndle"><span><?php echo $err ?></span>
                        </h2>
                        <div class="inside">
                            <fieldset style="float: right; margin-right: 50px; margin-top: 20px;">
                                <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                                <div>
                                    <label title='g:i a'>
                                        <input type="checkbox" name="24-hours" value="" checked disabled />
                                        <span>24 hours</span>
                                    </label>
                                </div>
                            </fieldset>
                            <form method="post" action="">
                                <input type="hidden" name="article_form_submit" value="Y">
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">SMTP server:</label></td>
                                        <td><input name="destination_api" id="destination_api" type="text"
                                                value="<?php echo $destination_api; ?>" class="regular-text code" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">SMTP port(465/587):</label></td>
                                        <td><input name="destination_port" id="destination_port" type="number"
                                                value="<?php echo $destination_port; ?>" class="regular-text code" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">SMTP security(tls/ssl):</label></td>
                                        <td><input name="destination_secure" id="destination_secure" type="text"
                                                value="<?php echo $destination_secure; ?>" class="regular-text code" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">Username/Email:</label></td>
                                        <td><input name="_user" id="_user" type="text" value="<?php echo $_user; ?>"
                                                class="regular-text code" /></td>
                                    </tr>
                                    <tr valign="top">
                                        <td scope="row"><label for="tablecell">Password:</label></td>
                                        <td>
                                            <?php
									if($_pass):
									$c = strlen($_pass);
									?>
                                            <input class="regular-text code" name="_pass" id="_pass" type="password"
                                                placeholder="<?php
									for($i = 0; $i<$c; $i++) 
										echo "*";
									?>" />
                                            <?php
									else:
									?>
                                            <input class="regular-text code" name="_pass" id="_pass" type="password"
                                                value="" maxlength="1000" />
                                            <?php
									endif;
									?>
                                        </td>
                                    <tr>
                                        <td>
                                            <hr>
                                        </td>
                                        <td>
                                            <hr>
                                        </td>
                                    </tr>
                                    </tr>
                                    <tr>
                                        <td><label for="mail_list">Mail List:</label></td>
                                        <td>
                                            <div class="mail_list" style="margin-top: 40px;">
                                                <textarea id="email-list" name="email-list" cols="40"
                                                    rows="5"><?php echo $email_list; ?></textarea>
                                                <div style="margin-top: 25px;" align='right' ;>
                                                    <input class="button-primary" type="submit"
                                                        name="article_form_submit" value="Save" />
                                                </div>
                                                <!-- <input class="button-primary" type="submit" name="article_form_update_mail" value="Update Mail List" /> -->

                                            </div>
                                        </td>
                                </table>

                                <br />
                        </div>