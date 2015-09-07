<?php
    
    set_time_limit(0);
    
    error_log('  Facebook Research Country (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_countries.php > '.__DIR__.'/logs/facebook_countries.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_countries_ages.php > '.__DIR__.'/logs/facebook_countries_ages.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_countries_comportamientos.php > '.__DIR__.'/logs/facebook_countries_comportamientos.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_countries_interests.php > '.__DIR__.'/logs/facebook_countries_interests.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_countries_languages.php > '.__DIR__.'/logs/facebook_countries_languages.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_countries_relationships.php > '.__DIR__.'/logs/facebook_countries_relationships.log 2>&1 &');
    
    error_log('  Facebook Research Country (f): '.date('d m Y H:i:s'));
    
    error_log('  Facebook Research City (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_cities.php > '.__DIR__.'/logs/facebook_cities.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_cities_ages.php > '.__DIR__.'/logs/facebook_cities_ages.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_cities_comportamientos.php > '.__DIR__.'/logs/facebook_cities_comportamientos.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_cities_interests.php > '.__DIR__.'/logs/facebook_cities_interests.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_cities_relationships.php > '.__DIR__.'/logs/facebook_cities_relationships.log 2>&1 &');
    
    error_log('  Facebook Research City (f): '.date('d m Y H:i:s'));
    
    error_log('  Facebook Research Region (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_regions.php > '.__DIR__.'/logs/facebook_regions.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_regions_ages.php > '.__DIR__.'/logs/facebook_regions_ages.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_regions_comportamientos.php > '.__DIR__.'/logs/facebook_regions_comportamientos.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_regions_interests.php > '.__DIR__.'/logs/facebook_regions_interests.log 2>&1 &');
    
    exec('php-cli '.__DIR__.'/facebook_regions_relationships.php > '.__DIR__.'/logs/facebook_regions_relationships.log 2>&1 &');
    
    error_log('  Facebook Research Region (f): '.date('d m Y H:i:s'));
    
    error_log('  Facebook Research Continent (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_continents.php > '.__DIR__.'/logs/facebook_continents.log 2>&1 &');
    
    error_log('  Facebook Research Continent (f): '.date('d m Y H:i:s'));
    
    die();