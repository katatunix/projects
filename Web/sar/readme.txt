- We need a web server with:
		+ Apache httpd 2.0+
		+ PHP 5.2+
	and a MySQL database server with support of InnoDB engine.

- In your database server:
	+ Create a new database named "sar".
	+ Import the database schema and sample data from the file "sar/config/data.txt".

- Enable the module: rewrite_module of your Apache.
	+ Go to <your Apache folder>\conf, open file httpd.conf by Notepad, go to this line:
		#LoadModule rewrite_module modules/mod_rewrite.so
	+ Remove the # character, then save.
	+ Restart your Apache.

- Go to your www or htdocs folder, copy the "sar" folder into this.

- Open the file sar/config/config.php, go to these lines and config your database server information:
	define('__DB_HOST',					'localhost');
	define('__DB_USERNAME',				'root');
	define('__DB_PASSWORD',				'');
	define('__DB_NAME',					'sar');

- Open your Web Browser, enter http://localhost[:YourPort]/sar/ to see the prototype website for SAR.

- Some accounts that you can use to login to the system:
	admin1			/ 123456
	coordinator1	/ 123456
	lecturer1		/ 123456
	tutor1			/ 123456
	student313		/ 123456
	student199		/ 123456
	
- If you have any trouble, please contact me at nghiabvgt60538@fpt.edu.vn
