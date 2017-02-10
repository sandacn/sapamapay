# FILE POLLER #
This script polls or check an upload folder, reads via each line of each files and calls UCM via its API.

#Step 1: Add table #
In the root directory there is sql folder, run that sql to add the table in the ucm database

#Step 2: Configurations#
in the root directory, there is the configs.json file, set the correct configuration.
//NB, for the upload_path and backup_path should be full path not relative

#Step 3: .sml#
Add .sml file in the "upload" directory specified in the upload_path above

#Step 4: backup folder#
Make the backup folder writable. You can run this command "sudo chmod -R 777 "YOUR BACKUP PATH"

#STEP 5: Tests"
In your terminal, go to tests folder and run "php pollTest.php" or go to the browser path that is  pollTest.php