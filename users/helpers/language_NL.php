<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
%m1% - Dymamic markers which are replaced at run time by the relevant index.
*/

$lang = array();

//Account
$lang = array_merge($lang,array(
	"ACCOUNT_USER_ADDED" 			=> "New user created successfully!",
	"ACCOUNT_USER_FAILED"		    => "Creation of new user failed",
	"ACCOUNT_USER_PERM_ADDED"		=> "Permissions added  for new user!",
	"ACCOUNT_REPORT_ADDED" 			=> "New report created!",
	"ACCOUNT_SPECIFY_USERNAME" 		=> "Please enter your username",
	"ACCOUNT_SPECIFY_PASSWORD" 		=> "Please enter your password",
	"ACCOUNT_SPECIFY_EMAIL"			=> "Please enter your email address",
	"ACCOUNT_INVALID_EMAIL"			=> "Invalid email address",
	"ACCOUNT_USER_OR_EMAIL_INVALID"	=> "Username or email address is invalid",
	"ACCOUNT_USER_OR_PASS_INVALID"	=> "Username or password is invalid",
	"ACCOUNT_USER_OR_PASS_INVALID2"	=> "We have upgraded our technology to better protect your account.<a href='forgot-password.php'> Please click here to update your password to our new system.</a> We are sorry for the inconvenience.",
	"ACCOUNT_ALREADY_ACTIVE"		=> "Your account is already activated",
	"ACCOUNT_INACTIVE"				=> "Your account is in-active. Check your emails / spam folder for account activation instructions",
	"ACCOUNT_USER_CHAR_LIMIT"		=> "Your username must be between %m1% and %m2% characters in length",
	"ACCOUNT_DISPLAY_CHAR_LIMIT"	=> "Your display name must be between %m1% and %m2% characters in length",
	"ACCOUNT_PASS_CHAR_LIMIT"		=> "Your password must be between %m1% and %m2% characters in length",
	"ACCOUNT_TITLE_CHAR_LIMIT"		=> "Titles must be between %m1% and %m2% characters in length",
	"ACCOUNT_PASS_MISMATCH"			=> "Your password and confirmation password must match",
	"ACCOUNT_DISPLAY_INVALID_CHARACTERS"	=> "Display name can only include alpha-numeric characters",
	"ACCOUNT_USERNAME_IN_USE"		=> "Username %m1% is already in use",
	"ACCOUNT_DISPLAYNAME_IN_USE"	=> "Display name %m1% is already in use",
	"ACCOUNT_EMAIL_IN_USE"			=> "Email %m1% is already in use",
	"ACCOUNT_LINK_ALREADY_SENT"		=> "An activation email has already been sent to this email address in the last %m1% hour(s)",
	"ACCOUNT_NEW_ACTIVATION_SENT"	=> "We have emailed you a new activation link, please check your email",
	"ACCOUNT_SPECIFY_NEW_PASSWORD"	=> "Please enter your new password",
	"ACCOUNT_SPECIFY_CONFIRM_PASSWORD"	=> "Please confirm your new password",
	"ACCOUNT_NEW_PASSWORD_LENGTH"	=> "New password must be between %m1% and %m2% characters in length",
	"ACCOUNT_PASSWORD_INVALID"		=> "Current password doesn't match the one we have on record",
	"ACCOUNT_DETAILS_UPDATED"		=> "Account details updated",
	"ACCOUNT_ACTIVATION_MESSAGE"		=> "You will need to activate your account before you can login. Please follow the link below to activate your account. \n\n
	%m1%activate-account.php?token=%m2%",
	"ACCOUNT_ACTIVATION_COMPLETE"		=> "You have successfully activated your account. You can now login <a href=\"login.php\">here</a>.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE1"	=> "You have successfully registered. You can now login <a href=\"login.php\">here</a>.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"	=> "You have successfully registered. You will soon receive an activation email.
	You must activate your account before logging in.",
	"ACCOUNT_PASSWORD_NOTHING_TO_UPDATE"	=> "You cannot update with the same password",
	"ACCOUNT_PASSWORD_UPDATED"		=> "Account password updated",
	"ACCOUNT_EMAIL_UPDATED"			=> "Account email updated",
	"ACCOUNT_TOKEN_NOT_FOUND"		=> "Token does not exist / Account is already activated",
	"ACCOUNT_USER_INVALID_CHARACTERS"	=> "Username can only include alpha-numeric characters",
	"ACCOUNT_DELETIONS_SUCCESSFUL"		=> "You have successfully deleted %m1% users",
	"ACCOUNT_MANUALLY_ACTIVATED"		=> "%m1%'s account has been manually activated",
	"ACCOUNT_DISPLAYNAME_UPDATED"		=> "Displayname changed to %m1%",
	"ACCOUNT_TITLE_UPDATED"			=> "%m1%'s title changed to %m2%",
	"ACCOUNT_PERMISSION_ADDED"		=> "Added access to %m1% permission levels",
	"ACCOUNT_PERMISSION_REMOVED"		=> "Removed access from %m1% permission levels",
	"ACCOUNT_INVALID_USERNAME"		=> "Invalid username",
	"CAPTCHA_ERROR"		=> "You failed the Captcha Test, Robot!",
	));

//Configuration
$lang = array_merge($lang,array(
	"CONFIG_NAME_CHAR_LIMIT"		=> "Site name must be between %m1% and %m2% characters in length",
	"CONFIG_URL_CHAR_LIMIT"			=> "Site name must be between %m1% and %m2% characters in length",
	"CONFIG_EMAIL_CHAR_LIMIT"		=> "Site name must be between %m1% and %m2% characters in length",
	"CONFIG_ACTIVATION_TRUE_FALSE"	=> "Email activation must be either `true` or `false`",
	"CONFIG_ACTIVATION_RESEND_RANGE"=> "Activation Threshold must be between %m1% and %m2% hours",
	"CONFIG_LANGUAGE_CHAR_LIMIT"	=> "Language path must be between %m1% and %m2% characters in length",
	"CONFIG_LANGUAGE_INVALID"		=> "There is no file for the language key `%m1%`",
	"CONFIG_TEMPLATE_CHAR_LIMIT"	=> "Template path must be between %m1% and %m2% characters in length",
	"CONFIG_TEMPLATE_INVALID"		=> "There is no file for the template key `%m1%`",
	"CONFIG_EMAIL_INVALID"			=> "The email you have entered is not valid",
	"CONFIG_INVALID_URL_END"		=> "Please include the ending / in your site's URL",
	"CONFIG_UPDATE_SUCCESSFUL"		=> "Your site's configuration has been updated. You may need to load a new page for all the settings to take effect",
	));

//Forgot Password
$lang = array_merge($lang,array(
	"FORGOTPASS_INVALID_TOKEN"		=> "Your activation token is not valid",
	"FORGOTPASS_NEW_PASS_EMAIL"		=> "We have emailed you a new password",
	"FORGOTPASS_REQUEST_CANNED"		=> "Lost password request cancelled",
	"FORGOTPASS_REQUEST_EXISTS"		=> "There is already a outstanding lost password request on this account",
	"FORGOTPASS_REQUEST_SUCCESS"	=> "We have emailed you instructions on how to regain access to your account",
	));
//Navigation Menu
$lang = array_merge($lang,array(
	"MENU_NAV_MAP"		            => "Kaart",
	"MENU_NAV_PLANNING"		        => "Planning",
	"MENU_NAV_PLAN_ETA"		        => "ETA Routing",
	"MENU_NAV_PLAN_STATUS"	        => "Status",
	"MENU_NAV_PLAN_DRIVERS"	        => "Drivers",
	"MENU_NAV_PLAN_CARGO"	        => "Cargo",
	"MENU_NAV_PLAN_CONTACTS"        => "Delivery Contacts",
	"MENU_NAV_RESOURCES"            => "Bronnen",
	"MENU_NAV_RES_VEHICLES"         => "Voertuigen",
	"MENU_NAV_RES_TRAILERS"         => "Trailers",
	"MENU_NAV_RES_DRIVERS"          => "Chauffeurs",
	"MENU_NAV_RES_GROUPS"           => "Voertuig Groepen",
	"MENU_NAV_RES_DEALERS"          => "Werkplaatsen",

	));

//Mail
$lang = array_merge($lang,array(
	"MAIL_ERROR"				=> "Fatal error attempting mail, contact your server administrator",
	"MAIL_TEMPLATE_BUILD_ERROR"		=> "Error building email template",
	"MAIL_TEMPLATE_DIRECTORY_ERROR"		=> "Unable to open mail-templates directory. Perhaps try setting the mail directory to %m1%",
	"MAIL_TEMPLATE_FILE_EMPTY"		=> "Template file is empty... nothing to send",
	));

//Miscellaneous
$lang = array_merge($lang,array(
	"CAPTCHA_FAIL"			=> "Failed security question",
	"CONFIRM"				=> "Confirm",
	"DENY"					=> "Deny",
	"SUCCESS"				=> "Success",
	"ERROR"					=> "Error",
	"NOTHING_TO_UPDATE"		=> "Nothing to update",
	"SQL_ERROR"				=> "Fatal SQL error",
	"FEATURE_DISABLED"		=> "This feature is currently disabled",
	"PAGE_PRIVATE_TOGGLED"	=> "This page is now %m1%",
	"PAGE_ACCESS_REMOVED"	=> "Page access removed for %m1% permission level(s)",
	"PAGE_ACCESS_ADDED"		=> "Page access added for %m1% permission level(s)",
	));


$lang = array_merge($lang,array(
	"MESSAGE_ARCHIVE_SUCCESSFUL"        => "You have successfully archived %m1% threads",
	"MESSAGE_UNARCHIVE_SUCCESSFUL"      => "You have successfully unarchived %m1% threads",
	"MESSAGE_DELETE_SUCCESSFUL"         => "You have successfully deleted %m1% threads",
	"USER_MESSAGE_EXEMPT"      			=> "User is %m1% exempted from messages.",
	));
// group management
$lang = array_merge($lang,array(
	"GROUP_UPDATED"                     => "Group details have been updated",
	));

//Permissions
$lang = array_merge($lang,array(
	"PERMISSION_CHAR_LIMIT"			=> "Permission names must be between %m1% and %m2% characters in length",
	"PERMISSION_NAME_IN_USE"		=> "Permission name %m1% is already in use",
	"PERMISSION_DELETIONS_SUCCESSFUL"=>"Successfully deleted %m1% permission level(s)",
	"PERMISSION_CREATION_SUCCESSFUL"=> "Successfully created the permission level `%m1%`",
	"PERMISSION_NAME_UPDATE"		=> "Permission level name changed to `%m1%`",
	"PERMISSION_REMOVE_PAGES"		=> "Successfully removed access to %m1% page(s)",
	"PERMISSION_ADD_PAGES"			=> "Successfully added access to %m1% page(s)",
	"PERMISSION_REMOVE_USERS"		=> "Successfully removed %m1% user(s)",
	"PERMISSION_ADD_USERS"			=> "Successfully added %m1% user(s)",
	"CANNOT_DELETE_NEWUSERS"		=> "You cannot delete the default 'new user' group",
	"CANNOT_DELETE_ADMIN"			=> "You cannot delete the default 'admin' group",
	));

// Signin
$lang = array_merge($lang,array(
	"SIGNIN_FAIL"			=> "** FAILED LOGIN **",
	"SIGNIN_TITLE"			=> "Please Log In",
	"SIGNIN_TEXT"			=> "Log In",
	"SIGNOUT_TEXT"			=> "Log Out",
	"SIGNIN_BUTTONTEXT"		=> "Login",
	"SIGNIN_AUDITTEXT"		=> "Logged In",
	"SIGNOUT_AUDITTEXT"		=> "Logged Out",
	"SIGNIN_NOTIFICATION"   => "Notification",
	"LOGIN_LBL_USERNAME"    => "Name",
	"LOGIN_FLD_USERNAME"    => "your username of e-mail address",
	"LOGIN_LBL_PSSWORD"     => "password",
	"LOGIN_FLD_PSSWORD"     => "your personal password",
	"LOGIN_FLD_FORGOTTEN"   => "forgot password",
	"LOGIN_LBL_Recaptcha"   => "Please check the box below to continue",
	));
//Navigation
$lang = array_merge($lang,array(
	"NAVTOP_HELPTEXT"		=> "Help",
	));
//Dashboard
$lang = array_merge($lang,array(
	"DASH_FleetStatus"		=> "FleetStatus",
	"DASH_UsageStats"		=> "Fleet Usage Statistics",
	"DASH_FleetHealth"		=> "Fleet Diagnostics",
	"DASH_Welcome"  		=> "Hello",
	"DASH_DriverStatus"		=> "DriverStatus",
	"DASH_CountOf"	    	=> "of last ",
	"DASH_Days" 	    	=> "days",
	"DASH_Today" 	    	=> "of Today",
	"DASH_MessageNone"      => "no",
	"DASH_Message" 	        => "You have",
	"DASH_MessageCount"     => "new messages",
	"DASH_Reports"          => "Scheduled reports",
	"DASH_API_status"       => "API interface status",
	"DASH_RED_Active"       => "active",
	"DASH_RED_Total"        => "total today",

	));
//Admin_Reporting
$lang = array_merge($lang,array(
	"ADMREP_Title"	        => "Report Settings",
	"ADMREP_Report_Save"	=> "Save Report settings",
	"ADMREP_SW_Reporting"	=> "Reporting",
	"ADMREP_SW_Report_info" => "To enable the creation and scheduling of reports",
	"ADMREP_SW_Adaptive"   	=> "Adaptive dates",
	"ADMREP_SW_Adapt_info" 	=> "Once set all reports will use same selected dates for reporting, during your session<br>",
	"ADMREP_TABTypes"	   	=> "Types",
	"ADMREP_TABPlanning"   	=> "Planning",
	"ADMREP_TABQueries"   	=> "Report Queries",
	"ADMREP_Types_info"	    => "Creation of different reports, edit existing or copy to create a personal report",
	"ADMREP_Planning_info"	=> " ",
	"ADMREP_Queries_info"	=> " ",
    "ADMREP_REP_header"     => "Report header text",
    "ADMREP_REP_logo"       => "Report header logo",
    "ADMREP_TABHelp"        => "Help",

	));

	$lang = array_merge($lang,array(
    "MENU_Rep_SM_DEALER"    => "dealer",
    "MENU_Rep_SM_TRIPS"     => "trips",
    "MENU_Rep_SM_USAGE"     => "usage",
    "MENU_Rep_SM_MISCELLANEOUS"     => "miscellaneous",
	"MENU_MAP"	            => "kaart",
	"MENU_RESOURCES"	    => "bronnen",
	"MENU_REPORTS"	        => "rapporten",
	"MENU_SETTINGS"         => "tools",
	"MENU_PLANNING"         => "planning",
	"MENU_PLAN_ETAROUTING"  => "ETA routing",
	"MENU_PLAN_Status"      => "status",
	"MENU_PLAN_Drivers"     => "rijders",
	"MENU_PLAN_Cargo"       => "cargo",
	"MENU_PLAN_Del_Contacts"=> "delivery contacts",
	"MENU_RES_VEHICLES"	    => "voertuigen",
	"MENU_RES_DRIVERS"      => "rijders",
	"MENU_RES_TRAILERS"	    => "trailers",
	"MENU_RES_GROUPS"  	    => "groepen",
	"MENU_RES_DEALERS"	    => "werkplaatsen",
	"MENU_Rep_Trips"   	    => "ritten",
	"MENU_Rep_TripAnalysis" => "rit analyse",
	"MENU_Rep_TripAnalysisAdd"   => "",
	"MENU_Rep_PDC"   	    => "voertuig controles",
	"MENU_Rep_AHistory"   	=> "voertuig waarschuwingen historie",
	"MENU_Rep_AAnalysis"    => "voertuig waarschuwing analyse",
	"MENU_Rep_Geofencing"   => "geofencing triggers report",
	"MENU_Rep_Planner" 	    => "report scheduler",
	"MENU_Rep_PlannerAdd" 	=> "Maintain all reports you created or want to create",
	"MENU_Rep_FleetUtil"    => "fleet utilisation",
	"MENU_Rep_VehicleAct"   => "vehicle activity",
	"MENU_Rep_DriverAct"    => "driver activity",
	"MENU_Rep_DriveTime"    => "driver drivetime report",
	"MENU_Rep_AdvFuel"      => "advanced fuel usage",
	"MENU_Rep_CC_Fuelcard"  => "cross-check fuel / card",
	"MENU_Rep_Maintenance"  => "maintenance",
	"MENU_Rep_Damages"      => "damage",
	"MENU_Rep_WS_history"   => "workshop history",
	"MENU_Rep_DelayedVehicles"   => "delayed vehicles heatmap",
	"MENU_Rep_H_trip"       => "trip",
	"MENU_Rep_H_Usage"      => "usage",
	"MENU_Rep_H_Workshop"   => "workshop",
	"MENU_Rep_H_misc"       => "miscellaneous",
	"MENU_Tools_Geofences"  => "geofences",
	"MENU_Tools_Monitoring" => "monitoring",
	"MENU_Tools_APIColl"    => "API - collector",
	"MENU_Tools_Settings"   => "settings",
	"MENU_Tools_FleetAdmin" => "fleetadmin",
	"MENU_Tools_CONNECTDEMO"=> "connect demo",
	"MENU_Tools_Terms"      => "terms & condition",
	"MENU_Tools_About"      => "about",
	"MENU_PERSON_MESSAGES"  => "messages",
	"MENU_Person_Notifications" => "notifcations",
	"MENU_PERSON_MYREPORT" => "reports",
	"MENU_PERSON_MYSETTINGS" => "profile",
	"MENU_PERSON_LOGOUT"    => "logout",
	));

$lang = array_merge($lang,array(
	"ADMDASH_USERS"	        => "users",
	"ADMDASH_ASSETSGROUPS"  => "vehicle groups",
	"ADMDASH_GROUPRelation" => "group assigment",
	"ADMDASH_PERMISSIONS"	=> "roles and permissions",
	"ADMDASH_PAGES"	        => "pages",
	"ADMDASH_EMAIL"	        => "e-mail settings",
	"ADMDASHMENU_Dashboard" => "dashboard",
    "ADMDASHMENU_Site"      => "application",
    "ADMDASHMENU_Style"     => "styling",
    "ADMDASHMENU_Users"     => "user settings",
    "ADMDASHMENU_Notifications" => "notifications",
    "ADMDASHMENU_Features"  => "Features",
    "ADMDASHMENU_API"       => "API data connectivity",
    "ADMDASHMENU_Report"    => "Report settings",
    "ADMDASHMENU_PDC"       => "PreDeparture Checks",
    "ADMDASHMENU_FM"        => "Fleet Management",
    "ADMDASHMENU_Import"    => "Data import",
	));
$lang = array_merge($lang,array(
	"VEHEDIT_NOTRAILER"     => "no trailer linked",
	"VEHEDIT_SEVEREDAMAGE"  => "Severe damage registered and vehicle is prohibited for operation",
	"VEHEDIT_MAINTENANCE"   => "Service distance is below threshold",
	"VEHEDIT_FUELLEVEL"     => "Fuel-level is below threshold",
	"VEHEDIT_ADBLUELEVEL"   => "AdBlue-level is below threshold",
	"VEHEDIT_SEVEREDAMAGE"  => "Severe damage registered and vehicle is prohibited for operation",
	"VEHEDIT_UPDATE"        => "Update / Save",
	"VEHEDIT_CANCEL"        => "return",
	"VEHEDIT_HISTORY"       => "history report",

	));

?>
