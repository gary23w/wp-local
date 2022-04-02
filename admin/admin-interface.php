<style>
.pass-notify {
    font-size: 0.4rem;
    color: black;
    font-family: Helvetica, Arial, sans-serif;
}

label {
    font-family: sans-serif;
    color: rgba(61, 67, 79, 0.75);
    width: 130px;
    float: left;

}

input[type=text],
input[type=number],
input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 100%;
    background-color: grey !important;
    color: #dcdcde;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: white;
}

.add_remove_mail_user {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-content: stretch;
    justify-content: space-around;
    align-items: baseline;
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

                                        <input class="regular-text code" name="_pass" id="_pass" type="password"
                                            value="<?php echo $_pass; ?>" />

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
                                    <td><label>Mail List:</label></td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <div class="mail_list" style="margin-top: 40px;">

                                            <ul id="list-mail">
                                                <?php 
                                                    $mail = explode(",", $email_list); 
                                                    if($mail[0] != '') {
                                                               foreach ($mail as $m) {
                                                        echo "<li class='admin-mail-li' id='$m'><div class='mail-div'><span class='mail-span'>".$m."</span><button class='del-btn' onclick='delthis()'>X</button></div></li>";
                                                    }
                                                    }
                                                    ?>
                                            </ul>
                                            <input type="text" id="candidate" style="width: 100%;"
                                                placeholder="user@mail.com" />
                                            <div class="add_remove_mail_user">
                                                <input class="button-primary" type="submit" onclick="addItem()"
                                                    class="addMail" value="Add" />

                                                <input class="button-primary" type="submit" onclick="removeItem()"
                                                    class="removeMail" value="Remove" />

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <div style="margin-top: 25px;" align='right' ;>
                                            <input class="button-primary" type="submit" name="article_form_submit"
                                                value="Update" onclick="save_creds()" />
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <br />
                        </div>