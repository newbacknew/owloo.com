<?php
	$datos = file_get_contents('https://graph.facebook.com/act_105368146303815/reachestimate?endpoint=/act_105368146303815/reachestimate&accountId=105368146303815&locale=es_LA&targeting_spec={"genders":[],"age_max":65,"age_min":13,"broad_age":true,"regions":[],"countries":["US"],"cities":[],"zips":[],"radius":0,"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[0],"locales":[],"work_networks":[],"user_adclusters":[]}&method=get&access_token=AAACZBzNhafycBAOplKp045ZCbxYjdNsdZBe7AofnabYw9ZBj0vZA38obNSEacROw2jVResvYDBZC8JZBuxVpfFe0hfmr5IwGJ59KX9eyuLz9SF6tz5orzs5');
		
	print_r(json_decode ($datos, true));
		