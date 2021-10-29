Test job created on Laravel

Admin panel where the user can register as a manager. <br />
Once created, it has the ability to create an employee. When creating, you need to provide an email address and password. <br />
Each created employee can enter the admin panel and create an entry there, which has:
title
image
category
<br />
There is a list of categories in the Seeder.<br />
Once created, an employee can see a list of all the records that he has created, but cannot see the records that other employees have created.<br />
There should be 10 entries on the page. Use the paginate method for eloquent for pagination.<br />
The manager can see a list of all the records that his employees have created.<br />
If you go to a record and click on its category, then the employee will see a list of all records in this category that he created, and the manager will see a list of all records in this category that were created by his employees.<br />
Also, the manager on the record page can see the author of the record and, by clicking on him, will see a list of all records of this employee.<br />
The manager can delete any record that his employee has created.<br />
An employee can update and delete any of their records.<br />


please dont forgot to run **php artisan storage:link** this needed for proper shaving of uploaded images

