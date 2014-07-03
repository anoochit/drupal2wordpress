drupal2wordpress
================


 1. Clone repository.

    `git clone https://github.com/anoochit/drupal2wordpress.git`

 2. Install composer and dependency packages.

    `php composer.phar install`

 3. Edit config file `my_data.php` for database connection (drupal database).

    `cp my_data.example.php my_data.php`

 4. Review `header.xml` to change default authors, it fixed value NOT query from database so please [change this line](https://github.com/anoochit/drupal2wordpress/blob/master/header.xml#L38-L54). 

 5. Review `drupal2wordpress.php` with [these line](https://github.com/anoochit/drupal2wordpress/blob/master/drupal2wordpress.php#L106-L110) for uid and author data for each entry

 6. Then open your browser (or wget) to `drupal2wordpress.php` 

