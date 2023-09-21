## Simple API

**What this API is about**:

* It's a +10 Laravel Project & MySQL Database.

* Firstly I've made an authentication system using Sanctum.

* Then, I created tags & posts API resources performing CRUD operations.

* After that,  I scheduled a Job that runs daily and force-deletes all softly-  deleted posts for more than 30 days.
 
* In addition to making another job that runs every six hours and makes HTTP requests to this endpoint (https://randomuser.me/api/) and logs only the results object in the response. 

* Finally I've made /stats API endpoint showing the number of all users, posts, and users with zero posts.
