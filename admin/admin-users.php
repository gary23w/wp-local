<?php
//check if user has super cookie
if(isset($_COOKIE['gary-login'])) {

}
function make_pass_ast($count) {
    for ($i = 0; $i < $count; $i++) {
        $pass .= "*";
    }
    return $pass;
}
?>
<style>
table,
th,
td {
    border: 1px solid #ddd;
    padding: 8px;
    color: #333;
}

table {
    /* center the table */
    margin-left: auto;
    margin-right: auto;
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 70%;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tr:hover {
    background-color: #ddd;
}

table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #dcdcde;
    color: white;
}

table {
    margin-bottom: 20px;
}

input[type=text] {
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

.user_add {
    width: 40%;
    /* center the div */
    margin-left: auto !important;
    margin-right: auto !important;
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
    margin: 40px;
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
                        <h2 class="hndle"><span><?php echo $err ?></span>
                        </h2>
                        <div class="users">
                            <div class="user_list">
                                <?php
                                    //open users.json file and parse it
                                    $file = file_get_contents(plugin_dir_path( __FILE__ ) . 'users.json');
                                    $user_json = json_decode($file, true);  
                                    //parse the json_decoded file
                                    $users = $user_json['users'];
                                    ?>
                                <table>

                                    <tr>
                                        <th>
                                            ID
                                        </th>
                                        <th>
                                            Name:
                                        </th>
                                        <th>
                                            Username:
                                        </th>
                                        <th>
                                            Password:
                                        </th>

                                        <tbody>
                                            <?php
                                    foreach ($users as $key => $user) {
                                     echo '<tr>';
                                     echo '<td>' . $user['id'] . '</td>';
                                     echo "<td>" . $user['name'] . "</td>"; 
                                     echo "<td>" . $user['username'] . "</td>";
                                     echo  "<td>" .  make_pass_ast(strlen($user['password'])) . "</td>";
                                     echo "</tr>";
                                    }
                                ?>
                                        </tbody>
                                </table>
                            </div>
                            <div class="user_add">

                                <input type="text" name="person_name" id="person_name" placeholder="Name" />
                                <input type="text" name="user_name" id="user_name" placeholder="Username" />
                                <input type="text" name="user_pass" id="user_pass" placeholder="Password" />
                                <input type="submit" name="user_add" id="user_add" value="Add" />

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>