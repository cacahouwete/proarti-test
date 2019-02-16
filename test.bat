cd c:/xampp/mysql/bin
mysql --user=root --password=""  --local-infile proarti  -e "LOAD DATA LOCAL INFILE '%1'  INTO TABLE donation   FIELDS TERMINATED BY ';' LINES TERMINATED BY '\n' IGNORE 1 lines (first_name,last_name,amount,project_name,reward,reward_quantity)"; 

