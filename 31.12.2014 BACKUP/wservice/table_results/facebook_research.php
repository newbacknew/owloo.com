<?php
    
    set_time_limit(0);
    
    error_log('  Facebook Research Country (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_countries.php');
    
    exec('php-cli '.__DIR__.'/facebook_countries_ages.php');
    
    exec('php-cli '.__DIR__.'/facebook_countries_comportamientos.php');
    
    exec('php-cli '.__DIR__.'/facebook_countries_interests.php');
    
    exec('php-cli '.__DIR__.'/facebook_countries_languages.php');
    
    exec('php-cli '.__DIR__.'/facebook_countries_relationships.php');
    
    error_log('  Facebook Research Country (f): '.date('d m Y H:i:s'));
    
    error_log('  Facebook Research City (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_cities.php');
    
    exec('php-cli '.__DIR__.'/facebook_cities_ages.php');
    
    exec('php-cli '.__DIR__.'/facebook_cities_comportamientos.php');
    
    exec('php-cli '.__DIR__.'/facebook_cities_interests.php');
    
    exec('php-cli '.__DIR__.'/facebook_cities_relationships.php');
    
    error_log('  Facebook Research City (f): '.date('d m Y H:i:s'));
    
    error_log('  Facebook Research Region (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_regions.php');
    
    exec('php-cli '.__DIR__.'/facebook_regions_ages.php');
    
    exec('php-cli '.__DIR__.'/facebook_regions_comportamientos.php');
    
    exec('php-cli '.__DIR__.'/facebook_regions_interests.php');
    
    exec('php-cli '.__DIR__.'/facebook_regions_relationships.php');
    
    error_log('  Facebook Research Region (f): '.date('d m Y H:i:s'));
    
    error_log('  Facebook Research Continent (i): '.date('d m Y H:i:s'));
    
    exec('php-cli '.__DIR__.'/facebook_continents.php');
    
    error_log('  Facebook Research Continent (f): '.date('d m Y H:i:s'));
    
    die();