<?php

return array(
	'/'                                           => 'main/index',

	'/<_c:qiwi>/<_a:bill|success|fail|callback>/' => '<_c>/<_a>',

	'/admin/'                                     => 'admin/index',
	'/sign-in/'                                   => 'admin/signIn',
	'/sign-out/'                                  => 'admin/signOut',
	'/admin/money-reports/'                       => 'admin/moneyReports',
	'/admin/money-reports/add/'                   => 'admin/moneyReportsAdd',
	'/admin/money-reports/<reportId:\d+>/edit/'   => 'admin/moneyReportsEdit',
);
