# BasicApiRest

.htaccess enable rewrite_mod 

The API class assume database tables names are :
	- User: user_id, name, email
	- Task: task_id, user_id, title, description, creation_date, status

The Database class contains hard coded user name and password for the database. 
	Currents are :
		host = localhost
		dbname = test
		db_user = root
		db_pass = root

If you want add tables, you have to add methods(endpoints) in the Api class.

Basics return codes are in RetCode class.

Exemples :

	POST methods :

		Create a user = curl --data "name=alpha&user_id=10&email=beta" HOST/user
		Create a task = curl --data "task_id=2&user_id=6&description=one_desc" HOST/task

	GET methods :

		Get all users = curl HOST/user/
		Get one user with his id = curl HOST/user/1
		Dynamic query database :
			- Find all tasks owned by one user  = curl -G -d "user_id=6" HOST/task
			- Get users by name = curl -G -d "name=realname" HOST/user

	DELETE method :

		Delete is taking only one arguments, the target id, and there is no security check.
		Delete one user = curl -X DELETE HOST/user/12 

	UPDATE method is missing
