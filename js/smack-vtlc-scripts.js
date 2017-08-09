function alertMsgWpTiger(msg) {
    alert(msg);
}

function enableTestDatabaseCredentials() {
    var str = document.getElementById("dbpass").value;
    if (str != '') {
        document.getElementById("Test-Database-Credentials").disabled = false;
    }
    else {
        document.getElementById("Test-Database-Credentials").disabled = true;
    }
}

function enableTestVtigerCredentials() {
    var str = document.getElementById("smack_host_access_key").value;
    if (str != '') {
        document.getElementById("Test-Vtiger-Credentials").disabled = false;
    }
    else {
        document.getElementById("Test-Vtiger-Credentials").disabled = true;
    }
}

function deactivate_wp_tig() {

        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'SmackWPTigerDeActivate',
            },
            success: function (data) {
                if(data == 'success'){
                    swal({
                        title: 'success',
                        text: "Wp Zoho CRM Deactivated and old files has been removed",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Go to Leads Builder Configuration",
                        closeOnConfirm: false
                    },
                    function(){
                        window.open('admin.php?page=wp-leads-builder-any-crm', '_self');
                    });                }
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
}

function activate_lb() {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'ActivateLeadsBuilder',
        },
        success: function (data) {
            if(data == 'success'){
                swal({
                    title: 'success',
                    text: "Leads Builder For Any CRM has been Activated.",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    closeOnConfirm: false
                },
                function(){
                    window.open('admin.php?page=wp-leads-builder-any-crm', '_self');
                });                }
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}

function migrate_to_lb() {
    document.getElementById('wp_tiger_error').style.display = 'none';
                    
    jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'SmackWPTigerToLb',
            },
            success: function (data) {
                // alert(data);
                if(data == 1){
                    swal({
                        title: 'success',
                        text: "Leads Builder For Any CRM Installed successfully.",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Activate Now",
                        closeOnConfirm: false
                    },
                    function(){
                        jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            'action': 'ActivateLeadsBuilder',
                        },
                        success: function (data) {
                            if(data == 'success'){
                                swal({
                                    title: 'success',
                                    text: "Leads Builder For Any CRM has been Activated.",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Ok",
                                    closeOnConfirm: false
                                },
                                function(){
                                    window.open('admin.php?page=wp-zoho-crm', '_self');
                                });                }
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });

                  });                
                }
                if (data == 'fail' ) {
                    document.getElementById('wp_tiger_error').style.display = 'block';
                    //alert(data);
                    swal({
                                    title: 'warning',
                                    text: 'Could not create directory! Please provide write permission to plugin directory or <a href="https://wordpress.org/plugins/wp-leads-builder-any-crm/" target="blank" style="text-decoration: none;"> download </a> and upload the plugin to install',
                                    type: "warning",
                                    html:true,
                                    showCancelButton: false,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Ok",
                                    closeOnConfirm: false
                                });
                   // swal('warning', 'Could not create directory! Please provide write permission to plugin directory', 'warning');
                }
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
}

function smack_validate_Fields() {
    var no_of_fields = jQuery('#no_of_vt_fields').val();
    for (var i = 0; i < no_of_fields; i++) {
        if (jQuery('#field_type' + i).val() == 'M'
            && !jQuery('#smack_vtlc_field' + i).is(':checked')) {
            alert(jQuery('#field_label' + i).val() + ' is mandatory');
            try {
                jQuery('#smack_vtlc_field' + i).focus();
            } catch (e) {
            }
            return false;
        }
    }
    return true;
}

function captureAlreadyRegisteredUsersWpTiger() {
    document.getElementById('please-upgrade').style.fontSize = "14px";
    document.getElementById('please-upgrade').style.fontFamily = "Sans Serif";
    document.getElementById('please-upgrade').style.color = "red";
    document.getElementById('please-upgrade').innerHTML = "Please Upgrade to WP-Tiger-Pro for Sync feature";
}

function testVtigerCredentials(siteurl) {
     //  document.getElementById("vtiger_process").style.display=" ";
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'SmackWPTigertestVtigerAccess',
            'url': jQuery("#url").val(),
            'smack_host_username': jQuery("#smack_host_username").val(),
            'smack_host_access_key': jQuery("#smack_host_access_key").val(),
            'check': "checkVtigerWebservice",
        },
        success: function (data) {
            if (data.indexOf("success") != -1) {
                document.getElementById('smack-vtiger-test-results').style.fontWeight = "bold";
             //   document.getElementById('vtiger_process').style.display = "none";
                document.getElementById('smack-vtiger-test-results').style.color = "green";
                document.getElementById('smack-vtiger-test-results').innerHTML = "Vtiger connected successfully";
            } else {
                document.getElementById('smack-vtiger-test-results').style.fontWeight = "bold";
               // document.getElementById('vtiger_process').style.display = "none";
                //document.getElementById('smack-vtiger-test-results').style.color = "green";
                document.getElementById('smack-vtiger-test-results').style.color = "red";
                document.getElementById('smack-vtiger-test-results').innerHTML = 'Vtiger Credentials are wrong.<p id="vtiger_process" style="display:none">Processing</p>';
            }
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}

function testDatabaseCredentials(siteurl) {
       //document.getElementById("database_process").style.display=" ";
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'SmackWPTigertestAccess',
            'hostname': jQuery("#hostname").val(),
            'dbuser': jQuery("#dbuser").val(),
            'dbpass': jQuery("#dbpass").val(),
            'dbname': jQuery("#dbname").val(),
            'check': "checkdatabase",
        },
        success: function (data) {
            if (data.indexOf("Success") != -1) {
                document.getElementById('smack-database-test-results').style.fontWeight = "bold";
         //       document.getElementById('database_process').style.display = "none";
                document.getElementById('smack-vtiger-test-results').style.color = "green";
                document.getElementById('smack-database-test-results').style.color = "green";
                document.getElementById('smack-database-test-results').innerHTML = "Database connected successfully";
            } else if (data == "1") {
                document.getElementById('smack-database-test-results').style.fontWeight = "bold";
           //     document.getElementById('database_process').style.display = "none";
                document.getElementById('smack-database-test-results').style.color = "red";
                document.getElementById('smack-database-test-results').innerHTML = "Not a VTiger database";
            } else {
                document.getElementById('smack-database-test-results').style.fontWeight = "bold";
             //   document.getElementById('database_process').style.display = "none";
                document.getElementById('smack-database-test-results').style.color = "red";
                document.getElementById('smack-database-test-results').innerHTML = "Database Credentials are wrong";
            }
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}

function upgradetopro() {
    window.setTimeout("showmessage()", 100);
    window.setTimeout("hidemessage()", 5000);
}

function move(val) {
    window.setTimeout("showmessage()", 100);
    window.setTimeout("hidemessage()", 5000);
}

function showmessage() {
    document.getElementById('upgradetopro').style.display = "";
}

function hidemessage() {
    document.getElementById('upgradetopro').style.display = "none";
    document.getElementById('skipduplicate').checked = false;
    document.getElementById('generateshortcode').checked = false;
}

function select_allfields(formid, module) {
    var i;
    var data = "";
    var form = document.getElementById(formid);
    var chkall = form.elements['selectall'];
    var chkBx_count = form.elements['no_of_vt_fields'].value;
    if (chkall.checked == true) {
        for (i = 0; i < chkBx_count; i++) {
            if (document.getElementById('smack_vtlc_field' + i).disabled == false)
                document.getElementById('smack_vtlc_field' + i).checked = true;
        }
    } else {
        for (i = 0; i < chkBx_count; i++) {
            if (document.getElementById('smack_vtlc_field' + i).disabled == false)
                document.getElementById('smack_vtlc_field' + i).checked = false;
        }
    }
}

function saveSettings() {
    window.setTimeout("showSuccessMessage()", 100);
    window.setTimeout("hideSuccessMessage()", 10000);
}

function showSuccessMessage() {
    document.getElementById('message-box').style.display = '';
}

function hideSuccessMessage() {
    document.getElementById('message-box').style.display = 'none';
}

