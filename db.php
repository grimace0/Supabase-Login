<?php

$dbHost = 'aws-0-ap-southeast-1.pooler.supabase.com';
$dbUser = 'postgres.xnovgtceysyapbyyfrjc';
$dbPassword = 'Progamer2005aw';



$conn = pg_connect("host=$dbHost port=5432 dbname=postgres user=$dbUser password=$dbPassword sslmode=require");

if (!$conn) {
    exit('Database connection failed.');
}



?>